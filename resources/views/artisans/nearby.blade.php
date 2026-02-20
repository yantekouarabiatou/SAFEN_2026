@extends('layouts.app')

@section('title', 'Artisans à proximité - TOTCHEMEGNON')

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

    .page-header {
        background: linear-gradient(135deg, var(--benin-green) 0%, var(--benin-red) 100%);
        color: white;
        padding: 5rem 0 4rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 3rem;
        border-radius: 0 0 30px 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.06'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        opacity: 0.15;
    }

    .filter-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid var(--beige);
    }

    .form-range {
        height: 10px;
    }

    .form-range::-webkit-slider-thumb {
        background: var(--benin-green);
        border: 3px solid white;
        box-shadow: 0 2px 8px rgba(0, 150, 57, 0.4);
    }

    .form-range::-moz-range-thumb {
        background: var(--benin-green);
        border: 3px solid white;
        box-shadow: 0 2px 8px rgba(0, 150, 57, 0.4);
    }

    .artisan-card {
        border: none;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.08);
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        background: white;
        height: 100%;
    }

    .artisan-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 20px 50px rgba(0, 150, 57, 0.18);
    }

    .artisan-img {
        height: 220px;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .artisan-card:hover .artisan-img {
        transform: scale(1.08);
    }

    .badge-craft {
        background: var(--benin-yellow);
        color: var(--charcoal);
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 50px;
    }

    .distance-badge {
        background: var(--benin-green);
        color: white;
        font-weight: 600;
        padding: 0.4rem 1rem;
        border-radius: 50px;
        font-size: 0.9rem;
    }

    .btn-benin {
        background: linear-gradient(135deg, var(--benin-red) 0%, var(--terracotta) 100%);
        border: none;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-benin:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(232, 17, 45, 0.3);
        color: white;
    }

    .empty-state {
        background: white;
        border-radius: 20px;
        padding: 5rem 2rem;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        text-align: center;
    }

    .empty-state i {
        font-size: 5rem;
        color: var(--beige);
        margin-bottom: 1.5rem;
    }
</style>
@endpush

@section('content')
<!-- Hero / Header -->
<section class="page-header">
    <div class="container position-relative">
        <div class="text-center">
            <h1 class="display-4 fw-bold mb-3">Artisans à proximité</h1>
            <p class="lead text-white-75 mb-0">
                Découvrez les talents artisanaux près de chez vous
            </p>
        </div>
    </div>
</section>

<div class="container py-5">
    <div class="row">
        <!-- Filtres -->
        <div class="col-lg-3">
            <div class="filter-card sticky-top" style="top: 2rem;">
                <h5 class="fw-bold mb-4">
                    <i class="bi bi-funnel me-2 text-benin-green"></i>
                    Filtres
                </h5>

                <form method="GET" action="{{ route('artisans.nearby') }}">
                    <div class="mb-4">
                        <label for="radius" class="form-label fw-bold">
                            Rayon de recherche
                        </label>
                        <input type="range" class="form-range" 
                               min="1" max="50" step="1" 
                               id="radius" name="radius" 
                               value="{{ $radius ?? 10 }}" 
                               onchange="updateRadiusValue(this.value)">
                        <div class="text-center mt-2">
                            <span id="radiusValue" class="fw-bold fs-5">{{ $radius ?? 10 }}</span> km
                        </div>
                    </div>

                    <input type="hidden" name="latitude" value="{{ $latitude }}">
                    <input type="hidden" name="longitude" value="{{ $longitude }}">

                    <button type="submit" class="btn btn-benin w-100 rounded-pill py-2">
                        <i class="bi bi-search me-2"></i> Appliquer
                    </button>
                </form>
            </div>
        </div>

        <!-- Liste des artisans -->
        <div class="col-lg-9">
            @if($artisans->count() > 0)
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0 fw-bold">
                        {{ $artisans->total() }} artisans trouvés
                    </h4>
                    <small class="text-muted">
                        Distance calculée depuis votre position
                    </small>
                </div>

                <div class="row g-4">
                    @foreach($artisans as $artisan)
                        <div class="col-md-6 col-lg-4">
                            <div class="artisan-card">
                                <img src="{{ $artisan->photos->first()?->full_url ?? asset('images/default-artisan.jpg') }}"
                                     class="card-img-top artisan-img"
                                     alt="{{ $artisan->user->name }}"
                                     loading="lazy">

                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title fw-bold mb-2">
                                        {{ $artisan->user->name }}
                                    </h5>

                                    <div class="mb-3">
                                        <span class="badge badge-craft me-2">
                                            {{ $artisan->craft_label }}
                                        </span>
                                        <span class="badge distance-badge">
                                            {{ round($artisan->distance ?? 0, 1) }} km
                                        </span>
                                    </div>

                                    <p class="text-muted mb-3">
                                        {{ $artisan->city }}
                                        @if($artisan->neighborhood)
                                            - {{ $artisan->neighborhood }}
                                        @endif
                                    </p>

                                    <div class="mt-auto">
                                        <a href="{{ route('artisans.show', $artisan) }}"
                                           class="btn btn-benin rounded-pill w-100">
                                            Voir le profil
                                            <i class="bi bi-arrow-right ms-2"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-5 d-flex justify-content-center">
                    {{ $artisans->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="empty-state">
                    <i class="bi bi-geo-alt-fill"></i>
                    <h4 class="mt-3">Aucun artisan trouvé</h4>
                    <p class="text-muted mb-4">
                        Élargissez le rayon de recherche ou vérifiez votre position.
                    </p>
                    <a href="{{ route('artisans.nearby') }}" class="btn btn-benin rounded-pill px-5">
                        Réinitialiser
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function updateRadiusValue(value) {
    document.getElementById('radiusValue').textContent = value;
}
</script>
@endsection