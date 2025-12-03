<x-app-layout>
    <div class="mx-auto px-4 py-4 container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 fw-bold">Overtime Request Details</h1>
            <a href="{{ route('overtime.index') }}" class="text-primary">
                ← Back to My Overtime
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <!-- Header with status -->
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="h4 fw-bold mb-1 text-white">
                            <i class="fas fa-clock me-2"></i>Overtime Request #{{ $overtimeRequest->id }}
                        </h2>
                        <p class="mb-0 opacity-75">
                            <i class="fas fa-calendar me-1"></i>Submitted on 
                            @if($overtimeRequest->created_at)
                                {{ \Carbon\Carbon::parse($overtimeRequest->created_at)->format('M d, Y \a\t H:i') }}
                            @else
                                {{ \Carbon\Carbon::now()->format('M d, Y \a\t H:i') }}
                            @endif
                        </p>
                    </div>
                    @php
                        $statusClasses = [
                            'pending' => 'bg-warning text-dark',
                            'approved' => 'bg-success text-white',
                            'rejected' => 'bg-danger text-white',
                        ];
                        $statusIcons = [
                            'pending' => 'fas fa-clock',
                            'approved' => 'fas fa-check-circle',
                            'rejected' => 'fas fa-times-circle',
                        ];
                    @endphp
                    <span class="badge {{ $statusClasses[$overtimeRequest->status] ?? 'bg-secondary' }} px-3 py-2 fs-6">
                        <i class="{{ $statusIcons[$overtimeRequest->status] ?? 'fas fa-question' }} me-1"></i>{{ ucfirst($overtimeRequest->status) }}
                    </span>
                </div>
            </div>

            <!-- Request Details -->
            <div class="card-body">
                <div class="row g-4">
                    <!-- Date and Time -->
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <h5 class="text-primary fw-bold mb-3">
                                <i class="fas fa-calendar-alt me-2"></i>Date & Time
                            </h5>
                            <div class="mb-2">
                                <strong class="text-secondary">Date:</strong>
                                <span class="ms-2">
                                    {{ $overtimeRequest->overtime_date ? $overtimeRequest->overtime_date->format('M d, Y') : 'Date not available' }}
                                </span>
                            </div>
                            <div class="mb-2">
                                <strong class="text-secondary">Start Time:</strong>
                                <span class="ms-2 badge bg-info text-dark">
                                    {{ $overtimeRequest->start_time ? \Carbon\Carbon::parse($overtimeRequest->start_time)->format('h:i A') : 'Time not available' }}
                                </span>
                            </div>
                            <div class="mb-2">
                                <strong class="text-secondary">End Time:</strong>
                                <span class="ms-2 badge bg-info text-dark">
                                    {{ $overtimeRequest->end_time ? \Carbon\Carbon::parse($overtimeRequest->end_time)->format('h:i A') : 'Time not available' }}
                                </span>
                            </div>
                            <div class="mb-0">
                                <strong class="text-secondary">Total Hours:</strong>
                                <span class="ms-2 badge bg-primary text-white fs-6">{{ number_format($overtimeRequest->total_hours, 2) }} hours</span>
                            </div>
                        </div>
                    </div>

                    <!-- Status Information -->
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <h5 class="text-primary fw-bold mb-3">
                                <i class="fas fa-info-circle me-2"></i>Status Information
                            </h5>
                            <div class="mb-2">
                                <strong class="text-secondary">Current Status:</strong>
                                <span class="ms-2 badge {{ $statusClasses[$overtimeRequest->status] ?? 'bg-secondary' }}">
                                    <i class="{{ $statusIcons[$overtimeRequest->status] ?? 'fas fa-question' }} me-1"></i>
                                    {{ ucfirst($overtimeRequest->status) }}
                                </span>
                            </div>
                            @if($overtimeRequest->approved_by)
                                <div class="mb-2">
                                    <strong class="text-secondary">Reviewed by:</strong>
                                    <span class="ms-2">{{ $overtimeRequest->approvedBy->name }}</span>
                                </div>
                                @if($overtimeRequest->approved_at)
                                    <div class="mb-0">
                                        <strong class="text-secondary">Reviewed on:</strong>
                                        <span class="ms-2">{{ \Carbon\Carbon::parse($overtimeRequest->approved_at)->format('M d, Y \a\t H:i') }}</span>
                                    </div>
                                @endif
                            @else
                                <div class="text-muted">
                                    <i class="fas fa-hourglass-half me-1"></i>
                                    Waiting for manager approval
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Reason -->
                <div class="mt-4">
                    <div class="border rounded p-3">
                        <h5 class="text-primary fw-bold mb-3">
                            <i class="fas fa-comment-alt me-2"></i>Reason for Overtime
                        </h5>
                        <div class="bg-light rounded p-3">
                            <p class="mb-0">{{ $overtimeRequest->reason }}</p>
                        </div>
                    </div>
                </div>

                <!-- Manager Remarks -->
                @if($overtimeRequest->manager_remarks)
                    <div class="mt-4">
                        <div class="border rounded p-3 border-warning">
                            <h5 class="text-warning fw-bold mb-3">
                                <i class="fas fa-user-tie me-2"></i>Manager's Remarks
                            </h5>
                            <div class="bg-light rounded p-3">
                                <p class="mb-0">{{ $overtimeRequest->manager_remarks }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Supporting Document -->
                @if($overtimeRequest->supporting_document)
                    <div class="mt-4">
                        <div class="border rounded p-3">
                            <h5 class="text-primary fw-bold mb-3">
                                <i class="fas fa-paperclip me-2"></i>Supporting Document
                            </h5>
                            <div class="bg-light rounded p-3">
                                <a href="{{ \Illuminate\Support\Facades\Storage::url($overtimeRequest->supporting_document) }}" 
                                   target="_blank"
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-download me-2"></i>Download Document
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Actions -->
            @if($overtimeRequest->status === 'pending')
                <div class="card-footer bg-light border-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            You can edit or delete this request while it's pending approval.
                        </small>
                        <div class="btn-group">
                            <a href="{{ route('overtime.edit', $overtimeRequest->id) }}" 
                               class="btn btn-warning btn-sm">
                                <i class="fas fa-edit me-1"></i>Edit Request
                            </a>
                            <form method="POST" action="{{ route('overtime.destroy', $overtimeRequest->id) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this overtime request?')">
                                    <i class="fas fa-trash me-1"></i>Delete Request
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>