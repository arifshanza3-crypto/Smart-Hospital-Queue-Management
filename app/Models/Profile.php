<?php
// app/Models/Profile.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
        'designation',
        'join_date',
        'status',
        'last_login'
    ];

    protected $casts = [
        'join_date' => 'date',
        'last_login' => 'datetime'
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Get full name with fallback
    public function getFullNameAttribute($value)
    {
        return $value ?? ($this->user ? $this->user->name : 'User');
    }

    // Get avatar URL
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar && Storage::disk('public')->exists($this->avatar)) {
            return asset('storage/' . $this->avatar);
        }
        
        $name = $this->full_name ?? 'User';
        return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=6366f1&color=fff&size=128';
    }

    // Get status badge class
    public function getStatusBadgeClass()
    {
        return [
            'active' => 'status-active',
            'pending' => 'status-pending',
            'inactive' => 'status-inactive',
        ][$this->status] ?? 'status-pending';
    }

    // Get status icon
    public function getStatusIcon()
    {
        return [
            'active' => 'fa-check-circle',
            'pending' => 'fa-clock',
            'inactive' => 'fa-times-circle',
        ][$this->status] ?? 'fa-circle';
    }

    // Scope for active profiles
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}