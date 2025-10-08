@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0">Employees Management</h2>
        <a href="{{ route('admin.employees.create') }}" class="btn btn-success fw-semibold d-flex align-items-center gap-2">
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

    <!-- Employees Table -->
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-primary text-white fw-semibold">
            Employees List
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th scope="col">Employee ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Role</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                            <tr>
                                <td class="fw-semibold">{{ $employee->employee_id ?? $employee->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($employee->profile_photo)
                                            <img src="{{ asset('storage/profile_photos/' . $employee->profile_photo) }}" 
                                                 alt="Profile" class="rounded-circle me-2" width="40" height="40">
                                        @else
                                            <img src="{{ asset('img/default-avatar.png') }}" 
                                                 alt="Default" class="rounded-circle me-2" width="40" height="40">
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
                                        <span class="badge bg-warning text-dark">Pending Setup</span>
                                    @else
                                        <span class="badge bg-success">Active</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <!-- Edit -->
                                        <a href="{{ route('admin.employees.edit', $employee->id) }}" 
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>

                                        <!-- Delete -->
                                        <button type="button" class="btn btn-outline-danger btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal{{ $employee->id }}">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>

                                        <!-- Resend Invitation -->
                                        @if($employee->remember_token)
                                            <button type="button" class="btn btn-outline-secondary btn-sm" 
                                                    onclick="resendInvitation({{ $employee->id }})">
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
<script>
    function resendInvitation(employeeId) {
        alert('Resend invitation for Employee ID: ' + employeeId);
    }
</script>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
@endsection
