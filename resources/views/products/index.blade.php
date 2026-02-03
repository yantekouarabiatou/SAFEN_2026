@extends('layouts.app')

@section('title', 'Arts & Artisanat - Marketplace AFRI-HERITAGE')

@push('styles')
<style>
    .product-grid {
        display: grid;
        gap: 1.5rem;
    }

    .product-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .product-image {
        position: relative;
        height: 250px;
        overflow: hidden;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .product-card:hover .product-image img {
        transform: scale(1.05);
    }

    .product-badges {
        position: absolute;
        top: 15px;
        left: 15px;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .product-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
        color: white;
    }

    .badge-featured {
        background: var(--benin-yellow);
        color: var(--charcoal);
    }

    .badge-out-of-stock {
        background: var(--benin-red);
    }

    .badge-low-stock {
        background: var(--terracotta);
    }

    .badge-custom-order {
        background: var(--navy);
    }

    .favorite-btn {
        position: absolute;
        top: 15px;
        right: 15px;
        width: 36px;
        height: 36px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .favorite-btn:hover {
        background: var(--benin-red);
        color: white;
    }

    .favorite-btn.active {
        background: var(--benin-red);
        color: white;
    }

    .quick-view {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(255, 255, 255, 0.95);
        padding: 1rem;
        transform: translateY(100%);
        transition: transform 0.3s ease;
        display: flex;
        gap: 5px;
    }

    .product-card:hover .quick-view {
        transform: translateY(0);
    }

    .quick-view img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 5px;
        cursor: pointer;
        border: 2px solid transparent;
        transition: border-color 0.3s ease;
    }

    .quick-view img:hover {
        border-color: var(--benin-green);
    }

    .price-display {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--benin-green);
    }

    .local-name {
        font-size: 0.9rem;
        color: var(--terracotta);
        font-style: italic;
    }

    .filter-section {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .price-range {
        margin-top: 1rem;
    }

    .price-range .range-values {
        display: flex;
        justify-content: space-between;
        margin-top: 0.5rem;
        font-size: 0.875rem;
        color: var(--charcoal);
    }

    @media (min-width: 576px) {
        .product-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (min-width: 768px) {
        .product-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (min-width: 992px) {
        .product-grid {
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
                        <li class="breadcrumb-item active">Arts & Artisanat</li>
                    </ol>
                </nav>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="display-6 fw-bold text-charcoal mb-2">Arts & Artisanat</h1>
                        <p class="text-muted mb-0">Achetez des objets artisanaux authentiques du Bénin</p>
                    </div>
                    <div class="d-flex gap-2">
                        @auth
                            @if(auth()->user()->artisan)
                                <a href="{{ route('products.create') }}" class="btn btn-benin-green rounded-pill">
                                    <i class="bi bi-plus-circle me-2"></i> Ajouter un produit
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <form id="product-filters">
                <div class="row g-3">
                    <!-- Search -->
                    <div class="col-md-4">
                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="Rechercher un produit..."
                               value="{{ request('search') }}">
                    </div>

                    <!-- Category -->
                    <div class="col-md-2">
                        <select name="category" class="form-select">
                            <option value="">Catégorie</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                    {{ \App\Models\Product::$categoryLabels[$category] ?? $category }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Ethnic Origin -->
                    <div class="col-md-2">
                        <select name="ethnic_origin" class="form-select">
                            <option value="">Origine ethnique</option>
                            @foreach($ethnicOrigins as $origin)
                                <option value="{{ $origin }}" {{ request('ethnic_origin') == $origin ? 'selected' : '' }}>
                                    {{ $origin }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Material -->
                    <div class="col-md-2">
                        <select name="material" class="form-select">
                            <option value="">Matériau</option>
                            @foreach($allMaterials as $material)
                                <option value="{{ $material }}" {{ request('material') == $material ? 'selected' : '' }}>
                                    {{ $material }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sort -->
                    <div class="col-md-2">
                        <select name="sort" class="form-select">
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Populaire</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Plus récent</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                        </select>
                    </div>
                </div>

                <!-- Price Range -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <label class="form-label">Fourchette de prix (FCFA)</label>
                        <div class="d-flex gap-3">
                            <input type="number"
                                   name="min_price"
                                   class="form-control"
                                   placeholder="Min"
                                   value="{{ request('min_price') }}">
                            <input type="number"
                                   name="max_price"
                                   class="form-control"
                                   placeholder="Max"
                                   value="{{ request('max_price') }}">
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="d-flex justify-content-end gap-2 mt-3">
                    <button type="submit" class="btn btn-benin-green rounded-pill">
                        <i class="bi bi-filter me-2"></i> Appliquer les filtres
                    </button>
                    <button type="button"
                            onclick="resetFilters()"
                            class="btn btn-outline-secondary rounded-pill">
                        Réinitialiser
                    </button>
                </div>
            </form>
        </div>

        <!-- Results -->
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <span class="text-muted">
                            {{ $products->total() }} produits trouvés
                        </span>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('search.advanced') }}" class="btn btn-outline-benin-green btn-sm rounded-pill">
                            <i class="bi bi-search me-1"></i> Recherche avancée
                        </a>
                    </div>
                </div>

                <!-- Products Grid -->
                @if($products->count() > 0)
                    <div class="product-grid">
                        @foreach($products as $product)
                            <div class="product-card">
                                <!-- Image -->
                                <div class="product-image">
                                    <a href="{{ route('products.show', $product) }}">
                                        <img src="{{ $product->images->first()->image_url ?? asset('images/default-product.jpg') }}"
                                             alt="{{ $product->name }}">
                                    </a>

                                    <!-- Badges -->
                                    <div class="product-badges">
                                        @if($product->featured)
                                            <span class="product-badge badge-featured">
                                                <i class="bi bi-star-fill me-1"></i> Vedette
                                            </span>
                                        @endif
                                        @if($product->stock_status === 'out_of_stock')
                                            <span class="product-badge badge-out-of-stock">
                                                <i class="bi bi-x-circle me-1"></i> Rupture
                                            </span>
                                        @elseif($product->stock_status === 'low_stock')
                                            <span class="product-badge badge-low-stock">
                                                <i class="bi bi-exclamation-triangle me-1"></i> Stock bas
                                            </span>
                                        @endif
                                        @if($product->custom_order)
                                            <span class="product-badge badge-custom-order">
                                                <i class="bi bi-gear me-1"></i> Sur commande
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Favorite Button -->
                                    <button class="favorite-btn"
                                            data-product-id="{{ $product->id }}"
                                            onclick="toggleFavorite(this)">
                                        <i class="bi bi-heart"></i>
                                    </button>

                                    <!-- Quick View -->
                                    @if($product->images->count() > 1)
                                        <div class="quick-view">
                                            @foreach($product->images->take(4) as $image)
                                                <img src="{{ $image->image_url }}"
                                                     alt="{{ $product->name }}"
                                                     onclick="window.location.href='{{ route('products.show', $product) }}'">
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <!-- Content -->
                                <div class="p-3">
                                    <!-- Name & Audio -->
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="fw-bold mb-1">
                                                <a href="{{ route('products.show', $product) }}"
                                                   class="text-decoration-none text-charcoal">
                                                    {{ Str::limit($product->name, 35) }}
                                                </a>
                                            </h6>
                                            @if($product->name_local)
                                                <div class="local-name d-flex align-items-center">
                                                    {{ $product->name_local }}
                                                    @if($product->audio_url)
                                                        <button class="audio-btn ms-2"
                                                                onclick="playAudio('{{ $product->audio_url }}')">
                                                            <i class="bi bi-volume-up"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Ethnic Origin -->
                                    <div class="mb-2">
                                        <span class="badge bg-light text-muted border">
                                            {{ $product->ethnic_origin }}
                                        </span>
                                        <span class="badge bg-light text-muted border ms-1">
                                            {{ $product->category_label }}
                                        </span>
                                    </div>

                                    <!-- Artisan -->
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="{{ $product->artisan->photos->first()->photo_url ?? asset('images/default-artisan.jpg') }}"
                                             alt="{{ $product->artisan->user->name }}"
                                             class="rounded-circle"
                                             style="width: 24px; height: 24px; object-fit: cover;">
                                        <small class="text-muted ms-2">
                                            {{ $product->artisan->user->name }}
                                        </small>
                                    </div>

                                    <!-- Price & Actions -->
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="price-display">
                                            {{ $product->formatted_price }}
                                        </div>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('products.show', $product) }}"
                                               class="btn btn-benin-green btn-sm rounded-pill px-3">
                                                Voir
                                            </a>
                                            @if($product->stock_status !== 'out_of_stock')
                                                <button class="btn btn-outline-benin-green btn-sm rounded-pill"
                                                        onclick="addToCart({{ $product->id }})">
                                                    <i class="bi bi-cart"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($products->hasPages())
                        <div class="d-flex justify-content-center mt-5">
                            {{ $products->withQueryString()->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-search fs-1 text-muted mb-3"></i>
                        <h4>Aucun produit trouvé</h4>
                        <p class="text-muted">Essayez de modifier vos critères de recherche</p>
                        <a href="{{ route('products.index') }}" class="btn btn-benin-green rounded-pill">
                            <i class="bi bi-arrow-left me-2"></i> Voir tous les produits
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function resetFilters() {
    window.location.href = "{{ route('products.index') }}";
}

function toggleFavorite(button) {
    const productId = button.dataset.productId;
    const isActive = button.classList.contains('active');

    // Toggle UI
    button.classList.toggle('active');
    const icon = button.querySelector('i');
    icon.className = isActive ? 'bi bi-heart' : 'bi bi-heart-fill';

    // Send AJAX request
    fetch('/favorites/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            product_id: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            showToast(data.message, data.success ? 'success' : 'error');
        }
    });
}

function playAudio(audioUrl) {
    const audio = new Audio(audioUrl);
    audio.play().catch(e => console.error('Erreur de lecture audio:', e));
}

function addToCart(productId) {
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        showToast(data.message, data.success ? 'success' : 'error');
    });
}

function showToast(message, type = 'info') {
    // Implementation de toast notification
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;

    document.body.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();

    toast.addEventListener('hidden.bs.toast', () => {
        toast.remove();
    });
}

// Auto-submit form on filter change
document.querySelectorAll('#product-filters select').forEach(select => {
    select.addEventListener('change', () => {
        document.getElementById('product-filters').submit();
    });
});
</script>
@endpush
