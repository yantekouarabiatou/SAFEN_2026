<?php

namespace App\Http\Controllers;

use App\Models\CulturalEvent;
use App\Models\UserEventSubscription;
use Illuminate\Http\Request;

class CulturalEventController extends Controller
{
    public function index()
    {
        $upcomingEvents = CulturalEvent::upcoming()
            ->with('notifications')
            ->paginate(12);

        $eventTypes = CulturalEvent::distinct('type')->pluck('type');
        $regions = CulturalEvent::distinct('region')->whereNotNull('region')->pluck('region');

        return view('events.index', compact('upcomingEvents', 'eventTypes', 'regions'));
    }

    public function show(CulturalEvent $event)
    {
        $event->increment('views');

        $relatedEvents = CulturalEvent::where('id', '!=', $event->id)
            ->where(function($query) use ($event) {
                $query->where('type', $event->type)
                      ->orWhere('region', $event->region);
            })
            ->upcoming()
            ->limit(3)
            ->get();

        $isSubscribed = auth()->check() &&
                       $event->subscribers()->where('user_id', auth()->id())->exists();

        return view('events.show', compact('event', 'relatedEvents', 'isSubscribed'));
    }

    public function subscribe(CulturalEvent $event)
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Veuillez vous connecter pour vous abonner aux notifications');
        }

        $event->subscribers()->syncWithoutDetaching([
            auth()->id() => [
                'is_sent' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        return back()->with('success', 'Vous recevrez une notification pour cet événement');
    }

    public function unsubscribe(CulturalEvent $event)
    {
        if (auth()->check()) {
            $event->subscribers()->detach(auth()->id());
        }

        return back()->with('success', 'Abonnement annulé');
    }

    public function updatePreferences(Request $request)
    {
        $validated = $request->validate([
            'event_type' => 'nullable|string',
            'region' => 'nullable|string',
        ]);

        UserEventSubscription::updateOrCreate(
            ['user_id' => auth()->id()],
            array_merge($validated, ['is_active' => true])
        );

        return back()->with('success', 'Préférences de notification mises à jour');
    }
}
