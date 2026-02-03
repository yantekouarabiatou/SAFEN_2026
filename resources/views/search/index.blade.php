@extends('layouts.app')

@section('title', 'Recherche - AFRI-HERITAGE')

@push('styles')
<style>
    .search-header {
        background: linear-gradient(135deg, var(--benin-green) 0%, var(--navy) 100%);
        color: white;
        padding: 4rem 0;
        text-align: center;
    }

    .search-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 2rem;
        overflow-x: auto;
        padding-bottom: 0.5rem;
    }

    .search-tab {
        padding: 0.75rem 1.5rem;
        border: 2px solid var(--beige);
        border-radius: 50px;
        background: white;
        color: var(--charcoal);
        font-weight: 500;
        white-space: nowrap;
        transition: all 0.3s ease;
    }

    .search-tab:hover,
    .search-tab.active {
        background: var(--benin-green);
        border-color: var(--benin-green);
        color: white;
    }

    .result-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .result-artisan .result-icon {
        background: var(--benin-green);
        color: white;
    }

    .result-product .result-icon {
        background: var(--benin-yellow);
        color: var(--charcoal);
    }

    .result-dish .result-icon {
        background: var(--benin-red);
        color: white;
    }

    .result-item {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease;
    }

    .result-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .highlight {
        background: yellow;
        padding: 0 2px;
        border-radius: 2px;
    }

    .no-results {
        text-align: center;
        padding: 4rem 0;
    }

    .search-suggestions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 2rem;
    }

    .suggestion-chip {
        padding: 0.5rem 1rem;
        background: var(--beige);
        border-radius: 50px;
        color: var(--charcoal);
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .suggestion-chip:hover {
        background: var(--benin-green);
        color: white;
    }
</style>
@endpush

@section('content')
<!-- Header -->
<section class="search-header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-4">Recherche</h1>
                <form action="{{ route('search') }}" method="GET" class="mb-4">
                    <div class="input-group input-group-lg">
                        <input type="text"
                               name="q"
                               class="form-control rounded-pill"
                               placeholder="Rechercher des artisans, produits, plats..."
                               value="{{ $query }}"
                               required>
                        <button class="btn btn-benin-yellow text-charcoal rounded-pill ms-2" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
                @if($query)
                    <p class="mb-0">
                        {{ $total }} résultat{{ $total > 1 ? 's' : '' }} pour "<strong>{{ $query }}</strong>"
                    </p>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Results -->
<div class="container py-5">
    @if($query)
        <!-- Tabs -->
        <div class="search-tabs">
            <a href="?q={{ urlencode($query) }}"
               class="search-tab {{ $type == 'all' ? 'active' : '' }}">
                Tout ({{ $total }})
            </a>
            @if(isset($results['artisans']))
                <a href="?q={{ urlencode($query) }}&type=artisans"
                   class="search-tab {{ $type == 'artisans' ? 'active' : '' }}">
                    Artisans ({{ $results['artisans']->count() }})
                </a>
            @endif
            @if(isset($results['products']))
                <a href="?q={{ urlencode($query) }}&type=products"
                   class="search-tab {{ $type == 'products' ? 'active' : '' }}">
                    Produits ({{ $results['products']->count() }})
                </a>
            @endif
            @if(isset($results['dishes']))
                <a href="?q={{ urlencode($query) }}&type=dishes"
                   class="search-tab {{ $type == 'dishes' ? 'active' : '' }}">
                    Plats ({{ $results['dishes']->count() }})
                </a>
            @endif
        </div>

        <!-- Results List -->
        @if($total > 0)
            <!-- Artisans -->
            @if(($type == 'all' || $type == 'artisans') && isset($results['artisans']) && $results['artisans']->count() > 0)
                <div class="mb-5">
                    <h3 class="fw-bold mb-4">Artisans ({{ $results['artisans']->count() }})</h3>
                    @foreach($results['artisans'] as $artisan)
                        <div class="result-item result-artisan">
                            <div class="d-flex align-items-center">
                                <div class="result-icon">
                                    <i class="bi bi-tools"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="fw-bold mb-1">
                                        <a href="{{ route('artisans.show', $artisan) }}" class="text-decoration-none text-charcoal">
                                            {!! highlightText($artisan->user->name, $query) !!}
                                        </a>
                                    </h5>
                                    <div class="mb-2">
                                        <span class="badge bg-benin-green text-white me-2">
                                            {!! highlightText($artisan->craft_label, $query) !!}
                                        </span>
                                        <span class="text-muted">
                                            <i class="bi bi-geo-alt-fill me-1"></i>
                                            {!! highlightText($artisan->city, $query) !!}
                                            @if($artisan->neighborhood)
                                                , {!! highlightText($artisan->neighborhood, $query) !!}
                                            @endif
                                        </span>
                                    </div>
                                    @if($artisan->bio)
                                        <p class="text-muted mb-0">
                                            {!! highlightText(Str::limit($artisan->bio, 150), $query) !!}
                                        </p>
                                    @endif
                                </div>
                                <div class="text-end">
                                    <div class="rating-stars mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi {{ $i <= floor($artisan->rating_avg) ? 'bi-star-fill' : 'bi-star' }}"></i>
                                        @endfor
                                    </div>
                                    <a href="{{ route('artisans.show', $artisan) }}" class="btn btn-benin-green btn-sm">
                                        Voir profil
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Products -->
            @if(($type == 'all' || $type == 'products') && isset($results['products']) && $results['products']->count() > 0)
                <div class="mb-5">
                    <h3 class="fw-bold mb-4">Produits ({{ $results['products']->count() }})</h3>
                    <div class="row g-4">
                        @foreach($results['products'] as $product)
                            <div class="col-md-4 col-sm-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <img src="{{ $product->images->first()->image_url ?? asset('images/default-product.jpg') }}"
                                         alt="{{ $product->name }}"
                                         class="card-img-top"
                                         style="height: 200px; object-fit: cover;">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-2">
                                            <a href="{{ route('products.show', $product) }}"
                                               class="text-decoration-none text-charcoal">
                                                {!! highlightText(Str::limit($product->name, 40), $query) !!}
                                            </a>
                                        </h6>
                                        @if($product->name_local)
                                            <p class="local-name small mb-2">
                                                {!! highlightText($product->name_local, $query) !!}
                                            </p>
                                        @endif
                                        <div class="mb-2">
                                            <span class="badge bg-light text-muted">
                                                {!! highlightText($product->category_label, $query) !!}
                                            </span>
                                            <span class="badge bg-light text-muted ms-1">
                                                {!! highlightText($product->ethnic_origin, $query) !!}
                                            </span>
                                        </div>
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
                </div>
            @endif

            <!-- Dishes -->
            @if(($type == 'all' || $type == 'dishes') && isset($results['dishes']) && $results['dishes']->count() > 0)
                <div class="mb-5">
                    <h3 class="fw-bold mb-4">Plats ({{ $results['dishes']->count() }})</h3>
                    <div class="row g-4">
                        @foreach($results['dishes'] as $dish)
                            <div class="col-md-4 col-sm-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <img src="{{ $dish->images->first()->image_url ?? asset('images/default-dish.jpg') }}"
                                         alt="{{ $dish->name }}"
                                         class="card-img-top"
                                         style="height: 200px; object-fit: cover;">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-2">
                                            <a href="{{ route('gastronomie.show', $dish) }}"
                                               class="text-decoration-none text-charcoal">
                                                {!! highlightText($dish->name, $query) !!}
                                            </a>
                                        </h6>
                                        @if($dish->name_local)
                                            <p class="local-name small mb-2">
                                                {!! highlightText($dish->name_local, $query) !!}
                                            </p>
                                        @endif
                                        <div class="mb-2">
                                            <span class="badge bg-light text-muted">
                                                {!! highlightText($dish->ethnic_origin, $query) !!}
                                            </span>
                                            <span class="badge bg-light text-muted ms-1">
                                                {!! highlightText($dish->region, $query) !!}
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                {!! highlightText($dish->category_label, $query) !!}
                                            </small>
                                            <a href="{{ route('gastronomie.show', $dish) }}"
                                               class="btn btn-sm btn-benin-red">
                                                Découvrir
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @else
            <!-- No Results -->
            <div class="no-results">
                <i class="bi bi-search fs-1 text-muted mb-3"></i>
                <h4>Aucun résultat trouvé pour "{{ $query }}"</h4>
                <p class="text-muted mb-4">Essayez de modifier vos termes de recherche</p>

                <!-- Suggestions -->
                <div class="search-suggestions justify-content-center">
                    @php
                        $suggestions = ['Masque', 'Sculpture', 'Amiwo', 'Couturier', 'Mécanicien', 'Tissu'];
                    @endphp
                    @foreach($suggestions as $suggestion)
                        <a href="?q={{ urlencode($suggestion) }}" class="suggestion-chip">
                            {{ $suggestion }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="no-results">
            <i class="bi bi-search fs-1 text-muted mb-3"></i>
            <h4>Que cherchez-vous ?</h4>
            <p class="text-muted mb-4">Recherchez des artisans, produits ou plats traditionnels</p>

            <!-- Quick Categories -->
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm text-center p-4">
                        <div class="result-icon result-artisan mb-3 mx-auto">
                            <i class="bi bi-tools"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Artisans</h5>
                        <p class="text-muted small mb-3">Trouvez des artisans qualifiés près de chez vous</p>
                        <a href="{{ route('artisans.index') }}" class="btn btn-outline-benin-green">
                            Explorer
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm text-center p-4">
                        <div class="result-icon result-product mb-3 mx-auto">
                            <i class="bi bi-palette"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Produits</h5>
                        <p class="text-muted small mb-3">Objets artisanaux authentiques du Bénin</p>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-benin-yellow">
                            Explorer
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm text-center p-4">
                        <div class="result-icon result-dish mb-3 mx-auto">
                            <i class="bi bi-egg-fried"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Gastronomie</h5>
                        <p class="text-muted small mb-3">Plats traditionnels et leurs histoires</p>
                        <a href="{{ route('gastronomie.index') }}" class="btn btn-outline-benin-red">
                            Explorer
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Helper function for highlighting text
function highlightText(text, query) {
    if (!query) return text;

    const regex = new RegExp(`(${query})`, 'gi');
    return text.replace(regex, '<span class="highlight">$1</span>');
}
</script>
@endpush

<?php
// Helper function for highlighting in Blade
if (!function_exists('highlightText')) {
    function highlightText($text, $query) {
        if (!$query) return e($text);

        $pattern = '/' . preg_quote($query, '/') . '/i';
        return preg_replace($pattern, '<span class="highlight">$0</span>', e($text));
    }
}
?>
