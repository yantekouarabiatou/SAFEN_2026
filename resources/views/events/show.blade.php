@extends('layouts.app')

@section('title', $event->name . ' - AFRI-HERITAGE')

@push('styles')
<style>
    .event-hero {
        position: relative;
        height: 400px;
        background-size: cover;
        background-position: center;
        border-radius: 20px;
        overflow: hidden;
    }

    .event-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, rgba(0,0,0,0.3), rgba(0,0,0,0.7));
    }

    .event-hero-content {
        position: relative;
        z-index: 2;
        color: white;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 3rem;
    }

    .event-info-card {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 100px;
    }

    .info-item {
        display: flex;
        align-items: start;
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #eee;
    }

    .info-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .info-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 1.2rem;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <!-- Event Hero -->
    <div class="event-hero mb-5"
         style="background-image: url('{{ $event->image_url ?? asset('images/default-event.jpg') }}')">
        <div class="event-hero-content">
            <div>
                <span class="badge bg-benin-red mb-3 px-4 py-2">{{ ucfirst($event->type) }}</span>
                <h1 class="display-4 fw-bold mb-3">{{ $event->name }}</h1>
                <p class="lead">{{ $event->location }}</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Description -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h3 class="fw-bold mb-4">
                        <i class="bi bi-info-circle text-benin-green"></i>
                        À propos de l'événement
                    </h3>
                    <p class="text-muted" style="line-height: 1.8;">
                        {{ $event->description }}
                    </p>
                </div>
            </div>

            <!-- Traditions -->
            @if($event->traditions)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-4">
                            <i class="bi bi-stars text-benin-yellow"></i>
                            Traditions & Rituels
                        </h3>
                        <p class="text-muted" style="line-height: 1.8;">
                            {{ $event->traditions }}
                        </p>
                    </div>
                </div>
            @endif

            <!-- Related Events -->
            @if($relatedEvents->count() > 0)
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-4">
                            <i class="bi bi-calendar-check text-benin-red"></i>
                            Événements similaires
                        </h3>
                        <div class="row g-3">
                            @foreach($relatedEvents as $related)
                                <div class="col-md-6">
                                    <a href="{{ route('events.show', $related) }}"
                                       class="text-decoration-none">
                                        <div class="card border h-100 hover-shadow">
                                            <div class="card-body">
                                                <h6 class="fw-bold text-charcoal">{{ $related->name }}</h6>
                                                <p class="text-muted small mb-2">
                                                    <i class="bi bi-calendar3"></i> {{ $related->formatted_date }}
                                                </p>
                                                <p class="text-muted small mb-0">
                                                    <i class="bi bi-geo-alt"></i> {{ $related->location }}
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="event-info-card">
                <h4 class="fw-bold mb-4">Informations pratiques</h4>

                <!-- Date -->
                <div class="info-item">
                    <div class="info-icon bg-benin-green text-white">
                        <i class="bi bi-calendar3"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Date</h6>
                        <p class="text-muted mb-0">{{ $event->formatted_date }}</p>
                        @if($event->event_time)
                            <p class="text-muted small mb-0">{{ $event->event_time }}</p>
                        @endif
                        @if($event->days_until_event >= 0)
                            <span class="badge bg-warning text-dark mt-2">
                                Dans {{ $event->days_until_event }} jour(s)
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Location -->
                <div class="info-item">
                    <div class="info-icon bg-benin-red text-white">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Lieu</h6>
                        <p class="text-muted mb-0">{{ $event->location }}</p>
                        @if($event->region)
                            <p class="text-muted small mb-0">Région: {{ $event->region }}</p>
                        @endif
                    </div>
                </div>

                <!-- Origin -->
                @if($event->ethnic_origin)
                    <div class="info-item">
                        <div class="info-icon bg-benin-yellow text-charcoal">
                            <i class="bi bi-people"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Origine ethnique</h6>
                            <p class="text-muted mb-0">{{ $event->ethnic_origin }}</p>
                        </div>
                    </div>
                @endif

                <!-- Views -->
                <div class="info-item">
                    <div class="info-icon bg-terracotta text-white">
                        <i class="bi bi-eye"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Vues</h6>
                        <p class="text-muted mb-0">{{ $event->views }} personne(s)</p>
                    </div>
                </div>

                <!-- Notification Button -->
                <div class="d-grid gap-2 mt-4">
                    @auth
                        @if($isSubscribed)
                            <form action="{{ route('events.unsubscribe', $event) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bi bi-check-circle"></i> Notifications activées
                                </button>
                            </form>
                            <small class="text-muted text-center">
                                Vous recevrez une notification {{ $event->notification_days_before }} jours avant l'événement
                            </small>
                        @else
                            <form action="{{ route('events.subscribe', $event) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-benin-red w-100">
                                    <i class="bi bi-bell"></i> Recevoir une notification
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-benin-red">
                            <i class="bi bi-bell"></i> Connectez-vous pour être notifié
                        </a>
                    @endauth

                    <a href="{{ route('events.index') }}" class="btn btn-outline-benin-green">
                        <i class="bi bi-arrow-left"></i> Tous les événements
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
