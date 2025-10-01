@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center mb-6">
            <a href="{{ route('leave.show', $leaveRequest) }}" class="text-blue-600 hover:text-blue-800 mr-4">
                <i class="fas fa-arrow-left"></i> Back to Request
            </a>
            <h2 class="text-2xl font-bold text-gray-800">Edit Leave Request #{{ $leaveRequest->id }}</h2>
        </div>

        @if($leaveRequest->status !== 'pending')
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <strong>Notice:</strong> This leave request has already been {{ $leaveRequest->status }}. You cannot edit it anymore.
            </div>
        @elseif($leaveRequest->user_id !== auth()->id())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <strong>Access Denied:</strong> You can only edit your own leave requests.
            </div>
        @else
            <form action="{{ route('leave.update', $leaveRequest) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Leave Type -->
                    <div>
                        <label for="leave_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Leave Type <span class="text-red-500">*</span>
                        </label>
                        <select name="leave_type" id="leave_type" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('leave_type') border-red-500 @enderror">
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
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status (hidden, keep current status) -->
                    <input type="hidden" name="status" value="{{ $leaveRequest->status }}">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Start Date -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Start Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="start_date" id="start_date" required
                               value="{{ old('start_date') ?? $leaveRequest->start_date }}"
                               min="{{ date('Y-m-d') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('start_date') border-red-500 @enderror">
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Date -->
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                            End Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="end_date" id="end_date" required
                               value="{{ old('end_date') ?? $leaveRequest->end_date }}"
                               min="{{ date('Y-m-d') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('end_date') border-red-500 @enderror">
                        @error('end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Reason -->
                <div>
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                        Reason for Leave <span class="text-red-500">*</span>
                    </label>
                    <textarea name="reason" id="reason" rows="4" required
                              placeholder="Please provide a detailed reason for your leave request..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('reason') border-red-500 @enderror">{{ old('reason') ?? $leaveRequest->reason }}</textarea>
                    @error('reason')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Supporting Document -->
                <div>
                    <label for="supporting_document" class="block text-sm font-medium text-gray-700 mb-2">
                        Supporting Document
                    </label>
                    
                    @if($leaveRequest->supporting_document)
                        <div class="mb-3 p-3 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600 mb-2">Current document:</p>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-file-alt text-blue-600"></i>
                                <a href="{{ asset('storage/' . $leaveRequest->supporting_document) }}" 
                                   target="_blank" 
                                   class="text-blue-600 hover:text-blue-800">
                                    View Current Document
                                </a>
                            </div>
                        </div>
                    @endif
                    
                    <input type="file" name="supporting_document" id="supporting_document"
                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('supporting_document') border-red-500 @enderror">
                    <p class="mt-1 text-sm text-gray-500">
                        Optional: Upload medical certificate, travel documents, or other supporting files (PDF, DOC, DOCX, JPG, PNG - max 5MB)
                        @if($leaveRequest->supporting_document)
                            <br><strong>Note:</strong> Uploading a new file will replace the current document.
                        @endif
                    </p>
                    @error('supporting_document')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Info Display -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-blue-800 mb-2">Current Request Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="text-blue-600 font-medium">Submitted:</span>
                            <p class="text-blue-800">{{ $leaveRequest->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div>
                            <span class="text-blue-600 font-medium">Status:</span>
                            <p class="text-blue-800">{!! $leaveRequest->getStatusBadge() !!}</p>
                        </div>
                        <div>
                            <span class="text-blue-600 font-medium">Current Days:</span>
                            <p class="text-blue-800">{{ $leaveRequest->total_days ?? $leaveRequest->calculateTotalDays() }} days</p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-6 border-t">
                    <a href="{{ route('leave.show', $leaveRequest) }}" 
                       class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition duration-200">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
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