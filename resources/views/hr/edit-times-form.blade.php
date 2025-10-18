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
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Edit Attendance Times
                    </h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        You are editing attendance times for <strong>{{ $attendance->user->name }}</strong> on <strong>{{ \Carbon\Carbon::parse($attendance->date)->format('F j, Y') }}</strong>
                    </div>

                    <form action="{{ route('hr.edit-employee', $attendance->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="time_in" class="form-label">
                                    <i class="fas fa-clock me-1"></i>Time In
                                </label>
                                <input type="time" 
                                       class="form-control @error('time_in') is-invalid @enderror" 
                                       id="time_in" 
                                       name="time_in" 
                                       value="{{ old('time_in', $attendance->time_in ? $attendance->time_in->format('H:i') : '') }}">
                                @error('time_in')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="time_out" class="form-label">
                                    <i class="fas fa-clock me-1"></i>Time Out
                                </label>
                                <input type="time" 
                                       class="form-control @error('time_out') is-invalid @enderror" 
                                       id="time_out" 
                                       name="time_out" 
                                       value="{{ old('time_out', $attendance->time_out ? $attendance->time_out->format('H:i') : '') }}">
                                @error('time_out')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Leave empty if employee hasn't clocked out yet</div>
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
                                      placeholder="Enter any remarks about this attendance modification...">{{ old('remarks', $attendance->remarks) }}</textarea>
                            @error('remarks')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Current Information Display -->
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fas fa-info-circle me-1"></i>Current Information
                                </h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <small class="text-muted">Current Time In:</small><br>
                                        <strong>{{ $attendance->time_in ? $attendance->time_in->format('h:i A') : 'Not set' }}</strong>
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted">Current Time Out:</small><br>
                                        <strong>{{ $attendance->time_out ? $attendance->time_out->format('h:i A') : 'Not set' }}</strong>
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted">Status:</small><br>
                                        <span class="badge bg-{{ $attendance->status === 'present' ? 'success' : ($attendance->status === 'absent' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($attendance->status) }}
                                        </span>
                                    </div>
                                </div>
                                @if($attendance->remarks)
                                <div class="mt-2">
                                    <small class="text-muted">Current Remarks:</small><br>
                                    <em>{{ $attendance->remarks }}</em>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('hr.management') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Update Attendance
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
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);
    border: none;
    border-radius: 8px;
    padding: 12px 24px;
    font-weight: 500;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #0b5ed7 0%, #5a0fc8 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
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

.bg-light {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
    border-radius: 10px;
}
</style>

<script>
// Auto-hide alerts after 3 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.auto-hide-alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 3000);
    });
});
</script>
@endsection