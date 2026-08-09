<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'token_number',
        'patient_id',
        'patient_name',
        'phone',
        'email',
        'type',
        'status',
        'estimated_time',
        'position',
        'called_at',
        'completed_at'
        // ❌ department removed from fillable
    ];

    protected $casts = [
        'patient_id' => 'string',
        'position' => 'integer',
        'estimated_time' => 'integer',
    ];
    
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}