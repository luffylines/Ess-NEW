<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'date', 'day_type', 'time_in', 'time_out', 'status', 'remarks', 'created_by', 'approved_by', 'approved_at', 'rejection_reason'];

    // Use $casts instead of $dates for Laravel 7+
    protected $casts = [
        'date' => 'date',
        'time_in' => 'datetime',
        'time_out' => 'datetime',
        'approved_at' => 'datetime',
    ];
    
    // In Attendance model
    public function createdByUser() 
    {   
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedByUser() 
    {   
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
