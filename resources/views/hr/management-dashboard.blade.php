@extends('layouts.app')

@section('title', 'Attendance Management Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div>
                    <h2 class="h3 h4-mobile mb-1">
                        <i class="fas fa-tachometer-alt text-primary me-2"></i>
                        <span class="d-block d-sm-inline">Attendance Management</span>
                    </h2>
                    <p class="mb-0 text-muted small">Manage employee attendance and track daily presence</p>
                </div>
                <div class="d-flex flex-column flex-sm-row gap-2">
                    <a href="{{ route('hr.pending-approvals') }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-clock me-1"></i>
                        <span class="d-none d-sm-inline">Pending</span> Approvals 
                        @if($stats['pending_approvals'] > 0)
                            <span class="badge bg-light text-dark ms-1">{{ $stats['pending_approvals'] }}</span>
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </div>

    @include('partials.flash-messages')

    <!-- Statistics Cards -->
    <div class="row mb-4 g-3">
        <div class="col-6 col-md-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-users fa-lg me-2 flex-shrink-0"></i>
                        <div class="text-truncate">
                            <h5 class="card-title mb-0 fs-6 fs-md-5">{{ $stats['total_employees'] }}</h5>
                            <p class="card-text mb-0 small">
                                <span class="d-none d-sm-inline">Total</span> Employees
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-check fa-lg me-2 flex-shrink-0"></i>
                        <div class="text-truncate">
                            <h5 class="card-title mb-0 fs-6 fs-md-5">{{ $stats['present_today'] }}</h5>
                            <p class="card-text mb-0 small">
                                Present <span class="d-none d-sm-inline">Today</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card bg-danger text-white h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-times fa-lg me-2 flex-shrink-0"></i>
                        <div class="text-truncate">
                            <h5 class="card-title mb-0 fs-6 fs-md-5">{{ $stats['missed_today'] }}</h5>
                            <p class="card-text mb-0 small">
                                Missed <span class="d-none d-sm-inline">Today</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card bg-warning text-white h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-clock fa-lg me-2 flex-shrink-0"></i>
                        <div class="text-truncate">
                            <h5 class="card-title mb-0 fs-6 fs-md-5">{{ $stats['pending_approvals'] }}</h5>
                            <p class="card-text mb-0 small">
                                Pending <span class="d-none d-sm-inline">Approvals</span>
                            </p>
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
            <div class="card-1 card shadow-sm h-100">
                <div class="card-header ">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-day me-2"></i>Today's Attendance - {{ $today->format('F d, Y') }}
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($employees->count() > 0)
                        <div class="table-responsive">
                            <table class="card-1 table-hover mb-0">
                                <thead class="card-1">
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
                                        <tr class=" align-middle">
                                            <td>
                                                       <div class="d-flex align-items-center">
                                                    @if($employee->profile_photo && file_exists(public_path('storage/' . $employee->profile_photo)))
                                                        <img src="{{ asset('storage/' . $employee->profile_photo) }}" 
                                                             alt="Profile" class="rounded-circle me-3" width="45" height="45"
                                                             style="object-fit: cover; border: 3px solid #667eea; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);">
                                                    @else
                                                        <div class="avatar-circle me-3">
                                                            <span class="avatar-initials">{{ substr($employee->name, 0, 1) }}</span>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0 fw-bold">{{ $employee->name }}</h6>
                                                        <small class="">ID: {{ $employee->employee_id ?? 'N/A' }}</small>
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
                                                    <span class="">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($attendance && $attendance->time_out)
                                                    <div class="time-display time-out">
                                                        <i class="fas fa-sign-out-alt me-1"></i>
                                                        <span>{{ $attendance->time_out->format('h:i A') }}</span>
                                                    </div>
                                                @else
                                                    <span class="">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if(!$attendance)
                                                    <!-- Mark Present/Absent Links -->
                                                    <div class="action-buttons">
                                                        <a href="{{ route('hr.mark-present.form', ['employee' => $employee->id, 'date' => $today->format('Y-m-d')]) }}" 
                                                           class="btn btn-success btn-sm action-btn">
                                                            <i class="fas fa-user-check me-1"></i>Mark Present
                                                        </a>
                                                        <a href="{{ route('hr.mark-absent.form', ['employee' => $employee->id, 'date' => $today->format('Y-m-d')]) }}" 
                                                           class="btn btn-danger btn-sm action-btn">
                                                            <i class="fas fa-user-times me-1"></i>Mark Absent
                                                        </a>
                                                    </div>
                                                @else
                                                    <!-- Edit Times Link -->
                                                    <a href="{{ route('hr.edit-times.form', ['attendance' => $attendance->id]) }}" 
                                                       class="btn btn-primary btn-sm action-btn">
                                                        <i class="fas fa-edit me-1"></i>Edit Times
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
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
                        
                        <a href="{{ route('hr.payroll.index') }}" class="action-card payroll">
                            <div class="action-icon">
                                <i class="fas fa-calculator"></i>
                            </div>
                            <div class="action-content">
                                <h6>Payroll</h6>
                                <span class="action-text">Manage Payroll</span>
                            </div>
                        </a>
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
                                            @if($employee->profile_photo && file_exists(public_path('storage/' . $employee->profile_photo)))
                                                <img src="{{ asset('storage/' . $employee->profile_photo) }}" 
                                                     alt="Profile" class="rounded-circle me-2" width="30" height="30"
                                                     style="object-fit: cover; border: 2px solid #ff9a9e;">
                                            @else
                                                <div class="missed-avatar">
                                                    <span>{{ substr($employee->name, 0, 1) }}</span>
                                                </div>
                                            @endif
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
    border-top: none;
    font-weight: 400;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    padding: 15px;

}
 /* Dark Mode */
