<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SmsService;
use Exception;

class SmsController extends Controller
{
    public function index()
    {
        try {
            $smsService = new SmsService();
            $status = $smsService->getServiceStatus();
            $devices = $smsService->getConnectedDevices();
        } catch (Exception $e) {
            // Fallback to basic status
            $status = [
                'semaphore' => [
                    'configured' => !empty(env('SMS_API_KEY')),
                    'api_url' => env('SMS_API_URL', ''),
                    'sender_id' => env('SMS_SENDER_ID', ''),
                    'has_api_key' => !empty(env('SMS_API_KEY'))
                ],
                'android_usb' => [
                    'enabled' => env('SMS_ANDROID_ENABLED', false),
                    'adb_path' => env('SMS_ANDROID_ADB_PATH', 'adb'),
                    'adb_available' => false,
                    'device_connected' => false,
                    'device_id' => env('SMS_ANDROID_DEVICE_ID', 'auto')
                ]
            ];
            $devices = ['devices' => []];
        }
        
        // Legacy settings for backward compatibility
        $currentSettings = [
            'api_url' => env('SMS_API_URL', ''),
            'api_key' => env('SMS_API_KEY', ''),
            'sender_id' => env('SMS_SENDER_ID', ''),
            'is_configured' => !empty(env('SMS_API_KEY'))
        ];
        
        return view('admin.sms.index', compact('currentSettings', 'status', 'devices'));
    }
    
    public function test(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'test_message' => 'required|string|max:160'
        ]);
        
        try {
            $smsService = new SmsService();
            $result = $smsService->testSms($request->phone_number, $request->test_message);
            
            if ($result['success']) {
                $method = $result['method'] ?? 'unknown';
                return back()->with('success', "Test SMS sent successfully via {$method}!");
            } else {
                $error = $result['error'] ?? 'Unknown error';
                return back()->with('error', "Failed to send test SMS: {$error}");
            }
        } catch (Exception $e) {
            return back()->with('error', 'SMS service error: ' . $e->getMessage());
        }
    }

    /**
     * Check Android device status
     */
    public function checkDevices()
    {
        try {
            $smsService = new SmsService();
            $devices = $smsService->getConnectedDevices();
            
            return response()->json([
                'success' => !isset($devices['error']),
                'data' => $devices
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Test ADB command
     */
    public function testAdb()
    {
        try {
            $adbPath = env('SMS_ANDROID_ADB_PATH', 'adb');
            
            // Test if ADB is available
            if ($adbPath === 'adb') {
                exec('where adb 2>nul', $whereOutput, $whereReturn);
                $adbAvailable = $whereReturn === 0;
                $adbLocation = $adbAvailable ? implode(', ', $whereOutput) : 'Not found in PATH';
            } else {
                $adbAvailable = file_exists($adbPath);
                $adbLocation = $adbPath;
            }
            
            // Test ADB devices command
            $command = "\"{$adbPath}\" devices";
            exec($command . ' 2>&1', $output, $returnCode);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'adb_available' => $adbAvailable,
                    'adb_location' => $adbLocation,
                    'command' => $command,
                    'output' => $output,
                    'return_code' => $returnCode,
                    'devices_found' => $this->parseDevicesFromOutput($output)
                ]
            ]);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Parse devices from ADB output
     */
    private function parseDevicesFromOutput($output)
    {
        $devices = [];
        foreach ($output as $line) {
            if (strpos($line, 'device') !== false && strpos($line, 'List of devices') === false) {
                $parts = explode("\t", trim($line));
                if (count($parts) >= 2) {
                    $devices[] = [
                        'id' => trim($parts[0]),
                        'status' => trim($parts[1])
                    ];
                }
            }
        }
        return $devices;
    }
}