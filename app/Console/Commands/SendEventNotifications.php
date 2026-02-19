<?php

namespace App\Console\Commands;

use App\Models\CulturalEvent;
use App\Models\User;
use App\Notifications\UpcomingEventNotification;
use Illuminate\Console\Command;

class SendEventNotifications extends Command
{
    protected $signature = 'events:notify';
    protected $description = 'Send notifications for upcoming cultural events';

    public function handle()
    {
        $this->info('Checking for events needing notifications...');

        // Événements dans 7 jours
        $eventsIn7Days = CulturalEvent::needingNotification(7)->get();
        $this->notifyUsers($eventsIn7Days, 7);

        // Événements dans 3 jours
        $eventsIn3Days = CulturalEvent::needingNotification(3)->get();
        $this->notifyUsers($eventsIn3Days, 3);

        // Événements demain
        $eventsTomorrow = CulturalEvent::needingNotification(1)->get();
        $this->notifyUsers($eventsTomorrow, 1);

        $this->info('Event notifications sent successfully!');
    }

    private function notifyUsers($events, $daysAhead)
    {
        foreach ($events as $event) {
            // Notifier les abonnés directs de l'événement
            $subscribers = $event->subscribers()
                ->wherePivot('is_sent', false)
                ->get();

            foreach ($subscribers as $user) {
                $user->notify(new UpcomingEventNotification($event, $daysAhead));

                $event->notifications()
                    ->where('user_id', $user->id)
                    ->update([
                        'is_sent' => true,
                        'sent_at' => now()
                    ]);
            }

            // Notifier les utilisateurs avec préférences générales
            $generalSubscribers = User::whereHas('eventSubscriptions', function($query) use ($event) {
                $query->where('is_active', true)
                      ->where(function($q) use ($event) {
                          $q->whereNull('event_type')
                            ->orWhere('event_type', $event->type);
                      })
                      ->where(function($q) use ($event) {
                          $q->whereNull('region')
                            ->orWhere('region', $event->region);
                      });
            })->get();

            foreach ($generalSubscribers as $user) {
                if (!$event->subscribers->contains($user->id)) {
                    $user->notify(new UpcomingEventNotification($event, $daysAhead));
                }
            }

            $this->info("Notified users for: {$event->name}");
        }
    }
}
