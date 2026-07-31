<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $table = 'profiles';

    protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'address',
        'city',
        'country',
        'hostel',
        'location',
        'avatar',
        'bio',
        'employee_id',
        'department',
        'join_date',
        'status',
        'last_login'
    ];

    protected $casts = [
        'join_date' => 'date',
        'last_login' => 'datetime'
    ];

    // ✅ Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ✅ Get full name
    public function getFullNameAttribute($value)
    {
        return $value ?? $this->user->name;
    }

    // ✅ Get avatar URL
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name ?? 'User') . '&background=00d4ff&color=fff';
    }
}