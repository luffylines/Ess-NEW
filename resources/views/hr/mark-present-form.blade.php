@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show auto-hide-alert" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show auto-hide-alert" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-user-check me-2"></i>
                        Mark Employee as Present
                    </h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        You are marking <strong>{{ $employee->name }}</strong> as present for <strong>{{ \Carbon\Carbon::parse($date)->format('F j, Y') }}</strong>
                    </div>

                    <form action="{{ route('hr.mark') }}" method="POST">
                        @csrf
                        <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                        <input type="hidden" name="date" value="{{ $date }}">
                        <input type="hidden" name="status" value="present">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="time_in" class="form-label">
                                    <i class="fas fa-sign-in-alt me-1 text-success"></i>Time In
                                </label>
                                <input type="time" 
                                       class="form-control @error('time_in') is-invalid @enderror" 
                                       id="time_in" 
                                       name="time_in" 
                                       value="{{ old('time_in', '08:00') }}" 
                                       required>
                                @error('time_in')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="time_out" class="form-label">
                                    <i class="fas fa-sign-out-alt me-1 text-danger"></i>Time Out
                                </label>
                                <input type="time" 
                                       class="form-control @error('time_out') is-invalid @enderror" 
                                       id="time_out" 
                                       name="time_out" 
                                       value="{{ old('time_out', '17:00') }}">
                                @error('time_out')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Leave empty if employee hasn't clocked out yet</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="breaktime_in" class="form-label">
                                    <i class="fas fa-coffee me-1 text-warning"></i>Break Time In
                                </label>
                                <input type="time" 
                                       class="form-control @error('breaktime_in') is-invalid @enderror" 
                                       id="breaktime_in" 
                                       name="breaktime_in" 
                                       value="{{ old('breaktime_in', '12:00') }}">
                                @error('breaktime_in')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Lunch break start (default: 12:00 PM)</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="breaktime_out" class="form-label">
                                    <i class="fas fa-coffee me-1 text-info"></i>Break Time Out
                                </label>
                                <input type="time" 
                                       class="form-control @error('breaktime_out') is-invalid @enderror" 
                                       id="breaktime_out" 
                                       name="breaktime_out" 
                                       value="{{ old('breaktime_out', '13:00') }}">
                                @error('breaktime_out')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Lunch break end (default: 1:00 PM)</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="daily_rate" class="form-label">
                                    <i class="fas fa-money-bill me-1 text-success"></i>Daily Rate
                                </label>
                                <input type="number" 
                                       class="form-control @error('daily_rate') is-invalid @enderror" 
                                       id="daily_rate" 
                                       name="daily_rate" 
                                       value="{{ old('daily_rate', '600') }}" 
                                       min="0" 
                                       step="0.01">
                                @error('daily_rate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Standard daily rate: ₱600</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-calculator me-1"></i>Attendance Summary
                                </label>
                                <div class="card bg-light border-0">
                                    <div class="card-body p-2">
                                        <div class="row text-center small">
                                            <div class="col-6">
                                                <div class="border-end">
                                                    <span class="fw-bold text-primary d-block" id="calcWorkingHours">8.00</span>
                                                    <small class="text-muted">Working Hours</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <span class="fw-bold text-success d-block" id="calcEarnedAmount">₱600.00</span>
                                                <small class="text-muted">Earned Amount</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="remarks" class="form-label">
                                <i class="fas fa-comment me-1"></i>Remarks
                            </label>
                            <textarea class="form-control @error('remarks') is-invalid @enderror" 
                                      id="remarks" 
                                      name="remarks" 
                                      rows="3" 
                                      placeholder="Enter any additional remarks about the attendance...">{{ old('remarks') }}</textarea>
                            @error('remarks')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('hr.management') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-user-check me-1"></i>Mark as Present
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    border-radius: 15px;
}

.card-header {
    border-radius: 15px 15px 0 0;
    border: none;
}

.form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

.btn-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    border-radius: 8px;
    padding: 12px 24px;
    font-weight: 500;
}

