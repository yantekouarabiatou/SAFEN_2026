<?php

namespace App\Notifications;

use App\Models\Artisan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ArtisanApproved extends Notification
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
            'type' => 'artisan_approved',
            'title' => 'Profil approuvé !',
            'message' => 'Votre profil "'.($this->artisan->business_name ?? $notifiable->name).'" est maintenant visible sur la plateforme.',
            'icon' => 'bi-patch-check-fill',
            'color' => '#008751',
            'url' => route('artisans.show', $this->artisan),
        ];
    }
}
