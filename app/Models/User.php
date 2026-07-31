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

    // ✅ Relationship with Profile
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    // ✅ Get or create profile
    public function getProfile()
    {
        if (!$this->profile) {
            $this->profile()->create([
                'full_name' => $this->name,
                'join_date' => now(),
                'status' => 'active'
            ]);
            $this->refresh();
        }
        return $this->profile;
    }

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

    // ✅ Get Avatar URL
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        $displayName = $this->full_name ?? $this->name ?? 'User';
        return 'https://ui-avatars.com/api/?name=' . urlencode($displayName) . '&background=00d4ff&color=fff';
    }
}