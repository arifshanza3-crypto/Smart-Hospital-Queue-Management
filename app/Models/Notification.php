<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'token_number',    // ✅ Add token_number
        'data',
        'read_at'
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $dates = ['read_at', 'created_at', 'updated_at'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    public function markAsRead(): void
    {
        $this->update(['read_at' => now()]);
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    public function getFormattedTimeAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getIconAttribute()
    {
        $data = is_string($this->data) ? json_decode($this->data, true) : $this->data;
        return $data['icon'] ?? 'fa-bell';
    }

    public function getUrlAttribute()
    {
        $data = is_string($this->data) ? json_decode($this->data, true) : $this->data;
        return $data['url'] ?? '#';
    }
}