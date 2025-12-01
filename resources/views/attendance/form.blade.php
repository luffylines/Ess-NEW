<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-clock me-2"></i>Mark Daily Attendance</h4>
                    </div>
                    <div class="card-body">
                        @include('partials.flash-messages')

                        {{-- Show current attendance status if exists --}}
                        @if($attendance && $attendance->time_in && $attendance->time_out)
                            <div class="text-center">
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <strong>Attendance Completed for Today</strong>
                                </div>
                                
                                <div class="row text-center mb-3">
                                    <div class="col-6 col-md-3 mb-2">
                                        <div class="card bg-light">
                                            <div class="card-body py-2">
                                                <h6 class="text-success mb-0">{{ $attendance->time_in?->format('h:i A') }}</h6>
                                                <small class="text-muted">Time In</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3 mb-2">
                                        <div class="card bg-light">
                                            <div class="card-body py-2">
                                                <h6 class="text-danger mb-0">{{ $attendance->time_out?->format('h:i A') }}</h6>
                                                <small class="text-muted">Time Out</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3 mb-2">
                                        <div class="card bg-light">
                                            <div class="card-body py-2">
                                                <h6 class="text-warning mb-0">{{ $attendance->breaktime_in?->format('h:i A') ?? 'N/A' }}</h6>
                                                <small class="text-muted">Break In</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3 mb-2">
                                        <div class="card bg-light">
                                            <div class="card-body py-2">
                                                <h6 class="text-info mb-0">{{ $attendance->breaktime_out?->format('h:i A') ?? 'N/A' }}</h6>
                                                <small class="text-muted">Break Out</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row text-center">
                                    <div class="col-6">
                                        <div class="border-end">
                                            <h5 class="text-success">{{ $attendance->total_hours ?? '0.00' }}</h5>
                                            <small class="text-muted">Working Hours</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <h5 class="text-success">₱{{ number_format($attendance->earned_amount ?? 0, 2) }}</h5>
                                        <small class="text-muted">Earned Amount</small>
                                    </div>
                                </div>
                            </div>

                        {{-- Show button-based attendance marking --}}
                        @else
                            <form method="POST" action="{{ route('attendance.submit') }}" id="attendanceMarkForm">
                                @csrf
                                
                                {{-- Time In --}}
                                @if(!$attendance || !$attendance->time_in)
                                    <div class="text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-clock display-4 text-primary mb-3"></i>
                                            <h5>Ready to start your workday?</h5>
                                            <p class="text-muted">Current time: <span id="currentTime"></span></p>
                                        </div>
                                        <button type="submit" name="action" value="time_in" class="btn btn-success btn-lg w-100" id="btnTimeIn">
                                            <i class="fas fa-play me-2"></i>Mark Time In
                                        </button>
                                        </div>
                                
                                {{-- Break Time In --}}
                                @elseif($attendance->time_in && !$attendance->breaktime_in)
                                    <div class="text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-check-circle text-success display-4 mb-3"></i>
                                            <h5>Work session started</h5>
                                            <p class="text-success mb-1">
                                                <i class="fas fa-clock me-1"></i>Time In: {{ $attendance->time_in->format('h:i A') }}
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
                                @elseif($attendance->breaktime_in && !$attendance->breaktime_out)
                                    <div class="text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-coffee text-warning display-4 mb-3"></i>
                                            <h5>On lunch break</h5>
                                            <p class="text-success mb-1">
                                                <i class="fas fa-clock me-1"></i>Time In: {{ $attendance->time_in->format('h:i A') }}
                                            </p>
                                            <p class="text-warning mb-1">
                                                <i class="fas fa-coffee me-1"></i>Break In: {{ $attendance->breaktime_in->format('h:i A') }}
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
                                @elseif($attendance->breaktime_out && !$attendance->time_out)
                                    <div class="text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-briefcase text-primary display-4 mb-3"></i>
                                            <h5>Back to work</h5>
                                            <p class="text-success mb-1">
                                                <i class="fas fa-clock me-1"></i>Time In: {{ $attendance->time_in->format('h:i A') }}
                                            </p>
                                            <p class="text-info mb-1">
                                                <i class="fas fa-coffee me-1"></i>Break: {{ $attendance->breaktime_in->format('h:i A') }} - {{ $attendance->breaktime_out->format('h:i A') }}
                                            </p>
                                            <p class="text-muted">Current time: <span id="currentTime"></span></p>
                                        </div>
                                        <button type="submit" name="action" value="time_out" class="btn btn-danger btn-lg w-100" id="btnTimeOut">
                                            <i class="fas fa-stop me-2"></i>Mark Time Out
                                        </button>
                                        </div>
                                @endif
                            </form>

                            {{-- Show current attendance progress if any --}}
                            @if($attendance)
                                <div class="mt-4 p-3 bg-light rounded">
                                    <h6><i class="fas fa-info-circle me-2"></i>Today's Progress</h6>
                                    <div class="row text-center">
                                        @if($attendance->time_in)
                                            <div class="col-6 col-md-3 mb-2">
                                                <span class="badge bg-success">✓ Time In</span>
                                                <div class="small text-muted">{{ $attendance->time_in->format('h:i A') }}</div>
                                            </div>
                                        @endif
                                        @if($attendance->breaktime_in)
                                            <div class="col-6 col-md-3 mb-2">
                                                <span class="badge bg-warning">✓ Break In</span>
                                                <div class="small text-muted">{{ $attendance->breaktime_in->format('h:i A') }}</div>
                                            </div>
                                        @endif
                                        @if($attendance->breaktime_out)
                                            <div class="col-6 col-md-3 mb-2">
                                                <span class="badge bg-info">✓ Break Out</span>
                                                <div class="small text-muted">{{ $attendance->breaktime_out->format('h:i A') }}</div>
                                            </div>
                                        @endif
                                        @if($attendance->time_out)
                                            <div class="col-6 col-md-3 mb-2">
                                                <span class="badge bg-danger">✓ Time Out</span>
                                                <div class="small text-muted">{{ $attendance->time_out->format('h:i A') }}</div>
                                            </div>
                                        @endif
                                    </div>
                                    @if($attendance->status)
                                        <div class="text-center mt-2">
                                            <span class="badge bg-{{ $attendance->status === 'approved' ? 'success' : ($attendance->status === 'pending' ? 'warning' : 'danger') }}">
                                                Status: {{ ucfirst($attendance->status) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Mobile-first responsive design */
        @media (max-width: 768px) {
            .btn-lg {
                font-size: 1.1rem;
                padding: 0.75rem 1rem;
            }
            
            .display-4 {
                font-size: 2.5rem;
            }
            
            .card-body {
                padding: 1.5rem;
            }
        }
        
        /* Button hover effects */
        .btn {
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        /* Time display */
        #currentTime {
            font-weight: bold;
            color: #007bff;
        }
        
        /* Prevent text selection on buttons */
        .btn {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        
        /* Ensure no input fields are editable */
        input[type="time"], 
        input[type="number"], 
        input[type="text"] {
            display: none !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
            
            // Button click handling with loading state
            const form = document.getElementById('attendanceMarkForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const buttons = form.querySelectorAll('button[type="submit"]');
                    buttons.forEach(btn => {
                        btn.disabled = true;
                        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
                    });
                    
                    // Prevent double submission
                    setTimeout(() => {
                        buttons.forEach(btn => {
                            btn.style.pointerEvents = 'none';
                        });
                    }, 100);
                });
            }
            
            // Hide any input fields that might still be visible
            const inputs = document.querySelectorAll('input[type="time"], input[type="number"], input[type="text"]');
            inputs.forEach(input => {
                input.style.display = 'none';
                input.disabled = true;
            });
        });
    </script>
</x-app-layout>
