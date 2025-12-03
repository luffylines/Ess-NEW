<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class SmsService
{
    private $apiUrl;
    private $apiKey;
    private $senderId;
    private $androidEnabled;
    private $adbPath;
    private $deviceId;

    public function __construct()
    {
        $this->apiUrl = env('SMS_API_URL');
        $this->apiKey = env('SMS_API_KEY');
        $this->senderId = env('SMS_SENDER_ID');
        $this->androidEnabled = env('SMS_ANDROID_ENABLED', false);
        $this->adbPath = env('SMS_ANDROID_ADB_PATH', 'adb');
        $this->deviceId = env('SMS_ANDROID_DEVICE_ID', 'auto');
    }

    /**
     * Send SMS notification for a single schedule
     */
    public function sendScheduleNotification($phoneNumber, $employeeName, $schedule)
    {
        $message = $this->formatScheduleMessage($employeeName, [$schedule]);
        return $this->sendSms($phoneNumber, $message);
    }

    /**
     * Send bulk SMS notifications with combined schedules per employee
     */
    public function sendBulkScheduleNotifications($scheduleData)
    {
        $results = [];
        
        // Group schedules by employee
        $groupedSchedules = collect($scheduleData)->groupBy('employee_phone');
        
        foreach ($groupedSchedules as $phoneNumber => $employeeSchedules) {
            if (empty($phoneNumber)) {
                continue;
            }
            
            $employeeName = $employeeSchedules->first()['name'] ?? 'Employee';
            $schedules = $employeeSchedules->toArray();
            
            // Send one combined message with all schedules
            $message = $this->formatBulkScheduleMessage($employeeName, $schedules);
            $result = $this->sendSms($phoneNumber, $message);
            
            $results[] = [
                'phone' => $phoneNumber,
                'employee' => $employeeName,
                'schedule_count' => count($schedules),
                'success' => $result['success'] ?? false,
                'method' => $result['method'] ?? 'unknown'
            ];
        }
        
        return $results;
    }

    /**
     * Send SMS using primary (Semaphore) or fallback (Android USB) method
     */
    private function sendSms($phoneNumber, $message)
    {
        $cleanPhone = $this->cleanPhoneNumber($phoneNumber);
        
        if (empty($cleanPhone)) {
            Log::error('SMS: Invalid phone number', ['phone' => $phoneNumber]);
            return ['success' => false, 'error' => 'Invalid phone number'];
        }

        // Try Semaphore API first
        $semaphoreResult = $this->sendViaSemaphore($cleanPhone, $message);
        if ($semaphoreResult['success']) {
            return array_merge($semaphoreResult, ['method' => 'semaphore']);
        }

        // Fallback to Android USB SMS if enabled
        if ($this->androidEnabled) {
            Log::info('SMS: Semaphore failed, trying Android USB fallback', [
                'phone' => $cleanPhone,
                'semaphore_error' => $semaphoreResult['error'] ?? 'Unknown error'
            ]);
            
            $androidResult = $this->sendViaAndroid($cleanPhone, $message);
            return array_merge($androidResult, ['method' => 'android_usb']);
        }

        // Both methods failed
        Log::error('SMS: All sending methods failed', [
            'phone' => $cleanPhone,
            'semaphore_error' => $semaphoreResult['error'] ?? 'Unknown error'
        ]);

        return ['success' => false, 'error' => 'All SMS methods failed'];
    }

    /**
     * Send SMS via Semaphore API
     */
    private function sendViaSemaphore($phoneNumber, $message)
    {
        if (empty($this->apiKey) || empty($this->apiUrl)) {
            return ['success' => false, 'error' => 'Semaphore API not configured'];
        }

        try {
            $response = Http::timeout(30)->post($this->apiUrl, [
                'apikey' => $this->apiKey,
                'number' => $phoneNumber,
                'message' => $message,
                'sendername' => $this->senderId
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                
                if (isset($responseData[0]['status']) && $responseData[0]['status'] === 'Queued') {
                    Log::info('SMS sent successfully via Semaphore', [
                        'phone' => $phoneNumber,
                        'message_id' => $responseData[0]['message_id'] ?? 'unknown'
                    ]);
                    return ['success' => true, 'response' => $responseData];
                } else {
                    $error = $responseData[0]['message'] ?? 'Unknown API error';
                    Log::error('Semaphore API error', ['phone' => $phoneNumber, 'error' => $error]);
                    return ['success' => false, 'error' => $error];
                }
            } else {
                $error = 'HTTP ' . $response->status() . ': ' . $response->body();
                Log::error('Semaphore HTTP error', ['phone' => $phoneNumber, 'error' => $error]);
                return ['success' => false, 'error' => $error];
            }

        } catch (Exception $e) {
            Log::error('Semaphore SMS exception', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage()
            ]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Send SMS via Android phone connected via USB (ADB)
     */
private function sendViaAndroid($phoneNumber, $message)
{
    try {
        Log::info('Android SMS: Starting attempt', ['phone' => $phoneNumber]);
        
        if (!$this->isAdbAvailable()) {
            return ['success' => false, 'error' => 'ADB not available at: ' . $this->adbPath];
        }

        if (!$this->isDeviceConnected()) {
            return ['success' => false, 'error' => 'No Android device connected via USB'];
        }

        // Convert +63XXXXXXXXX -> 09XXXXXXXXX for local SMS apps
        $localPhone = $phoneNumber;
        if (strpos($phoneNumber, '+63') === 0) {
            $localPhone = '0' . substr($phoneNumber, 3);
        }

        // Clean message
        $cleanMessage = preg_replace('/[^\p{L}\p{N}\s\.\!\?\,\-\:]/u', '', $message);
        
        // Replace spaces
        $cleanMessage = str_replace(' ', '%s', $cleanMessage);

        // Split long message
        $chunks = str_split($cleanMessage, 100);

        $deviceParam = $this->deviceId === 'auto' ? '' : "-s {$this->deviceId}";

        $commands = [];

        // Open SMS intent
        $commands[] = "\"{$this->adbPath}\" $deviceParam shell am start -a android.intent.action.SENDTO -d smsto:$localPhone";

        // Wait for app to open
        $commands[] = "timeout 2";
        
        // Force tap EXACT message input box (Vivo 1723)
        $commands[] = "\"{$this->adbPath}\" $deviceParam shell input tap 480 1890";
        $commands[] = "sleep 1";
        // Type message chunks
        foreach ($chunks as $chunk) {
            $commands[] = "\"{$this->adbPath}\" $deviceParam shell input text \"$chunk\"";
            $commands[] = "sleep 1";
        }

        // Ensure keyboard is visible, in case it's hidden
        $commands[] = "\"{$this->adbPath}\" $deviceParam shell input keyevent 61"; // TAB key (sometimes forces focus)
        $commands[] = "timeout 1";
        // ==========================================================
        // AUTO SEND-BUTTON TAPPING (SAFE VERSION)
        // ==========================================================

        // Coordinates for send button (Vivo 1723)
        $sendX = 1000;
        $sendY = 2200;
       
        // Tap send button
        $commands[] = "\"{$this->adbPath}\" $deviceParam shell input tap $sendX $sendY";


        // ==========================================================

        $allOutput = [];
        $success = true;

        foreach ($commands as $i => $command) {

            if (strpos($command, 'timeout') !== false) {
                sleep((strpos($command, '2') !== false) ? 2 : 1);
                continue;
            }

            $output = [];
            $returnCode = 0;
            exec($command . ' 2>&1', $output, $returnCode);

            $allOutput[] = "Step " . ($i + 1) . ": " . implode(' ', $output);

            if ($returnCode !== 0 && $i === 0) {
                $success = false;
                break;
            }

            usleep(250000);
        }

        Log::info('Android SMS: Command sequence result', [
            'success' => $success,
            'output' => $allOutput
        ]);

        if ($success) {
            return [
                'success' => true,
                'output' => $allOutput,
                'note' => 'SMS sent automatically via Android ADB with auto screen-tap.'
            ];
        }

        return [
            'success' => false,
            'error' => 'Failed to send SMS: ' . implode(' | ', $allOutput)
        ];

    } catch (Exception $e) {
        Log::error('Android SMS exception', ['error' => $e->getMessage()]);
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

    /**
     * Check if ADB is available
     */
    private function isAdbAvailable()
    {
        if ($this->adbPath === 'adb') {
            // Check if adb is in PATH
            exec('where adb 2>nul', $output, $returnCode);
            Log::info('ADB PATH check', ['output' => $output, 'return_code' => $returnCode]);
            return $returnCode === 0;
        } else {
            // Check specific path
            $exists = file_exists($this->adbPath);
            Log::info('ADB file check', ['path' => $this->adbPath, 'exists' => $exists]);
            return $exists;
        }
    }

    /**
     * Check if Android device is connected
     */
    private function isDeviceConnected()
    {
        $command = "\"{$this->adbPath}\" devices";
        exec($command, $output, $returnCode);
        
        if ($returnCode !== 0) {
            return false;
        }

        // Parse output to check for connected devices
        foreach ($output as $line) {
            if (strpos($line, 'device') !== false && strpos($line, 'List of devices') === false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Format message for a single schedule
     */
    private function formatScheduleMessage($employeeName, $schedules)
    {
        $schedule = $schedules[0];
        $date = \Carbon\Carbon::parse($schedule['date'])->format('M j, Y (D)');
        $startTime = \Carbon\Carbon::parse($schedule['start_time'])->format('g:i A');
        $endTime = \Carbon\Carbon::parse($schedule['end_time'])->format('g:i A');
        $location = $schedule['store_name'] ?? 'Office';

        return "Hello {$employeeName}! You have been assigned a new work schedule: {$date} from {$startTime} to {$endTime} at {$location}. Thank you!";
    }

    /**
     * Format message for bulk schedules (combined in one message)
     */
        private function formatBulkScheduleMessage($employeeName, $schedules)
        {
            $message = "Hello {$employeeName}! You have been assigned new work schedules: ";
            
            $totalSchedules = count($schedules);
            
            // Loop through the schedules and format each one
            foreach ($schedules as $index => $schedule) {
                $date = \Carbon\Carbon::parse($schedule['date'])->format('M j, Y (D)');
                $startTime = \Carbon\Carbon::parse($schedule['start_time'])->format('g:i A');
                $endTime = \Carbon\Carbon::parse($schedule['end_time'])->format('g:i A');
                
                // Format each schedule
                $message .= ($index + 1) . ". {$date} | {$startTime} - {$endTime} ";
            }

            // Add store name at the end after the schedules
            $storeName = $schedules[0]['store_name'] ?? 'Office';
            $message .= " Store: {$storeName} ";

            // Final message
            $message .= " Thank you!";

            return $message;
        }

    /**
     * Clean and format Philippine phone number
     */
    private function cleanPhoneNumber($phoneNumber)
    {
        if (empty($phoneNumber)) {
            return null;
        }

        // Remove all non-numeric characters
        $cleaned = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Handle different Philippine number formats
        if (strlen($cleaned) === 11 && substr($cleaned, 0, 2) === '09') {
            // 09XXXXXXXXX -> +639XXXXXXXXX
            return '+63' . substr($cleaned, 1);
        } elseif (strlen($cleaned) === 10 && substr($cleaned, 0, 1) === '9') {
            // 9XXXXXXXXX -> +639XXXXXXXXX
            return '+63' . $cleaned;
        } elseif (strlen($cleaned) === 12 && substr($cleaned, 0, 2) === '63') {
            // 639XXXXXXXXX -> +639XXXXXXXXX
            return '+' . $cleaned;
        } elseif (strlen($cleaned) === 13 && substr($cleaned, 0, 3) === '639') {
            // Already in correct format, ensure + prefix
            return '+' . $cleaned;
        }

        // If none of the above formats match, return null
        return null;
    }

    /**
     * Test SMS functionality
     */
    public function testSms($phoneNumber, $customMessage = null)
    {
        $message = $customMessage ?: "Test message from Place of Beauty ESS system. SMS is working correctly!";
        return $this->sendSms($phoneNumber, $message);
    }

    /**
     * Get SMS service status
     */
    public function getServiceStatus()
    {
        return [
            'semaphore' => [
                'configured' => !empty($this->apiKey) && !empty($this->apiUrl),
                'api_url' => $this->apiUrl,
                'sender_id' => $this->senderId,
                'has_api_key' => !empty($this->apiKey)
            ],
            'android_usb' => [
                'enabled' => $this->androidEnabled,
                'adb_path' => $this->adbPath,
                'adb_available' => $this->isAdbAvailable(),
                'device_connected' => $this->isDeviceConnected(),
                'device_id' => $this->deviceId
            ]
        ];
    }

    /**
     * Get connected Android devices
     */
    public function getConnectedDevices()
    {
        if (!$this->isAdbAvailable()) {
            return ['error' => 'ADB not available'];
        }

        $command = "\"{$this->adbPath}\" devices";
        exec($command, $output, $returnCode);
        
        if ($returnCode !== 0) {
            return ['error' => 'Failed to execute ADB command'];
        }

        $devices = [];
        foreach ($output as $line) {
            if (strpos($line, 'device') !== false && strpos($line, 'List of devices') === false) {
                $parts = explode("\t", $line);
                if (count($parts) >= 2) {
                    $devices[] = [
                        'id' => trim($parts[0]),
                        'status' => trim($parts[1])
                    ];
                }
            }
        }

        return ['devices' => $devices];
    }
}