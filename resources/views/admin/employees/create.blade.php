<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold h3 text-dark">
            {{ __('Add New Employee') }}
        </h2>
    </x-slot>

    <div class="">
        <div class="container-fluid mx-auto">
            <div class="bg-white shadow-sm">
                <div class="p-4">
                    
                    <!-- Back Button -->
                    <div class="mb-4">
                        <a href="{{ route('admin.employees.index') }}" 
                           class="align-items-center px-4 py-2 border fw-semibold small text-white">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Employees
                        </a>
                    </div>

                    <!-- Add Employee Form -->
                    <div class="w-50">
                        <form method="POST" action="{{ route('admin.employees.store') }}">
                            @csrf

                            <!-- Name -->
                            <div class="mb-3">
                                <x-input-label for="name" :value="__('Employee Name')" />
                                <x-text-input id="name" class="d-block mt-1 w-100" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <x-input-label for="email" :value="__('Email Address')" />
                                <x-text-input id="email" class="d-block mt-1 w-100" type="email" name="email" :value="old('email')" required autocomplete="username" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <!-- Role -->
                            <div class="mb-4">
                                <x-input-label for="role" :value="__('Role')" />
                                <select id="role" name="role" class="d-block mt-1 w-100 border shadow-sm" required>
                                    <option value="">{{ __('Select Role') }}</option>
                                    <option value="employee" {{ old('role') == 'employee' ? 'selected' : '' }}>{{ __('Employee') }}</option>
                                    <option value="hr" {{ old('role') == 'hr' ? 'selected' : '' }}>{{ __('HR') }}</option>
                                </select>
                                <x-input-error :messages="$errors->get('role')" class="mt-2" />
                            </div>

                            <div class="d-flex align-items-center justify-content-between">
                                <x-primary-button>
                                    {{ __('Add Employee & Send Invitation') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>

                    <!-- Information Box -->
                    <div class="p-3 bg-primary bg-opacity-10 border">
                        <div class="d-flex">
                            <div class="">
                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="small fw-medium">
                                    How it works
                                </h3>
                                <div class="mt-2 small">
                                    <ul class="mb-1">
                                        <li>Employee will receive an email invitation</li>
                                        <li>They can click the link to complete their profile</li>
                                        <li>They'll set their password and update additional info</li>
                                        <li>Once complete, they can log in to the system</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>