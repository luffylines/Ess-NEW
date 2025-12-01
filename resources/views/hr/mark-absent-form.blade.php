@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @include('partials.flash-messages')

            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-user-times me-2"></i>
                        Mark Employee as Absent
                    </h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        You are marking <strong>{{ $employee->name }}</strong> as absent for <strong>{{ \Carbon\Carbon::parse($date)->format('F j, Y') }}</strong>
                    </div>

                    <form action="{{ route('hr.mark') }}" method="POST">
                        @csrf
                        <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                        <input type="hidden" name="date" value="{{ $date }}">
                        <input type="hidden" name="status" value="absent">

                        <div class="mb-4">
                            <label for="remarks" class="form-label">
                                <i class="fas fa-comment me-1"></i>Reason for Absence <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('remarks') is-invalid @enderror" 
                                      id="remarks" 
                                      name="remarks" 
                                      rows="4" 
                                      placeholder="Please provide a reason for marking this employee as absent..."
                                      required>{{ old('remarks') }}</textarea>
                            @error('remarks')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">This reason will be recorded in the attendance log</div>
                        </div>

                        <div class="alert alert-danger">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Important:</strong> This action will mark the employee as absent for the entire day. 
                            Make sure this is correct before proceeding.
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('hr.management') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                            </a>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-user-times me-1"></i>Mark as Absent
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
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

.btn-danger {
    background: linear-gradient(135deg, #dc3545 0%, #e74c3c 100%);
    border: none;
    border-radius: 8px;
    padding: 12px 24px;
    font-weight: 500;
}

.btn-danger:hover {
    background: linear-gradient(135deg, #c82333 0%, #d63031 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

.btn-secondary {
    border-radius: 8px;
    padding: 12px 24px;
    font-weight: 500;
}

.alert-warning {
    border-radius: 10px;
    border: none;
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
}

.alert-danger {
    border-radius: 10px;
    border: none;
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
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