<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services';
    
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'image',
        'price',
        'duration',
        'status',
        'display_order',
        'department'
    ];
    
    protected $casts = [
        'price' => 'decimal:2',
        'duration' => 'integer',
        'display_order' => 'integer'
    ];
    
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($service) {
            $service->slug = Str::slug($service->name);
        });
        
        static::updating(function ($service) {
            if ($service->isDirty('name')) {
                $service->slug = Str::slug($service->name);
            }
        });
    }
    
    public function getFormattedPriceAttribute()
    {
        if (!$this->price) {
            return 'Contact for Quote';
        }
        return '$' . number_format($this->price, 2);
    }
    
    public function getFormattedDurationAttribute()
    {
        if (!$this->duration) {
            return 'Varies';
        }
        
        if ($this->duration >= 60) {
            $hours = floor($this->duration / 60);
            $minutes = $this->duration % 60;
            return $hours . 'h ' . ($minutes > 0 ? $minutes . 'm' : '');
        }
        
        return $this->duration . ' minutes';
    }
    
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('name');
    }
}