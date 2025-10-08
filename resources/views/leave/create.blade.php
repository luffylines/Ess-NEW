@extends('layouts.app')

@section('content')
<div class="mx-auto p-3">
    <div class="bg-white rounded p-4">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('leave.index') }}" class="text-primary mr-4">
                <i class="fas fa-arrow-left"></i> Back to Leave Requests
            </a>
            <h2 class="h2 fw-bold text-dark">Request Leave</h2>
        </div>

        <form action="{{ route('leave.store') }}" method="POST" enctype="multipart/form-data" class="mb-3">
            @csrf

            <div class="row">
                <!-- Leave Type -->
                <div>
                    <label for="leave_type" class="d-block small fw-medium text-secondary mb-2">
                        Leave Type <span class="text-red-500">*</span>
                    </label>
                    <select name="leave_type" id="leave_type" required
                            class="w-100 px-3 py-2 border">
                        <option value="">Select Leave Type</option>
                        <option value="sick" {{ old('leave_type') == 'sick' ? 'selected' : '' }}>Sick Leave</option>
                        <option value="vacation" {{ old('leave_type') == 'vacation' ? 'selected' : '' }}>Vacation Leave</option>
                        <option value="personal" {{ old('leave_type') == 'personal' ? 'selected' : '' }}>Personal Leave</option>
                        <option value="emergency" {{ old('leave_type') == 'emergency' ? 'selected' : '' }}>Emergency Leave</option>
                        <option value="maternity" {{ old('leave_type') == 'maternity' ? 'selected' : '' }}>Maternity Leave</option>
                        <option value="paternity" {{ old('leave_type') == 'paternity' ? 'selected' : '' }}>Paternity Leave</option>
                        <option value="bereavement" {{ old('leave_type') == 'bereavement' ? 'selected' : '' }}>Bereavement Leave</option>
                    </select>
                    @error('leave_type')
                        <p class="mt-1 small text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status (hidden, defaults to pending) -->
                <input type="hidden" name="status" value="pending">
            </div>

            <div class="row">
                <!-- Start Date -->
                <div>
                    <label for="start_date" class="d-block small fw-medium text-secondary mb-2">
                        Start Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="start_date" id="start_date" required
                           value="{{ old('start_date') }}"
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
                           value="{{ old('end_date') }}"
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
                          class="w-100 px-3 py-2 border">{{ old('reason') }}</textarea>
                @error('reason')
                    <p class="mt-1 small text-danger">{{ $message }}</p>
                @enderror
            </div>

            <!-- Supporting Document -->
            <div>
                <label for="supporting_document" class="d-block small fw-medium text-secondary mb-2">
                    Supporting Document
                </label>
                <input type="file" name="supporting_document" id="supporting_document"
                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                       class="w-100 px-3 py-2 border">
                <p class="mt-1 small text-muted">
                    Optional: Upload medical certificate, travel documents, or other supporting files (PDF, DOC, DOCX, JPG, PNG - max 5MB)
                </p>
                @error('supporting_document')
                    <p class="mt-1 small text-danger">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="d-flex justify-content-end gap-3">
                <a href="{{ route('leave.index') }}" 
                   class="py-2 bg-secondary text-secondary rounded">
                    Cancel
                </a>
                <button type="submit" 
                        class="py-2 bg-primary text-white rounded">
                    <i class="fas fa-paper-plane mr-2"></i>Submit Leave Request
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    
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
});
</script>
@endsection