@extends('layouts.app')

@section('title', 'Culture Béninoise - TOTCHEMEGNON')

@push('styles')
    <style>
        .culture-hero {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.9)),
                url('{{ asset('products/tissu.jpg') }}');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 6rem 0;
            text-align: center;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
            margin-top: -4rem;
            position: relative;
            z-index: 1;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-10px);
        }

        .stat-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--benin-green) 0%, var(--benin-yellow) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
        }

        .culture-categories {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin: 4rem 0;
        }

        .culture-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
        }

        .culture-card:hover {
            transform: translateY(-5px);
        }

        .culture-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .fact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .fact-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            border-left: 4px solid var(--benin-green);
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
        }

        .fact-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .timeline {
            position: relative;
            padding-left: 2rem;
            margin: 3rem 0;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--benin-green);
        }

        .timeline-item {
            position: relative;
            margin-bottom: 2rem;
            padding-left: 2rem;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -2.5rem;
            top: 0.5rem;
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
            background: var(--benin-red);
            border: 3px solid white;
            box-shadow: 0 0 0 2px var(--benin-green);
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .stat-number {
                font-size: 2rem;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="culture-hero">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <span class="badge bg-benin-yellow text-charcoal px-4 py-2 rounded-pill mb-3 fs-6">
                        🇧🇯 Patrimoine Culturel
                    </span>
                    <h1 class="display-4 fw-bold mb-4">Culture Béninoise</h1>
                    <p class="lead mb-0" style="max-width: 700px; margin: 0 auto;">
                        Découvrez la richesse du patrimoine culturel béninois : traditions, histoire, artisanat et
                        gastronomie
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon bg-benin-green text-white">
                    <i class="bi bi-people"></i>
                </div>
                <div class="stat-number" data-count="{{ $stats['artisans'] }}">0</div>
                <p class="text-muted mb-0">Artisans inscrits</p>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-benin-yellow text-charcoal">
                    <i class="bi bi-palette"></i>
                </div>
                <div class="stat-number" data-count="{{ $stats['products'] }}">0</div>
                <p class="text-muted mb-0">Produits artisanaux</p>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-benin-red text-white">
                    <i class="bi bi-egg-fried"></i>
                </div>
                <div class="stat-number" data-count="{{ $stats['dishes'] }}">0</div>
                <p class="text-muted mb-0">Plats traditionnels</p>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-terracotta text-white">
                    <i class="bi bi-geo-alt"></i>
                </div>
                <div class="stat-number" data-count="{{ $stats['regions'] }}">0</div>
                <p class="text-muted mb-0">Régions représentées</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container py-5">
        <!-- Culture Categories -->
        <div class="culture-categories">
            <div class="culture-card">
                <img src="{{ asset('products/Calebasse1.jpg') }}" alt="Artisanat">
                <div class="p-4">
                    <h4 class="fw-bold mb-3">Artisanat Traditionnel</h4>
                    <p class="text-muted mb-3">
                        Découvrez les techniques ancestrales de fabrication des masques, sculptures, tissages et poteries
                        béninois.
                    </p>
                    <a href="{{ route('products.index') }}" class="btn btn-benin-green rounded-pill">
                        Explorer l'artisanat
                    </a>
                </div>
            </div>

            <div class="culture-card">
                <img src="{{ asset('dishes/ebaSauegombo.jpg') }}" alt="Gastronomie">
                <div class="p-4">
                    <h4 class="fw-bold mb-3">Gastronomie</h4>
                    <p class="text-muted mb-3">
                        Explorez les saveurs authentiques du Bénin avec nos plats traditionnels et leurs histoires
                        fascinantes.
                    </p>
                    <a href="{{ route('gastronomie.index') }}" class="btn btn-benin-red rounded-pill">
                        Découvrir les saveurs
                    </a>
                </div>
            </div>

            <div class="culture-card">
                <img src="{{ asset('products/guedele.jpg') }}" alt="Traditions">
                <div class="p-4">
                    <h4 class="fw-bold mb-3">Traditions & Rituels</h4>
                    <p class="text-muted mb-3">
                        Plongez dans les traditions ancestrales, cérémonies et rituels qui font la richesse culturelle du
                        Bénin.
                    </p>
                    <a href="{{ route('culture.traditions') }}" class="btn btn-benin-yellow rounded-pill">
                        Explorer les traditions
                    </a>
                </div>
            </div>
        </div>

        <!-- Cultural Facts -->
        <div class="row mt-5">
            <div class="col-12">
                <h2 class="fw-bold mb-4">Faits Culturels</h2>
                <div class="fact-grid">
                    @foreach($culturalFacts as $fact)
                        <div class="fact-card">
                            <div class="fact-icon text-benin-green">

                            </div>
                            <h5 class="fw-bold mb-2">{{ $fact['title'] }}</h5>
                            <p class="text-muted mb-0">{{ $fact['description'] }}</p>
                            <div class="mt-3">
                                <span class="badge bg-light text-muted">{{ $fact['category'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Featured Products -->
        @if($featuredProducts->count() > 0)
            <div class="row mt-5">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="fw-bold mb-0">Objets Culturels en Vedette</h2>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-benin-green rounded-pill">
                            Voir tous
                        </a>
                    </div>
                    <div class="row g-4">
                        @foreach($featuredProducts as $product)
                            <div class="col-md-3 col-sm-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <img src="{{ $product->images->first()->image_url ?? asset('images/default-product.jpg') }}"
                                        alt="{{ $product->name }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                                    <div class="card-body">
                                        <h6 class="fw-bold">
                                            <a href="{{ route('products.show', $product) }}"
                                                class="text-decoration-none text-charcoal">
                                                {{ Str::limit($product->name, 30) }}
                                            </a>
                                        </h6>
                                        <p class="text-muted small mb-2">{{ $product->ethnic_origin }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-benin-green fw-bold">
                                                {{ $product->formatted_price }}
                                            </span>
                                            <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-benin-green">
                                                Voir
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Featured Artisans -->
        @if($featuredArtisans->count() > 0)
            <div class="row mt-5">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="fw-bold mb-0">Maîtres Artisans</h2>
                        <a href="{{ route('artisans.vue') }}" class="btn btn-outline-benin-green rounded-pill">
                            Voir tous
                        </a>
                    </div>
                    <div class="row g-4">
                        @foreach($featuredArtisans as $artisan)
                            <div class="col-md-4 col-sm-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <img src="{{ $artisan->photos->first()->photo_url ?? asset('images/default-artisan.jpg') }}"
                                        alt="{{ $artisan->user->name }}" class="card-img-top"
                                        style="height: 200px; object-fit: cover;">
                                    <div class="card-body text-center">
                                        <h6 class="fw-bold mb-2">{{ $artisan->user->name }}</h6>
                                        <p class="text-benin-green mb-3">
                                            <i class="bi bi-tools me-1"></i> {{ $artisan->craft_label }}
                                        </p>
                                        <div class="rating-stars mb-3">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi {{ $i <= floor($artisan->rating_avg) ? 'bi-star-fill' : 'bi-star' }}"></i>
                                            @endfor
                                        </div>
                                        <a href="{{ route('artisans.show', $artisan) }}"
                                            class="btn btn-benin-green btn-sm rounded-pill">
                                            Voir le profil
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Popular Dishes -->
        @if($popularDishes->count() > 0)
            <div class="row mt-5">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="fw-bold mb-0">Plats Populaires</h2>
                        <a href="{{ route('gastronomie.index') }}" class="btn btn-outline-benin-red rounded-pill">
                            Voir tous
                        </a>
                    </div>
                    <div class="row g-4">
                        @foreach($popularDishes as $dish)
                            <div class="col-md-4 col-sm-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <img src="{{ $dish->images->first()->image_url ?? asset('images/default-dish.jpg') }}"
                                        alt="{{ $dish->name }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-2">{{ $dish->name }}</h6>
                                        <p class="text-muted small mb-2">
                                            {{ $dish->ethnic_origin }} • {{ $dish->region }}
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="bi bi-eye me-1"></i> {{ $dish->views }} vues
                                            </small>
                                            <a href="{{ route('gastronomie.show', $dish) }}" class="btn btn-sm btn-benin-red">
                                                Découvrir
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Upcoming Cultural Events -->
        @if(isset($upcomingEvents) && $upcomingEvents->count() > 0)
            <div class="row mt-5">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="fw-bold mb-0">
                            <i class="bi bi-calendar-event text-benin-red"></i>
                            Événements Culturels à Venir
                        </h2>
                        <a href="{{ route('events.index') }}" class="btn btn-outline-benin-red rounded-pill">
                            <i class="bi bi-calendar-check me-1"></i>
                            Voir tous les événements
                        </a>
                    </div>
                    <div class="row g-4">
                        @foreach($upcomingEvents->take(3) as $event)
                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm h-100 overflow-hidden">
                                    @if($event->image_url)
                                        <img src="{{ $event->image_url }}" alt="{{ $event->name }}" class="card-img-top"
                                            style="height: 200px; object-fit: cover;">
                                    @else
                                        <div class="card-img-top bg-benin-green d-flex align-items-center justify-content-center"
                                            style="height: 200px;">
                                            <i class="bi bi-calendar-event text-white" style="font-size: 4rem;"></i>
                                        </div>
                                    @endif
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="badge bg-benin-red">{{ ucfirst($event->type) }}</span>
                                            @if($event->days_until_event >= 0 && $event->days_until_event <= 7)
                                                <span class="badge bg-warning text-dark">
                                                    <i class="bi bi-clock"></i> {{ $event->days_until_event }}j
                                                </span>
                                            @endif
                                        </div>
                                        <h5 class="fw-bold mb-2">{{ $event->name }}</h5>
                                        <p class="text-muted small mb-3">
                                            {{ Str::limit($event->description, 100) }}
                                        </p>
                                        <div class="mb-3">
                                            <div class="text-muted small mb-1">
                                                <i class="bi bi-calendar3 text-benin-green"></i>
                                                {{ $event->formatted_date }}
                                            </div>
                                            <div class="text-muted small">
                                                <i class="bi bi-geo-alt text-benin-red"></i>
                                                {{ $event->location }}
                                            </div>
                                        </div>
                                        <div class="d-grid gap-2">
                                            <a href="{{ route('events.show', $event) }}" class="btn btn-benin-green btn-sm">
                                                <i class="bi bi-eye"></i> Voir détails
                                            </a>
                                            @auth
                                                @if(!$event->subscribers->contains(auth()->id()))
                                                    <form action="{{ route('events.subscribe', $event) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-benin-red btn-sm w-100">
                                                            <i class="bi bi-bell"></i> Me notifier
                                                        </button>
                                                    </form>
                                                @endif
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Historical Timeline -->
        <div class="row mt-5">
            <div class="col-12">
                <h2 class="fw-bold mb-4">Histoire du Bénin</h2>
                <div class="timeline">
                    <div class="timeline-item">
                        <h5 class="fw-bold">Royaume du Dahomey (1600-1894)</h5>
                        <p class="text-muted">
                            Puissant royaume africain connu pour ses guerrières Amazones et son commerce.
                            Palais royaux classés au patrimoine mondial de l'UNESCO.
                        </p>
                    </div>
                    <div class="timeline-item">
                        <h5 class="fw-bold">Colonisation française (1894-1960)</h5>
                        <p class="text-muted">
                            Devenu colonie française sous le nom de Dahomey. Influence sur l'administration,
                            l'éducation et la culture.
                        </p>
                    </div>
                    <div class="timeline-item">
                        <h5 class="fw-bold">Indépendance (1960)</h5>
                        <p class="text-muted">
                            Le Dahomey obtient son indépendance de la France le 1er août 1960.
                        </p>
                    </div>
                    <div class="timeline-item">
                        <h5 class="fw-bold">République du Bénin (1975)</h5>
                        <p class="text-muted">
                            Changement de nom du Dahomey en République populaire du Bénin, inspiré
                            par le Royaume du Bénin voisin.
                        </p>
                    </div>
                    <div class="timeline-item">
                        <h5 class="fw-bold">Démocratisation (1990)</h5>
                        <p class="text-muted">
                            Transition vers la démocratie multipartite et adoption d'une nouvelle constitution.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Counter animation
        function animateCounter(element) {
            const target = parseInt(element.dataset.count);
            const duration = 2000;
            const step = target / (duration / 16);
            let current = 0;

            const timer = setInterval(() => {
                current += step;
                if (current >= target) {
                    element.textContent = target;
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current);
                }
            }, 16);
        }

        // Trigger counter animation when visible
        const counters = document.querySelectorAll('.stat-number');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !entry.target.classList.contains('animated')) {
                    animateCounter(entry.target);
                    entry.target.classList.add('animated');
                }
            });
        }, { threshold: 0.5 });

        counters.forEach(counter => observer.observe(counter));
    </script>
@endpush
