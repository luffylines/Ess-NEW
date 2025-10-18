<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action_type',
        'description',
        'ip_address',
        'user_agent',
        'properties',
    ];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Get time elapsed in human readable format
    public function getTimeElapsedAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    // Get formatted action date
    public function getFormattedDateAttribute(): string
    {
        return $this->created_at->format('d M Y - h:i A');
    }

    // Get action type badge HTML
    public function getActionTypeBadge(): string
    {
        $colors = match($this->action_type) {
            'login' => 'bg-green-100 text-green-800',
            'logout' => 'bg-yellow-100 text-yellow-800',
            'create' => 'bg-blue-100 text-blue-800',
            'update' => 'bg-orange-100 text-orange-800',
            'delete' => 'bg-red-100 text-red-800',
            'view' => 'bg-purple-100 text-purple-800',
            'export' => 'bg-indigo-100 text-indigo-800',
            'access_denied' => 'bg-red-100 text-red-800',
            'login_error' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };

        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $colors . '">' . 
               ucfirst($this->action_type) . '</span>';
    }

    // Scope for filtering by date range
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    // Scope for filtering by action type
    public function scopeByActionType($query, $actionType)
    {
        return $query->where('action_type', $actionType);
    }

    // Scope for filtering by user
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}