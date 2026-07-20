<?php

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

    // ✅ Role Check Methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isStaff()
    {
        return $this->role === 'staff';
    }

    public function isUser()
    {
        return $this->role === 'user' || $this->role === 'patient';
    }

    public function isPatient()
    {
        return $this->role === 'patient';
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    // ✅ Get Avatar URL
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name ?? $this->full_name) . '&background=00d4ff&color=fff';
    }

    // ✅ Get Full Name
    public function getFullNameAttribute()
    {
        return $this->full_name ?? $this->name;
    }

    // ✅ Scope for active users
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // ✅ Scope for staff
    public function scopeStaff($query)
    {
        return $query->where('role', 'staff');
    }
}