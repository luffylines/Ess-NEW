<x-app-layout>
    <div class="container mx-auto px-4 py-6 max-w-4xl">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Overtime Request Details</h1>
            <a href="{{ route('overtime.index') }}" class="text-blue-600 hover:text-blue-800">
                ← Back to My Overtime
            </a>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <!-- Header with status -->
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-semibold">
                            Overtime Request #{{ $overtimeRequest->id }}
                        </h2>
                        <p class="text-sm">
                            Submitted on {{ $overtimeRequest->created_at->format('M d, Y \a\t H:i') }}
                        </p>
                    </div>
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $overtimeRequest->statusBadge }}">
                        {{ ucfirst($overtimeRequest->status) }}
                    </span>
                </div>
            </div>

            <!-- Request Details -->
            <div class="px-6 py-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Date and Time -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Date & Time</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm"><strong>Date:</strong> {{ $overtimeRequest->overtime_date->format('M d, Y') }}</p>
                            <p class="text-sm"><strong>Start Time:</strong> {{ $overtimeRequest->start_time->format('H:i') }}</p>
                            <p class="text-sm"><strong>End Time:</strong> {{ $overtimeRequest->end_time->format('H:i') }}</p>
                            <p class="text-sm"><strong>Total Hours:</strong> {{ $overtimeRequest->total_hours }} hours</p>
                        </div>
                    </div>

                    <!-- Status Information -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Status Information</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm"><strong>Status:</strong> {{ ucfirst($overtimeRequest->status) }}</p>
                            @if($overtimeRequest->approved_by)
                                <p class="text-sm"><strong>Reviewed by:</strong> {{ $overtimeRequest->approvedBy->name }}</p>
                                <p class="text-sm"><strong>Reviewed on:</strong> {{ $overtimeRequest->approved_at->format('M d, Y \a\t H:i') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Reason -->
                <div class="mt-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Reason for Overtime</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-900">{{ $overtimeRequest->reason }}</p>
                    </div>
                </div>

                <!-- Manager Remarks -->
                @if($overtimeRequest->manager_remarks)
                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Manager's Remarks</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-900">{{ $overtimeRequest->manager_remarks }}</p>
                        </div>
                    </div>
                @endif

                <!-- Supporting Document -->
                @if($overtimeRequest->supporting_document)
                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Supporting Document</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <a href="{{ \Illuminate\Support\Facades\Storage::url($overtimeRequest->supporting_document) }}" 
                               target="_blank"
                               class="inline-flex items-center text-blue-600 hover:text-blue-800">
                                <i class="fas fa-file mr-2"></i>
                                View Document
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Actions -->
            @if($overtimeRequest->status === 'pending')
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('overtime.edit', $overtimeRequest) }}" 
                           class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-200">
                            Edit Request
                        </a>
                        <form method="POST" action="{{ route('overtime.destroy', $overtimeRequest) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition duration-200"
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