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

        body.dark .card,
        body.dark .dropdown-menu,
        body.dark .form-control,
        body.dark .form-select {
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

        .navbar-dark .navbar-brand,
        .navbar-light .navbar-brand {
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

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    <!-- Left Side -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                            </li>

                            @if(auth()->user()->role === 'admin')
                                <li class="nav-item"><a class="nav-link" href="{{ route('admin.index') }}">Employees</a></li>

                                <li class="nav-item dropdown">
                                    <a id="deductionsDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                        Deductions & Contributions
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('admin.loans.sss') }}">SSS Loan</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.loans.pagibig') }}">Pag-Ibig Loan</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.loans.company') }}">Company Loan</a></li>
                                    </ul>
                                </li>

                            @elseif(auth()->user()->role === 'hr')
                                <li class="nav-item"><a class="nav-link" href="{{ route('hr.approve') }}">Approve Attendance</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('hr.attendance') }}">Monitor Attendance</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('hr.approveleave.show') }}">Approve Leave</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('hr.approveOvertime.show') }}">Approve Overtime</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('hr.reports') }}">Generate Reports</a></li>

                            @elseif(auth()->user()->role === 'employee')
                                <li class="nav-item"><a class="nav-link" href="{{ route('profile.edit') }}">My Profile</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('attendance.my') }}">My Attendance</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('attendance.requests') }}">Request Leave</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('overtime.index') }}">Overtime</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('payslip.index') }}">Payslips</a></li>
                            @endif
                        @endauth
                    </ul>

                    <!-- Right Side -->
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
                                   data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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

        <!-- Main Content -->
        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- Optional Right Sidebar -->
    @includeWhen(View::exists('layouts.right-sidebar'), 'layouts.right-sidebar')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Script Slot -->
    @stack('scripts')
</body>
</html>
