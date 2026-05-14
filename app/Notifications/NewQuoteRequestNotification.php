<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewQuoteRequestNotification extends Notification
{
    use Queueable;

    public function __construct(public $quote) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'new_quote',
            'title' => 'Demande de devis reçue',
            'message' => \Illuminate\Support\Str::limit($this->quote->description ?? 'Nouvelle demande', 70),
            'icon' => 'bi-chat-left-text-fill',
            'color' => '#E8112D',
            'url' => route('dashboard.artisan'),
        ];
    }
}
