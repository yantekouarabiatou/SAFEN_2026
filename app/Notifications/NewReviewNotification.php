<?php

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewReviewNotification extends Notification
{
    use Queueable;

    public function __construct(public Review $review) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $stars = str_repeat('★', $this->review->rating).str_repeat('☆', 5 - $this->review->rating);

        return [
            'type' => 'new_review',
            'title' => 'Nouvel avis reçu',
            'message' => $stars.' — '.\Illuminate\Support\Str::limit($this->review->comment ?? 'Avis sans commentaire', 60),
            'icon' => 'bi-star-fill',
            'color' => '#f59e0b',
            'url' => route('artisans.show', $this->review->reviewable_id).'#reviews',
        ];
    }
}
