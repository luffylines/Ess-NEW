<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Employee Self-Service') }} - Place Of Beauty</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logo.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --bs-dark-bg: #1a1a1a;
            --bs-light-bg: #ffffff;
            --sidebar-collapsed: 72px;
            --sidebar-expanded: 270px;
        }

        body.light { background-color: var(--bs-light-bg); color: #212529; }
        body.dark { background-color: var(--bs-dark-bg); color: #ffffff; }

        nav.navbar { transition: left .28s ease, width .28s ease, background-color .3s ease, color .3s ease; z-index: 1050; }
        body.light .navbar { background-color: rgba(255,255,255,.88) !important; color: #212529 !important; backdrop-filter: blur(18px); border-bottom: 1px solid rgba(53,40,49,.08); }
        body.dark .navbar { background-color: rgba(31,27,31,.92) !important; color: #ffffff !important; backdrop-filter: blur(18px); border-bottom: 1px solid rgba(255,255,255,.08); }

        .app-navbar {
            left: var(--sidebar-collapsed) !important;
            width: calc(100% - var(--sidebar-collapsed));
            min-height: 68px;
        }
        body.sidebar-expanded .app-navbar {
            left: var(--sidebar-expanded) !important;
            width: calc(100% - var(--sidebar-expanded));
        }

        .navbar-toggler { border: 1px solid rgba(0,0,0,.1); padding: .375rem .5rem; border-radius: .75rem; }
        body.dark .navbar-toggler { border-color: rgba(255,255,255,.3); color: #fff !important; }
        .navbar-brand { font-weight: 800; color: inherit !important; letter-spacing: -.025em; }
        .navbar-logo { transition: transform .3s ease; }
        .navbar-brand:hover .navbar-logo { transform: scale(1.06) rotate(-2deg); }

        main {
            margin-left: var(--sidebar-collapsed);
            padding-top: 78px;
            min-height: 100vh;
            transition: margin-left .28s cubic-bezier(.2,.7,.2,1);
        }
        body.sidebar-expanded main { margin-left: var(--sidebar-expanded); }

        #themeToggle { border-radius: 50%; width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; transition: transform .2s; }
        #themeToggle:hover { transform: scale(1.08); }

        body, .navbar, .card, .form-control, .main-sidebar { transition: background-color .3s ease, color .3s ease, border-color .3s ease, box-shadow .3s ease; }
        .main-sidebar { overflow-y: auto; }
        .mobile-menu-toggle { display: none; background: none; border: none; font-size: 1.5rem; color: inherit; cursor: pointer; z-index: 1051; }
        .sidebar-overlay { position: fixed; inset: 0; background: rgba(18,13,17,.44); backdrop-filter: blur(3px); z-index: 1035; display: none; }
        .sidebar-overlay.show { display: block; }

        /* Do not expand on hover anymore — only the sidebar button controls desktop width. */
        @media (min-width: 993px) {
            .pob-sidebar:not(.expanded):hover { width: var(--sidebar-collapsed) !important; }
            .pob-sidebar:not(.expanded):hover .menu-text { display: none !important; }
            .pob-sidebar.expanded { width: var(--sidebar-expanded) !important; }
            .pob-sidebar.expanded .menu-text { display: block !important; }
        }

        @media (max-width: 992px) {
            .app-navbar { left: 0 !important; width: 100% !important; }
            main { margin-left: 0 !important; padding-left: 15px; padding-right: 15px; padding-top: 74px; }
            .mobile-menu-toggle { display: block; }
            .navbar .container-fluid { position: relative; }
        }

        @media (max-width: 576px) {
            main { padding-left: 10px; padding-right: 10px; }
            .navbar-brand { font-size: 1rem; }
            .navbar-logo { width: 28px !important; height: 28px !important; }
            .container-fluid { padding-left: 10px !important; padding-right: 10px !important; }
            .card { margin-bottom: 1rem; }
            .row > [class*="col-"] { margin-bottom: 15px; }
            .table-responsive { font-size: .875rem; }
            .btn-group .btn { font-size: .75rem; padding: .25rem .5rem; }
            .form-control { font-size: 16px; }
        }
    </style>

    <script>
        (function () {
            const theme = localStorage.getItem('theme') || 'light';
            document.documentElement.classList.add(theme);
            document.addEventListener('DOMContentLoaded', () => document.body.classList.add(theme));
        })();
    </script>
</head>

<body class="{{ request()->routeIs('attendance.my') ? 'attendance-premium-page' : '' }}">
<div id="app">
    <nav class="navbar navbar-expand-lg fixed-top shadow-sm app-navbar">
        <div class="container-fluid px-4">
            <button class="mobile-menu-toggle me-2" id="mobileMenuToggle" type="button" aria-label="Open sidebar">
                <i class="bi bi-layout-sidebar-inset"></i>
            </button>

            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                <img src="{{ asset('img/logo.png') }}" alt="Company Logo" class="navbar-logo" style="width:32px;height:32px;object-fit:contain;">
                <span class="fw-bold">Place Of Beauty</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars fa-lg"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item me-3">
                        <button class="btn btn-outline-secondary btn-sm" id="themeToggle" title="Toggle Theme">
                            <i class="bi bi-moon" id="themeIcon"></i>
                        </button>
                    </li>
                    @guest
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">{{ Auth::user()->name }}</a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="dropdown-item">Logout</button></form></li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    @includeIf('layouts.sidebar')

    <main>
        @yield('content')
        {{ $slot ?? '' }}
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="//unpkg.com/alpinejs" defer></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');
    const body = document.body;
    const html = document.documentElement;
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    function setTheme(theme) {
        body.classList.remove('light', 'dark');
        html.classList.remove('light', 'dark');
        body.classList.add(theme);
        html.classList.add(theme);
        localStorage.setItem('theme', theme);
        if (themeIcon) themeIcon.className = theme === 'dark' ? 'bi bi-sun' : 'bi bi-moon';
    }
    setTheme(localStorage.getItem('theme') || 'light');
    themeToggle?.addEventListener('click', () => setTheme(body.classList.contains('dark') ? 'light' : 'dark'));

    function syncSidebarLayout() {
        if (!sidebar || window.innerWidth <= 992) {
            body.classList.remove('sidebar-expanded');
            return;
        }
        body.classList.toggle('sidebar-expanded', sidebar.classList.contains('expanded'));
    }

    if (sidebar) {
        new MutationObserver(syncSidebarLayout).observe(sidebar, { attributes: true, attributeFilter: ['class'] });
        syncSidebarLayout();
    }

    function toggleMobileSidebar() {
        sidebar?.classList.toggle('mobile-active');
        sidebarOverlay?.classList.toggle('show');
        document.body.style.overflow = sidebar?.classList.contains('mobile-active') ? 'hidden' : '';
    }
    function closeMobileSidebar() {
        sidebar?.classList.remove('mobile-active');
        sidebarOverlay?.classList.remove('show');
        document.body.style.overflow = '';
    }

    mobileMenuToggle?.addEventListener('click', toggleMobileSidebar);
    sidebarOverlay?.addEventListener('click', closeMobileSidebar);
    window.addEventListener('resize', () => { if (window.innerWidth > 992) closeMobileSidebar(); syncSidebarLayout(); });
    sidebar?.addEventListener('click', (e) => {
        const link = e.target.closest('.nav-link');
        if (window.innerWidth <= 992 && link && !link.classList.contains('dropdown-toggle') && !link.classList.contains('sidebar-submenu-toggle')) closeMobileSidebar();
    });
});
</script>

@auth
    @if(auth()->user()->role === 'employee')
        @include('components.ai-chatbot')
    @endif
@endauth

@if(request()->routeIs('attendance.my'))
<style>
/* Premium My Attendance — visual-only override, keeps all existing attendance logic intact. */
.attendance-premium-page main {
    background:
        radial-gradient(circle at 8% 4%, rgba(201,79,128,.10), transparent 26rem),
        radial-gradient(circle at 92% 12%, rgba(125,104,184,.10), transparent 30rem),
        #faf8f9;
}
.attendance-premium-page.dark main { background:#171317; }
.attendance-premium-page main > .px-4 { max-width:1180px; margin:0 auto; padding-top:1.2rem !important; padding-bottom:3rem; }
.attendance-premium-page main > .px-4 > h1 {
    font-size:clamp(2.15rem,4vw,3.5rem);
    letter-spacing:-.045em;
    font-weight:800;
    margin:0 !important;
    color:var(--pob-text,#211b20);
}
.attendance-premium-page main > .px-4 > .text-end { margin-top:-2.25rem; margin-bottom:1.8rem; color:#8b7d86; }
.attendance-premium-page main > .px-4::before {
    content:'MY WORKDAY';
    display:inline-flex;
    align-items:center;
    margin-bottom:.55rem;
    padding:.38rem .68rem;
    border-radius:999px;
    border:1px solid rgba(201,79,128,.18);
    background:rgba(255,255,255,.68);
    color:#a53a67;
    font-size:.68rem;
    letter-spacing:.12em;
    font-weight:800;
}
.attendance-premium-page main .card {
    border:1px solid rgba(65,43,57,.10) !important;
    border-radius:26px !important;
    background:rgba(255,255,255,.82) !important;
    box-shadow:0 22px 55px rgba(61,37,53,.09) !important;
    backdrop-filter:blur(18px) saturate(145%);
    overflow:hidden;
}
.attendance-premium-page.dark main .card { background:rgba(35,29,35,.84) !important; border-color:rgba(255,255,255,.09) !important; }
.attendance-premium-page main .card.mx-auto { max-width:820px !important; }
.attendance-premium-page main .card-header {
    padding:1.15rem 1.35rem !important;
    border:0 !important;
    background:linear-gradient(135deg,#a93462,#c94f80 55%,#7d68b8) !important;
    color:#fff !important;
}
.attendance-premium-page main .card-header h4 { font-weight:800; letter-spacing:-.025em; font-size:1.05rem; }
.attendance-premium-page main .card-body { padding:clamp(1.35rem,3vw,2.2rem) !important; }
.attendance-premium-page #currentTime {
    color:#a93462 !important;
    font-family:'Figtree',sans-serif !important;
    font-weight:800 !important;
    font-size:1.15rem;
}
.attendance-premium-page main .display-4 {
    width:86px; height:86px; display:grid; place-items:center; margin:0 auto 1rem !important;
    border-radius:28px;
    background:linear-gradient(135deg,rgba(201,79,128,.13),rgba(125,104,184,.12));
    color:#b84072 !important;
    font-size:2rem !important;
}
.attendance-premium-page main h5 { font-weight:800; letter-spacing:-.02em; font-size:1.35rem; }
.attendance-premium-page #locationStatus { margin-top:.75rem; }
.attendance-premium-page main .text-info { color:#7f6d78 !important; line-height:1.65; }
.attendance-premium-page main .btn-lg {
    min-height:56px;
    border:0 !important;
    border-radius:16px !important;
    font-weight:800;
    letter-spacing:-.01em;
    box-shadow:0 13px 28px rgba(70,45,60,.12);
    transition:transform .2s ease,box-shadow .2s ease;
}
.attendance-premium-page main .btn-lg:hover { transform:translateY(-2px); box-shadow:0 17px 34px rgba(70,45,60,.16); }
.attendance-premium-page #btnTimeIn { background:linear-gradient(135deg,#24815e,#42a47d) !important; }
.attendance-premium-page #btnBreakIn { background:linear-gradient(135deg,#b87824,#dda548) !important; color:#fff !important; }
.attendance-premium-page #btnBreakOut { background:linear-gradient(135deg,#577aa8,#7698c4) !important; color:#fff !important; }
.attendance-premium-page #btnTimeOut { background:linear-gradient(135deg,#ad4654,#d16473) !important; }
.attendance-premium-page main .alert { border:0; border-radius:18px; box-shadow:none; }
.attendance-premium-page main .table-responsive { border-radius:18px; overflow:hidden; }
.attendance-premium-page main .table { margin-bottom:0; }
.attendance-premium-page main .table thead th { background:rgba(201,79,128,.07); border:0; color:#756771; font-size:.72rem; text-transform:uppercase; letter-spacing:.07em; }
.attendance-premium-page main .table tbody td { border-color:rgba(65,43,57,.07); vertical-align:middle; }
.attendance-premium-page main .badge { border-radius:999px; padding:.48rem .7rem; }
.attendance-premium-page main .row.text-center > div { padding:1rem; }
.attendance-premium-page main .border-end { border-color:rgba(65,43,57,.10) !important; }
@media(max-width:768px){
    .attendance-premium-page main > .px-4 > .text-end { margin-top:.4rem; text-align:left !important; margin-bottom:1rem; }
    .attendance-premium-page main > .px-4 { padding-left:.2rem !important; padding-right:.2rem !important; }
    .attendance-premium-page main .card-body { padding:1.15rem !important; }
    .attendance-premium-page main > .px-4 > h1 { font-size:2.2rem; }
}
</style>
@endif

@stack('scripts')
</body>
</html>