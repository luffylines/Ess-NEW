@php
    $user = Auth::user();
@endphp

<div class="container-fluid py-10  ">
    <div class="mx-auto space-y-10 space-x-0 md:space-x-4">

        {{-- Profile Information --}}
        <div class="card card-profile shadow-sm mb-4 border-0 ">
            <div class="card-body">
                <h4 class="fw-bold mb-2">Profile Information</h4>
                <p class="small mb-4">Update your profile information below.</p>

                <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('patch')

                    {{-- Profile Photo --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Profile Photo</label>
                        <div class="d-flex align-items-center gap-3">
                            @if($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" 
                                     alt="Profile Photo"
                                     id="profile-photo-preview"
                                     class="rounded-circle border"
                                     style="width: 64px; height: 64px; object-fit: cover;">
                            @else
                                <div id="profile-photo-placeholder"
                                     class="rounded-circle border d-flex align-items-center justify-content-center"
                                     style="width: 64px; height: 64px; font-weight: 600; font-size: 1.25rem;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <input type="file" id="profile_photo" name="profile_photo" accept="image/*" class="form-control form-control-sm">
                                <small>JPG, PNG, GIF up to 2MB</small>
                                @error('profile_photo')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Name --}}
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Full Name</label>
                        <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    {{-- Gender --}}
                    <div class="mb-3">
                        <label for="gender" class="form-label fw-semibold">Gender</label>
                        <select id="gender" name="gender" class="form-control" required>
                            <option value="" {{ old('gender', $user->gender) == '' ? 'selected' : '' }}>Select Gender</option>
                            <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div class="mb-3">
                        <label for="phone" class="form-label fw-semibold">Phone Number</label>
                        <input id="phone" name="phone" type="tel" class="form-control" 
                               value="{{ old('phone', $user->phone) }}" 
                               placeholder="+639XXXXXXXXX"
                               pattern="^\+63[0-9]{10}$" required>
                        <div class="form-text">
                            Format: +63 followed by 10 digits (e.g., +639171234567)
                        </div>
                        @error('phone')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Address --}}
                    <div class="mb-4">
                        <label for="address" class="form-label fw-semibold">Address</label>
                        <textarea id="address" name="address" rows="3" class="form-control" placeholder="Enter your complete address" required>{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <div class="small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex flex-column align-items-start">
                        <button type="submit" class="btn btn-primary mb-1">Save Changes</button>
                        @if (session('status') === 'profile-updated')
                            <span class="text-success small">Profile updated successfully.</span>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- Email Update --}}
        <div class="card card-profile shadow-sm mb-4 border-0">
            <div class="card-body">
                <h4 class="fw-bold mb-2">Email Address</h4>
                <p class=" small mb-4">Update your email address. You'll need to verify your new email.</p>

                <form method="POST" action="{{ route('profile.updateEmail') }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="alert alert-warning alert-dismissible fade show auto-hide-alert" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>Email Verification Required!</strong>
                            <p class="mb-2 mt-1">Your email address is unverified.</p>
                            <button type="button" onclick="document.getElementById('send-verification').submit();" class="btn btn-link p-0 text-decoration-underline">
                                Click here to re-send the verification email.
                            </button>
                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 text-success small">A new verification link has been sent to your email address.</p>
                            @endif
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @include('partials.flash-messages')

                    <div class="d-flex flex-column align-items-start">
                        <button type="submit" class="btn btn-primary mb-1">Update Email</button>
                        @if (session('status') === 'email-updated')
                            <span class="text-success small">Email updated successfully.</span>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <form id="send-verification" method="POST" action="{{ route('verification.send') }}" style="display: none;">
            @csrf
        </form>

        {{-- Password Update --}}
        <div class="card card-password shadow-sm mb-4 border-0">
            <div class="card-body">
                <header class="mb-3">
                    <h2 class="h4 fw-medium">Update Password</h2>
                    <p class="small">Ensure your account is using a long, random password to stay secure.</p>
                </header>

                <form method="post" action="{{ route('profile.updatePassword') }}" class="mt-4 mb-3" novalidate>
                    @csrf
                    @method('patch')

                    <div class="mb-3">
                        <label for="update_password_current_password" class="form-label fw-semibold">Current Password</label>
                        <input id="update_password_current_password" name="current_password" type="password" class="form-control" autocomplete="current-password" />
                        @error('current_password', 'updatePassword')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="update_password_password" class="form-label fw-semibold">New Password</label>
                        <input id="update_password_password" name="password" type="password" class="form-control" autocomplete="new-password" />
                        @error('password', 'updatePassword')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="update_password_password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                        <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password" />
                        @error('password_confirmation', 'updatePassword')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex flex-column align-items-start">
                        <button type="submit" class="btn btn-primary mb-1">Save</button>
                        @if (session('status') === 'password-updated')
                            <span class="text-success small">Password updated successfully.</span>
                        @endif
                    </div>
                </form>
            </div>
        </div>



{{-- Profile Photo Preview --}}
<script>
document.getElementById('profile_photo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            let preview = document.getElementById('profile-photo-preview');
            let placeholder = document.getElementById('profile-photo-placeholder');
            if (preview) {
                preview.src = event.target.result;
            } else if (placeholder) {
                const img = document.createElement('img');
                img.src = event.target.result;
                img.alt = 'Profile Photo Preview';
                img.id = 'profile-photo-preview';
                img.className = 'rounded-circle border';
                img.style.width = '64px';
                img.style.height = '64px';
                img.style.objectFit = 'cover';
                placeholder.replaceWith(img);
            }
        };
        reader.readAsDataURL(file);
    }
});
</script>

{{-- Theme Toggle --}}
<script>
let currentTheme = localStorage.getItem('theme') || 'light';
setTheme(currentTheme);

document.getElementById('themeToggle')?.addEventListener('click', () => {
    currentTheme = currentTheme === 'light' ? 'dark' : 'light';
    setTheme(currentTheme);
    localStorage.setItem('theme', currentTheme);
});

function setTheme(theme) {
    document.body.classList.remove('light', 'dark');
    document.body.classList.add(theme);
}
</script>

<style>
body.light {
    background-color: #f8f9fa;
    color: #212529;
}

body.dark {
    background-color: #121212;
    color: #f8f9fa;
}

/* Cards */
.card-profile {
    background-color: #e9f7fe;
}

.card-password {
    background-color: #e9f7fe; /* light blue */
}

.card-delete {
    background-color: #ffe5e5;
    border: 1px solid #ffcccc;
}

/* Form Inputs */
.form-control {
    background-color: #ffffff;
    color: #212529;
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
}

/* Delete Button */
.btn-delete {
    background-color: #dc3545;
    color: #ffffff;
    font-weight: 600;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    border: none;
    transition: background-color 0.2s ease;
}

.btn-delete:hover {
    background-color: #b52a3a;
}

/* Secondary Buttons */
.btn-secondary {
    background-color: #6c757d;
    color: #ffffff;
    border-radius: 0.375rem;
    padding: 0.5rem 1rem;
}

/* Alerts */
.alert {
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const phoneInput = document.getElementById('phone');
    
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value;
            
            // Remove all non-digit characters except +
            value = value.replace(/[^\d+]/g, '');
            
            // Auto-format based on input
            if (value.length > 0 && !value.startsWith('+63')) {
                if (value.startsWith('63')) {
                    value = '+' + value;
                } else if (value.startsWith('09')) {
                    value = '+63' + value.substring(1);
                } else if (value.startsWith('9') && value.length <= 10) {
                    value = '+63' + value;
                } else if (!value.startsWith('+')) {
                    value = '+639' + value.replace(/^0+/, '');
                }
            }
            
            // Limit to +63 + 10 digits
            if (value.startsWith('+63') && value.length > 13) {
                value = value.substring(0, 13);
            }
            
            e.target.value = value;
        });
        
        phoneInput.addEventListener('blur', function(e) {
            let value = e.target.value;
            if (value && !value.match(/^\+63[0-9]{10}$/)) {
                e.target.classList.add('is-invalid');
            } else {
                e.target.classList.remove('is-invalid');
            }
        });
    }
});
</script>
