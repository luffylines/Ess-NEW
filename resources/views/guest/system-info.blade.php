<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>System Information - {{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />

    <style>
        body.light {
            background-color: #ffffff;
            color: #212529;
        }

        body.dark {
            background-color: #1a1a1a;
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
        .dropdown-item:hover {
            background-color: #f0f0f0;
        }

        body.dark .dropdown-item:hover {
            background-color: #444;
        }

        body {
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .system-title {
            font-size: 2.5rem;
            font-weight: bold;
            color: #212529;
            text-align: center;
            margin-bottom: 30px;
        }
        .card, .navbar, .form-control, .form-select {
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }
        .card, .list-group-item {
            background-color: #ff69b4;
        
            border-bottom: #000000 1px solid;
        }
        main {
            margin-top: 80px;
        }
            .navbar {
        background-color: #ff69b4 !important; /* Baby pink */
        border-bottom: 2px solid #ff69b4; /* slightly deeper pink for definition */
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
    </style>
</head>
<body class="{{ (Auth::check() && Auth::user()->display_mode === 'dark') ? 'dark' : 'light' }}">
    <div id="app">
        <!-- NAVBAR -->
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
                    <!-- Right Side Of Navbar -->
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
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
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

        <!-- MAIN CONTENT -->
        <main class="container py-5" style="margin-top: 90px;">
            <h1 class="system-title">System Information</h1>
            <div class="card p-4 shadow-sm">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>App Version:</strong> 1.0.0</li>
                    <li class="list-group-item"><strong>Developer:</strong> Christian Baynado Aring</li>
                    <li class="list-group-item"><strong>PHP Version:</strong> {{ phpversion() }}</li>
                    <li class="list-group-item"><strong>Laravel Version:</strong> {{ app()->version() }}</li>
                    <li class="list-group-item"><strong>Server Software:</strong> {{ $_SERVER['SERVER_SOFTWARE'] ?? 'N/A' }}</li>
                    <li class="list-group-item"><strong>Database Connection:</strong> {{ config('database.default') }}</li>
                    <li class="list-group-item"><strong>Hosting:</strong> Ngrok</li>
                </ul>
            </div>
        </main>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
