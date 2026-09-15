@extends('layouts.guest')

@section('content')
<div data-reveal class="is-visible">
    <div class="d-flex align-items-center gap-3 mb-4">
        <div class="feature-icon"><i class="bi bi-person-lock"></i></div>
        <div>
            <h2 class="h3 fw-bold mb-1">Welcome back</h2>
            <p class="text-secondary mb-0">Sign in with your Employee ID or Gmail account.</p>
        </div>
    </div>

    @include('partials.flash-messages')

    <form method="POST" action="{{ route('login') }}" id="loginForm">
        @csrf

        <div class="mb-3">
            <label for="login" class="form-label">Employee ID or Gmail</label>
            <div class="position-relative">
                <i class="bi bi-person position-absolute top-50 translate-middle-y" style="left:16px;color:var(--pob-muted);z-index:2;"></i>
                <input type="text" id="login" name="login"
                    value="{{ old('login', $rememberedLogin ?? '') }}"
                    required autofocus autocomplete="username"
                    class="form-control ps-5"
                    placeholder="Enter Employee ID or Gmail">
            </div>
        </div>

        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <label for="password" class="form-label">Password</label>
                @if (Route::has('password.request'))
                    <a class="small text-decoration-none fw-bold" href="{{ route('password.request') }}">Forgot password?</a>
                @endif
            </div>
            <div class="position-relative">
                <i class="bi bi-lock position-absolute top-50 translate-middle-y" style="left:16px;color:var(--pob-muted);z-index:2;"></i>
                <input id="password" type="password" name="password" autocomplete="current-password"
                    class="form-control ps-5 pe-5" required placeholder="Enter your password">
                <button id="togglePassword" type="button" class="btn border-0 position-absolute top-50 end-0 translate-middle-y me-1" aria-label="Show password" style="color:var(--pob-muted);box-shadow:none!important;">
                    <i class="bi bi-eye-slash"></i>
                </button>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember" {{ old('remember', !empty($rememberedLogin)) ? 'checked' : '' }}>
                <label for="remember_me" class="form-check-label small">Remember me</label>
            </div>
            <span class="small text-secondary"><i class="bi bi-shield-check me-1"></i>Secure login</span>
        </div>

        <div class="mb-3 text-center recaptcha-container">
            <div id="recaptcha-widget" class="g-recaptcha d-inline-block" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
            <div id="recaptcha-error" class="text-danger mt-2 small" style="display:none;"></div>
        </div>

        <button type="submit" class="btn btn-gradient-primary w-100 py-3 fw-bold" id="loginButton">
            <span class="login-label"><i class="bi bi-arrow-right-circle me-1"></i> Sign in to ESS</span>
        </button>

        <div class="d-flex align-items-center gap-3 my-4">
            <div class="flex-grow-1 border-top" style="border-color:var(--pob-line)!important;"></div>
            <small class="text-secondary">or continue with</small>
            <div class="flex-grow-1 border-top" style="border-color:var(--pob-line)!important;"></div>
        </div>

        <a href="{{ route('google.redirect') }}" class="btn btn-outline-secondary w-100 py-3 fw-bold d-flex align-items-center justify-content-center gap-2">
            <img src="{{ asset('img/google.png') }}" width="20" height="20" alt="Google">
            Sign in with Google
        </a>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    const form = document.getElementById('loginForm');
    const button = document.getElementById('loginButton');
    const remember = document.getElementById('remember_me');
    const login = document.getElementById('login');

    toggle?.addEventListener('click', function () {
        const show = password.type === 'password';
        password.type = show ? 'text' : 'password';
        this.innerHTML = show ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
        this.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
    });

    remember?.addEventListener('change', function () {
        if (!this.checked && '{{ !empty($rememberedLogin) }}' === '1') {
            login.value = '';
            let clear = form.querySelector('input[name="clear_remembered"]');
            if (!clear) {
                clear = document.createElement('input');
                clear.type = 'hidden';
                clear.name = 'clear_remembered';
                clear.value = '1';
                form.appendChild(clear);
            }
        } else if (this.checked) {
            form.querySelector('input[name="clear_remembered"]')?.remove();
        }
    });

    form?.addEventListener('submit', function (event) {
        const mobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        if (typeof grecaptcha !== 'undefined') {
            const response = grecaptcha.getResponse();
            if (!response) {
                event.preventDefault();
                const error = document.getElementById('recaptcha-error');
                error.textContent = 'Please complete the reCAPTCHA verification.';
                error.style.display = 'block';
                return;
            }
        } else if (!mobile) {
            event.preventDefault();
            const error = document.getElementById('recaptcha-error');
            error.textContent = 'reCAPTCHA is not available. Please refresh and try again.';
            error.style.display = 'block';
            return;
        }

        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span> Signing in…';
    });
});
</script>
@endsection
