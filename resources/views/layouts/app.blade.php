<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Phosphor Icons -->
    <link href="https://unpkg.com/@phosphor-icons/web@2.0.3/src/bold/style.css" rel="stylesheet">
    
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
        body.light,
        html:not(.dark) body {
            background-color: #ffffff;
            color: #212529;
        }

        /* Dark theme */
        body.dark,
        html.dark body {
            background-color: #1a1a1a;
            color: #ffffff;
        }

        /* Navbar styling */
        body.dark .navbar,
        html.dark .navbar {
            background-color: #2d2d2d !important;
        }

        /* Card styling */
        body.dark .card,
        html.dark .card {
            background-color: #2d2d2d;
            border-color: #444;
            color: #ffffff;
        }

        /* Table styling */
        body.dark .table,
        html.dark .table {
            --bs-table-bg: #2d2d2d;
            --bs-table-color: #ffffff;
        }

        /* Form controls */
        body.dark .form-control,
        body.dark .form-select,
        html.dark .form-control,
        html.dark .form-select {
            background-color: #3d3d3d;
            border-color: #555;
            color: #ffffff;
        }

        body.dark .form-control:focus,
        html.dark .form-control:focus {
            background-color: #3d3d3d;
            border-color: #0d6efd;
            color: #ffffff;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        /* Button styling */
        body.dark .btn-outline-secondary,
        html.dark .btn-outline-secondary {
            color: #ffffff;
            border-color: #6c757d;
        }

        body.dark .btn-outline-secondary:hover,
        html.dark .btn-outline-secondary:hover {
            background-color: #6c757d;
            color: #ffffff;
        }

        /* Theme configuration offcanvas styling */
        body.dark .offcanvas,
        html.dark .offcanvas {
            background-color: #2d2d2d;
            color: #ffffff;
        }

        body.dark .list-group-item,
        html.dark .list-group-item {
            background-color: #3d3d3d;
            border-color: #555;
            color: #ffffff;
        }

        body.dark .list-group-item:hover,
        html.dark .list-group-item:hover {
            background-color: #4d4d4d;
        }

        body.dark .form-check-input:checked,
        html.dark .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        /* Dropdown and modal styling */
        body.dark .dropdown-menu,
        html.dark .dropdown-menu {
            background-color: #2d2d2d;
            border-color: #444;
        }

        body.dark .dropdown-item,
        html.dark .dropdown-item {
            color: #ffffff;
        }

        body.dark .dropdown-item:hover,
        html.dark .dropdown-item:hover {
            background-color: #3d3d3d;
            color: #ffffff;
        }

        /* Alert styling */
        body.dark .alert,
        html.dark .alert {
            background-color: #2d2d2d;
            border-color: #444;
            color: #ffffff;
        }

        /* Pagination styling */
        body.dark .page-link,
        html.dark .page-link {
            background-color: #3d3d3d;
            border-color: #555;
            color: #ffffff;
        }

        body.dark .page-link:hover,
        html.dark .page-link:hover {
            background-color: #4d4d4d;
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
    
    <!-- Theme initialization script to prevent flash -->
    <script>
        // Apply saved theme immediately to prevent flash
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            const html = document.documentElement;
            const body = document.body;
            
            if (savedTheme === 'auto') {
                const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (isDark) {
                    html.classList.add('dark');
                    body.classList.add('dark');
                } else {
                    body.classList.add('light');
                }
            } else if (savedTheme === 'dark') {
                html.classList.add('dark');
                body.classList.add('dark');
            } else {
                body.classList.add('light');
            }
        })();
    </script>
</head>
    
</head>
<body>
    <div id="app">
        <!-- Navigation -->
        @if(isset($showNavigation) ? $showNavigation : true)
            <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
                <div class="container">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                    
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    
                    <div class="collapse navbar-collapse" id="navbarNav">
                         <ul class="navbar-nav me-auto">
                           {{-- <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                            </li> --}}
                            <!-- Add more navigation items here -->
                        </ul>
                        
                        <ul class="navbar-nav">
                            @guest
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">Register</a>
                                </li>
                            @else
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                        {{ Auth::user()->name }}
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="dropdown-item">Logout</button>
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>
        @endif

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif
        
        @include('layouts.sidebar')
        <!-- Page Content -->
        <main class="py-4">
            @yield('content')
            {{ $slot ?? '' }}
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