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
        'department',
        'status',
        'full_name',
        'email',
        'type',
        'est_time',
        'position',
        'called_at',
        'completed_at'
    ];
    
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}