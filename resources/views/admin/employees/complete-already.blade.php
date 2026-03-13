@extends('layouts.guest')
@section('content')
<div class="container py-5 text-center">
    <h2 class="text-success mb-3"><i class="bi bi-check-circle"></i> Profile Already Completed</h2>
    <p class="lead">Your profile is already set up. You can now log in to your account.</p>
    <a href="{{ route('login') }}" class="btn btn-primary mt-3">Go to Login</a>
</div>
@endsection