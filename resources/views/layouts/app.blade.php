    <!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

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

            .navbar-brand {
                font-weight: 600;
                color: inherit !important;
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
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
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
        
        <!-- ✅ Theme Toggle JS -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const themeToggle = document.getElementById('themeToggle');
                const themeIcon = document.getElementById('themeIcon');
                const body = document.body;
                const html = document.documentElement;

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
            });
        </script>

        @stack('scripts')
    </body>
    </html>
