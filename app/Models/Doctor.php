<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Doctor extends Model
{
    use HasFactory;

    protected $table = 'doctors';
    
    protected $fillable = [
        'name',
        'specialization',
        'qualification',
        'email',
        'phone',
        'status',
        // ❌ 'photo' remove kar diya
        'profile_image',
        'shift',
        'experience',
        'fee',
        'display_order',
        'slug'
    ];

    // ✅ Auto-generate slug from name
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($doctor) {
            $doctor->slug = Str::slug($doctor->name);
        });
        
        static::updating(function ($doctor) {
            if ($doctor->isDirty('name')) {
                $doctor->slug = Str::slug($doctor->name);
            }
        });
    }

    // ✅ Get profile image URL
    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_image) {
            return asset('storage/' . $this->profile_image);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=00d4ff&color=fff&size=200';
    }

    // ✅ Get full name with title
    public function getFullNameAttribute()
    {
        return 'Dr. ' . $this->name;
    }

    // ✅ Get status badge class
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'active' => 'success',
            'on_duty' => 'primary',
            'inactive' => 'danger'
        ];
        return $badges[$this->status] ?? 'secondary';
    }

    // ✅ Get status text
    public function getStatusTextAttribute()
    {
        $texts = [
            'active' => 'Active',
            'on_duty' => 'On Duty',
            'inactive' => 'Inactive'
        ];
        return $texts[$this->status] ?? ucfirst($this->status);
    }

    // ✅ Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')->orWhere('status', 'on_duty');
    }

    public function scopeBySpecialization($query, $specialization)
    {
        return $query->where('specialization', $specialization);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where('name', 'LIKE', "%{$term}%")
                     ->orWhere('specialization', 'LIKE', "%{$term}%")
                     ->orWhere('qualification', 'LIKE', "%{$term}%");
    }
}