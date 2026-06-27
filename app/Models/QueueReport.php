<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueueReport extends Model
{
    use HasFactory;

    protected $table = 'queue_reports';
    
<<<<<<< HEAD
    // ✅ SIRF EK BAAR fillable array - Duplicate nahi hona chahiye
=======
>>>>>>> ad8262ee5046eced21425c2cc6aa14495d6f4a02
    protected $fillable = [
        'token_number',
        'patient_name',
        'doctor_id',
        'doctor_name',
        'department',
        'status',
<<<<<<< HEAD
        'type',
=======
>>>>>>> ad8262ee5046eced21425c2cc6aa14495d6f4a02
        'waiting_time',
        'service_time',
        'completed_at',
        'date'
    ];
    
    protected $casts = [
        'completed_at' => 'datetime',
        'date' => 'date'
    ];
    
    // Status constants
    const STATUS_WAITING = 'waiting';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    
    // Get status badge
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'waiting' => '<span class="badge badge-warning">⏳ Waiting</span>',
            'in_progress' => '<span class="badge badge-info">🔄 In Progress</span>',
            'completed' => '<span class="badge badge-success">✅ Completed</span>',
            'cancelled' => '<span class="badge badge-danger">❌ Cancelled</span>',
        ];
        
        return $badges[$this->status] ?? '<span class="badge badge-secondary">Unknown</span>';
    }
}