@extends('layouts.app')

@section('title', 'Artisans & Services - AFRI-HERITAGE')

@section('content')
<!-- Hero Section -->
<section class="hero-section"
         style="background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.9)), url('https://images.unsplash.com/photo-1581094794329-c8112a89af12?q=80&w=2070');">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-white text-opacity-75">Accueil</a>
                </li>
                <li class="breadcrumb-item active text-white" aria-current="page">Artisans & Services</li>
            </ol>
        </nav>
        <h1 class="text-white fw-bold mb-3">
            <i class="bi bi-tools me-2 text-benin-yellow"></i>
            Artisans & Services
        </h1>
        <p class="text-white text-opacity-75 lead mb-4">
            Trouvez des artisans qualifiés et des prestataires de services près de chez vous. Contact direct, avis vérifiés.
        </p>

        <!-- Quick Stats -->
        <div class="d-flex gap-4 flex-wrap">
            <div class="text-center">
                <h3 class="text-benin-yellow fw-bold mb-0">{{ $artisans->total() }}+</h3>
                <small class="text-white-50">Artisans inscrits</small>
            </div>
            <div class="text-center">
                <h3 class="text-benin-yellow fw-bold mb-0">{{ $cities->count() }}+</h3>
                <small class="text-white-50">Villes couvertes</small>
            </div>
            <div class="text-center">
                <h3 class="text-benin-yellow fw-bold mb-0">{{ $crafts->count() }}+</h3>
                <small class="text-white-50">Métiers différents</small>
            </div>
            <div class="text-center">
                <h3 class="text-benin-yellow fw-bold mb-0">98%</h3>
                <small class="text-white-50">Clients satisfaits</small>
            </div>
        </div>
    </div>
</section>

