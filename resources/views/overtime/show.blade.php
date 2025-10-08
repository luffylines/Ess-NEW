<x-app-layout>
    <div class="mx-auto px-4 py-4 container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 fw-bold">Overtime Request Details</h1>
            <a href="{{ route('overtime.index') }}" class="text-primary">
                ← Back to My Overtime
            </a>
        </div>

        <div class="bg-white rounded shadow">
            <!-- Header with status -->
            <div class="py-3 bg-light border">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="h4 fw-semibold">
                            Overtime Request #{{ $overtimeRequest->id }}
                        </h2>
                        <p class="small">
                            Submitted on {{ $overtimeRequest->created_at->format('M d, Y \a\t H:i') }}
                        </p>
                    </div>
                    <span class="px-3 py-1 small fw-semibold rounded-circle">
                        {{ ucfirst($overtimeRequest->status) }}
                    </span>
                </div>
            </div>

            <!-- Request Details -->
            <div class="py-4">
                <div class="row">
                    <!-- Date and Time -->
                    <div>
                        <h3 class="small fw-medium text-secondary mb-2">Date & Time</h3>
                        <div class="bg-light rounded p-3">
                            <p class="small"><strong>Date:</strong> {{ $overtimeRequest->overtime_date->format('M d, Y') }}</p>
                            <p class="small"><strong>Start Time:</strong> {{ $overtimeRequest->start_time->format('H:i') }}</p>
                            <p class="small"><strong>End Time:</strong> {{ $overtimeRequest->end_time->format('H:i') }}</p>
                            <p class="small"><strong>Total Hours:</strong> {{ $overtimeRequest->total_hours }} hours</p>
                        </div>
                    </div>

                    <!-- Status Information -->
                    <div>
                        <h3 class="small fw-medium text-secondary mb-2">Status Information</h3>
                        <div class="bg-light rounded p-3">
                            <p class="small"><strong>Status:</strong> {{ ucfirst($overtimeRequest->status) }}</p>
                            @if($overtimeRequest->approved_by)
                                <p class="small"><strong>Reviewed by:</strong> {{ $overtimeRequest->approvedBy->name }}</p>
                                <p class="small"><strong>Reviewed on:</strong> {{ $overtimeRequest->approved_at->format('M d, Y \a\t H:i') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Reason -->
                <div class="mt-4">
                    <h3 class="small fw-medium text-secondary mb-2">Reason for Overtime</h3>
                    <div class="bg-light rounded p-3">
                        <p class="small">{{ $overtimeRequest->reason }}</p>
                    </div>
                </div>

                <!-- Manager Remarks -->
                @if($overtimeRequest->manager_remarks)
                    <div class="mt-4">
                        <h3 class="small fw-medium text-secondary mb-2">Manager's Remarks</h3>
                        <div class="bg-light rounded p-3">
                            <p class="small">{{ $overtimeRequest->manager_remarks }}</p>
                        </div>
                    </div>
                @endif

                <!-- Supporting Document -->
                @if($overtimeRequest->supporting_document)
                    <div class="mt-4">
                        <h3 class="small fw-medium text-secondary mb-2">Supporting Document</h3>
                        <div class="bg-light rounded p-3">
                            <a href="{{ \Illuminate\Support\Facades\Storage::url($overtimeRequest->supporting_document) }}" 
                               target="_blank"
                               class="align-items-center text-primary">
                                <i class="fas fa-file mr-2"></i>
                                View Document
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Actions -->
            @if($overtimeRequest->status === 'pending')
                <div class="py-3 bg-light border">
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('overtime.edit', $overtimeRequest) }}" 
                           class="px-4 py-2 text-white">
                            Edit Request
                        </a>
                        <form method="POST" action="{{ route('overtime.destroy', $overtimeRequest) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="px-4 py-2 text-white"
                                    onclick="return confirm('Are you sure you want to delete this overtime request?')">
                                Delete Request
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>