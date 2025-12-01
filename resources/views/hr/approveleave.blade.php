@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Classic Elegant Header -->
    <div class="row mb-1">
        <div class="col-12">
            <div class="text-center border-bottom pb-4 mb-4">
                <h1 class="display-5 fw-bold text-primary mb-2" style="font-family: 'Georgia', serif;">Leave Request Management</h1>
                <p class="lead  mb-0">Review and approve employee leave applications with elegance and efficiency</p>
                <div class="mt-3">
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" style="border-left: 4px solid #28a745 !important; border-radius: 10px;">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Success!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" style="border-left: 4px solid #dc3545 !important; border-radius: 10px;">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Error!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-3">
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card border-0 shadow-sm text-center" style="border-radius: 15px; border-top: 4px solid #007bff;">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <i class="fas fa-clock fa-3x text-primary"></i>
                    </div>
                    <h3 class="fw-bold text-primary mb-1">{{ count($leaveRequests) }}</h3>
                    <p class="text-muted mb-0">Pending Requests</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card border-0 shadow-sm text-center" style="border-radius: 15px; border-top: 4px solid #28a745;">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <i class="fas fa-check-circle fa-3x text-success"></i>
                    </div>
                    <h3 class="fw-bold text-success mb-1">{{ date('j') }}</h3>
                    <p class="text-muted mb-0">Processed Today</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card border-0 shadow-sm text-center" style="border-radius: 15px; border-top: 4px solid #ffc107;">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <i class="fas fa-calendar-alt fa-3x text-warning"></i>
                    </div>
                    <h3 class="fw-bold text-warning mb-1">{{ date('F') }}</h3>
                    <p class="text-muted mb-0">Current Period</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Leave Requests Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center" style="border-radius: 15px 15px 0 0; padding: 1.5rem;">
                    <div>
                        <h4 class="mb-0 fw-bold text-dark">
                            <i class="fas fa-list-alt text-primary me-3"></i>Pending Leave Applications
                        </h4>
                        <p class="text-muted mb-0 mt-1">Review and process employee leave requests</p>
                    </div>
                    @if(count($leaveRequests) > 0)
                        <span class="badge bg-warning text-dark fs-6 px-3 py-2 rounded-pill">{{ count($leaveRequests) }} pending</span>
                    @endif
                </div>
                <div class="card-body p-0">
                    @if(count($leaveRequests) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" style="border-radius: 0 0 15px 15px; overflow: hidden;">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 px-4 py-3 fw-bold text-uppercase text-muted" style="font-size: 0.85rem; letter-spacing: 1px;">Employee</th>
                                        <th class="border-0 px-4 py-3 fw-bold text-uppercase text-muted" style="font-size: 0.85rem; letter-spacing: 1px;">Leave Type</th>
                                        <th class="border-0 px-4 py-3 fw-bold text-uppercase text-muted" style="font-size: 0.85rem; letter-spacing: 1px;">Period</th>
                                        <th class="border-0 px-4 py-3 fw-bold text-uppercase text-muted text-center" style="font-size: 0.85rem; letter-spacing: 1px;">Duration</th>
                                        <th class="border-0 px-4 py-3 fw-bold text-uppercase text-muted" style="font-size: 0.85rem; letter-spacing: 1px;">Reason</th>
                                        <th class="border-0 px-4 py-3 fw-bold text-uppercase text-muted text-center" style="font-size: 0.85rem; letter-spacing: 1px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($leaveRequests as $index => $request)
                                        <tr style="border-left: 4px solid transparent;" onmouseover="this.style.borderLeftColor='#007bff'" onmouseout="this.style.borderLeftColor='transparent'">
                                            <td class="px-4 py-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-gradient-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                                        <span class="text-white fw-bold">{{ strtoupper(substr($request->user->name, 0, 2)) }}</span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-semibold">{{ $request->user->name }}</h6>
                                                        <small class="text-muted">Employee</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4">
                                                <span class="badge bg-info-subtle text-info px-3 py-2 rounded-pill fw-semibold">
                                                    {{ ucfirst(str_replace('_', ' ', $request->leave_type)) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4">
                                                <div>
                                                    <div class="fw-semibold text-dark">{{ $request->start_date->format('M d, Y') }}</div>
                                                    <small class="text-muted">to {{ $request->end_date->format('M d, Y') }}</small>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 text-center">
                                                <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill fw-bold">
                                                    {{ $request->total_days }} {{ $request->total_days == 1 ? 'day' : 'days' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4">
                                                <div style="max-width: 200px;">
                                                    <p class="mb-0 text-muted small">{{ Str::limit($request->reason, 80) }}</p>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4">
                                                <div class="btn-group-vertical w-100" style="gap: 8px;">
                                                    <!-- Approve Form -->
                                                    <form method="POST" action="{{ route('hr.approveleave') }}" class="mb-2">
                                                        @csrf
                                                        <input type="hidden" name="request_id" value="{{ $request->id }}">
                                                        <input type="hidden" name="action" value="approve">
                                                        <div class="mb-2">
                                                            <input type="text" name="manager_remarks" 
                                                                   placeholder="Add approval notes..." 
                                                                   class="form-control form-control-sm" 
                                                                   style="border-radius: 8px; border: 1px solid #e0e0e0;">
                                                        </div>
                                                        <button type="submit" class="btn btn-success btn-sm w-100 fw-semibold" 
                                                                style="border-radius: 8px; padding: 8px 16px;">
                                                            <i class="fas fa-check me-2"></i>Approve
                                                        </button>
                                                    </form>
                                                    
                                                    <!-- Reject Form -->
                                                    <form method="POST" action="{{ route('hr.approveleave') }}">
                                                        @csrf
                                                        <input type="hidden" name="request_id" value="{{ $request->id }}">
                                                        <input type="hidden" name="action" value="reject">
                                                        <div class="mb-2">
                                                            <input type="text" name="manager_remarks" 
                                                                   placeholder="Reason for rejection..." 
                                                                   class="form-control form-control-sm" 
                                                                   style="border-radius: 8px; border: 1px solid #e0e0e0;">
                                                        </div>
                                                        <button type="submit" class="btn btn-outline-danger btn-sm w-100 fw-semibold" 
                                                                style="border-radius: 8px; padding: 8px 16px;">
                                                            <i class="fas fa-times me-2"></i>Reject
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                            </div>
                            <h4 class="fw-bold text-muted mb-2">No Pending Leave Requests</h4>
                            <p class="text-muted mb-0">All leave applications have been processed or no requests have been submitted.</p>
                            <div class="mt-4">
                                <a href="#" class="btn btn-outline-primary btn-sm px-4" style="border-radius: 25px;">
                                    <i class="fas fa-refresh me-2"></i>Refresh
                                </a>
                            </div>
                        </div>
                    @endif
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

<!-- Custom Styles -->
<style>
.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}
.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05) !important;
}
.btn {
    transition: all 0.2s ease;
}
.btn:hover {
    transform: translateY(-1px);
}

/* Dark and light mode styling */
body.light-mode .card-body {
    background-color: #000 !important;
    color: #fff !important;
}

/* DARK THEME → Card is WHITE */
body.dark-mode .card-body {
    background-color: #fff !important;
    color: #000 !important;
}

</style>
@endsection