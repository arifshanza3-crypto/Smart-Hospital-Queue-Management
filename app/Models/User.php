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

<<<<<<< HEAD
    // ✅ Role Check Methods
=======
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

>>>>>>> 6b3e1247f30e0d61f40e6ce48d469327b3ab9296
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isStaff()
    {
        return $this->role === 'staff';
    }

<<<<<<< HEAD
    public function isUser()
    {
        return $this->role === 'user' || $this->role === 'patient';
    }

=======
    // ✅ "patient" ki jagah "user"
    public function isUser()
    {
        return $this->role === 'user';
    }

    // ✅ Alias for backward compatibility
>>>>>>> 6b3e1247f30e0d61f40e6ce48d469327b3ab9296
    public function isPatient()
    {
        return $this->role === 'user';
    }

<<<<<<< HEAD
    public function isActive()
    {
        return $this->status === 'active';
    }

=======
>>>>>>> 6b3e1247f30e0d61f40e6ce48d469327b3ab9296
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

<<<<<<< HEAD
    // ✅ Get Avatar URL
=======
    // =============================================
    // ✅ HELPERS
    // =============================================

>>>>>>> 6b3e1247f30e0d61f40e6ce48d469327b3ab9296
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name ?? $this->full_name) . '&background=00d4ff&color=fff';
    }

<<<<<<< HEAD
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
=======
    public function getNameAttribute()
    {
        return $this->full_name ?? $this->name;
    }
>>>>>>> 6b3e1247f30e0d61f40e6ce48d469327b3ab9296
}