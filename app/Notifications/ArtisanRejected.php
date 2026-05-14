<?php

namespace App\Notifications;

use App\Models\Artisan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ArtisanRejected extends Notification
{
    use Queueable;

    public function __construct(public Artisan $artisan) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'artisan_rejected',
            'title' => 'Profil non approuvé',
            'message' => $this->artisan->rejection_reason
                ? 'Raison : '.\Illuminate\Support\Str::limit($this->artisan->rejection_reason, 80)
                : 'Votre profil n\'a pas été approuvé. Contactez le support.',
            'icon' => 'bi-x-circle-fill',
            'color' => '#E8112D',
            'url' => route('artisans.edit', $this->artisan),
        ];
    }
}
