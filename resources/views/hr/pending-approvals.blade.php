@extends('layouts.app')

@section('title', 'Pending Attendance Approvals')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h3 mb-1">
                        <i class="fas fa-clock text-warning me-2"></i>
                        Pending Attendance Approvals
                    </h2>
                    <p class=" mb-0">Review and approve/reject employee attendance records</p>
                </div>
                <div>
                    <a href="{{ route('hr.management') }}" class="btn btn-outline-primary me-2">
                        <i class="fas fa-tachometer-alt me-2"></i>Management Dashboard
                    </a>
                    <a href="{{ route('hr.create-for-employee.form') }}" class="btn btn-success">
                        <i class="fas fa-user-plus me-2"></i>Create for Employee
                    </a>
                </div>
            </div>
        </div>
    </div>

    @include('partials.flash-messages')

    <!-- Filter Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('hr.pending-approvals') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="employee_name" class="form-label">Employee Name</label>
                    <input type="text" class="form-control" id="employee_name" name="employee_name" 
                           value="{{ request('employee_name') }}" placeholder="Search by name...">
                </div>
                <div class="col-md-3">
                    <label for="date_from" class="form-label">Date From</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" 
                           value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label for="date_to" class="form-label">Date To</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" 
                           value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-clock fa-2x me-3"></i>
                        <div>
                            <h5 class="card-title mb-0">{{ $pendingAttendances->total() }}</h5>
                            <p class="card-text mb-0">Pending Records</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Attendance Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2"></i>Pending Attendance Records
            </h5>
        </div>
        <div class="card-body p-0">
            @if($pendingAttendances->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Employee</th>
                                <th>Date</th>
                                <th>Day Type</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                                <th>Status</th>
                                <th>Created By</th>
                                <th>Remarks</th>
                                <th>Submitted</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingAttendances as $attendance)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($attendance->user->profile_photo && file_exists(public_path('storage/' . $attendance->user->profile_photo)))
                                                <img src="{{ asset('storage/' . $attendance->user->profile_photo) }}" 
                                                     class="rounded-circle me-2" 
                                                     style="width: 32px; height: 32px; object-fit: cover; border: 2px solid #0d6efd;"
                                                     alt="{{ $attendance->user->name }}">
                                            @else
                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                     style="width: 32px; height: 32px; border: 2px solid #0d6efd;">
                                                    <span class="text-white fw-bold" style="font-size: 14px;">{{ substr($attendance->user->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                            <div>
                                                <span class="fw-medium">{{ $attendance->user->name }}</span>
                                                <br><small class="text-muted">ID: {{ $attendance->user->employee_id ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-medium">{{ $attendance->date->format('M d, Y') }}</span>
                                        <br><small class="text-muted">{{ $attendance->date->format('l') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($attendance->day_type ?? 'Regular') }}</span>
                                    </td>
                                    <td>
                                        @if($attendance->time_in)
                                            <span class="text-success fw-medium">
                                                {{ $attendance->time_in->format('h:i A') }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($attendance->time_out)
                                            <span class="text-danger fw-medium">
                                                {{ $attendance->time_out->format('h:i A') }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $status = 'Unknown';
                                            if (!$attendance->time_in && !$attendance->time_out) {
                                                $status = 'Absent';
                                                $badgeClass = 'bg-secondary';
                                            } elseif ($attendance->time_in && $attendance->time_out) {
                                                $status = 'Present';
                                                $badgeClass = 'bg-success';
                                            } elseif ($attendance->time_in && !$attendance->time_out) {
                                                $status = 'Incomplete';
                                                $badgeClass = 'bg-warning';
                                            }
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ $status }}</span>
                                    </td>
                                    <td>
                                        @if($attendance->createdByUser)
                                            <span class="fw-medium">{{ $attendance->createdByUser->name }}</span>
                                            @if($attendance->user_id === $attendance->created_by)
                                                <br><small class="text-success">
                                                    <i class="fas fa-user-check me-1"></i>Self-marked
                                                </small>
                                            @else
                                                <br><small class="text-warning">
                                                    <i class="fas fa-user-cog me-1"></i>Created by {{ $attendance->createdByUser->role }}
                                                </small>
                                            @endif
                                        @else
                                            <span class="text-muted">System</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($attendance->remarks)
                                            <span title="{{ $attendance->remarks }}">
                                                {{ Str::limit($attendance->remarks, 30) }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-medium">{{ $attendance->created_at->format('M d, h:i A') }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <!-- Approve Button -->
                                            <form action="{{ route('hr.approve', $attendance->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm" 
                                                        onclick="return confirm('Are you sure you want to approve this attendance record?')">
                                                    <i class="fas fa-check me-1"></i>Approve
                                                </button>
                                            </form>
                                            
                                            <!-- Reject Button -->
                                            <button type="button" class="btn btn-danger btn-sm" 
                                                    data-bs-toggle="modal" data-bs-target="#rejectModal{{ $attendance->id }}">
                                                <i class="fas fa-times me-1"></i>Reject
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectModal{{ $attendance->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('hr.reject', $attendance->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Reject Attendance</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to reject the attendance record for 
                                                       <strong>{{ $attendance->user->name }}</strong> on 
                                                       <strong>{{ $attendance->date->format('M d, Y') }}</strong>?</p>
                                                    
                                                    <div class="mb-3">
                                                        <label for="rejection_reason{{ $attendance->id }}" class="form-label">
                                                            Reason for Rejection <span class="text-danger">*</span>
                                                        </label>
                                                        <textarea class="form-control" id="rejection_reason{{ $attendance->id }}" 
                                                                  name="rejection_reason" rows="3" required
                                                                  placeholder="Please provide a reason for rejecting this attendance..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="fas fa-times me-1"></i>Reject
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            Showing {{ $pendingAttendances->firstItem() }} to {{ $pendingAttendances->lastItem() }} 
                            of {{ $pendingAttendances->total() }} results
                        </div>
                        {{ $pendingAttendances->withQueryString()->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                    <h5>No Pending Approvals</h5>
                    <p class="text-muted">All attendance records have been processed.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
    font-size: 0.875rem;
}

.btn-group .btn {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

.badge {
    font-size: 0.75rem;
    font-weight: 500;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.modal-title {
    color: #495057;
    font-weight: 600;
}
</style>
@endsection