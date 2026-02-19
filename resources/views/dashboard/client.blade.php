@extends('layouts.admin')

@section('title', 'Tableau de bord - AFRI-HERITAGE')

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

    .dashboard-hero {
        background: linear-gradient(135deg, rgba(0, 150, 57, 0.1) 0%, rgba(245, 230, 211, 0.2) 100%);
        border-radius: 20px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        border: 1px solid var(--beige);
    }

    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 150, 57, 0.1);
        border-color: var(--benin-green);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .stat-icon.favorites {
        background: linear-gradient(135deg, #FF6B6B, #FF8E53);
        color: white;
    }

    .stat-icon.views {
        background: linear-gradient(135deg, #4ECDC4, #44A08D);
        color: white;
    }

    .stat-icon.orders {
        background: linear-gradient(135deg, #9D50BB, #6E48AA);
        color: white;
    }

    .stat-icon.messages {
        background: linear-gradient(135deg, #36D1DC, #5B86E5);
        color: white;
    }

    .product-card-mini {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        background: white;
    }

    .product-card-mini:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        border-color: var(--benin-green);
    }

    .product-card-mini img {
        width: 100%;
        height: 140px;
        object-fit: cover;
    }

    .empty-state {
        background: #f8f9fa;
        border: 2px dashed #dee2e6;
        border-radius: 15px;
        padding: 3rem 1rem;
        text-align: center;
    }

    .quick-actions .btn-action {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 1rem;
        text-align: center;
        transition: all 0.3s ease;
        text-decoration: none;
        color: var(--charcoal);
    }

    .quick-actions .btn-action:hover {
        background: var(--benin-green);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 150, 57, 0.2);
    }

    .btn-action i {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    .section-title {
        position: relative;
        padding-bottom: 0.75rem;
        margin-bottom: 1.5rem;
        border-bottom: 2px solid var(--beige);
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 60px;
        height: 2px;
        background: var(--benin-green);
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <!-- Header du tableau de bord -->
    <div class="dashboard-hero">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="display-6 fw-bold text-charcoal mb-2">
                    Bienvenue, {{ auth()->user()->name }} !
                </h1>
                <p class="text-muted mb-0">
                    Retrouvez ici vos favoris, vos demandes et l'historique de vos consultations.
                </p>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="d-flex flex-column flex-md-row gap-2 justify-content-md-end">
                    <a href="{{ route('cart.index') }}" class="btn btn-benin-green">
                        <i class="bi bi-cart3 me-2"></i>Mon panier
                    </a>
                    <a href="{{ route('dashboard.favorites') }}" class="btn btn-outline-benin-green">
                        <i class="bi bi-heart me-2"></i>Favoris
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="row g-4 mb-5">
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon favorites">
                    <i class="bi bi-heart-fill"></i>
                </div>
                <h4 class="fw-bold mb-1">{{ $favorites->count() }}</h4>
                <p class="text-muted mb-0">Favoris</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon views">
                    <i class="bi bi-eye-fill"></i>
                </div>
                <h4 class="fw-bold mb-1">{{ count(session()->get('recently_viewed', [])) }}</h4>
                <p class="text-muted mb-0">Vues récentes</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon orders">
                    <i class="bi bi-bag-fill"></i>
                </div>
                <h4 class="fw-bold mb-1">{{ auth()->user()->orders()->count() }}</h4>
                <p class="text-muted mb-0">Commandes</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon messages">
                    <i class="bi bi-chat-left-text-fill"></i>
                </div>
                <h4 class="fw-bold mb-1">0</h4>
                <p class="text-muted mb-0">Messages</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Favoris récents -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold mb-0 section-title d-inline-block">Vos favoris récents</h4>
                        <a href="{{ route('dashboard.favorites') }}" class="btn btn-sm btn-outline-benin-green">
                            Voir tout <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>

                    @if($favorites->count() > 0)
                        <div class="row g-3">
                            @foreach($favorites->take(6) as $favorite)
                                <div class="col-md-4">
                                    <div class="product-card-mini">
                                        @if($favorite->favoritable_type == 'App\Models\Product')
                                            @php
                                                $product = $favorite->favoritable;
                                                $image = $product->images->first();
                                            @endphp
                                            <img src="{{ $image->full_url ?? asset('images/default-product.jpg') }}"
                                                 alt="{{ $product->name }}"
                                                 class="img-fluid">
                                            <div class="p-3">
                                                <h6 class="fw-bold mb-1">{{ Str::limit($product->name, 40) }}</h6>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="text-benin-green fw-bold">
                                                        {{ $product->formatted_price }}
                                                    </span>
                                                    <a href="{{ route('products.show', $product) }}"
                                                       class="btn btn-sm btn-outline-benin-green">
                                                        Voir
                                                    </a>
                                                </div>
                                            </div>
                                        @elseif($favorite->favoritable_type == 'App\Models\Artisan')
                                            @php
                                                $artisan = $favorite->favoritable;
                                                $photo = $artisan->photos->first();
                                            @endphp
                                            <img src="{{ $photo->full_url ?? asset('images/default-artisan.jpg') }}"
                                                 alt="{{ $artisan->user->name }}"
                                                 class="img-fluid">
                                            <div class="p-3">
                                                <h6 class="fw-bold mb-1">{{ Str::limit($artisan->user->name, 40) }}</h6>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="badge bg-benin-green">{{ $artisan->craft_label }}</small>
                                                    <a href="{{ route('artisans.show', $artisan) }}"
                                                       class="btn btn-sm btn-outline-benin-green">
                                                        Visiter
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="bi bi-heart text-muted fs-1 mb-3"></i>
                            <h5 class="text-muted">Aucun favori pour le moment</h5>
                            <p class="text-muted">Explorez nos artisans et produits pour en ajouter à vos favoris</p>
                            <a href="{{ route('artisans.index') }}" class="btn btn-benin-green mt-2">
                                <i class="bi bi-search me-2"></i>Découvrir les artisans
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Produits récemment consultés -->
            <div class="card border-0 shadow-sm rounded-3 mt-4">
                <div class="card-body">
                    <h4 class="fw-bold mb-4 section-title d-inline-block">Récemment consultés</h4>

                    @if($recentViews->count() > 0)
                        <div class="row g-3">
                            @foreach($recentViews->take(6) as $product)
                                <div class="col-md-4">
                                    <div class="product-card-mini">
                                        <img src="{{ $product->images->first()->full_url ?? asset('images/default-product.jpg') }}"
                                             alt="{{ $product->name }}"
                                             class="img-fluid">
                                        <div class="p-3">
                                            <h6 class="fw-bold mb-1">{{ Str::limit($product->name, 40) }}</h6>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-benin-green fw-bold">
                                                    {{ $product->formatted_price }}
                                                </span>
                                                <a href="{{ route('products.show', $product) }}"
                                                   class="btn btn-sm btn-outline-benin-green">
                                                    Voir
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state py-4">
                            <i class="bi bi-clock-history text-muted fs-1 mb-3"></i>
                            <p class="text-muted">Aucun produit consulté récemment</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar - Actions rapides -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 sticky-top" style="top: 20px;">
                <div class="card-body">
                    <h4 class="fw-bold mb-4 section-title d-inline-block">Actions rapides</h4>

                    <div class="quick-actions">
                        <div class="row g-3">
                            <div class="col-6">
                                <a href="{{ route('cart.index') }}" class="btn-action">
                                    <i class="bi bi-cart3"></i>
                                    <span>Mon panier</span>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('dashboard.favorites') }}" class="btn-action">
                                    <i class="bi bi-heart"></i>
                                    <span>Mes favoris</span>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('dashboard.orders') }}" class="btn-action">
                                    <i class="bi bi-bag"></i>
                                    <span>Mes commandes</span>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('dashboard.messages') }}" class="btn-action">
                                    <i class="bi bi-chat-left-text"></i>
                                    <span>Messages</span>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('dashboard.requests') }}" class="btn-action">
                                    <i class="bi bi-chat-quote"></i>
                                    <span>Mes demandes</span>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('dashboard.profile') }}" class="btn-action">
                                    <i class="bi bi-person"></i>
                                    <span>Mon profil</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Suggestions -->
                    <div>
                        <h5 class="fw-bold mb-3">Suggestions pour vous</h5>
                        <div class="d-grid gap-2">
                            <a href="{{ route('artisans.index') }}" class="btn btn-outline-benin-green">
                                <i class="bi bi-search me-2"></i>Découvrir des artisans
                            </a>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-benin-green">
                                <i class="bi bi-grid me-2"></i>Voir tous les produits
                            </a>
                            <a href="{{ route('quotes.create') }}" class="btn btn-outline-benin-red">
                                <i class="bi bi-chat-quote me-2"></i>Demander un devis
                            </a>
                        </div>
                    </div>

                    <!-- Statistiques personnelles -->
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="fw-bold mb-3">Votre activité</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Commandes actives</span>
                            <span class="fw-bold">0</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Devis en attente</span>
                            <span class="fw-bold">0</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Messages non lus</span>
                            <span class="fw-bold">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Marquer comme lus les notifications
    document.addEventListener('DOMContentLoaded', function() {
        // Animation pour les cartes de statistiques
        const statCards = document.querySelectorAll('.stat-card');
        statCards.forEach((card, index) => {
            card.style.animationDelay = `${index * 100}ms`;
        });
    });
</script>
@endpush
