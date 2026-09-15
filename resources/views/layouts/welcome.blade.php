<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#171117">
    <title>@yield('title', 'Place Of Beauty ESS')</title>

    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .public-shell {
            --public-bg: #fbf9fa;
            --public-panel: rgba(255,255,255,.72);
            --public-ink: #21181f;
            --public-muted: #766a72;
            --public-line: rgba(65,43,57,.10);
            --public-rose: #c94f80;
            --public-violet: #7d68b8;
            --public-gold: #d8b878;
            background:
                radial-gradient(circle at 8% 4%, rgba(201,79,128,.13), transparent 24rem),
                radial-gradient(circle at 92% 12%, rgba(125,104,184,.12), transparent 30rem),
                linear-gradient(180deg, #fff 0%, var(--public-bg) 56%, #f8f3f6 100%);
            color: var(--public-ink);
        }
        .public-shell.dark {
            --public-bg: #151116;
            --public-panel: rgba(31,25,31,.74);
            --public-ink: #fbf6f9;
            --public-muted: #bfb2bb;
            --public-line: rgba(255,255,255,.09);
            background:
                radial-gradient(circle at 8% 4%, rgba(201,79,128,.15), transparent 24rem),
                radial-gradient(circle at 92% 12%, rgba(125,104,184,.14), transparent 30rem),
                #151116;
        }
        .public-nav {
            position: fixed;
            top: 14px;
            left: 0;
            right: 0;
            z-index: 1100;
            padding: 0;
            background: transparent !important;
            border: 0 !important;
        }
        .public-nav > .container {
            min-height: 64px;
            padding: .55rem .7rem .55rem .85rem;
            border: 1px solid rgba(255,255,255,.72);
            border-radius: 22px;
            background: rgba(255,255,255,.72);
            box-shadow: 0 18px 50px rgba(63,39,53,.09);
            backdrop-filter: blur(22px) saturate(150%);
            -webkit-backdrop-filter: blur(22px) saturate(150%);
        }
        .public-shell.dark .public-nav > .container {
            background: rgba(28,22,28,.78);
            border-color: var(--public-line);
            box-shadow: 0 18px 50px rgba(0,0,0,.24);
        }
        .public-brand { display: inline-flex; align-items: center; gap: .7rem; color: var(--public-ink) !important; text-decoration: none; font-weight: 800; letter-spacing: -.03em; }
        .public-brand img { width: 40px; height: 40px; object-fit: contain; border-radius: 13px; filter: drop-shadow(0 8px 16px rgba(132,66,101,.16)); }
        .public-nav .nav-link { color: var(--public-muted) !important; font-weight: 700; font-size: .9rem; padding: .55rem .85rem !important; border-radius: 999px; }
        .public-nav .nav-link:hover, .public-nav .nav-link.active { color: var(--public-ink) !important; background: rgba(201,79,128,.09); }
        .public-login-btn { display: inline-flex; align-items: center; gap: .5rem; padding: .7rem 1rem; border-radius: 999px; color: #fff !important; text-decoration: none; font-weight: 800; background: linear-gradient(135deg, #a93462, var(--public-rose), var(--public-violet)); box-shadow: 0 12px 26px rgba(169,52,98,.22); transition: transform .2s ease, box-shadow .2s ease; }
        .public-login-btn:hover { transform: translateY(-2px); box-shadow: 0 16px 32px rgba(169,52,98,.28); }
        .public-main { padding-top: 94px !important; }
        .public-footer { margin-top: 0 !important; padding: 4.5rem 0 2rem; border-top: 1px solid var(--public-line); background: rgba(255,255,255,.42); }
        .public-shell.dark .public-footer { background: rgba(255,255,255,.015); }
        .public-footer .footer-title { color: var(--public-ink); font-size: .78rem; letter-spacing: .11em; text-transform: uppercase; font-weight: 800; margin-bottom: .75rem; }
        .public-footer .footer-link { display: inline-block; margin: .28rem 0; color: var(--public-muted); text-decoration: none; font-weight: 650; }
        .public-footer .footer-link:hover { color: var(--public-rose); }
        .public-shell .section-copy, .public-shell .text-secondary { color: var(--public-muted) !important; }
        .public-shell .navbar-toggler { color: var(--public-ink); }
        .public-shell [data-theme-toggle] { border-color: var(--public-line) !important; color: var(--public-ink); background: rgba(255,255,255,.42); }
        .public-shell.dark [data-theme-toggle] { background: rgba(255,255,255,.04); }
        @media (max-width: 991px) {
            .public-nav { top: 8px; padding: 0 .45rem; }
            .public-nav > .container { border-radius: 18px; }
            #publicMenu { padding: .65rem .25rem .25rem; }
            .public-nav .nav-link { padding: .75rem .85rem !important; }
            .public-main { padding-top: 82px !important; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; animation-duration: .001ms !important; animation-iteration-count: 1 !important; transition-duration: .001ms !important; }
        }
    </style>

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
                <button type="button" class="btn btn-sm rounded-circle d-grid" data-theme-toggle aria-label="Toggle theme" style="width:40px;height:40px;place-items:center;">
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

    <footer class="public-footer">
        <div class="container">
            <div class="row g-4 align-items-start">
                <div class="col-lg-5">
                    <a class="public-brand mb-3" href="{{ url('/') }}">
                        <img src="{{ asset('img/logo.png') }}" alt="Place Of Beauty logo">
                        <span>Place Of Beauty ESS</span>
                    </a>
                    <p class="section-copy mb-0" style="max-width:520px;">A polished employee self-service workspace for attendance, schedules, leave, overtime, payroll, reports and day-to-day HR tasks.</p>
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
            <div class="d-flex flex-column flex-md-row justify-content-between gap-2 border-top mt-4 pt-3" style="border-color:var(--public-line)!important;">
                <small class="text-secondary">© {{ date('Y') }} Place Of Beauty. Employee Self-Service System.</small>
                <small class="text-secondary">A modern workspace for a better workday.</small>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
