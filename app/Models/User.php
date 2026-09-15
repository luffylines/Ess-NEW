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

    protected $hidden = [
        'password',
        'remember_token',
    ];

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
     * Resolve the user's profile photo from Cloudinary, durable DB storage,
     * or older local-storage records.
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        if (!$this->profile_photo) {
            return asset('img/default-avatar.png');
        }

        $photo = trim((string) $this->profile_photo);

        if ($photo === 'database') {
            $stored = UserProfilePhoto::where('user_id', $this->id)->first();

            if ($stored && filled($stored->image_data)) {
                return 'data:' . ($stored->mime_type ?: 'image/jpeg') . ';base64,' . $stored->image_data;
            }

            return asset('img/default-avatar.png');
        }

        if (str_starts_with($photo, 'http://') || str_starts_with($photo, 'https://') || str_starts_with($photo, 'data:')) {
            return $photo;
        }

        if (str_starts_with($photo, '/storage/') || str_starts_with($photo, 'storage/')) {
            return asset(ltrim($photo, '/'));
        }

        return asset('storage/' . ltrim($photo, '/'));
    }

    public function getInitialsAttribute(): string
    {
        $words = preg_split('/\s+/', trim((string) $this->name)) ?: [];
        $initials = '';

        foreach ($words as $word) {
            if ($word !== '') {
                $initials .= strtoupper(substr($word, 0, 1));
            }
        }

        return substr($initials ?: 'U', 0, 2);
    }

    public static function generateEmployeeId($role)
    {
        $prefix = match($role) {
            'employee' => 'emp',
            'hr' => 'hr',
            'manager' => 'm',
            'admin' => 'admin',
            default => 'emp'
        };

        $lastEmployee = static::where('employee_id', 'like', $prefix . '%')
            ->orderByRaw('CAST(SUBSTRING(employee_id, ' . (strlen($prefix) + 1) . ') AS UNSIGNED) DESC')
            ->first();

        if ($lastEmployee) {
            $lastNumber = (int) substr($lastEmployee->employee_id, strlen($prefix));
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return $prefix . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
    }
}
