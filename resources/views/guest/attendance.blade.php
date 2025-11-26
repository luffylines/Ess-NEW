@extends('layouts.welcome')

@section('content')
<div class="container mt-5 pt-4">

    <!-- Hero Section -->
    <div class="row align-items-center mb-5">
        <div class="col-md-6">
            <h1 class="display-5 fw-bold">Attendance Management</h1>
            <p class="lead mt-3">
                Simplify and streamline employee time tracking. Our ESS attendance system ensures accurate records, seamless clock-ins, and transparency for both employees and HR.
            </p>
        </div>
        <div class="col-md-6 text-center">
            <img src="https://imgs.search.brave.com/Jut9raZob7nCVZqrWsQL9OHlizqeLRTtI-nE1agkomo/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9jZG4u/cHJvZC53ZWJzaXRl/LWZpbGVzLmNvbS82/MmQ4NGIzZDNiYTQ0/NmIyZWMwNDFhMTkv/Njg0MDMwYjFmNGE5/ZjY3MWVmNDllMWJi/X2RfVUFXR2swUXJp/QVU1R0tfWUJFaXcu/anBlZw" alt="Attendance System" class="img-fluid rounded shadow">
        </div>
    </div>

    <!-- Feature Cards -->
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <img src="https://imgs.search.brave.com/rn3pJg3Z-WtCmUcnHASJU43D4yd8Z9f_mWeIXETPknI/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tZWRp/YS5pc3RvY2twaG90/by5jb20vaWQvMTM2/MTI0NTcxNy9waG90/by90aW1lLWlzLXJ1/bm5pbmctb3V0LWNv/bmNlcHQtd2l0aC1h/bGFybS1jbG9jay1h/c2lkZS0zZC1yZW5k/ZXJpbmcuanBnP3M9/NjEyeDYxMiZ3PTAm/az0yMCZjPURMMk1H/LVItX2ZsWDk5YkR5/U21tYXpjLVcxODYy/aktrVXN3Yy1SZFpi/bkk9" class="card-img-top" alt="Clock In / Clock Out">
                <div class="card-body">
                    <h5 class="card-title">One-Click Clock In/Out</h5>
                    <p class="card-text">Start and end your workday with a single click. ESS logs your time precisely, minimizing errors and manual entries.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <img src="https://imgs.search.brave.com/l2ilUrczi-1Cwqoi3sOAH8F6PN52321Y_rGLODF7tyI/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tZWRp/YS5pc3RvY2twaG90/by5jb20vaWQvMTE2/ODMwNTY0Ny92ZWN0/b3IvdGltZS1hbmQt/YXR0ZW5kYW5jZS10/cmFja2luZy1zeXN0/ZW0tY29uY2VwdC12/ZWN0b3ItaWxsdXN0/cmF0aW9uLmpwZz9z/PTYxMng2MTImdz0w/Jms9MjAmYz1yOXIz/UEtZVVBDYlVaS2NR/aWF5aVJOU2tPQ1Nu/S2hJOHBVbEF3a0dj/OUNRPQ" class="card-img-top" alt="Attendance History">
                <div class="card-body">
                    <h5 class="card-title">Real-Time Attendance Logs</h5>
                    <p class="card-text">Track your attendance history with timestamps, shift durations, and any leave or late marks, all in real-time.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <img src="https://imgs.search.brave.com/SjfoLc7O4j2aEJVV_ptgU9qubCyi1BPBE_c7RyMpMQI/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly90My5m/dGNkbi5uZXQvanBn/LzA4LzQxLzk0LzAy/LzM2MF9GXzg0MTk0/MDI0MF9kdTBJa0c4/dUx4dlFZQXVwc1lr/ZDdwN0xPUGxPbW9T/YS5qcGc" class="card-img-top" alt="Analytics">
                <div class="card-body">
                    <h5 class="card-title">Analytics & Reports</h5>
                    <p class="card-text">Get visual reports of your monthly attendance, late entries, overtime, and more for improved personal insights.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="row mt-5 py-5 bg-light rounded shadow-sm">
        <div class="col text-center">
            <h2 class="mb-3">Precision Meets Productivity</h2>
            <p class="mb-4">
                Eliminate manual tracking and focus on what matters. ESS handles the rest.
            </p>
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Start Tracking Attendance</a>
        </div>
    </div>

</div>
@endsection
