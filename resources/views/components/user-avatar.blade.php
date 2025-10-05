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

<div class="flex items-center space-x-3">
    @if($user->profile_photo_url)
        <img src="{{ $user->profile_photo_url }}" 
             alt="{{ $user->name }}" 
             class="{{ $sizeClasses }} rounded-full object-cover border-2 border-gray-300 dark:border-gray-600">
    @else
        <div class="{{ $sizeClasses }} rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center border-2 border-gray-300 dark:border-gray-600">
            <span class="font-semibold text-white">{{ $user->initials }}</span>
        </div>
    @endif
    
    @if($showName)
        <div>
            <p class="font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</p>
            @if($user->email)
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
            @endif
        </div>
    @endif
</div>