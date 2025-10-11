<x-app-layout>
    <div class="py-3">
        <div class="px-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    @if(Auth::user()->role === 'admin')
                        @include('admin.dashboard')
                    @elseif(Auth::user()->role === 'hr' || Auth::user()->role === 'manager')
                        @include('hr.dashboard')
                    @else
                        {{-- Employee Dashboard --}}
                        <div class="container-fluid">
                            {{-- Header Section --}}
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h1 class="h3 fw-bold text-primary mb-1">Welcome, {{ $user->name }}!</h1>
                                            <p class="text-muted mb-0">
                                                Employee ID: {{ $user->employee_id }} | Role: {{ ucfirst($user->role) }}
                                            </p>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-muted">{{ \Carbon\Carbon::now()->format('l, F j, Y') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Key Metrics Cards --}}
                            <div class="row mb-4">
                                <div class="col-xl-3 col-md-6 mb-3">
                                    <div class="card bg-primary text-white shadow-sm h-100">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="card-title mb-1">Attendance This Month</h5>
                                                <h2 class="mb-0">{{ $attendanceCount }}/{{ $totalWorkingDays }}</h2>
                                                <small class="opacity-75">{{ $attendancePercentage }}% Present</small>
                                            </div>
                                            <div class="fs-1 opacity-75"><i class="fas fa-calendar-check"></i></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-6 mb-3">
                                    <div class="card bg-success text-white shadow-sm h-100">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="card-title mb-1">Overtime Hours</h5>
                                                <h2 class="mb-0">{{ $totalOvertime }}</h2>
                                                <small class="opacity-75">Last 30 Days</small>
                                            </div>
                                            <div class="fs-1 opacity-75"><i class="fas fa-clock"></i></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-6 mb-3">
                                    <div class="card bg-warning text-white shadow-sm h-100">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="card-title mb-1">Leave Taken</h5>
                                                <h2 class="mb-0">{{ $totalLeaveTaken }}</h2>
                                                <small class="opacity-75">Last 30 Days</small>
                                            </div>
                                            <div class="fs-1 opacity-75"><i class="fas fa-plane-departure"></i></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-6 mb-3">
                                    <div class="card bg-info text-white shadow-sm h-100">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="card-title mb-1">Leave Balance</h5>
                                                <h2 class="mb-0">{{ $leaveBalance }}</h2>
                                                <small class="opacity-75">Days Remaining</small>
                                            </div>
                                            <div class="fs-1 opacity-75"><i class="fas fa-calendar-minus"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Holiday & Working Days Information --}}
                            <div class="row mb-4">
                                <div class="col-xl-3 col-md-6 mb-3">
                                    <div class="card bg-secondary text-white shadow-sm h-100">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="card-title mb-1">Remaining Work Days</h5>
                                                <h2 class="mb-0">{{ $remainingWorkingDays }}</h2>
                                                <small class="opacity-75">This Month</small>
                                            </div>
                                            <div class="fs-1 opacity-75"><i class="fas fa-business-time"></i></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-6 mb-3">
                                    <div class="card {{ $isTodayHoliday ? 'bg-danger' : 'bg-dark' }} text-white shadow-sm h-100">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="card-title mb-1">Today's Status</h5>
                                                <h2 class="mb-0">{{ $isTodayHoliday ? 'Holiday' : 'Work Day' }}</h2>
                                                <small class="opacity-75">{{ \Carbon\Carbon::now()->format('M j, Y') }}</small>
                                            </div>
                                            <div class="fs-1 opacity-75">
                                                <i class="fas {{ $isTodayHoliday ? 'fa-star' : 'fa-briefcase' }}"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-6 mb-3">
                                    <div class="card bg-purple text-white shadow-sm h-100" style="background: linear-gradient(45deg, #6f42c1, #8e44ad);">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="card-title mb-1">Holidays This Month</h5>
                                                <h2 class="mb-0">{{ $holidayCount }}</h2>
                                                <small class="opacity-75">{{ \Carbon\Carbon::now()->format('F') }}</small>
                                            </div>
                                            <div class="fs-1 opacity-75"><i class="fas fa-calendar-alt"></i></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-6 mb-3">
                                    <div class="card bg-gradient-primary text-white shadow-sm h-100" style="background: linear-gradient(45deg, #007bff, #0056b3);">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="card-title mb-1">Upcoming Holidays</h5>
                                                <h2 class="mb-0">{{ $upcomingHolidays->count() }}</h2>
                                                <small class="opacity-75">Next 30 Days</small>
                                            </div>
                                            <div class="fs-1 opacity-75"><i class="fas fa-calendar-plus"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Status Cards --}}
                            <div class="row mb-4">
                                <div class="col-md-4 mb-3">
                                    <div class="card shadow-sm text-center">
                                        <div class="card-body">
                                            <i class="fas fa-hourglass-half text-warning fa-2x mb-2"></i>
                                            <h5 class="card-title">Pending Requests</h5>
                                            <p class="mb-1"><strong>{{ $pendingLeaveRequests }}</strong> Leave Requests</p>
                                            <p class="mb-0"><strong>{{ $pendingOvertimeRequests }}</strong> Overtime Requests</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <div class="card shadow-sm text-center">
                                        <div class="card-body">
                                            <i class="fas fa-calendar-day text-success fa-2x mb-2"></i>
                                            <h5 class="card-title">Upcoming Leave</h5>
                                            @if($upcomingLeave)
                                                <p class="mb-1"><strong>{{ $upcomingLeave->leave_type }}</strong></p>
                                                <p class="mb-0">{{ \Carbon\Carbon::parse($upcomingLeave->start_date)->format('M d, Y') }}</p>
                                            @else
                                                <p class="text-muted mb-0">No upcoming leave</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <div class="card shadow-sm text-center">
                                        <div class="card-body">
                                            <i class="fas fa-user-check text-primary fa-2x mb-2"></i>
                                            <h5 class="card-title">This Month</h5>
                                            <p class="mb-1">Attendance: <strong>{{ $attendancePercentage }}%</strong></p>
                                            <p class="mb-0">Working Days: <strong>{{ $totalWorkingDays }}</strong></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Upcoming Holidays Section --}}
                            @if($upcomingHolidays->count() > 0)
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="card shadow-sm">
                                        <div class="card-header bg-gradient-primary text-white">
                                            <h5 class="mb-0">
                                                <i class="fas fa-star me-2"></i>
                                                Upcoming Holidays (Next 30 Days)
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                @foreach($upcomingHolidays as $holiday)
                                                <div class="col-md-6 col-lg-4 mb-3">
                                                    <div class="border rounded p-3 h-100 {{ $holiday->type === 'regular' ? 'border-danger' : 'border-warning' }}">
                                                        <div class="d-flex justify-content-between align-items-start">
                                                            <div>
                                                                <h6 class="fw-bold text-dark mb-1">{{ $holiday->name }}</h6>
                                                                <p class="text-muted mb-1">
                                                                    {{ \Carbon\Carbon::parse($holiday->date)->format('l, F j, Y') }}
                                                                </p>
                                                                <small class="badge {{ $holiday->type === 'regular' ? 'bg-danger' : 'bg-warning' }}">
                                                                    {{ ucfirst($holiday->type) }} Holiday
                                                                </small>
                                                            </div>
                                                            <div class="text-{{ $holiday->type === 'regular' ? 'danger' : 'warning' }}">
                                                                <i class="fas fa-calendar-day fa-lg"></i>
                                                            </div>
                                                        </div>
                                                        @if($holiday->type === 'regular')
                                                        <small class="text-muted d-block mt-2">
                                                            <i class="fas fa-info-circle me-1"></i>
                                                            Double pay applies
                                                        </small>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            {{-- Charts Section (Side by Side) --}}
                            <div class="row mb-4">
                                <div class="col-lg-6 mb-3">
                                    <div class="card shadow-sm rounded-4 p-3">
                                        <h5>Attendance Overview (Last 30 Days) - Line Chart</h5>
                                        <canvas id="lineAttendanceChart" height="150"></canvas>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <div class="card shadow-sm rounded-4 p-3">
                                        <h5>Attendance Overview (Last 30 Days) - Bar Chart</h5>
                                        <canvas id="barAttendanceChart" height="150"></canvas>
                                    </div>
                                </div>
                            </div>

                            {{-- Recent Attendance --}}
                            <div class="row mb-4">
                                <div class="col-lg-4 mb-3">
                                    <div class="card shadow-sm">
                                        <div class="card-header bg-light">
                                            <h5 class="card-title mb-0">Recent Attendance</h5>
                                        </div>
                                        <div class="card-body">
                                            @forelse($recentAttendance as $attendance)
                                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                    <div>
                                                        <strong>{{ \Carbon\Carbon::parse($attendance->date)->format('M d') }}</strong><br>
                                                        <small class="text-muted">{{ ucfirst($attendance->day_type ?? 'Regular') }}</small>
                                                    </div>
                                                    <div class="text-end">
                                                        @if($attendance->time_in)
                                                            <small class="text-success">✓ Present</small><br>
                                                            <small class="text-muted">{{ \Carbon\Carbon::parse($attendance->time_in)->format('h:i A') }}</small>
                                                        @else
                                                            <small class="text-muted">- Absent</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            @empty
                                                <p class="text-muted text-center mb-0">No recent attendance records</p>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>

                                {{-- Monthly Stats --}}
                                <div class="col-lg-8 mb-3">
                                    <div class="card shadow-sm">
                                        <div class="card-header bg-light">
                                            <h5 class="card-title mb-0">
                                                Monthly Attendance Statistics ({{ \Carbon\Carbon::now()->year }})
                                                <small class="text-muted">- Excludes holidays & weekends</small>
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Month</th>
                                                            <th>Days Present</th>
                                                            <th>Working Days</th>
                                                            <th>Attendance %</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($monthlyStats as $stat)
                                                            <tr>
                                                                <td><strong>{{ $stat['month'] }}</strong></td>
                                                                <td>{{ $stat['attendance'] }}</td>
                                                                <td>{{ $stat['working_days'] }}</td>
                                                                <td>
                                                                    <span class="badge bg-{{ $stat['percentage'] >= 90 ? 'success' : ($stat['percentage'] >= 75 ? 'warning' : 'danger') }}">
                                                                        {{ $stat['percentage'] }}%
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    @if($stat['percentage'] >= 90)
                                                                        <span class="text-success">Excellent</span>
                                                                    @elseif($stat['percentage'] >= 75)
                                                                        <span class="text-warning">Good</span>
                                                                    @else
                                                                        <span class="text-danger">Needs Improvement</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Leave Requests Section --}}
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="card shadow-sm rounded-4 p-3">
                                        <h5>Leave Requests</h5>
                                        @forelse ($leaveRequests ?? [] as $leaveRequest)
                                            <div class="alert alert-info d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>{{ $leaveRequest->type }}:</strong> 
                                                    {{ \Carbon\Carbon::parse($leaveRequest->start_date)->format('M d, Y') }} to 
                                                    {{ \Carbon\Carbon::parse($leaveRequest->end_date)->format('M d, Y') }}
                                                </div>
                                                <span class="badge rounded-pill 
                                                    {{ $leaveRequest->status == 'approved' ? 'bg-success' : ($leaveRequest->status == 'pending' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                                    {{ ucfirst($leaveRequest->status) }}
                                                </span>
                                            </div>
                                        @empty
                                            <div class="alert alert-warning">
                                                You have no pending leave requests.
                                            </div>
                                        @endforelse
                                        <a href="{{ route('leave.index') }}" class="btn btn-primary mt-3">Request Leave</a>
                                    </div>
                                </div>
                            </div>

                            {{-- Recent Activity Section --}}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card shadow-sm rounded-4 p-3">
                                        <h5>Recent Activity</h5>
                                        @forelse ($recentActivities ?? [] as $activity)
                                            <div class="alert alert-light">
                                                <strong>{{ \Carbon\Carbon::parse($activity->created_at)->format('M d, Y h:i A') }}:</strong>
                                                {{ $activity->description }}
                                            </div>
                                        @empty
                                            <div class="alert alert-info">
                                                No recent activity.
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Line Chart - Attendance (Present vs Absent)
        const lineCtx = document.getElementById('lineAttendanceChart').getContext('2d');
        new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: @json($chartData['labels']),
                datasets: [{
                    label: 'Attendance',
                    data: @json($chartData['data']),
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 1,
                        ticks: {
                            stepSize: 1,
                            callback: function(value) { return value === 1 ? 'Present' : 'Absent'; }
                        }
                    }
                }
            }
        });

        // Bar Chart - Daily Attendance
        const barCtx = document.getElementById('barAttendanceChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: @json($attendanceLabels ?? []),
                datasets: [{
                    label: 'Present',
                    data: @json($attendanceCounts ?? []),
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                },
                plugins: { legend: { display: true, position: 'top' } }
            }
        });
    </script>
</x-app-layout>
