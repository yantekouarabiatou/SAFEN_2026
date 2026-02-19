<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CulturalEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'event_date',
        'event_time',
        'location',
        'region',
        'ethnic_origin',
        'traditions',
        'is_recurring',
        'recurrence_pattern',
        'notification_days_before',
        'image_url',
        'is_active',
        'views'
    ];

    protected $casts = [
        'event_date' => 'date',
        'is_recurring' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function notifications()
    {
        return $this->hasMany(EventNotification::class);
    }

    public function subscribers()
    {
        return $this->belongsToMany(User::class, 'event_notifications')
                    ->withPivot('is_sent', 'sent_at')
                    ->withTimestamps();
    }

    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now())
                    ->where('is_active', true)
                    ->orderBy('event_date', 'asc');
    }

    public function scopeNeedingNotification($query, $daysAhead = 7)
    {
        $notificationDate = now()->addDays($daysAhead)->toDateString();

        return $query->where('is_active', true)
                    ->where('event_date', $notificationDate)
                    ->whereDoesntHave('notifications', function($q) use ($daysAhead) {
                        $q->where('is_sent', true)
                          ->where('sent_at', '>=', now()->subDays($daysAhead));
                    });
    }

    public function getFormattedDateAttribute()
    {
        return $this->event_date->locale('fr')->isoFormat('D MMMM YYYY');
    }

    public function getDaysUntilEventAttribute()
    {
        return now()->diffInDays($this->event_date, false);
    }
}
