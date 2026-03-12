@extends('layouts.guest')

@section('content')
<div class="card shadow-lg rounded-4 bg-white border-0">
    <div class="card-body p-3 p-sm-4 p-md-5">

        <div class="text-center mb-4">
            <h2 class="fw-bold fs-3 fs-md-2">Forgot Password</h2>

            <p class="text-muted small px-2">
                {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="alert alert-success mb-3" role="alert">
                <small>{{ session('status') }}</small>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="form-label fw-semibold small">
                    {{ __('Email Address') }}
                </label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    placeholder="Enter your email"
                    class="form-control form-control-lg @error('email') is-invalid @enderror"
                >

                @error('email')
                    <div class="invalid-feedback small">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- BUTTON -->
            <div class="d-grid mb-4">
                <button type="submit" class="btn btn-primary btn-lg fw-semibold w-100 reset-btn">
                    Email Password Reset Link
                </button>
            </div>

            <!-- Back -->
            <div class="text-center">
                <a href="{{ route('login') }}" class="text-decoration-none small">
                    ← Back to Login
                </a>
            </div>

        </form>

    </div>
</div>

<style>

/* FIX BUTTON */
.reset-btn{
    background-color:#0d6efd !important;
    border:none !important;
    color:#fff !important;
    padding:12px 16px !important;
    font-size:16px !important;
    border-radius:8px;
    display:block;
}

/* Mobile */
@media (max-width:576px){

    .card-body{
        padding:1.5rem !important;
    }

    .form-control-lg{
        font-size:1rem;
        padding:0.75rem 1rem;
    }

    .btn-lg{
        font-size:1rem;
        padding:0.75rem 1rem;
    }

}

/* Desktop */
@media (min-width:992px){

    .card{
        max-width:450px;
        margin:0 auto;
    }

}

</style>

@endsection