<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ config('app.name', 'Employee Self-Service') }} - Place Of Beauty</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logo.png') }}">

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
             --bs-primary: #dd1f7e;
            --bs-dark-bg: #1a1a1a;
            --bs-light-bg: hsla(324, 14%, 93%, 1.00);
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

        .dropdown-item:hover {
            background-color: #f0f0f0;
        }

        body.dark .dropdown-item:hover {
            background-color: #f108ac;
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
    /* Navbar */
    .navbar {
        background-color: #ff69b4 !important; /* Baby pink */
        border-bottom: none; /* slightly deeper pink for definition */
    }

    .navbar .navbar-brand,
    .navbar .nav-link {
        color: #000 !important;
        font-weight: 500;
        transition: color 0.3s ease, background-color 0.3s ease;
    }

    .navbar .nav-link:hover,
    .navbar .nav-link:focus,
    .navbar .nav-item.active .nav-link {
        color: #fff !important;
        background-color: #f4a8c4 !important;
        border-radius: 6px;
    }

    .dropdown-menu {
        background-color: #FADADD !important;
        border: 1px solid #f8c8dc !important;
    }

    .dropdown-item {
        color: #000 !important;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .dropdown-item:hover,
    .dropdown-item:focus {
        background-color: #f4a8c4 !important;
        color: #fff !important;
    }

    .navbar-toggler {
        border-color: #f4a8c4 !important;
    }

    .navbar-toggler-icon {
        background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='black' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E") !important;
    }

    .navbar,
    .dropdown-menu,
    .navbar .nav-link {
        transition: background-color 0.3s ease, color 0.3s ease;
    }
    
    /* Navbar Darkmode */
    body.dark-mode .navbar {
        background-color: #333 !important; /* Dark background */
        border-bottom: 2px solid #555 !important; /* Darker border */
    }

    body.dark .navbar .navbar-brand,
    body.dark .navbar .nav-link {
        color: #fff !important;
        font-weight: 500;
        transition: color 0.3s ease, background-color 0.3s ease;
    }

    body.dark-mode .navbar .nav-link:hover,
    body.dark-mode .navbar .nav-link:focus,
    body.dark-mode .navbar .nav-item.active .nav-link {
        color: #fff !important;
        background-color: #f4a8c4 !important;
        border-radius: 6px;
    }

    body.dark-mode .dropdown-menu {
        background-color: #FADADD !important;
        border: 1px solid #f8c8dc !important;
    }

    body.dark-mode .dropdown-item {
        color: #fff !important;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    body.dark-mode .dropdown-item:hover,
    .dropdown-item:focus {
        background-color: #f4a8c4 !important;
        color: #fff !important;
    }

    body.dark-mode .navbar-toggler {
        border-color: #f4a8c4 !important;
    }

    body.dark-mode .navbar-toggler-icon {
        background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='black' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E") !important;
    }

    body.dark-mode .navbar,
    body.dark-mode .dropdown-menu,
    body.dark-mode .navbar .nav-link {
        transition: background-color 0.3s ease, color 0.3s ease;
    }
    </style>

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
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
                                <a class="nav-link" href="{{ route('dashboard') }}" class="nav-link">
                                    <i class="fas fa-tachometer-alt nav-icon me-2"></i>Dashboard</a>
                            </li>

                            @if(auth()->user()->role === 'admin')
                                <li class="nav-item"><a class="nav-link" href="{{ route('admin.index') }}"><i class="fas fa-users nav-icon me-2"></i> Employees</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('admin.activity-logs.index') }}"><i class="fas fa-history nav-icon me-2"></i> Activity Logs</a></li>
                                                    <li class="nav-item">
                                <a href="{{ route('admin.stores.index') }}" class="nav-link">
                                    <i class="fas fa-map-marker-alt nav-icon me-2"></i> Store Locations
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.networks.index') }}" class="nav-link">
                                    <i class="fas fa-network-wired nav-icon me-2"></i> Allowed Networks
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.sms.index') }}" class="nav-link">
                                    <i class="fas fa-sms nav-icon me-2"></i> SMS Configuration
                                </a>
                            </li>

                            @elseif(auth()->user()->role === 'hr' || auth()->user()->role === 'manager')
                                                    <li class="nav-item">
                        <a href="#" class="nav-link dropdown-toggle" id="attendanceToggle">
                            <i class="fas fa-calendar-check nav-icon me-2"></i> Attendance Management
                        </a>
                        <ul class="nav flex-column ms-4" id="attendanceMenu" style="display: none;">
                            <li class="nav-item">
                                <a href="{{ route('hr.pending-approvals') }}" class="nav-link">
                                    <i class="far fa-circle me-2"></i>Pending Approvals
                                    @php
                                        $pendingCount = \App\Models\Attendance::where('status', 'pending')->count();
                                    @endphp
                                    @if($pendingCount > 0)
                                        <span class="badge bg-warning text-dark ms-1">{{ $pendingCount }}</span>
                                    @endif
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('hr.create-for-employee.form') }}" class="nav-link">
                                    <i class="far fa-circle me-2"></i>Create for Employee
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('hr.attendance') }}" class="nav-link">
                                    <i class="far fa-circle me-2"></i>Monitor Attendance
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item"><a href="{{ route('schedules.index') }}" class="nav-link"><i class="fas fa-calendar-week me-2"></i>Manage Schedules</a></li>
                    <li class="nav-item"><a href="{{ route('hr.approveleave.show') }}" class="nav-link"><i class="fas fa-plane-departure me-2"></i>Approve Leave</a></li>
                    <li class="nav-item"><a href="{{ route('hr.approveOvertime.show') }}" class="nav-link"><i class="fas fa-clock me-2"></i>Approve Overtime</a></li>
                    <li class="nav-item"><a href="{{ route('hr.reports') }}" class="nav-link"><i class="fas fa-file-alt me-2"></i>Generate Reports</a></li>
               
               
                    @elseif(auth()->user()->role === 'employee')
                                <li class="nav-item"><a class="nav-link" href="{{ route('profile.edit') }}">My Profile</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('attendance.my') }}">My Attendance</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('attendance.requests') }}">Request Leave</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('overtime.index') }}">Overtime</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('schedules.my') }}"><i class="fas fa-calendar-alt me-2"></i>My Schedule</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('payslip.index') }}">Payslips</a></li>
                            @endif
                        @endauth
                    </ul>

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


    <!-- Footer (Guest Only) -->
        @guest
        <footer>
            <div class="container">
                <div class="row text-center text-md-start">
                    <div class="col-md-3 mb-3">
                        <h5>About ESS</h5>
                        <p>Employee Self-Service (ESS) Streamline employee attendance, reports, and tasks efficiently.</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <h5>Links</h5>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('about') }}">About</a></li>
                            <li><a href="{{ route('contact') }}">Contact Us</a></li>
                            <li><a href="{{ route('terms') }}">Terms & Conditions</a></li>
                            <li><a href="{{ route('system-info') }}">System Info</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3 mb-3">
                        <h5>Developer</h5>
                        <p>Christian Aring And Rovic Cabansag</p>
                        <p>Email: <a href="mailto:chba.aring.sjc@phinmaed.com">chba.aring.sjc@phinmaed.com</a></p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <h5>Social</h5>
                        <a href="https://www.facebook.com/christianaring6" class="me-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://www.instagram.com/crstn.aring6" class="me-2"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.linkedin.com/in/christian-aring-57bab1344/"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="text-center mt-3">
                    &copy; {{ date('Y') }} ESS. All rights reserved.
                </div>
            </div>
        </footer>
        @endguest
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Script Slot -->
    @stack('scripts')
</body>
</html>
