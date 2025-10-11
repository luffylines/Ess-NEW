@extends('layouts.app')

@section('title', 'Attendance Management Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h3 mb-1">
                        <i class="fas fa-tachometer-alt text-primary me-2"></i>
                        Attendance Management Dashboard
                    </h2>
                    <p class=" mb-0">Manage employee attendance and track daily presence</p>
                </div>
                <div>
                    <a href="{{ route('hr.pending-approvals') }}" class="btn btn-warning">
                        <i class="fas fa-clock me-2"></i>Pending Approvals 
                        @if($stats['pending_approvals'] > 0)
                            <span class="badge bg-light text-dark ms-1">{{ $stats['pending_approvals'] }}</span>
                        @endif
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

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-users fa-2x me-3"></i>
                        <div>
                            <h4 class="card-title mb-0">{{ $stats['total_employees'] }}</h4>
                            <p class="card-text mb-0">Total Employees</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-check fa-2x me-3"></i>
                        <div>
                            <h4 class="card-title mb-0">{{ $stats['present_today'] }}</h4>
                            <p class="card-text mb-0">Present Today</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-danger text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-times fa-2x me-3"></i>
                        <div>
                            <h4 class="card-title mb-0">{{ $stats['missed_today'] }}</h4>
                            <p class="card-text mb-0">Missed Today</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-clock fa-2x me-3"></i>
                        <div>
                            <h4 class="card-title mb-0">{{ $stats['pending_approvals'] }}</h4>
                            <p class="card-text mb-0">Pending Approvals</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats & Actions -->
    <div class="card shadow-sm mb-4 bg-gradient-primary text-white">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="card-title mb-2">
                        <i class="fas fa-chart-line me-2"></i>Today's Summary
                    </h5>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="mb-0">{{ $stats['present_today'] }}/{{ $stats['total_employees'] }}</h4>
                                <small>Present Today</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="mb-0">{{ $stats['missed_today'] }}</h4>
                                <small>Missed Today</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="mb-0">{{ $stats['pending_approvals'] }}</h4>
                                <small>Pending Approvals</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="mb-0">{{ number_format(($stats['present_today'] / max($stats['total_employees'], 1)) * 100, 1) }}%</h4>
                                <small>Attendance Rate</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <div class="d-grid gap-2">
                        <a href="{{ route('hr.pending-approvals') }}" class="btn btn-warning btn-lg">
                            <i class="fas fa-clock me-2"></i>Review Approvals
                            @if($stats['pending_approvals'] > 0)
                                <span class="badge bg-light text-dark ms-2">{{ $stats['pending_approvals'] }}</span>
                            @endif
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Today's Attendance Overview -->
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header ">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-day me-2"></i>Today's Attendance - {{ $today->format('F d, Y') }}
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($employees->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Employee</th>
                                        <th>Status</th>
                                        <th>Time In</th>
                                        <th>Time Out</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($employees as $employee)
                                        @php
                                            $attendance = $todayAttendances[$employee->id] ?? null;
                                        @endphp
                                        <tr class="align-middle">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle me-3">
                                                        <span class="avatar-initials">{{ substr($employee->name, 0, 1) }}</span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold">{{ $employee->name }}</h6>
                                                        <small class="text-muted">ID: {{ $employee->employee_id ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($attendance)
                                                    @if($attendance->time_in && $attendance->time_out)
                                                        <span class="status-badge status-present">
                                                            <i class="fas fa-check me-1"></i>Present
                                                        </span>
                                                    @elseif($attendance->time_in)
                                                        <span class="status-badge status-partial">
                                                            <i class="fas fa-clock me-1"></i>Time In Only
                                                        </span>
                                                    @else
                                                        <span class="status-badge status-absent">
                                                            <i class="fas fa-times me-1"></i>Absent
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="status-badge status-missing">
                                                        <i class="fas fa-exclamation me-1"></i>Not Marked
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($attendance && $attendance->time_in)
                                                    <div class="time-display time-in">
                                                        <i class="fas fa-sign-in-alt me-1"></i>
                                                        <span>{{ $attendance->time_in->format('h:i A') }}</span>
                                                    </div>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($attendance && $attendance->time_out)
                                                    <div class="time-display time-out">
                                                        <i class="fas fa-sign-out-alt me-1"></i>
                                                        <span>{{ $attendance->time_out->format('h:i A') }}</span>
                                                    </div>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if(!$attendance)
                                                    <!-- Mark Present/Absent Buttons -->
                                                    <div class="action-buttons">
                                                        <button type="button" class="btn btn-success btn-sm action-btn" 
                                                                data-bs-toggle="modal" data-bs-target="#markPresentModal{{ $employee->id }}">
                                                            <i class="fas fa-user-check me-1"></i>Mark Present
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-sm action-btn" 
                                                                data-bs-toggle="modal" data-bs-target="#markAbsentModal{{ $employee->id }}">
                                                            <i class="fas fa-user-times me-1"></i>Mark Absent
                                                        </button>
                                                    </div>
                                                @else
                                                    <!-- Edit Times Button -->
                                                    <button type="button" class="btn btn-primary btn-sm action-btn" 
                                                            data-bs-toggle="modal" data-bs-target="#editTimesModal{{ $attendance->id }}">
                                                        <i class="fas fa-edit me-1"></i>Edit Times
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>

                                        @if(!$attendance)
                                            <!-- Mark Present Modal -->
                                            <div class="modal fade" id="markPresentModal{{ $employee->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="{{ route('hr.mark') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                                                            <input type="hidden" name="date" value="{{ $today->format('Y-m-d') }}">
                                                            <input type="hidden" name="status" value="present">
                                                            
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Mark {{ $employee->name }} as Present</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-6 mb-3">
                                                                        <label for="time_in{{ $employee->id }}" class="form-label">Time In</label>
                                                                        <input type="time" class="form-control" id="time_in{{ $employee->id }}" 
                                                                               name="time_in" value="08:00">
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <label for="time_out{{ $employee->id }}" class="form-label">Time Out</label>
                                                                        <input type="time" class="form-control" id="time_out{{ $employee->id }}" 
                                                                               name="time_out" value="17:00">
                                                                    </div>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="remarks{{ $employee->id }}" class="form-label">Remarks</label>
                                                                    <textarea class="form-control" id="remarks{{ $employee->id }}" 
                                                                              name="remarks" rows="2" 
                                                                              placeholder="Reason for marking attendance..."></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-success">
                                                                    <i class="fas fa-user-check me-1"></i>Mark Present
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Mark Absent Modal -->
                                            <div class="modal fade" id="markAbsentModal{{ $employee->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="{{ route('hr.mark') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                                                            <input type="hidden" name="date" value="{{ $today->format('Y-m-d') }}">
                                                            <input type="hidden" name="status" value="absent">
                                                            
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Mark {{ $employee->name }} as Absent</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Are you sure you want to mark <strong>{{ $employee->name }}</strong> as absent for today?</p>
                                                                <div class="mb-3">
                                                                    <label for="absent_remarks{{ $employee->id }}" class="form-label">Remarks</label>
                                                                    <textarea class="form-control" id="absent_remarks{{ $employee->id }}" 
                                                                              name="remarks" rows="2" 
                                                                              placeholder="Reason for absence..."></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-danger">
                                                                    <i class="fas fa-user-times me-1"></i>Mark Absent
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <!-- Edit Times Modal -->
                                            <div class="modal fade" id="editTimesModal{{ $attendance->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="{{ route('hr.edit-employee', $attendance->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Edit Times for {{ $employee->name }}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-6 mb-3">
                                                                        <label for="edit_time_in{{ $attendance->id }}" class="form-label">Time In</label>
                                                                        <input type="time" class="form-control" id="edit_time_in{{ $attendance->id }}" 
                                                                               name="time_in" value="{{ $attendance->time_in ? $attendance->time_in->format('H:i') : '' }}">
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <label for="edit_time_out{{ $attendance->id }}" class="form-label">Time Out</label>
                                                                        <input type="time" class="form-control" id="edit_time_out{{ $attendance->id }}" 
                                                                               name="time_out" value="{{ $attendance->time_out ? $attendance->time_out->format('H:i') : '' }}">
                                                                    </div>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="edit_remarks{{ $attendance->id }}" class="form-label">Remarks</label>
                                                                    <textarea class="form-control" id="edit_remarks{{ $attendance->id }}" 
                                                                              name="remarks" rows="2">{{ $attendance->remarks }}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-primary">
                                                                    <i class="fas fa-save me-1"></i>Update
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state text-center py-5">
                            <div class="empty-icon mb-3">
                                <i class="fas fa-users text-muted"></i>
                            </div>
                            <h5 class="text-muted">No Employees Found</h5>
                            <p class="text-muted">No employees are available in the system.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions & Summary -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100 quick-actions-card">
                <div class="card-header bg-gradient-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="action-grid mb-4">
                        <a href="{{ route('hr.pending-approvals') }}" class="action-card pending">
                            <div class="action-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="action-content">
                                <h6>Pending Approvals</h6>
                                <span class="action-count">{{ $stats['pending_approvals'] }}</span>
                            </div>
                        </a>

                        <a href="{{ route('hr.create-for-employee.form') }}" class="action-card create">
                            <div class="action-icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="action-content">
                                <h6>Create Record</h6>
                                <span class="action-text">For Employee</span>
                            </div>
                        </a>

                        <a href="{{ route('hr.reports') }}" class="action-card reports">
                            <div class="action-icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div class="action-content">
                                <h6>View Reports</h6>
                                <span class="action-text">All Records</span>
                            </div>
                        </a>
                        
                        <div class="action-card export">
                            <div class="action-icon">
                                <i class="fas fa-download"></i>
                            </div>
                            <div class="action-content">
                                <h6>Export Data</h6>
                                <span class="action-text">PDF/Excel</span>
                            </div>
                        </div>
                    </div>

                    <div class="missed-employees-section">
                        <h6 class="section-title">
                            <i class="fas fa-exclamation-triangle me-2"></i>Employees Who Missed Today
                        </h6>
                        @if($missedAttendanceEmployees->count() > 0)
                            <div class="missed-list">
                                @foreach($missedAttendanceEmployees->take(4) as $employee)
                                    <div class="missed-item">
                                        <div class="missed-info">
                                            <div class="missed-avatar">
                                                <span>{{ substr($employee->name, 0, 1) }}</span>
                                            </div>
                                            <div class="missed-details">
                                                <span class="missed-name">{{ $employee->name }}</span>
                                                <small class="missed-id">ID: {{ $employee->employee_id ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                        <div class="missed-actions">
                                            <button type="button" class="btn btn-success btn-xs" 
                                                    data-bs-toggle="modal" data-bs-target="#markPresentModal{{ $employee->id }}"
                                                    title="Mark Present">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-xs" 
                                                    data-bs-toggle="modal" data-bs-target="#markAbsentModal{{ $employee->id }}"
                                                    title="Mark Absent">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                                @if($missedAttendanceEmployees->count() > 4)
                                    <div class="missed-more">
                                        <small class="text-muted">... and {{ $missedAttendanceEmployees->count() - 4 }} more employees</small>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="no-missed">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span>All employees have marked their attendance today!</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Background Gradients */
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #667eea 0%, #4facfe 100%);
}

/* Enhanced Cards */
.card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
}

/* Avatar Circles */
.avatar-circle {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 16px;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.avatar-initials {
    font-size: 16px;
    font-weight: 700;
}

/* Status Badges */
.status-badge {
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-flex;
    align-items: center;
}

.status-present {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
}

.status-partial {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}

.status-absent {
    background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
    color: #d63384;
}

.status-missing {
    background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
    color: #fd7e14;
}

/* Time Display */
.time-display {
    display: flex;
    align-items: center;
    padding: 6px 12px;
    border-radius: 10px;
    font-weight: 600;
}

.time-in {
    background-color: rgba(40, 167, 69, 0.1);
    color: #28a745;
}

.time-out {
    background-color: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

/* Action Buttons */
.action-btn {
    border-radius: 10px;
    font-weight: 600;
    padding: 8px 16px;
    margin: 2px;
    transition: all 0.3s ease;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.action-buttons {
    display: flex;
    gap: 5px;
    justify-content: center;
    flex-wrap: wrap;
}

/* Table Enhancements */
.table {
    border-radius: 15px;
    overflow: hidden;
}

.table th {
    border-top: none;
    font-weight: 700;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 15px;
}

.table td {
    padding: 15px;
    vertical-align: middle;
}

.table-hover tbody tr:hover {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
}

/* Quick Actions Card */
.quick-actions-card .card-header {
    border-radius: 15px 15px 0 0;
}

.action-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.action-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border: 2px solid transparent;
    border-radius: 12px;
    padding: 15px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 10px;
}

.action-card:hover {
    transform: translateY(-3px);
    border-color: #667eea;
    color: inherit;
    text-decoration: none;
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.2);
}

.action-card.pending {
    background: linear-gradient(135deg, #fff3cd 0%, #ffffff 100%);
}

.action-card.create {
    background: linear-gradient(135deg, #d4edda 0%, #ffffff 100%);
}

.action-card.missed {
    background: linear-gradient(135deg, #f8d7da 0%, #ffffff 100%);
}

.action-card.reports {
    background: linear-gradient(135deg, #d1ecf1 0%, #ffffff 100%);
}

.action-card.export {
    background: linear-gradient(135deg, #d4edda 0%, #ffffff 100%);
}

.action-icon {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 14px;
}

.action-content h6 {
    margin: 0;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #343a40;
}

.action-count {
    font-size: 18px;
    font-weight: 700;
    color: #667eea;
}

.action-text {
    font-size: 11px;
    color: #6c757d;
}

/* Missed Employees Section */
.missed-employees-section {
    margin-top: 20px;
}

.section-title {
    color: #495057;
    font-size: 14px;
    font-weight: 700;
    margin-bottom: 15px;
    padding-bottom: 8px;
    border-bottom: 2px solid #e9ecef;
}

.missed-list {
    max-height: 200px;
    overflow-y: auto;
}

.missed-item {
    display: flex;
    justify-content: between;
    align-items: center;
    padding: 10px;
    margin-bottom: 8px;
    background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
    border-radius: 10px;
    border: 1px solid #e9ecef;
}

.missed-info {
    display: flex;
    align-items: center;
    flex: 1;
    gap: 10px;
}

.missed-avatar {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #d63384;
    font-weight: bold;
    font-size: 12px;
}

.missed-details {
    display: flex;
    flex-direction: column;
}

.missed-name {
    font-weight: 600;
    font-size: 13px;
    color: #495057;
}

.missed-id {
    font-size: 11px;
    color: #6c757d;
}

.missed-actions {
    display: flex;
    gap: 5px;
}

.btn-xs {
    padding: 4px 8px;
    font-size: 11px;
    border-radius: 6px;
}

.missed-more {
    text-align: center;
    padding: 10px;
    font-style: italic;
}

.no-missed {
    text-align: center;
    padding: 20px;
    color: #28a745;
    font-weight: 600;
}

/* Empty State */
.empty-state {
    padding: 60px 20px;
}

.empty-icon i {
    font-size: 4rem;
    opacity: 0.3;
}

/* Responsive Design */
@media (max-width: 768px) {
    .action-grid {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .avatar-circle {
        width: 35px;
        height: 35px;
        font-size: 14px;
    }
    
    .status-badge {
        padding: 6px 12px;
        font-size: 11px;
    }
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    animation: fadeInUp 0.6s ease-out;
}

/* Modal Enhancements */
.modal-content {
    border-radius: 15px;
    border: none;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
}

.modal-header {
    border-bottom: 2px solid #e9ecef;
    border-radius: 15px 15px 0 0;
}

.modal-footer {
    border-top: 2px solid #e9ecef;
    border-radius: 0 0 15px 15px;
}
</style>
@endsection