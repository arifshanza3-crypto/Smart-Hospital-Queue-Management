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
        'photo',
        'slug'  // ✅ Add slug to fillable
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
}