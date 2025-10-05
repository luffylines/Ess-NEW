<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Complete Your Profile</h2>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            Welcome {{ $user->name }}! Please complete your profile setup to get started.
        </p>
    </div>

    <!-- Display Success/Error Messages -->
    @if (session('success'))
        <div class="mb-4 p-4 text-green-700 bg-green-100 border border-green-300 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 p-4 text-red-700 bg-red-100 border border-red-300 rounded">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.employees.complete.store', $user->remember_token) }}">
        @csrf

        <!-- Password -->
        <div class="mb-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autofocus autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Phone -->
        <div class="mb-4">
            <x-input-label for="phone" :value="__('Phone Number')" />
            <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone')" autocomplete="tel" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Gender -->
        <div class="mb-4">
            <x-input-label for="gender" :value="__('Gender')" />
            <select id="gender" name="gender" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <option value="">{{ __('-- Select --') }}</option>
                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>{{ __('Female') }}</option>
                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>{{ __('Other') }}</option>
            </select>
            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
        </div>

        <!-- Address -->
        <div class="mb-6">
            <x-input-label for="address" :value="__('Address')" />
            <textarea id="address" name="address" rows="3" 
                      class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                      placeholder="Enter your complete address">{{ old('address') }}</textarea>
            <x-input-error :messages="$errors->get('address')" class="mt-2" />
        </div>

        <div class="flex items-center justify-center">
            <x-primary-button class="w-full justify-center">
                {{ __('Complete Profile') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Information -->
    <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-md">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
        </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm text-blue-700 dark:text-blue-300">
                After completing your profile, you'll be redirected to the login page where you can sign in using your email and the password you just set.
            </p>
        </div>
    </div>
</x-guest-layout>