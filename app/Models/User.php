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

    // =============================================
    // ✅ NOTIFICATIONS RELATIONS
    // =============================================
    
    /**
     * Get all notifications for this user
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get unread notifications
     */
    public function unreadNotifications()
    {
        return $this->hasMany(Notification::class)->where('is_read', false);
    }

    /**
     * Get unread notification count
     */
    public function getUnreadNotificationCountAttribute()
    {
        return $this->unreadNotifications()->count();
    }

    // =============================================
    // ✅ ROLE CHECK METHODS
    // =============================================

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isStaff()
    {
        return $this->role === 'staff';
    }

    // ✅ "patient" ki jagah "user"
    public function isUser()
    {
        return $this->role === 'user';
    }

    // ✅ Alias for backward compatibility
    public function isPatient()
    {
        return $this->role === 'user';
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

    // =============================================
    // ✅ HELPERS
    // =============================================

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name ?? $this->full_name) . '&background=00d4ff&color=fff';
    }

    public function getNameAttribute()
    {
        return $this->full_name ?? $this->name;
    }
}