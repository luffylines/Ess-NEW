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
                                    <i class="fas fa-clock me-1"></i>Time In
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
                                    <i class="fas fa-clock me-1"></i>Time Out
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