@extends('layouts.app')

@section('content')
<style>
    /* Card Hover Lift Effect */
    .hover-card {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .hover-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    /* Colored Summary Cards */
    .summary-card {
        color: white;
        border: none;
    }
    .bg-users { background: linear-gradient(135deg, #4e73df, #224abe); }
    .bg-sessions { background: linear-gradient(135deg, #1cc88a, #0e8c5a); }
    .bg-attendance { background: linear-gradient(135deg, #f6c23e, #c69500); }
    .bg-logs { background: linear-gradient(135deg, #e74a3b, #be2617); }

    /* Theme Toggle */

.card {
    border-radius: 0.5rem;
    padding: 1rem;
    box-shadow: 0 0.125rem 0.25rem rgba(235, 230, 230, 0.075);
    transition: background-color 0.3s ease, color 0.3s ease;
}

    /* Light Mode */
    .light .card {
        background-color: #ffffff;
        color: #000000;
    }
    .light .card .table,
    .light .card .table td,
    .light .card .table th {
        color: #000000;
    }

    /* Dark Mode */
    .dark .card {
        background-color: #1e1e2f;
        color: #ffffff;
    }
    .dark .card .table,
    .dark .card .table td,
    .dark .card .table th {
        background-color: #1e1e2f;
        color: #fff;
    }
    .dark .table thead.table-primary {
        background-color: #2c2f4a !important;
        color: #fff !important;
    }
    .dark .table tbody tr {
        background-color: #2b2b3b;
        color: #000000;
        border-color: #3a3a3a;
    }

</style>

<div class="container py-5">

    <h1 class="mb-4 fw-bold">Admin Dashboard</h1>
    <p class="lead">Welcome, <strong>{{ Auth::user()->name }}</strong>! You have admin access.</p>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card summary-card bg-users rounded-4 p-3 hover-card">
                <h5>Total Users</h5>
                <p class="display-6 fw-bold">{{ $totalUsers ?? 0 }}</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card summary-card bg-sessions rounded-4 p-3 hover-card">
                <h5>Active Sessions</h5>
                <p class="display-6 fw-bold">{{ $activeSessions ?? 0 }}</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card summary-card bg-attendance rounded-4 p-3 hover-card">
                <h5>Attendances</h5>
                <p class="display-6 fw-bold">{{ $totalAttendances ?? 0 }}</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card summary-card bg-logs rounded-4 p-3 hover-card">
                <h5>Activity Logs</h5>
                <p class="display-6 fw-bold">{{ $totalActivities ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm rounded-4 p-3 hover-card">
                <h5>Attendance Overview (Last 7 Days)</h5>
                <canvas id="attendanceChart" height="100"></canvas>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm rounded-4 p-3 hover-card">
                <h5>Activity Logs (Last 7 Days)</h5>
                <canvas id="activityChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity Logs -->
    <div class="card shadow-sm rounded-4 p-3 hover-card">
        <h5 class="mb-3">Recent Activity Logs</h5>
        <table class="table table-hover">
            <thead class="table-primary">
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
                        <td colspan="4" class="text-center">No recent activity</td>
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

//theme toggle script
document.addEventListener('DOMContentLoaded', () => {
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');
    const body = document.body;
    const html = document.documentElement;

    function setTheme(theme) {
        body.classList.remove('light', 'dark');
        html.classList.remove('light', 'dark');
        body.classList.add(theme);
        html.classList.add(theme);
        localStorage.setItem('theme', theme);
        themeIcon.className = theme === 'dark' ? 'bi bi-sun' : 'bi bi-moon';
    }
});

</script>
@endsection
