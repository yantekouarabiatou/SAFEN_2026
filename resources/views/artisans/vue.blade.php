@extends('layouts.app')

@section('title', __('Liste des artisans '))

@push('styles')
    <style>
        :root {
            --benin-green: #009639;
            --benin-yellow: #FCD116;
            --benin-red: #E8112D;
            --terracotta: #D4774E;
            --beige: #F5E6D3;
            --charcoal: #2C3E50;
            --light-gray: #f8f9fa;
        }

        .page-header {
            background: linear-gradient(135deg, var(--benin-green) 0%, var(--benin-red) 100%);
            color: white;
            padding: 5rem 0 4rem;
            border-radius: 0 0 30px 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.08'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.15;
        }

        .filter-sidebar {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--beige);
        }

        .view-toggle .btn {
            border-radius: 0 !important;
            padding: 0.6rem 1.2rem;
            font-weight: 500;
        }

        .view-toggle .btn:first-child {
            border-top-left-radius: 50px !important;
            border-bottom-left-radius: 50px !important;
        }

        .view-toggle .btn:last-child {
            border-top-right-radius: 50px !important;
            border-bottom-right-radius: 50px !important;
        }

        .view-toggle .btn.active {
            background: var(--benin-green) !important;
            color: white !important;
            border-color: var(--benin-green) !important;
        }

        .artisan-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .artisan-list .artisan-card {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .artisan-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.06);
            transition: all 0.35s ease;
        }

        .artisan-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0, 150, 57, 0.15);
        }

        .artisan-img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }

        .craft-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: var(--benin-yellow);
            color: var(--charcoal);
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .distance-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--benin-green);
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-size: 0.85rem;
        }

        .verified-badge {
            background: var(--benin-green);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
        }

        .map-container {
            height: 600px;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
        }

        .empty-state {
            background: white;
            border-radius: 16px;
            padding: 5rem 2rem;
            text-align: center;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.08);
        }
    </style>
@endpush

