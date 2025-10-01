@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <a href="{{ route('admin.activity-logs.index') }}" 
                   class="text-blue-600 hover:text-blue-800 mr-4">
                    <i class="fas fa-arrow-left"></i> Back to Activity Logs
                </a>
                <h2 class="text-2xl font-bold text-gray-800">Activity Log Details</h2>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.activity-logs.export.pdf', $activityLog->id) }}" 
                   class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                    <i class="fas fa-file-pdf mr-2"></i>Export PDF
                </a>
                <a href="{{ route('admin.activity-logs.export.csv', $activityLog->id) }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                    <i class="fas fa-file-csv mr-2"></i>Export CSV
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- Basic Information --}}
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Basic Information</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-600">Log ID:</span>
                        <span class="text-gray-900">#{{ $activityLog->id }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-600">Action Type:</span>
                        <div>{!! $activityLog->getActionTypeBadge() !!}</div>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-600">Date & Time:</span>
                        <span class="text-gray-900">{{ $activityLog->formatted_date }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-600">Time Elapsed:</span>
                        <span class="text-gray-900">{{ $activityLog->time_elapsed }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-600">IP Address:</span>
                        <span class="text-gray-900 font-mono">{{ $activityLog->ip_address }}</span>
                    </div>
                </div>
            </div>

            {{-- User Information --}}
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">User Information</h3>
                
                @if($activityLog->user)
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600">Name:</span>
                            <span class="text-gray-900">{{ $activityLog->user->name }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600">Email:</span>
                            <span class="text-gray-900">{{ $activityLog->user->email }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600">Role:</span>
                            <span class="text-gray-900 capitalize">{{ $activityLog->user->role ?? 'N/A' }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600">User ID:</span>
                            <span class="text-gray-900">#{{ $activityLog->user->id }}</span>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-user-slash text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">User information not available</p>
                        <p class="text-sm text-gray-400">This user may have been deleted</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Description --}}
        <div class="mt-8 bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Description</h3>
            <p class="text-gray-700 leading-relaxed">{{ $activityLog->description }}</p>
        </div>

        {{-- Technical Details --}}
        <div class="mt-8 bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Technical Details</h3>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <span class="font-medium text-gray-600">User Agent:</span>
                    <p class="text-sm text-gray-700 mt-1 break-all">
                        {{ $activityLog->user_agent ?? 'Not available' }}
                    </p>
                </div>
                
                <div>
                    <span class="font-medium text-gray-600">Timestamp:</span>
                    <p class="text-sm text-gray-700 mt-1">
                        {{ $activityLog->created_at->format('Y-m-d H:i:s T') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Additional Properties --}}
        @if($activityLog->properties && count($activityLog->properties) > 0)
            <div class="mt-8 bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Additional Properties</h3>
                
                <div class="bg-white rounded border p-4">
                    <pre class="text-sm text-gray-700 overflow-x-auto">{{ json_encode($activityLog->properties, JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
        @endif

        {{-- Action Timeline --}}
        <div class="mt-8 bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Timeline</h3>
            
            <div class="relative">
                <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-300"></div>
                
                <div class="relative flex items-start space-x-4">
                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-white text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Activity Logged</p>
                        <p class="text-sm text-gray-500">{{ $activityLog->formatted_date }}</p>
                        <p class="text-sm text-gray-700 mt-1">{{ $activityLog->description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush
@endsection