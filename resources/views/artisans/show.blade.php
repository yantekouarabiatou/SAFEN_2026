@extends('layouts.app')

@section('title', $artisan->user->name . ' - Artisan AFRI-HERITAGE')

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

    .artisan-hero {
        background: linear-gradient(135deg, var(--benin-green) 0%, var(--benin-red) 100%);
        color: white;
        padding: 6rem 0 4rem;
        position: relative;
        overflow: hidden;
        border-radius: 0 0 30px 30px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.25);
    }

    .artisan-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.07'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        opacity: 0.12;
    }

    .avatar-large {
        width: 160px;
        height: 160px;
        border: 6px solid white;
        border-radius: 50%;
        object-fit: cover;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.35);
    }

    .badge-craft {
        background: var(--benin-yellow);
        color: var(--charcoal);
        font-weight: 700;
        padding: 0.6rem 1.2rem;
        border-radius: 50px;
        font-size: 1.1rem;
    }

    .nav-tabs-custom {
        border-bottom: 3px solid var(--beige);
    }

    .nav-tabs-custom .nav-link {
        color: var(--charcoal);
        font-weight: 600;
        padding: 1rem 1.8rem;
        border: none;
        border-radius: 0;
        position: relative;
        transition: all 0.3s ease;
    }

    .nav-tabs-custom .nav-link:hover {
        color: var(--benin-green);
        background: rgba(0, 150, 57, 0.05);
    }

    .nav-tabs-custom .nav-link.active {
        color: var(--benin-green);
        background: transparent;
    }

    .nav-tabs-custom .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -3px;
        left: 50%;
        transform: translateX(-50%);
        width: 60%;
        height: 4px;
        background: var(--benin-green);
        border-radius: 4px 4px 0 0;
    }

    .portfolio-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
    }

    .portfolio-item {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.08);
        transition: all 0.4s ease;
        position: relative;
    }

    .portfolio-item:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 50px rgba(0, 150, 57, 0.18);
    }

    .portfolio-item img {
        width: 100%;
        height: 240px;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .portfolio-item:hover img {
        transform: scale(1.08);
    }

    .contact-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 10px 35px rgba(0, 0, 0, 0.1);
        border: 1px solid var(--beige);
        position: sticky;
        top: 2rem;
    }

    .btn-contact {
        background: linear-gradient(135deg, var(--benin-green) 0%, #007a2e 100%);
        border: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-contact:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0, 150, 57, 0.3);
    }

    .btn-whatsapp {
        background: #25D366;
        border: none;
        color: white;
    }

    .btn-devis {
        background: var(--benin-red);
        border: none;
        color: white;
    }

    .map-wrapper {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
    }

    .empty-portfolio {
        background: var(--light-gray);
        border-radius: 16px;
        padding: 5rem 2rem;
        text-align: center;
        border: 2px dashed var(--beige);
    }
</style>
@endpush

