@extends('layouts.welcome')

@section('content')
<div class="container">
    <!-- Main Welcome Section -->
    <div class="row justify-content-center mb-5">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="text-center">
                    <h1 class="display-4 font-weight-bold text-primary">Employee Self-Service Portal</h1>
                    <p class="mt-3 text-muted">Welcome to the Employee Self-Service System. <br> Access your personal and work-related information with ease.<br> Stay informed and connected to all the resources you need to thrive.</p>
                </div>
                <div class="text-center">
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-5">Login to Your Account</a>
                </div>
            </div>
        </div>
    </div>

@endsection