.btn-success:hover {
    background: linear-gradient(135deg, #218838 0%, #1ba085 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

.btn-secondary {
    border-radius: 8px;
    padding: 12px 24px;
    font-weight: 500;
}

.alert-info {
    border-radius: 10px;
    border: none;
    background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
}
</style>

<script>
// Real-time attendance calculation
function calculateAttendance() {
    const timeIn = document.getElementById('time_in').value;
    const timeOut = document.getElementById('time_out').value;
    const breaktimeIn = document.getElementById('breaktime_in').value;
    const breaktimeOut = document.getElementById('breaktime_out').value;
    const dailyRate = parseFloat(document.getElementById('daily_rate').value) || 600;

    if (timeIn && timeOut) {
        // Calculate total hours at work
        const startTime = new Date(`1970-01-01T${timeIn}:00`);
        const endTime = new Date(`1970-01-01T${timeOut}:00`);
        let totalMinutes = (endTime - startTime) / (1000 * 60);

        // Subtract break time to get actual working hours
        if (breaktimeIn && breaktimeOut) {
            const breakStart = new Date(`1970-01-01T${breaktimeIn}:00`);
            const breakEnd = new Date(`1970-01-01T${breaktimeOut}:00`);
            const breakMinutes = (breakEnd - breakStart) / (1000 * 60);
            totalMinutes -= breakMinutes;
        }

        const workingHours = totalMinutes / 60;
        const standardHours = 8;
        const hourlyRate = dailyRate / standardHours;

        // Calculate earned amount
        let earnedAmount = dailyRate;
        
        if (workingHours < 7) {
            earnedAmount = workingHours * hourlyRate;
        } else if (workingHours < 8) {
            earnedAmount = workingHours * hourlyRate;
        } else {
            earnedAmount = dailyRate; // Full pay for 8+ hours
        }

        // Update display
        document.getElementById('calcWorkingHours').textContent = workingHours.toFixed(2);
        document.getElementById('calcEarnedAmount').textContent = `₱${earnedAmount.toFixed(2)}`;

        // Change colors based on hours
        const workingHoursEl = document.getElementById('calcWorkingHours');
        const earnedEl = document.getElementById('calcEarnedAmount');
        
        if (workingHours >= 8) {
            workingHoursEl.className = 'fw-bold text-success d-block';
            earnedEl.className = 'fw-bold text-success d-block';
        } else if (workingHours >= 7) {
            workingHoursEl.className = 'fw-bold text-warning d-block';
            earnedEl.className = 'fw-bold text-warning d-block';
        } else {
            workingHoursEl.className = 'fw-bold text-danger d-block';
            earnedEl.className = 'fw-bold text-danger d-block';
        }
    } else {
        document.getElementById('calcWorkingHours').textContent = '0.00';
        document.getElementById('calcEarnedAmount').textContent = '₱0.00';
    }
}

// Auto-hide alerts after 3 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.auto-hide-alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 3000);
    });

    // Add calculation event listeners
    ['time_in', 'time_out', 'breaktime_in', 'breaktime_out', 'daily_rate'].forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('change', calculateAttendance);
            element.addEventListener('input', calculateAttendance);
        }
    });

    // Initial calculation
    calculateAttendance();

    // Time validation
    document.getElementById('time_in').addEventListener('change', function() {
        const timeIn = this.value;
        const timeOut = document.getElementById('time_out').value;
        
        if (timeIn && timeOut && timeIn >= timeOut) {
            document.getElementById('time_out').value = '';
            alert('Time Out must be after Time In');
        }
        calculateAttendance();
    });
    
    document.getElementById('time_out').addEventListener('change', function() {
        const timeIn = document.getElementById('time_in').value;
        const timeOut = this.value;
        
        if (timeIn && timeOut && timeOut <= timeIn) {
            this.value = '';
            alert('Time Out must be after Time In');
        }
        calculateAttendance();
    });
});
</script>
@endsection