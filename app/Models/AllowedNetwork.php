<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllowedNetwork extends Model
{
    protected $fillable = [
        'name', 'ip_ranges', 'active'
    ];

    protected $casts = [
        'ip_ranges' => 'array',
        'active' => 'boolean',
    ];
}