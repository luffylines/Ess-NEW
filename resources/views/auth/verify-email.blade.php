@extends('layouts.guest')

@section('content')
<div class="card shadow-lg rounded-4 bg-white">
    <div class="card-body p-4 p-md-5">

        <!-- Logo / Title -->
        <div class="mb-4 text-center">
            <span class="h2 fw-bold text-primary">YourCompany</span>
        </div>

        <h4 class="fw-semibold mb-3 text-secondary text-center">Verify Your Email</h4>

        <p class="text-muted mb-4 text-center">
            Thanks for signing up! Before getting started, please verify your email address 
            by clicking on the link we just emailed to you.  
            If you didn’t receive the email, you can request another one below.
        </p>

        <!-- Status Message -->
        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success rounded-3 shadow-sm text-center">
                A new verification link has been sent to the email address you provided during registration.
            </div>
        @endif

        <!-- Buttons -->
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between mt-4 gap-3">

            <!-- Resend Verification -->
            <form method="POST" action="{{ route('verification.send') }}" class="w-100 w-md-auto">
                @csrf
                <button type="submit" class="btn btn-gradient-primary w-100 fw-semibold rounded-3 shadow-sm">
                    Resend Verification Email
                </button>
            </form>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}" class="w-100 w-md-auto">
                @csrf
                <button type="submit" class="btn btn-outline-secondary w-100 fw-semibold rounded-3 shadow-sm">
                    Log Out
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    .btn-gradient-primary {
        background: linear-gradient(90deg, #0066ff 0%, #3399ff 100%);
        border: none;
        color: white;
    }

    .btn-gradient-primary:hover {
        background: linear-gradient(90deg, #004ecc 0%, #2672ff 100%);
    }

    .btn-outline-secondary {
        border: 1.5px solid #6c757d;
        color: #6c757d;
    }

    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: white;
    }
</style>
@endsection
