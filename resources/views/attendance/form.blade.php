<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-clock me-2"></i>Mark Daily Attendance</h4>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('attendance.submit') }}" id="attendanceForm">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold"><i class="fas fa-sign-in-alt me-1 text-success"></i>Time In:</label>
                                    <input type="time" name="time_in" id="time_in" class="form-control form-control-lg" 
                                           value="{{ $attendance?->time_in?->format('H:i') ?? '08:00' }}" required>
                                    <small class="form-text text-muted">Standard work start time: 8:00 AM</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold"><i class="fas fa-sign-out-alt me-1 text-danger"></i>Time Out:</label>
                                    <input type="time" name="time_out" id="time_out" class="form-control form-control-lg" 
                                           value="{{ $attendance?->time_out?->format('H:i') ?? '17:00' }}" required>
                                    <small class="form-text text-muted">Standard work end time: 5:00 PM</small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold"><i class="fas fa-coffee me-1 text-warning"></i>Break Time In:</label>
                                    <input type="time" name="breaktime_in" id="breaktime_in" class="form-control form-control-lg" 
                                           value="{{ $attendance?->breaktime_in?->format('H:i') ?? '12:00' }}">
                                    <small class="form-text text-muted">Lunch break start (default: 12:00 PM)</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold"><i class="fas fa-coffee me-1 text-info"></i>Break Time Out:</label>
                                    <input type="time" name="breaktime_out" id="breaktime_out" class="form-control form-control-lg" 
                                           value="{{ $attendance?->breaktime_out?->format('H:i') ?? '13:00' }}">
                                    <small class="form-text text-muted">Lunch break end (default: 1:00 PM)</small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold"><i class="fas fa-money-bill me-1 text-success"></i>Daily Rate:</label>
                                    <input type="number" name="daily_rate" id="daily_rate" class="form-control form-control-lg" 
                                           value="{{ $attendance?->daily_rate ?? '600' }}" min="0" step="0.01">
                                    <small class="form-text text-muted">Standard daily rate: ₱600</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold"><i class="fas fa-info-circle me-1"></i>Remarks (Optional):</label>
                                    <input type="text" name="remarks" class="form-control form-control-lg" 
                                           value="{{ $attendance?->remarks }}" placeholder="Any additional notes...">
                                </div>
                            </div>

                            <!-- Real-time calculation display -->
                            <div class="card bg-light border-0 mb-3">
                                <div class="card-body">
                                    <h6 class="card-title"><i class="fas fa-calculator me-2"></i>Attendance Summary</h6>
                                    <div class="row text-center">
                                        <div class="col-md-3">
                                            <div class="border-end">
                                                <h5 class="text-primary mb-0" id="totalHours">9.00</h5>
                                                <small class="text-muted">Total Hours</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="border-end">
                                                <h5 class="text-success mb-0" id="workingHours">8.00</h5>
                                                <small class="text-muted">Working Hours</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="border-end">
                                                <h5 class="text-warning mb-0" id="deductionAmount">₱0.00</h5>
                                                <small class="text-muted">Deduction</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <h5 class="text-info mb-0" id="earnedAmount">₱600.00</h5>
                                            <small class="text-muted">Earned Amount</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save me-2"></i>Submit Attendance
                                </button>
                            </div>
                        </form>

                        @if($attendance)
                            <div class="mt-4 p-3 bg-light rounded">
                                <p class="mb-0"><strong>Current Status:</strong> 
                                    <span class="badge bg-{{ $attendance->status === 'approved' ? 'success' : ($attendance->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($attendance->status) }}
                                    </span>
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const timeIn = document.getElementById('time_in');
            const timeOut = document.getElementById('time_out');
            const breaktimeIn = document.getElementById('breaktime_in');
            const breaktimeOut = document.getElementById('breaktime_out');
            const dailyRate = document.getElementById('daily_rate');

            function calculateAttendance() {
                const timeInValue = timeIn.value;
                const timeOutValue = timeOut.value;
                const breaktimeInValue = breaktimeIn.value;
                const breaktimeOutValue = breaktimeOut.value;
                const rate = parseFloat(dailyRate.value) || 600;

                if (timeInValue && timeOutValue) {
                    // Calculate total hours
                    const startTime = new Date(`1970-01-01T${timeInValue}:00`);
                    const endTime = new Date(`1970-01-01T${timeOutValue}:00`);
                    let totalMinutes = (endTime - startTime) / (1000 * 60);

                    // Subtract break time
                    if (breaktimeInValue && breaktimeOutValue) {
                        const breakStart = new Date(`1970-01-01T${breaktimeInValue}:00`);
                        const breakEnd = new Date(`1970-01-01T${breaktimeOutValue}:00`);
                        const breakMinutes = (breakEnd - breakStart) / (1000 * 60);
                        totalMinutes -= breakMinutes;
                    }

                    const totalHours = totalMinutes / 60;
                    const workingHours = Math.min(totalHours, 8); // Cap at 8 hours for regular pay
                    const standardHours = 8;
                    const hourlyRate = rate / standardHours;

                    // Calculate deductions
                    let deductionAmount = 0;
                    let earnedAmount = rate;

                    if (totalHours < 7) {
                        // Significant deduction for <7 hours
                        const deductionHours = standardHours - totalHours;
                        deductionAmount = deductionHours * hourlyRate;
                        earnedAmount = totalHours * hourlyRate;
                    } else if (totalHours < 8) {
                        // Partial deduction for 7-7.99 hours
                        const deductionHours = standardHours - totalHours;
                        deductionAmount = deductionHours * hourlyRate;
                        earnedAmount = totalHours * hourlyRate;
                    } else {
                        // No deduction for 8+ hours
                        deductionAmount = 0;
                        earnedAmount = rate;
                    }

                    // Update display
                    document.getElementById('totalHours').textContent = totalHours.toFixed(2);
                    document.getElementById('workingHours').textContent = totalHours.toFixed(2);
                    document.getElementById('deductionAmount').textContent = `₱${deductionAmount.toFixed(2)}`;
                    document.getElementById('earnedAmount').textContent = `₱${earnedAmount.toFixed(2)}`;

                    // Change colors based on hours
                    const workingHoursEl = document.getElementById('workingHours');
                    const deductionEl = document.getElementById('deductionAmount');
                    
                    if (totalHours >= 8) {
                        workingHoursEl.className = 'text-success mb-0';
                        deductionEl.className = 'text-success mb-0';
                    } else if (totalHours >= 7) {
                        workingHoursEl.className = 'text-warning mb-0';
                        deductionEl.className = 'text-warning mb-0';
                    } else {
                        workingHoursEl.className = 'text-danger mb-0';
                        deductionEl.className = 'text-danger mb-0';
                    }
                }
            }

            // Add event listeners
            [timeIn, timeOut, breaktimeIn, breaktimeOut, dailyRate].forEach(input => {
                input.addEventListener('change', calculateAttendance);
                input.addEventListener('input', calculateAttendance);
            });

            // Initial calculation
            calculateAttendance();
        });
    </script>
</x-app-layout>
