<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#211b20">
    <title>@yield('title', 'Place Of Beauty ESS')</title>

    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
</head>
<body class="public-shell {{ (Auth::check() && Auth::user()->display_mode === 'dark') ? 'dark' : '' }}">
    <nav class="navbar navbar-expand-lg public-nav">
        <div class="container">
            <a class="public-brand" href="{{ url('/') }}" aria-label="Place Of Beauty ESS home">
                <img src="{{ asset('img/logo.png') }}" alt="Place Of Beauty logo">
                <span>Place Of Beauty <span class="d-none d-sm-inline">ESS</span></span>
            </a>

            <div class="d-flex align-items-center gap-2 order-lg-3">
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle d-grid place-items-center" data-theme-toggle aria-label="Toggle theme" style="width:40px;height:40px;place-items:center;">
                    <i class="bi bi-moon-stars"></i>
                </button>
                @auth
                    <a class="public-login-btn d-none d-sm-inline-flex" href="{{ route('dashboard') }}"><i class="bi bi-grid"></i> Dashboard</a>
                @else
                    <a class="public-login-btn d-none d-sm-inline-flex" href="{{ route('login') }}"><i class="bi bi-person-lock"></i> Employee Login</a>
                @endauth
                <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#publicMenu" aria-controls="publicMenu" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="bi bi-list fs-3"></i>
                </button>
            </div>

            <div class="collapse navbar-collapse order-lg-2" id="publicMenu">
                <ul class="navbar-nav ms-auto me-lg-3 align-items-lg-center gap-lg-1">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">About</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('terms') ? 'active' : '' }}" href="{{ route('terms') }}">Terms</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('system-info') ? 'active' : '' }}" href="{{ route('system-info') }}">System Info</a></li>
                    @guest
                        <li class="nav-item d-sm-none mt-2"><a class="public-login-btn w-100 justify-content-center" href="{{ route('login') }}"><i class="bi bi-person-lock"></i> Employee Login</a></li>
                    @else
                        <li class="nav-item d-sm-none mt-2"><a class="public-login-btn w-100 justify-content-center" href="{{ route('dashboard') }}"><i class="bi bi-grid"></i> Dashboard</a></li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="public-main">
        @yield('content')
    </main>

    <footer class="public-footer mt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-5">
                    <a class="public-brand mb-3" href="{{ url('/') }}">
                        <img src="{{ asset('img/logo.png') }}" alt="Place Of Beauty logo">
                        <span>Place Of Beauty ESS</span>
                    </a>
                    <p class="section-copy mb-0" style="max-width:520px;">A secure employee self-service workspace for attendance, schedules, leave, overtime, payroll, reports, and day-to-day HR tasks.</p>
                </div>
                <div class="col-6 col-lg-2 ms-lg-auto">
                    <div class="footer-title">Explore</div>
                    <a class="footer-link" href="{{ route('about') }}">About</a><br>
                    <a class="footer-link" href="{{ route('system-info') }}">System Info</a><br>
                    <a class="footer-link" href="{{ route('login') }}">Employee Login</a>
                </div>
                <div class="col-6 col-lg-2">
                    <div class="footer-title">Support</div>
                    <a class="footer-link" href="{{ route('contact') }}">Contact</a><br>
                    <a class="footer-link" href="{{ route('terms') }}">Terms & Conditions</a>
                </div>
            </div>
            <div class="d-flex flex-column flex-md-row justify-content-between gap-2 border-top mt-4 pt-3" style="border-color:var(--pob-line)!important;">
                <small class="text-secondary">© {{ date('Y') }} Place Of Beauty. Employee Self-Service System.</small>
                <small class="text-secondary">Designed for a clear, secure and human workday.</small>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
