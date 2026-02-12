@extends('layouts.app')

@section('title', $vendor->name . ' - Vendeur Gastronomie Béninoise')

@push('styles')
    <style>
        :root {
            --benin-green: #009639;
            --benin-yellow: #FCD116;
            --benin-red: #E8112D;
            --terracotta: #D4774E;
            --beige: #F5E6D3;
            --charcoal: #2C3E50;
            --dark-green: #006d2c;
            --light-green: #00b44f;
        }

        /* Hero Section (même style que produits) */
        .vendor-hero {
            background: linear-gradient(135deg, var(--benin-green) 0%, var(--benin-red) 100%);
            padding: 6rem 0 4rem;
            position: relative;
            overflow: hidden;
            margin-bottom: 3rem;
        }

        .vendor-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.3;
        }

        .vendor-hero h1 {
            color: white;
            font-size: 3.5rem;
            font-weight: 900;
            text-shadow: 3px 3px 10px rgba(245, 239, 239, 0.6);
            margin-bottom: 1rem;
        }

        .vendor-hero .lead {
            color: rgba(255,255,255,0.95);
            font-size: 1.35rem;
            max-width: 800px;
            margin: 0 auto 2rem;
        }

        .vendor-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 1.2rem;
            justify-content: center;
        }

        .vendor-badge {
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            padding: 0.7rem 1.5rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .vendor-verified {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
        }

        /* Contenu principal */
        .vendor-main {
            padding: 4rem 0;
            background: #f8f9fa;
        }

        .vendor-info-card {
            background: white;
            border-radius: 25px;
            padding: 3rem;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            margin-bottom: 3rem;
        }

        .section-title {
            font-size: 2.2rem;
            font-weight: 900;
            color: var(--charcoal);
            margin-bottom: 2rem;
            position: relative;
            display: inline-block;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 120px;
            height: 5px;
            background: linear-gradient(90deg, var(--benin-green), var(--benin-yellow));
            border-radius: 5px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .info-item {
            background: rgba(0, 150, 57, 0.03);
            border-radius: 16px;
            padding: 1.8rem;
            border: 1px solid rgba(0, 150, 57, 0.1);
            transition: all 0.3s ease;
        }

        .info-item:hover {
            background: rgba(0, 150, 57, 0.08);
            transform: translateY(-8px);
            box-shadow: 0 10px 30px rgba(0, 150, 57, 0.15);
        }

        .info-icon {
            font-size: 2.5rem;
            color: var(--benin-green);
            margin-bottom: 1rem;
        }

        .info-label {
            color: #666;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .info-value {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--charcoal);
        }

        /* Plats */
        .dish-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 2.2rem;
        }

        .dish-card {
            background: white;
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .dish-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 20px 45px rgba(0, 150, 57, 0.25);
        }

        .dish-image {
            height: 240px;
            overflow: hidden;
            position: relative;
        }

        .dish-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.7s ease;
        }

        .dish-card:hover .dish-image img {
            transform: scale(1.15);
        }

        .dish-price {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--benin-green);
            color: white;
            padding: 0.7rem 1.4rem;
            border-radius: 50px;
            font-weight: 700;
            box-shadow: 0 5px 15px rgba(0,150,57,0.4);
            z-index: 10;
        }

        .card-body {
            padding: 1.5rem;
        }

        .dish-title {
            font-size: 1.4rem;
            font-weight: 800;
            margin-bottom: 0.8rem;
            color: var(--charcoal);
        }

        .dish-title a {
            color: var(--charcoal);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .dish-title a:hover {
            color: var(--benin-green);
        }

        .dish-description {
            color: #555;
            font-size: 0.98rem;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .card-footer {
            background: white;
            border: none;
            padding: 0 1.5rem 1.5rem;
        }

        .btn-benin {
            background: var(--benin-green);
            border: none;
            color: white;
            font-weight: 700;
            padding: 0.9rem 1.8rem;
            border-radius: 50px;
            transition: all 0.3s ease;
            width: 100%;
            display: block;
            text-align: center;
        }

        .btn-benin:hover {
            background: var(--dark-green);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,150,57,0.4);
            color: white;
        }
    </style>
@endpush

@section('content')
    <!-- Hero -->
    <div class="vendor-hero">
        <div class="container text-center">
            <h1>{{ $vendor->name }}</h1>
            <p class="lead">
                Découvrez les spécialités authentiques proposées par ce vendeur béninois
            </p>

            <div class="vendor-badges mt-4">
                <span class="vendor-badge">
                    <i class="bi bi-shop-window"></i> {{ ucfirst($vendor->type) }}
                </span>
                <span class="vendor-badge">
                    <i class="bi bi-geo-alt-fill"></i> {{ $vendor->city }}
                </span>
                @if($vendor->verified)
                    <span class="vendor-badge vendor-verified">
                        <i class="bi bi-check-circle-fill"></i> Vérifié
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="container vendor-main">
        <div class="vendor-info-card">
            <!-- À propos -->
            @if($vendor->description)
                <div class="mb-5">
                    <h2 class="section-title">
                        <i class="bi bi-info-circle-fill"></i> À propos de {{ $vendor->name }}
                    </h2>
                    <p class="lead text-muted lh-lg">{!! nl2br(e($vendor->description)) !!}</p>
                </div>
            @endif

            <!-- Infos pratiques -->
            <div class="row g-5 mb-5">
                <div class="col-md-6">
                    <h2 class="section-title">
                        <i class="bi bi-telephone-fill"></i> Contact & Horaires
                    </h2>
                    @if($vendor->phone)
                        <p class="mb-3"><strong>Téléphone :</strong> {{ $vendor->phone }}</p>
                    @endif
                    @if($vendor->whatsapp)
                        <p class="mb-3"><strong>WhatsApp :</strong> {{ $vendor->whatsapp }}</p>
                    @endif
                    <p><strong>Horaires :</strong> {{ $vendor->opening_hours ?? 'Non précisé' }}</p>
                </div>

                <div class="col-md-6">
                    <h2 class="section-title">
                        <i class="bi bi-geo-alt-fill"></i> Localisation
                    </h2>
                    <p class="mb-3">{{ $vendor->address ?? 'Adresse non précisée' }}</p>
                    <p class="mb-3"><strong>Ville :</strong> {{ $vendor->city }}</p>
                    @if($vendor->neighborhood)
                        <p><strong>Quartier :</strong> {{ $vendor->neighborhood }}</p>
                    @endif
                </div>
            </div>

            <!-- Plats proposés -->
            <h2 class="section-title">
                <i class="bi bi-dish"></i> Plats proposés ({{ $dishes->total() }})
            </h2>

            @if($dishes->isEmpty())
                <div class="alert alert-info text-center py-5">
                    <i class="bi bi-emoji-frown fs-4 d-block mb-3"></i>
                    Ce vendeur n'a pas encore ajouté de plats à son catalogue.
                </div>
            @else
                <div class="dish-grid">
                    @foreach($dishes as $dish)
                        <div class="dish-card">
                            <div class="dish-image">
                                @if($dish->images->first())
                                    <img src="{{ asset($dish->images->first()->image_url) }}" alt="{{ $dish->name }}">
                                @else
                                    <img src="{{ asset('images/default-dish.jpg') }}" alt="{{ $dish->name }}">
                                @endif

                                @if($dish->pivot && $dish->pivot->price)
                                    <div class="dish-price">
                                        {{ number_format($dish->pivot->price, 0, ',', ' ') }} FCFA
                                    </div>
                                @endif
                            </div>

                            <div class="card-body">
                                <h5 class="dish-title">
                                    <a href="{{ route('gastronomie.show', $dish) }}">
                                        {{ $dish->name }}
                                    </a>
                                </h5>
                                <p class="dish-description">
                                    {{ Str::limit($dish->description ?? 'Aucune description disponible', 90) }}
                                </p>
                            </div>

                            <div class="card-footer">
                                <a href="{{ route('gastronomie.show', $dish) }}" class="btn btn-benin">
                                    Voir le plat
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-5 d-flex justify-content-center">
                    {{ $dishes->links() }}
                </div>
            @endif

            <!-- Vendeurs similaires -->
            @if($similarVendors->isNotEmpty())
                <h2 class="section-title mt-5">
                    <i class="bi bi-shop-window"></i> Autres vendeurs similaires
                </h2>

                <div class="row g-4">
                    @foreach($similarVendors as $similar)
                        <div class="col-md-3">
                            <div class="card h-100 border-0 shadow-sm text-center p-4">
                                <h6 class="fw-bold mb-2">{{ $similar->name }}</h6>
                                <p class="text-muted small mb-3">{{ $similar->city }}</p>
                                <p class="text-success small mb-3">
                                    <strong>{{ $similar->dishes_count ?? 0 }}</strong> plats
                                </p>
                                <a href="{{ route('vendors.show', $similar) }}" class="btn btn-sm btn-outline-success">
                                    Voir le profil
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
