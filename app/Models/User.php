<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'full_name',
        'email',
        'employee_id',
        'department',
        'password',
        'phone',
        'role',
        'status',
        'avatar'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Role Constants
    const ROLE_ADMIN = 'admin';
    const ROLE_STAFF = 'staff';
    const ROLE_USER = 'user';

    // Status Constants
    const STATUS_ACTIVE = 'active';
    const STATUS_PENDING = 'pending';
    const STATUS_INACTIVE = 'inactive';

    // Relationship with Profile
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    // Get or create profile
    public function getProfile()
    {
        if (!$this->profile) {
            $profile = $this->profile()->create([
                'full_name' => $this->full_name ?? $this->name,
                'join_date' => now(),
                'status' => 'active'
            ]);
            $this->refresh();
        }
        return $this->profile;
    }

    // Role Check Methods
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isStaff()
    {
        return $this->role === self::ROLE_STAFF;
    }

    public function isUser()
    {
        return $this->role === self::ROLE_USER;
    }

    // Get Role Badge Class
    public function getRoleBadgeClass()
    {
        return [
            self::ROLE_ADMIN => 'badge-admin',
            self::ROLE_STAFF => 'badge-staff',
            self::ROLE_USER => 'badge-user',
        ][$this->role] ?? 'badge-user';
    }

    // Get Status Badge Class
    public function getStatusBadgeClass()
    {
        return [
            self::STATUS_ACTIVE => 'status-active',
            self::STATUS_PENDING => 'status-pending',
            self::STATUS_INACTIVE => 'status-inactive',
        ][$this->status] ?? 'status-pending';
    }

    // Get Avatar URL
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        $displayName = $this->full_name ?? $this->name ?? 'User';
        return 'https://ui-avatars.com/api/?name=' . urlencode($displayName) . '&background=6366f1&color=fff&size=128';
    }
}