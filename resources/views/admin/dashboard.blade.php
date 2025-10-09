@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Admin Dashboard</h1>
    <p class="lead">Welcome, {{ Auth::user()->name }}! You have admin access.</p>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm rounded-4 p-3">
                <h5>Total Users</h5>
                <p class="display-6 fw-bold">{{ $totalUsers ?? 0 }}</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm rounded-4 p-3">
                <h5>Active Sessions</h5>
                <p class="display-6 fw-bold">{{ $activeSessions ?? 0 }}</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm rounded-4 p-3">
                <h5>Attendances</h5>
                <p class="display-6 fw-bold">{{ $totalAttendances ?? 0 }}</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm rounded-4 p-3">
                <h5>Activity Logs</h5>
                <p class="display-6 fw-bold">{{ $totalActivities ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm rounded-4 p-3">
                <h5>Attendance Overview (Last 7 Days)</h5>
                <canvas id="attendanceChart" height="100"></canvas>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm rounded-4 p-3">
                <h5>Activity Logs (Last 7 Days)</h5>
                <canvas id="activityChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity Logs -->
    <div class="card shadow-sm rounded-4 p-3">
        <h5 class="mb-3">Recent Activity Logs</h5>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Action</th>
                    <th>User</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recentActivities ?? [] as $activity)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($activity->created_at)->format('M d, Y h:i A') }}</td>
                        <td>{{ $activity->action_type ?? 'N/A' }}</td>
                        <td>{{ $activity->user->name ?? 'System' }}</td>
                        <td>{{ $activity->ip_address ?? 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">No recent activity</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
new Chart(attendanceCtx, {
    type: 'line',
    data: {
        labels: @json($attendanceChart['labels'] ?? []),
        datasets: [{
            label: 'Attendances',
            data: @json($attendanceChart['totals'] ?? []),
            fill: true,
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            tension: 0.3
        }]
    },
    options: { scales: { y: { beginAtZero: true } } }
});

const activityCtx = document.getElementById('activityChart').getContext('2d');
new Chart(activityCtx, {
    type: 'bar',
    data: {
        labels: @json($activityChart['labels'] ?? []),
        datasets: [{
            label: 'Activity Logs',
            data: @json($activityChart['totals'] ?? []),
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: { scales: { y: { beginAtZero: true } } }
});
</script>
@endsection
