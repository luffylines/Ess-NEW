@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Modern Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-1 fw-bold">Leave Request Management</h1>
                    <p class="mb-0">Review and approve employee leave applications</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary btn-sm" onclick="location.reload()">
                        <i class="fas fa-refresh me-1"></i>Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>

    @include('partials.flash-messages')

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                        <i class="fas fa-clock fa-lg text-primary"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold text-primary mb-0">{{ count($leaveRequests) }}</h4>
                        <p class="text-muted mb-0 small">Pending Requests</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                        <i class="fas fa-check-circle fa-lg text-success"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold text-success mb-0">{{ date('j') }}</h4>
                        <p class="text-muted mb-0 small">Processed Today</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                        <i class="fas fa-calendar-alt fa-lg text-warning"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold text-warning mb-0">{{ date('F') }}</h4>
                        <p class="text-muted mb-0 small">Current Period</p>
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
                                                    @if($request->user->profile_photo && file_exists(public_path('storage/' . $request->user->profile_photo)))
                                                        <img src="{{ asset('storage/' . $request->user->profile_photo) }}" 
                                                             alt="{{ $request->user->name }}" 
                                                             class="rounded-circle me-3" 
                                                             style="width: 32px; height: 32px; object-fit: cover; border: 1px solid #dee2e6;">
                                                    @else
                                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                             style="width: 32px; height: 32px; font-size: 0.75rem;">
                                                            <span class="text-white fw-bold">{{ strtoupper(substr($request->user->name, 0, 2)) }}</span>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0 fw-semibold" style="font-size: 0.9rem;">{{ $request->user->name }}</h6>
                                                        <small class="text-muted" style="font-size: 0.75rem;">{{ $request->user->position ?? 'Employee' }}</small>
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
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <!-- Quick Action Buttons -->
                                                    <button class="btn btn-success btn-sm" 
                                                            onclick="showApprovalModal({{ $request->id }}, 'approve', '{{ addslashes($request->user->name) }}')">
                                                        <i class="fas fa-check me-1"></i>Approve
                                                    </button>
                                                    <button class="btn btn-danger btn-sm" 
                                                            onclick="showApprovalModal({{ $request->id }}, 'reject', '{{ addslashes($request->user->name) }}')">
                                                        <i class="fas fa-times me-1"></i>Reject
                                                    </button>
                                                    
                                                    <!-- View Details Button -->
                                                    <button class="btn btn-outline-primary btn-sm" 
                                                            onclick="showLeaveDetails({{ $request->id }}, '{{ addslashes($request->reason) }}', '{{ $request->start_date->format('M d, Y') }}', '{{ $request->end_date->format('M d, Y') }}', '{{ $request->total_days }}')">
                                                        <i class="fas fa-eye me-1"></i>Details
                                                    </button>
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

<!-- Approval/Rejection Modal -->
<div class="modal fade" id="approvalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approvalModalTitle">Approve Leave Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="approvalForm" method="POST" action="{{ route('hr.approveleave') }}">
                @csrf
                <input type="hidden" name="request_id" id="modalRequestId">
                <input type="hidden" name="action" id="modalAction">
                <div class="modal-body">
                    <div class="mb-3">
                        <strong>Employee:</strong> <span id="modalEmployeeName"></span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" id="remarksLabel">Manager Remarks</label>
                        <textarea name="manager_remarks" id="modalRemarks" class="form-control" rows="3" 
                                  placeholder="Add your comments..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn" id="modalSubmitBtn">Approve</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Leave Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Leave Request Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-4"><strong>Duration:</strong></div>
                    <div class="col-sm-8" id="detailsDuration"></div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-4"><strong>Period:</strong></div>
                    <div class="col-sm-8" id="detailsPeriod"></div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-4"><strong>Reason:</strong></div>
                    <div class="col-sm-8" id="detailsReason"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
</div>
<!-- JavaScript for Theme Switching -->
<script>
// Modal functions
function showApprovalModal(requestId, action, employeeName) {
    document.getElementById('modalRequestId').value = requestId;
    document.getElementById('modalAction').value = action;
    document.getElementById('modalEmployeeName').textContent = employeeName;
    
    if (action === 'approve') {
        document.getElementById('approvalModalTitle').textContent = 'Approve Leave Request';
        document.getElementById('remarksLabel').textContent = 'Approval Notes (Optional)';
        document.getElementById('modalRemarks').placeholder = 'Add approval notes...';
        document.getElementById('modalSubmitBtn').textContent = 'Approve';
        document.getElementById('modalSubmitBtn').className = 'btn btn-success';
    } else {
        document.getElementById('approvalModalTitle').textContent = 'Reject Leave Request';
        document.getElementById('remarksLabel').textContent = 'Rejection Reason (Required)';
        document.getElementById('modalRemarks').placeholder = 'Please provide reason for rejection...';
        document.getElementById('modalSubmitBtn').textContent = 'Reject';
        document.getElementById('modalSubmitBtn').className = 'btn btn-danger';
    }
    
    new bootstrap.Modal(document.getElementById('approvalModal')).show();
}

function showLeaveDetails(requestId, reason, startDate, endDate, totalDays) {
    document.getElementById('detailsDuration').textContent = totalDays + ' day' + (totalDays == 1 ? '' : 's');
    document.getElementById('detailsPeriod').textContent = startDate + ' to ' + endDate;
    document.getElementById('detailsReason').textContent = reason;
    
    new bootstrap.Modal(document.getElementById('detailsModal')).show();
}

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