<div class="container py-5">
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3 mb-4">
            <div class="filter-sidebar sticky-top" style="top: 20px;">
                <h5 class="fw-bold mb-4">
                    <i class="bi bi-funnel me-2"></i> Filtres
                </h5>

                <form id="filter-form" method="GET" action="{{ route('artisans.index') }}">
                    <!-- Search -->
                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Recherche</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Nom, métier, ville...">
                            <button class="btn btn-benin-green" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Craft Filter -->
                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Métier</label>
                        <select class="form-select" name="craft" onchange="this.form.submit()">
                            <option value="">Tous les métiers</option>
                            @foreach($crafts as $key => $label)
                                <option value="{{ $key }}" {{ request('craft') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- City Filter -->
                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Ville</label>
                        <select class="form-select" name="city" onchange="this.form.submit()">
                            <option value="">Toutes les villes</option>
                            @foreach($cities as $city)
                                <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                    {{ $city }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Verified Filter -->
                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Statut vérification</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="verified" value="true"
                                   id="verified-check" {{ request('verified') == 'true' ? 'checked' : '' }}
                                   onchange="this.form.submit()">
                            <label class="form-check-label" for="verified-check">
                                Artisans vérifiés uniquement
                            </label>
                        </div>
                    </div>

                    <!-- Rating Filter -->
                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Note minimum</label>
                        <div class="d-flex gap-1">
                            @for($i = 5; $i >= 1; $i--)
                                <button type="button" class="btn btn-sm {{ request('rating_min') == $i ? 'btn-warning' : 'btn-outline-warning' }}"
                                        onclick="setRatingFilter({{ $i }})">
                                    {{ $i }} <i class="bi bi-star-fill"></i>
                                </button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating_min" id="rating-min" value="{{ request('rating_min') }}">
                    </div>

                    <!-- Sort -->
                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Trier par</label>
                        <select class="form-select" name="sort" onchange="this.form.submit()">
                            <option value="rating_avg" {{ request('sort') == 'rating_avg' ? 'selected' : '' }}>Meilleures notes</option>
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Plus récents</option>
                            <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>Plus populaires</option>
                            <option value="years_experience" {{ request('sort') == 'years_experience' ? 'selected' : '' }}>Plus d'expérience</option>
                        </select>
                    </div>

                    <!-- Reset -->
                    <a href="{{ route('artisans.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Réinitialiser
                    </a>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- View Toggle & Results -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <div>
                    <span class="text-muted">{{ $artisans->total() }} artisans trouvés</span>
                    @if(request()->anyFilled(['craft', 'city', 'search', 'verified', 'rating_min']))
                        <span class="badge bg-benin-green ms-2">Filtres actifs</span>
                    @endif
                </div>
                <div class="d-flex gap-2">
                    <!-- View Mode -->
                    <div class="btn-group" role="group" aria-label="View mode">
                        <a href="{{ request()->fullUrlWithQuery(['view' => 'grid']) }}"
                           class="btn btn-sm {{ $viewMode == 'grid' ? 'btn-benin-green' : 'btn-outline-secondary' }}">
                            <i class="bi bi-grid-3x3-gap"></i>
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['view' => 'list']) }}"
                           class="btn btn-sm {{ $viewMode == 'list' ? 'btn-benin-green' : 'btn-outline-secondary' }}">
                            <i class="bi bi-list"></i>
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['view' => 'map']) }}"
                           class="btn btn-sm {{ $viewMode == 'map' ? 'btn-benin-green' : 'btn-outline-secondary' }}">
                            <i class="bi bi-map"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Map View -->
            @if($viewMode == 'map')
                <div class="card mb-4">
                    <div class="card-body p-0" style="height: 400px;">
                        <div id="artisans-map" class="h-100 w-100 bg-secondary d-flex align-items-center justify-content-center">
                            <div class="text-center text-white">
                                <i class="bi bi-map fs-1 mb-2"></i>
                                <p class="mb-0">Carte interactive Google Maps</p>
                                <small class="opacity-75">Intégration avec géolocalisation</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Grid/List View -->
            @if($artisans->count() > 0)
                <div class="{{ $viewMode == 'grid' ? 'row g-4' : '' }}">
                    @foreach($artisans as $artisan)
                        @if($viewMode == 'list')
                            <!-- List View -->
                            <div class="card card-artisan mb-3">
                                <div class="row g-0">
                                    <div class="col-md-3">
                                        <img src="{{ $artisan->photos->first()->photo_url ?? asset('images/default-artisan.jpg') }}"
                                             alt="{{ $artisan->user->name }}"
                                             class="img-fluid rounded-start h-100"
                                             style="object-fit: cover; min-height: 200px;">
                                    </div>
                                    <div class="col-md-9">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h5 class="card-title mb-1">
                                                        {{ $artisan->user->name }}
                                                        @if($artisan->verified)
                                                            <i class="bi bi-patch-check-fill text-benin-green ms-2"></i>
                                                        @endif
                                                    </h5>
                                                    <p class="text-terracotta fw-semibold mb-1">
                                                        {{ $artisan->craft_label }}
                                                    </p>
                                                    <p class="text-muted small mb-2">
                                                        <i class="bi bi-geo-alt me-1"></i>
                                                        {{ $artisan->neighborhood ? $artisan->neighborhood . ', ' : '' }}{{ $artisan->city }}
                                                    </p>
                                                </div>
                                                <div class="text-end">
                                                    <div class="rating-stars">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="bi {{ $i <= $artisan->rating_avg ? 'bi-star-fill' : 'bi-star' }}"></i>
                                                        @endfor
                                                        <span class="fw-bold ms-1">{{ number_format($artisan->rating_avg, 1) }}</span>
                                                        <span class="text-muted">({{ $artisan->rating_count }})</span>
                                                    </div>
                                                </div>
                                            </div>
                                            @if($artisan->bio)
                                                <p class="text-muted small mb-3">
                                                    {{ Str::limit($artisan->bio, 150) }}
                                                </p>
                                            @endif
                                            <p class="text-muted small mb-3">
                                                <i class="bi bi-clock-history me-1"></i>
                                                {{ $artisan->years_experience ?? 'Non spécifié' }} ans d'expérience
                                            </p>
                                            <div class="d-flex gap-2">
                                                @if($artisan->whatsapp)
                                                    <a href="https://wa.me/{{ $artisan->whatsapp }}" target="_blank"
                                                       class="btn btn-success btn-sm">
                                                        <i class="bi bi-whatsapp me-1"></i> WhatsApp
                                                    </a>
                                                @endif
                                                @if($artisan->phone)
                                                    <a href="tel:{{ $artisan->phone }}" class="btn btn-outline-secondary btn-sm">
                                                        <i class="bi bi-telephone me-1"></i> Appeler
                                                    </a>
                                                @endif
                                                <a href="{{ route('artisans.show', $artisan) }}" class="btn btn-benin-green btn-sm">
                                                    Voir profil <i class="bi bi-arrow-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Grid View -->
                            <div class="col-md-6 col-xl-4">
                                <div class="card card-artisan h-100">
                                    <div class="position-relative">
                                        <img src="{{ $artisan->photos->first()->photo_url ?? asset('images/default-artisan.jpg') }}"
                                             alt="{{ $artisan->user->name }}"
                                             class="card-img-top"
                                             style="height: 180px; object-fit: cover;">
                                        @if($artisan->verified)
                                            <span class="position-absolute top-0 end-0 m-2 badge bg-benin-green">
                                                <i class="bi bi-patch-check me-1"></i> Vérifié
                                            </span>
                                        @endif
                                    </div>
                                    <div class="card-body text-center">
                                        <h6 class="card-title mb-1">{{ $artisan->user->name }}</h6>
                                        <p class="text-terracotta fw-semibold mb-1">{{ $artisan->craft_label }}</p>
                                        <p class="text-muted small mb-2">
                                            <i class="bi bi-geo-alt me-1"></i>
                                            {{ $artisan->city }}
                                        </p>
                                        <div class="rating-stars mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi {{ $i <= $artisan->rating_avg ? 'bi-star-fill' : 'bi-star' }}"></i>
                                            @endfor
                                            <span class="text-muted ms-1 small">({{ $artisan->rating_count }})</span>
                                        </div>
                                        <p class="text-muted small mb-3">
                                            {{ $artisan->years_experience ?? 'Non spécifié' }} ans d'expérience
                                        </p>
                                        <div class="d-flex gap-2">
                                            @if($artisan->whatsapp)
                                                <a href="https://wa.me/{{ $artisan->whatsapp }}" target="_blank"
                                                   class="btn btn-success btn-sm flex-fill">
                                                    <i class="bi bi-whatsapp"></i>
                                                </a>
                                            @endif
                                            <a href="{{ route('artisans.show', $artisan) }}" class="btn btn-benin-green btn-sm flex-fill">
                                                Profil <i class="bi bi-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $artisans->withQueryString()->links() }}
                </div>
            @else
                <!-- No Results -->
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-search fs-1 text-muted"></i>
                    </div>
                    <h4 class="text-muted mb-3">Aucun artisan trouvé</h4>
                    <p class="text-muted mb-4">
                        Aucun artisan ne correspond à vos critères de recherche. Essayez de modifier vos filtres.
                    </p>
                    <a href="{{ route('artisans.index') }}" class="btn btn-benin-green">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Réinitialiser les filtres
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Become Artisan CTA -->
<section class="py-5 bg-light-green">
    <div class="container text-center">
        <h2 class="fw-bold text-charcoal mb-3">Vous êtes artisan ?</h2>
        <p class="text-muted mb-4" style="max-width: 600px; margin: 0 auto;">
            Rejoignez notre plateforme pour augmenter votre visibilité, trouver plus de clients et développer votre activité.
        </p>
        <div class="d-flex gap-3 justify-content-center">
            <a href="{{ route('register') }}?role=artisan" class="btn btn-benin-green btn-lg">
                <i class="bi bi-person-plus me-2"></i> Devenir artisan
            </a>
            <a href="{{ route('faq') }}" class="btn btn-outline-benin-green btn-lg">
                <i class="bi bi-question-circle me-2"></i> En savoir plus
            </a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
function setRatingFilter(rating) {
    document.getElementById('rating-min').value = rating;
    document.getElementById('filter-form').submit();
}

// Initialize map if in map view
@if($viewMode == 'map')
function initMap() {
    // This is a placeholder for Google Maps integration
    const mapElement = document.getElementById('artisans-map');
    if (!mapElement) return;

    // You'll need to replace with actual Google Maps initialization
    // and add markers for each artisan
    console.log('Map initialization would happen here');
}

// Load Google Maps API and initialize
if (typeof google === 'undefined') {
    const script = document.createElement('script');
    script.src = `https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap`;
    script.async = true;
    script.defer = true;
    document.head.appendChild(script);
} else {
    initMap();
}
@endif
</script>
@endpush
