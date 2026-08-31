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
        'completed_at',
        'created_at'
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

    /**
     * ✅ Dynamic Estimated Time Calculate Karne Ke Liye
     * Queue mein aage kitne patients hain us ke hisaab se time calculate karein
     */
    public function getDynamicEstimatedTime()
    {
        // Agar token complete ya serving hai toh time 0
        if (in_array($this->status, ['completed', 'serving', 'cancelled', 'missed'])) {
            return 0;
        }

        // Waiting queue mein aage kitne patients hain
        $aheadCount = Token::where('status', 'waiting')
            ->where('position', '<', $this->position)
            ->count();

        // Har patient 15 minutes
        $timePerPatient = 15;
        
        // Total estimated time = aage walo ka time
        $totalTime = $aheadCount * $timePerPatient;

        return $totalTime;
    }

    /**
     * ✅ Dynamic Waiting Time Calculate Karne Ke Liye
     */
    public function getDynamicWaitingTime()
    {
        if (in_array($this->status, ['completed', 'serving', 'cancelled', 'missed'])) {
            return 0;
        }

        $aheadCount = Token::where('status', 'waiting')
            ->where('position', '<', $this->position)
            ->count();

        return $aheadCount * 15;
    }

    /**
     * ✅ Dynamic Position Calculate Karne Ke Liye
     */
    public function getDynamicPosition()
    {
        if (in_array($this->status, ['completed', 'serving', 'cancelled', 'missed'])) {
            return 0;
        }

        $position = Token::where('status', 'waiting')
            ->where('position', '<=', $this->position)
            ->count();

        return $position;
    }
}