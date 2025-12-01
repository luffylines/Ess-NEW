@extends('layouts.guest')

@section('content')
<div class="card shadow-lg rounded-4 bgcolor">
    <div class="card-body p-10 p-md-4">

        <!-- Logo / Title -->
        <div class="mb-2 text-center d-flex align-items-center justify-content-center gap-3">
            <img src="{{ asset('img/logo.png') }}" alt="Company Logo" class="logo-img" style="width: 50px; height: 50px; object-fit: contain;">
            <span class="text h2 fw-bold text mb-0">Place Of Beauty</span>
        </div>

        <h3 class="text fw-semibold mb-1 text-center">Welcome Back</h3>
        <p class="text mb-4 text-center">Use your Employee ID or Gmail to log in</p>

        @include('partials.flash-messages')

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Employee ID or Gmail -->
            <div class="mb-3">
                <label for="login" class="text form-label fw-semibold">Employee ID or Gmail</label>
                <input type="text" id="login" name="login"
                       value="{{ old('login', $rememberedLogin ?? '') }}"
                       required autofocus
                       class="form-control form-control-lg rounded-3 shadow-sm"
                       placeholder="Enter Employee ID or Gmail">
                @error('login')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="text form-label fw-semibold ">Password</label>
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
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember" 
                       {{ old('remember', !empty($rememberedLogin)) ? 'checked' : '' }}>
                <label for="remember_me" class="form-check-label small text">Remember me</label>
            </div>

            <!-- Google reCAPTCHA -->
            <div class="mb-1 text-center recaptcha-container">
                <!-- reCAPTCHA will be rendered here by the explicit API -->
                <div id="recaptcha-widget" class="g-recaptcha d-inline-block" 
                     data-sitekey="6Lfv5fArAAAAAPvO-IYEtHxiwPmU4YRYmYifrw8j"></div>
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
                   class="text btn btn-outline-secondary d-flex align-items-center justify-content-center gap-2 px-4 py-2 w-100 fw-semibold rounded-3 shadow-sm">
                    <img src="{{ asset('img/google.png') }}" width="20" alt="Google logo">
                    Sign in with Google
                </a>
            </div>

            <!-- Forgot Password -->
            @if (Route::has('password.request'))
                <p class="small mb-2 text-center">
                    <a class="text text-decoration-none" href="{{ route('password.request') }}">Forgot password?</a>
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

    // reCAPTCHA callbacks (make them global so the API can access them)
    window.recaptchaCallback = function(response) {
        console.log('reCAPTCHA success:', response);
        document.getElementById('recaptcha-error').style.display = 'none';
    };

    window.recaptchaError = function() {
        console.error('reCAPTCHA error occurred');
        document.getElementById('recaptcha-error').innerHTML = 'reCAPTCHA failed to load. Please refresh the page.';
        document.getElementById('recaptcha-error').style.display = 'block';
    };

    // Mobile device detection and reCAPTCHA handling
    function isMobileDevice() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    }

    // Enhanced form submission for mobile compatibility
    document.addEventListener('DOMContentLoaded', function() {
        const loginForm = document.querySelector('form');
        const submitButton = loginForm.querySelector('button[type="submit"]');
        
        loginForm.addEventListener('submit', function(e) {
            // Check if grecaptcha is loaded and if reCAPTCHA is completed
            if (typeof grecaptcha !== 'undefined') {
                const recaptchaResponse = grecaptcha.getResponse();
                
                if (!recaptchaResponse) {
                    e.preventDefault();
                    document.getElementById('recaptcha-error').innerHTML = 'Please complete the reCAPTCHA verification.';
                    document.getElementById('recaptcha-error').style.display = 'block';
                    return false;
                }
            } else {
                // If reCAPTCHA didn't load, show warning but allow form submission on mobile
                if (isMobileDevice()) {
                    console.warn('reCAPTCHA not loaded on mobile device, allowing submission');
                } else {
                    e.preventDefault();
                    document.getElementById('recaptcha-error').innerHTML = 'reCAPTCHA service is not available. Please refresh the page.';
                    document.getElementById('recaptcha-error').style.display = 'block';
                    return false;
                }
            }
            
            // Disable submit button to prevent double submission
            submitButton.disabled = true;
            submitButton.innerHTML = 'Logging in...';
            
            // Re-enable after 5 seconds in case of error
            setTimeout(() => {
                submitButton.disabled = false;
                submitButton.innerHTML = 'Login';
            }, 5000);
        });
    });
    
    // Handle Remember Me functionality
    document.addEventListener('DOMContentLoaded', function() {
        const rememberCheckbox = document.getElementById('remember_me');
        const loginInput = document.getElementById('login');
        const loginForm = document.querySelector('form');
        
        // Clear login field when unchecking remember me
        rememberCheckbox.addEventListener('change', function() {
            if (!this.checked) {
                // If unchecking and there's a remembered login, clear the field
                const hasRememberedLogin = '{{ !empty($rememberedLogin) }}' === '1';
                if (hasRememberedLogin) {
                    loginInput.value = '';
                    // Add a hidden field to signal that we want to clear the remembered login
                    let clearField = document.querySelector('input[name="clear_remembered"]');
                    if (!clearField) {
                        clearField = document.createElement('input');
                        clearField.type = 'hidden';
                        clearField.name = 'clear_remembered';
                        clearField.value = '1';
                        loginForm.appendChild(clearField);
                    }
                }
            } else {
                // Remove the clear flag if re-checking
                const clearField = document.querySelector('input[name="clear_remembered"]');
                if (clearField) {
                    clearField.remove();
                }
            }
        });
    });

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

    .bgcolor {
        background: linear-gradient(90deg, #ff79bc 0%, #dd1f7e 100%);
    }

    .text, .text-muted {
        color: #0f090c;
    }
    
    /* Logo styling */
    .logo-img {
        transition: transform 0.3s ease;
    }
    
    .logo-img:hover {
        transform: scale(1.05);
    }
    
    /* Responsive logo and title */
    @media screen and (max-width: 576px) {
        .logo-img {
            width: 40px !important;
            height: 40px !important;
        }
        .h2 {
            font-size: 1.5rem !important;
        }
    }
    
    @media screen and (max-width: 400px) {
    .g-recaptcha {
      transform: scale(0.77);
      transform-origin: 0 0;
      margin: 0 auto;
    }
    
    .recaptcha-container {
      overflow: hidden;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    
    /* Ensure form doesn't break on very small screens */
    .card-body {
      padding: 1.5rem !important;
    }
  }

  /* Additional mobile optimizations */
  @media screen and (max-width: 576px) {
    .g-recaptcha {
      transform: scale(0.85);
      transform-origin: center center;
    }
    
    .recaptcha-container {
      margin: 0.5rem 0;
    }
    
    /* Prevent horizontal scroll on mobile */
    body {
      overflow-x: hidden;
    }
    
    .card {
      margin: 1rem;
      max-width: calc(100vw - 2rem);
    }
  }
    .btn-gradient-primary {
        background: linear-gradient(90deg, #0f090c 0%, #0f090c 100%);
        border: none;
        color: white;
    }

    .btn-gradient-primary:hover {
        background: linear-gradient(90deg, #9e165a 0%, #c90165 100%);
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