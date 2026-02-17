@extends('layouts.app')

@section('title', 'Gastronomie Béninoise - AFRI-HERITAGE')

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
    .hero-gastro {
        background: linear-gradient(135deg, var(--benin-green) 0%, var(--benin-red) 100%);
        padding: 4rem 0 3rem;
        margin-bottom: 3rem;
        position: relative;
        overflow: hidden;
    }

    .hero-gastro::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        opacity: 0.3;
    }

    .hero-gastro .container {
        position: relative;
        z-index: 1;
    }

    .hero-gastro h1 {
        color: white;
        font-size: 3rem;
        font-weight: 700;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        margin-bottom: 1rem;
    }

    .hero-gastro .lead {
        color: rgba(255,255,255,0.95);
        font-size: 1.2rem;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
    }

    /* Category Tabs améliorés */
    .category-tabs {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 3rem;
        overflow-x: auto;
        padding: 1rem;
        background: white;
        border-radius: 50px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

    .category-tabs::-webkit-scrollbar {
        height: 6px;
    }

    .category-tabs::-webkit-scrollbar-track {
        background: var(--beige);
        border-radius: 10px;
    }

    .category-tabs::-webkit-scrollbar-thumb {
        background: var(--benin-red);
        border-radius: 10px;
    }

    .category-tab {
        padding: 0.75rem 1.75rem;
        border: 2px solid transparent;
        border-radius: 50px;
        background: var(--beige);
        color: var(--charcoal);
        font-weight: 600;
        white-space: nowrap;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .category-tab::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.5s;
    }

    .category-tab:hover::before {
        left: 100%;
    }

    .category-tab:hover {
        background: var(--benin-green);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,150,57,0.3);
    }

    .category-tab.active {
        background: linear-gradient(135deg, var(--benin-red) 0%, var(--terracotta) 100%);
        color: white;
        box-shadow: 0 6px 20px rgba(232,17,45,0.4);
        transform: scale(1.05);
    }

    /* Search & Filters améliorés */
    .search-section {
        background: white;
        padding: 2rem;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        margin-bottom: 3rem;
    }

    .search-section input,
    .search-section select {
        border: 2px solid var(--beige);
        border-radius: 50px;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
    }

    .search-section input:focus,
    .search-section select:focus {
        border-color: var(--benin-red);
        box-shadow: 0 0 0 0.2rem rgba(232,17,45,0.1);
    }

    .search-section .btn {
        border-radius: 50px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .search-section .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(232,17,45,0.3);
    }

    /* Dish Grid */
    .dish-grid {
        display: grid;
        gap: 2rem;
        margin-bottom: 3rem;
    }

    /* Dish Card amélioré */
    .dish-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .dish-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    }

    .dish-image {
        height: 250px;
        overflow: hidden;
        position: relative;
    }

    .dish-image::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 50%;
        background: linear-gradient(to top, rgba(0,0,0,0.5), transparent);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .dish-card:hover .dish-image::after {
        opacity: 1;
    }

    .dish-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .dish-card:hover .dish-image img {
        transform: scale(1.1);
    }

    .dish-category {
        position: absolute;
        top: 15px;
        left: 15px;
        background: linear-gradient(135deg, var(--benin-green) 0%, var(--benin-red) 100%);
        color: white;
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        z-index: 2;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Audio Button amélioré */
    .audio-btn {
        background: linear-gradient(135deg, var(--benin-yellow) 0%, var(--terracotta) 100%);
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 10px rgba(212,119,78,0.3);
        position: relative;
    }

    .audio-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 15px rgba(212,119,78,0.5);
    }

    .audio-btn:active {
        transform: scale(0.95);
    }

    .audio-btn.playing {
        animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.15);
        }
    }

    .audio-btn i {
        color: white;
        font-size: 1rem;
    }

    /* Content Section */
    .dish-content {
        padding: 1.5rem;
    }

    .dish-name {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 0.75rem;
        gap: 0.5rem;
    }

    .dish-name h6 {
        font-size: 1.1rem;
        font-weight: 700;
        margin: 0;
        flex: 1;
    }

    .dish-name a {
        color: var(--charcoal);
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .dish-name a:hover {
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

    .local-name i {
        font-size: 0.85rem;
    }

    /* Badges */
    .ethnic-badge {
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

    .ethnic-badge:hover {
        background: var(--benin-green);
        color: white;
        transform: translateY(-2px);
    }

    .ethnic-badge i {
        font-size: 0.7rem;
        margin-right: 4px;
    }

    /* Ingredients */
    .ingredients-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .ingredient-tag {
        background: linear-gradient(135deg, var(--beige) 0%, #e8dcc8 100%);
        color: var(--charcoal);
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 500;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .ingredient-tag:hover {
        border-color: var(--benin-red);
        transform: translateY(-2px);
    }

    /* Occasions */
    .occasions-info {
        background: rgba(0,150,57,0.05);
        padding: 0.75rem;
        border-radius: 12px;
        margin-bottom: 1rem;
        border-left: 3px solid var(--benin-green);
    }

    .occasions-info small {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--charcoal);
        line-height: 1.5;
    }

    .occasions-info i {
        color: var(--benin-green);
        font-size: 1rem;
    }

    /* Actions */
    .dish-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid var(--beige);
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
        display: inline-block;
    }

    .btn-discover:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(232,17,45,0.3);
        color: white;
    }

    .views-count {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #6c757d;
        font-size: 0.85rem;
    }

    .views-count i {
        color: var(--benin-green);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
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

    /* Pagination - ULTRA SPÉCIFIQUE pour forcer Bootstrap */
    nav[role="navigation"] .pagination,
    .pagination {
        margin-top: 3rem !important;
        gap: 0 !important;
    }

    nav[role="navigation"] .pagination .page-link,
    .pagination > .page-item > .page-link,
    .pagination .page-link {
        border-radius: 8px !important;
        margin: 0 3px !important;
        border: 1px solid var(--beige) !important;
        color: var(--charcoal) !important;
        font-weight: 600 !important;
        font-size: 14px !important;
        padding: 6px 12px !important;
        min-width: unset !important;
        width: auto !important;
        height: auto !important;
        line-height: 1.5 !important;
        display: inline-block !important;
        text-align: center !important;
        transition: all 0.3s ease !important;
        box-sizing: border-box !important;
    }

    nav[role="navigation"] .pagination .page-link:hover,
    .pagination > .page-item > .page-link:hover,
    .pagination .page-link:hover {
        background: var(--benin-red) !important;
        border-color: var(--benin-red) !important;
        color: white !important;
        transform: translateY(-2px) !important;
        text-decoration: none !important;
    }

    nav[role="navigation"] .pagination .page-item.active .page-link,
    .pagination > .page-item.active > .page-link,
    .pagination .page-item.active .page-link {
        background: var(--benin-red) !important;
        border-color: var(--benin-red) !important;
        color: white !important;
        z-index: 1 !important;
    }

    nav[role="navigation"] .pagination .page-item.disabled .page-link,
    .pagination > .page-item.disabled > .page-link,
    .pagination .page-item.disabled .page-link {
        opacity: 0.5 !important;
        background: #f8f9fa !important;
        cursor: not-allowed !important;
    }

    /* Forcer la taille des page-item */
    nav[role="navigation"] .pagination .page-item,
    .pagination > .page-item,
    .pagination .page-item {
        margin: 0 !important;
        display: inline-block !important;
    }

    /* S'assurer que les svg/icônes dans les boutons suivent aussi */
    .pagination .page-link svg,
    .pagination .page-link i {
        width: 14px !important;
        height: 14px !important;
        font-size: 14px !important;
    }

    /* Responsive */
    @media (min-width: 576px) {
        .dish-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (min-width: 992px) {
        .dish-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (min-width: 1200px) {
        .dish-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    @media (max-width: 768px) {
        .hero-gastro h1 {
            font-size: 2rem;
        }

        .hero-gastro .lead {
            font-size: 1rem;
        }

        .category-tabs {
            padding: 0.75rem;
            border-radius: 15px;
        }

        .search-section {
            padding: 1.5rem;
        }
    }

    /* Loading Animation */
    @keyframes shimmer {
        0% {
            background-position: -1000px 0;
        }
        100% {
            background-position: 1000px 0;
        }
    }

    .loading {
        animation: shimmer 2s infinite;
        background: linear-gradient(to right, var(--beige) 8%, #e0d4c0 18%, var(--beige) 33%);
        background-size: 1000px 100%;
    }

    /* Nouveaux styles pour les vendeurs */
    .vendors-info {
        background: linear-gradient(135deg, rgba(0,150,57,0.08) 0%, rgba(252,209,22,0.08) 100%);
        padding: 0.75rem;
        border-radius: 12px;
        margin-bottom: 1rem;
        border-left: 3px solid var(--benin-green);
    }

    .vendors-count {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--charcoal);
        font-size: 0.85rem;
        font-weight: 600;
    }

    .vendors-count i {
        color: var(--benin-green);
        font-size: 1rem;
    }

    .price-range {
        display: inline-block;
        background: linear-gradient(135deg, var(--benin-yellow) 0%, var(--terracotta) 100%);
        color: white;
        padding: 0.4rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 700;
        margin-top: 0.5rem;
        box-shadow: 0 2px 8px rgba(212,119,78,0.3);
    }

    .no-vendors {
        color: #999;
        font-size: 0.85rem;
        font-style: italic;
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<div class="hero-gastro">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="background: rgba(255,255,255,0.1); padding: 0.75rem 1rem; border-radius: 50px;">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: white;">Accueil</a></li>
                <li class="breadcrumb-item active" style="color: white;">Gastronomie</li>
            </ol>
        </nav>

        <div class="text-center">
            <h1 class="mb-3">🍲 Gastronomie Béninoise</h1>
            <p class="lead" style="max-width: 700px; margin: 0 auto;">
                Découvrez les saveurs authentiques du Bénin, leurs histoires et où les déguster
            </p>
        </div>
    </div>
</div>

<div class="container">
    <!-- Category Tabs -->
    <div class="category-tabs">
        <button class="category-tab {{ !request('category') ? 'active' : '' }}"
                onclick="filterByCategory('')">
            <i class="bi bi-grid-3x3-gap me-2"></i>Tous les plats
        </button>
        @foreach($categories as $category)
            <button class="category-tab {{ request('category') == $category ? 'active' : '' }}"
                    onclick="filterByCategory('{{ $category }}')">
                {{ \App\Models\Dish::$categoryLabels[$category] ?? $category }}
            </button>
        @endforeach
    </div>

    <!-- Search & Filters -->
    <div class="search-section">
        <form action="{{ route('gastronomie.index') }}" method="GET" id="searchForm">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0" style="border-radius: 50px 0 0 50px; border: 2px solid var(--beige); border-right: none;">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text"
                               name="search"
                               class="form-control border-start-0"
                               placeholder="Rechercher un plat, un ingrédient..."
                               value="{{ request('search') }}"
                               style="border-radius: 0 50px 50px 0; border-left: none;">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="region" class="form-select" onchange="this.form.submit()">
                        <option value="">📍 Toutes les régions</option>
                        @foreach($regions as $region)
                            <option value="{{ $region }}" {{ request('region') == $region ? 'selected' : '' }}>
                                {{ $region }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="sort" class="form-select" onchange="this.form.submit()">
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>A-Z</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Populaires</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Récents</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Prix croissant</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Prix décroissant</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="form-check" style="padding-top: 0.5rem;">
                        <input class="form-check-input" type="checkbox" name="with_vendors" id="withVendors"
                               {{ request('with_vendors') ? 'checked' : '' }} onchange="this.form.submit()">
                        <label class="form-check-label" for="withVendors">
                            Avec vendeurs
                        </label>
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-benin-red w-100">
                        <i class="bi bi-funnel"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Dishes Grid -->
    @if($dishes->count() > 0)
        <div class="dish-grid">
            @foreach($dishes as $dish)
                <div class="dish-card">
                    <!-- Image -->
                    <div class="dish-image">
                        <a href="{{ route('gastronomie.show', $dish) }}">
                            <img src="{{ $dish->images->first()->image_url ?? asset('images/default-dish.jpg') }}"
                                 alt="{{ $dish->name }}"
                                 loading="lazy">
                        </a>
                        <span class="dish-category">
                            {{ $dish->category_label }}
                        </span>
                    </div>

                    <!-- Content -->
                    <div class="dish-content">
                        <!-- Name with Audio -->
                        <div class="dish-name">
                            <h6>
                                <a href="{{ route('gastronomie.show', $dish) }}">
                                    {{ $dish->name }}
                                </a>
                            </h6>
                            @if($dish->name_local)
                                <button class="audio-btn"
                                        onclick="speakText('{{ addslashes($dish->name_local) }}')"
                                        title="Écouter la prononciation">
                                    <i class="bi bi-volume-up-fill"></i>
                                </button>
                            @endif
                        </div>

                        <!-- Local Name -->
                        @if($dish->name_local)
                            <div class="local-name">
                                <i class="bi bi-translate"></i>
                                {{ $dish->name_local }}
                            </div>
                        @endif

                        <!-- Ethnic Origin & Region -->
                        <div class="mb-3">
                            <span class="ethnic-badge">
                                <i class="bi bi-people-fill"></i>{{ $dish->ethnic_origin }}
                            </span>
                            <span class="ethnic-badge ms-1">
                                <i class="bi bi-geo-alt-fill"></i>{{ $dish->region }}
                            </span>
                        </div>

                        <!-- Vendors Info -->
                        @if($dish->hasVendors())
                            <div class="vendors-info">
                                <div class="vendors-count">
                                    <i class="bi bi-shop"></i>
                                    <span>{{ $dish->vendors->count() }} {{ Str::plural('vendeur', $dish->vendors->count()) }}</span>
                                </div>
                                @if($dish->getPriceRange())
                                    <div class="price-range">
                                        <i class="bi bi-cash me-1"></i>{{ $dish->getPriceRange() }}
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="vendors-info">
                                <div class="no-vendors">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Aucun vendeur référencé
                                </div>
                            </div>
                        @endif

                        <!-- Ingredients -->
                        @if($dish->ingredients && count($dish->ingredients) > 0)
                            <div class="ingredients-preview">
                                @foreach(collect($dish->ingredients)->take(3) as $ingredient)
                                    <span class="ingredient-tag">{{ $ingredient }}</span>
                                @endforeach

                                @if(count($dish->ingredients) > 3)
                                    <span class="ingredient-tag" style="background: var(--benin-red); color: white;">
                                        +{{ count($dish->ingredients) - 3 }} autres
                                    </span>
                                @endif
                            </div>
                        @endif

                        <!-- Occasions -->
                        @if($dish->occasions)
                            <div class="occasions-info">
                                <small>
                                    <i class="bi bi-calendar-event-fill"></i>
                                    {{ Str::limit($dish->occasions, 65) }}
                                </small>
                            </div>
                        @endif

                        <!-- Actions -->
                        <div class="dish-actions">
                            <a href="{{ route('gastronomie.show', $dish) }}" class="btn-discover">
                                Découvrir <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                            <div class="views-count">
                                <i class="bi bi-eye-fill"></i>
                                <span>{{ number_format($dish->views) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($dishes->hasPages())
            <div class="pagination-container mt-5">
                {{ $dishes->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="empty-state">
            <i class="bi bi-egg-fried"></i>
            <h4>Aucun plat trouvé</h4>
            <p class="text-muted mb-4">Essayez de modifier vos critères de recherche ou explorez d'autres catégories</p>
            <a href="{{ route('gastronomie.index') }}" class="btn btn-benin-red rounded-pill px-4">
                <i class="bi bi-arrow-clockwise me-2"></i>Réinitialiser les filtres
            </a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// (Garder tous les scripts existants)
function filterByCategory(category) {
    const url = new URL(window.location.href);
    if (category) {
        url.searchParams.set('category', category);
    } else {
        url.searchParams.delete('category');
    }
    url.searchParams.delete('page');
    window.location.href = url.toString();
}

function speakText(text) {
    if ('speechSynthesis' in window) {
        window.speechSynthesis.cancel();
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = 'fr-FR';
        utterance.rate = 1.0;
        utterance.pitch = 1.0;
        utterance.volume = 1.0;

        const voices = window.speechSynthesis.getVoices();
        const frenchVoice = voices.find(voice => voice.lang === 'fr-FR' || voice.lang === 'fr');
        if (frenchVoice) {
            utterance.voice = frenchVoice;
        }

        window.speechSynthesis.speak(utterance);
    } else {
        alert("La synthèse vocale n'est pas disponible sur votre navigateur.");
    }
}

window.speechSynthesis.onvoiceschanged = () => {
    console.log("Voix disponibles :", window.speechSynthesis.getVoices());
};

document.addEventListener('DOMContentLoaded', function() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
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

    document.querySelectorAll('.dish-card').forEach(card => {
        observer.observe(card);
    });

    // FORCER LES STYLES DE PAGINATION
    function fixPagination() {
        const paginationLinks = document.querySelectorAll('.pagination .page-link');
        paginationLinks.forEach(link => {
            // Remplacer le texte "Previous" et "Next" par des icônes
            if (link.textContent.trim() === 'Previous' || link.textContent.trim() === '‹ Previous' || link.textContent.includes('Précédent')) {
                link.innerHTML = '<i class="bi bi-chevron-left"></i>';
            }
            if (link.textContent.trim() === 'Next' || link.textContent.trim() === 'Next ›' || link.textContent.includes('Suivant')) {
                link.innerHTML = '<i class="bi bi-chevron-right"></i>';
            }

            link.style.cssText = `
                padding: 8px 12px !important;
                font-size: 14px !important;
                border-radius: 8px !important;
                margin: 0 3px !important;
                border: 1px solid var(--beige) !important;
                color: var(--charcoal) !important;
                font-weight: 600 !important;
                min-width: 36px !important;
                width: auto !important;
                height: 36px !important;
                line-height: 1.2 !important;
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
            `;
        });

        const activeLinks = document.querySelectorAll('.pagination .page-item.active .page-link');
        activeLinks.forEach(link => {
            link.style.cssText = `
                padding: 8px 12px !important;
                font-size: 14px !important;
                border-radius: 8px !important;
                margin: 0 3px !important;
                background: var(--benin-red) !important;
                border-color: var(--benin-red) !important;
                color: white !important;
                font-weight: 600 !important;
                min-width: 36px !important;
                width: auto !important;
                height: 36px !important;
                line-height: 1.2 !important;
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
            `;
        });

        const disabledLinks = document.querySelectorAll('.pagination .page-item.disabled .page-link');
        disabledLinks.forEach(link => {
            link.style.opacity = '0.4';
            link.style.cursor = 'not-allowed';
        });
    }

    function toggleFavorite(favoritableId, favoritableType) {
    fetch('/favorites/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            favoritable_id: favoritableId,
            favoritable_type: favoritableType
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mettre à jour l'interface (icône, compteur)
            console.log(data.message);
        }
    });
}

    // Appliquer immédiatement
    fixPagination();

    // Réappliquer après 100ms au cas où
    setTimeout(fixPagination, 100);
});
</script>
@endpush
