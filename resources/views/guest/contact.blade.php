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

        /* Custom Styles for Contact Us Page */
        .contact-section {
            margin-top: 75px;
        }

        .contact-title {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--bs-primary);
        }

        .contact-description {
            font-size: 1.25rem;
            line-height: 1;
            margin-top: 0px;
        }

        /* Layout for Feedback Form and Location */
        .contact-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            
        }

        .contact-form {
            flex: 1;
            min-width: 300px;
        }

        .location-info {
            flex: 1;
            min-width: 300px;
            padding-left: 30px;
            border-left: 2px solid #ccc;
        }

        .contact-chat-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #0d6efd;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            text-decoration: none;
        }

        .contact-chat-btn:hover {
            background-color: #0b5ed7;
        }

        .form-control {
            margin-bottom: 15px;
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

        <div class="container contact-section">
    <h1 class="contact-title text-center">Contact & Feedback</h1>
    <p class="contact-description text-center mb-5">
        We'd love to hear what you think about the Employee Self-Service System.
    </p>

    @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <!-- Feedback Form (Left Side) -->
        <div class="col-md-6">
            <div class="card shadow-sm p-4">
                <h5 class="mb-4">Send Us Your Thoughts</h5>
                <form action="{{ route('submitFeedback') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="name">Your Name</label>
                        <input type="text" class="form-control rounded-3" id="name" name="name" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="email">Your Email</label>
                        <input type="email" class="form-control rounded-3" id="email" name="email" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="feedback">Your Feedback</label>
                        <textarea class="form-control rounded-3" id="feedback" name="feedback" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-2">Submit Feedback</button>
                </form>
            </div>
        </div>

        <!-- Location Map (Right Side) -->
        <div class="col-md-6 mt-4 mt-md-0">
            <div class="card shadow-sm p-4">
                <h5 class="mb-3">Developer Location</h5>
                <p><strong>Christian Aring</strong><br>Manila, Philippines</p>
                <iframe
                    width="100%"
                    height="300"
                    frameborder="0"
                    style="border:0; border-radius: 10px;"
                    src="https://www.google.com/maps/embed/v1/place?q=Vasra,+Quezon+City,+Metro+Manila&key=AIzaSyDru-BMggS0xquefSQdAnBjSQ0KMH5Vzwk"
                    allowfullscreen>
                </iframe>

                <a href="https://mail.google.com/mail/?view=cm&fs=1&to=chba.aring.sjc@phinmaed.com&su=Feedback%20on%20ESS%20System" 
                   target="_blank" 
                   class="btn btn-outline-primary mt-3 w-100">
                    <i class="fas fa-envelope"></i> Chat via Gmail
                </a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
