@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="mx-auto" style="max-width: 800px;">
        
        <!-- Header -->
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('admin.employees.index') }}" class="btn btn-outline-secondary me-3">
                <i class="bi bi-arrow-left"></i> Back to Employees
            </a>
            <h2 class="h4 fw-bold mb-0">Add New Employee</h2>
        </div>

        <div class="row g-4">
            <!-- Form Card -->
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.employees.store') }}">
                            @csrf

                            <!-- Name -->
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">Employee Name</label>
                                <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name') }}" required autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email Address</label>
                                <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Role -->
                            <div class="mb-4">
                                <label for="role" class="form-label fw-semibold">Role</label>
                                <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
                                    <option value="">Select Role</option>
                                    <option value="employee" {{ old('role') == 'employee' ? 'selected' : '' }}>Employee</option>
                                    <option value="hr" {{ old('role') == 'hr' ? 'selected' : '' }}>HR</option>
                                    <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send"></i> Add Employee & Send Invitation
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Information Card -->
            <div class="col-md-4">
                <div class="card bg-light border-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-info-circle text-primary me-2 mt-1"></i>
                            <div>
                                <h5 class="card-title fw-bold text-primary mb-2">How it works</h5>
                                <ul class="list-unstyled small text-muted mb-0">
                                    <li class="mb-2">✅ Auto-generates unique employee ID</li>
                                    <li class="mb-2">📧 Sends email invitation automatically</li>
                                    <li class="mb-2">🔗 Employee clicks link to complete setup</li>
                                    <li class="mb-2">🔑 Sets password and profile info</li>
                                    <li class="mb-0">🚀 Ready to log in to the system</li>
                                </ul>
                            </div>
                        </div>
                        
                        <hr class="my-3">
                        
                        <div class="small">
                            <strong class="text-primary">Employee ID Format:</strong>
                            <div class="mt-1">
                                <span class="badge bg-primary bg-opacity-25 text-primary me-1">emp01, emp02...</span> Employee<br>
                                <span class="badge bg-success bg-opacity-25 text-success me-1">hr01, hr02...</span> HR<br>
                                <span class="badge bg-warning bg-opacity-25 text-warning me-1">m01, m02...</span> Manager
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection