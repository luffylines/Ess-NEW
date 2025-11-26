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
            --bs-primary: #212529;
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

        /* Custom Styles for Terms & Conditions */
        .terms-section {
            margin-top: 100px;
        }

        .terms-title {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--bs-primary);
            text-align: center;
            margin-bottom: 30px;
        }

        .terms-content {
            font-size: 1.1rem;
            line-height: 1.7;
            max-width: 800px;
            margin: 0 auto;
            padding: 0 15px;
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

        <div class="container terms-section">
            <h1 class="terms-title">Terms & Conditions</h1>
            <div class="terms-content">
                <p>Welcome to the Employee Self-Service (ESS) System. By accessing or using this system, you agree to comply with the following terms and conditions:</p>

                <ul>
                    <li><strong>Use Policy:</strong> The ESS system is intended for authorized employees only. Unauthorized use or access is strictly prohibited.</li>
                    <li><strong>Data Privacy:</strong> All personal and work-related information entered into the system is confidential and should be handled in accordance with company data privacy policies.</li>
                    <li><strong>Account Responsibility:</strong> Users are responsible for maintaining the confidentiality of their login credentials and must not share their accounts with others.</li>
                    <li><strong>Prohibited Activities:</strong> Users shall not use the system for any illegal activities, harassment, or actions that violate company policies.</li>
                    <li><strong>System Availability:</strong> The company strives to maintain system uptime but does not guarantee uninterrupted access and reserves the right to perform maintenance at any time.</li>
                    <li><strong>Feedback and Reporting:</strong> Users are encouraged to report bugs or provide feedback to improve the system through the designated contact channels.</li>
                    <li><strong>Compliance:</strong> Failure to comply with these terms may result in disciplinary action, including suspension of system access or termination of employment.</li>
                </ul>

                <p>If you have any questions about these terms, please contact the system administrator or your HR department.</p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