.dark .card-1 {
    background-color: #1e1e2f !important;
    color: #ffffff !important;
    border-color: #2c2c3b;
    border-top: none;
    font-weight: 400;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 15px;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
}
.card-1 table th,
.card-1 table td {
    font-size: 12px !important;  
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
    font-size: 1px;
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

/* Action Buttons - STABILIZED */
.action-btn {
    border-radius: 10px;
    font-weight: 600;
    padding: 8px 16px;
    margin: 2px;
    position: relative;
    transform: none !important;
    transition: background-color 0.2s ease, border-color 0.2s ease !important;
    backface-visibility: hidden !important;
    -webkit-backface-visibility: hidden !important;
}

.action-btn:hover {
    /* Remove transform animations to prevent flickering */
    transform: none !important;
    opacity: 0.9;
}

.action-btn:focus {
    transform: none !important;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.action-btn:active {
    transform: none !important;
}

.action-buttons {
    position: relative;
    min-height: 40px;
    display: flex;
    gap: 5px;
    justify-content: center;
    flex-wrap: wrap;
    align-items: center;
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

.action-card.payroll {
    background: linear-gradient(135deg, #e2e3ff 0%, #ffffff 100%);
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

/* Modal Enhancements - COMPLETE STABILIZATION */
.modal {
    z-index: 1055 !important;
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
    overflow-x: hidden !important;
    overflow-y: auto !important;
    outline: 0 !important;
    transform: none !important;
    transition: none !important;
    animation: none !important;
}

.modal-backdrop {
    z-index: 1050 !important;
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    background-color: rgba(0, 0, 0, 0.5) !important;
}

.modal-content {
    border-radius: 15px !important;
    border: none !important;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2) !important;
    pointer-events: auto !important;
    position: relative !important;
    display: flex !important;
    flex-direction: column !important;
    width: 100% !important;
    background-color: #fff !important;
    background-clip: padding-box !important;
    outline: 0 !important;
    transform: none !important;
    transition: none !important;
    animation: none !important;
}

.modal-dialog {
    position: relative !important;
    width: auto !important;
    margin: 0.5rem auto !important;
    pointer-events: none !important;
    transform: none !important;
    transition: none !important;
    animation: none !important;
}

.modal.show .modal-dialog {
    transform: none !important;
    animation: none !important;
    transition: none !important;
}

.modal.fade .modal-dialog {
    transform: none !important;
    transition: none !important;
}

/* Remove ALL fade effects */
.modal.fade {
    opacity: 1 !important;
}

.modal.fade.show {
    opacity: 1 !important;
}

.modal-header {
    border-bottom: 2px solid #e9ecef;
    border-radius: 15px 15px 0 0;
}

.modal-footer {
    border-top: 2px solid #e9ecef;
    border-radius: 0 0 15px 15px;
}

/* Mobile Responsive Styles */
@media (max-width: 768px) {
    .h4-mobile {
        font-size: 1.25rem !important;
    }
    
    .card {
        margin-bottom: 0.5rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
        border-radius: 0.5rem;
        overflow: hidden;
    }
    
    .btn-group {
        flex-direction: column;
        width: 100%;
    }
    
    .btn-group .btn {
        border-radius: 0.25rem !important;
        margin-bottom: 0.25rem;
    }
    
    .modal-dialog {
        margin: 0.5rem;
    }
    
    .modal-content {
        border-radius: 1rem;
    }
    
    .form-control,
    .form-select {
        font-size: 16px; /* Prevents zoom on iOS */
        padding: 0.75rem;
    }
    
    .input-group {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .input-group .btn {
        border-radius: 0.375rem !important;
    }
}

@media (max-width: 576px) {
    .container-fluid {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .btn {
        font-size: 0.875rem;
        padding: 0.5rem 0.75rem;
    }
    
    .table td,
    .table th {
        padding: 0.5rem;
        font-size: 0.8rem;
    }
    
    .badge {
        font-size: 0.7rem;
    }
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