@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Modern Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-1 text-dark fw-bold">My Leave Requests</h1>
                    <p class="text-muted mb-0">Track and manage your leave applications</p>
                </div>
                <a href="{{ route('leave.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Request Leave
                </a>
            </div>
        </div>
    </div>

    @include('partials.flash-messages')

    <!-- Leave Summary Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                        <i class="fas fa-calendar-check fa-lg text-primary"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold text-primary mb-0">{{ $leaveRequests->where('status', 'approved')->count() }}</h4>
                        <p class="text-muted mb-0 small">Approved</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                        <i class="fas fa-clock fa-lg text-warning"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold text-warning mb-0">{{ $leaveRequests->where('status', 'pending')->count() }}</h4>
                        <p class="text-muted mb-0 small">Pending</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-danger bg-opacity-10 p-3 me-3">
                        <i class="fas fa-times-circle fa-lg text-danger"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold text-danger mb-0">{{ $leaveRequests->where('status', 'rejected')->count() }}</h4>
                        <p class="text-muted mb-0 small">Rejected</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                        <i class="fas fa-list-alt fa-lg text-info"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold text-info mb-0">{{ $leaveRequests->count() }}</h4>
                        <p class="text-muted mb-0 small">Total Requests</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leave Requests Table -->
    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-header bg-white border-0 pb-0" style="border-radius: 15px 15px 0 0;">
            <h5 class="fw-bold mb-3">Leave History</h5>
            @if($leaveRequests->count() > 0)
                <span class="badge bg-info text-white fs-6 px-3 py-2 rounded-pill">{{ $leaveRequests->count() }} total requests</span>
            @endif
        </div>
        <div class="card-body p-0">
            @if($leaveRequests->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="border-radius: 0 0 15px 15px; overflow: hidden;">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 px-4 py-3 fw-bold text-uppercase text-muted" style="font-size: 0.85rem;">Request</th>
                                <th class="border-0 px-4 py-3 fw-bold text-uppercase text-muted" style="font-size: 0.85rem;">Type</th>
                                <th class="border-0 px-4 py-3 fw-bold text-uppercase text-muted" style="font-size: 0.85rem;">Period</th>
                                <th class="border-0 px-4 py-3 fw-bold text-uppercase text-muted text-center" style="font-size: 0.85rem;">Days</th>
                                <th class="border-0 px-4 py-3 fw-bold text-uppercase text-muted" style="font-size: 0.85rem;">Status</th>
                                <th class="border-0 px-4 py-3 fw-bold text-uppercase text-muted" style="font-size: 0.85rem;">Submitted</th>
                                <th class="border-0 px-4 py-3 fw-bold text-uppercase text-muted text-center" style="font-size: 0.85rem;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leaveRequests as $request)
                                <tr style="border-left: 4px solid transparent;" onmouseover="this.style.borderLeftColor='#007bff'" onmouseout="this.style.borderLeftColor='transparent'">
                                    <td class="px-4 py-4">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                <i class="fas fa-calendar-alt text-primary"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold">#{{ $request->id }}</h6>
                                                <small class="text-muted">Request ID</small>
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
                                            <div class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($request->start_date)->format('M d, Y') }}</div>
                                            <small class="text-muted">to {{ \Carbon\Carbon::parse($request->end_date)->format('M d, Y') }}</small>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill fw-bold">
                                            {{ $request->total_days ?? $request->calculateTotalDays() }} {{ ($request->total_days ?? $request->calculateTotalDays()) == 1 ? 'day' : 'days' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        {!! $request->getStatusBadge() !!}
                                    </td>
                                    <td class="px-4 py-4">
                                        <div>
                                            <div class="fw-medium">{{ $request->created_at->format('M d, Y') }}</div>
                                            <small class="text-muted">{{ $request->created_at->format('g:i A') }}</small>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('leave.show', $request) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye me-1"></i>View
                                            </a>
                                            @if($request->status === 'pending')
                                                <a href="{{ route('leave.edit', $request) }}" class="btn btn-outline-warning btn-sm">
                                                    <i class="fas fa-edit me-1"></i>Edit
                                                </a>
                                                <form action="{{ route('leave.destroy', $request) }}" method="POST" class="d-inline" 
                                                      onsubmit="return confirm('Are you sure you want to delete this leave request?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                                        <i class="fas fa-trash me-1"></i>Delete
                                                    </button>
                                                </form>
                                            @endif
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
                        <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                    </div>
                    <h4 class="fw-bold text-muted mb-2">No Leave Requests</h4>
                    <p class="text-muted mb-4">You haven't submitted any leave requests yet. Start by creating your first request.</p>
                    <a href="{{ route('leave.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Request Your First Leave
                    </a>
                </div>
            @endif
        </div>
        
        @if($leaveRequests->hasPages())
            <div class="card-footer bg-white border-0">
                {{ $leaveRequests->links() }}
            </div>
        @endif
    </div>
</div>
@endsection