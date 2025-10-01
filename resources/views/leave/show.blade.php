@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <a href="{{ route('leave.index') }}" class="text-blue-600 hover:text-blue-800 mr-4">
                    <i class="fas fa-arrow-left"></i> Back to Leave Requests
                </a>
                <h2 class="text-2xl font-bold text-gray-800">Leave Request #{{ $leaveRequest->id }}</h2>
            </div>
            <div class="flex space-x-2">
                {!! $leaveRequest->getStatusBadge() !!}
                @if($leaveRequest->status === 'pending' && $leaveRequest->user_id === auth()->id())
                    <a href="{{ route('leave.edit', $leaveRequest) }}" 
                       class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Request Details -->
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Request Details</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600">Employee:</span>
                            <span class="text-gray-900">{{ $leaveRequest->user->name }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600">Leave Type:</span>
                            <span class="text-gray-900">{{ ucfirst($leaveRequest->leave_type) }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600">Start Date:</span>
                            <span class="text-gray-900">{{ \Carbon\Carbon::parse($leaveRequest->start_date)->format('M d, Y (l)') }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600">End Date:</span>
                            <span class="text-gray-900">{{ \Carbon\Carbon::parse($leaveRequest->end_date)->format('M d, Y (l)') }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600">Total Days:</span>
                            <span class="text-gray-900 font-semibold">{{ $leaveRequest->total_days ?? $leaveRequest->calculateTotalDays() }} days</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600">Submitted:</span>
                            <span class="text-gray-900">{{ $leaveRequest->created_at->format('M d, Y h:i A') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Reason -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Reason for Leave</h3>
                    <p class="text-gray-700 leading-relaxed">{{ $leaveRequest->reason }}</p>
                </div>

                <!-- Supporting Document -->
                @if($leaveRequest->supporting_document)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Supporting Document</h3>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-file-alt text-blue-600 text-2xl"></i>
                            <div>
                                <a href="{{ asset('storage/' . $leaveRequest->supporting_document) }}" 
                                   target="_blank" 
                                   class="text-blue-600 hover:text-blue-800 font-medium">
                                    View Document
                                </a>
                                <p class="text-sm text-gray-500">Click to open in new tab</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Approval Status -->
            <div class="space-y-6">
                @if($leaveRequest->status !== 'pending')
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Approval Information</h3>
                        
                        <div class="space-y-3">
                            @if($leaveRequest->approved_by)
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-600">Reviewed By:</span>
                                    <span class="text-gray-900">{{ $leaveRequest->approver->name ?? 'HR Department' }}</span>
                                </div>
                            @endif
                            
                            @if($leaveRequest->approved_at)
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-600">Review Date:</span>
                                    <span class="text-gray-900">{{ \Carbon\Carbon::parse($leaveRequest->approved_at)->format('M d, Y h:i A') }}</span>
                                </div>
                            @endif
                            
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-600">Status:</span>
                                {!! $leaveRequest->getStatusBadge() !!}
                            </div>
                        </div>
                    </div>

                    @if($leaveRequest->manager_remarks)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Manager Remarks</h3>
                            <p class="text-gray-700 leading-relaxed">{{ $leaveRequest->manager_remarks }}</p>
                        </div>
                    @endif
                @else
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="fas fa-clock text-yellow-600 text-2xl mr-3"></i>
                            <div>
                                <h3 class="text-lg font-semibold text-yellow-800">Pending Review</h3>
                                <p class="text-yellow-700">Your leave request is waiting for manager approval.</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Timeline -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Request Timeline</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-3 h-3 bg-blue-600 rounded-full mt-1"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Request Submitted</p>
                                <p class="text-xs text-gray-500">{{ $leaveRequest->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        
                        @if($leaveRequest->status !== 'pending')
                            <div class="flex items-start space-x-3">
                                <div class="w-3 h-3 {{ $leaveRequest->status === 'approved' ? 'bg-green-600' : 'bg-red-600' }} rounded-full mt-1"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        Request {{ ucfirst($leaveRequest->status) }}
                                    </p>
                                    @if($leaveRequest->approved_at)
                                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($leaveRequest->approved_at)->format('M d, Y h:i A') }}</p>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="flex items-start space-x-3">
                                <div class="w-3 h-3 bg-gray-300 rounded-full mt-1"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Awaiting Approval</p>
                                    <p class="text-xs text-gray-400">Pending manager review</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        @if($leaveRequest->status === 'pending' && $leaveRequest->user_id === auth()->id())
            <div class="flex justify-end space-x-4 pt-6 mt-6 border-t">
                <form action="{{ route('leave.destroy', $leaveRequest) }}" method="POST" 
                      onsubmit="return confirm('Are you sure you want to delete this leave request?')" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-200">
                        <i class="fas fa-trash mr-2"></i>Delete Request
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection