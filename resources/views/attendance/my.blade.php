<x-app-layout>
    <div class="px-4">
        <h1 class="mb-3">My Attendance</h1>
            <div class="text-end">
               <small class="">{{ \Carbon\Carbon::now()->format('l, F j, Y') }}</small>
            </div>
        @include('partials.flash-messages')
        
        {{-- Mobile-Specific Styles --}}
        <style>
            /* Mobile-specific optimizations */
            .mobile-device .btn-lg {
                padding: 1rem 2rem;
                font-size: 1.1rem;
                min-height: 60px;
            }
            
            .mobile-device .card {
                border-radius: 15px;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            }
            
            .mobile-device .display-4 {
                font-size: 2.5rem;
            }
            
            /* Better touch targets for mobile */
            .mobile-device button {
                min-height: 48px;
                -webkit-tap-highlight-color: transparent;
            }
            
            /* Prevent zoom on form focus for iOS */
            @media screen and (-webkit-min-device-pixel-ratio: 0) {
                .mobile-device input, .mobile-device select, .mobile-device textarea {
                    font-size: 16px !important;
                }
            }
            
            /* Enhanced mobile form styling */
            .mobile-device .form-control {
                font-size: 16px;
                padding: 0.75rem;
            }
            
            /* Real-time clock styling */
            #currentTime {
                font-weight: bold;
                color: #007bff;
                font-family: 'Courier New', monospace;
            }
            
            /* Loading state for buttons */
            .btn-loading {
                position: relative;
            }
            
            .btn-loading .fa-spinner {
                animation: spin 1s linear infinite;
            }
            
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            
            /* Mobile alert positioning */
            @media (max-width: 768px) {
                .alert {
                    margin: 0.5rem;
                    font-size: 0.9rem;
                }
                
                .fixed-top-alert {
                    position: fixed !important;
                    top: 280px !important;  /* Much lower - below header, employee name, title, date, and card header */
                    left: 10px !important;
                    right: 10px !important;
                    transform: none !important;
                    z-index: 9999 !important;
                    max-width: none !important;
                    width: auto !important;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.3) !important; /* More prominent shadow */
                }
                
                .fixed-top-alert .btn-sm {
                    padding: 0.25rem 0.5rem;
                    font-size: 0.8rem;
                }
            }
            
            /* Desktop alert positioning */
            @media (min-width: 769px) {
                .fixed-top-alert {
                    position: fixed !important;
                    top: 70px !important; /* Much lower - below navbar, employee name, title, date, and card header */
                    right: 20% !important;
                    transform: translateX(40%) !important;
                    z-index: 9999 !important;
                    max-width: 600px !important;
                    width: 90% !important;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.25) !important;
                }
            }
            
            /* Location status indicator */
            #locationStatus {
                min-height: 20px;
                transition: all 0.3s ease;
            }
            
            /* Enhanced button styles for help actions */
            .btn-outline-primary:hover, .btn-outline-info:hover {
                transform: translateY(-1px);
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
        </style>
        {{-- === MARK ATTENDANCE === --}}
        <div class="card shadow-sm mb-4 mx-auto" style="max-width: 600px;">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-clock me-2"></i>Mark Attendance</h4>
            </div>
            <div class="card-body">
                {{-- System Status Check --}}
                @if(isset($systemStatus) && !$systemStatus['available'])
                    <div class="text-center">
                        <div class="mb-3">
                            @if(str_contains($systemStatus['error'], 'not configured'))
                                <i class="fas fa-cogs display-4 text-warning mb-3"></i>
                                <h5 class="text-warning">System Not Configured</h5>
                                <div class="alert alert-warning" role="alert">
                                    <i class="fas fa-wrench me-2"></i>
                                    <strong>{{ $systemStatus['error'] }}</strong><br>
                                    <small>{{ $systemStatus['details'] }}</small>
                                </div>
                                <p class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    The attendance system needs to be set up by an administrator.
                                </p>
                            @else
                                <i class="fas fa-ban display-4 text-danger mb-3"></i>
                                <h5 class="text-danger">System Temporarily Disabled</h5>
                                <div class="alert alert-danger" role="alert">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>{{ $systemStatus['error'] }}</strong><br>
                                    <small>{{ $systemStatus['details'] }}</small>
                                </div>
                                <p class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Please contact your administrator to reactivate the attendance system.
                                </p>
                            @endif
                        </div>
                    </div>
                @elseif($todayAttendance && $todayAttendance->time_in && $todayAttendance->time_out)
                    <div class="text-center">
                        <p class="text-success fw-semibold mb-3">
                            <i class="fas fa-check-circle me-2"></i>You have completed attendance for today.
                        </p>
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-end">
                                    <h6 class="text-success">{{ $todayAttendance->total_hours ?? '0.00' }}</h6>
                                    <small class="text-muted">Working Hours</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h6 class="text-success">₱{{ number_format($todayAttendance->earned_amount ?? 0, 2) }}</h6>
                                <small class="text-muted">Earned Amount</small>
                            </div>
                        </div>
                    </div>
                @elseif(!isset($systemStatus) || !$systemStatus['available'])
                    {{-- System disabled - form already handled above --}}
                @else
                    
                    <form method="POST" action="{{ route('attendance.submit') }}" id="attendanceMarkForm" novalidate>
                        @csrf
                        
                        {{-- Time In --}}
                        @if(!$todayAttendance || !$todayAttendance->time_in)
                            <div class="text-center">
                                <div class="mb-3">
                                    <i class="fas fa-clock display-4 text-primary mb-3"></i>
                                    <h5>Ready to start your workday?</h5>
                                    <p class="text-muted">Current time: <span id="currentTime"></span></p>
                                    <div id="locationStatus" class="mb-2"></div>
                                    <div class="text-center mt-2">
                                        <small class="text-info">
                                            <i class="fas fa-info-circle me-1"></i>
                                            <strong>Two ways to mark attendance:</strong><br>
                                            📶 Connect to Store WiFi (instant) OR 📍 Use GPS within 50m radius
                                        </small>
                                    </div>
                                </div>
                                <button type="submit" name="action" value="time_in" class="btn btn-success btn-lg w-100" id="btnTimeIn">
                                    <i class="fas fa-play me-2"></i>Mark Time In
                                </button>
                                <small class="text-muted d-block mt-2">Standard work start time: 8:00 AM</small>
                            </div>
                        
                        {{-- Break Time In --}}
                        @elseif($todayAttendance && $todayAttendance->time_in && !$todayAttendance->breaktime_in)
                            <div class="text-center">
                                <div class="mb-3">
                                    <i class="fas fa-check-circle text-success display-4 mb-3"></i>
                                    <h5>Work session started</h5>
                                    <p class="text-success mb-1">
                                        <i class="fas fa-clock me-1"></i>Time In: {{ $todayAttendance->time_in->format('h:i A') }}
                                    </p>
                                    <p class="text-muted">Current time: <span id="currentTime"></span></p>
                                    <div id="locationStatus" class="mb-2"></div>
                                    <div class="text-center mt-2">
                                        <small class="text-info">
                                            <i class="fas fa-info-circle me-1"></i>
                                            System is working properly - No errors detected
                                        </small>
                                    </div>
                                </div>
                                <button type="submit" name="action" value="break_in" class="btn btn-warning btn-lg w-100" id="btnBreakIn">
                                    <i class="fas fa-coffee me-2"></i>Start Lunch Break
                                </button>
                                <small class="text-muted d-block mt-2">Lunch break start (default: 12:00 PM)</small>
                            </div>
                        
                        {{-- Break Time Out --}}
                        @elseif($todayAttendance && $todayAttendance->breaktime_in && !$todayAttendance->breaktime_out)
                            <div class="text-center">
                                <div class="mb-3">
                                    <i class="fas fa-coffee text-warning display-4 mb-3"></i>
                                    <h5>On lunch break</h5>
                                    <p class="text-success mb-1">
                                        <i class="fas fa-clock me-1"></i>Time In: {{ $todayAttendance->time_in->format('h:i A') }}
                                    </p>
                                    <p class="text-warning mb-1">
                                        <i class="fas fa-coffee me-1"></i>Break In: {{ $todayAttendance && $todayAttendance->breaktime_in ? $todayAttendance->breaktime_in->format('h:i A') : 'Not recorded' }}
                                    </p>
                                    <p class="text-muted">Current time: <span id="currentTime"></span></p>
                                    <div id="locationStatus" class="mb-2"></div>
                                    <div class="text-center mt-2">
                                        <small class="text-info">
                                            <i class="fas fa-info-circle me-1"></i>
                                            System is working properly - No errors detected
                                        </small>
                                    </div>
                                </div>
                                <button type="submit" name="action" value="break_out" class="btn btn-info btn-lg w-100" id="btnBreakOut">
                                    <i class="fas fa-utensils me-2"></i>End Lunch Break
                                </button>
                                <small class="text-muted d-block mt-2">Lunch break end (default: 1:00 PM)</small>
                            </div>
                        
                        {{-- Time Out --}}
                        @elseif($todayAttendance && $todayAttendance->breaktime_out && !$todayAttendance->time_out)
                            <div class="text-center">
                                <div class="mb-3">
                                    <i class="fas fa-briefcase text-primary display-4 mb-3"></i>
                                    <h5>Back to work</h5>
                                    <p class="text-success mb-1">
                                        <i class="fas fa-clock me-1"></i>Time In: {{ $todayAttendance->time_in->format('h:i A') }}
                                    </p>
                                    <p class="text-info mb-1">
                                        <i class="fas fa-coffee me-1"></i>Break: {{ $todayAttendance->breaktime_in ? $todayAttendance->breaktime_in->format('h:i A') : '--' }} - {{ $todayAttendance->breaktime_out ? $todayAttendance->breaktime_out->format('h:i A') : '--' }}
                                    </p>
                                    <p class="text-muted">Current time: <span id="currentTime"></span></p>
                                </div>
                                <button type="submit" name="action" value="time_out" class="btn btn-danger btn-lg w-100" id="btnTimeOut">
                                    <i class="fas fa-stop me-2"></i>Mark Time Out
                                </button>
                              </div>
                        @endif
                    </form>
                @endif
            </div>
        </div>
        {{-- Enhanced Mobile-Friendly Geofencing Script --}}
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('attendanceMarkForm');
            
            // Update current time every second
            function updateTime() {
                const now = new Date();
                const timeString = now.toLocaleTimeString('en-US', {
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                });
                const timeElement = document.getElementById('currentTime');
                if (timeElement) {
                    timeElement.textContent = timeString;
                }
            }
            
            updateTime();
            setInterval(updateTime, 1000);

            // Auto-detect location on page load for better user experience
            detectLocationOnLoad();

            if (!form) return;

            let isSubmitting = false;

            async function checkLocationAndSubmit(actionValue) {
                // Prevent double submission
                if (isSubmitting) {
                    return false;
                }
                isSubmitting = true;

                const btn = document.querySelector(`button[name=\"action\"][value=\"${actionValue}\"]`);
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
                }

                try {
                    // 1) Try network-based allowance first (no GPS required)
                    const resNet = await fetch('/api/attendance/check-location', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({})
                    });
                    
                    if (!resNet.ok) {
                        throw new Error('Network check failed');
                    }
                    
                    const dataNet = await resNet.json();
                    if (dataNet.allowed) {
                        // Create and submit form properly for mobile
                        const hiddenAction = document.createElement('input');
                        hiddenAction.type = 'hidden';
                        hiddenAction.name = 'action';
                        hiddenAction.value = actionValue;
                        form.appendChild(hiddenAction);
                        
                        // For mobile compatibility, use the form submit method
                        form.submit();
                        return true;
                    }

                    // Check if system is disabled due to configuration
                    if (dataNet.error) {
                        showError(dataNet.error);
                        return false;
                    }

                    // 2) If not same network, request GPS and validate radius
                    if (!('geolocation' in navigator)) {
                        showError('Your device does not support GPS. Please connect to Store Wi‑Fi or enable location.');
                        return false;
                    }

                    const position = await new Promise((resolve, reject) => {
                        navigator.geolocation.getCurrentPosition(resolve, reject, {
                            enableHighAccuracy: true,
                            timeout: 15000, // Increased timeout for mobile
                            maximumAge: 60000 // Allow cached location up to 1 minute
                        });
                    });

                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    const res = await fetch('/api/attendance/check-location', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ lat, lng })
                    });
                    
                    if (!res.ok) {
                        throw new Error('Location check failed');
                    }
                    
                    const data = await res.json();
                    if (data.allowed) {
                        const hiddenAction = document.createElement('input');
                        hiddenAction.type = 'hidden';
                        hiddenAction.name = 'action';
                        hiddenAction.value = actionValue;
                        form.appendChild(hiddenAction);
                        form.submit();
                        return true;
                    } else {
                        // Check if it's a system configuration error
                        if (data.error) {
                            showError(data.error);
                        } else {
                            const dist = data.distance_m ?? 'unknown';
                            showError(`You are too far from the store. Distance: ${dist}m (limit: ${data.radius_m}m).`);
                        }
                        return false;
                    }
                } catch (err) {
                    console.error('Geolocation error:', err);
                    let msg = '';
                    let solution = '';
                    
                    if (err && err.code === 1) {
                        msg = '📍 Location access denied.';
                        solution = '✅ Solutions: (1) Allow location in browser settings, or (2) Connect to Store Wi-Fi';
                    } else if (err && err.code === 2) {
                        msg = '🛰️ GPS signal not available.';
                        solution = '✅ Solutions: (1) Go outside for better GPS signal, or (2) Connect to Store Wi-Fi';
                    } else if (err && err.code === 3) {
                        msg = '⏰ Location detection timed out.';
                        solution = '✅ Solutions: (1) Try again in a moment, or (2) Connect to Store Wi-Fi';
                    } else {
                        msg = '📱 Location services unavailable.';
                        solution = '✅ Solutions: (1) Enable GPS in device settings, or (2) Connect to Store Wi-Fi';
                    }
                    
                    showDetailedError(msg, solution);
                    return false;
                } finally {
                    isSubmitting = false;
                    if (btn) {
                        btn.disabled = false;
                        // Restore original button text
                        const originalText = btn.getAttribute('data-original-text');
                        if (originalText) {
                            btn.innerHTML = originalText;
                        } else {
                            // Fallback restoration
                            const action = btn.value;
                            switch(action) {
                                case 'time_in':
                                    btn.innerHTML = '<i class="fas fa-play me-2"></i>Mark Time In';
                                    break;
                                case 'break_in':
                                    btn.innerHTML = '<i class="fas fa-coffee me-2"></i>Start Lunch Break';
                                    break;
                                case 'break_out':
                                    btn.innerHTML = '<i class="fas fa-utensils me-2"></i>End Lunch Break';
                                    break;
                                case 'time_out':
                                    btn.innerHTML = '<i class="fas fa-stop me-2"></i>Mark Time Out';
                                    break;
                            }
                        }
                    }
                }
            }

            // Enhanced error display for mobile with detailed solutions
            function showDetailedError(message, solution) {
                const alertHtml = `
                    <div class="alert alert-warning alert-dismissible fade show auto-hide-alert fixed-top-alert" role="alert" style="font-size: 0.95rem; border-left: 5px solid #ff6b35;">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-map-marker-alt me-2 mt-1" style="color: #e74c3c; font-size: 1.2rem;"></i>
                            <div class="flex-grow-1">
                                <strong style="font-size: 1rem;">${message}</strong><br>
                                <small class="text-muted" style="font-size: 0.85rem;">${solution}</small><br>
                                <div class="mt-2">
                                    <button class="btn btn-sm btn-outline-primary me-2" onclick="requestLocationAgain()" style="font-weight: 600;">
                                        <i class="fas fa-location-arrow me-1"></i>Try GPS Again
                                    </button>
                                    <button class="btn btn-sm btn-outline-info" onclick="showWiFiHelp()" style="font-weight: 600;">
                                        <i class="fas fa-wifi me-1"></i>WiFi Help
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
                
                // Remove existing alerts
                const existingAlerts = document.querySelectorAll('.auto-hide-alert');
                existingAlerts.forEach(alert => alert.remove());
                
                // Add new alert
                document.body.insertAdjacentHTML('afterbegin', alertHtml);
                
                // Auto-hide after 12 seconds (longer duration)
                setTimeout(() => {
                    const alert = document.querySelector('.auto-hide-alert');
                    if (alert) {
                        const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                        bsAlert.close();
                    }
                }, 12000);
            }

            // Simple error for system configuration issues
            function showError(message) {
                const alertHtml = `
                    <div class="alert alert-danger alert-dismissible fade show auto-hide-alert fixed-top-alert" role="alert" style="font-size: 0.95rem; border-left: 5px solid #dc3545;">
                        <i class="fas fa-exclamation-triangle me-10" style="font-size: 1.2rem;"></i>
                        <strong style="font-size: 1rem;">System Error!</strong> <span style="font-size: 0.9rem;">${message}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
                
                const existingAlerts = document.querySelectorAll('.auto-hide-alert');
                existingAlerts.forEach(alert => alert.remove());
                document.body.insertAdjacentHTML('afterbegin', alertHtml);
                
                // Auto-hide after 10 seconds (longer duration)
                setTimeout(() => {
                    const alert = document.querySelector('.auto-hide-alert');
                    if (alert) {
                        const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                        bsAlert.close();
                    }
                }, 10000);
            }

            // Store original button text and Hook buttons to geofence check
            ['time_in','break_in','break_out','time_out'].forEach(action => {
                const button = document.querySelector(`button[name="action"][value="${action}"]`);
                if (button) {
                    // Store original text
                    button.setAttribute('data-original-text', button.innerHTML);
                    
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        checkLocationAndSubmit(action);
                    });
                }
            });

            // Auto-detect location on page load for better UX
            function detectLocationOnLoad() {
                if ('geolocation' in navigator) {
                    // Show a subtle notification that we're checking location
                    const locationStatus = document.getElementById('locationStatus');
                    if (locationStatus) {
                        locationStatus.innerHTML = '<small class="text-info"><i class="fas fa-spinner fa-spin me-1"></i>Detecting your location...</small>';
                    }
                    
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            const accuracy = position.coords.accuracy;
                            
                            console.log(`GPS detected: ${lat}, ${lng} (accuracy: ${accuracy}m)`);
                            
                            // Check if within range immediately
                            checkLocationStatus(lat, lng);
                        },
                        function(error) {
                            console.log('Location detection failed:', error);
                            if (locationStatus) {
                                if (error.code === 1) {
                                    locationStatus.innerHTML = '<small class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i>Location access needed - Please allow location for GPS attendance</small>';
                                } else {
                                    locationStatus.innerHTML = '<small class="text-muted"><i class="fas fa-wifi me-1"></i>Connect to Store WiFi for easy attendance marking</small>';
                                }
                            }
                        },
                        {
                            enableHighAccuracy: true,  // Better accuracy for you
                            timeout: 8000,             // Longer timeout
                            maximumAge: 60000          // 1 minute cache
                        }
                    );
                } else {
                    const locationStatus = document.getElementById('locationStatus');
                    if (locationStatus) {
                        locationStatus.innerHTML = '<small class="text-muted"><i class="fas fa-wifi me-1"></i>GPS not supported - Use Store WiFi for attendance</small>';
                    }
                }
            }

            // Check location status and show helpful info
            async function checkLocationStatus(lat, lng) {
                try {
                    const res = await fetch('/api/attendance/check-location', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ lat, lng })
                    });
                    
                    const data = await res.json();
                    const locationStatus = document.getElementById('locationStatus');
                    
                    if (data.allowed) {
                        if (locationStatus) {
                            if (data.network && data.network.allowed_network) {
                                locationStatus.innerHTML = '<small class="text-success"><i class="fas fa-wifi me-1"></i>✅ Connected to Store WiFi - Ready to mark attendance!</small>';
                            } else {
                                const distance = data.distance_m ? Math.round(data.distance_m) : 0;
                                locationStatus.innerHTML = `<small class="text-success"><i class="fas fa-map-marker-alt me-1"></i>✅ GPS Location Verified - ${distance}m from store (limit: ${data.radius_m}m)</small>`;
                            }
                        }
                    } else {
                        if (locationStatus) {
                            const distance = data.distance_m ? Math.round(data.distance_m) : 'unknown';
                            locationStatus.innerHTML = `<small class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i>⚠️ Too far from store: ${distance}m (limit: ${data.radius_m}m)</small>`;
                        }
                    }
                } catch (error) {
                    console.log('Location status check failed:', error);
                    const locationStatus = document.getElementById('locationStatus');
                    if (locationStatus) {
                        locationStatus.innerHTML = '<small class="text-muted"><i class="fas fa-exclamation-circle me-1"></i>Unable to verify location - Try again or use Store WiFi</small>';
                    }
                }
            }

            // Helper functions for user actions
            window.requestLocationAgain = function() {
                if ('geolocation' in navigator) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            showDetailedError(
                                '✅ GPS detected successfully!', 
                                'Your location has been updated. Try marking attendance again.'
                            );
                        },
                        function(error) {
                            showDetailedError(
                                '❌ GPS still not available.', 
                                'Please check device settings or try connecting to Store WiFi instead.'
                            );
                        },
                        { enableHighAccuracy: true, timeout: 10000 }
                    );
                } else {
                    showDetailedError(
                        '❌ GPS not supported on this device.', 
                        'Please connect to Store WiFi to mark attendance.'
                    );
                }
            }

            window.showWiFiHelp = function() {
                const helpHtml = `
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <h6><i class="fas fa-wifi me-2"></i>How to Connect to Store WiFi:</h6>
                        <ol class="mb-2" style="font-size: 0.9rem;">
                            <li>Open your device's WiFi settings</li>
                            <li>Look for the Store WiFi network</li>
                            <li>Connect using the store password</li>
                            <li>Return to this page and try again</li>
                        </ol>
                        <small class="text-muted">💡 Tip: Store WiFi allows instant attendance marking without GPS!</small>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
                
                // Remove existing alerts first
                document.querySelectorAll('.alert').forEach(alert => {
                    if (alert.textContent.includes('How to Connect')) alert.remove();
                });
                
                document.body.insertAdjacentHTML('afterbegin', helpHtml);
            }

            // Test function to demonstrate different error scenarios
            window.testErrorMessages = function() {
                const errorScenarios = [
                    {
                        title: '📍 GPS Permission Denied',
                        message: '📍 Location access denied.',
                        solution: '✅ Solutions: (1) Allow location in browser settings, or (2) Connect to Store Wi-Fi'
                    },
                    {
                        title: '🛰️ GPS Signal Problems', 
                        message: '🛰️ GPS signal not available.',
                        solution: '✅ Solutions: (1) Go outside for better GPS signal, or (2) Connect to Store Wi-Fi'
                    },
                    {
                        title: '⏰ Location Timeout',
                        message: '⏰ Location detection timed out.',
                        solution: '✅ Solutions: (1) Try again in a moment, or (2) Connect to Store Wi-Fi'
                    },
                    {
                        title: '📱 GPS Unavailable',
                        message: '📱 Location services unavailable.',
                        solution: '✅ Solutions: (1) Enable GPS in device settings, or (2) Connect to Store Wi-Fi'
                    }
                ];
                
                let currentTest = 0;
                
                function showNextError() {
                    if (currentTest < errorScenarios.length) {
                        const scenario = errorScenarios[currentTest];
                        showDetailedError(scenario.message, scenario.solution);
                        currentTest++;
                        
                        setTimeout(() => {
                            const existingAlerts = document.querySelectorAll('.auto-hide-alert');
                            existingAlerts.forEach(alert => alert.remove());
                            
                            if (currentTest < errorScenarios.length) {
                                setTimeout(showNextError, 1000); // Longer gap between messages
                            } else {
                                setTimeout(() => {
                                    const demoComplete = `
                                        <div class="alert alert-success alert-dismissible fade show fixed-top-alert" role="alert" style="font-size: 0.95rem;">
                                            <i class="fas fa-check-circle me-2"></i>
                                            <strong>Demo Complete!</strong> Those are the error messages employees see when they have location problems.
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    `;
                                    document.body.insertAdjacentHTML('afterbegin', demoComplete);
                                    
                                    // Auto-hide demo complete message
                                    setTimeout(() => {
                                        const alert = document.querySelector('.alert-success');
                                        if (alert) {
                                            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                                            bsAlert.close();
                                        }
                                    }, 8000);
                                }, 1000);
                            }
                        }, 5000); // Show each message for 5 seconds
                    }
                }
                
                showNextError();
            }

            // Mobile-specific optimizations
            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            if (isMobile) {
                // Prevent zoom on form interactions
                const viewport = document.querySelector('meta[name="viewport"]');
                if (viewport) {
                    viewport.setAttribute('content', 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no');
                }
                
                // Add mobile-specific styling
                document.body.classList.add('mobile-device');
            }
        });
        </script>

        {{-- === TOOLBAR ABOVE TABLE === --}}
        <div class="d-flex flex-wrap mb-3 gap-2">
            <div class="d-flex flex-wrap gap-2">
                <button id="refreshBtn" class="btn btn-info btn-sm">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
                <button id="enableFilteringBtn" class="btn btn-dark btn-sm">
                    <i class="fas fa-filter"></i> Enable Filtering
                </button>
                <button id="selectAllBtn" class="btn btn-secondary btn-sm">
                    <i class="fas fa-check-double"></i> Select All
                </button>
                <a href="{{ route('attendance.pdf') }}" target="_blank" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-file-pdf"></i> PDF Print All
                </a>
                <button id="printSelectedBtn" class="btn btn-purple btn-sm" disabled>
                    <i class="fas fa-print"></i> Print Selected
                </button>
                <button id="downloadSelectedBtn" class="btn btn-success btn-sm" disabled>
                    <i class="fas fa-download"></i> Download Selected
                </button>
            </div>
            <div id="selectedCount" class="small text-muted d-none">
                <i class="fas fa-check-circle"></i> Selected: <span id="selectedCountNumber">0</span> records
            </div>
        </div>

        {{-- === SEARCH & FILTER FORM === --}}
        <form method="GET" action="{{ route('attendance.my') }}" class="d-flex flex-wrap gap-3 mb-3">
            {{-- Search by Created By --}}
            <div>
                <label for="search_created_by" class="form-label small fw-medium">Search by Created:</label>
                <input type="text" name="search_created_by" id="search_created_by" value="{{ request('search_created_by') }}"
                       class="form-control form-control-sm" placeholder="Enter Created By..." />
            </div>

            {{-- Date From --}}
            <div>
                <label for="date_from" class="form-label small fw-medium">From:</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                       class="form-control form-control-sm" />
            </div>

            {{-- Date To --}}
            <div>
                <label for="date_to" class="form-label fw-medium">To:</label>
                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                       class="form-control form-control-sm" />
            </div>

                        {{-- Submit and Clear buttons --}}
            <div class="gap-2">
                <!-- Search Button -->
                <button type="submit" class="btn btn-primary btn-sm">
                    Search
                </button>

                <!-- Clear Button -->
                <a href="{{ route('attendance.my') }}" class="btn btn-secondary btn-sm">
                    Clear
                </a>
            </div>
        </form>

        {{-- === ADVANCED FILTERING SECTION === --}}
        <div id="advancedFiltering" class="border rounded p-3 mb-3 d-none">
            <h3 class="mb-3">Advanced Filtering</h3>
            <div class="row">
                <!-- Date Filter -->
                <div class="col-md-3">
                    <label class="mb-1">Date</label>
                    <input type="date" id="filterDate" class="form-control form-control-sm">
                </div>
                
                <!-- Day Type Filter -->
                <div class="col-md-3">
                    <label class="mb-1">Day Type</label>
                    <select id="filterDayType" class="form-select">
                        <option value="">All Day Types</option>
                        <option value="Regular">Regular</option>
                        <option value="Holiday">Holiday</option>
                        <option value="Rest Day">Rest Day</option>
                        <option value="Overtime">Overtime</option>
                    </select>
                </div>
                
                <!-- Status Filter -->
                <div class="col-md-3">
                    <label class="mb-1">Status</label>
                    <select id="filterStatus" class="form-select">
                        <option value="">All Status</option>
                        <option value="Present">Present</option>
                        <option value="Late">Late</option>
                        <option value="Absent">Absent</option>
                        <option value="Time In Only">Time In Only</option>
                        <option value="Incomplete">Incomplete</option>
                    </select>
                </div>
                
                <!-- Created By Filter -->
                <div class="col-md-3">
                    <label class="mb-1">Created By</label>
                    <input type="text" id="filterCreatedBy" placeholder="Enter name..." class="form-control form-control-sm">
                </div>
                
                <!-- Time In Range -->
                <div class="col-md-3">
                    <label class="mb-1">Time In From</label>
                    <input type="time" id="filterTimeInFrom" class="form-control form-control-sm">
                </div>
                
                <div class="col-md-3">
                    <label class="mb-1">Time In To</label>
                    <input type="time" id="filterTimeInTo" class="form-control form-control-sm">
                </div>
                
                <!-- Remarks Filter -->
                <div class="col-md-3">
                    <label class="mb-1">Remarks</label>
                    <input type="text" id="filterRemarks" placeholder="Search remarks..." class="form-control form-control-sm">
                </div>
                
                <!-- Filter Actions -->
                <div class="gap-2">
                    <button id="applyFiltersBtn" class="btn btn-primary btn-sm">
                        Apply Filters
                    </button>
                    <button id="clearFiltersBtn" class="btn btn-secondary btn-sm">
                        Clear All
                    </button>
                </div>
            </div>
        </div>

        {{-- === ATTENDANCE TABLE === --}}
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-check me-2"></i>
                    My Attendance Records
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="attendanceTable">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" style="width: 50px;">
                                    <input type="checkbox" id="selectAllCheckbox" class="form-check-input">
                                </th>
                                <th><i class="fas fa-calendar-day me-1"></i>Date</th>
                                <th><i class="fas fa-tags me-1"></i>Day Type</th>
                                <th><i class="fas fa-sign-in-alt me-1"></i>Time In</th>
                                <th><i class="fas fa-sign-out-alt me-1"></i>Time Out</th>
                                <th><i class="fas fa-coffee me-1"></i>Break In</th>
                                <th><i class=""></i>Break Out</th>
                                <th><i class="fas fa-user-check me-1"></i>Status</th>
                                <th><i class="fas fa-comment me-1"></i>Remarks</th>
                                <th><i class=""></i>Created At</th>
                                <th><i class="fas fa-user me-1"></i>Created By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances as $attendance)
                                <tr class="attendance-row" 
                                    data-date="{{ $attendance['date'] }}"
                                    data-day-type="{{ $attendance['day_type'] }}"
                                    data-time-in="{{ $attendance['time_in'] }}"
                                    data-time-out="{{ $attendance['time_out'] }}"
                                    data-status="{{ $attendance['status'] }}"
                                    data-remarks="{{ $attendance['remarks'] }}"
                                    data-created-at="{{ $attendance['created_at'] }}"
                                    data-created-by="{{ $attendance['created_by'] }}">
                                    <td class="empty text-center">
                                        <input type="checkbox" class="row-checkbox form-check-input" value="{{ $attendance['id'] }}">
                                    </td>
                                    <td>
                                        <span class="fw-medium">{{ \Carbon\Carbon::parse($attendance['date'])->format('M d, Y') }}</span>
                                        <br><small class="">{{ \Carbon\Carbon::parse($attendance['date'])->format('D') }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $dayTypeClass = '';
                                            switch($attendance['day_type']) {
                                                case 'Holiday':
                                                    $dayTypeClass = 'badge bg-danger';
                                                    break;
                                                case 'Rest Day':
                                                    $dayTypeClass = 'badge bg-warning';
                                                    break;
                                                case 'Overtime':
                                                    $dayTypeClass = 'badge bg-info';
                                                    break;
                                                default:
                                                    $dayTypeClass = 'badge bg-secondary';
                                            }
                                        @endphp
                                        <span class="{{ $dayTypeClass }}">{{ $attendance['day_type'] }}</span>
                                    </td>
                                    <td>
                                        @if($attendance['time_in'])
                                            <span class="text-success fw-medium">
                                                <i class="fas fa-sign-in-alt me-1"></i>{{ $attendance['time_in'] }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($attendance['time_out'])
                                            <span class="text-danger fw-medium">
                                                <i class="fas fa-sign-out-alt me-1"></i>{{ $attendance['time_out'] }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($attendance['breaktime_in']) && $attendance['breaktime_in'])
                                            <span class="text-warning fw-medium">
                                                <i class="fas fa-coffee me-1"></i>{{ \Carbon\Carbon::parse($attendance['breaktime_in'])->format('h:i A') }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($attendance['breaktime_out']) && $attendance['breaktime_out'])
                                            <span class="text-info fw-medium">
                                                <i class="fas fa-coffee me-1"></i>{{ \Carbon\Carbon::parse($attendance['breaktime_out'])->format('h:i A') }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    <td>
                                        @php
                                            $status = $attendance['status'];
                                            $attendanceStatus = $attendance['attendance_status'] ?? 'pending';
                                        @endphp
                                        
                                        {{-- Display attendance status with approval status --}}
                                        <div>
                                            @if($status === 'Present')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>{{ $status }}
                                                </span>
                                            @elseif($status === 'Late')
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-clock me-1"></i>{{ $status }}
                                                </span>
                                            @elseif($status === 'Absent')
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times me-1"></i>{{ $status }}
                                                </span> 
                                            @else
                                                <span class="badge bg-secondary">{{ $status }}</span>
                                            @endif
                                            
                                            {{-- Show approval status --}}
                                            <br>
                                            @if($attendanceStatus === 'approved')
                                                <small class="text-success">
                                                    <i class="fas fa-check-circle me-1"></i>Approved
                                                </small>
                                            @elseif($attendanceStatus === 'rejected')
                                                <small class="text-danger">
                                                    <i class="fas fa-times-circle me-1"></i>Rejected
                                                </small>
                                            @else
                                                <small class="text-warning">
                                                    <i class="fas fa-hourglass-half me-1"></i>Pending
                                                </small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($attendance['remarks'])
                                            <span class="">{{ $attendance['remarks'] }}</span>
                                        @else
                                            <span class="fst-italic">No remarks</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="">
                                            {{ \Carbon\Carbon::parse($attendance['created_at'])->format('M d, Y') }}
                                            <br>{{ \Carbon\Carbon::parse($attendance['created_at'])->format('h:i A') }}
                                        </small>
                                    </td>
                                    <td>
                                        @php
                                            $createdByCurrentUser = $attendance['created_by'] === Auth::user()->name;
                                        @endphp
                                        
                                        <div>
                                            <span class="fw-medium">{{ $attendance['created_by'] }}</span>
                                            <br>
                                            @if($createdByCurrentUser)
                                                <small class="text-success">
                                                    <i class="fas fa-user-check me-1"></i>Self-marked
                                                </small>
                                            @else
                                                <small class="text-warning">
                                                    <i class="fas fa-user-cog me-1"></i>Created by HR/Manager
                                                </small>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        @if(request()->hasAny(['search_created_by', 'date_from', 'date_to']))
                                        <div class="py-4 text-center">
                                            <!-- Bootstrap Icon instead of image -->
                                            <i class="bi bi-search empty" style="font-size: 3rem;"></i>
                                            <h5 class="fw-medium empty mt-3">No records match your search criteria</h5>
                                            <p class="small empty">Try adjusting your search criteria</p>
                                            
                                            <a href="{{ route('attendance.my') }}" class="btn btn-outline-primary btn-sm mt-2">
                                                <i class="bi bi-x-circle me-1"></i> Clear Search
                                            </a>
                                        </div>

                                        @else
                                            <div class="py-4">
                                                <img src="{{ asset('img/no-data.png') }}" alt="No Data" style="width: 64px; height: 64px;" class="mb-3">
                                                <h5 class="fw-medium text-muted">No attendance records found</h5>
                                                <p class="small text-muted">Start by marking your attendance above</p>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

{{-- === PAGINATION === --}}
@if($attendances->hasPages())
    <div class="mt-3">
        {{ $attendances->appends(request()->query())->links() }}
    </div>
@endif
    </div>

<style>
    body.light .empty {
    color: #050505; /* secondary color */
    }

    body.dark .empty {
        color: #ffffff; /* white for dark mode */
    }

    
    .btn-purple {
        background-color: #6f42c1;
        border-color: #6f42c1;
        color: white;
    }
    .btn-purple:hover {
        background-color: #5a359a;
        border-color: #5a359a;
        color: white;
    }
    .btn-purple:disabled {
        background-color: #9b59b6;
        border-color: #9b59b6;
        opacity: 0.6;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.1);
    }
    
    .attendance-row {
        transition: all 0.2s ease;
    }
    
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid rgba(0, 0, 0, 0.125);
    }

    /* Enhanced Alert Message Styling */
        .alert {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-left: 4px solid;
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border-left-color: #28a745;
            color: #155724;
        }

        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            border-left-color: #dc3545;
            color: #721c24;
        }

        .alert-warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border-left-color: #ffc107;
            color: #856404;
        }

        .alert i {
            font-size: 1.1em;
        }

        /* Animation for alert entrance */
        .alert.fade.show {
            animation: slideInDown 0.5s ease-out;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

     /* MOBILE STYLES */
        @media (max-width: 576px) {
            #attendanceTable th, #attendanceTable td {
                font-size: 12px;
            }
            .btn {
                padding: 0.5rem 0.75rem;
                font-size: 0.875rem;
            }
            .table-hover tbody tr:hover {
                background-color: inherit;
            }
        }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.auto-hide-alert');
    
    alerts.forEach(function(alert) {
        // Add a progress bar for visual feedback
        const progressBar = document.createElement('div');
        progressBar.style.cssText = `
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: rgba(0, 0, 0, 0.2);
            width: 100%;
            animation: progressBar 5s linear forwards;
        `;
        alert.style.position = 'relative';
        alert.appendChild(progressBar);

        // Auto-hide after 5 seconds
        setTimeout(function() {
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    });

    // CSS for progress bar animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes progressBar {
            from { width: 100%; }
            to { width: 0%; }
        }
    `;
    document.head.appendChild(style);

    // Elements
    const enableFilteringBtn = document.getElementById('enableFilteringBtn');
    const advancedFiltering = document.getElementById('advancedFiltering');
    const selectAllBtn = document.getElementById('selectAllBtn');
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    const selectedCount = document.getElementById('selectedCount');
    const selectedCountNumber = document.getElementById('selectedCountNumber');
    const printSelectedBtn = document.getElementById('printSelectedBtn');
    const downloadSelectedBtn = document.getElementById('downloadSelectedBtn');
    const applyFiltersBtn = document.getElementById('applyFiltersBtn');
    const clearFiltersBtn = document.getElementById('clearFiltersBtn');
    
    // Toggle Advanced Filtering
    enableFilteringBtn.addEventListener('click', function() {
        const isHidden = advancedFiltering.classList.contains('d-none');
        if (isHidden) {
            advancedFiltering.classList.remove('d-none');
            enableFilteringBtn.innerHTML = '<i class="fas fa-eye-slash"></i> Hide Filtering';
            enableFilteringBtn.className = 'btn btn-danger btn-sm';
        } else {
            advancedFiltering.classList.add('d-none');
            enableFilteringBtn.innerHTML = '<i class="fas fa-filter"></i> Enable Filtering';
            enableFilteringBtn.className = 'btn btn-dark btn-sm';
        }
    });
    
    // Select All functionality
    function updateSelectAll() {
        const visibleCheckboxes = Array.from(rowCheckboxes).filter(cb => 
            !cb.closest('tr').classList.contains('d-none')
        );
        const checkedBoxes = visibleCheckboxes.filter(cb => cb.checked);
        
        selectAllCheckbox.checked = visibleCheckboxes.length > 0 && 
                                   checkedBoxes.length === visibleCheckboxes.length;
        selectAllCheckbox.indeterminate = checkedBoxes.length > 0 && 
                                        checkedBoxes.length < visibleCheckboxes.length;
        
        // Update count and button states
        selectedCountNumber.textContent = checkedBoxes.length;
        if (checkedBoxes.length > 0) {
            selectedCount.classList.remove('d-none');
            printSelectedBtn.disabled = false;
            downloadSelectedBtn.disabled = false;
        } else {
            selectedCount.classList.add('d-none');
            printSelectedBtn.disabled = true;
            downloadSelectedBtn.disabled = true;
        }
    }
    
    // Select All checkbox
    selectAllCheckbox.addEventListener('change', function() {
        const visibleCheckboxes = Array.from(rowCheckboxes).filter(cb => 
            !cb.closest('tr').classList.contains('d-none')
        );
        visibleCheckboxes.forEach(cb => cb.checked = this.checked);
        updateSelectAll();
    });
    
    // Select All button
    selectAllBtn.addEventListener('click', function() {
        const visibleCheckboxes = Array.from(rowCheckboxes).filter(cb => 
            !cb.closest('tr').classList.contains('d-none')
        );
        const allChecked = visibleCheckboxes.every(cb => cb.checked);
        visibleCheckboxes.forEach(cb => cb.checked = !allChecked);
        updateSelectAll();
    });
    
    // Individual checkboxes
    rowCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectAll);
    });
    
    // Refresh button
    document.getElementById('refreshBtn').addEventListener('click', function() {
        location.reload();
    });
    // Filtering functionality
    function applyFilters() {
        const filterDate = document.getElementById('filterDate').value;
        const filterDayType = document.getElementById('filterDayType').value;
        const filterStatus = document.getElementById('filterStatus').value;
        const filterCreatedBy = document.getElementById('filterCreatedBy').value.toLowerCase();
        const filterTimeInFrom = document.getElementById('filterTimeInFrom').value;
        const filterTimeInTo = document.getElementById('filterTimeInTo').value;
        const filterRemarks = document.getElementById('filterRemarks').value.toLowerCase();
        
        const rows = document.querySelectorAll('.attendance-row');
        
        rows.forEach(row => {
            let show = true;
            
            // Date filter
            if (filterDate && row.dataset.date !== filterDate) {
                show = false;
            }
            
            // Day Type filter
            if (filterDayType && row.dataset.dayType !== filterDayType) {
                show = false;
            }
            
            // Status filter
            if (filterStatus && !row.dataset.status.includes(filterStatus)) {
                show = false;
            }
            
            // Created By filter
            if (filterCreatedBy && !row.dataset.createdBy.toLowerCase().includes(filterCreatedBy)) {
                show = false;
            }
            
            // Remarks filter
            if (filterRemarks && !row.dataset.remarks.toLowerCase().includes(filterRemarks)) {
                show = false;
            }
            
            // Time In range filter
            if (filterTimeInFrom || filterTimeInTo) {
                const timeIn = row.dataset.timeIn;
                if (timeIn && timeIn !== '-') {
                    const timeIn24 = convertTo24Hour(timeIn);
                    if (filterTimeInFrom && timeIn24 < filterTimeInFrom) {
                        show = false;
                    }
                    if (filterTimeInTo && timeIn24 > filterTimeInTo) {
                        show = false;
                    }
                }
            }
            
            // Show/hide row
            if (show) {
                row.classList.remove('d-none');
            } else {
                row.classList.add('d-none');
                row.querySelector('.row-checkbox').checked = false;
            }
        });
        
        updateSelectAll();
    }
    
    // Convert 12-hour time to 24-hour format
    function convertTo24Hour(time12h) {
        if (time12h === '-') return '';
        const [time, modifier] = time12h.split(' ');
        let [hours, minutes] = time.split(':');
        if (hours === '12') {
            hours = '00';
        }
        if (modifier === 'PM') {
            hours = parseInt(hours, 10) + 12;
        }
        return `${hours.padStart(2, '0')}:${minutes}`;
    }
    
    // Clear all filters
    function clearFilters() {
        document.getElementById('filterDate').value = '';
        document.getElementById('filterDayType').value = '';
        document.getElementById('filterStatus').value = '';
        document.getElementById('filterCreatedBy').value = '';
        document.getElementById('filterTimeInFrom').value = '';
        document.getElementById('filterTimeInTo').value = '';
        document.getElementById('filterRemarks').value = '';
        
        document.querySelectorAll('.attendance-row').forEach(row => {
            row.classList.remove('d-none');
        });
        
        updateSelectAll();
    }
    
    // Event listeners for filters
    applyFiltersBtn.addEventListener('click', applyFilters);
    clearFiltersBtn.addEventListener('click', clearFilters);
    
    // Print selected records
    printSelectedBtn.addEventListener('click', function() {
        const selectedIds = Array.from(rowCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);
        
        if (selectedIds.length === 0) {
            alert('Please select at least one record to print.');
            return;
        }
        
        // Create a new window for printing
        const printWindow = window.open('', '_blank');
        const selectedRows = Array.from(rowCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.closest('tr'));
        
        let printContent = `
            <html>
            <head>
                <title>Attendance Records</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th, td { border: 1px solid #000; padding: 8px; text-align: left; font-size: 12px; }
                    th { background-color: #f0f0f0; font-weight: bold; }
                    h1 { text-align: center; margin-bottom: 20px; }
                    .print-info { margin-bottom: 20px; }
                </style>
            </head>
            <body>
                <h1>Attendance Records</h1>
                <div class="print-info">
                    <p><strong>Generated:</strong> ${new Date().toLocaleString()}</p>
                    <p><strong>Records:</strong> ${selectedIds.length}</p>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Day Type</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th>Created At</th>
                            <th>Created By</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        selectedRows.forEach(row => {
            const cells = row.querySelectorAll('td');
            printContent += '<tr>';
            // Skip checkbox column (index 0)
            for (let i = 1; i < cells.length; i++) {
                let cellContent = cells[i].textContent.trim();
                // Clean up content
                cellContent = cellContent.replace(/\s+/g, ' ').trim();
                printContent += `<td>${cellContent}</td>`;
            }
            printContent += '</tr>';
        });
        
        printContent += `
                    </tbody>
                </table>
            </body>
            </html>
        `;
        
        printWindow.document.write(printContent);
        printWindow.document.close();
        printWindow.print();
    });
    
    // Download selected records as CSV
    downloadSelectedBtn.addEventListener('click', function() {
        const selectedIds = Array.from(rowCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);
        
        if (selectedIds.length === 0) {
            alert('Please select at least one record to download.');
            return;
        }
        
        const selectedRows = Array.from(rowCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.closest('tr'));
        
        let csvContent = 'Date,Day Type,Time In,Time Out,Status,Remarks,Created At,Created By\n';
        
        selectedRows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const rowData = [];
            // Skip checkbox column (index 0)
            for (let i = 1; i < cells.length; i++) {
                let cellContent = cells[i].textContent.trim();
                // Clean up content
                cellContent = cellContent.replace(/\s+/g, ' ').trim();
                // Escape commas and quotes in CSV
                if (cellContent.includes(',') || cellContent.includes('"')) {
                    cellContent = '"' + cellContent.replace(/"/g, '""') + '"';
                }
                rowData.push(cellContent);
            }
            csvContent += rowData.join(',') + '\n';
        });
        
        // Create and download file
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `attendance_records_${new Date().toISOString().split('T')[0]}.csv`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
    });
    
    // Initialize
    updateSelectAll();
});
</script>
</x-app-layout>
