@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Edit Employee</h4>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Full Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $employee->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $employee->email) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Role</label>
                    <select name="role" class="form-select" required>
                        <option value="employee" {{ $employee->role == 'employee' ? 'selected' : '' }}>Employee</option>
                        <option value="hr" {{ $employee->role == 'hr' ? 'selected' : '' }}>HR</option>
                        <option value="manager" {{ $employee->role == 'manager' ? 'selected' : '' }}>Manager</option>
                        <option value="admin" {{ $employee->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Phone</label>
                    <input type="tel" id="phone" name="phone" class="form-control" 
                           value="{{ old('phone', $employee->phone) }}" 
                           placeholder="+639XXXXXXXXX"
                           pattern="^\+63[0-9]{10}$">
                    <div class="">
                        Format: +63 followed by 10 digits (e.g., +639171234567)
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const phoneInput = document.getElementById('phone');
    
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value;
            
            // Remove all non-digit characters except +
            value = value.replace(/[^\d+]/g, '');
            
            // Auto-format based on input
            if (value.length > 0 && !value.startsWith('+63')) {
                if (value.startsWith('63')) {
                    value = '+' + value;
                } else if (value.startsWith('09')) {
                    value = '+63' + value.substring(1);
                } else if (value.startsWith('9') && value.length <= 10) {
                    value = '+63' + value;
                } else if (!value.startsWith('+')) {
                    value = '+639' + value.replace(/^0+/, '');
                }
            }
            
            // Limit to +63 + 10 digits
            if (value.startsWith('+63') && value.length > 13) {
                value = value.substring(0, 13);
            }
            
            e.target.value = value;
        });
        
        phoneInput.addEventListener('blur', function(e) {
            let value = e.target.value;
            if (value && !value.match(/^\+63[0-9]{10}$/)) {
                e.target.classList.add('is-invalid');
            } else {
                e.target.classList.remove('is-invalid');
            }
        });
    }
});
</script>
@endsection
