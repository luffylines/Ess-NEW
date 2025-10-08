@extends('layouts.app')

@section('content')
<div class="mx-auto p-3">
    <div class="bg-white rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center">
                <a href="{{ route('leave.index') }}" class="text-primary mr-4">
                    <i class="fas fa-arrow-left"></i> Back to Leave Requests
                </a>
                <h2 class="h2 fw-bold text-dark">Leave Request #{{ $leaveRequest->id }}</h2>
            </div>
            <div class="d-flex gap-2">
                {!! $leaveRequest->getStatusBadge() !!}
                @if($leaveRequest->status === 'pending' && $leaveRequest->user_id === auth()->id())
                    <a href="{{ route('leave.edit', $leaveRequest) }}" 
                       class="bg-warning text-white px-4 py-2 rounded">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                @endif
            </div>
        </div>

        <div class="row">
            <!-- Request Details -->
            <div class="mb-3">
                <div class="bg-light rounded p-3">
                    <h3 class="h4 fw-semibold text-dark mb-3">Request Details</h3>
                    
                    <div class="mb-2">
                        <div class="d-flex justify-content-between">
                            <span class="fw-medium text-muted">Employee:</span>
                            <span class="">{{ $leaveRequest->user->name }}</span>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <span class="fw-medium text-muted">Leave Type:</span>
                            <span class="">{{ ucfirst($leaveRequest->leave_type) }}</span>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <span class="fw-medium text-muted">Start Date:</span>
                            <span class="">{{ \Carbon\Carbon::parse($leaveRequest->start_date)->format('M d, Y (l)') }}</span>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <span class="fw-medium text-muted">End Date:</span>
                            <span class="">{{ \Carbon\Carbon::parse($leaveRequest->end_date)->format('M d, Y (l)') }}</span>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <span class="fw-medium text-muted">Total Days:</span>
                            <span class="fw-semibold">{{ $leaveRequest->total_days ?? $leaveRequest->calculateTotalDays() }} days</span>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <span class="fw-medium text-muted">Submitted:</span>
                            <span class="">{{ $leaveRequest->created_at->format('M d, Y h:i A') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Reason -->
                <div class="bg-light rounded p-3">
                    <h3 class="h4 fw-semibold text-dark mb-3">Reason for Leave</h3>
                    <p class="text-secondary">{{ $leaveRequest->reason }}</p>
                </div>

                <!-- Supporting Document -->
                @if($leaveRequest->supporting_document)
                    <div class="bg-light rounded p-3">
                        <h3 class="h4 fw-semibold text-dark mb-3">Supporting Document</h3>
                        <div class="d-flex align-items-center">
                            <i class="text-primary h2"></i>
                            <div>
                                <a href="{{ asset('storage/' . $leaveRequest->supporting_document) }}" 
                                   target="_blank" 
                                   class="text-primary fw-medium">
                                    View Document
                                </a>
                                <p class="small text-muted">Click to open in new tab</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Approval Status -->
            <div class="mb-3">
                @if($leaveRequest->status !== 'pending')
                    <div class="bg-light rounded p-3">
                        <h3 class="h4 fw-semibold text-dark mb-3">Approval Information</h3>
                        
                        <div class="mb-2">
                            @if($leaveRequest->approved_by)
                                <div class="d-flex justify-content-between">
                                    <span class="fw-medium text-muted">Reviewed By:</span>
                                    <span class="">{{ $leaveRequest->approver->name ?? 'HR Department' }}</span>
                                </div>
                            @endif
                            
                            @if($leaveRequest->approved_at)
                                <div class="d-flex justify-content-between">
                                    <span class="fw-medium text-muted">Review Date:</span>
                                    <span class="">{{ \Carbon\Carbon::parse($leaveRequest->approved_at)->format('M d, Y h:i A') }}</span>
                                </div>
                            @endif
                            
                            <div class="d-flex justify-content-between">
                                <span class="fw-medium text-muted">Status:</span>
                                {!! $leaveRequest->getStatusBadge() !!}
                            </div>
                        </div>
                    </div>

                    @if($leaveRequest->manager_remarks)
                        <div class="bg-light rounded p-3">
                            <h3 class="h4 fw-semibold text-dark mb-3">Manager Remarks</h3>
                            <p class="text-secondary">{{ $leaveRequest->manager_remarks }}</p>
                        </div>
                    @endif
                @else
                    <div class="border rounded p-3">
                        <div class="d-flex align-items-center">
                            <i class="text-warning h2 mr-3"></i>
                            <div>
                                <h3 class="h4 fw-semibold">Pending Review</h3>
                                <p class="text-yellow-700">Your leave request is waiting for manager approval.</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Timeline -->
                <div class="bg-light rounded p-3">
                    <h3 class="h4 fw-semibold text-dark mb-3">Request Timeline</h3>
                    
                    <div class="mb-3">
                        <div class="d-flex align-items-start">
                            <div class="bg-primary rounded-circle mt-1"></div>
                            <div>
                                <p class="small fw-medium">Request Submitted</p>
                                <p class="small text-muted">{{ $leaveRequest->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        
                        @if($leaveRequest->status !== 'pending')
                            <div class="d-flex align-items-start">
                                <div class="rounded-circle mt-1"></div>
                                <div>
                                    <p class="small fw-medium">
                                        Request {{ ucfirst($leaveRequest->status) }}
                                    </p>
                                    @if($leaveRequest->approved_at)
                                        <p class="small text-muted">{{ \Carbon\Carbon::parse($leaveRequest->approved_at)->format('M d, Y h:i A') }}</p>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="d-flex align-items-start">
                                <div class="bg-secondary rounded-circle mt-1"></div>
                                <div>
                                    <p class="small fw-medium text-muted">Awaiting Approval</p>
                                    <p class="small text-muted">Pending manager review</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        @if($leaveRequest->status === 'pending' && $leaveRequest->user_id === auth()->id())
            <div class="d-flex justify-content-end gap-3 mt-4">
                <form action="{{ route('leave.destroy', $leaveRequest) }}" method="POST" 
                      onsubmit="return confirm('Are you sure you want to delete this leave request?')" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="py-2 bg-danger text-white rounded">
                        <i class="fas fa-trash mr-2"></i>Delete Request
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection