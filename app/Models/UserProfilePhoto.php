<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfilePhoto extends Model
{
    protected $fillable = [
        'user_id',
        'mime_type',
        'image_data',
        'size_bytes',
    ];
}
