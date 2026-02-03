@extends('layouts.app')

@section('title', $artisan->user->name . ' - Artisan AFRI-HERITAGE')

@push('styles')
<style>
    .artisan-header {
        background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.9));
        color: white;
        padding: 4rem 0;
        position: relative;
        overflow: hidden;
    }

    .artisan-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: url('{{ $artisan->photos->first()->photo_url ?? asset("images/default-artisan.jpg") }}');
        background-size: cover;
        background-position: center;
        filter: blur(5px);
        opacity: 0.5;
        z-index: 0;
    }

    .artisan-avatar {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        border: 5px solid white;
        object-fit: cover;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .nav-tabs-artisan {
        border-bottom: 2px solid var(--beige);
    }

    .nav-tabs-artisan .nav-link {
        border: none;
        color: var(--charcoal);
        font-weight: 500;
        padding: 1rem 1.5rem;
        border-radius: 0;
        position: relative;
    }

    .nav-tabs-artisan .nav-link:hover {
        color: var(--benin-green);
    }

    .nav-tabs-artisan .nav-link.active {
        color: var(--benin-green);
        background: transparent;
    }

    .nav-tabs-artisan .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--benin-green);
        border-radius: 3px 3px 0 0;
    }

    .portfolio-grid {
        display: grid;
        gap: 1rem;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    }

    .portfolio-item {
        position: relative;
        border-radius: 10px;
        overflow: hidden;
        cursor: pointer;
    }

    .portfolio-item img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .portfolio-item:hover img {
        transform: scale(1.05);
    }

    .portfolio-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.8) 0%, transparent 60%);
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        align-items: flex-end;
        padding: 1rem;
    }

    .portfolio-item:hover .portfolio-overlay {
        opacity: 1;
    }

    .map-container {
        height: 400px;
        border-radius: 10px;
        overflow: hidden;
    }

    .product-card-mini {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease;
    }

    .product-card-mini:hover {
        transform: translateY(-3px);
    }

    .product-card-mini img {
        width: 100%;
        height: 150px;
        object-fit: cover;
    }

    .contact-widget {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 100px;
    }
</style>
@endpush

