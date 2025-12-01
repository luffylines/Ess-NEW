@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px;">
                <div class="card-body text-white p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h2 fw-bold mb-1">
                                <i class="fas fa-chart-line me-2"></i>Monthly Attendance Report
                            </h1>
                            <p class="mb-0 opacity-75">Comprehensive attendance tracking and analytics</p>
                        </div>
                        <div class="text-end">
                            <div class="badge bg-light text-dark fs-6 px-3 py-2">
                                {{ date('F Y', mktime(0,0,0,$month,1,$year)) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-header bg-white border-0" style="border-radius: 12px 12px 0 0;">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-filter text-primary me-2"></i>Report Filters
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form method="GET" action="{{ route('hr.reports') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Year</label>
                            <select name="year" class="form-select" style="border-radius: 8px;">
                                @for($y = date('Y'); $y >= date('Y')-5; $y--)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Month</label>
                            <select name="month" class="form-select" style="border-radius: 8px;">
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                        {{ date('F', mktime(0,0,0,$m,1)) }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="btn-group w-100" role="group">
                                <button type="submit" class="btn btn-primary" style="border-radius: 8px 0 0 8px;">
                                    <i class="fas fa-search me-2"></i>Generate Report
                                </button>
                                <a href="{{ route('hr.reports.export', ['year' => $year, 'month' => $month]) }}"
                                   class="btn btn-success" style="border-radius: 0 8px 8px 0;">
                                    <i class="fas fa-download me-2"></i>Export CSV
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #28a745;">
                <div class="card-body text-center p-3">
                    <div class="mb-2">
                        <i class="fas fa-user-check fa-2x text-success"></i>
                    </div>
                    <h4 class="fw-bold text-success mb-1">{{ $report->sum('present') }}</h4>
                    <p class="text-muted mb-0 small">Total Present Days</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #dc3545;">
                <div class="card-body text-center p-3">
                    <div class="mb-2">
                        <i class="fas fa-user-times fa-2x text-danger"></i>
                    </div>
                    <h4 class="fw-bold text-danger mb-1">{{ $report->sum('absent') }}</h4>
                    <p class="text-muted mb-0 small">Total Absent Days</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #ffc107;">
                <div class="card-body text-center p-3">
                    <div class="mb-2">
                        <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                    </div>
                    <h4 class="fw-bold text-warning mb-1">{{ $report->sum('in_only') }}</h4>
                    <p class="text-muted mb-0 small">Incomplete Records</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #17a2b8;">
                <div class="card-body text-center p-3">
                    <div class="mb-2">
                        <i class="fas fa-users fa-2x text-info"></i>
                    </div>
                    <h4 class="fw-bold text-info mb-1">{{ $report->count() }}</h4>
                    <p class="text-muted mb-0 small">Total Employees</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center" style="border-radius: 12px 12px 0 0;">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-table text-primary me-2"></i>Detailed Attendance Report
                    </h5>
                    <span class="badge bg-primary">{{ $report->count() }} employees</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 px-4 py-3 fw-semibold text-center">#</th>
                                    <th class="border-0 px-4 py-3 fw-semibold">Employee Name</th>
                                    <th class="border-0 px-4 py-3 fw-semibold text-center">Present</th>
                                    <th class="border-0 px-4 py-3 fw-semibold text-center">Absent</th>
                                    <th class="border-0 px-4 py-3 fw-semibold text-center">Incomplete</th>
                                    <th class="border-0 px-4 py-3 fw-semibold text-center">Attendance Rate</th>
                                    <th class="border-0 px-4 py-3 fw-semibold text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($report as $index => $row)
                                    @php
                                        $attendanceRate = $row['total_days'] > 0 ? round(($row['present'] / $row['total_days']) * 100, 1) : 0;
                                        $statusClass = $attendanceRate >= 95 ? 'success' : ($attendanceRate >= 80 ? 'warning' : 'danger');
                                        $statusText = $attendanceRate >= 95 ? 'Excellent' : ($attendanceRate >= 80 ? 'Good' : 'Poor');
                                    @endphp
                                    <tr>
                                        <td class="px-4 py-3 text-center fw-semibold text-muted">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                    <span class="text-white fw-bold">{{ strtoupper(substr($row['name'], 0, 2)) }}</span>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-semibold">{{ $row['name'] }}</h6>
                                                    <small class="text-muted">ID: {{ $row['user_id'] }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="badge bg-success-subtle text-success px-3 py-2">{{ $row['present'] }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="badge bg-danger-subtle text-danger px-3 py-2">{{ $row['absent'] }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="badge bg-warning-subtle text-warning px-3 py-2">{{ $row['in_only'] }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div class="progress" style="width: 60px; height: 8px;">
                                                    <div class="progress-bar bg-{{ $statusClass }}" style="width: {{ $attendanceRate }}%"></div>
                                                </div>
                                                <span class="ms-2 fw-semibold text-{{ $statusClass }}">{{ $attendanceRate }}%</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="badge bg-{{ $statusClass }} px-3 py-2">{{ $statusText }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">No Attendance Data</h5>
                                            <p class="text-muted">No attendance records found for the selected period.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
