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

    public function isUser()
    {
        return $this->role === 'user';
    }

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
        // ✅ FIXED: Use full_name or name directly, not through accessor
        $displayName = $this->full_name ?? $this->name ?? 'User';
        return 'https://ui-avatars.com/api/?name=' . urlencode($displayName) . '&background=00d4ff&color=fff';
    }

    // ✅ REMOVED the problematic getNameAttribute() that was causing the infinite loop
    // The name field already exists in the database, so we don't need an accessor
}