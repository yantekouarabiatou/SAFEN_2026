<?php

namespace App\Notifications;

use App\Models\CulturalEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UpcomingEventNotification extends Notification
{
    use Queueable;

    public function __construct(
        public CulturalEvent $event,
        public int $daysUntil
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $message = match($this->daysUntil) {
            1 => 'C\'est demain !',
            default => "Dans {$this->daysUntil} jours"
        };

        return (new MailMessage)
            ->subject("🎭 {$this->event->name} - {$message}")
            ->greeting("Bonjour {$notifiable->name} !")
            ->line("L'événement culturel **{$this->event->name}** approche !")
            ->line("📅 Date : {$this->event->formatted_date}")
            ->line("📍 Lieu : {$this->event->location}")
            ->line($this->event->description)
            ->action('Voir les détails', route('events.show', $this->event))
            ->line('Merci de faire vivre la culture béninoise avec AFRI-HERITAGE !');
    }

    public function toArray($notifiable): array
    {
        return [
            'event_id' => $this->event->id,
            'event_name' => $this->event->name,
            'event_date' => $this->event->event_date,
            'days_until' => $this->daysUntil,
            'location' => $this->event->location,
            'type' => $this->event->type,
        ];
    }
}