@section('content')
    <!-- Hero / Header -->
    <section class="page-header">
        <div class="container position-relative">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb text-white">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">{{ __('messages.home') }}</a></li>
                    <li class="breadcrumb-item active text-white">{{ __('Liste des Artisans') }}</li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-4">
                <div>
                    <h1 class="display-5 fw-bold text-white mb-2">{{ __('artisans.title') }}</h1>
                    <p class="lead text-white-75 mb-0">{{ __('Liste des artisans') }}</p>
                </div>
                <a href="{{ route('artisans.create') }}" class="btn btn-light rounded-pill px-4 py-2 fw-bold">
                    <i class="bi bi-plus-circle me-2"></i> {{ __('artisans.become_artisan') ?? 'Devenir artisan' }}
                </a>
            </div>
        </div>
    </section>

    <div class="container py-5">
        <div class="row g-5">
            <!-- Sidebar Filtres -->
            <div class="col-lg-3">
                <div class="filter-sidebar sticky-top" style="top: 2rem;">
                    <h5 class="fw-bold mb-4">
                        <i class="bi bi-funnel me-2 text-benin-green"></i> {{ __('messages.filter') }}
                    </h5>

                    <form method="GET" action="{{ route('artisans.index') }}">
                        <div class="mb-4">
                            <label for="search" class="form-label fw-bold">{{ __('messages.search') }}</label>
                            <input type="text" name="search" id="search" class="form-control rounded-pill"
                                value="{{ request('search') }}" placeholder="{{ __('artisans.search_placeholder') }}">
                        </div>

                        <div class="mb-4">
                            <label for="craft" class="form-label fw-bold">{{ __('artisans.filter_by_craft') }}</label>
                            <select name="craft" id="craft" class="form-select rounded-pill">
                                <option value="">{{ __('artisans.all_crafts') }}</option>
                                @foreach(__('artisans.crafts') as $key => $craft)
                                    <option value="{{ $key }}" {{ request('craft') == $key ? 'selected' : '' }}>
                                        {{ $craft }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="city" class="form-label fw-bold">{{ __('artisans.filter_by_city') }}</label>
                            <select name="city" id="city" class="form-select rounded-pill">
                                <option value="">{{ __('artisans.all_cities') }}</option>
                                @foreach(__('artisans.cities') as $key => $city)
                                    <option value="{{ $key }}" {{ request('city') == $key ? 'selected' : '' }}>
                                        {{ $city }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        @if(request('latitude') && request('longitude'))
                            <input type="hidden" name="latitude" value="{{ request('latitude') }}">
                            <input type="hidden" name="longitude" value="{{ request('longitude') }}">
                            <div class="mb-4">
                                <label class="form-label fw-bold">{{ __('artisans.radius') ?? 'Rayon (km)' }}</label>
                                <input type="range" name="radius" min="5" max="100" step="5" value="{{ request('radius', 30) }}"
                                    class="form-range" oninput="document.getElementById('radiusVal').textContent = this.value">
                                <div class="text-center mt-2 fw-bold">
                                    <span id="radiusVal">{{ request('radius', 30) }}</span> km
                                </div>
                            </div>
                        @endif

                        <button type="submit" class="btn btn-benin-green w-100 rounded-pill py-2">
                            <i class="bi bi-search me-2"></i> {{ __('messages.filter') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Contenu principal -->
            <div class="col-lg-9">
                <!-- Barre de vue + stats -->
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                    <div>
                        <span class="fs-5 fw-bold text-charcoal">
                            {{ $artisans->total() }} {{ __('artisans.found_artisans') ?? 'artisans trouvés' }}
                        </span>
                    </div>

                    <div class="btn-group view-toggle" role="group">
                        <button type="button" class="btn btn-outline-secondary view-btn" data-view="grid"
                            onclick="changeView('grid')">
                            <i class="bi bi-grid-3x3-gap"></i> {{ __('artisans.view_grid') }}
                        </button>
                        <button type="button" class="btn btn-outline-secondary view-btn" data-view="list"
                            onclick="changeView('list')">
                            <i class="bi bi-list-ul"></i> {{ __('artisans.view_list') }}
                        </button>
                        <button type="button" class="btn btn-outline-secondary view-btn" data-view="map"
                            onclick="changeView('map')">
                            <i class="bi bi-geo-alt"></i> {{ __('artisans.view_map') }}
                        </button>
                    </div>
                </div>

                <!-- Vue Carte -->
                <div id="view-map" data-view-content="map" style="display: none;">
                    <div class="map-container">
                        <div id="artisans-map" class="w-100 h-100"></div>
                    </div>
                </div>

                <!-- Vue Grille / Liste -->
                <div id="view-content" data-view-content="grid" data-view-content="list" style="display: none;">
                    <div class="view-wrapper artisan-grid">
                        @forelse($artisans as $artisan)
                            <div class="artisan-card">
                                <!-- Image -->
                                <div class=""
                                    style="">
                                    <a href="{{ route('artisans.show', $artisan) }}">
                                        <img src="{{ $artisan->photos->first()?->full_url ?? asset('images/default-artisan.jpg') }}"
                                            alt="{{ $artisan->user->name }}" class="img-fluid w-100"
                                            style="height: 220px; object-fit: cover;">
                                    </a>

                                    <span class="craft-badge">
                                        {{ $artisan->craft_label }}
                                    </span>

                                    @if(request('latitude') && request('longitude') && isset($artisan->distance))
                                        <span class="distance-badge">
                                            {{ round($artisan->distance, 1) }} km
                                        </span>
                                    @endif
                                </div>

                                <!-- Contenu -->
                                <div class="p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h5 class="mb-1">
                                                <a href="{{ route('artisans.show', $artisan) }}"
                                                    class="text-decoration-none text-charcoal fw-bold">
                                                    {{ $artisan->user->name }}
                                                </a>
                                                @if($artisan->verified)
                                                    <span class="verified-badge ms-2">
                                                        <i class="bi bi-patch-check-fill me-1"></i>{{ __('artisans.verified') }}
                                                    </span>
                                                @endif
                                            </h5>
                                            <small class="text-muted d-block">
                                                <i class="bi bi-geo-alt-fill text-benin-red me-1"></i>
                                                {{ $artisan->city }}
                                                @if($artisan->neighborhood) • {{ $artisan->neighborhood }} @endif
                                            </small>
                                        </div>

                                        <div class="rating-stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i
                                                    class="bi {{ $i <= floor($artisan->rating_avg ?? 0) ? 'bi-star-fill' : 'bi-star' }} text-warning"></i>
                                            @endfor
                                            <small class="text-muted ms-1">({{ $artisan->rating_count ?? 0 }})</small>
                                        </div>
                                    </div>

                                    <p class="text-muted small mb-3">
                                        {{ Str::limit($artisan->bio ?? 'Artisan passionné', 100) }}
                                    </p>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="small text-muted">
                                            <i class="bi bi-calendar-check me-1"></i>
                                            {{ $artisan->years_experience }}+ ans
                                        </div>

                                        <div class="d-flex gap-2">
                                            @if($artisan->whatsapp)
                                                <a href="https://wa.me/{{ preg_replace('/\D/', '', $artisan->whatsapp) }}"
                                                    target="_blank" class="btn btn-success btn-sm rounded-circle">
                                                    <i class="bi bi-whatsapp"></i>
                                                </a>
                                            @endif
                                            <a href="{{ route('artisans.show', $artisan) }}"
                                                class="btn btn-benin-green btn-sm rounded-pill px-4">
                                                {{ __('views.see') }} {{ __('views.view_profile') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="empty-state">
                                    <i class="bi bi-search fs-1 text-muted mb-3 d-block"></i>
                                    <h4>{{ __('views.no_artisans_found') }}</h4>
                                    <p class="text-muted">{{ __('views.modify_search') }}</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($artisans->hasPages())
                        <div class="d-flex justify-content-center mt-5">
                            {{ $artisans->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Valeur par défaut
    let currentView = localStorage.getItem('artisansView') || 'grid';

    function changeView(view) {
        currentView = view;
        localStorage.setItem('artisansView', view);

        // Mise à jour boutons
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.classList.remove('active', 'btn-primary');
            btn.classList.add('btn-outline-secondary');
        });

        const activeBtn = document.querySelector(`.view-btn[data-view="${view}"]`);
        if (activeBtn) {
            activeBtn.classList.remove('btn-outline-secondary');
            activeBtn.classList.add('active', 'btn-primary');
        }

        // Gestion des vues grid/list
        const viewWrapper = document.querySelector('.view-wrapper');
        const artisanCards = document.querySelectorAll('.artisan-card');

        if (view === 'grid') {
            viewWrapper.classList.remove('artisan-list');
            viewWrapper.classList.add('artisan-grid');

            artisanCards.forEach(card => {
                card.classList.remove('d-flex', 'mb-3');
                const imageDiv = card.querySelector('div:first-child');
                const contentDiv = card.querySelector('div.p-4');

                if (imageDiv) {
                    imageDiv.classList.remove('flex-shrink-0');
                    imageDiv.style.width = '';
                }
                if (contentDiv) {
                    contentDiv.classList.remove('flex-grow-1');
                }
            });
        } else if (view === 'list') {
            viewWrapper.classList.remove('artisan-grid');
            viewWrapper.classList.add('artisan-list');

            artisanCards.forEach(card => {
                card.classList.add('d-flex', 'mb-3');
                const imageDiv = card.querySelector('div:first-child');
                const contentDiv = card.querySelector('div.p-4');

                if (imageDiv) {
                    imageDiv.classList.add('flex-shrink-0');
                    imageDiv.style.width = '240px';
                }
                if (contentDiv) {
                    contentDiv.classList.add('flex-grow-1');
                }
            });
        }

        // Affichage des contenus
        document.querySelectorAll('[data-view-content]').forEach(el => {
            el.style.display = (el.dataset.viewContent === view) ? 'block' : 'none';
        });

        // Chargement carte si nécessaire
        if (view === 'map' && typeof google !== 'undefined' && !window.artisansMapInitialized) {
            initArtisansMap();
            window.artisansMapInitialized = true;
        }
    }

    // Appliquer au chargement
    document.addEventListener('DOMContentLoaded', () => {
        changeView(currentView);
    });
</script>

<!-- Carte Google Maps -->
@if($artisans->count() > 0)
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&callback=initArtisansMap" async defer></script>
    <script>
        let map;
        let markers = [];

        function initArtisansMap() {
            const center = { lat: 9.3077, lng: 2.3158 }; // Centre Bénin

            map = new google.maps.Map(document.getElementById('artisans-map'), {
                center: center,
                zoom: 7,
                styles: [{ featureType: "poi", elementType: "labels", stylers: [{ visibility: "off" }] }]
            });

            @foreach($artisans as $artisan)
                @if($artisan->latitude && $artisan->longitude)
                    addMarker({
                        lat: {{ $artisan->latitude }},
                        lng: {{ $artisan->longitude }},
                        title: "{{ addslashes($artisan->user->name) }}",
                        craft: "{{ addslashes($artisan->craft_label) }}",
                        city: "{{ addslashes($artisan->city) }}",
                        id: {{ $artisan->id }},
                        distance: {{ $artisan->distance ?? 'null' }}
                    });
                @endif
            @endforeach
        }

        function addMarker(data) {
            const marker = new google.maps.Marker({
                position: { lat: data.lat, lng: data.lng },
                map: map,
                title: data.title
            });

            const info = new google.maps.InfoWindow({
                content: `
                    <div class="p-2" style="min-width:220px;">
                        <h6 class="fw-bold mb-1">${data.title}</h6>
                        <p class="small mb-1"><strong>${data.craft}</strong></p>
                        <p class="small mb-2">${data.city}</p>
                        ${data.distance ? `<p class="small mb-2 text-success"><strong>${data.distance.toFixed(1)} km</strong></p>` : ''}
                        <a href="/artisans/${data.id}" class="btn btn-sm btn-benin-green w-100 mt-2">
                            {{ __('artisans.view_profile') }}
                        </a>
                    </div>
                `
            });

            marker.addListener('click', () => info.open(map, marker));
            markers.push(marker);
        }
    </script>
@endif
@endpush