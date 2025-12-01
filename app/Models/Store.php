<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
        'name', 'lat', 'lng', 'radius_meters', 'active'
    ];

    protected $casts = [
        'lat' => 'float',
        'lng' => 'float',
        'radius_meters' => 'integer',
        'active' => 'boolean',
    ];
}
