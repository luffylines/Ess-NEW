<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-3" :status="session('status')" />

    <!-- General Error Message -->
    @if ($errors->any())
        <div class="alert alert-danger rounded-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Login Container -->
    <div class="d-flex justify-content-center align-items-center min-vh-100 bg-light">
        <div class="card shadow-lg border-0 rounded-4" style="max-width: 420px; width: 100%;">
            <div class="card-body p-5">

                <h3 class="text-center mb-3 fw-bold text-primary">Welcome Back!</h3>
                <p class="text-center text-muted mb-4">Please log in to continue</p>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Role Selection -->
                    <div class="d-flex justify-content-center mb-4 gap-3">
                        <div id="employee" class="role-box p-3 px-4 rounded-3 border text-center fw-semibold"
                             onclick="selectRole('employee')">
                            Employee
                        </div>
                        <div id="hr" class="role-box p-3 px-4 rounded-3 border text-center fw-semibold"
                             onclick="selectRole('hr')">
                            HR
                        </div>
                    </div>

                    <!-- Hidden input to hold selected role -->
                    <input type="hidden" id="role" name="role" value="">

                    <!-- Email Address -->
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="form-control rounded-3" autocomplete="username">
                        <x-input-error :messages="$errors->get('email')" class="text-danger mt-1" />
                    </div>

                    <!-- Password -->
                    <div class="mb-3 position-relative">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                               class="form-control rounded-3 pe-5">
                        <x-input-error :messages="$errors->get('password')" class="text-danger mt-1" />

                        <!-- Show Password Toggle -->
                        <button type="button" id="togglePassword"
                                class="btn btn-sm position-absolute top-50 end-0 translate-middle-y me-3 border-0 bg-transparent">
                            <img src="{{ asset('img/eyeoff.png') }}" width="22" alt="Toggle Password">
                        </button>
                    </div>

                    <!-- Remember Me -->
                    <div class="form-check mb-3">
                        <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                        <label for="remember_me" class="form-check-label small text-muted">
                            Remember me
                        </label>
                    </div>

                    <!-- Google reCAPTCHA -->
                    <div class="mb-3 text-center">
                        {{-- Replace YOUR_RECAPTCHA_SITE_KEY with your actual key --}}
                        <div class="g-recaptcha d-inline-block" data-sitekey="6Lf1DOMrAAAAAPrvWSUuGlntxpG0EC1QdZcQIMuc"></div>
                        <x-input-error :messages="$errors->get('g-recaptcha-response')" class="text-danger mt-2" />
                    </div>

                    <!-- Login Button -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary py-2 fw-semibold rounded-3">
                            Log in
                        </button>
                    </div>

                    <!-- Forgot Password -->
                    @if (Route::has('password.request'))
                        <div class="mt-3 text-center">
                            <a class="small text-decoration-none text-muted" href="{{ route('password.request') }}">
                                Forgot your password?
                            </a>
                        </div>
                    @endif
                </form>

                <!-- Divider -->
                <div class="border-top my-4"></div>

                <!-- Google Sign-In -->
                <div class="d-flex justify-content-center">
                    <a href="{{ route('google.redirect') }}"
                       class="btn btn-outline-secondary d-flex align-items-center justify-content-center gap-2 px-4 py-2 w-100 fw-semibold rounded-3">
                        <img src="{{ asset('img/google.png') }}" width="20" alt="Google logo">
                        Sign in with Google
                    </a>
                </div>

            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        // Password toggle
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            this.innerHTML = passwordInput.type === 'password'
                ? `<img src="{{ asset('img/eyeoff.png') }}" width="22" alt="Hide">`
                : `<img src="{{ asset('img/eyeon.png') }}" width="22" alt="Show">`;
        });

        // Role selection
        function selectRole(role) {
            document.querySelectorAll('.role-box').forEach(box => {
                box.classList.remove('border-primary', 'bg-primary', 'text-white', 'shadow-sm');
                box.classList.add('border', 'bg-white', 'text-dark');
            });

            const selected = document.getElementById(role);
            selected.classList.add('border-primary', 'bg-primary', 'text-white', 'shadow-sm');
            document.getElementById('role').value = role;
        }

        // Auto-remove error after 5 seconds
        window.addEventListener('DOMContentLoaded', () => {
            const errorDiv = document.querySelector('.alert-danger');
            if (errorDiv) setTimeout(() => errorDiv.remove(), 5000);
        });
    </script>

    <style>
        body {
            background: linear-gradient(135deg, #f0f5ff, #ffffff);
            font-family: 'Poppins', sans-serif;
        }

        .role-box {
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .role-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .role-box.active {
            background-color: #0d6efd !important;
            color: #fff !important;
            border-color: #0d6efd !important;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
    </style>
</x-guest-layout>
