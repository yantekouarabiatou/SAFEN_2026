<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventNotification extends Model
{
    protected $fillable = [
        'cultural_event_id',
        'user_id',
        'is_sent',
        'sent_at'
    ];

    protected $casts = [
        'is_sent' => 'boolean',
        'sent_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(CulturalEvent::class, 'cultural_event_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
