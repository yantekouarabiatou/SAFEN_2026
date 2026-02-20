@extends('layouts.app')

@section('title', 'Arts & Artisanat - AFRI-HERITAGE')

@push('styles')
    <style>
        :root {
            --benin-green: #009639;
            --benin-yellow: #FCD116;
            --benin-red: #E8112D;
            --terracotta: #D4774E;
            --beige: #F5E6D3;
            --charcoal: #2C3E50;
        }

        /* Hero Section avec gradient */
        .hero-products {
            background: linear-gradient(135deg, var(--benin-green) 0%, var(--benin-red) 100%);
            padding: 4rem 0 3rem;
            margin-bottom: 3rem;
            position: relative;
            overflow: hidden;
        }

        .hero-products::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.3;
        }

        .hero-products .container {
            position: relative;
            z-index: 1;
        }

        .hero-products h1 {
            color: white;
            font-size: 3rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            margin-bottom: 1rem;
        }

        .hero-products .lead {
            color: rgba(255, 255, 255, 0.95);
            font-size: 1.2rem;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        /* Filter Section */
        .filter-section {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 3rem;
        }

        .filter-section input,
        .filter-section select {
            border: 2px solid var(--beige);
            border-radius: 50px;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
        }

        .filter-section input:focus,
        .filter-section select:focus {
            border-color: var(--benin-red);
            box-shadow: 0 0 0 0.2rem rgba(232, 17, 45, 0.1);
        }

        .filter-section .btn {
            border-radius: 50px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .filter-section .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 150, 57, 0.3);
        }

        /* Product Grid */
        .product-grid {
            display: grid;
            gap: 2rem;
            margin-bottom: 3rem;
        }

        /* Product Card amélioré */
        .product-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .product-image {
            height: 250px;
            overflow: hidden;
            position: relative;
        }

        .product-image::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 50%;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.5), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .product-card:hover .product-image::after {
            opacity: 1;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .product-card:hover .product-image img {
            transform: scale(1.1);
        }

        /* Product Badges */
        .product-badges {
            position: absolute;
            top: 15px;
            left: 15px;
            display: flex;
            flex-direction: column;
            gap: 5px;
            z-index: 2;
        }

        .product-badge {
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            color: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-featured {
            background: linear-gradient(135deg, var(--benin-yellow) 0%, #e8b000 100%);
            color: var(--charcoal);
        }

        .badge-out-of-stock {
            background: linear-gradient(135deg, var(--benin-red) 0%, #c40e26 100%);
        }

        .badge-low-stock {
            background: linear-gradient(135deg, var(--terracotta) 0%, #b85d32 100%);
        }

        .badge-custom-order {
            background: linear-gradient(135deg, var(--benin-green) 0%, #007a2e 100%);
        }

        /* Favorite Button */
        .favorite-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 3;
            cursor: pointer;
        }

        .favorite-btn:hover {
            background: var(--benin-red);
            color: white;
            transform: scale(1.1);
        }

        .favorite-btn.active {
            background: var(--benin-red);
            color: white;
        }

        .favorite-btn i {
            font-size: 1.1rem;
        }

        /* Quick View */
        .quick-view {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem;
            transform: translateY(100%);
            transition: transform 0.3s ease;
            display: flex;
            gap: 8px;
            justify-content: center;
            z-index: 2;
        }

        .product-card:hover .quick-view {
            transform: translateY(0);
        }

        .quick-view img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 10px;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .quick-view img:hover {
            border-color: var(--benin-green);
            transform: scale(1.05);
        }

        /* Audio Button */
        .audio-btn {
            background: linear-gradient(135deg, var(--benin-yellow) 0%, var(--terracotta) 100%);
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 10px rgba(212, 119, 78, 0.3);
        }

        .audio-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 15px rgba(212, 119, 78, 0.5);
        }

        .audio-btn.playing {
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.15);
            }
        }

        .audio-btn i {
            color: white;
            font-size: 0.9rem;
        }

        /* Product Content */
        .product-content {
            padding: 1.5rem;
        }

        .product-name {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            gap: 0.5rem;
        }

        .product-name h6 {
            font-size: 1.1rem;
            font-weight: 700;
            margin: 0;
            flex: 1;
        }

        .product-name a {
            color: var(--charcoal);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .product-name a:hover {
            color: var(--benin-red);
        }

        .local-name {
            font-size: 0.95rem;
            color: var(--terracotta);
            font-style: italic;
            font-weight: 500;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Badges */
        .category-badge {
            background: var(--beige);
            color: var(--charcoal);
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
        }

        .category-badge:hover {
            background: var(--benin-green);
            color: white;
            transform: translateY(-2px);
        }

        .category-badge i {
            font-size: 0.7rem;
            margin-right: 4px;
        }

        /* Artisan Info */
        .artisan-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
            padding: 0.5rem;
            background: rgba(0, 150, 57, 0.05);
            border-radius: 10px;
        }

        .artisan-info img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--benin-green);
        }

        .artisan-info .artisan-name {
            font-size: 0.85rem;
            color: var(--charcoal);
            font-weight: 500;
        }

        /* Price Display */
        .price-display {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--benin-green);
            margin-bottom: 1rem;
        }

        /* Actions */
        .product-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid var(--beige);
            gap: 0.5rem;
        }

        .btn-discover {
            background: linear-gradient(135deg, var(--benin-red) 0%, var(--terracotta) 100%);
            color: white;
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-discover:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(232, 17, 45, 0.3);
            color: white;
        }

        .btn-cart {
            background: white;
            color: var(--benin-green);
            border: 2px solid var(--benin-green);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .btn-cart:hover {
            background: var(--benin-green);
            color: white;
            transform: scale(1.1);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 5rem 2rem;
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .empty-state i {
            font-size: 5rem;
            color: var(--beige);
            margin-bottom: 2rem;
            display: block;
        }

        .empty-state h4 {
            color: var(--charcoal);
            margin-bottom: 1rem;
        }

        /* Pagination wrapper */
        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 3rem;
            padding: 2rem 0;
        }

        .pagination {
            display: flex;
            gap: 0.5rem;
            list-style: none;
            padding: 0;
            margin: 0;
            flex-wrap: wrap;
            justify-content: center;
        }

        .pagination .page-item {
            margin: 0;
        }

        .pagination .page-link {
            border-radius: 50px;
            border: 2px solid var(--beige);
            background: white;
            color: var(--charcoal);
            font-weight: 600;
            padding: 0.6rem 1.2rem;
            min-width: 45px;
            text-align: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .pagination .page-link:hover {
            background: var(--benin-red);
            border-color: var(--benin-red);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(232, 17, 45, 0.3);
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, var(--benin-red) 0%, var(--terracotta) 100%);
            border-color: var(--benin-red);
            color: white;
            box-shadow: 0 6px 20px rgba(232, 17, 45, 0.4);
            transform: scale(1.1);
        }

        /* Responsive */
        @media (min-width: 576px) {
            .product-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 992px) {
            .product-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (min-width: 1200px) {
            .product-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 768px) {
            .hero-products h1 {
                font-size: 2rem;
            }

            .hero-products .lead {
                font-size: 1rem;
            }

            .filter-section {
                padding: 1.5rem;
            }
        }

        /* Select2 customisation Bénin */
        .select2-container--default .select2-selection--single {
            border: 2px solid #e9ecef;
            border-radius: 50px;
            height: 42px;
            padding: 0 1rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 38px;
            color: #444;
            font-weight: 500;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--benin-green);
            color: white;
        }

        .select2-dropdown {
            border-radius: 12px;
            border: 1px solid #e9ecef;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush

@section('content')
    <!-- Hero Section -->
    <div class="hero-products">
        <div class="container">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb"
                    style="background: rgba(255,255,255,0.1); padding: 0.75rem 1rem; border-radius: 50px;">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: white;">Accueil</a></li>
                    <li class="breadcrumb-item active" style="color: white;">Arts & Artisanat</li>
                </ol>
            </nav>

            <div class="text-center">
                <h1 class="mb-3"> Arts & Artisanat</h1>
                <p class="lead" style="max-width: 700px; margin: 0 auto;">
                    Découvrez des objets artisanaux authentiques créés par nos artisans béninois
                </p>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Filter Section -->
        <div class="filter-section">
            <form action="{{ route('products.index') }}" method="GET" id="product-filters">
                <div class="row g-3">
                    <!-- Search -->
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"
                                style="border-radius: 50px 0 0 50px; border: 2px solid var(--beige); border-right: none;">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0"
                                placeholder="Rechercher un produit, un artisan..." value="{{ request('search') }}"
                                style="border-radius: 0 50px 50px 0; border-left: none;">
                        </div>
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
                            <option value="">Origine</option>
                            @foreach($ethnicOrigins as $origin)
                                <option value="{{ $origin }}" {{ request('ethnic_origin') == $origin ? 'selected' : '' }}>
                                    {{ $origin }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sort -->
                    <div class="col-md-2">
                        <select name="sort" class="form-select">
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Populaire</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Plus récent</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix ↑</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prix ↓</option>
                        </select>
                    </div>
                </div>

                <!-- Actions -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <span class="text-muted">
                        {{ $products->total() }} produits trouvés
                    </span>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-benin-green">
                            <i class="bi bi-funnel me-2"></i>Filtrer
                        </button>
                        <button type="button" onclick="resetFilters()" class="btn btn-outline-secondary">
                            Réinitialiser
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Products Grid -->
        @if($products->count() > 0)
            <div class="product-grid">
                @foreach($products as $product)
                    <div class="product-card">
                        <!-- Image -->
                        <div class="product-image">
                            <a href="{{ route('products.show', $product) }}">
                                @if($product->images && $product->images->first())
                                    <img src="{{ $product->images->first()->full_url }}" alt="{{ $product->name }}">
                                @else
                                    <img src="{{ asset('images/default-product.jpg') }}" alt="{{ $product->name }}" loading="lazy">
                                @endif
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
                            <button class="favorite-btn {{ $product->isFavorited ? 'active' : '' }}"
                                data-product-id="{{ $product->id }}" onclick="toggleFavorite(this)">
                                <i class="bi bi-heart{{ $product->isFavorited ? '-fill' : '' }}"></i>
                            </button>

                            <!-- Quick View -->
                            @if($product->images && $product->images->count() > 1)
                                <div class="quick-view">
                                    @foreach($product->images->take(4) as $image)
                                        <img src="{{ $image->full_url }}" alt="{{ $product->name }}"
                                            onclick="window.location.href='{{ route('products.show', $product) }}'">
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="product-content">
                            <!-- Name -->
                            <div class="product-name">
                                <h6>
                                    <a href="{{ route('products.show', $product) }}">
                                        {{ $product->name }}
                                    </a>
                                </h6>
                            </div>

                            <!-- Local Name with Audio -->
                            @if($product->name_local)
                                <div class="local-name">
                                    <i class="bi bi-translate"></i>
                                    {{ $product->name_local }}

                                    <!-- Bouton qui déclenche la synthèse vocale -->
                                    <button class="audio-btn" onclick="speakText('{{ addslashes($product->name_local) }}')"
                                        title="Écouter la prononciation">
                                        <i class="bi bi-volume-up-fill"></i>
                                    </button>
                                </div>
                            @endif

                            <!-- Category & Origin -->
                            <div class="mb-3">
                                <span class="category-badge">
                                    <i class="bi bi-tag-fill"></i>{{ $product->category_label }}
                                </span>
                                <span class="category-badge ms-1">
                                    <i class="bi bi-people-fill"></i>{{ $product->ethnic_origin }}
                                </span>
                            </div>

                            <!-- Artisan Info -->
                            @if($product->artisan)
                                <div class="artisan-info">
                                    @if($product->artisan->photos && $product->artisan->photos->first())
                                        <img src="{{ $product->artisan->photos->first()->full_url }}"
                                            alt="{{ $product->artisan->business_name ?? $product->artisan->user->name }}"
                                            class="artisan-avatar">
                                    @else
                                        <!-- Initiales -->
                                        <div class="avatar-initials"
                                            style="width: 32px; height: 32px; border-radius: 50%; background: var(--benin-green); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1rem; border: 2px solid white;">
                                            {{ strtoupper(substr($product->artisan->user->name ?? 'A', 0, 1)) .
                                        (str_word_count($product->artisan->user->name ?? '') > 1
                                            ? strtoupper(substr(strrchr($product->artisan->user->name, ' '), 1, 1))
                                            : '') }}
                                        </div>
                                    @endif

                                    <span class="artisan-name">
                                        <i class="bi bi-person-circle me-1"></i>
                                        {{ $product->artisan->business_name ?? $product->artisan->user->name }}
                                    </span>
                                </div>
                            @endif
                            <!-- Price -->
                            <div class="price-display">
                                {{ number_format($product->price, 0, ',', ' ') }} FCFA
                            </div>

                            <!-- Actions -->
                            <div class="product-actions">
                                <a href="{{ route('products.show', $product) }}" class="btn-discover">
                                    Découvrir <i class="bi bi-arrow-right"></i>
                                </a>
                                @if($product->stock_status !== 'out_of_stock')
                                    <button class="btn-cart" onclick="addToCart({{ $product->id }})" title="Ajouter au panier">
                                        <i class="bi bi-cart-plus-fill"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="pagination-wrapper">
                    <nav aria-label="Navigation des pages">
                        {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
                    </nav>
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="empty-state">
                <i class="bi bi-basket"></i>
                <h4>Aucun produit trouvé</h4>
                <p class="text-muted mb-4">Essayez de modifier vos critères de recherche</p>
                <a href="{{ route('products.index') }}" class="btn btn-benin-green rounded-pill px-4">
                    <i class="bi bi-arrow-clockwise me-2"></i>Voir tous les produits
                </a>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        function resetFilters() {
            window.location.href = "{{ route('products.index') }}";
        }

        // Fonction de synthèse vocale (celle qui fonctionne bien)
        function speakText(text) {
            if ('speechSynthesis' in window) {
                // Arrêter toute lecture en cours
                window.speechSynthesis.cancel();

                const utterance = new SpeechSynthesisUtterance(text);
                utterance.lang = 'fr-FR';

                // Optionnel : régler la voix, la vitesse, le ton
                utterance.rate = 1.0;   // vitesse (0.5 à 2)
                utterance.pitch = 1.0;  // ton (0 à 2)
                utterance.volume = 1.0; // volume

                // Trouver une voix française si possible
                const voices = window.speechSynthesis.getVoices();
                const frenchVoice = voices.find(voice => voice.lang === 'fr-FR' || voice.lang === 'fr');
                if (frenchVoice) {
                    utterance.voice = frenchVoice;
                }

                window.speechSynthesis.speak(utterance);

                console.log("Synthèse vocale lancée pour :", text);
            } else {
                console.log("Synthèse vocale non supportée par ce navigateur");
                alert("La synthèse vocale n'est pas disponible sur votre navigateur.");
            }
        }

        // Optionnel : recharger les voix au cas où elles ne soient pas encore chargées
        window.speechSynthesis.onvoiceschanged = () => {
            // Les voix sont maintenant disponibles
            console.log("Voix disponibles :", window.speechSynthesis.getVoices());
        };
        function toggleFavorite(button) {
            const productId = button.dataset.productId;
            const isActive = button.classList.contains('active');

            button.classList.toggle('active');
            const icon = button.querySelector('i');
            icon.className = isActive ? 'bi bi-heart' : 'bi bi-heart-fill';

            fetch('/favorites/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    favoritable_id: productId,
                    favoritable_type: 'product'
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        showToast(data.message, data.success ? 'success' : 'error');
                    }
                });
        }
        // Fonction pour ajouter au panier
        function addToCart(productId, quantity = 1) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity
                })
            })
                .then(response => {
                    // D'abord, obtenir la réponse en texte
                    return response.text().then(text => {
                        // Essayer de parser en JSON
                        let data;
                        try {
                            data = JSON.parse(text);
                        } catch (e) {
                            // Si ce n'est pas du JSON, on retourne le texte brut
                            return {
                                status: response.status,
                                ok: response.ok,
                                text: text
                            };
                        }
                        // Si c'est du JSON, on retourne l'objet
                        return {
                            status: response.status,
                            ok: response.ok,
                            data: data
                        };
                    });
                })
                .then(result => {
                    if (!result.ok) {
                        // Si la réponse n'est pas OK, afficher l'erreur
                        if (result.text) {
                            // C'est du texte (HTML probablement)
                            console.error('Réponse non JSON:', result.text);
                            // Extraire le message d'erreur de la page HTML si possible
                            const match = result.text.match(/<title>(.*?)<\/title>/);
                            let message = 'Erreur serveur. Veuillez réessayer.';
                            if (match) {
                                message = match[1];
                            }
                            throw new Error(message);
                        } else {
                            // C'est du JSON
                            throw new Error(result.data.message || 'Une erreur est survenue.');
                        }
                    }

                    // Succès
                    if (result.data.success) {
                        showToast("✅ Produit ajouté au panier avec succès !");
                    } else {
                        throw new Error(result.data.message || 'Erreur inconnue.');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    // Afficher l'erreur à l'utilisateur
                    alert('Erreur: ' + error.message);
                });
        }
        // Fonction pour mettre à jour le compteur du panier
        function updateCartCount(count) {
            const cartCounters = document.querySelectorAll('.cart-count');
            cartCounters.forEach(counter => {
                counter.textContent = count;
                counter.style.display = count > 0 ? 'inline' : 'none';
            });
        }

        function showToast(message, type = 'info') {
            const bgColor = type === 'success' ? 'bg-success' : (type === 'error' ? 'bg-danger' : 'bg-info');
            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-white ${bgColor} border-0 position-fixed top-0 end-0 m-3`;
            toast.setAttribute('role', 'alert');
            toast.style.zIndex = '9999';
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

        // Initialiser Select2 sur tous les filtres
        $('.select2-filter').select2({
            placeholder: function () {
                return $(this).data('placeholder') || "Sélectionner...";
            },
            allowClear: true,
            width: '100%',
            minimumResultsForSearch: 10, // active la recherche dès 10+ options
            dropdownCssClass: "select2-benin-dropdown"
        });

        // Soumettre le formulaire quand un select change
        $('.select2-filter').on('change', function () {
            $('#product-filters').submit();
        });

        // Optionnel : améliorer le look du dropdown Select2
        $('.select2-benin-dropdown').css({
            'border-radius': '12px',
            'box-shadow': '0 8px 25px rgba(0,0,0,0.15)'
        });


        // Animation au scroll
        document.addEventListener('DOMContentLoaded', function () {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function (entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '0';
                        entry.target.style.transform = 'translateY(20px)';

                        setTimeout(() => {
                            entry.target.style.transition = 'all 0.6s ease';
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0)';
                        }, 100);

                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.product-card').forEach(card => {
                observer.observe(card);
            });
        });
    </script>
@endpush

@section('content')
    <!-- Hero -->
    <div class="hero-products">
        <div class="container">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb"
                    style="background: rgba(255,255,255,0.1); padding: 0.75rem 1rem; border-radius: 50px;">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: white;">Accueil</a></li>
                    <li class="breadcrumb-item active" style="color: white;">Arts & Artisanat</li>
                </ol>
            </nav>

            <div class="text-center">
                <h1 class="mb-3">Arts & Artisanat</h1>
                <p class="lead" style="max-width: 700px; margin: 0 auto;">
                    Découvrez des objets artisanaux authentiques créés par nos artisans béninois
                </p>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Filtres avec Select2 -->
        <div class="filter-section">
            <form action="{{ route('products.index') }}" method="GET" id="product-filters">
                <div class="row g-3">
                    <!-- Recherche -->
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0" style="border-radius: 50px 0 0 50px;">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0"
                                placeholder="Rechercher un produit, artisan..." value="{{ request('search') }}"
                                style="border-radius: 0 50px 50px 0;">
                        </div>
                    </div>

                    <!-- Catégorie (Select2) -->
                    <div class="col-md-2">
                        <select name="category" class="form-control select2-filter" data-placeholder="Catégorie">
                            <option value=""></option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                    {{ \App\Models\Product::$categoryLabels[$category] ?? $category }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Origine ethnique (Select2) -->
                    <div class="col-md-2">
                        <select name="ethnic_origin" class="form-control select2-filter" data-placeholder="Origine">
                            <option value=""></option>
                            @foreach($ethnicOrigins as $origin)
                                <option value="{{ $origin }}" {{ request('ethnic_origin') == $origin ? 'selected' : '' }}>
                                    {{ $origin }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Ville (Select2) -->
                    <div class="col-md-2">
                        <select name="city" class="form-control select2-filter" data-placeholder="Ville">
                            <option value=""></option>
                            @foreach($cities as $city)
                                <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                    {{ $city }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tri (Select2) -->
                    <div class="col-md-2">
                        <select name="sort" class="form-control select2-filter" data-placeholder="Trier par">
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Populaire</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Plus récent</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix croissant
                            </option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prix
                                décroissant</option>
                        </select>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <span class="text-muted">{{ $products->total() }} produit{{ $products->total() > 1 ? 's' : '' }}
                        trouvé{{ $products->total() > 1 ? 's' : '' }}</span>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-benin-green px-4">
                            <i class="bi bi-funnel me-2"></i> Filtrer
                        </button>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary px-4">
                            Réinitialiser
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Grille produits -->
        @if($products->count() > 0)
            <div class="product-grid">
                @foreach($products as $product)
                    <div class="product-card">
                        <div class="product-image">
                            <a href="{{ route('products.show', $product) }}">
                                @if($product->images && $product->images->first())
                                    <img src="{{ $product->images->first()->full_url }}" alt="{{ $product->name }}">
                                @else
                                    <img src="{{ asset('images/default-product.jpg') }}" alt="{{ $product->name }}">
                                @endif
                            </a>

                            <!-- Badges -->
                            <div class="product-badges">
                                @if($product->featured)
                                    <span class="product-badge badge-featured">Vedette</span>
                                @endif
                                @if($product->stock_status === 'out_of_stock')
                                    <span class="product-badge badge-out-of-stock">Rupture</span>
                                @elseif($product->stock_status === 'low_stock')
                                    <span class="product-badge badge-low-stock">Stock bas</span>
                                @endif
                                @if($product->custom_order)
                                    <span class="product-badge badge-custom-order">Sur commande</span>
                                @endif
                            </div>
                        </div>

                        <div class="product-content">
                            <h6><a href="{{ route('products.show', $product) }}">{{ $product->name }}</a></h6>

                            @if($product->name_local)
                                <div class="local-name">
                                    <i class="bi bi-translate"></i> {{ $product->name_local }}
                                </div>
                            @endif

                            <div class="mb-3">
                                <span class="category-badge">{{ $product->category_label }}</span>
                                <span class="category-badge ms-1">{{ $product->ethnic_origin }}</span>
                            </div>

                            @if($product->artisan)
                                <div class="artisan-info">
                                    <span class="artisan-name">
                                        {{ $product->artisan->business_name ?? $product->artisan->user->name }}
                                    </span>
                                </div>
                            @endif

                            <div class="price-display">
                                {{ number_format($product->price, 0, ',', ' ') }} FCFA
                            </div>

                            <div class="product-actions">
                                <a href="{{ route('products.show', $product) }}" class="btn-discover">
                                    Découvrir <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pagination-container mt-5">
                {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-basket"></i>
                <h4>Aucun produit trouvé</h4>
                <p class="text-muted">Aucun produit ne correspond à vos critères pour le moment.</p>
                <a href="{{ route('products.index') }}" class="btn btn-benin-green mt-3">
                    Voir tous les produits
                </a>
            </div>
        @endif
    </div>
@endsection