@section('content')
<!-- Header -->
<section class="artisan-header">
    <div class="container position-relative z-1">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-4 mb-4">
                    <img src="{{ $artisan->photos->first()->photo_url ?? asset('images/default-artisan.jpg') }}"
                         alt="{{ $artisan->user->name }}"
                         class="artisan-avatar">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <h1 class="text-white mb-0">{{ $artisan->user->name }}</h1>
                            @if($artisan->verified)
                                <span class="badge bg-benin-green rounded-pill px-3 py-1">
                                    <i class="bi bi-patch-check-fill me-1"></i> Vérifié
                                </span>
                            @endif
                        </div>
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span class="badge bg-benin-yellow text-charcoal fs-6 px-3 py-2">
                                <i class="bi bi-tools me-1"></i>
                                {{ \App\Models\Artisan::$craftLabels[$artisan->craft] ?? $artisan->craft }}
                            </span>
                            <div class="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi {{ $i <= floor($artisan->rating_avg) ? 'bi-star-fill' : 'bi-star' }} fs-5"></i>
                                @endfor
                                <span class="text-white ms-2">({{ $artisan->rating_count }} avis)</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-4 text-white-50">
                            <div>
                                <i class="bi bi-geo-alt-fill text-benin-red me-2"></i>
                                {{ $artisan->city }}, {{ $artisan->neighborhood }}
                            </div>
                            <div>
                                <i class="bi bi-calendar-check text-benin-green me-2"></i>
                                {{ $artisan->years_experience }}+ ans d'expérience
                            </div>
                            <div>
                                <i class="bi bi-eye text-benin-yellow me-2"></i>
                                {{ $artisan->views }} vues
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="d-flex gap-2 justify-content-lg-end">
                    <a href="https://wa.me/{{ $artisan->whatsapp }}"
                       target="_blank"
                       class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-whatsapp me-2"></i> WhatsApp
                    </a>
                    <a href="tel:{{ $artisan->phone }}"
                       class="btn btn-outline-light rounded-pill px-4">
                        <i class="bi bi-telephone me-2"></i> Appeler
                    </a>
                    <button class="btn btn-benin-green rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#quoteModal">
                        <i class="bi bi-chat-left-text me-2"></i> Devis
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Content -->
<div class="container py-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Navigation Tabs -->
            <ul class="nav nav-tabs nav-tabs-artisan mb-5" id="artisanTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="about-tab" data-bs-toggle="tab" data-bs-target="#about" type="button">
                        À propos
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="portfolio-tab" data-bs-toggle="tab" data-bs-target="#portfolio" type="button">
                        Portfolio
                    </button>
                </li>
                @if($artisan->products->count() > 0)
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="products-tab" data-bs-toggle="tab" data-bs-target="#products" type="button">
                        Produits ({{ $artisan->products->count() }})
                    </button>
                </li>
                @endif
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="location-tab" data-bs-toggle="tab" data-bs-target="#location" type="button">
                        Localisation
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="artisanTabContent">
                <!-- About Tab -->
                <div class="tab-pane fade show active" id="about" role="tabpanel">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            @if($artisan->bio)
                                <div class="mb-5">
                                    <h4 class="fw-bold mb-3">Biographie</h4>
                                    <p class="text-muted mb-0">{{ $artisan->bio }}</p>
                                </div>
                            @endif

                            <!-- Spécialités -->
                            <div class="mb-5">
                                <h4 class="fw-bold mb-3">Spécialités</h4>
                                <div class="d-flex flex-wrap gap-2">
                                    @php
                                        $specialties = json_decode($artisan->specialties ?? '[]', true);
                                    @endphp
                                    @if(is_array($specialties) && count($specialties) > 0)
                                        @foreach($specialties as $specialty)
                                            <span class="badge bg-benin-green text-white px-3 py-2">
                                                {{ $specialty }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">Aucune spécialité spécifiée</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Langues parlées -->
                            <div class="mb-5">
                                <h4 class="fw-bold mb-3">Langues parlées</h4>
                                <div class="d-flex flex-wrap gap-2">
                                    @if(is_array($artisan->languages_spoken) && count($artisan->languages_spoken) > 0)
                                        @foreach($artisan->languages_spoken as $language)
                                            <span class="badge bg-light text-charcoal border px-3 py-2">
                                                <i class="bi bi-translate me-1"></i> {{ $language }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">Français seulement</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Zones d'intervention -->
                            <div>
                                <h4 class="fw-bold mb-3">Zones d'intervention</h4>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-geo-alt-fill text-benin-red me-3 fs-5"></i>
                                            <div>
                                                <div class="fw-bold">Ville principale</div>
                                                <div class="text-muted">{{ $artisan->city }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-compass text-benin-green me-3 fs-5"></i>
                                            <div>
                                                <div class="fw-bold">Quartier</div>
                                                <div class="text-muted">{{ $artisan->neighborhood ?? 'Non spécifié' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Portfolio Tab -->
                <div class="tab-pane fade" id="portfolio" role="tabpanel">
                    @if($artisan->photos->count() > 0)
                        <div class="portfolio-grid">
                            @foreach($artisan->photos as $photo)
                                <div class="portfolio-item" data-bs-toggle="modal" data-bs-target="#imageModal{{ $photo->id }}">
                                    <img src="{{ $photo->photo_url }}" alt="{{ $photo->caption }}">
                                    @if($photo->caption)
                                        <div class="portfolio-overlay">
                                            <p class="text-white mb-0">{{ $photo->caption }}</p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Image Modal -->
                                <div class="modal fade" id="imageModal{{ $photo->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-body p-0">
                                                <img src="{{ $photo->photo_url }}"
                                                     alt="{{ $photo->caption }}"
                                                     class="img-fluid w-100">
                                            </div>
                                            @if($photo->caption)
                                                <div class="modal-footer">
                                                    <p class="mb-0">{{ $photo->caption }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-images fs-1 text-muted mb-3"></i>
                            <h4>Aucune photo portfolio</h4>
                            <p class="text-muted">Cet artisan n'a pas encore ajouté de photos de ses réalisations</p>
                        </div>
                    @endif
                </div>

                <!-- Products Tab -->
                <div class="tab-pane fade" id="products" role="tabpanel">
                    @if($artisan->products->count() > 0)
                        <div class="row g-4">
                            @foreach($artisan->products as $product)
                                <div class="col-md-6">
                                    <div class="product-card-mini">
                                        <img src="{{ $product->images->first()->image_url ?? asset('images/default-product.jpg') }}"
                                             alt="{{ $product->name }}">
                                        <div class="p-3">
                                            <h6 class="fw-bold mb-2">{{ Str::limit($product->name, 40) }}</h6>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-benin-green fw-bold">
                                                    {{ $product->formatted_price }}
                                                </span>
                                                <a href="{{ route('products.show', $product) }}"
                                                   class="btn btn-sm btn-benin-green">
                                                    Voir
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Location Tab -->
                <div class="tab-pane fade" id="location" role="tabpanel">
                    @if($artisan->latitude && $artisan->longitude)
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="map-container mb-4">
                                    <div id="location-map" class="w-100 h-100"></div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="bi bi-geo-alt-fill text-benin-red me-3 fs-4"></i>
                                            <div>
                                                <div class="fw-bold">Adresse</div>
                                                <div class="text-muted">
                                                    {{ $artisan->neighborhood ?? 'Quartier non spécifié' }}, {{ $artisan->city }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="bi bi-compass text-benin-green me-3 fs-4"></i>
                                            <div>
                                                <div class="fw-bold">Coordonnées</div>
                                                <div class="text-muted">
                                                    {{ $artisan->latitude }}, {{ $artisan->longitude }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-benin-green w-100 rounded-pill" onclick="getDirections()">
                                    <i class="bi bi-signpost-split me-2"></i> Obtenir l'itinéraire
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-geo-alt fs-1 text-muted mb-3"></i>
                            <h4>Localisation non disponible</h4>
                            <p class="text-muted">Cet artisan n'a pas fourni ses coordonnées de localisation</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="contact-widget">
                <h5 class="fw-bold mb-4">Contact</h5>

                <!-- Contact Info -->
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="d-flex align-items-center justify-content-center rounded-circle"
                             style="width: 40px; height: 40px; background-color: var(--benin-green);">
                            <i class="bi bi-telephone text-white"></i>
                        </div>
                        <div class="ms-3">
                            <div class="small text-muted">Téléphone</div>
                            <div class="fw-bold">{{ $artisan->phone ?? 'Non disponible' }}</div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-3">
                        <div class="d-flex align-items-center justify-content-center rounded-circle"
                             style="width: 40px; height: 40px; background-color: #25D366;">
                            <i class="bi bi-whatsapp text-white"></i>
                        </div>
                        <div class="ms-3">
                            <div class="small text-muted">WhatsApp</div>
                            <div class="fw-bold">{{ $artisan->whatsapp }}</div>
                        </div>
                    </div>
                </div>

                <!-- Pricing Info -->
                @if($artisan->pricing_info)
                    <div class="mb-4">
                        <h6 class="fw-bold mb-2">Informations tarifaires</h6>
                        <p class="text-muted small">{{ $artisan->pricing_info }}</p>
                    </div>
                @endif

                <!-- Availability -->
                <div class="mb-4">
                    <h6 class="fw-bold mb-2">Disponibilité</h6>
                    <div class="d-flex align-items-center">
                        <div class="me-2">
                            <i class="bi bi-circle-fill text-success" style="font-size: 0.5rem;"></i>
                        </div>
                        <span>Disponible maintenant</span>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="d-grid gap-2">
                    <a href="https://wa.me/{{ $artisan->whatsapp }}"
                       target="_blank"
                       class="btn btn-success rounded-pill">
                        <i class="bi bi-whatsapp me-2"></i> Envoyer un message
                    </a>
                    <button class="btn btn-benin-green rounded-pill"
                            data-bs-toggle="modal"
                            data-bs-target="#quoteModal">
                        <i class="bi bi-chat-left-text me-2"></i> Demander un devis
                    </button>
                    <button class="btn btn-outline-benin-green rounded-pill" onclick="shareArtisan()">
                        <i class="bi bi-share me-2"></i> Partager
                    </button>
                </div>
            </div>

            <!-- Similar Artisans -->
            @if($similarArtisans->count() > 0)
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Artisans similaires</h6>
                        <div class="row g-3">
                            @foreach($similarArtisans as $similar)
                                <div class="col-12">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $similar->photos->first()->photo_url ?? asset('images/default-artisan.jpg') }}"
                                             alt="{{ $similar->user->name }}"
                                             class="rounded-circle"
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                        <div class="ms-3 flex-grow-1">
                                            <a href="{{ route('artisans.show', $similar) }}"
                                               class="text-decoration-none text-charcoal fw-bold d-block">
                                                {{ Str::limit($similar->user->name, 20) }}
                                            </a>
                                            <small class="text-muted">{{ $similar->craft_label }}</small>
                                        </div>
                                        <div class="text-end">
                                            <div class="rating-stars small">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi {{ $i <= floor($similar->rating_avg) ? 'bi-star-fill' : 'bi-star' }}"></i>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Quote Modal -->
<div class="modal fade" id="quoteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Demande de devis</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('quotes.store') }}" method="POST">
                @csrf
                <input type="hidden" name="artisan_id" value="{{ $artisan->id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Description du projet *</label>
                        <textarea name="description"
                                  class="form-control"
                                  rows="4"
                                  required
                                  placeholder="Décrivez votre projet en détail..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Budget estimé (FCFA)</label>
                        <input type="number"
                               name="budget"
                               class="form-control"
                               placeholder="Optionnel">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date souhaitée</label>
                        <input type="date"
                               name="desired_date"
                               class="form-control"
                               min="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-benin-green">Envoyer la demande</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($artisan->latitude && $artisan->longitude)
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}"></script>
<script>
function initLocationMap() {
    const location = { lat: {{ $artisan->latitude }}, lng: {{ $artisan->longitude }} };

    const map = new google.maps.Map(document.getElementById('location-map'), {
        center: location,
        zoom: 15,
        styles: [
            {
                featureType: 'poi',
                elementType: 'labels',
                stylers: [{ visibility: 'off' }]
            }
        ]
    });

    new google.maps.Marker({
        position: location,
        map: map,
        title: '{{ $artisan->user->name }}',
        icon: {
            url: '{{ asset("images/markers/pin.png") }}',
            scaledSize: new google.maps.Size(50, 50)
        }
    });
}

function getDirections() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition((position) => {
            const userLat = position.coords.latitude;
            const userLng = position.coords.longitude;
            const destination = '{{ $artisan->latitude }},{{ $artisan->longitude }}';

            window.open(`https://www.google.com/maps/dir/${userLat},${userLng}/${destination}`, '_blank');
        });
    } else {
        window.open(`https://www.google.com/maps/dir//{{ $artisan->latitude }},{{ $artisan->longitude }}`, '_blank');
    }
}

// Initialize map when tab is shown
document.getElementById('location-tab').addEventListener('shown.bs.tab', function () {
    setTimeout(initLocationMap, 100);
});
</script>
@endif

<script>
function shareArtisan() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $artisan->user->name }} - Artisan AFRI-HERITAGE',
            text: 'Découvrez cet artisan béninois sur AFRI-HERITAGE',
            url: window.location.href
        });
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(window.location.href);
        alert('Lien copié dans le presse-papier !');
    }
}
</script>
@endpush
