<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\User;

class UserAvatar extends Component
{
    public $user;
    public $size;
    public $showName;

    /**
     * Create a new component instance.
     */
    public function __construct(User $user, $size = 'md', $showName = false)
    {
        $this->user = $user;
        $this->size = $size;
        $this->showName = $showName;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.user-avatar');
    }

    /**
     * Get the size classes for the avatar
     */
    public function getSizeClasses()
    {
        return match($this->size) {
            'xs' => 'w-6 h-6 text-xs',
            'sm' => 'w-8 h-8 text-sm',
            'md' => 'w-10 h-10 text-base',
            'lg' => 'w-16 h-16 text-lg',
            'xl' => 'w-20 h-20 text-xl',
            '2xl' => 'w-24 h-24 text-2xl',
            default => 'w-10 h-10 text-base',
        };
    }
}
