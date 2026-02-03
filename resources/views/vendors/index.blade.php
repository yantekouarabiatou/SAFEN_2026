@extends('layouts.app')

@section('title', 'Vendeurs & Restaurants - AFRI-HERITAGE')

@push('styles')
<style>
    .vendor-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease;
    }

    .vendor-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .vendor-image {
        height: 200px;
        overflow: hidden;
        position: relative;
    }

    .vendor-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .vendor-card:hover .vendor-image img {
        transform: scale(1.05);
    }

    .vendor-type {
        position: absolute;
        top: 15px;
        left: 15px;
        background: var(--benin-red);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .verified-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: var(--benin-green);
        color: white;
        padding: 4px 8px;
        border-radius: 20px;
        font-size: 0.7rem;
    }

    .specialty-tag {
        background: var(--beige);
        color: var(--charcoal);
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        margin-right: 0.25rem;
        margin-bottom: 0.25rem;
    }

    .opening-status {
        display: inline-flex;
        align-items: center;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .status-open {
        background: #d1fae5;
        color: #065f46;
    }

    .status-closed {
        background: #fee2e2;
        color: #991b1b;
    }

    @media (min-width: 768px) {
        .vendor-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }
    }

    @media (min-width: 992px) {
        .vendor-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="container">
        <!-- Header -->
        <div class="row mb-5">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('gastronomie.index') }}">Gastronomie</a></li>
                        <li class="breadcrumb-item active">Vendeurs & Restaurants</li>
                    </ol>
                </nav>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="display-6 fw-bold text-charcoal mb-2">Vendeurs & Restaurants</h1>
                        <p class="text-muted mb-0">Découvrez où déguster la cuisine béninoise authentique</p>
                    </div>
                    <a href="{{ route('vendors.create') }}" class="btn btn-benin-red rounded-pill">
                        <i class="bi bi-plus-circle me-2"></i> Ajouter mon établissement
                    </a>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-md-8">
                <form action="{{ route('vendors.index') }}" method="GET" class="d-flex gap-2">
                    <div class="flex-grow-1">
                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="Rechercher un vendeur, restaurant..."
                               value="{{ request('search') }}">
                    </div>
                    <button type="submit" class="btn btn-benin-red rounded-pill px-4">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>
            <div class="col-md-4">
                <div class="d-flex gap-2">
                    <select name="type" class="form-select" onchange="this.form.submit()">
                        <option value="">Type d'établissement</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                {{ \App\Models\Vendor::$typeLabels[$type] ?? $type }}
                            </option>
                        @endforeach
                    </select>
                    <select name="city" class="form-select" onchange="this.form.submit()">
                        <option value="">Toutes les villes</option>
                        @foreach($cities as $city)
                            <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                {{ $city }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Vendors Grid -->
        @if($vendors->count() > 0)
            <div class="vendor-grid">
                @foreach($vendors as $vendor)
                    <div class="vendor-card">
                        <!-- Image -->
                        <div class="vendor-image">
                            <a href="{{ route('vendors.show', $vendor) }}">
                                <img src="{{ asset('images/default-vendor.jpg') }}"
                                     alt="{{ $vendor->name }}">
                            </a>
                            <span class="vendor-type">
                                {{ $vendor->type_label }}
                            </span>
                            @if($vendor->verified)
                                <span class="verified-badge">
                                    <i class="bi bi-patch-check-fill me-1"></i> Vérifié
                                </span>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="p-3">
                            <!-- Name & Rating -->
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="fw-bold mb-0">
                                    <a href="{{ route('vendors.show', $vendor) }}"
                                       class="text-decoration-none text-charcoal">
                                        {{ $vendor->name }}
                                    </a>
                                </h5>
                                <div class="rating-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi {{ $i <= floor($vendor->rating_avg) ? 'bi-star-fill' : 'bi-star' }}"></i>
                                    @endfor
                                    <small class="text-muted ms-1">({{ $vendor->rating_count }})</small>
                                </div>
                            </div>

                            <!-- Location -->
                            <p class="text-muted small mb-2">
                                <i class="bi bi-geo-alt-fill text-benin-red me-1"></i>
                                {{ $vendor->location }}
                                @if(isset($vendor->distance))
                                    <span class="text-benin-green ms-2">
                                        • {{ round($vendor->distance, 1) }} km
                                    </span>
                                @endif
                            </p>

                            <!-- Description -->
                            @if($vendor->description)
                                <p class="text-muted small mb-2">
                                    {{ Str::limit($vendor->description, 100) }}
                                </p>
                            @endif

                            <!-- Specialties -->
                            @if($vendor->specialties && count($vendor->specialties) > 0)
                                <div class="d-flex flex-wrap mb-2">
                                    @foreach($vendor->specialties as $specialty)
                                        <span class="specialty-tag">{{ $specialty }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Opening Status -->
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    @if($vendor->opening_hours)
                                        @php
                                            $isOpen = $this->isOpenNow($vendor->opening_hours);
                                        @endphp
                                        <span class="opening-status {{ $isOpen ? 'status-open' : 'status-closed' }}">
                                            <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i>
                                            {{ $isOpen ? 'Ouvert' : 'Fermé' }}
                                        </span>
                                    @endif
                                </div>
                                <small class="text-muted">
                                    <i class="bi bi-eye me-1"></i> {{ $vendor->views }} vues
                                </small>
                            </div>

                            <!-- Actions -->
                            <div class="d-flex gap-2">
                                <a href="https://wa.me/{{ $vendor->whatsapp }}"
                                   target="_blank"
                                   class="btn btn-success btn-sm flex-grow-1 rounded-pill">
                                    <i class="bi bi-whatsapp me-1"></i> WhatsApp
                                </a>
                                <a href="tel:{{ $vendor->phone }}"
                                   class="btn btn-outline-benin-red btn-sm rounded-pill">
                                    <i class="bi bi-telephone"></i>
                                </a>
                                <a href="{{ route('vendors.show', $vendor) }}"
                                   class="btn btn-benin-red btn-sm rounded-pill">
                                    Voir
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($vendors->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    {{ $vendors->withQueryString()->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="bi bi-shop fs-1 text-muted mb-3"></i>
                <h4>Aucun vendeur trouvé</h4>
                <p class="text-muted mb-4">Essayez de modifier vos critères de recherche</p>
                <a href="{{ route('vendors.index') }}" class="btn btn-benin-red rounded-pill">
                    Voir tous les vendeurs
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function isOpenNow(openingHours) {
    if (!openingHours) return false;

    const now = new Date();
    const day = now.getDay(); // 0 = dimanche, 1 = lundi, etc.
    const currentTime = now.getHours() * 100 + now.getMinutes();

    const todaySchedule = openingHours[day];
    if (!todaySchedule || !todaySchedule.open) return false;

    const openTime = parseInt(todaySchedule.open.replace(':', ''));
    const closeTime = parseInt(todaySchedule.close.replace(':', ''));

    return currentTime >= openTime && currentTime <= closeTime;
}
</script>
@endpush
