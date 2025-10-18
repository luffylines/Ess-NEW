@extends('layouts.guest')

@section('content')
<div class="card shadow-lg rounded-4 bg-white">
    <div class="card-body p-10 p-md-4">

        <!-- Logo / Title -->
        <div class="mb-2 text-center">
            <span class="h2 fw-bold text-primary">Salon</span>
        </div>

        <h3 class="fw-semibold mb-1 text-secondary text-center">Welcome Back</h3>
        <p class="text-muted mb-4 text-center">Use your Employee ID or Gmail to log in</p>

        <!-- Session Status -->
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Role Selection -->
            <div class="d-flex justify-content-center mb-4 gap-3">
                <div id="employee" class="role-box p-2 rounded-3 border border-secondary text-center fw-semibold text-secondary"
                     onclick="selectRole('employee')" role="button" tabindex="0">
                    Employee
                </div>
                <div id="manager" class="role-box p-2 rounded-3 border border-secondary text-center fw-semibold text-secondary"
                     onclick="selectRole('manager')" role="button" tabindex="0">
                    Manager
                </div>
            </div>
            <input type="hidden" id="role" name="role" value="">

            <!-- Employee ID or Gmail -->
            <div class="mb-3">
                <label for="login" class="form-label fw-semibold text-secondary">Employee ID or Gmail</label>
                <input type="text" id="login" name="login"
                       value="{{ old('login') }}"
                       required autofocus
                       class="form-control form-control-lg rounded-3 shadow-sm"
                       placeholder="Enter Employee ID or Gmail">
                @error('login')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label fw-semibold text-secondary">Password</label>
                <div class="position-relative">
                    <input id="password" type="password" name="password"
                           class="form-control form-control-lg rounded-3 pe-5 shadow-sm"
                           required
                           placeholder="Enter your password">
                    <span id="togglePassword" class="position-absolute top-50 end-0 translate-middle-y me-3 password-toggle" style="cursor: pointer;">
                        <img src="{{ asset('img/eyeoff.png') }}" width="22" alt="Toggle Password">
                    </span>
                </div>
                @error('password')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="form-check mb-3">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                <label for="remember_me" class="form-check-label small text-muted">Remember me</label>
            </div>

            <!-- Google reCAPTCHA -->
            <div class="mb-1 text-center">
                <!-- Using Google's test site key that works on any domain -->
                <div class="g-recaptcha d-inline-block" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI" data-callback="recaptchaCallback" data-error-callback="recaptchaError"></div>
                @error('g-recaptcha-response')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
                <div id="recaptcha-error" class="text-danger mt-2" style="display: none;"></div>
                
            </div>

            <!-- Login Button -->
            <div class="mb-3">
                <button type="submit" class="btn btn-gradient-primary btn-lg w-100 fw-semibold rounded-3 shadow-sm">
                    Login
                </button>
            </div>

            <!-- Google Sign-In -->
            <div class="d-flex justify-content-center mb-3">
                <a href="{{ route('google.redirect') }}"
                   class="btn btn-outline-secondary d-flex align-items-center justify-content-center gap-2 px-4 py-2 w-100 fw-semibold rounded-3 shadow-sm">
                    <img src="{{ asset('img/google.png') }}" width="20" alt="Google logo">
                    Sign in with Google
                </a>
            </div>

            <!-- Forgot Password -->
            @if (Route::has('password.request'))
                <p class="small mb-2 text-center">
                    <a class="text-muted text-decoration-none" href="{{ route('password.request') }}">Forgot password?</a>
                </p>
            @endif
        </form>
    </div>
</div>

<script>
    // Password toggle
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    togglePassword.addEventListener('click', function () {
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;
        this.innerHTML = type === 'password'
            ? `<img src="{{ asset('img/eyeoff.png') }}" width="22" alt="Hide">`
            : `<img src="{{ asset('img/eyeon.png') }}" width="22" alt="Show">`;
    });

    // Role selection
    function selectRole(role) {
        document.querySelectorAll('.role-box').forEach(box => {
            box.classList.remove('border-primary', 'bg-primary', 'text-white', 'shadow');
            box.classList.add('border-secondary', 'bg-white', 'text-secondary');
        });

        const selected = document.getElementById(role);
        selected.classList.remove('border-secondary', 'bg-white', 'text-secondary');
        selected.classList.add('border-primary', 'bg-primary', 'text-white', 'shadow');
        document.getElementById('role').value = role;
    }

    // reCAPTCHA callbacks
    function recaptchaCallback(response) {
        console.log('reCAPTCHA success:', response);
        document.getElementById('recaptcha-error').style.display = 'none';
    }

    function recaptchaError() {
        console.error('reCAPTCHA error occurred');
        document.getElementById('recaptcha-error').innerHTML = 'reCAPTCHA failed to load. Please refresh the page.';
        document.getElementById('recaptcha-error').style.display = 'block';
    }

    // Auto-hide error messages after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert-danger');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                if (alert.classList.contains('alert-dismissible')) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                } else {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.style.display = 'none', 500);
                }
            }, 5000);
        });
    });
</script>

<style>
    @media screen and (max-width: 400px) {
    .g-recaptcha {
      transform: scale(0.75);
      transform-origin: 0 0;
    }
  }
    .role-box {
        cursor: pointer;
        transition: all 0.25s ease;
        user-select: none;
    }

    .role-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .btn-gradient-primary {
        background: linear-gradient(90deg, #ff00d4 0%, #ff33ee 100%);
        border: none;
        color: white;
    }

    .btn-gradient-primary:hover {
        background: linear-gradient(90deg, #cc0077 0%, #ff26ed 100%);
    }

    .password-toggle {
        cursor: pointer;
        z-index: 10;
        transition: opacity 0.2s ease;
        top: 50% !important;
        right: 12px !important;
        transform: translateY(-50%) !important;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 4px;
        border-radius: 4px;
    }

    .password-toggle:hover {
        opacity: 0.7;
        background-color: rgba(0, 0, 0, 0.05);
    }

    /* Ensure password toggle is properly positioned inside the input */
    .password-toggle img {
        width: 22px;
        height: 22px;
        object-fit: contain;
        display: block;
    }

    /* Ensure input has enough padding for the toggle button */
    .form-control.pe-5 {
        padding-right: 3rem !important;
    }

    /* Auto-hide alert styling */
    .alert-danger {
        transition: opacity 0.5s ease;
    }
</style>
@endsection