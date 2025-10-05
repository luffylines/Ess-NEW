<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- General Error Message -->
    @if ($errors->any())
        <div class="mb-4 text-red-600 bg-red-100 dark:bg-red-900 dark:text-red-200 border border-red-300 dark:border-red-700 p-3 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Role Selection -->
        <div class="flex mb-6 gap-1">
            <div
                class="role-box flex-1 h-12 flex p-4 items-center justify-center rounded-lg border border-gray-300 dark:border-gray-600 cursor-pointer transition duration-300 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-100"
                id="admin"
                onclick="selectRole('admin')"
            >
                Admin
            </div>
            <div
                class="role-box flex-1 h-12 flex p-4 items-center justify-center rounded-lg border border-gray-300 dark:border-gray-600 cursor-pointer transition duration-300 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-100"
                id="employee"
                onclick="selectRole('employee')"
            >
                Employee
            </div>
            <div
                class="role-box flex-1 h-12 flex p-4 items-center justify-center rounded-lg border border-gray-300 dark:border-gray-600 cursor-pointer transition duration-300 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-100"
                id="hr"
                onclick="selectRole('hr')"
            >
                HR
            </div>
        </div>

        <!-- Hidden input to hold the selected role -->
        <input type="hidden" id="role" name="role" value="">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4 relative">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />

            <!-- Show Password Toggle -->
            <button type="button" id="togglePassword" class="absolute top-0 right-0 flex items-center">
               <img src="{{ asset('img/eyeoff.png') }}" class="w-5 h-5 opacity-70" alt="Toggle Password"> 
            </button>
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                       class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm
                              focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                       name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <!-- Login Button Centered -->
        <div class="flex justify-center mt-10">
            <x-primary-button>
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <!-- Forgot Password Link -->
        @if (Route::has('password.request'))
            <div class="mt-4 text-center">
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100
                          focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                   href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            </div>
        @endif
    </form>

    <!-- Divider -->
    <div class="my-6 border-t border-gray-300 dark:border-gray-700"></div>

    <!-- Google Sign-In Centered -->
    <div class="flex justify-center mt-4">
        <a href="{{ route('google.redirect') }}"
           class="flex items-center justify-center gap-2 px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-md shadow
                  hover:bg-gray-100 transition dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700">
            <img src="{{ asset('img/google.png') }}" class="w-5 h-5" alt="Google logo">
            <span>Sign in with Google</span>
        </a>
    </div>

    <!-- Show Password & Role Selection Script -->
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;

            if (passwordInput.type === 'password') {
                togglePassword.innerHTML = `<img src="{{ asset('img/eyeoff.png') }}" class="w-5 h-5 opacity-70" alt="Toggle Password">`;
            } else {
                togglePassword.innerHTML = `<img src="{{ asset('img/eyeon.png') }}" class="w-5 h-5 opacity-70" alt="Toggle Password">`;
            }
        });

        function selectRole(role) {
            const roles = document.querySelectorAll('.role-box');
            roles.forEach(box => {
                box.classList.remove('bg-blue-200', 'dark:bg-blue-800', 'border-blue-400', 'dark:border-blue-500', 'text-blue-900', 'dark:text-blue-100');
                box.classList.add('border-gray-300', 'dark:border-gray-600', 'text-gray-900', 'dark:text-gray-100');
            });

            const selectedBox = document.getElementById(role);
            selectedBox.classList.add('bg-blue-200', 'dark:bg-blue-800', 'border-blue-400', 'dark:border-blue-500', 'text-blue-900', 'dark:text-blue-100');
            selectedBox.classList.remove('border-gray-300', 'dark:border-gray-600', 'text-gray-900', 'dark:text-gray-100');

            document.getElementById('role').value = role;
        }

            // Remove error message after 5 seconds
        window.addEventListener('DOMContentLoaded', () => {
        const errorDiv = document.querySelector('div.mb-4.text-red-600');
        if (errorDiv) {
            setTimeout(() => {
                errorDiv.remove();
            }, 5000); // 5000 milliseconds = 5 seconds
        }
        });
    </script>
</x-guest-layout>
