@extends('layouts.welcome')

@section('content')
<div class="container mt-5 pt-4">

    <!-- Hero Section -->
    <div class="row align-items-center mb-5">
        <div class="col-md-6">
            <h1 class="display-5 fw-bold">Reports & Analytics</h1>
            <p class="lead mt-3">
                Make informed decisions with powerful reporting tools. Monitor attendance trends, evaluate task performance, and stay ahead with real-time analytics tailored for employees and HR teams.
            </p>
        </div>
        <div class="col-md-6 text-center">
            <img src="https://imgs.search.brave.com/SjfoLc7O4j2aEJVV_ptgU9qubCyi1BPBE_c7RyMpMQI/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly90My5m/dGNkbi5uZXQvanBn/LzA4LzQxLzk0LzAy/LzM2MF9GXzg0MTk0/MDI0MF9kdTBJa0c4/dUx4dlFZQXVwc1lr/ZDdwN0xPUGxPbW9T/YS5qcGc" alt="Reports and Analytics" class="img-fluid rounded shadow">
        </div>
    </div>

    <!-- Feature Cards -->
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <img src="https://imgs.search.brave.com/vljlzkK34DUVWgS3Nc48jrNu89xAbUiHeavmJaI1wPo/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly93d3cu/b25ldGFwY2hlY2tp/bi5jb20vaW1hZ2Vz/L3NlY3VyZS1ncmFu/dHMtd2l0aC1vbmV0/YXBhdHRlbmRhbmNl/LXJlcG9ydHMud2Vi/cA" class="card-img-top" alt="Attendance Reports">
                <div class="card-body">
                    <h5 class="card-title">Attendance Reports</h5>
                    <p class="card-text">Download monthly summaries, view daily logs, and detect attendance anomalies with just a few clicks.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <img src="https://imgs.search.brave.com/UrxIz4EHjO-KkpGrpfdlIIpV93qzWLNFN1-5f4C5HdQ/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly93d3cu/YWloci5jb20vd3At/Y29udGVudC91cGxv/YWRzL2xlYXZlLW9m/LWFic2VuY2UtcG9s/aWN5LWNvdmVyLnBu/Zw" class="card-img-top" alt="Leave & Absence Analysis">
                <div class="card-body">
                    <h5 class="card-title">Leave & Absence Analysis</h5>
                    <p class="card-text">Track leave balances, identify patterns in absences, and optimize team availability through data insights.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <img src="https://imgs.search.brave.com/TVTDDmKG_eald2hQhYBcBPk5RbeNQXHOcwPehivE0HQ/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pbWcu/ZnJlZXBpay5jb20v/ZnJlZS12ZWN0b3Iv/YnVzaW5lc3MtcGVy/Zm9ybWFuY2UtYW5h/bHlzaXMtd2l0aC1n/cmFwaHNfNTM4NzYt/NjYyNjEuanBnP3Nl/bXQ9YWlzX2h5YnJp/ZCZ3PTc0MCZxPTgw" class="card-img-top" alt="Performance Metrics">
                <div class="card-body">
                    <h5 class="card-title">Performance Metrics</h5>
                    <p class="card-text">Monitor task completion rates, deadline adherence, and engagement through visual analytics and dashboards.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="row mt-5 py-5 bg-light rounded shadow-sm">
        <div class="col text-center">
            <h2 class="mb-3">Data-Driven Decisions Start Here</h2>
            <p class="mb-4">
                Leverage reports and analytics to improve productivity, transparency, and performance across your organization.
            </p>
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Explore Your Reports</a>
        </div>
    </div>

</div>
@endsection
