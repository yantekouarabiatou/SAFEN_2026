<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification
{
    use Queueable;

    public function __construct(public $order) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'new_order',
            'title' => 'Nouvelle commande',
            'message' => 'Commande #'.$this->order->id.' — '.number_format($this->order->total_amount ?? 0).' FCFA',
            'icon' => 'bi-cart-check-fill',
            'color' => '#3b82f6',
            'url' => route('orders.show', $this->order->id),
        ];
    }
}
