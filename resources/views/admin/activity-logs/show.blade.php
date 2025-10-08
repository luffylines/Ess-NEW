@extends('layouts.app')

@section('content')
<div class="mx-auto p-3">
    <div class="bg-white rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.activity-logs.index') }}" 
                   class="text-primary mr-4">
                    <i class="fas fa-arrow-left"></i> Back to Activity Logs
                </a>
                <h2 class="h2 fw-bold text-dark">Activity Log Details</h2>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.activity-logs.export.pdf', $activityLog->id) }}" 
                   class="bg-danger text-white px-4 py-2 rounded d-flex align-items-center">
                    <i class="fas fa-file-pdf mr-2"></i>Export PDF
                </a>
                <a href="{{ route('admin.activity-logs.export.csv', $activityLog->id) }}" 
                   class="bg-success text-white px-4 py-2 rounded d-flex align-items-center">
                    <i class="fas fa-file-csv mr-2"></i>Export CSV
                </a>
            </div>
        </div>

        <div class="row gap-8">
            {{-- Basic Information --}}
            <div class="bg-light rounded p-4">
                <h3 class="h4 fw-semibold text-dark mb-3">Basic Information</h3>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span class="fw-medium text-muted">Log ID:</span>
                        <span class="">#{{ $activityLog->id }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <span class="fw-medium text-muted">Action Type:</span>
                        <div>{!! $activityLog->getActionTypeBadge() !!}</div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <span class="fw-medium text-muted">Date & Time:</span>
                        <span class="">{{ $activityLog->formatted_date }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <span class="fw-medium text-muted">Time Elapsed:</span>
                        <span class="">{{ $activityLog->time_elapsed }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <span class="fw-medium text-muted">IP Address:</span>
                        <span class="">{{ $activityLog->ip_address }}</span>
                    </div>
                </div>
            </div>

            {{-- User Information --}}
            <div class="bg-light rounded p-4">
                <h3 class="h4 fw-semibold text-dark mb-3">User Information</h3>
                
                @if($activityLog->user)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="fw-medium text-muted">Name:</span>
                            <span class="">{{ $activityLog->user->name }}</span>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <span class="fw-medium text-muted">Email:</span>
                            <span class="">{{ $activityLog->user->email }}</span>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <span class="fw-medium text-muted">Role:</span>
                            <span class="">{{ $activityLog->user->role ?? 'N/A' }}</span>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <span class="fw-medium text-muted">User ID:</span>
                            <span class="">#{{ $activityLog->user->id }}</span>
                        </div>
                    </div>
                @else
                    <div class="text-center">
                        <i class="mb-3"></i>
                        <p class="text-muted">User information not available</p>
                        <p class="small text-muted">This user may have been deleted</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Description --}}
        <div class="bg-light rounded p-4">
            <h3 class="h4 fw-semibold text-dark mb-3">Description</h3>
            <p class="text-secondary">{{ $activityLog->description }}</p>
        </div>

        {{-- Technical Details --}}
        <div class="bg-light rounded p-4">
            <h3 class="h4 fw-semibold text-dark mb-3">Technical Details</h3>
            
            <div class="row">
                <div>
                    <span class="fw-medium text-muted">User Agent:</span>
                    <p class="small text-secondary mt-1">
                        {{ $activityLog->user_agent ?? 'Not available' }}
                    </p>
                </div>
                
                <div>
                    <span class="fw-medium text-muted">Timestamp:</span>
                    <p class="small text-secondary mt-1">
                        {{ $activityLog->created_at->format('Y-m-d H:i:s T') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Additional Properties --}}
        @if($activityLog->properties && count($activityLog->properties) > 0)
            <div class="bg-light rounded p-4">
                <h3 class="h4 fw-semibold text-dark mb-3">Additional Properties</h3>
                
                <div class="bg-white rounded border p-3">
                    <pre class="small text-secondary">{{ json_encode($activityLog->properties, JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
        @endif

        {{-- Action Timeline --}}
        <div class="bg-light rounded p-4">
            <h3 class="h4 fw-semibold text-dark mb-3">Timeline</h3>
            
            <div class="position-relative">
                <div class="position-absolute bg-secondary"></div>
                
                <div class="position-relative d-flex align-items-start gap-3">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center">
                        <i class="text-white small"></i>
                    </div>
                    <div class="">
                        <p class="small fw-medium">Activity Logged</p>
                        <p class="small text-muted">{{ $activityLog->formatted_date }}</p>
                        <p class="small text-secondary mt-1">{{ $activityLog->description }}</p>
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