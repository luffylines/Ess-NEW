    <!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Employee Self-Service') }} - Place Of Beauty</title>
        
        <!-- Favicon -->
        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('img/logo.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('img/logo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Bootstrap Icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

        <!-- FontAwesome (for Sidebar icons) -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --bs-dark-bg: #1a1a1a;
                --bs-light-bg: #ffffff;
                --sidebar-width: 250px;
            }

            /* Body Theme */
            body.light {
                background-color: var(--bs-light-bg);
                color: #212529;
            }

            body.dark {
                background-color: var(--bs-dark-bg);
                color: #ffffff;
            }

            /* Navbar Styling */
            nav.navbar {
                transition: background-color 0.3s ease, color 0.3s ease;
                z-index: 1050;
            }

            body.light .navbar {
                background-color: #f8f9fa !important;
                color: #212529 !important;
            }

            body.dark .navbar {
                background-color: #2d2d2d !important;
                color: #ffffff !important;
            }

            /* Navbar toggler styles for dark/light mode */
            .navbar-toggler {
                border: 1px solid rgba(0, 0, 0, 0.1);
                padding: 0.375rem 0.5rem;
                border-radius: 0.375rem;
            }

            body.light .navbar-toggler {
                border-color: rgba(0, 0, 0, 0.2);
                color: #212529;
            }

            body.dark .navbar-toggler {
                border-color: rgba(255, 255, 255, 0.4);
                color: #ffffff !important;
            }

            body.dark .navbar-toggler i {
                color: #ffffff !important;
            }

            .navbar-toggler:focus {
                box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            }

            .navbar-toggler:hover {
                background-color: rgba(0, 0, 0, 0.05);
            }

            body.dark .navbar-toggler:hover {
                background-color: rgba(255, 255, 255, 0.1);
            }

            body.light .navbar-toggler {
                border-color: rgba(0, 0, 0, 0.1);
                color: #212529;
            }

            body.dark .navbar-toggler {
                border-color: rgba(255, 255, 255, 0.3);
                color: #ffffff;
            }

            .navbar-toggler:focus {
                box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            }

            .navbar-brand {
                font-weight: 600;
                color: inherit !important;
            }

            .navbar-logo {
                transition: transform 0.3s ease;
            }

            .navbar-brand:hover .navbar-logo {
                transform: scale(1.1);
            }

            /* Responsive navbar logo */
            @media (max-width: 768px) {
                .navbar-logo {
                    width: 28px !important;
                    height: 28px !important;
                }
            }

            /* Sidebar alignment fix */
            main {
                margin-left: var(--sidebar-width);
                padding-top: 80px;
                transition: margin-left 0.3s ease;
            }

            /* When sidebar collapsed */
            .main-sidebar.collapsed + main {
                margin-left: 60px;
            }

            /* Theme Toggle Button */
            #themeToggle {
                border-radius: 50%;
                width: 38px;
                height: 38px;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: transform 0.2s;
            }

            #themeToggle:hover {
                transform: scale(1.1);
            }

            /* Smooth transition for theme change */
            body, .navbar, .card, .form-control, .main-sidebar {
                transition: all 0.3s ease;
            }

            /* Sidebar theme sync */
            body.light .main-sidebar {
                background-color: #f8f9fa;
                color: #212529;
            }

            body.dark .main-sidebar {
                background-color: #343a40;
                color: #ffffff;
            }

            /* Scroll fix for sidebar */
            .main-sidebar {
                overflow-y: auto;
            }

            /* Mobile Hamburger Menu */
            .mobile-menu-toggle {
                display: none;
                background: none;
                border: none;
                font-size: 1.5rem;
                color: inherit;
                cursor: pointer;
                z-index: 1051;
            }

            /* Mobile overlay for sidebar */
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 1035;
                display: none;
            }

            .sidebar-overlay.show {
                display: block;
            }

            /* Responsive adjustments */
            @media (max-width: 992px) {
                main {
                    margin-left: 0;
                    padding-left: 15px;
                    padding-right: 15px;
                }

                .mobile-menu-toggle {
                    display: block;
                }

                /* Hide desktop navbar items on mobile */
                .navbar .container-fluid {
                    position: relative;
                }
            }

            @media (max-width: 576px) {
                main {
                    padding-left: 10px;
                    padding-right: 10px;
                }

                .navbar-brand {
                    font-size: 1rem;
                }

                .container-fluid {
                    padding-left: 10px !important;
                    padding-right: 10px !important;
                }

                .card {
                    margin-bottom: 1rem;
                }

                /* Mobile-friendly cards */
                .row > [class*="col-"] {
                    margin-bottom: 15px;
                }

                /* Mobile table responsiveness */
                .table-responsive {
                    font-size: 0.875rem;
                }

                /* Mobile buttons */
                .btn-group .btn {
                    font-size: 0.75rem;
                    padding: 0.25rem 0.5rem;
                }

                /* Mobile form elements */
                .form-control {
                    font-size: 16px; /* Prevents zoom on iOS */
                }
            }

            /* Placeholder fix for dark mode */
            body.dark textarea::placeholder {
                color: #eee !important;
            }

            body.dark textarea.form-control {
                color: #fff;
            }

        </style>

        <!-- Initialize Theme Early -->
        <script>
            (function () {
                const theme = localStorage.getItem('theme') || 'light';
                document.documentElement.classList.add(theme);
                document.addEventListener('DOMContentLoaded', () => {
                    document.body.classList.add(theme);
                });
            })();
        </script>
    </head>

    <body>
        <div id="app">

            <!-- ✅ Navbar -->
            <nav class="navbar navbar-expand-lg fixed-top shadow-sm">
                <div class="container-fluid px-4">
                    <!-- Mobile Menu Toggle Button -->
                    <button class="mobile-menu-toggle me-2" id="mobileMenuToggle" type="button">
                        <i class="fa-brands fa-buffer"></i>
                    </button>

                    <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                        <img src="{{ asset('img/logo.png') }}" alt="Company Logo" class="navbar-logo" style="width: 32px; height: 32px; object-fit: contain;">
                        <span class="fw-bold">Place Of Beauty</span>
                    </a>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-bars fa-lg"></i>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarNav">
                        <!-- Right -->
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
                                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                        {{ Auth::user()->name }}
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
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

            <!-- ✅ Sidebar Overlay for Mobile -->
            <div class="sidebar-overlay" id="sidebarOverlay"></div>

            <!-- ✅ Sidebar -->
            @includeIf('layouts.sidebar')

            <!-- ✅ Page Content -->
            <main>
                @yield('content')
                {{ $slot ?? '' }}
            </main>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <script src="//unpkg.com/alpinejs" defer></script>
        
        <!-- ✅ Theme Toggle & Mobile Menu JS -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const themeToggle = document.getElementById('themeToggle');
                const themeIcon = document.getElementById('themeIcon');
                const body = document.body;
                const html = document.documentElement;

                // Mobile menu elements
                const mobileMenuToggle = document.getElementById('mobileMenuToggle');
                const sidebar = document.getElementById('sidebar');
                const sidebarOverlay = document.getElementById('sidebarOverlay');

                function setTheme(theme) {
                    body.classList.remove('light', 'dark');
                    html.classList.remove('light', 'dark');
                    body.classList.add(theme);
                    html.classList.add(theme);
                    localStorage.setItem('theme', theme);
                    themeIcon.className = theme === 'dark' ? 'bi bi-sun' : 'bi bi-moon';
                }

                // Initialize saved theme
                const savedTheme = localStorage.getItem('theme') || 'light';
                setTheme(savedTheme);

                themeToggle.addEventListener('click', () => {
                    const newTheme = body.classList.contains('dark') ? 'light' : 'dark';
                    setTheme(newTheme);
                });

                // Mobile menu functionality
                function toggleMobileSidebar() {
                    sidebar.classList.toggle('mobile-active');
                    sidebarOverlay.classList.toggle('show');
                    document.body.style.overflow = sidebar.classList.contains('mobile-active') ? 'hidden' : '';
                }

                function closeMobileSidebar() {
                    sidebar.classList.remove('mobile-active');
                    sidebarOverlay.classList.remove('show');
                    document.body.style.overflow = '';
                }

                // Mobile menu toggle
                if (mobileMenuToggle) {
                    mobileMenuToggle.addEventListener('click', toggleMobileSidebar);
                }

                // Close sidebar when clicking overlay
                if (sidebarOverlay) {
                    sidebarOverlay.addEventListener('click', closeMobileSidebar);
                }

                // Close sidebar on window resize to desktop size
                window.addEventListener('resize', () => {
                    if (window.innerWidth > 992) {
                        closeMobileSidebar();
                    }
                });

                // Close sidebar when clicking on nav links (mobile)
                if (sidebar) {
                    sidebar.addEventListener('click', (e) => {
                        if (window.innerWidth <= 992 && e.target.classList.contains('nav-link') && !e.target.classList.contains('dropdown-toggle')) {
                            closeMobileSidebar();
                        }
                    });
                }
            });
        </script>

        @stack('scripts')
    </body>
    </html>
