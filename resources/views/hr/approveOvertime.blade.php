@extends('layouts.app')

@section('content')
    <div class="mx-auto px-4 py-4">
        <!-- Modern Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h2 fw-bold mb-1">
                    <i class="fas fa-clipboard-check text-primary me-2"></i>Approve Overtime Requests
                </h1>
                <p class=" mb-0">Review and approve pending overtime requests from employees</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 10px; border-left: 4px solid #28a745;">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 10px; border-left: 4px solid #dc3545;">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Overtime Requests Section -->
        <div class="mb-5">
            <div class="d-flex align-items-center mb-4">
                <h2 class="h3 fw-semibold mb-0 me-3">
                    <i class="fas fa-list-check text-warning me-2"></i>Pending Overtime Requests
                </h2>
                <span class="badge bg-warning text-dark">{{ count($overtimeRequests) }} pending</span>
            </div>
            
            @if(count($overtimeRequests) > 0)
                <!-- Modern Card-based Design -->
                <div class="row">
                    @foreach($overtimeRequests as $request)
                        <div class="col-lg-6 col-xl-4 mb-4">
                            <div class="card h-100 shadow-sm border-0" style="transition: transform 0.2s; border-radius: 12px;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                                <div class="card-header bg-gradient-primary" style="border-radius: 12px 12px 0 0;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 fw-bold">
                                            <i class="fas fa-user me-2 text-dark"></i>{{ $request->user->name }}
                                        </h6>
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-clock me-1"></i>{{ $request->total_hours }}h
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-calendar text-primary me-2"></i>
                                                <strong class="me-2">Date:</strong>
                                                <span class="text-muted">{{ $request->overtime_date->format('M d, Y') }}</span>
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-play text-success me-2"></i>
                                                <strong class="me-2">Start:</strong>
                                                <span class="text-muted">{{ \Carbon\Carbon::parse($request->start_time)->format('h:i A') }}</span>
                                            </div>
                                            <div class="d-flex align-items-center mb-3">
                                                <i class="fas fa-stop text-danger me-2"></i>
                                                <strong class="me-2">End:</strong>
                                                <span class="text-muted">{{ \Carbon\Carbon::parse($request->end_time)->format('h:i A') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex align-items-start">
                                            <i class="fas fa-comment-alt text-info me-2 mt-1"></i>
                                            <div>
                                                <strong class="d-block mb-1">Reason:</strong>
                                                <p class="text-muted mb-0 small">{{ $request->reason }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-light" style="border-radius: 0 0 12px 12px;">
                                    <form method="POST" action="{{ route('hr.approveOvertime') }}" class="mb-2">
                                        @csrf
                                        <input type="hidden" name="request_id" value="{{ $request->id }}">
                                        <input type="hidden" name="action" value="approve">
                                        <div class="mb-2">
                                            <input type="text" name="manager_remarks" placeholder="Add your remarks (optional)" class="form-control form-control-sm" style="border-radius: 8px;">
                                        </div>
                                        <button type="submit" class="btn btn-success btn-sm w-100 mb-2" style="border-radius: 8px;">
                                            <i class="fas fa-check me-2"></i>Approve Request
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('hr.approveOvertime') }}">
                                        @csrf
                                        <input type="hidden" name="request_id" value="{{ $request->id }}">
                                        <input type="hidden" name="action" value="reject">
                                        <div class="mb-2">
                                            <input type="text" name="manager_remarks" placeholder="Reason for rejection" class="form-control form-control-sm" style="border-radius: 8px;">
                                        </div>
                                        <button type="submit" class="btn btn-outline-danger btn-sm w-100" style="border-radius: 8px;">
                                            <i class="fas fa-times me-2"></i>Reject Request
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="">No Pending Overtime Requests</h5>
                    <p class="">All overtime requests have been processed.</p>
                </div>
            @endif
        </div>

        <!-- Quick Stats & Reports -->
        <div class="row mt-5">
            <div class="col-md-6 mb-10 mx-auto">
                <div class="card border-0 shadow-sm rounded-3 p-3">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-clock fa-2x text-primary"></i>
                        </div>
                        <h3 class="h2 fw-bold text-primary">{{ count($overtimeRequests) }}</h3>
                        <p class="text-muted mb-0">Pending Overtime Requests</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

     <!-- JavaScript for Theme Switching -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        function applyTheme() {
            const isDark = window.matchMedia("(prefers-color-scheme: dark)").matches;

            if (isDark) {
                document.body.classList.add("dark-mode");
                document.body.classList.remove("light-mode");
            } else {
                document.body.classList.add("light-mode");
                document.body.classList.remove("dark-mode");
            }
        }

        applyTheme();
        window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change", applyTheme);
    });
    </script>

    <!-- Custom Styles for Dark/Light Theme -->
    <style>
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    }
    .btn {
        transition: all 0.2s ease;
    }
    .btn:hover {
        transform: translateY(-1px);
    }

    /* Dark and light mode styling */
    /* LIGHT THEME → Card is BLACK */
    body.light-mode .card,
    body.light-mode .card-body,
    body.light-mode .card-header,
    body.light-mode .card-footer {
        background-color: #000 !important;
        color: #fff !important;
        border-color: #333 !important;
    }

    /* DARK THEME → Card is WHITE */
    body.dark-mode .card,
    body.dark-mode .card-body,
    body.dark-mode .card-header,
    body.dark-mode .card-footer {
        background-color: #fff !important;
        color: #000 !important;
        border-color: #ddd !important;
    }

    /* Override for specific elements in dark mode */
    body.dark-mode .text-primary {
        color: #0056b3 !important;
    }

    body.dark-mode .text-muted {
        color: #6c757d !important;
    }

    /* Override for specific elements in light mode */
    body.light-mode .text-primary {
        color: #4dabf7 !important;
    }

    body.light-mode .text-muted {
        color: #adb5bd !important;
    }

    /* Badge styling for both themes */
    body.light-mode .badge {
        color: #000 !important;
    }

    body.dark-mode .badge {
        color: #000 !important;
    }

    /* Header gradient styling */
    body.light-mode .bg-gradient-primary {
        background: linear-gradient(45deg, #333, #555) !important;
        color: #fff !important;
    }

    body.dark-mode .bg-gradient-primary {
        background: linear-gradient(45deg, #007bff, #0056b3) !important;
        color: #fff !important;
    }

    body.light-mode .bg-gradient-info {
        background: linear-gradient(45deg, #333, #555) !important;
        color: #fff !important;
    }

    body.dark-mode .bg-gradient-info {
        background: linear-gradient(45deg, #17a2b8, #138496) !important;
        color: #fff !important;
    }

    /* Form inputs in different themes */
    body.light-mode .form-control {
        background-color: #333 !important;
        color: #fff !important;
        border-color: #555 !important;
    }

    body.light-mode .form-control::placeholder {
        color: #adb5bd !important;
    }

    body.dark-mode .form-control {
        background-color: #fff !important;
        color: #000 !important;
        border-color: #ddd !important;
    }

    body.dark-mode .form-control::placeholder {
        color: #6c757d !important;
    }
    </style>
@endsection