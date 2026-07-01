<?php
// app/Models/Token.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'token_number',
        'patient_id',
        'department',
        'status',
        'patient_name',    // ✅ YEH ADD KARO (pehle missing tha)
        'phone',
        'email',
        'estimated_time',   // ✅ YEH BHI ADD KARO
        'position',
        'called_at',
        'completed_at'
    ];
    
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}