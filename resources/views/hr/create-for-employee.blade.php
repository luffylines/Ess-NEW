@extends('layouts.app')

@section('title', 'Create Employee Attendance')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h3 mb-1">
                        <i class="fas fa-user-plus text-primary me-2"></i>
                        Create Employee Attendance
                    </h2>
                    <p class="text-muted mb-0">Create attendance records for employees (HR/Manager Only)</p>
                </div>
                <div>
                    <a href="{{ route('hr.management') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Please correct the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Main Form Card -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-plus me-2"></i>
                        Create New Attendance Record
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('hr.createForEmployee') }}" id="createAttendanceForm">
                        @csrf
                        
                        <!-- Employee Selection -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="employee_id" class="form-label fw-bold">
                                    <i class="fas fa-user me-1"></i>Select Employee <span class="text-danger">*</span>
                                </label>
                                <select name="employee_id" id="employee_id" class="form-select form-select-lg" required>
                                    <option value="">Choose an employee...</option>
                                    @foreach(\App\Models\User::where('role', 'employee')->orderBy('name')->get() as $employee)
                                        <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->name }} ({{ $employee->employee_id }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">Select the employee for whom you want to create attendance</div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="date" class="form-label fw-bold">
                                    <i class="fas fa-calendar me-1"></i>Date <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="date" id="date" class="form-control form-control-lg" 
                                       value="{{ old('date', date('Y-m-d')) }}" required max="{{ date('Y-m-d') }}">
                                <div class="form-text">Cannot create attendance for future dates</div>
                            </div>
                        </div>

                        <!-- Time Settings -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="time_in" class="form-label fw-bold">
                                    <i class="fas fa-clock me-1"></i>Time In
                                </label>
                                <input type="time" name="time_in" id="time_in" class="form-control form-control-lg" 
                                       value="{{ old('time_in', '08:00') }}">
                                <div class="form-text">Leave empty if employee was absent</div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="time_out" class="form-label fw-bold">
                                    <i class="fas fa-clock me-1"></i>Time Out
                                </label>
                                <input type="time" name="time_out" id="time_out" class="form-control form-control-lg" 
                                       value="{{ old('time_out', '17:00') }}">
                                <div class="form-text">Leave empty if employee didn't time out</div>
                            </div>
                        </div>

                        <!-- Day Type and Status -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="day_type" class="form-label fw-bold">
                                    <i class="fas fa-tags me-1"></i>Day Type <span class="text-danger">*</span>
                                </label>
                                <select name="day_type" id="day_type" class="form-select form-select-lg" required>
                                    <option value="regular" {{ old('day_type', 'regular') == 'regular' ? 'selected' : '' }}>Regular Day</option>
                                    <option value="holiday" {{ old('day_type') == 'holiday' ? 'selected' : '' }}>Holiday</option>
                                    <option value="rest_day" {{ old('day_type') == 'rest_day' ? 'selected' : '' }}>Rest Day</option>
                                    <option value="overtime" {{ old('day_type') == 'overtime' ? 'selected' : '' }}>Overtime</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="attendance_status" class="form-label fw-bold">
                                    <i class="fas fa-check-circle me-1"></i>Attendance Status
                                </label>
                                <select name="attendance_status" id="attendance_status" class="form-select form-select-lg">
                                    <option value="present" {{ old('attendance_status', 'present') == 'present' ? 'selected' : '' }}>Present</option>
                                    <option value="absent" {{ old('attendance_status') == 'absent' ? 'selected' : '' }}>Absent</option>
                                    <option value="late" {{ old('attendance_status') == 'late' ? 'selected' : '' }}>Late</option>
                                </select>
                            </div>
                        </div>

                        <!-- Remarks -->
                        <div class="mb-4">
                            <label for="remarks" class="form-label fw-bold">
                                <i class="fas fa-comment me-1"></i>Remarks <span class="text-danger">*</span>
                            </label>
                            <textarea name="remarks" id="remarks" class="form-control" rows="4" required 
                                      placeholder="Provide a detailed reason for creating this attendance record...">{{ old('remarks') }}</textarea>
                            <div class="form-text">
                                <strong>Examples:</strong> Employee forgot to mark attendance, System was down, Working from home, Medical emergency, etc.
                            </div>
                        </div>

                        <!-- Quick Reason Buttons -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-bolt me-1"></i>Quick Reason Templates
                            </label>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm quick-reason" 
                                        data-reason="Employee forgot to mark attendance as reported">
                                    Forgot to Mark
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm quick-reason" 
                                        data-reason="System was temporarily unavailable">
                                    System Issue
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm quick-reason" 
                                        data-reason="Working from home - attendance confirmed by supervisor">
                                    Work from Home
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm quick-reason" 
                                        data-reason="Employee was in field work - confirmed by manager">
                                    Field Work
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm quick-reason" 
                                        data-reason="Medical emergency - attendance manually recorded">
                                    Medical Emergency
                                </button>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('hr.management') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </a>
                                    
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-outline-primary" id="previewBtn">
                                            <i class="fas fa-eye me-2"></i>Preview
                                        </button>
                                        <button type="submit" class="btn btn-success btn-lg">
                                            <i class="fas fa-save me-2"></i>Create Attendance Record
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Help Card -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Help & Guidelines
                    </h6>
                </div>
                <div class="card-body">
                    <div class="help-section mb-3">
                        <h6 class="fw-bold text-primary">When to Create Attendance:</h6>
                        <ul class="small">
                            <li>Employee forgot to mark attendance</li>
                            <li>System technical issues</li>
                            <li>Remote work situations</li>
                            <li>Emergency situations</li>
                            <li>Field work assignments</li>
                        </ul>
                    </div>
                    
                    <div class="help-section mb-3">
                        <h6 class="fw-bold text-warning">Important Notes:</h6>
                        <ul class="small">
                            <li>All created records require approval</li>
                            <li>Detailed remarks are mandatory</li>
                            <li>Cannot create future date records</li>
                            <li>Times can be left empty for absent days</li>
                        </ul>
                    </div>
                    
                    <div class="help-section">
                        <h6 class="fw-bold text-success">Best Practices:</h6>
                        <ul class="small">
                            <li>Verify employee request before creating</li>
                            <li>Use descriptive remarks</li>
                            <li>Check for existing records first</li>
                            <li>Notify employee of record creation</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-history me-2"></i>Recent Created Records
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $recentRecords = \App\Models\Attendance::with('user')
                            ->where('created_by', Auth::id())
                            ->where('created_at', '>=', now()->subDays(7))
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp
                    
                    @if($recentRecords->count() > 0)
                        @foreach($recentRecords as $record)
                            <div class="recent-item mb-2 p-2 border rounded">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $record->user->name }}</strong>
                                    <small class="text-muted">{{ $record->date->format('M d') }}</small>
                                </div>
                                <small class="text-muted">{{ Str::limit($record->remarks, 50) }}</small>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted small">No recent records created</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-eye me-2"></i>Preview Attendance Record
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Employee:</strong>
                        <p id="preview-employee" class="text-muted"></p>
                    </div>
                    <div class="col-md-6">
                        <strong>Date:</strong>
                        <p id="preview-date" class="text-muted"></p>
                    </div>
                    <div class="col-md-6">
                        <strong>Time In:</strong>
                        <p id="preview-time-in" class="text-muted"></p>
                    </div>
                    <div class="col-md-6">
                        <strong>Time Out:</strong>
                        <p id="preview-time-out" class="text-muted"></p>
                    </div>
                    <div class="col-md-6">
                        <strong>Day Type:</strong>
                        <p id="preview-day-type" class="text-muted"></p>
                    </div>
                    <div class="col-md-6">
                        <strong>Status:</strong>
                        <p id="preview-status" class="text-muted"></p>
                    </div>
                    <div class="col-12">
                        <strong>Remarks:</strong>
                        <p id="preview-remarks" class="text-muted"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="confirmCreate">
                    <i class="fas fa-save me-2"></i>Create Record
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.form-control-lg, .form-select-lg {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-control-lg:focus, .form-select-lg:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.quick-reason {
    border-radius: 20px;
    transition: all 0.3s ease;
}

.quick-reason:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.help-section ul {
    margin-bottom: 0;
}

.recent-item {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border: 1px solid #e9ecef !important;
}

.modal-content {
    border-radius: 15px;
    border: none;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
}

@media (max-width: 768px) {
    .d-flex.gap-2 {
        flex-direction: column;
    }
    
    .quick-reason {
        width: 100%;
        margin-bottom: 5px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quick reason buttons
    document.querySelectorAll('.quick-reason').forEach(button => {
        button.addEventListener('click', function() {
            const reason = this.getAttribute('data-reason');
            const remarksTextarea = document.getElementById('remarks');
            
            if (remarksTextarea.value.trim() === '') {
                remarksTextarea.value = reason;
            } else {
                remarksTextarea.value += '\n\n' + reason;
            }
            
            // Highlight button briefly
            this.classList.add('btn-primary');
            this.classList.remove('btn-outline-secondary');
            setTimeout(() => {
                this.classList.remove('btn-primary');
                this.classList.add('btn-outline-secondary');
            }, 1000);
        });
    });
    
    // Time validation
    document.getElementById('time_in').addEventListener('change', function() {
        const timeIn = this.value;
        const timeOut = document.getElementById('time_out').value;
        
        if (timeIn && timeOut && timeIn >= timeOut) {
            document.getElementById('time_out').value = '';
            alert('Time Out must be after Time In');
        }
    });
    
    document.getElementById('time_out').addEventListener('change', function() {
        const timeIn = document.getElementById('time_in').value;
        const timeOut = this.value;
        
        if (timeIn && timeOut && timeOut <= timeIn) {
            this.value = '';
            alert('Time Out must be after Time In');
        }
    });
    
    // Attendance status auto-update
    document.getElementById('attendance_status').addEventListener('change', function() {
        const status = this.value;
        const timeIn = document.getElementById('time_in');
        const timeOut = document.getElementById('time_out');
        
        if (status === 'absent') {
            timeIn.value = '';
            timeOut.value = '';
            timeIn.disabled = true;
            timeOut.disabled = true;
        } else {
            timeIn.disabled = false;
            timeOut.disabled = false;
            if (!timeIn.value) timeIn.value = '08:00';
            if (!timeOut.value && status === 'present') timeOut.value = '17:00';
        }
    });
    
    // Preview functionality
    document.getElementById('previewBtn').addEventListener('click', function() {
        const employeeSelect = document.getElementById('employee_id');
        const employeeName = employeeSelect.options[employeeSelect.selectedIndex].text;
        
        document.getElementById('preview-employee').textContent = employeeName || 'Not selected';
        document.getElementById('preview-date').textContent = document.getElementById('date').value || 'Not set';
        document.getElementById('preview-time-in').textContent = document.getElementById('time_in').value || 'Not set';
        document.getElementById('preview-time-out').textContent = document.getElementById('time_out').value || 'Not set';
        document.getElementById('preview-day-type').textContent = document.getElementById('day_type').value || 'Not set';
        document.getElementById('preview-status').textContent = document.getElementById('attendance_status').value || 'Not set';
        document.getElementById('preview-remarks').textContent = document.getElementById('remarks').value || 'No remarks';
        
        new bootstrap.Modal(document.getElementById('previewModal')).show();
    });
    
    // Confirm create from preview
    document.getElementById('confirmCreate').addEventListener('click', function() {
        document.getElementById('createAttendanceForm').submit();
    });
    
    // Form validation
    document.getElementById('createAttendanceForm').addEventListener('submit', function(e) {
        const employee = document.getElementById('employee_id').value;
        const date = document.getElementById('date').value;
        const remarks = document.getElementById('remarks').value.trim();
        
        if (!employee) {
            e.preventDefault();
            alert('Please select an employee');
            return false;
        }
        
        if (!date) {
            e.preventDefault();
            alert('Please select a date');
            return false;
        }
        
        if (!remarks) {
            e.preventDefault();
            alert('Please provide remarks explaining why this record is being created');
            return false;
        }
        
        if (remarks.length < 10) {
            e.preventDefault();
            alert('Please provide more detailed remarks (at least 10 characters)');
            return false;
        }
    });
});
</script>
@endsection