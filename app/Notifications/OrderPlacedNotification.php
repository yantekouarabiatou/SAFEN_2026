<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPlacedNotification extends Notification
{
    use Queueable;

    public function __construct($order, $recipientType)
    {
        $this->order = $order;
        $this->recipientType = $recipientType; // 'customer', 'admin', 'artisan'
    }

    public function via($notifiable)
    {
        return ['mail']; // tu peux ajouter 'database', 'sms' etc.
    }

    public function toMail($notifiable)
    {
        $subject = $this->recipientType === 'client'
            ? 'Confirmation de votre commande #' . $this->order->order_number
            : 'Nouvelle commande #' . $this->order->order_number;

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Bonjour ' . ($this->recipientType === 'client' ? $this->order->guest_name : 'Équipe') . ',')
            ->line('Une nouvelle commande a été passée sur AFRI-HERITAGE.')
            ->line('Numéro de commande : #' . $this->order->order_number)
            ->line('Montant total : ' . number_format($this->order->total_amount, 0, ',', ' ') . ' FCFA')
            ->line('Méthode de paiement : ' . ucfirst($this->order->payment_method))
            ->action('Voir la commande', route('orders.show', $this->order)) // à adapter selon tes routes
            ->line('Merci de votre confiance !');
    }

    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'type' => $this->recipientType,
        ];
    }
}
