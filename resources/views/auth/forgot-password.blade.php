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
            <div class="alert alert-success mb-3 alert-sm" role="alert">
                <small>{{ session('status') }}</small>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-4">
                <label for="email" class="form-label fw-semibold small">{{ __('Email') }}</label>
                <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                       name="email" value="{{ old('email') }}" required autofocus placeholder="Enter your email address">
                @error('email')
                    <div class="invalid-feedback small">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="d-grid mb-4">
                <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                    <span class="d-none d-sm-inline">{{ __('Email Password Reset Link') }}</span>
                    <span class="d-sm-none">{{ __('Send Reset Link') }}</span>
                </button>
            </div>

            <div class="text-center">
                <a href="{{ route('login') }}" class="text-decoration-none small">
                    <i class="bi bi-arrow-left me-1"></i>{{ __('Back to Login') }}
                </a>
            </div>
        </form>
    </div>
</div>

<style>
    /* Mobile specific styles */
    @media (max-width: 576px) {
        .card-body {
            padding: 1.5rem !important;
        }
        
        .form-control-lg {
            font-size: 1rem;
            padding: 0.75rem 1rem;
        }
        
        .btn-lg {
            font-size: 1rem;
            padding: 0.75rem 1rem;
        }
    }

    /* Desktop styles */
    @media (min-width: 992px) {
        .card {
            max-width: 450px;
            margin: 0 auto;
        }
    }
</style>
@endsection
