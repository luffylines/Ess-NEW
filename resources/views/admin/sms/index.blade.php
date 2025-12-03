@extends('layouts.app')

@section('title', 'SMS Configuration')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">SMS Configuration</h1>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Semaphore API Configuration -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-cloud me-2"></i>
                            Semaphore API (Primary Method)
                        </h3>
                    </div>
                    <div class="card-body">
                        @if(isset($status['semaphore']) && $status['semaphore']['configured'])
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                Semaphore API is configured and ready
                            </div>
                            <ul class="list-unstyled">
                                <li><strong>API URL:</strong> {{ $status['semaphore']['api_url'] ?? 'Not set' }}</li>
                                <li><strong>Sender ID:</strong> {{ $status['semaphore']['sender_id'] ?? 'Not set' }}</li>
                                <li><strong>API Key:</strong> 
                                    @if($status['semaphore']['has_api_key'])
                                        <span class="text-success">✓ Configured</span>
                                    @else
                                        <span class="text-danger">✗ Missing</span>
                                    @endif
                                </li>
                            </ul>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Semaphore API is not properly configured
                            </div>
                            <div class="mt-3">
                                <h5>Setup Instructions:</h5>
                                <ol>
                                    <li>Sign up for a Semaphore account at <a href="https://semaphore.co" target="_blank">semaphore.co</a></li>
                                    <li>Get your API key from the dashboard</li>
                                    <li>Add these settings to your <code>.env</code> file:
                                        <pre class="mt-2 p-2 bg-light">
                                        SMS_API_URL=https://api.semaphore.co/api/v4/messages
                                        SMS_API_KEY=your_api_key_here
                                        SMS_SENDER_ID=YourSenderName</pre>
                                    </li>
                                    <li>Clear config cache: <code>php artisan config:clear</code></li>
                                </ol>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Android USB SMS Configuration -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-mobile-alt me-2"></i>
                            Android USB SMS (Fallback Method)
                        </h3>
                    </div>
                    <div class="card-body">
                        @if(isset($status['android_usb']) && $status['android_usb']['enabled'])
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Android USB SMS is enabled as fallback
                            </div>
                            
                            <ul class="list-unstyled">
                                <li><strong>ADB Path:</strong> {{ $status['android_usb']['adb_path'] ?? 'Not set' }}</li>
                                <li><strong>ADB Available:</strong> 
                                    @if($status['android_usb']['adb_available'] ?? false)
                                        <span class="text-success">✓ Yes</span>
                                    @else
                                        <span class="text-danger">✗ No</span>
                                    @endif
                                </li>
                                <li><strong>Device Connected:</strong> 
                                    @if($status['android_usb']['device_connected'] ?? false)
                                        <span class="text-success">✓ Yes</span>
                                    @else
                                        <span class="text-warning">⚠ No device</span>
                                    @endif
                                </li>
                            </ul>

                            @if(isset($devices['devices']) && count($devices['devices']) > 0)
                                <h6>Connected Devices:</h6>
                                <ul class="list-group list-group-flush">
                                    @foreach($devices['devices'] as $device)
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>{{ $device['id'] }}</span>
                                            <span class="badge bg-success">{{ $device['status'] }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="checkDevices()">
                                <i class="fas fa-sync me-1"></i> Refresh Devices
                            </button>
                            
                            <!-- Troubleshooting section -->
                            @if($status['android_usb']['enabled'] && (!$status['android_usb']['adb_available'] || !$status['android_usb']['device_connected']))
                                <div class="alert alert-warning mt-3">
                                    <h6><i class="fas fa-wrench me-2"></i>Troubleshooting:</h6>
                                    @if(!$status['android_usb']['adb_available'])
                                        <div class="mb-2">
                                            <strong>ADB not found:</strong>
                                            <ul class="mb-2">
                                                <li>Download Android Platform Tools from <a href="https://developer.android.com/studio/releases/platform-tools" target="_blank">here</a></li>
                                                <li>Extract to <code>C:\platform-tools\</code></li>
                                                <li>Update .env: <code>SMS_ANDROID_ADB_PATH=C:\platform-tools\adb.exe</code></li>
                                                <li>Or add ADB to Windows PATH environment variable</li>
                                            </ul>
                                        </div>
                                    @endif
                                    @if($status['android_usb']['adb_available'] && !$status['android_usb']['device_connected'])
                                        <div class="mb-2">
                                            <strong>Device not detected:</strong>
                                            <ul class="mb-2">
                                                <li>Make sure phone is connected via USB</li>
                                                <li>Enable Developer Options: Settings → About Phone → Tap Build Number 7 times</li>
                                                <li>Enable USB Debugging: Settings → Developer Options → USB Debugging</li>
                                                <li>Allow USB debugging when prompted on phone</li>
                                                <li>Try different USB cable or port</li>
                                                <li>Install phone drivers if needed</li>
                                            </ul>
                                            <button type="button" class="btn btn-sm btn-outline-info" onclick="testAdbCommand()">
                                                <i class="fas fa-terminal me-1"></i> Test ADB Connection
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Test SMS Section -->
        <div class="row mt-4">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-paper-plane me-2"></i>
                            Test SMS Functionality
                        </h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.sms.test') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone_number" class="form-label">Phone Number</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="phone_number" 
                                               name="phone_number" 
                                               placeholder="09XXXXXXXXX" 
                                               required>
                                        <small class="form-text text-muted">
                                            Enter Philippine mobile number (e.g., 09171234567)
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="test_message" class="form-label">Test Message</label>
                                        <textarea class="form-control sms-placeholder" 
                                                  id="test_message" 
                                                  name="test_message" 
                                                  rows="3" 
                                                  maxlength="160" 
                                                  placeholder="Test message from ESS system">Test SMS from Place of Beauty ESS. This system can send schedule notifications automatically!</textarea>
                                        <small class="form-text">
                                            Maximum 160 characters for SMS
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Send Test SMS
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- SMS Flow Info -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle me-2"></i>
                            How SMS Notifications Work
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Automatic SMS Triggers:</h5>
                                <ul>
                                    <li><strong>Single Schedule:</strong> When a manager creates a schedule for an employee</li>
                                    <li><strong>Bulk Assignment:</strong> When multiple schedules are assigned at once (combined in one message)</li>
                                </ul>
                                
                                <h5 class="mt-3">SMS Methods Priority:</h5>
                                <ol>
                                    <li><strong>Semaphore API:</strong> Tries first (reliable, cloud-based)</li>
                                    <li><strong>Android USB:</strong> Fallback option (uses connected phone's SIM)</li>
                                </ol>
                            </div>
                            <div class="col-md-6">
                                <h5>Sample Bulk Message:</h5>
                                <div class="alert alert-light">
                                    <small>
                                        Hello Juan Dela Cruz! You have been assigned new work schedules:<br><br>
                                        1. Dec 3, 2025 (Tue) | 8:00 AM-5:00 PM | Main Store<br>
                                        2. Dec 4, 2025 (Wed) | 9:00 AM-6:00 PM | Branch 2<br>
                                        3. Dec 5, 2025 (Thu) | 8:00 AM-5:00 PM | Main Store<br><br>
                                        Thank you!
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    pre {
        white-space: pre-wrap;       /* CSS3 */
        white-space: -moz-pre-wrap;  /* Firefox */
        white-space: -pre-wrap;      /* Opera <7 */
        white-space: -o-pre-wrap;    /* Opera 7 */
        word-wrap: break-word;       /* IE */
        }
</style>
<script>
function checkDevices() {
    fetch('{{ route("admin.sms.check-devices") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to check devices: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to check devices');
        });
}

function testAdbCommand() {
    fetch('{{ route("admin.sms.test-adb") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const result = data.data;
                let message = 'ADB Test Results:\n\n';
                message += `ADB Available: ${result.adb_available ? 'Yes' : 'No'}\n`;
                message += `ADB Location: ${result.adb_location}\n`;
                message += `Command: ${result.command}\n`;
                message += `Return Code: ${result.return_code}\n\n`;
                message += 'Output:\n' + result.output.join('\n') + '\n\n';
                message += `Devices Found: ${result.devices_found.length}\n`;
                
                if (result.devices_found.length > 0) {
                    message += 'Device List:\n';
                    result.devices_found.forEach(device => {
                        message += `- ${device.id} (${device.status})\n`;
                    });
                }
                
                alert(message);
            } else {
                alert('ADB Test Failed: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to test ADB: ' + error.message);
        });
}
</script>
@endsection