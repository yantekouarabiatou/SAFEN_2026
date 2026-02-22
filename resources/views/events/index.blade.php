@extends('layouts.app')

@section('title', 'Événements Culturels - TOTCHEMEGNON')

@push('styles')
<style>
    .events-hero {
        background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.8)),
                    url('{{ asset('images/events-bg.jpg') }}');
        background-size: cover;
        background-position: center;
        color: white;
        padding: 5rem 0;
        text-align: center;
    }

    .event-card {
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    }

    .event-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }

    .event-image {
        height: 250px;
        object-fit: cover;
        width: 100%;
    }

    .event-date-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 10px;
        padding: 10px 15px;
        text-align: center;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .event-day {
        font-size: 1.5rem;
        font-weight: bold;
        color: var(--benin-red);
        line-height: 1;
    }

    .event-month {
        font-size: 0.8rem;
        color: var(--benin-green);
        text-transform: uppercase;
    }

    .countdown-badge {
        background: linear-gradient(135deg, var(--benin-red), var(--benin-yellow));
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-weight: bold;
        font-size: 0.85rem;
    }

    .filter-section {
        background: white;
        padding: 2rem;
        border-radius: 15px;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
        margin-bottom: 2rem;
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="events-hero">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3">🎭 Événements Culturels</h1>
        <p class="lead">Découvrez et participez aux événements culturels béninois</p>
    </div>
</section>

<div class="container py-5">
    <!-- Filters -->
    <div class="filter-section">
        <form method="GET" action="{{ route('events.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-bold">Type d'événement</label>
                <select name="type" class="form-select">
                    <option value="">Tous les types</option>
                    @foreach($eventTypes as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                            {{ ucfirst($type) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Région</label>
                <select name="region" class="form-select">
                    <option value="">Toutes les régions</option>
                    @foreach($regions as $region)
                        <option value="{{ $region }}" {{ request('region') == $region ? 'selected' : '' }}>
                            {{ $region }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-benin-green w-100">
                    <i class="bi bi-search"></i> Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Events Grid -->
    @if($upcomingEvents->count() > 0)
        <div class="row g-4">
            @foreach($upcomingEvents as $event)
                <div class="col-md-4">
                    <div class="card event-card h-100">
                        <div class="position-relative">
                            @if($event->image_url)
                                <img src="{{ $event->image_url }}" alt="{{ $event->name }}" class="event-image">
                            @else
                                <div class="event-image bg-benin-green d-flex align-items-center justify-content-center">
                                    <i class="bi bi-calendar-event text-white" style="font-size: 4rem;"></i>
                                </div>
                            @endif

                            <div class="event-date-badge">
                                <div class="event-day">{{ $event->event_date->format('d') }}</div>
                                <div class="event-month">{{ $event->event_date->locale('fr')->format('M') }}</div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-benin-red">{{ ucfirst($event->type) }}</span>
                                @if($event->days_until_event >= 0 && $event->days_until_event <= 7)
                                    <span class="countdown-badge">
                                        <i class="bi bi-clock"></i>
                                        {{ $event->days_until_event == 0 ? "Aujourd'hui" : $event->days_until_event . 'j' }}
                                    </span>
                                @endif
                            </div>

                            <h5 class="fw-bold mb-3">{{ $event->name }}</h5>

                            <p class="text-muted small mb-3">
                                {{ Str::limit($event->description, 120) }}
                            </p>

                            <div class="mb-3">
                                <div class="text-muted small mb-2">
                                    <i class="bi bi-calendar3 text-benin-green"></i>
                                    {{ $event->formatted_date }}
                                    @if($event->event_time)
                                        • {{ $event->event_time }}
                                    @endif
                                </div>
                                <div class="text-muted small mb-2">
                                    <i class="bi bi-geo-alt text-benin-red"></i>
                                    {{ $event->location }}
                                    @if($event->region)
                                        ({{ $event->region }})
                                    @endif
                                </div>
                                @if($event->ethnic_origin)
                                    <div class="text-muted small">
                                        <i class="bi bi-people text-benin-yellow"></i>
                                        {{ $event->ethnic_origin }}
                                    </div>
                                @endif
                            </div>

                            <div class="d-grid gap-2">
                                <a href="{{ route('events.show', $event) }}"
                                   class="btn btn-benin-green">
                                    <i class="bi bi-eye"></i> Voir les détails
                                </a>

                                @auth
                                    @if(!$event->subscribers->contains(auth()->id()))
                                        <form action="{{ route('events.subscribe', $event) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-benin-red w-100">
                                                <i class="bi bi-bell"></i> Me notifier
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-success w-100" disabled>
                                            <i class="bi bi-check-circle"></i> Notifications activées
                                        </button>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-outline-benin-red">
                                        <i class="bi bi-bell"></i> Connectez-vous pour recevoir des notifications
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-5">
            {{ $upcomingEvents->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-calendar-x text-muted" style="font-size: 5rem;"></i>
            <h3 class="mt-3 text-muted">Aucun événement à venir</h3>
            <p class="text-muted">Revenez bientôt pour découvrir nos prochains événements culturels !</p>
        </div>
    @endif
</div>
@endsection
