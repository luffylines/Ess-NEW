<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet" />
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    
    <!-- Custom Theme Styles -->
    <style>
        :root {
            --bs-primary: #0d6efd;
            --bs-dark-bg: #1a1a1a;
            --bs-light-bg: #ffffff;
        }

        body.light {
            background-color: var(--bs-light-bg);
            color: #212529;
        }

        body.dark {
            background-color: var(--bs-dark-bg);
            color: #ffffff;
        }

        body.dark .navbar {
            background-color: #2d2d2d !important;
        }

        body.dark .card, body.dark .dropdown-menu, body.dark .form-control, body.dark .form-select {
            background-color: #2d2d2d;
            color: #ffffff;
            border-color: #444;
        }

        .navbar-light .nav-link {
            color: #000 !important;
        }

        .navbar-dark .nav-link {
            color: #fff !important;
        }

        .navbar-dark .navbar-brand, .navbar-light .navbar-brand {
            font-weight: 600;
        }

        .dropdown-item:hover {
            background-color: #f0f0f0;
        }

        body.dark .dropdown-item:hover {
            background-color: #444;
        }

        body {
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .card, .navbar, .form-control, .form-select {
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        main {
            margin-top: 80px;
        }

        /* Custom Styles for About Page */
        .about-section {
            margin-top: 100px;
        }

        .about-title {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--bs-primary);
        }

        .about-description {
            font-size: 1.25rem;
            line-height: 1.7;
            margin-top: 20px;
        }

        .feature-card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .feature-card .card-header {
            background-color: var(--bs-primary);
            color: white;
            font-weight: bold;
        }

        .feature-card .card-body {
            font-size: 1rem;
            color: #555;
        }

        .feature-card .card-footer {
            background-color: #f8f9fa;
            text-align: center;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease-in-out;
        }
    </style>

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="{{ (Auth::check() && Auth::user()->display_mode === 'dark') ? 'dark' : 'light' }}">
    <div id="app">
        <!-- ✅ FIXED & RESPONSIVE NAVBAR -->
        <nav class="navbar navbar-expand-lg {{ (Auth::check() && Auth::user()->display_mode === 'dark') ? 'navbar-dark bg-dark' : 'navbar-light bg-light' }} shadow-sm fixed-top">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Right Side -->
                    <ul class="navbar-nav ms-auto">
                        @guest
                            <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact Us</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('terms') }}">Terms & Conditions</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('system-info') }}">System Info</a></li>
                            @if(Route::has('login'))
                                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Account Settings</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"> Logout </a>
                                    </li>
                                </ul>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container about-section">
            <h1 class="about-title">About the Employee Self-Service (ESS) System</h1>
            <p class="about-description">
                Employee Self-Service (ESS) system by Christian Baynado Aring is designed to streamline employee attendance, reports, and tasks efficiently. ESS provides employees with a self-managed platform to track attendance, access performance metrics, and manage various administrative tasks. With an easy-to-use interface, it simplifies the daily work experience.
            </p>

            <!-- Features Section -->
            <div class="row g-4 mt-5">
                <div class="col-md-4">
                    <div class="card feature-card">
                        <div class="card-header">
                            <i class="fas fa-calendar-check"></i> Attendance Management
                        </div>
                        <div class="card-body">
                            ESS allows employees to log their attendance, view attendance history, and track work hours easily.
                        </div>
                        <div class="card-footer">
                            Learn More
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card">
                        <div class="card-header">
                            <i class="fas fa-file-alt"></i> Reports & Analytics
                        </div>
                        <div class="card-body">
                            Generate and review attendance, leave reports, and more with our dynamic reporting feature.
                        </div>
                        <div class="card-footer">
                            Learn More
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card">
                        <div class="card-header">
                            <i class="fas fa-tasks"></i> Task Management
                        </div>
                        <div class="card-body">
                            Organize and manage your tasks in one place. Set reminders and deadlines to stay on top of your responsibilities.
                        </div>
                        <div class="card-footer">
                            Learn More
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
