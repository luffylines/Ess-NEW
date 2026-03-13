@extends('layouts.guest')

@section('content')
<div class="d-flex justify-content-center align-items-center min-vh-100" style="background: linear-gradient(90deg, #f8cdda 0%, #f88fa6 100%);">
<form method="POST" action="{{ route('password.store') }}" class="card shadow p-4 mx-auto" style="max-width: 400px; border-radius: 1.5rem; min-width: 320px;">
    @csrf
    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <div class="text-center mb-3">
        <i class="bi bi-person-circle display-4 text-primary mb-2"></i>
        @php
            $user = \App\Models\User::where('email', $request->email)->first();
        @endphp
        <div class="fw-semibold mb-1" style="font-size:1.1em;">
            @if($user)
                <span>{{ $user->name }}</span>
            @else
                <span>{{ $request->email }}</span>
            @endif
        </div>
        <div class="text-muted small mb-2" style="font-size:0.98em;">{{ $request->email }}</div>
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <div class="mb-3 position-relative">
        <label for="password" class="form-label fw-semibold">Password</label>
        <div class="position-relative">
            <input id="password" name="password" type="password"
                   class="form-control form-control-lg pe-5 @error('password') is-invalid @enderror"
                   autocomplete="new-password" minlength="6" maxlength="32" pattern="(?=.*[A-Z]).{6,}">
            <span class="position-absolute top-50 end-0 translate-middle-y me-3 toggle-password"
                  style="cursor: pointer;" data-target="password">
                <i class="bi bi-eye-slash fs-5"></i>
            </span>
        </div>
        <div id="passwordHelp" class="form-text mt-1">
            Password must be at least 6 characters and contain at least one uppercase letter.
        </div>
        @error('password')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

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

    <div class="d-grid mt-3">
        <button type="submit" class="btn btn-primary btn-lg rounded-pill" id="submitBtn">Reset Password</button>
    </div>
</form>
</div>
<script>
// Password match and rules feedback
document.addEventListener('DOMContentLoaded', function() {
    const password = document.getElementById('password');
    const confirm = document.getElementById('password_confirmation');
    const feedback = document.getElementById('confirmPasswordFeedback');
    const submitBtn = document.getElementById('submitBtn');
</script>
    function checkPasswordMatch() {
        if (!password.value && !confirm.value) {
            feedback.textContent = '';
            submitBtn.disabled = true;
            return;
        }
        if (password.value !== confirm.value) {
            feedback.textContent = 'Passwords do not match.';
            feedback.classList.remove('text-success');
            feedback.classList.add('text-danger');
            submitBtn.disabled = true;
        } else if (!/(?=.*[A-Z]).{6,}/.test(password.value)) {
            feedback.textContent = 'Password must be at least 6 characters and contain an uppercase letter.';
            feedback.classList.remove('text-success');
            feedback.classList.add('text-danger');
            submitBtn.disabled = true;
        } else {
            feedback.textContent = 'Passwords match!';
            feedback.classList.remove('text-danger');
            feedback.classList.add('text-success');
            submitBtn.disabled = false;
        }
    }
    password.addEventListener('input', checkPasswordMatch);
    confirm.addEventListener('input', checkPasswordMatch);
    checkPasswordMatch();
});
</script>
</script>
@endsection
