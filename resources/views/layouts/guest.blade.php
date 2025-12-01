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
<body class="bg-light">

    <div class="container-fluid min-vh-100">
        <div class="row min-vh-100 g-0">

            <!-- Left Side: Image - Hidden on mobile, visible on tablet+ -->
            <div class="col-lg-6 col-xl-7 d-none d-md-block position-relative">
                <div class="position-absolute top-0 start-0 w-100 h-100" 
                     style="background: linear-gradient(135deg, #ea6692 0%, #e61ba2 100%);">
                    <div class="d-flex align-items-center justify-content-center h-100 text-white text-center p-4">
                        <div>
                            <h1 class="display-4 fw-bold mb-3">Welcome</h1>
                            <p class="lead">Your Employee Management System</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Content -->
            <!-- Right Side: Centered Content -->
            <div class="col-12 col-md-12 col-lg-6 col-xl-5 d-flex align-items-center justify-content-center">
                <div class="w-100 d-flex align-items-center justify-content-center p-3 p-sm-4 p-md-5">
                    <div class="desktop-max-width w-100">
                        <!-- Mobile Logo/Brand - Only visible on mobile -->

                        @yield('content')
                    </div>
                </div>

        </div>
    </div>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
