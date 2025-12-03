<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkSchedule extends Model
{
    protected $fillable = [
        'employee_id',
        'assigned_by',
        'schedule_date',
        'shift_start',
        'shift_end',
        'shift_type',
        'store_id',
        'notes',
        'status',
        'acknowledged_at'
    ];

    protected $casts = [
        'schedule_date' => 'date',
        'acknowledged_at' => 'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    // Get working hours for the shift (automatically subtracts 1 hour break for shifts >= 6 hours)
    public function getWorkingHours(): float
    {
        $start = \Carbon\Carbon::parse($this->shift_start);
        $end = \Carbon\Carbon::parse($this->shift_end);
        
        // Handle overnight shifts
        if ($end->lessThan($start)) {
            $end->addDay();
        }
        
        // Calculate difference in hours correctly
        $totalHours = $start->diffInRealHours($end);
        
        // Automatically subtract 1 hour break for shifts 6 hours or longer
        if ($totalHours >= 6) {
            $totalHours -= 1;
        }
        
        return round($totalHours, 1);
    }

    // Check if schedule is for today
    public function isToday(): bool
    {
        return $this->schedule_date->isToday();
    }

    // Check if schedule is upcoming
    public function isUpcoming(): bool
    {
        return $this->schedule_date->isFuture();
    }

    // Check if schedule is past
    public function isPast(): bool
    {
        return $this->schedule_date->isPast();
    }

    // Acknowledge the schedule
    public function acknowledge(): void
    {
        $this->update([
            'status' => 'acknowledged',
            'acknowledged_at' => now()
        ]);
    }

    // Get status badge color
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'assigned' => 'bg-warning text-dark',
            'acknowledged' => 'bg-info text-white',
            'completed' => 'bg-success text-white',
            'missed' => 'bg-danger text-white',
            default => 'bg-secondary text-white',
        };
    }

    // Get shift type badge color
    public function getShiftTypeBadgeAttribute(): string
    {
        return match($this->shift_type) {
            'regular' => 'bg-primary text-white',
            'overtime' => 'bg-warning text-dark',
            'holiday' => 'bg-danger text-white',
            default => 'bg-secondary text-white',
        };
    }
}