@section('content')
<!-- Hero Header -->
<section class="artisan-hero">
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-5 mb-5">
                    <!-- Avatar -->
                    @if($artisan->photos->first())
                        <img src="{{ $artisan->photos->first()->full_url }}"
                             alt="{{ $artisan->user->name }}"
                             class="avatar-large">
                    @else
                        <div class="avatar-large d-flex align-items-center justify-content-center bg-white text-benin-green fw-bold fs-1">
                            {{ getInitials($artisan->user->name ?? 'A') }}
                        </div>
                    @endif

                    <div>
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <h1 class="display-5 fw-bold text-white mb-0">
                                {{ $artisan->user->name }}
                            </h1>
                            @if($artisan->verified)
                                <span class="badge bg-white text-benin-green px-4 py-2 fs-5">
                                    <i class="bi bi-patch-check-fill me-2"></i>Vérifié
                                </span>
                            @endif
                        </div>

                        <div class="d-flex flex-wrap gap-3 mb-4">
                            <span class="badge badge-craft fs-5 px-4 py-2">
                                {{ $artisan->craft_label }}
                            </span>

                            <div class="d-flex align-items-center gap-2 text-white fs-5">
                                <i class="bi bi-star-fill text-warning"></i>
                                {{ number_format($artisan->rating_avg ?? 0, 1) }}
                                <span class="text-white-75">({{ $artisan->rating_count ?? 0 }})</span>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-4 text-white-75 fs-5">
                            <div>
                                <i class="bi bi-geo-alt-fill text-benin-yellow me-2"></i>
                                {{ $artisan->city }}
                                @if($artisan->neighborhood) • {{ $artisan->neighborhood }} @endif
                            </div>
                            <div>
                                <i class="bi bi-calendar-check text-benin-yellow me-2"></i>
                                {{ $artisan->years_experience }}+ ans
                            </div>
                            <div>
                                <i class="bi bi-eye text-benin-yellow me-2"></i>
                                {{ number_format($artisan->views) }} vues
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="col-lg-4 text-lg-end">
                <div class="d-flex flex-column gap-3">
                    @if($artisan->whatsapp)
                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $artisan->whatsapp) }}"
                           target="_blank"
                           class="btn btn-whatsapp btn-lg rounded-pill py-3">
                            <i class="bi bi-whatsapp fs-4 me-2"></i> Contacter sur WhatsApp
                        </a>
                    @endif

                    <button class="btn btn-devis btn-lg rounded-pill py-3"
                            data-bs-toggle="modal"
                            data-bs-target="#quoteModal">
                        <i class="bi bi-chat-left-text fs-4 me-2"></i> Demander un devis
                    </button>

                    <button class="btn btn-outline-light btn-lg rounded-pill py-3"
                            onclick="shareArtisan()">
                        <i class="bi bi-share fs-4 me-2"></i> Partager
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container py-5">
    <div class="row g-5">
        <!-- Contenu principal -->
        <div class="col-lg-8">
            <ul class="nav nav-tabs-custom mb-5" id="artisanTab" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="about-tab" data-bs-toggle="tab" data-bs-target="#about">
                        À propos
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="portfolio-tab" data-bs-toggle="tab" data-bs-target="#portfolio">
                        Portfolio
                    </button>
                </li>
                @if($artisan->products->count() > 0)
                <li class="nav-item">
                    <button class="nav-link" id="products-tab" data-bs-toggle="tab" data-bs-target="#products">
                        Produits ({{ $artisan->products->count() }})
                    </button>
                </li>
                @endif
                <li class="nav-item">
                    <button class="nav-link" id="location-tab" data-bs-toggle="tab" data-bs-target="#location">
                        Localisation
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                <!-- À propos -->
                <div class="tab-pane fade show active" id="about">
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        @if($artisan->bio)
                            <h4 class="fw-bold mb-4">Biographie</h4>
                            <p class="text-muted lh-lg">{{ $artisan->bio }}</p>
                        @endif

                        <hr class="my-4">

                        <div class="row g-4">
                            <div class="col-md-6">
                                <h5 class="fw-bold mb-3">Spécialités</h5>
                                <div class="d-flex flex-wrap gap-2">
                                    @if($artisan->specialties && count(json_decode($artisan->specialties, true)) > 0)
                                        @foreach(json_decode($artisan->specialties, true) as $spec)
                                            <span class="badge bg-benin-green px-3 py-2">{{ $spec }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">Non spécifié</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h5 class="fw-bold mb-3">Langues parlées</h5>
                                <div class="d-flex flex-wrap gap-2">
                                    @if(is_array($artisan->languages_spoken) && count($artisan->languages_spoken) > 0)
                                        @foreach($artisan->languages_spoken as $lang)
                                            <span class="badge bg-light border px-3 py-2">{{ $lang }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">Français</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Portfolio -->
                <div class="tab-pane fade" id="portfolio">
                    @if($artisan->photos->count() > 0)
                        <div class="portfolio-grid">
                            @foreach($artisan->photos as $photo)
                                <div class="portfolio-item" data-bs-toggle="modal" data-bs-target="#imgModal{{ $photo->id }}">
                                    <img src="{{ $photo->full_url }}" alt="{{ $photo->caption ?? 'Réalisation' }}">
                                    @if($photo->caption)
                                        <div class="portfolio-overlay">
                                            <p class="text-white mb-0 fw-bold">{{ $photo->caption }}</p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Modal -->
                                <div class="modal fade" id="imgModal{{ $photo->id }}">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content border-0">
                                            <img src="{{ $photo->full_url }}" class="w-100" alt="{{ $photo->caption }}">
                                            @if($photo->caption)
                                                <div class="modal-footer bg-white border-0">
                                                    <p class="mb-0">{{ $photo->caption }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-portfolio">
                            <i class="bi bi-images fs-1 text-muted mb-3 d-block"></i>
                            <h5 class="text-muted">Aucune photo dans le portfolio</h5>
                        </div>
                    @endif
                </div>

                <!-- Produits -->
                <div class="tab-pane fade" id="products">
                    @if($artisan->products->count() > 0)
                        <div class="row g-4">
                            @foreach($artisan->products as $product)
                                <div class="col-md-6">
                                    <div class="product-card-mini">
                                        <img src="{{ $product->images->first()?->full_url ?? asset('images/default-product.jpg') }}"
                                             alt="{{ $product->name }}">
                                        <div class="p-4">
                                            <h5 class="fw-bold mb-2">{{ Str::limit($product->name, 45) }}</h5>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="fs-4 fw-bold text-benin-green">
                                                    {{ $product->formatted_price }}
                                                </span>
                                                <a href="{{ route('products.show', $product) }}"
                                                   class="btn btn-sm btn-benin rounded-pill px-4">
                                                    Voir
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-basket fs-1 text-muted mb-3 d-block"></i>
                            <h5>Aucun produit publié pour le moment</h5>
                        </div>
                    @endif
                </div>

                <!-- Localisation -->
                <div class="tab-pane fade" id="location">
                    @if($artisan->latitude && $artisan->longitude)
                        <div class="map-wrapper">
                            <div id="map" style="height: 450px;"></div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-geo-alt fs-1 text-muted mb-3 d-block"></i>
                            <h5>Localisation non disponible</h5>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="contact-card">
                <h4 class="fw-bold mb-4">Contacter l'artisan</h4>

                @if($artisan->whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $artisan->whatsapp) }}"
                       class="btn btn-whatsapp w-100 mb-3 py-3 fs-5">
                        <i class="bi bi-whatsapp fs-4 me-2"></i> Message WhatsApp
                    </a>
                @endif

                @if($artisan->phone)
                    <a href="tel:{{ $artisan->phone }}"
                       class="btn btn-outline-benin-green w-100 mb-3 py-3 fs-5">
                        <i class="bi bi-telephone fs-4 me-2"></i> Appeler
                    </a>
                @endif

                <button class="btn btn-devis w-100 py-3 fs-5"
                        data-bs-toggle="modal"
                        data-bs-target="#quoteModal">
                    <i class="bi bi-chat-left-text fs-4 me-2"></i> Demander un devis
                </button>

                @if($artisan->pricing_info)
                    <div class="mt-4">
                        <h6 class="fw-bold mb-2">Tarifs indicatifs</h6>
                        <p class="text-muted small">{{ $artisan->pricing_info }}</p>
                    </div>
                @endif
            </div>

            <!-- Artisans similaires -->
            @if($similarArtisans->count() > 0)
                <div class="card border-0 shadow-sm mt-4 rounded-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-4">Artisans similaires</h5>
                        @foreach($similarArtisans as $similar)
                            <div class="d-flex align-items-center mb-4">
                                @if($similar->photos->first())
                                    <img src="{{ $similar->photos->first()->full_url }}"
                                         class="rounded-circle me-3"
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 bg-benin-green text-white fw-bold"
                                         style="width: 60px; height: 60px; font-size: 1.4rem;">
                                        {{ getInitials($similar->user->name ?? 'A') }}
                                    </div>
                                @endif

                                <div class="flex-grow-1">
                                    <a href="{{ route('artisans.show', $similar) }}"
                                       class="fw-bold text-decoration-none d-block">
                                        {{ Str::limit($similar->user->name, 22) }}
                                    </a>
                                    <small class="text-muted">{{ $similar->craft_label }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Devis -->
<div class="modal fade" id="quoteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Demande de devis</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('quotes.store') }}" method="POST">
                @csrf
                <input type="hidden" name="artisan_id" value="{{ $artisan->id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Description du projet *</label>
                        <textarea name="description" class="form-control" rows="4" required
                                  placeholder="Décrivez précisément ce dont vous avez besoin..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Budget estimé (FCFA)</label>
                        <input type="number" name="budget" class="form-control" placeholder="Optionnel">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-benin px-5">Envoyer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($artisan->latitude && $artisan->longitude)
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&callback=initMap" async defer></script>
<script>
function initMap() {
    const pos = { lat: {{ $artisan->latitude }}, lng: {{ $artisan->longitude }} };
    const map = new google.maps.Map(document.getElementById('map'), {
        center: pos,
        zoom: 15,
        styles: [
            { featureType: "poi", elementType: "labels", stylers: [{ visibility: "off" }] }
        ]
    });

    new google.maps.Marker({
        position: pos,
        map,
        title: "{{ $artisan->user->name }}",
        icon: {
            url: "{{ asset('images/markers/pin-green.png') }}",
            scaledSize: new google.maps.Size(48, 48)
        }
    });
}

document.getElementById('location-tab')?.addEventListener('shown.bs.tab', () => {
    setTimeout(initMap, 200);
});

function getDirections() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(pos => {
            const dest = '{{ $artisan->latitude }},{{ $artisan->longitude }}';
            window.open(`https://www.google.com/maps/dir/${pos.coords.latitude},${pos.coords.longitude}/${dest}`, '_blank');
        }, () => {
            window.open(`https://www.google.com/maps/dir//{{ $artisan->latitude }},{{ $artisan->longitude }}`, '_blank');
        });
    }
}
</script>
@endif

<script>
function shareArtisan() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $artisan->user->name }} - AFRI-HERITAGE',
            text: 'Découvrez cet artisan talentueux du Bénin',
            url: window.location.href
        });
    } else {
        navigator.clipboard.writeText(window.location.href);
        alert('Lien copié !');
    }
}
</script>
@endpush