<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

<div class="custom-bg d-flex justify-content-center align-items-center min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-10 col-md-10 col-lg-6">
                <div class="card glass-card shadow-lg border-0">
                    <div class="card-body p-4 p-sm-5">

                        
                        @include('partials.flash-messages')


                        <div class="text-center mb-4">
                            <i class="bi bi-person-circle display-4 text-primary mb-2"></i>
                            <h2 class="h4 fw-bold text-primary mobile-text-lg">Complete Your Profile</h2>
                            <p class="text-muted mobile-text-md">
                                Welcome <strong>{{ $user->name }}</strong>! Please complete your profile to get started.
                            </p>

                        @if($user->employee_id)
                            <div class="alert alert-info mt-3">
                                <i class="bi bi-badge-check"></i> Your Employee ID: <strong>{{ $user->employee_id }}</strong>
                            </div>
                        @endif
                    </div>                        <form method="POST" action="{{ route('employees.complete.store', $user->remember_token) }}" novalidate>
                            @csrf

                            <!-- Password -->
                            <div class="mb-3 position-relative">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <div class="position-relative">
                                <input id="password" name="password" type="password"
                                       class="form-control form-control-lg pe-5 @error('password') is-invalid @enderror"
                                       autocomplete="new-password">
                                    <span class="position-absolute top-50 end-0 translate-middle-y me-3 toggle-password"
                                          style="cursor: pointer;" data-target="password">
                                        <i class="bi bi-eye-slash fs-5"></i>
                                    </span>
                                </div>
                                <div id="passwordHelp" class="form-text mt-1">
                                    Password must include uppercase, lowercase, number, and symbol.
                                </div>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-3 position-relative">
                                <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                                <div class="position-relative">
                                <input id="password_confirmation" name="password_confirmation" type="password"
                                       class="form-control form-control-lg pe-5 @error('password_confirmation') is-invalid @enderror"
                                       autocomplete="new-password">
                                    <span class="position-absolute top-50 end-0 translate-middle-y me-3 toggle-password"
                                          style="cursor: pointer;" data-target="password_confirmation">
                                        <i class="bi bi-eye-slash fs-5"></i>
                                    </span>
                                </div>
                                <div id="confirmPasswordFeedback" class="form-text mt-1"></div>
                                @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div class="mb-3">
                                <label for="phone" class="form-label fw-semibold">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text">+63</span>
                                    <input id="phone" name="phone" type="tel"
                                           class="form-control form-control-lg @error('phone') is-invalid @enderror"
                                           value="{{ old('phone', $user->phone ? substr($user->phone, 3) : '') }}"
                                           placeholder="9XXXXXXXXX"
                                           inputmode="numeric"
                                           autocomplete="tel"
                                           maxlength="10">
                                </div>
                                <div class="form-text">Enter 10-digit number starting with 9. (e.g. 9171234567)</div>
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Gender -->
                            <div class="mb-3">
                                <label for="gender" class="form-label fw-semibold">Gender</label>
                                <select id="gender" name="gender" class="form-select form-select-lg @error('gender') is-invalid @enderror">
                                    <option value="">-- Select --</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                                @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Address -->
                            <div class="mb-4">
                                <label for="address" class="form-label fw-semibold">Address</label>
                                <textarea id="address" name="address" rows="3"
                                          class="form-control form-control-lg @error('address') is-invalid @enderror"
                                          placeholder="Enter your complete address">{{ old('address') }}</textarea>
                                @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill" id="submitBtn">
                                    <i class="bi bi-check-circle"></i> Complete Profile
                                </button>
                            </div>
                        </form>

                        <div class="mt-4 alert alert-info small">
                            <i class="bi bi-info-circle me-2"></i>
                            After completing your profile, you'll be redirected to the login page to sign in.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Bootstrap JS and dependencies (Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

