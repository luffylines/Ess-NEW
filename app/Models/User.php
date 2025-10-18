<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Attendance;
use App\Traits\LogsActivity;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'gender',
        'address',
        'profile_photo',
        'employee_id',
        'role',
        'google_id',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get the user's profile photo URL
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo && file_exists(public_path('storage/profile_photos/' . $this->profile_photo))) {
            return asset('storage/profile_photos/' . $this->profile_photo);
        }
        return null;
    }

    /**
     * Get the user's initials for avatar fallback
     */
    public function getInitialsAttribute()
    {
        $words = explode(' ', $this->name);
        $initials = '';
        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        return substr($initials, 0, 2);
    }

    /**
     * Generate unique employee ID based on role
     */
    public static function generateEmployeeId($role)
    {
        $prefix = match($role) {
            'employee' => 'emp',
            'hr' => 'hr',
            'manager' => 'm',
            'admin' => 'admin',
            default => 'emp'
        };

        // Get the highest existing number for this role
        $lastEmployee = static::where('employee_id', 'like', $prefix . '%')
            ->orderByRaw('CAST(SUBSTRING(employee_id, ' . (strlen($prefix) + 1) . ') AS UNSIGNED) DESC')
            ->first();

        if ($lastEmployee) {
            // Extract number from existing ID and increment
            $lastNumber = (int) substr($lastEmployee->employee_id, strlen($prefix));
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        // Format with leading zeros (2 digits)
        return $prefix . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
    }
}
