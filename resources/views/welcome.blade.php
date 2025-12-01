@extends('layouts.welcome')

@section('content')
<div class="container">
    <!-- Main Welcome Section -->
    <div class="row justify-content-center mb-2">
        <div class="col-md-100">
            <div class="card shadow-lg border-0 rounded-lg">
                <!-- Header Section -->
                <div class="card-header text-center custom-header text-white rounded-top">
                    <h1 class="display-4 font-weight-bold">Employee Self-Service Portal</h1>
                    <p class="lead">Your Personal Hub for All Work-Related Information</p>
                </div>

                <!-- Body Section -->
                <div class="card-body text-center">
                    <div class="row justify-content-center mb-4">
                        <div class="col-md-6">
                            <p class="mt-3">
                                Welcome to the Employee Self-Service System. <br> 
                                Access your personal and work-related information with ease. <br> 
                                Stay informed and connected to all the resources you need to thrive.
                            </p>
                        </div>
                    </div>

                    <!-- Service Icons Section -->
                    <div class="row justify-content-center mt-4 mb-2">
                        <div class="col-md-3 mb-3">
                            <div class="icon-box text-center">
                                <i class="fas fa-users fa-3x pink-icon"></i>
                                <h5 class="mt-2">Employee Directory</h5>
                                <p>Find colleagues and more.</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-1">
                            <div class="icon-box text-center">
                                <i class="fas fa-calendar-alt fa-3x text-success"></i>
                                <h5 class="mt-100">Leave Management</h5>
                                <p>Request and manage your time off.</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-1">
                            <div class="icon-box text-center">
                                <i class="fas fa-briefcase fa-3x text-warning"></i>
                                <h5 class="mt-2">Payroll & Benefits</h5>
                                <p>Access payslip and more.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Call to Action Section -->
                    <div class="d-grid gap-0 d-md-flex justify-content-center mt-2">
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-5">Login to Your Account</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Header Background */
        .custom-header {
            background: linear-gradient(135deg, #ff69b4, #ff85c2) !important;
            color: white;
        }

        /* Pink Login Button */
        .btn-primary {
            background-color: #ff69b4 !important;
            border-color: #ff69b4 !important;
            color: white !important;
        }

        .btn-primary:hover {
            background-color: #ff4fa3 !important;
            border-color: #ff4fa3 !important;
        }

        /* Icon Boxes */
        .icon-box {
            border: 2px solid #f4f4f4;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            background-color: #fff;
            transition: transform 0.3s ease;
        }

        .icon-box:hover {
            transform: translateY(-10px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        /* Optional: Make default icons pink too */
        .pink-icon {
            color: #ff69b4 !important;
        }
    </style>
@endsection
