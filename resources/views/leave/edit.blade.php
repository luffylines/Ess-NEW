@extends('layouts.app')

@section('content')
<div class="mx-auto p-3">
    <div class="bg-white rounded p-4">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('leave.show', $leaveRequest) }}" class="text-primary mr-4">
                <i class="fas fa-arrow-left"></i> Back to Request
            </a>
            <h2 class="h2 fw-bold text-dark">Edit Leave Request #{{ $leaveRequest->id }}</h2>
        </div>

        @if($leaveRequest->status !== 'pending')
            <div class="border px-4 rounded mb-4">
                <strong>Notice:</strong> This leave request has already been {{ $leaveRequest->status }}. You cannot edit it anymore.
            </div>
        @elseif($leaveRequest->user_id !== auth()->id())
            <div class="border px-4 rounded mb-4">
                <strong>Access Denied:</strong> You can only edit your own leave requests.
            </div>
        @else
            <form action="{{ route('leave.update', $leaveRequest) }}" method="POST" enctype="multipart/form-data" class="mb-3">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Leave Type -->
                    <div>
                        <label for="leave_type" class="d-block small fw-medium text-secondary mb-2">
                            Leave Type <span class="text-red-500">*</span>
                        </label>
                        <select name="leave_type" id="leave_type" required
                                class="w-100 px-3 py-2 border">
                            <option value="">Select Leave Type</option>
                            <option value="sick" {{ (old('leave_type') ?? $leaveRequest->leave_type) == 'sick' ? 'selected' : '' }}>Sick Leave</option>
                            <option value="vacation" {{ (old('leave_type') ?? $leaveRequest->leave_type) == 'vacation' ? 'selected' : '' }}>Vacation Leave</option>
                            <option value="personal" {{ (old('leave_type') ?? $leaveRequest->leave_type) == 'personal' ? 'selected' : '' }}>Personal Leave</option>
                            <option value="emergency" {{ (old('leave_type') ?? $leaveRequest->leave_type) == 'emergency' ? 'selected' : '' }}>Emergency Leave</option>
                            <option value="maternity" {{ (old('leave_type') ?? $leaveRequest->leave_type) == 'maternity' ? 'selected' : '' }}>Maternity Leave</option>
                            <option value="paternity" {{ (old('leave_type') ?? $leaveRequest->leave_type) == 'paternity' ? 'selected' : '' }}>Paternity Leave</option>
                            <option value="bereavement" {{ (old('leave_type') ?? $leaveRequest->leave_type) == 'bereavement' ? 'selected' : '' }}>Bereavement Leave</option>
                        </select>
                        @error('leave_type')
                            <p class="mt-1 small text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status (hidden, keep current status) -->
                    <input type="hidden" name="status" value="{{ $leaveRequest->status }}">
                </div>

                <div class="row">
                    <!-- Start Date -->
                    <div>
                        <label for="start_date" class="d-block small fw-medium text-secondary mb-2">
                            Start Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="start_date" id="start_date" required
                               value="{{ old('start_date') ?? $leaveRequest->start_date }}"
                               min="{{ date('Y-m-d') }}"
                               class="w-100 px-3 py-2 border">
                        @error('start_date')
                            <p class="mt-1 small text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Date -->
                    <div>
                        <label for="end_date" class="d-block small fw-medium text-secondary mb-2">
                            End Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="end_date" id="end_date" required
                               value="{{ old('end_date') ?? $leaveRequest->end_date }}"
                               min="{{ date('Y-m-d') }}"
                               class="w-100 px-3 py-2 border">
                        @error('end_date')
                            <p class="mt-1 small text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Reason -->
                <div>
                    <label for="reason" class="d-block small fw-medium text-secondary mb-2">
                        Reason for Leave <span class="text-red-500">*</span>
                    </label>
                    <textarea name="reason" id="reason" rows="4" required
                              placeholder="Please provide a detailed reason for your leave request..."
                              class="w-100 px-3 py-2 border">{{ old('reason') ?? $leaveRequest->reason }}</textarea>
                    @error('reason')
                        <p class="mt-1 small text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Supporting Document -->
                <div>
                    <label for="supporting_document" class="d-block small fw-medium text-secondary mb-2">
                        Supporting Document
                    </label>
                    
                    @if($leaveRequest->supporting_document)
                        <div class="mb-3 bg-light rounded">
                            <p class="small text-muted mb-2">Current document:</p>
                            <div class="d-flex align-items-center">
                                <i class="fa-file-alt text-primary"></i>
                                <a href="{{ asset('storage/' . $leaveRequest->supporting_document) }}" 
                                   target="_blank" 
                                   class="text-primary">
                                    View Current Document
                                </a>
                            </div>
                        </div>
                    @endif
                    
                    <input type="file" name="supporting_document" id="supporting_document"
                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                           class="w-100 px-3 py-2 border">
                    <p class="mt-1 small text-muted">
                        Optional: Upload medical certificate, travel documents, or other supporting files (PDF, DOC, DOCX, JPG, PNG - max 5MB)
                        @if($leaveRequest->supporting_document)
                            <br><strong>Note:</strong> Uploading a new file will replace the current document.
                        @endif
                    </p>
                    @error('supporting_document')
                        <p class="mt-1 small text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Info Display -->
                <div class="bg-primary bg-opacity-10 border rounded p-3">
                    <h3 class="small fw-medium mb-2">Current Request Information</h3>
                    <div class="row gap-3 small">
                        <div>
                            <span class="text-primary fw-medium">Submitted:</span>
                            <p class="text-blue-800">{{ $leaveRequest->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div>
                            <span class="text-primary fw-medium">Status:</span>
                            <p class="text-blue-800">{!! $leaveRequest->getStatusBadge() !!}</p>
                        </div>
                        <div>
                            <span class="text-primary fw-medium">Current Days:</span>
                            <p class="text-blue-800">{{ $leaveRequest->total_days ?? $leaveRequest->calculateTotalDays() }} days</p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-end gap-3">
                    <a href="{{ route('leave.show', $leaveRequest) }}" 
                       class="py-2 bg-secondary text-secondary rounded">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="py-2 bg-primary text-white rounded">
                        <i class="fas fa-save mr-2"></i>Update Leave Request
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    
    if (startDate && endDate) {
        startDate.addEventListener('change', function() {
            endDate.min = this.value;
            if (endDate.value && endDate.value < this.value) {
                endDate.value = this.value;
            }
        });
        
        endDate.addEventListener('change', function() {
            if (this.value < startDate.value) {
                this.value = startDate.value;
            }
        });
    }
});
</script>
@endsection