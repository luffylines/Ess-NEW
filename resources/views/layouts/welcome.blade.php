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


    <!-- Custom CSS for theme -->
    <style>
        :root {
            --bs-primary: #0d6efd;
            --bs-secondary: #6c757d;
            --bs-success: #198754;
            --bs-info: #0dcaf0;
            --bs-warning: #ffc107;
            --bs-danger: #dc3545;
            --bs-light: #f8f9fa;
            --bs-dark: #212529;
        }

        /* Light theme (default) */
        body.light {
            background-color: #ffffff;
            color: #212529;
        }

        /* Dark theme */
        body.dark {
            background-color: #1a1a1a;
            color: #ffffff;
        }

        body.dark .navbar {
            background-color: #2d2d2d !important;
        }

        body.dark .card {
            background-color: #2d2d2d;
            border-color: #444;
            color: #ffffff;
        }

        body.dark .table {
            --bs-table-bg: #2d2d2d;
            --bs-table-color: #ffffff;
        }

        body.dark .form-control,
        body.dark .form-select {
            background-color: #3d3d3d;
            border-color: #555;
            color: #ffffff;
        }

        body.dark .btn-outline-secondary {
            color: #ffffff;
            border-color: #6c757d;
        }

        body.dark .btn-outline-secondary:hover {
            background-color: #6c757d;
            color: #ffffff;
        }

        /* RTL Support */
        [dir="rtl"] {
            text-align: right;
        }

        [dir="rtl"] .offcanvas-end {
            right: auto;
            left: 0;
        }

        /* Smooth transitions */
        body {
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .card, .navbar, .form-control, .form-select {
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="{{ (Auth::check() && Auth::user()->display_mode === 'dark') ? 'dark' : 'light' }}">

    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" 
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link " href="{{ route('dashboard') }}">Dashboard</a></li>

                            @if(auth()->user()->role === 'admin')
                                <li class="nav-item"><a class="nav-link" href="{{ route('admin.index') }}">Employees</a></li>
                                <li class="nav-item dropdown">
                                    <a id="deductionsDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Deductions & Contributions
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="deductionsDropdown">
                                        <li><a class="dropdown-item" href="{{ route('admin.loans.sss') }}">SSS Loan</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.loans.pagibig') }}">Pag-Ibig Loan</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.loans.company') }}">Company Loan</a></li>
                                    </ul>
                                </li>
                            @elseif(auth()->user()->role === 'hr')
                                <li class="nav-item">
                                    <a class="nav-link " href="{{ route('hr.approve') }}">Approve Attendance</a></li>
                                  <li class="nav-item"><a class="nav-link" href="{{ route('hr.attendance') }}">Monitor Attendance</a></li>
                              <li class="nav-item"><a class="nav-link" href="{{ route('hr.approveleave') }}">Approve Leave & Overtime</a></li>
                              <li class="nav-item"><a class="nav-link" href="{{ route('hr.reports') }}">Generate Reports</a></li>


                            @elseif(auth()->user()->role === 'employee')
                                <li class="nav-item"><a class="nav-link" href="{{ route('profile.edit') }}">My Profile</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('attendance.my') }}">My Attendance</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('attendance.requests') }}">Request Leave/Overtime</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('payslip.index') }}">Payslips</a></li>
                            @endif
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if(Route::has('about'))
                                <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About</a></li>
                            @endif
                            @if(Route::has('contact'))
                                <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact Us</a></li>
                            @endif
                            @if(Route::has('login'))
                                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" 
                                   data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Account Settings</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>
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

        <main class="py-4 container">
            @yield('content')
            {{-- Or use $slot if you prefer --}}
        </main>
    </div>
    <!-- Include Theme Configuration Sidebar -->
    @include('layouts.right-sidebar')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Additional Scripts -->
    @stack('scripts')
</body>
</html>
