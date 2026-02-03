@extends('layouts.app')

@section('title', $product->name . ' - AFRI-HERITAGE')

@push('styles')
<style>
    .product-gallery {
        position: relative;
    }

    .main-image {
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 1rem;
    }

    .main-image img {
        width: 100%;
        height: 400px;
        object-fit: cover;
    }

    .thumbnails {
        display: flex;
        gap: 0.5rem;
        overflow-x: auto;
        padding-bottom: 0.5rem;
    }

    .thumbnail {
        width: 80px;
        height: 80px;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        border: 2px solid transparent;
        flex-shrink: 0;
    }

    .thumbnail.active {
        border-color: var(--benin-green);
    }

    .thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-actions {
        position: sticky;
        top: 100px;
    }

    .action-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        margin-bottom: 1.5rem;
    }

    .cultural-description {
        position: relative;
        max-height: 200px;
        overflow: hidden;
    }

    .cultural-description.expanded {
        max-height: none;
    }

    .read-more {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to bottom, transparent, white);
        padding: 3rem 0 1rem;
        text-align: center;
    }

    .read-more.hidden {
        display: none;
    }

    .specs-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .spec-item {
        background: var(--beige);
        padding: 1rem;
        border-radius: 8px;
    }

    .similar-products {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Arts & Artisanat</a></li>
            <li class="breadcrumb-item active">{{ Str::limit($product->name, 30) }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Gallery -->
        <div class="col-lg-7">
            <div class="product-gallery">
                <!-- Main Image -->
                <div class="main-image">
                    <img id="main-image"
                         src="{{ $product->images->first()->image_url ?? asset('images/default-product.jpg') }}"
                         alt="{{ $product->name }}"
                         data-bs-toggle="modal"
                         data-bs-target="#imageModal">
                </div>

                <!-- Thumbnails -->
                @if($product->images->count() > 1)
                    <div class="thumbnails">
                        @foreach($product->images as $index => $image)
                            <div class="thumbnail {{ $index === 0 ? 'active' : '' }}"
                                 onclick="changeImage('{{ $image->image_url }}', this)">
                                <img src="{{ $image->image_url }}" alt="{{ $product->name }}">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Cultural Description -->
            @if($product->description_cultural)
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body">
                        <h4 class="fw-bold mb-3">Histoire & Symbolique</h4>
                        <div class="cultural-description" id="cultural-description">
                            {!! nl2br(e($product->description_cultural)) !!}
                            <div class="read-more" id="read-more">
                                <button class="btn btn-benin-green btn-sm" onclick="expandDescription()">
                                    Lire la suite <i class="bi bi-chevron-down"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Specifications -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">
                    <h4 class="fw-bold mb-3">Caractéristiques</h4>
                    <div class="specs-grid">
                        @if($product->materials && count($product->materials) > 0)
                            <div class="spec-item">
                                <div class="small text-muted">Matériaux</div>
                                <div class="fw-bold">
                                    {{ implode(', ', $product->materials) }}
                                </div>
                            </div>
                        @endif

                        @if($product->dimensions)
                            <div class="spec-item">
                                <div class="small text-muted">Dimensions</div>
                                <div class="fw-bold">
                                    {{ $product->width }} × {{ $product->height }} × {{ $product->depth }} cm
                                </div>
                            </div>
                        @endif

                        @if($product->weight)
                            <div class="spec-item">
                                <div class="small text-muted">Poids</div>
                                <div class="fw-bold">{{ $product->weight }} kg</div>
                            </div>
                        @endif

                        <div class="spec-item">
                            <div class="small text-muted">Origine ethnique</div>
                            <div class="fw-bold">{{ $product->ethnic_origin }}</div>
                        </div>

                        <div class="spec-item">
                            <div class="small text-muted">Catégorie</div>
                            <div class="fw-bold">{{ $product->category_label }}</div>
                        </div>

                        <div class="spec-item">
                            <div class="small text-muted">Disponibilité</div>
                            <div class="fw-bold">
                                @if($product->stock_status === 'in_stock')
                                    <span class="text-success">En stock</span>
                                @elseif($product->stock_status === 'low_stock')
                                    <span class="text-warning">Stock bas</span>
                                @else
                                    <span class="text-danger">Rupture</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Info & Actions -->
        <div class="col-lg-5">
            <div class="product-actions">
                <div class="action-card">
                    <h1 class="h2 fw-bold mb-2">{{ $product->name }}</h1>

                    @if($product->name_local)
                        <div class="d-flex align-items-center mb-3">
                            <span class="local-name fs-5">{{ $product->name_local }}</span>
                            @if($product->audio_url)
                                <button class="audio-btn ms-3" onclick="playAudio('{{ $product->audio_url }}')">
                                    <i class="bi bi-volume-up"></i>
                                </button>
                            @endif
                        </div>
                    @endif

                    <!-- Rating & Views -->
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="rating-stars">
                            @php
                                $avgRating = $product->reviews->avg('rating') ?? 0;
                                $reviewCount = $product->reviews->count();
                            @endphp
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi {{ $i <= floor($avgRating) ? 'bi-star-fill' : 'bi-star' }}"></i>
                            @endfor
                            @if($reviewCount > 0)
                                <span class="ms-2">({{ $reviewCount }} avis)</span>
                            @endif
                        </div>
                        <div class="text-muted">
                            <i class="bi bi-eye"></i> {{ $product->views }} vues
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="mb-4">
                        <div class="price-display fs-2">
                            {{ $product->formatted_price }}
                        </div>
                        @if($product->currency !== 'XOF')
                            <div class="text-muted small">
                                ≈ {{ $product->price_in_currency }}
                            </div>
                        @endif
                    </div>

                    <!-- Stock Status -->
                    <div class="alert {{ $product->stock_status === 'in_stock' ? 'alert-success' : ($product->stock_status === 'low_stock' ? 'alert-warning' : 'alert-danger') }} mb-4">
                        <i class="bi {{ $product->stock_status === 'in_stock' ? 'bi-check-circle' : ($product->stock_status === 'low_stock' ? 'bi-exclamation-triangle' : 'bi-x-circle') }} me-2"></i>
                        @if($product->stock_status === 'in_stock')
                            En stock • Livraison sous 3-5 jours
                        @elseif($product->stock_status === 'low_stock')
                            Plus que quelques pièces disponibles
                        @else
                            Rupture de stock • Commandes personnalisées possibles
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="d-grid gap-2 mb-4">
                        @if($product->stock_status !== 'out_of_stock')
                            <button class="btn btn-benin-green btn-lg rounded-pill"
                                    onclick="addToCart({{ $product->id }}, 1)">
                                <i class="bi bi-cart me-2"></i> Ajouter au panier
                            </button>
                        @endif

                        <button class="btn btn-outline-benin-green btn-lg rounded-pill"
                                onclick="contactArtisan()">
                            <i class="bi bi-chat-left-text me-2"></i> Contacter l'artisan
                        </button>
                    </div>

                    <!-- Secondary Actions -->
                    <div class="d-flex justify-content-center gap-3">
                        <button class="btn btn-outline-secondary"
                                onclick="toggleFavorite({{ $product->id }})"
                                id="favorite-btn">
                            <i class="bi bi-heart"></i> Favori
                        </button>

                        <button class="btn btn-outline-secondary" onclick="shareProduct()">
                            <i class="bi bi-share"></i> Partager
                        </button>

                        <button class="btn btn-outline-secondary"
                                onclick="reportProduct({{ $product->id }})">
                            <i class="bi bi-flag"></i> Signaler
                        </button>
                    </div>
                </div>

                <!-- Artisan Info -->
                <div class="action-card">
                    <h5 class="fw-bold mb-3">L'Artisan</h5>
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ $product->artisan->photos->first()->photo_url ?? asset('images/default-artisan.jpg') }}"
                             alt="{{ $product->artisan->user->name }}"
                             class="rounded-circle me-3"
                             style="width: 60px; height: 60px; object-fit: cover;">
                        <div>
                            <h6 class="fw-bold mb-1">{{ $product->artisan->user->name }}</h6>
                            <div class="text-muted small">
                                <i class="bi bi-tools me-1"></i> {{ $product->artisan->craft_label }}
                            </div>
                            <div class="rating-stars small">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi {{ $i <= floor($product->artisan->rating_avg) ? 'bi-star-fill' : 'bi-star' }}"></i>
                                @endfor
                                <span class="text-muted ms-1">({{ $product->artisan->rating_count }})</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="{{ route('artisans.show', $product->artisan) }}"
                           class="btn btn-outline-benin-green rounded-pill">
                            Voir le profil
                        </a>
                        <a href="https://wa.me/{{ $product->artisan->whatsapp }}"
                           target="_blank"
                           class="btn btn-success rounded-pill">
                            <i class="bi bi-whatsapp me-2"></i> Contacter sur WhatsApp
                        </a>
                    </div>
                </div>

                <!-- Delivery Info -->
                <div class="action-card">
                    <h5 class="fw-bold mb-3">Livraison & Retours</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="bi bi-truck text-benin-green me-2"></i>
                            <strong>Livraison :</strong> 3-5 jours ouvrables
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-arrow-left-right text-benin-green me-2"></i>
                            <strong>Retours :</strong> 14 jours après réception
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-shield-check text-benin-green me-2"></i>
                            <strong>Garantie :</strong> Authenticité garantie
                        </li>
                        <li>
                            <i class="bi bi-globe text-benin-green me-2"></i>
                            <strong>International :</strong> Livraison mondiale
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Similar Products -->
    @if($similarProducts->count() > 0)
        <div class="row mt-5">
            <div class="col-12">
                <h3 class="fw-bold mb-4">Produits similaires</h3>
                <div class="similar-products">
                    @foreach($similarProducts as $similar)
                        <div class="card border-0 shadow-sm">
                            <img src="{{ $similar->images->first()->image_url ?? asset('images/default-product.jpg') }}"
                                 alt="{{ $similar->name }}"
                                 class="card-img-top"
                                 style="height: 150px; object-fit: cover;">
                            <div class="card-body">
                                <h6 class="card-title fw-bold">
                                    <a href="{{ route('products.show', $similar) }}" class="text-decoration-none text-charcoal">
                                        {{ Str::limit($similar->name, 30) }}
                                    </a>
                                </h6>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-benin-green fw-bold">
                                        {{ $similar->formatted_price }}
                                    </span>
                                    <a href="{{ route('products.show', $similar) }}" class="btn btn-sm btn-benin-green">
                                        Voir
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Same Artisan Products -->
    @if($sameArtisanProducts->count() > 0)
        <div class="row mt-5">
            <div class="col-12">
                <h3 class="fw-bold mb-4">Autres créations de {{ $product->artisan->user->name }}</h3>
                <div class="similar-products">
                    @foreach($sameArtisanProducts as $other)
                        <div class="card border-0 shadow-sm">
                            <img src="{{ $other->images->first()->image_url ?? asset('images/default-product.jpg') }}"
                                 alt="{{ $other->name }}"
                                 class="card-img-top"
                                 style="height: 150px; object-fit: cover;">
                            <div class="card-body">
                                <h6 class="card-title fw-bold">
                                    <a href="{{ route('products.show', $other) }}" class="text-decoration-none text-charcoal">
                                        {{ Str::limit($other->name, 30) }}
                                    </a>
                                </h6>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-benin-green fw-bold">
                                        {{ $other->formatted_price }}
                                    </span>
                                    <a href="{{ route('products.show', $other) }}" class="btn btn-sm btn-benin-green">
                                        Voir
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body p-0 position-relative">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3"
                        data-bs-dismiss="modal"></button>
                <img id="modal-image" src="" class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function changeImage(src, element) {
    document.getElementById('main-image').src = src;
    document.getElementById('modal-image').src = src;

    // Update active thumbnail
    document.querySelectorAll('.thumbnail').forEach(thumb => {
        thumb.classList.remove('active');
    });
    element.classList.add('active');
}

