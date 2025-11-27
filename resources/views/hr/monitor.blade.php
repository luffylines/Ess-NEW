@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2 fw-bold mb-1">
                            <i class="fas fa-chart-line text-primary me-2"></i>Attendance Monitoring
                        </h1>
                        <p class="mb-0">Monitor and manage employee attendance records</p>
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

        <!-- Filter Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-filter me-2"></i>Filter Attendance Records
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('hr.monitor') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="date_from" class="form-label">
                            <i class="fas fa-calendar me-1"></i>From Date
                        </label>
                        <input type="date" name="date_from" id="date_from" 
                               value="{{ request('date_from') }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="date_to" class="form-label">
                            <i class="fas fa-calendar me-1"></i>To Date
                        </label>
                        <input type="date" name="date_to" id="date_to" 
                               value="{{ request('date_to') }}" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label for="user_id" class="form-label">
                            <i class="fas fa-user me-1"></i>Employee
                        </label>
                        <select name="user_id" id="user_id" class="form-select">
                            <option value="">All Employees</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" @selected(request('user_id') == $u->id)>
                                    {{ $u->name }} ({{ $u->employee_id }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Attendance Table -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-table me-2"></i>Attendance Records
                </h5>
            </div>
            <div class="card-body p-0">
                @if($attendances->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th><i class="fas fa-user me-1"></i>Employee</th>
                                    <th><i class="fas fa-calendar me-1"></i>Date</th>
                                    <th><i class="fas fa-sign-in-alt me-1"></i>Time In</th>
                                    <th><i class="fas fa-sign-out-alt me-1"></i>Time Out</th>
                                    <th><i class="fas fa-coffee me-1"></i>Break In</th>
                                    <th><i class="fas fa-coffee me-1"></i>Break Out</th>
                                    <th><i class="fas fa-clock me-1"></i>Hours</th>
                                    <th><i class="fas fa-check-circle me-1"></i>Approval Status</th>
                                    <th><i class="fas fa-info-circle me-1"></i>Attendance Status</th>
                                    <th><i class="fas fa-cogs me-1"></i>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendances as $att)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" 
                                                     style="width: 32px; height: 32px; font-size: 14px;">
                                                    {{ substr($att->user->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <span class="fw-medium">{{ $att->user->name }}</span>
                                                    <br><small class="text-muted">{{ $att->user->employee_id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-medium">{{ $att->date->format('M d, Y') }}</span>
                                            <br><small class="text-muted">{{ $att->date->format('D') }}</small>
                                        </td>
                                        <td>
                                            @if($att->time_in)
                                                <span class="text-success fw-medium">
                                                    <i class="fas fa-sign-in-alt me-1"></i>{{ $att->time_in->format('h:i A') }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($att->time_out)
                                                <span class="text-danger fw-medium">
                                                    <i class="fas fa-sign-out-alt me-1"></i>{{ $att->time_out->format('h:i A') }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($att->breaktime_in)
                                                <span class="text-warning fw-medium">
                                                    <i class="fas fa-coffee me-1"></i>{{ $att->breaktime_in->format('h:i A') }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($att->breaktime_out)
                                                <span class="text-info fw-medium">
                                                    <i class="fas fa-coffee me-1"></i>{{ $att->breaktime_out->format('h:i A') }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($att->total_hours)
                                                <span class="fw-medium {{ $att->total_hours >= 8 ? 'text-success' : ($att->total_hours >= 7 ? 'text-warning' : 'text-danger') }}">
                                                    <i class="fas fa-clock me-1"></i>{{ number_format($att->total_hours, 2) }}h
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($att->status === 'approved')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>Approved
                                                </span>
                                            @elseif($att->status === 'rejected')
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times me-1"></i>Rejected
                                                </span>
                                            @else
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-hourglass-half me-1"></i>Pending
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($att->time_in && $att->time_out)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>Present
                                                </span>
                                            @elseif($att->time_in && !$att->time_out)
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-clock me-1"></i>Incomplete
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times me-1"></i>Absent
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <form method="POST" action="{{ route('hr.attendance.delete', $att->id) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" 
                                                            onclick="return confirm('Are you sure you want to delete this attendance record? This action cannot be undone.')"
                                                            title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No attendance records found</h5>
                        <p class="text-muted">Try adjusting your filter criteria</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Pagination -->
        @if($attendances->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $attendances->withQueryString()->links() }}
            </div>
        @endif
    </div>

    <style>
    .avatar {
        font-weight: bold;
    }
    
    .btn-group .btn {
        margin-right: 2px;
    }
    
    .btn-group .btn:last-child {
        margin-right: 0;
    }
    
    .table th {
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .card {
        border: none;
        border-radius: 10px;
    }
    
    .card-header {
        border-radius: 10px 10px 0 0 !important;
    }
    </style>
@endsection
