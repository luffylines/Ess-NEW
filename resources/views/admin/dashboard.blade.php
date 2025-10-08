@extends('layouts.app')

@section('content')
<div class="container py-5">

    <h1 class="mb-4">Admin Dashboard</h1>
    <p class="lead">Welcome, {{ Auth::user()->name }}! You have admin access.</p>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm rounded-4 p-3">
                <h5>Total Users</h5>
                <p class="display-6 fw-bold">{{ $totalUsers ?? 0 }}</p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm rounded-4 p-3">
                <h5>Active Sessions</h5>
                <p class="display-6 fw-bold">{{ $activeSessions ?? 0 }}</p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm rounded-4 p-3">
                <h5>Page Views</h5>
                <p class="display-6 fw-bold">{{ $pageViews ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="card shadow-sm rounded-4 p-3">
        <h5 class="mb-3">Website Traffic (Last 7 Days)</h5>
        <canvas id="analyticsChart" height="100"></canvas>
    </div>
</div>

<!-- Google Analytics Script -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-KY3YW11B51"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', 'G-KY3YW11B51');
</script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('analyticsChart').getContext('2d');
const analyticsChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($trafficData['labels'] ?? []),
        datasets: [{
            label: 'Page Views',
            data: @json($trafficData['pageViews'] ?? []),
            fill: true,
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        scales: { y: { beginAtZero: true } }
    }
});
</script>
@endsection