function expandDescription() {
    const description = document.getElementById('cultural-description');
    const readMore = document.getElementById('read-more');

    description.classList.add('expanded');
    readMore.classList.add('hidden');
}

function playAudio(audioUrl) {
    const audio = new Audio(audioUrl);
    audio.play().catch(e => console.error('Erreur de lecture audio:', e));
}

function toggleFavorite(productId) {
    const button = document.getElementById('favorite-btn');
    const icon = button.querySelector('i');

    fetch('/favorites/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            product_id: productId,
            type: 'product'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            icon.className = data.is_favorite ? 'bi bi-heart-fill' : 'bi bi-heart';
            button.classList.toggle('text-danger', data.is_favorite);
        }
    });
}

function addToCart(productId, quantity = 1) {
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Produit ajouté au panier !', 'success');
        } else {
            showToast(data.message, 'error');
        }
    });
}

function contactArtisan() {
    const message = `Bonjour ${encodeURIComponent('{{ $product->artisan->user->name }}')},\n\nJe suis intéressé par votre produit "${encodeURIComponent($product->name)}".\n\nPouvez-vous m'en dire plus ?`;
    const whatsappUrl = `https://wa.me/{{ $product->artisan->whatsapp }}?text=${encodeURIComponent(message)}`;
    window.open(whatsappUrl, '_blank');
}

function shareProduct() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $product->name }} - AFRI-HERITAGE',
            text: 'Découvrez ce produit artisanal béninois sur AFRI-HERITAGE',
            url: window.location.href
        });
    } else {
        navigator.clipboard.writeText(window.location.href);
        showToast('Lien copié dans le presse-papier !', 'success');
    }
}

function reportProduct(productId) {
    const reason = prompt('Veuillez indiquer la raison de votre signalement :');
    if (reason) {
        fetch('/reports', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                product_id: productId,
                reason: reason
            })
        })
        .then(response => response.json())
        .then(data => {
            showToast(data.message, data.success ? 'success' : 'error');
        });
    }
}

// Initialize modal image
document.addEventListener('DOMContentLoaded', function() {
    const mainImage = document.getElementById('main-image');
    document.getElementById('modal-image').src = mainImage.src;
});
</script>
@endpush
