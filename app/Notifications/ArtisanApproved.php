<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ArtisanApproved extends Notification
{
    use Queueable;

    protected $artisan;

    public function __construct($artisan)
    {
        $this->artisan = $artisan;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Votre profil artisan a été approuvé !')
            ->line('Félicitations ! Votre profil "' . $this->artisan->business_name . '" a été approuvé.')
            ->action('Voir mon profil', route('artisans.show', $this->artisan))
            ->line('Merci de faire partie de AFRI-HERITAGE Bénin !');
    }
}