<style>
    body, html {
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', sans-serif;
    }

    .custom-bg {
        background: linear-gradient(to right, #fbd3e9, #fcb69f);
        background-size: cover;
        background-attachment: fixed;
    }

    .glass-card {
        width: 100%;
        max-width: 100%;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        margin: 0 auto;
    }

    .toggle-password {
        z-index: 5;
    }

    /* Mobile-first responsive design */
    @media (max-width: 576px) {
        .glass-card {
            padding: 1rem;
            max-width: 95%;
            margin: 1rem auto;
        }

        .card-body {
            padding: 1.5rem !important;
        }

        input[type="text"],
        input[type="password"],
        input[type="tel"],
        select,
        textarea {
            font-size: 16px !important;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            border-radius: 8px;
        }

        /* Prevent zoom on iOS when input is focused */
        input[type="tel"]:focus,
        input[type="password"]:focus,
        input[type="text"]:focus,
        select:focus,
        textarea:focus {
            font-size: 16px !important;
            outline: none;
        }

        /* Mobile button improvements */
        .btn-lg {
            padding: 1rem 1.5rem;
            font-size: 1.1rem;
            touch-action: manipulation;
            -webkit-tap-highlight-color: transparent;
        }

        /* iOS Safari specific fixes */
        .form-control:focus {
            -webkit-box-shadow: none;
            box-shadow: none;
        }

        /* Better touch targets */
        .toggle-password {
            padding: 0.5rem;
            min-width: 44px;
            min-height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    }

    @media (min-width: 577px) and (max-width: 991.98px) {
        .glass-card {
            max-width: 720px;
            padding: 2rem;
        }

        .card-body {
            padding: 2.5rem !important;
        }
    }

    @media (min-width: 992px) {
        .glass-card {
            max-width: 600px;
            padding: 2.5rem;
        }

        .card-body {
            padding: 3rem !important;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const passwordInput = document.getElementById('password');
    const passwordConfirmationInput = document.getElementById('password_confirmation');
    const strengthMessage = document.getElementById('passwordHelp');
    const confirmPasswordFeedback = document.getElementById('confirmPasswordFeedback');
    const phoneInput = document.getElementById('phone');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.querySelector('form');

    // Simplified form submission for better mobile compatibility
    let isSubmitting = false;
    
    form.addEventListener('submit', function(e) {
        // Prevent double submission
        if (isSubmitting) {
            e.preventDefault();
            return false;
        }
        
        // Simple validation - let server handle detailed validation
        const password = passwordInput.value.trim();
        const confirmPassword = passwordConfirmationInput.value.trim();
        const phone = phoneInput.value.trim();
        const gender = document.getElementById('gender').value;
        const address = document.getElementById('address').value.trim();

        // Basic required field check only
        if (!password) {
            alert('Please enter a password.');
            passwordInput.focus();
            e.preventDefault();
            return false;
        }

        if (!confirmPassword) {
            alert('Please confirm your password.');
            passwordConfirmationInput.focus();
            e.preventDefault();
            return false;
        }

        if (password !== confirmPassword) {
            alert('Passwords do not match.');
            passwordConfirmationInput.focus();
            e.preventDefault();
            return false;
        }

        if (!phone) {
            alert('Please enter your phone number.');
            phoneInput.focus();
            e.preventDefault();
            return false;
        }

        if (!gender) {
            alert('Please select your gender.');
            document.getElementById('gender').focus();
            e.preventDefault();
            return false;
        }

        if (!address) {
            alert('Please enter your address.');
            document.getElementById('address').focus();
            e.preventDefault();
            return false;
        }

        // If we reach here, allow form to submit
        isSubmitting = true;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Processing...';
        
        // Don't prevent default - let form submit normally
        return true;
    });

    // Phone input - simplified for mobile
    phoneInput.addEventListener('input', function(e) {
        // Only allow numbers
        let value = e.target.value.replace(/\D/g, '');
        
        // Limit to 10 digits and auto-add 9 if needed
        if (value.length > 0 && value[0] !== '9') {
            value = '9' + value.substring(1);
        }
        if (value.length > 10) {
            value = value.substring(0, 10);
        }
        
        e.target.value = value;
    });

    // Password strength - simplified
    passwordInput.addEventListener('input', function () {
        const password = passwordInput.value;
        const hasUpper = /[A-Z]/.test(password);
        const hasLower = /[a-z]/.test(password);
        const hasNumber = /\d/.test(password);
        const hasSymbol = /[!@#$%^&*(),.?":{}|<>]/.test(password);

        if (password.length >= 8 && hasUpper && hasLower && hasNumber && hasSymbol) {
            strengthMessage.classList.remove('text-danger');
            strengthMessage.classList.add('text-success');
            strengthMessage.textContent = 'Password is strong.';
        } else {
            strengthMessage.classList.remove('text-success');
            strengthMessage.classList.add('text-danger');
            strengthMessage.textContent = 'Password must be at least 8 characters with uppercase, lowercase, number, and symbol.';
        }
    });

    // Confirm password - simplified
    passwordConfirmationInput.addEventListener('input', function () {
        if (passwordConfirmationInput.value === passwordInput.value && passwordInput.value.length > 0) {
            confirmPasswordFeedback.textContent = 'Passwords match.';
            confirmPasswordFeedback.classList.remove('text-danger');
            confirmPasswordFeedback.classList.add('text-success');
        } else if (passwordConfirmationInput.value.length > 0) {
            confirmPasswordFeedback.textContent = 'Passwords do not match.';
            confirmPasswordFeedback.classList.remove('text-success');
            confirmPasswordFeedback.classList.add('text-danger');
        }
    });

    // Toggle password visibility - simplified
    document.querySelectorAll('.toggle-password').forEach(span => {
        span.addEventListener('click', function (e) {
            e.preventDefault();
            const inputId = this.getAttribute('data-target');
            const input = document.getElementById(inputId);
            const icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        });
    });

    // Safari/iOS specific fixes
    const isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
    const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent);
    
    if (isSafari || isIOS) {
        // Disable autocomplete that might interfere
        form.setAttribute('autocomplete', 'off');
        
        // Add viewport meta for iOS if not exists
        let viewport = document.querySelector('meta[name="viewport"]');
        if (!viewport) {
            viewport = document.createElement('meta');
            viewport.name = 'viewport';
            viewport.content = 'width=device-width, initial-scale=1.0, user-scalable=no';
            document.head.appendChild(viewport);
        }
        
        // Prevent iOS keyboard issues
        document.addEventListener('touchend', function(e) {
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') {
                setTimeout(() => {
                    window.scrollTo(0, 0);
                }, 100);
            }
        });
    }
});
</script>
