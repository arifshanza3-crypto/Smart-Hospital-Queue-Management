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
        'department',
        'type',
        'status',
        'estimated_time',
        'position',
        'called_at',
        'completed_at'
    ];

    protected $casts = [
        'patient_id' => 'string',  // ✅ Auto convert to string
        'position' => 'integer',
        'estimated_time' => 'integer',
    ];
    
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}