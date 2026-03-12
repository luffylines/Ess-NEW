<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google reCAPTCHA with mobile optimization -->
    <script src="https://www.google.com/recaptcha/api.js?onload=onRecaptchaLoad&render=explicit" async defer></script>
    
    <script>
        var onRecaptchaLoad = function() {
            // Mobile-friendly reCAPTCHA initialization
            var recaptchaElements = document.getElementsByClassName('g-recaptcha');
            for (var i = 0; i < recaptchaElements.length; i++) {
                var element = recaptchaElements[i];
                var sitekey = element.getAttribute('data-sitekey');
                
                // Check if we're on mobile and adjust size
                var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
                var size = isMobile ? 'compact' : 'normal';
                
                grecaptcha.render(element, {
                    'sitekey': sitekey,
                    'callback': window.recaptchaCallback,
                    'error-callback': window.recaptchaError,
                    'size': size,
                    'theme': 'light'
                });
            }
        };
    </script>
    

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Custom responsive styles */
        @media (max-width: 576px) {
            .mobile-padding { padding: 1rem !important; }   
            .mobile-text-sm { font-size: 0.875rem !important; }
            .mobile-h-auto { height: auto !important; }
        }
        
        @media (max-width: 768px) {
            .tablet-padding { padding: 1.5rem !important; }
            .image-mobile { height: 200px !important; }
        }
        
        @media (min-width: 992px) {
            .desktop-max-width { max-width: 480px !important; }
        }
    </style>
</head>
<body class="bg-light guest-auth-body">

    <div class="guest-auth-shell">
        <div class="guest-auth-row">
            <!-- Welcome Banner: always visible, styled for mobile/desktop -->
            <div class="guest-auth-hero text-center d-flex flex-column justify-content-center align-items-center">
                <h1 class="display-4 fw-bold mb-2">Welcome</h1>
                <p class="lead mb-0">Place Of Beauty - Employee Self-Service System</p>
            </div>
            <div class="guest-auth-content d-flex flex-column justify-content-center align-items-center">
                <div class="guest-auth-content-inner w-100">
                    <div class="auth-card shadow rounded-4 bg-white p-4 mx-auto">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .guest-auth-body {
            min-height: 100vh;
        }
        .guest-auth-shell {
            min-height: 100vh;
        }
        .guest-auth-row {
            min-height: 100vh;
            display: flex;
            background: #f3f4f6;
        }
        .guest-auth-hero {
            flex: 0 0 50%;
            min-height: 100vh;
            background: linear-gradient(135deg, #ea6692 0%, #e61ba2 100%);
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 2rem 1rem;
        }
        .guest-auth-content {
            flex: 0 0 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }
        .guest-auth-content-inner {
            width: 100%;
            max-width: 420px;
            padding: 0;
        }
        .auth-card {
            background: #fff;
            border-radius: 1.5rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            padding: 2rem 1.5rem;
        }
        @media (max-width: 991.98px) {
            .guest-auth-row {
                flex-direction: column;
                min-height: 100vh;
            }
            .guest-auth-hero {
                min-height: unset;
                padding: 2rem 1rem 1.5rem 1rem;
                border-radius: 0 0 2rem 2rem;
                flex: unset;
            }
            .guest-auth-content {
                min-height: unset;
                width: 100%;
                padding: 1.5rem 0.5rem 2rem 0.5rem;
                flex: unset;
            }
            .guest-auth-content-inner {
                max-width: 100%;
            }
            .auth-card {
                padding: 1.5rem 0.5rem;
                border-radius: 1.25rem;
            }
        }
        @media (max-width: 576px) {
            .auth-card {
                padding: 1rem 0.25rem;
                border-radius: 1rem;
            }
            .guest-auth-hero {
                padding: 1.5rem 0.5rem 1rem 0.5rem;
            }
        }
    </style>
</body>
</html>
