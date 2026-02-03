@extends('layouts.app')

@section('title', 'Artisans & Services - AFRI-HERITAGE Bénin')

@push('styles')
<style>
    .artisan-grid {
        display: grid;
        gap: 1.5rem;
    }

    .artisan-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .artisan-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .craft-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: rgba(0, 150, 57, 0.9);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .distance-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(44, 44, 44, 0.9);
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
    }

    .verified-badge {
        display: inline-flex;
        align-items: center;
        background: var(--benin-green);
        color: white;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.7rem;
        margin-left: 5px;
    }

    .filter-sidebar {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        height: fit-content;
        position: sticky;
        top: 100px;
    }

    .view-toggle {
        display: flex;
        gap: 5px;
        background: var(--beige);
        padding: 5px;
        border-radius: 8px;
    }

    .view-toggle button {
        flex: 1;
        border: none;
        background: transparent;
        padding: 8px;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .view-toggle button.active {
        background: var(--benin-green);
        color: white;
    }

    .map-container {
        height: 600px;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    @media (min-width: 768px) {
        .artisan-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (min-width: 992px) {
        .artisan-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (min-width: 1200px) {
        .artisan-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="container">
        <!-- Hero Section -->
        <div class="row mb-5">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item active">Artisans & Services</li>
                    </ol>
                </nav>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="display-6 fw-bold text-charcoal mb-2">Artisans & Services</h1>
                        <p class="text-muted mb-0">Trouvez des artisans qualifiés près de chez vous</p>
                    </div>
                    <a href="{{ route('artisans.create') }}" class="btn btn-benin-green rounded-pill">
                        <i class="bi bi-plus-circle me-2"></i> Devenir artisan
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar Filters -->
            <div class="col-lg-3 mb-4">
                <div class="filter-sidebar">
                    <h5 class="fw-bold mb-3">Filtres</h5>

                    <form id="filter-form">
                        <!-- Recherche -->
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Recherche</label>
                            <input type="text"
                                   name="search"
                                   class="form-control form-control-sm"
                                   placeholder="Nom, métier, ville..."
                                   value="{{ request('search') }}">
                        </div>

                        <!-- Métier -->
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Métier</label>
                            <select name="craft" class="form-select form-select-sm">
                                <option value="">Tous les métiers</option>
                                @foreach($crafts as $craft)
                                    <option value="{{ $craft }}" {{ request('craft') == $craft ? 'selected' : '' }}>
                                        {{ \App\Models\Artisan::$craftLabels[$craft] ?? $craft }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Ville -->
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Ville</label>
                            <select name="city" class="form-select form-select-sm">
                                <option value="">Toutes les villes</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                        {{ $city }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Note minimum -->
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Note minimum</label>
                            <div class="rating-input">
                                <div class="d-flex gap-1">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio"
                                               name="rating"
                                               id="rating-{{ $i }}"
                                               value="{{ $i }}"
                                               {{ request('rating') == $i ? 'checked' : '' }}
                                               style="display: none;">
                                        <label for="rating-{{ $i }}" class="cursor-pointer">
                                            <i class="bi {{ $i <= (request('rating') ?? 0) ? 'bi-star-fill text-benin-yellow' : 'bi-star text-gray-300' }}"></i>
                                        </label>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        <!-- Géolocalisation -->
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Près de moi</label>
                            <div class="d-flex gap-2">
                                <button type="button"
                                        class="btn btn-outline-benin-green btn-sm flex-grow-1"
                                        onclick="getUserLocation()">
                                    <i class="bi bi-geo-alt me-1"></i> Localiser
                                </button>
                                <select name="radius" class="form-select form-select-sm w-auto">
                                    <option value="5" {{ request('radius') == 5 ? 'selected' : '' }}>5 km</option>
                                    <option value="10" {{ request('radius') == 10 ? 'selected' : '' }}>10 km</option>
                                    <option value="20" {{ request('radius') == 20 ? 'selected' : '' }}>20 km</option>
                                    <option value="50" {{ request('radius') == 50 ? 'selected' : '' }}>50 km</option>
                                </select>
                            </div>
                            <input type="hidden" name="lat" value="{{ request('lat') }}">
                            <input type="hidden" name="lng" value="{{ request('lng') }}">
                        </div>

                        <!-- Tri -->
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Trier par</label>
                            <select name="sort" class="form-select form-select-sm">
                                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Meilleures notes</option>
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Plus récents</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nom (A-Z)</option>
                            </select>
                        </div>

                        <!-- Actions -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-benin-green btn-sm rounded-pill">
                                <i class="bi bi-filter me-1"></i> Appliquer
                            </button>
                            <button type="button"
                                    onclick="resetFilters()"
                                    class="btn btn-outline-secondary btn-sm rounded-pill">
                                Réinitialiser
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <!-- View Toggle & Stats -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <span class="text-muted">
                            {{ $artisans->total() }} artisans trouvés
                        </span>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="view-toggle">
                            <button class="{{ $view == 'grid' ? 'active' : '' }}"
                                    onclick="changeView('grid')">
                                <i class="bi bi-grid-3x3-gap"></i>
                            </button>
                            <button class="{{ $view == 'list' ? 'active' : '' }}"
                                    onclick="changeView('list')">
                                <i class="bi bi-list"></i>
                            </button>
                            <button class="{{ $view == 'map' ? 'active' : '' }}"
                                    onclick="changeView('map')">
                                <i class="bi bi-map"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Map View -->
                @if($view == 'map')
                    <div class="map-container">
                        <div id="artisans-map" class="w-100 h-100"></div>
                    </div>
                @endif

                <!-- Grid/List View -->
                @if(in_array($view, ['grid', 'list']))
                    <div class="{{ $view == 'grid' ? 'artisan-grid' : 'artisan-list' }}">
                        @forelse($artisans as $artisan)
                            <div class="artisan-card {{ $view == 'list' ? 'd-flex mb-3' : '' }}">
                                <!-- Image -->
                                <div class="{{ $view == 'list' ? 'flex-shrink-0' : '' }}"
                                     style="{{ $view == 'list' ? 'width: 200px;' : '' }}">
                                    <a href="{{ route('artisans.show', $artisan) }}">
                                        <img src="{{ $artisan->photos->first()->photo_url ?? asset('images/default-artisan.jpg') }}"
                                             alt="{{ $artisan->user->name }}"
                                             class="img-fluid w-100"
                                             style="{{ $view == 'grid' ? 'height: 200px; object-fit: cover;' : 'height: 200px; object-fit: cover;' }}">
                                    </a>
                                    @if($view == 'grid')
                                        <span class="craft-badge">
                                            {{ \App\Models\Artisan::$craftLabels[$artisan->craft] ?? $artisan->craft }}
                                        </span>
                                        @if(request('lat') && request('lng') && isset($artisan->distance))
                                            <span class="distance-badge">
                                                {{ round($artisan->distance, 1) }} km
                                            </span>
                                        @endif
                                    @endif
                                </div>

                                <!-- Content -->
                                <div class="{{ $view == 'list' ? 'flex-grow-1 p-3' : 'p-3' }}">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h5 class="mb-1">
                                                <a href="{{ route('artisans.show', $artisan) }}"
                                                   class="text-decoration-none text-charcoal">
                                                    {{ $artisan->user->name }}
                                                </a>
                                                @if($artisan->verified)
                                                    <span class="verified-badge">
                                                        <i class="bi bi-patch-check-fill me-1"></i> Vérifié
                                                    </span>
                                                @endif
                                            </h5>
                                            @if($view == 'list')
                                                <span class="badge bg-benin-green text-white">
                                                    {{ \App\Models\Artisan::$craftLabels[$artisan->craft] ?? $artisan->craft }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="rating-stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi {{ $i <= floor($artisan->rating_avg) ? 'bi-star-fill' : 'bi-star' }}"></i>
                                            @endfor
                                            <small class="text-muted ms-1">({{ $artisan->rating_count }})</small>
                                        </div>
                                    </div>

                                    <p class="text-muted small mb-2">
                                        <i class="bi bi-geo-alt-fill text-benin-red me-1"></i>
                                        {{ $artisan->city }}, {{ $artisan->neighborhood }}
                                        @if($view == 'list' && request('lat') && request('lng') && isset($artisan->distance))
                                            <span class="ms-2 text-benin-green">
                                                • {{ round($artisan->distance, 1) }} km
                                            </span>
                                        @endif
                                    </p>

                                    <p class="small mb-3">
                                        {{ Str::limit($artisan->bio, 100) }}
                                    </p>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <small class="text-muted">
                                                <i class="bi bi-calendar-check me-1"></i>
                                                {{ $artisan->years_experience }}+ ans
                                            </small>
                                            <small class="text-muted ms-3">
                                                <i class="bi bi-eye me-1"></i>
                                                {{ $artisan->views }} vues
                                            </small>
                                        </div>
                                        <button onclick="getLocation()" class="btn btn-outline-primary">
                                            <i class="bi bi-geo-alt"></i> Trouver des artisans près de moi
                                        </button>
                                        <div class="d-flex gap-2">
                                            <a href="https://wa.me/{{ $artisan->whatsapp }}"
                                               target="_blank"
                                               class="btn btn-success btn-sm rounded-pill">
                                                <i class="bi bi-whatsapp"></i>
                                            </a>
                                            <a href="{{ route('artisans.show', $artisan) }}"
                                               class="btn btn-benin-green btn-sm rounded-pill">
                                                Voir
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="text-center py-5">
                                    <i class="bi bi-search fs-1 text-muted mb-3"></i>
                                    <h4>Aucun artisan trouvé</h4>
                                    <p class="text-muted">Essayez de modifier vos critères de recherche</p>
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
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($view == 'map')
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&callback=initMap" async defer></script>
<script>
let map;
let markers = [];

function initMap() {
    // Centrer sur le Bénin par défaut
    const center = { lat: 6.3654, lng: 2.4183 }; // Cotonou

    map = new google.maps.Map(document.getElementById('artisans-map'), {
        center: center,
        zoom: 8,
        styles: [
            {
                featureType: 'poi',
                elementType: 'labels',
                stylers: [{ visibility: 'off' }]
            }
        ]
    });

    // Ajouter les artisans
    @foreach($artisans as $artisan)
        @if($artisan->latitude && $artisan->longitude)
            addMarker({
                lat: {{ $artisan->latitude }},
                lng: {{ $artisan->longitude }},
                title: '{{ $artisan->user->name }}',
                craft: '{{ $artisan->craft }}',
                rating: {{ $artisan->rating_avg }},
                id: {{ $artisan->id }},
                city: '{{ $artisan->city }}'
            });
        @endif
    @endforeach
}

function addMarker(data) {
    // Icône personnalisée selon le métier
    const icon = {
        url: '{{ asset("images/markers/default.png") }}',
        scaledSize: new google.maps.Size(40, 40),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(20, 40)
    };

    const marker = new google.maps.Marker({
        position: { lat: data.lat, lng: data.lng },
        map: map,
        title: data.title,
        icon: icon
    });

    // InfoWindow
    const infoWindow = new google.maps.InfoWindow({
        content: `
            <div class="p-2">
                <h6 class="fw-bold mb-1">${data.title}</h6>
                <p class="small mb-1">
                    <span class="badge bg-benin-green text-white">${data.craft}</span>
                </p>
                <p class="small mb-1">
                    <i class="bi bi-star-fill text-benin-yellow"></i> ${data.rating.toFixed(1)}
                </p>
                <p class="small mb-2">${data.city}</p>
                <a href="/artisans/${data.id}" class="btn btn-benin-green btn-sm w-100">
                    Voir profil
                </a>
            </div>
        `
    });

    marker.addListener('click', () => {
        infoWindow.open(map, marker);
    });

    markers.push(marker);
}
</script>
@endif

<script>
function getUserLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                document.querySelector('input[name="lat"]').value = position.coords.latitude;
                document.querySelector('input[name="lng"]').value = position.coords.longitude;
                document.getElementById('filter-form').submit();
            },
            (error) => {
                alert('Impossible de récupérer votre position. Veuillez activer la géolocalisation.');
            }
        );
    } else {
        alert('La géolocalisation n\'est pas supportée par votre navigateur.');
    }
}

function changeView(view) {
    const url = new URL(window.location.href);
    url.searchParams.set('view', view);
    window.location.href = url.toString();
}

function resetFilters() {
    window.location.href = "{{ route('artisans.index') }}";
}

// Gestion des étoiles de notation
document.querySelectorAll('.rating-input input[type="radio"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const stars = document.querySelectorAll('.rating-input label i');
        const value = parseInt(this.value);

        stars.forEach((star, index) => {
            if (5 - index <= value) {
                star.className = 'bi bi-star-fill text-benin-yellow';
            } else {
                star.className = 'bi bi-star text-gray-300';
            }
        });
    });

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            alert("La géolocalisation n'est pas supportée par ce navigateur.");
        }
    }

    function showPosition(position) {
        const latitude = position.coords.latitude;
        const longitude = position.coords.longitude;

        // Rediriger vers la page des artisans à proximité
        window.location.href = `/artisans/nearby?latitude=${latitude}&longitude=${longitude}&radius=10`;
    }
});
</script>
@endpush
