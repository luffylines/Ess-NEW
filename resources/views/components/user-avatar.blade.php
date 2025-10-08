@props(['user', 'size' => 'md', 'showName' => false])

@php
    $sizeClasses = match($size) {
        'xs' => 'w-6 h-6 text-xs',
        'sm' => 'w-8 h-8 text-sm',
        'md' => 'w-10 h-10 text-base',
        'lg' => 'w-16 h-16 text-lg',
        'xl' => 'w-20 h-20 text-xl',
        '2xl' => 'w-24 h-24 text-2xl',
        default => 'w-10 h-10 text-base',
    };
@endphp

<div class="d-flex align-items-center">
    @if($user->profile_photo_url)
        <img src="{{ $user->profile_photo_url }}" 
             alt="{{ $user->name }}" 
             class="rounded-circle border">
    @else
        <div class="rounded-circle d-flex align-items-center justify-content-center border">
            <span class="fw-semibold text-white">{{ $user->initials }}</span>
        </div>
    @endif
    
    @if($showName)
        <div>
            <p class="fw-medium">{{ $user->name }}</p>
            @if($user->email)
                <p class="small text-muted">{{ $user->email }}</p>
            @endif
        </div>
    @endif
</div>