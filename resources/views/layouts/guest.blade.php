<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#211b20">
    <title>{{ config('app.name', 'Employee Self-Service') }} - Place Of Beauty</title>

    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <script src="https://www.google.com/recaptcha/api.js?onload=onRecaptchaLoad&render=explicit" async defer></script>
    <script>
        window.onRecaptchaLoad = function () {
            document.querySelectorAll('.g-recaptcha').forEach(function (element) {
                if (element.dataset.rendered === '1' || typeof grecaptcha === 'undefined') return;
                var compact = window.matchMedia('(max-width: 480px)').matches;
                grecaptcha.render(element, {
                    sitekey: element.getAttribute('data-sitekey'),
                    callback: window.recaptchaCallback,
                    'error-callback': window.recaptchaError,
                    size: compact ? 'compact' : 'normal',
                    theme: document.body.classList.contains('dark') ? 'dark' : 'light'
                });
                element.dataset.rendered = '1';
            });
        };
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
</head>
<body class="guest-auth-body">
    <div class="guest-auth-shell">
        <div class="guest-auth-row">
            <section class="guest-auth-hero">
                <div class="auth-hero-brand">
                    <img src="{{ asset('img/logo.png') }}" alt="Place Of Beauty logo" class="auth-hero-logo">
                    <div><div class="fw-bold fs-5">Place Of Beauty</div><div class="small" style="color:rgba(255,255,255,.58);">Employee Self-Service</div></div>
                </div>
                <h1 class="auth-hero-title">One secure place for your workday.</h1>
                <p class="auth-hero-copy">Clock in, review schedules, request leave, manage overtime and access payslips from an employee experience designed to stay simple.</p>
                <div class="auth-hero-pills">
                    <span class="auth-hero-pill"><i class="bi bi-shield-check me-1"></i> Secure access</span>
                    <span class="auth-hero-pill"><i class="bi bi-phone me-1"></i> Mobile ready</span>
                    <span class="auth-hero-pill"><i class="bi bi-lightning-charge me-1"></i> Fast workflow</span>
                </div>
            </section>

            <section class="guest-auth-content">
                <div class="guest-auth-content-inner">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <a href="{{ url('/') }}" class="auth-back"><i class="bi bi-arrow-left"></i> Back to home</a>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle d-grid" data-theme-toggle aria-label="Toggle theme" style="width:40px;height:40px;place-items:center;"><i class="bi bi-moon-stars"></i></button>
                    </div>
                    <div class="auth-card">
                        @yield('content')
                    </div>
                    <p class="text-center text-secondary small mt-3 mb-0">Authorized Place Of Beauty personnel only.</p>
                </div>
            </section>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
