@extends('layouts.app')

@section('content')
<style>
    /* Table Row Hover Effect (Lift) */
    .employee-row {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .employee-row:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        background-color: #f8f9fa;
    }

    /* Button Styling */
    .btn-outline-primary:hover { background-color: #0d6efd; color: #fff; }
    .btn-outline-danger:hover { background-color: #dc3545; color: #fff; }
    .btn-outline-secondary:hover { background-color: #6c757d; color: #fff; }

    /* Status Badges */
    .badge-success { background-color: #28a745 !important; }
    .badge-warning { background-color: #ffc107 !important; color: #000 !important; }
</style>

<div class="container-fluid py-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0 text-primary">Employees Management</h2>
        <a href="{{ route('admin.employees.create') }}" class="btn btn-success fw-semibold d-flex align-items-center gap-2 shadow-sm">
            <i class="bi bi-person-plus-fill"></i> Add Employee
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    <!-- Error Message -->
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    <!-- Employees Table -->
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-gradient bg-primary text-white fw-semibold">
            Employees List
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                            <tr class="employee-row">
                                <td class="fw-semibold">{{ $employee->employee_id ?? $employee->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($employee->profile_photo)
                                            <img src="{{ asset('storage/' . $employee->profile_photo) }}"
                                                 alt="Profile" class="rounded-circle me-2 shadow-sm" width="40" height="40">
                                        @else
                                            <img src="{{ asset('img/default-avatar.png') }}"
                                                 alt="Default" class="rounded-circle me-2 shadow-sm" width="40" height="40">
                                        @endif
                                        <div>
                                            <span class="fw-semibold">{{ $employee->name }}</span><br>
                                            <small class="text-muted">{{ ucfirst($employee->role) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $employee->email }}</td>
                                <td class="text-capitalize">{{ $employee->role }}</td>
                                <td>{{ $employee->phone ?? '—' }}</td>
                                <td>
                                    @if($employee->remember_token)
                                        <span class="badge badge-warning">Pending Setup</span>
                                    @else
                                        <span class="badge badge-success">Active</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.employees.edit', $employee->id) }}"
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $employee->id }}">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                        @if($employee->remember_token)
                                            <button type="button" class="btn btn-outline-secondary btn-sm"
                                                    onclick="resendInvitation({{ $employee->id }}, '{{ $employee->name }}')">
                                                <i class="bi bi-envelope-fill"></i> Resend
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            <!-- Delete Confirmation Modal -->
                            <div class="modal fade" id="deleteModal{{ $employee->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete <strong>{{ $employee->name }}</strong> ({{ $employee->email }})?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <form action="{{ route('admin.employees.destroy', $employee->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    No employees found.
                                    <a href="{{ route('admin.employees.create') }}" class="text-primary text-decoration-none">
                                        Add your first employee
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- JS -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
function resendInvitation(employeeId, employeeName) {
    if (!confirm(`Resend invitation to ${employeeName}?`)) return;

    fetch(`/admin/employees/${employeeId}/resend`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message || 'Error occurred.');
    })
    .catch(() => alert('Something went wrong while resending the invitation.'));
}
// Auto-dismiss alerts after 3 seconds
document.addEventListener('DOMContentLoaded', () => {
    const alert = document.querySelector('.alert-dismissible');
    if (alert) {
        // Automatically dismiss after 3 seconds (3000ms)
        setTimeout(() => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert.close();
        }, 3000);
    }
});
</script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
@endsection
