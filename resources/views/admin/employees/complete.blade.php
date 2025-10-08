@extends('layouts.guest')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="text-center mb-4">
            <h2 class="h3 fw-bold text-primary">Complete Your Profile</h2>
            <p class="text-muted">
                Welcome <strong>{{ $user->name }}</strong>! Please complete your profile setup to get started.
            </p>
            @if($user->employee_id)
                <div class="alert alert-info">
                    <i class="bi bi-badge-check"></i> Your Employee ID: <strong>{{ $user->employee_id }}</strong>
                </div>
            @endif
        </div>

        <!-- Display Success/Error Messages -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('employees.complete.store', $user->remember_token) }}">
            @csrf

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label fw-semibold">Password</label>
                <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                       required autofocus>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" 
                       class="form-control @error('password_confirmation') is-invalid @enderror" required>
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Phone -->
            <div class="mb-3">
                <label for="phone" class="form-label fw-semibold">Phone Number</label>
                <input id="phone" name="phone" type="tel" class="form-control @error('phone') is-invalid @enderror" 
                       value="{{ old('phone') }}">
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Gender -->
            <div class="mb-3">
                <label for="gender" class="form-label fw-semibold">Gender</label>
                <select id="gender" name="gender" class="form-select @error('gender') is-invalid @enderror">
                    <option value="">-- Select --</option>
                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('gender')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Address -->
            <div class="mb-4">
                <label for="address" class="form-label fw-semibold">Address</label>
                <textarea id="address" name="address" rows="3" 
                          class="form-control @error('address') is-invalid @enderror"
                          placeholder="Enter your complete address">{{ old('address') }}</textarea>
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Complete Profile
                </button>
            </div>
        </form>

        <!-- Information -->
        <div class="mt-4 alert alert-info">
            <div class="d-flex align-items-start">
                <i class="bi bi-info-circle me-2 mt-1"></i>
                <div>
                    <strong>Next Steps:</strong>
                    <p class="mb-0 small">
                        After completing your profile, you'll be redirected to the login page where you can sign in using your email and the password you just set.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection