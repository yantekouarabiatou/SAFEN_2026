@extends('layouts.app')

@section('title', 'TOTCHEMEGNON - Le Bénin authentique, raconté par l\'IA')

@push('styles')
<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    /* Hero Section amélioré */
    .hero-section {
        position: relative;
        min-height: 100vh;
        display: flex;
        align-items: center;
        overflow: hidden;
    }

    .hero-slider {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 0;
    }

    .hero-slider .swiper-slide {
        position: relative;
    }

    .hero-slider .swiper-slide::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(0, 150, 57, 0.651) 0%, rgba(94, 16, 16, 0.9) 100%);
        z-index: 1;
    }

    .hero-slider .swiper-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    /* Floating animation pour la carte IA */
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }

    .floating-card {
        animation: float 3s ease-in-out infinite;
    }

    /* Carousels personnalisés */
    .product-carousel .swiper-slide,
    .artisan-carousel .swiper-slide {
        height: auto;
    }

    .swiper-button-next,
    .swiper-button-prev {
        background: white;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .swiper-button-next:after,
    .swiper-button-prev:after {
        font-size: 20px;
        color: var(--benin-green);
        font-weight: bold;
    }

    .swiper-pagination-bullet {
        width: 12px;
        height: 12px;
        background: var(--benin-green);
        opacity: 0.4;
    }

    .swiper-pagination-bullet-active {
        opacity: 1;
        background: var(--benin-yellow);
    }

    /* Parallax effect */
    .parallax-section {
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }

    /* Category cards avec hover effects */
    .category-card {
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .category-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .category-card:hover::before {
        left: 100%;
    }

    .category-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    /* Product card améliorée */
    .product-card-enhanced {
        position: relative;
        overflow: hidden;
        border-radius: 15px;
        transition: all 0.3s ease;
    }

    .product-card-enhanced .product-image {
        position: relative;
        overflow: hidden;
        height: 250px;
    }

    .product-card-enhanced .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .product-card-enhanced:hover .product-image img {
        transform: scale(1.15);
    }

    .product-card-enhanced .product-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.7) 0%, transparent 60%);
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        align-items: flex-end;
        padding: 20px;
    }

    .product-card-enhanced:hover .product-overlay {
        opacity: 1;
    }

    /* Testimonial carousel */
    .testimonial-card {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        height: 100%;
    }

    /* Counter animation */
    .counter {
        font-size: 3rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--benin-green) 0%, var(--benin-yellow) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Search bar améliorée */
    .search-bar-enhanced {
        background: white;
        border-radius: 50px;
        padding: 8px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    }

    .search-bar-enhanced input {
        border: none;
        background: transparent;
        padding: 12px 24px;
    }

    .search-bar-enhanced input:focus {
        outline: none;
        box-shadow: none;
    }

    /* Bento grid pour la section culture */
    .bento-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    .bento-item {
        border-radius: 20px;
        overflow: hidden;
        position: relative;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .bento-item:hover {
        transform: scale(1.05);
    }

    .bento-item:nth-child(1) {
        grid-column: span 2;
        grid-row: span 2;
    }

    .bento-item:nth-child(2) {
        grid-column: span 2;
    }

    .bento-item:nth-child(3) {
        grid-column: span 2;
    }

    @media (max-width: 768px) {
        .bento-grid {
            grid-template-columns: 1fr;
        }

        .bento-item:nth-child(1) {
            grid-column: span 1;
            grid-row: span 1;
        }

        .hero-section {
            min-height: 70vh;
        }

        .counter {
            font-size: 2rem;
        }

    }
    .testimonial-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 15px;
        padding: 1.5rem;
        height: 100%;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .testimonial-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 150, 57, 0.1);
        border-color: var(--benin-green);
    }

    .rating-stars {
        color: #FFD700;
        font-size: 1.1rem;
    }

    .testimonial-carousel {
        position: relative;
        padding: 0 40px;
    }

    .testimonial-carousel .swiper-button-next,
    .testimonial-carousel .swiper-button-prev {
        color: var(--benin-green);
        background: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .testimonial-carousel .swiper-button-next:after,
    .testimonial-carousel .swiper-button-prev:after {
        font-size: 1.2rem;
    }

    .testimonial-carousel .swiper-pagination-bullet {
        background: #dee2e6;
        opacity: 1;
    }

    .testimonial-carousel .swiper-pagination-bullet-active {
        background: var(--benin-green);
    }

    @media (max-width: 768px) {
        .testimonial-carousel {
            padding: 0 20px;
        }

        .testimonial-carousel .swiper-button-next,
        .testimonial-carousel .swiper-button-prev {
            display: none;
        }
    }
</style>
@endpush

@push('styles')
<style>
    .testimonial-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 15px;
        padding: 1.5rem;
        height: 100%;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .testimonial-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 150, 57, 0.1);
        border-color: var(--benin-green);
    }

    .rating-stars {
        color: #FFD700;
        font-size: 1.1rem;
    }

    .testimonial-carousel {
        position: relative;
        padding: 0 40px;
    }

    .testimonial-carousel .swiper-button-next,
    .testimonial-carousel .swiper-button-prev {
        color: var(--benin-green);
        background: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .testimonial-carousel .swiper-button-next:after,
    .testimonial-carousel .swiper-button-prev:after {
        font-size: 1.2rem;
    }

    .testimonial-carousel .swiper-pagination-bullet {
        background: #dee2e6;
        opacity: 1;
    }

    .testimonial-carousel .swiper-pagination-bullet-active {
        background: var(--benin-green);
    }

    @media (max-width: 768px) {
        .testimonial-carousel {
            padding: 0 20px;
        }

        .testimonial-carousel .swiper-button-next,
        .testimonial-carousel .swiper-button-prev {
            display: none;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section avec Slider -->
<section class="hero-section">
    <!-- Background Slider -->
    <div class="hero-slider swiper">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <img src="{{ asset('artisans/artisan3.jpg') }}" alt="{{ __('messages.artisan_beninois') }}">
            </div>
            <div class="swiper-slide">
                <img src="{{ asset('dishes/ebaSauegombo.jpg') }}" alt="{{ __('messages.artisanat_traditionnel') }}">
            </div>
            <div class="swiper-slide">
                <img src="{{ asset('products/tissu.jpg') }}" alt="{{ __('messages.culture_beninoise') }}">
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="container hero-content">
        <div class="row align-items-center">
            <div class="col-lg-7" data-aos="fade-right">
                <div class="mb-3">
                    <span class="badge bg-benin-yellow text-charcoal px-3 py-2 rounded-pill">
                        🇧🇯 {{ __('messages.propulse_par_ia') }}
                    </span>
                </div>

                <h1 class="display-3 fw-bold text-white mb-4">
                    {{ __('messages.artisanat_beninois') }}<br>
                    <span class="text-benin-yellow">{{ __('messages.a_portee_de_clic') }}</span>
                </h1>

                <p class="lead text-white mb-4 fs-4" style="max-width: 600px;">
                    {{ __('messages.decouvrez_comprenez_acquerir') }}
                </p>

                <!-- Search Bar -->
                <div class="search-bar-enhanced mb-4" style="max-width: 650px;">
                    <form action="{{ route('search') }}" method="GET">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-search fs-5 text-muted ms-3"></i>
                            <input type="text" class="form-control form-control-lg flex-grow-1"
                                   name="q"
                                   placeholder="{{ __('messages.rechercher_artisan_produit_plat') }}"
                                   aria-label="{{ __('messages.recherche') }}">
                            <button class="btn btn-benin-green rounded-pill px-4 me-2" type="submit">
                                <i class="bi bi-arrow-right me-2"></i> {{ __('messages.rechercher') }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Quick Stats -->
                <div class="row g-4 mt-2">
                    <div class="col-6 col-md-3">
                        <div class="counter" data-target="{{ $stats['artisans'] }}">{{ $stats['artisans'] }}</div>
                        <small class="text-white-50 d-block">{{ __('messages.artisans') }}</small>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="counter" data-target="{{ $stats['products'] }}">{{ $stats['products'] }}</div>
                        <small class="text-white-50 d-block">{{ __('messages.produits') }}</small>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="counter" data-target="{{ $stats['dishes'] }}">{{ $stats['dishes'] }}</div>
                        <small class="text-white-50 d-block">{{ __('messages.plats') }}</small>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="counter" data-target="12">0</div>
                        <small class="text-white-50 d-block">{{ __('messages.departements') }}</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 d-none d-lg-block" data-aos="fade-left">
                <div class="card floating-card border-0 shadow-lg">
                    <div class="card-body text-center p-5">
                        <div class="rounded-circle bg-gradient d-inline-flex align-items-center justify-content-center mb-4"
                             style="width: 100px; height: 100px; background: linear-gradient(135deg, var(--benin-green), var(--navy));">
                            <i class="bi bi-robot text-dark" style="font-size: 3rem;"></i>
                        </div>
                        <h4 class="fw-bold mb-3">{{ __('messages.anansi_assistant_ia') }}</h4>
                        <p class="text-muted mb-4">
                            {{ __('messages.votre_guide_culturel') }}
                        </p>
                        <button class="btn btn-benin-green w-100 rounded-pill"
                                onclick="document.querySelector('.chatbot-btn').click()">
                            <i class="bi bi-chat-left-text me-2"></i> {{ __('messages.parler_a_anansi') }}
                        </button>
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="bi bi-check-circle-fill text-success me-1"></i> {{ __('messages.disponible_24_7') }}
                                <i class="bi bi-translate text-info ms-2 me-1"></i> {{ __('messages.multilingue') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5 bg-beige">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="badge bg-benin-green text-white px-3 py-2 rounded-pill mb-3">
                {{ __('messages.explorez_categories') }}
            </span>
            <h2 class="display-5 fw-bold text-charcoal mb-3">{{ __('messages.decouvrez_benin_authentique') }}</h2>
            <p class="text-muted fs-5" style="max-width: 700px; margin: 0 auto;">
                {{ __('messages.plongez_richesse_culturelle') }}
            </p>
        </div>

        <div class="row g-4">
            <!-- Artisans -->
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="category-card card border-0 shadow-sm h-100">
                    <div class="card-body p-4 text-center">
                        <div class="mb-4">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                 style="width: 90px; height: 90px; background: linear-gradient(135deg, var(--benin-green), #00c04b);">
                                <i class="bi bi-tools text-white" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold mb-3">{{ __('messages.artisans_services') }}</h4>
                        <p class="text-muted mb-4">
                            {{ __('messages.trouvez_artisans_qualifies') }}
                        </p>
                        <ul class="list-unstyled text-start mb-4 small text-muted">
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-benin-green me-2"></i> {{ __('messages.geolocalisation_precise') }}</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-benin-green me-2"></i> {{ __('messages.avis_verifies') }}</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-benin-green me-2"></i> {{ __('messages.contact_direct') }}</li>
                        </ul>
                        <a href="{{ route('artisans.vue') }}" class="btn btn-benin-green w-100 rounded-pill">
                            <i class="bi bi-compass me-2"></i> {{ __('messages.explorer_artisans') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Marketplace -->
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="category-card card border-0 shadow-sm h-100">
                    <div class="card-body p-4 text-center">
                        <div class="mb-4">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                 style="width: 90px; height: 90px; background: linear-gradient(135deg, var(--benin-yellow), #ffe14d);">
                                <i class="bi bi-palette text-charcoal" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold mb-3">{{ __('messages.arts_artisanat') }}</h4>
                        <p class="text-muted mb-4">
                            {{ __('messages.achetez_objets_authentiques') }}
                        </p>
                        <ul class="list-unstyled text-start mb-4 small text-muted">
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-gold me-2"></i> {{ __('messages.produits_authentiques') }}</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-gold me-2"></i> {{ __('messages.histoire_culturelle_ia') }}</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-gold me-2"></i> {{ __('messages.livraison_securisee') }}</li>
                        </ul>
                        <a href="{{ route('products.index') }}" class="btn btn-benin-yellow w-100 rounded-pill text-charcoal">
                            <i class="bi bi-compass me-2"></i> {{ __('messages.voir_marketplace') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Gastronomie -->
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="category-card card border-0 shadow-sm h-100">
                    <div class="card-body p-4 text-center">
                        <div class="mb-4">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                 style="width: 90px; height: 90px; background: linear-gradient(135deg, var(--benin-red), #ff2d47);">
                                <i class="bi bi-egg-fried text-white" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold mb-3">{{ __('messages.gastronomie') }}</h4>
                        <p class="text-muted mb-4">
                            {{ __('messages.decouvrez_saveurs_authentiques') }}
                        </p>
                        <ul class="list-unstyled text-start mb-4 small text-muted">
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-benin-red me-2"></i> {{ __('messages.recettes_traditionnelles') }}</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-benin-red me-2"></i> {{ __('messages.audio_prononciation') }}</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-benin-red me-2"></i> {{ __('messages.origine_culturelle') }}</li>
                        </ul>
                        <a href="{{ route('gastronomie.index') }}" class="btn btn-benin-red w-100 rounded-pill">
                            <i class="bi bi-compass me-2"></i> {{ __('messages.decouvrir_plats') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Carousel -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5" data-aos="fade-up">
            <div>
                <span class="badge bg-benin-yellow text-charcoal px-3 py-2 rounded-pill mb-2">
                    ✨ {{ __('messages.selection') }}
                </span>
                <h2 class="display-6 fw-bold text-charcoal mb-2">{{ __('messages.produits_en_vedette') }}</h2>
                <p class="text-muted">{{ __('messages.selection_coup_de_coeur') }}</p>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-outline-benin-green rounded-pill d-none d-md-inline-flex">
                {{ __('messages.voir_tous') }} <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>

        <div class="product-carousel swiper" data-aos="fade-up" data-aos-delay="100">
            <div class="swiper-wrapper">
                @foreach($featuredProducts as $product)
                <div class="swiper-slide">
                    <div class="product-card-enhanced card border-0 shadow-sm h-100">
                        <div class="product-image">
                            <img src="{{ $product->primaryImage->image_url ?? asset('images/default-product.jpg') }}"
                                 alt="{{ $product->name }}">

                            @if($product->featured)
                                <span class="position-absolute top-0 start-0 m-3 badge bg-benin-yellow text-charcoal rounded-pill px-3 py-2">
                                    <i class="bi bi-star-fill me-1"></i> {{ __('messages.vedette') }}
                                </span>
                            @endif

                            <button class="position-absolute top-0 end-0 m-3 btn btn-light rounded-circle"
                                    style="width: 40px; height: 40px;">
                                <i class="bi bi-heart"></i>
                            </button>

                            <div class="product-overlay">
                                <div class="w-100">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('products.show', $product) }}"
                                           class="btn btn-light flex-grow-1">
                                            <i class="bi bi-eye me-1"></i> {{ __('messages.voir') }}
                                        </a>
                                        <button class="btn btn-benin-green"
                                                onclick="speakText('{{ $product->name_local ?? $product->name }}')">
                                            <i class="bi bi-volume-up"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-3">
                            <h6 class="card-title mb-0 fw-bold">{{ Str::limit($product->name, 35) }}</h6>

                            <p class="text-muted small mb-2">
                                <i class="bi bi-person me-1"></i> {{ $product->artisan->user->name }}
                            </p>

                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-light text-muted me-2">{{ $product->category }}</span>
                                <small class="text-muted">{{ $product->ethnic_origin }}</small>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-benin-green fw-bold fs-5">{{ $product->formatted_price }}</span>
                                <span class="text-muted small">
                                    <i class="bi bi-eye me-1"></i> {{ rand(50, 500) }} {{ __('messages.vues') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination mt-4"></div>
        </div>

        <div class="text-center mt-4 d-md-none">
            <a href="{{ route('products.index') }}" class="btn btn-outline-benin-green rounded-pill">
                {{ __('messages.voir_tous_produits') }} <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Featured Artisans Carousel -->
<section class="py-5 bg-beige">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5" data-aos="fade-up">
            <div>
                <span class="badge bg-benin-green text-white px-3 py-2 rounded-pill mb-2">
                    🏆 {{ __('messages.top_artisans') }}
                </span>
                <h2 class="display-6 fw-bold text-charcoal mb-2">{{ __('messages.artisans_du_mois') }}</h2>
                <p class="text-muted">{{ __('messages.artisans_mieux_notes') }}</p>
            </div>
            <a href="{{ route('artisans.vue') }}" class="btn btn-outline-benin-green rounded-pill d-none d-md-inline-flex">
                {{ __('messages.voir_tous') }} <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>

        <div class="artisan-carousel swiper" data-aos="fade-up" data-aos-delay="100">
            <div class="swiper-wrapper">
                @foreach($featuredArtisans as $artisan)
                <div class="swiper-slide">
                    <div class="card border-0 shadow-sm h-100 overflow-hidden">
                        <div class="position-relative">
                            <img src="{{ $artisan->photos->first()->photo_url ?? asset('images/default-artisan.jpg') }}"
                                 alt="{{ $artisan->user->name }}"
                                 class="card-img-top"
                                 style="height: 250px; object-fit: cover;">

                            @if($artisan->verified)
                                <span class="position-absolute top-0 end-0 m-3 badge bg-benin-green rounded-pill px-3 py-2">
                                    <i class="bi bi-patch-check-fill me-1"></i> {{ __('messages.verifie') }}
                                </span>
                            @endif

                            <div class="position-absolute bottom-0 start-0 m-3">
                                <span class="badge bg-success rounded-pill px-3 py-2">
                                    <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i> {{ __('messages.disponible') }}
                                </span>
                            </div>
                        </div>

                        <div class="card-body text-center p-4">
                            <h5 class="card-title fw-bold mb-2">{{ $artisan->user->name }}</h5>
                            <p class="text-benin-green fw-semibold mb-3">
                                <i class="bi bi-tools me-1"></i> {{ $artisan->craft_label }}
                            </p>

                            <div class="rating-stars mb-3">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi {{ $i <= floor($artisan->rating_avg) ? 'bi-star-fill' : 'bi-star' }}"></i>
                                @endfor
                                <span class="text-muted ms-2 small">({{ $artisan->rating_count }} {{ __('messages.avis') }})</span>
                            </div>

                            <p class="text-muted small mb-3">
                                <i class="bi bi-geo-alt-fill text-benin-red me-1"></i>
                                {{ $artisan->city }}, {{ $artisan->neighborhood }}
                            </p>

                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="bi bi-calendar-check me-1"></i>
                                    {{ $artisan->years_experience }}+ {{ __('messages.ans_experience') }}
                                </small>
                            </div>

                            <div class="d-flex gap-2">
                                <a href="https://wa.me/{{ $artisan->whatsapp }}" target="_blank"
                                   class="btn btn-success flex-fill rounded-pill">
                                    <i class="bi bi-whatsapp"></i>
                                </a>
                                <a href="tel:{{ $artisan->phone }}"
                                   class="btn btn-outline-benin-green flex-fill rounded-pill">
                                    <i class="bi bi-telephone"></i>
                                </a>
                                <a href="{{ route('artisans.show', $artisan) }}"
                                   class="btn btn-benin-green flex-fill rounded-pill">
                                    {{ __('messages.profil') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination mt-4"></div>
        </div>
    </div>
</section>

<!-- Testimonials Carousel -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="badge bg-benin-red text-white px-3 py-2 rounded-pill mb-2">
                💬 {{ __('messages.temoignages') }}
            </span>
            <h2 class="display-6 fw-bold text-charcoal mb-2">{{ __('messages.ce_que_disent_utilisateurs') }}</h2>
            <p class="text-muted">{{ __('messages.experiences_communaute') }}</p>
        </div>

        @if($testimonials->count() > 0)
            <div class="testimonial-carousel swiper" data-aos="fade-up" data-aos-delay="100">
                <div class="swiper-wrapper pb-5">
                    @foreach($testimonials as $testimonial)
                        @php
                            $userName = $testimonial->user->name ?? 'Utilisateur';
                            $userImage = $testimonial->user->avatar_url ?? asset('images/default-user.jpg');
                            $userRole = $testimonial->reviewable->craft_label ?? 'Client';
                            $userCity = $testimonial->reviewable->city ?? '';
                        @endphp

                        <div class="swiper-slide">
                            <div class="testimonial-card">
                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ $userImage }}"
                                         alt="{{ $userName }}"
                                         class="rounded-circle me-3"
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $userName }}</h6>
                                        <small class="text-muted">
                                            {{ $userRole }}
                                            @if($userCity)
                                                • {{ $userCity }}
                                            @endif
                                        </small>
                                    </div>
                                </div>
                                <div class="rating-stars mb-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= $testimonial->rating ? '-fill' : '' }} text-warning"></i>
                                    @endfor
                                    <small class="text-muted ms-2">{{ $testimonial->rating }}/5</small>
                                </div>
                                <p class="text-muted mb-0">
                                    "{{ Str::limit($testimonial->comment, 180) }}"
                                </p>
                                @isset($testimonial->created_at)
                                    <small class="text-benin-green mt-2 d-block">
                                        <i class="bi bi-calendar me-1"></i>
                                        {{ $testimonial->created_at->format('d/m/Y') }}
                                    </small>
                                @endisset
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="swiper-pagination"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        @else
            <div class="text-center py-4">
                <i class="bi bi-chat-quote fs-1 text-muted mb-3"></i>
                <p class="text-muted">{{ __('messages.soyez_premier_partager') }}</p>
                <a href="{{ route('artisans.index') }}" class="btn btn-benin-green">
                    {{ __('messages.decouvrir_artisans') }}
                </a>
            </div>
        @endif

        @auth
            <div class="text-center mt-5">
                <div class="card border-0 shadow-sm" style="max-width: 600px; margin: 0 auto;">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">{{ __('messages.partagez_experience') }}</h5>
                        <p class="text-muted mb-3">
                            {{ __('messages.avis_aide_communaute') }}
                        </p>
                        <a href="{{ route('artisans.vue') }}" class="btn btn-benin-green">
                            <i class="bi bi-star me-2"></i> {{ __('messages.laisser_avis') }}
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center mt-5">
                <p class="text-muted mb-3">{{ __('messages.connectez_vous_partager') }}</p>
                <a href="{{ route('login') }}" class="btn btn-outline-benin-green me-2">
                    <i class="bi bi-box-arrow-in-right me-2"></i> {{ __('messages.se_connecter') }}
                </a>
                <a href="{{ route('register') }}" class="btn btn-benin-green">
                    <i class="bi bi-person-plus me-2"></i> {{ __('messages.sinscrire') }}
                </a>
            </div>
        @endauth
    </div>
</section>

<!-- Culture Section -->
<section class="py-5 parallax-section"
         style="background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.8)), url('{{ asset('products/tissu.jpg') }}');">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 text-white mb-4 mb-lg-0" data-aos="fade-right">
                <span class="badge bg-benin-yellow text-charcoal px-3 py-2 rounded-pill mb-3">
                    📚 {{ __('messages.culture_patrimoine') }}
                </span>
                <h2 class="display-5 fw-bold mb-4">{{ __('messages.decouvrez_histoire_benin') }}</h2>

                <div class="culture-fact card bg-dark border-benin-yellow border-2 mb-4">
                    <div class="card-body">
                        <h5 class="text-benin-yellow fw-bold mb-3" id="fact-title"></h5>
                        <p class="text-white-50 mb-3" id="fact-content"></p>
                        <button class="btn btn-outline-benin-yellow btn-sm rounded-pill" onclick="loadNewFact()">
                            <i class="bi bi-arrow-repeat me-2" style="color: aliceblue"></i> {{ __('messages.autre_anecdote') }}
                        </button>
                    </div>
                </div>

                <div class="card bg-white bg-opacity-10 border-0 backdrop-blur">
                    <div class="card-body">
                        <h6 class="text-benin-yellow mb-3">
                            <i class="bi bi-robot me-2"></i> {{ __('messages.posez_question_anansi') }}
                        </h6>
                        <div class="input-group">
                            <input type="text" class="form-control bg-dark text-white border-0"
                                   id="quick-question"
                                   placeholder="{{ __('messages.exemple_zangbeto') }}">
                            <button class="btn btn-benin-green" onclick="askQuickQuestion()">
                                <i class="bi bi-send"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7" data-aos="fade-left">
                <div class="bento-grid">
                    <div class="bento-item bg-benin-green d-flex align-items-center justify-content-center p-5">
                        <div class="text-center text-white">
                            <i class="bi bi-globe fs-1 mb-3"></i>
                            <h3 class="fw-bold mb-2">12 {{ __('messages.departements') }}</h3>
                            <p class="mb-0">{{ __('messages.diversite_culturelle_unique') }}</p>
                        </div>
                    </div>

                    <div class="bento-item bg-benin-yellow d-flex align-items-center justify-content-center p-4">
                        <div class="text-center text-charcoal">
                            <i class="bi bi-people fs-1 mb-2"></i>
                            <h4 class="fw-bold mb-1">50+ {{ __('messages.ethnies') }}</h4>
                            <small>{{ __('messages.richesse_patrimoniale') }}</small>
                        </div>
                    </div>

                    <div class="bento-item bg-benin-red d-flex align-items-center justify-content-center p-4">
                        <div class="text-center text-white">
                            <i class="bi bi-translate fs-1 mb-2"></i>
                            <h4 class="fw-bold mb-1">60+ {{ __('messages.langues') }}</h4>
                            <small>{{ __('messages.diversite_linguistique') }}</small>
                        </div>
                    </div>

                    <div class="bento-item bg-terracotta d-flex align-items-center justify-content-center p-4">
                        <div class="text-center text-white">
                            <i class="bi bi-award fs-1 mb-2"></i>
                            <h4 class="fw-bold mb-1">UNESCO</h4>
                            <small>{{ __('messages.sites_classes') }}</small>
                        </div>
                    </div>

                    <div class="bento-item bg-navy d-flex align-items-center justify-content-center p-4">
                        <div class="text-center text-white">
                            <i class="bi bi-star fs-1 mb-2"></i>
                            <h4 class="fw-bold mb-1">{{ __('messages.vaudou') }}</h4>
                            <small>{{ __('messages.berceau_mondial') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5" style="background: linear-gradient(135deg, var(--benin-green) 0%, var(--navy) 100%);">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-8" data-aos="zoom-in">
                <span class="badge bg-benin-yellow text-charcoal px-4 py-2 rounded-pill mb-3 fs-6">
                    🚀 {{ __('messages.rejoignez_nous') }}
                </span>
                <h2 class="display-4 text-white fw-bold mb-4">
                    {{ __('messages.pret_decouvrir_benin_authentique') }}
                </h2>
                <p class="text-white-50 fs-5 mb-5" style="max-width: 700px; margin: 0 auto;">
                    {{ __('messages.rejoignez_communaute_valorisation') }}
                </p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="{{ route('register') }}" class="btn btn-benin-yellow btn-lg rounded-pill px-5">
                        <i class="bi bi-person-plus me-2"></i> {{ __('messages.creer_mon_compte') }}
                    </a>
                    <a href="{{ route('artisans.vue') }}" class="btn btn-outline-light btn-lg rounded-pill px-5">
                        <i class="bi bi-compass me-2"></i> {{ __('messages.explorer') }}
                    </a>
                </div>

                <div class="mt-5 pt-4 border-top border-white border-opacity-25">
                    <div class="row g-4 text-white-50">
                        <div class="col-6 col-md-3">
                            <i class="bi bi-shield-check fs-3 text-benin-yellow mb-2"></i>
                            <div class="small">{{ __('messages.paiements_secures') }}</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <i class="bi bi-truck fs-3 text-benin-yellow mb-2"></i>
                            <div class="small">{{ __('messages.livraison_garantie') }}</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <i class="bi bi-patch-check fs-3 text-benin-yellow mb-2"></i>
                            <div class="small">{{ __('messages.artisans_verifies') }}</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <i class="bi bi-headset fs-3 text-benin-yellow mb-2"></i>
                            <div class="small">{{ __('messages.support_24_7') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- AOS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<script>
// AOS
AOS.init({ duration: 800, once: true, offset: 100 });

// Hero Slider
new Swiper('.hero-slider', {
    loop: true,
    autoplay: { delay: 5000, disableOnInteraction: false },
    effect: 'fade',
    fadeEffect: { crossFade: true },
    speed: 1500
});

// Product Carousel
new Swiper('.product-carousel', {
    slidesPerView: 1,
    spaceBetween: 20,
    loop: true,
    autoplay: { delay: 3000, disableOnInteraction: false },
    pagination: { el: '.swiper-pagination', clickable: true },
    navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
    breakpoints: {
        640: { slidesPerView: 2, spaceBetween: 20 },
        768: { slidesPerView: 3, spaceBetween: 25 },
        1024: { slidesPerView: 4, spaceBetween: 30 }
    }
});

// Artisan Carousel
new Swiper('.artisan-carousel', {
    slidesPerView: 1,
    spaceBetween: 20,
    loop: true,
    autoplay: { delay: 4000, disableOnInteraction: false },
    pagination: { el: '.swiper-pagination', clickable: true },
    navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
    breakpoints: {
        640: { slidesPerView: 2, spaceBetween: 20 },
        768: { slidesPerView: 3, spaceBetween: 25 },
        1024: { slidesPerView: 4, spaceBetween: 30 }
    }
});

// Testimonial Carousel
new Swiper('.testimonial-carousel', {
    slidesPerView: 1,
    spaceBetween: 30,
    loop: true,
    autoplay: { delay: 5000, disableOnInteraction: false },
    pagination: { el: '.swiper-pagination', clickable: true },
    breakpoints: {
        768: { slidesPerView: 2, spaceBetween: 30 },
        1024: { slidesPerView: 3, spaceBetween: 30 }
    }
});

// Counter Animation
function animateCounter(el) {
    const target = parseInt(el.dataset.target);
    const duration = 2000;
    const step = target / (duration / 16);
    let current = 0;

    const timer = setInterval(() => {
        current += step;
        if (current >= target) {
            el.textContent = target + '+';
            clearInterval(timer);
        } else {
            el.textContent = Math.floor(current);
        }
    }, 16);
}

const counters = document.querySelectorAll('.counter');
const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
        if (entry.isIntersecting && !entry.target.classList.contains('animated')) {
            animateCounter(entry.target);
            entry.target.classList.add('animated');
        }
    });
}, { threshold: 0.5 });

counters.forEach(counter => observer.observe(counter));

// Culture Facts (multilingue)
const cultureFacts = [
    {
        title: "{{ __('messages.masque_gueledede') }}",
        content: "{{ __('messages.masque_gueledede_desc') }}"
    },
    {
        title: "{{ __('messages.route_esclave') }}",
        content: "{{ __('messages.route_esclave_desc') }}"
    },
    {
        title: "{{ __('messages.tissu_kente') }}",
        content: "{{ __('messages.tissu_kente_desc') }}"
    },
    {
        title: "{{ __('messages.tata_somba') }}",
        content: "{{ __('messages.tata_somba_desc') }}"
    },
    {
        title: "{{ __('messages.berceau_vaudou') }}",
        content: "{{ __('messages.berceau_vaudou_desc') }}"
    },
    {
        title: "{{ __('messages.amazones_dahomey') }}",
        content: "{{ __('messages.amazones_dahomey_desc') }}"
    },
    {
        title: "{{ __('messages.royaume_abomey') }}",
        content: "{{ __('messages.royaume_abomey_desc') }}"
    }
];

function loadNewFact() {
    const fact = cultureFacts[Math.floor(Math.random() * cultureFacts.length)];
    document.getElementById('fact-title').textContent = fact.title;
    document.getElementById('fact-content').textContent = fact.content;
}

// Quick Question to Anansi
function askQuickQuestion() {
    const question = document.getElementById('quick-question').value.trim();
    if (question) {
        document.querySelector('.chatbot-btn').click();
        setTimeout(() => {
            const chatbot = Alpine.$data(document.querySelector('[x-data="chatbot()"]'));
            if (chatbot) {
                chatbot.input = question;
                chatbot.sendMessage();
                document.getElementById('quick-question').value = '';
            }
        }, 500);
    }
}

// Text-to-Speech (langue dynamique)
function speakText(text) {
    if ('speechSynthesis' in window) {
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = '{{ app()->getLocale() }}' === 'fon' ? 'fr-FR' : '{{ app()->getLocale() }}-{{ strtoupper(app()->getLocale()) }}';
        window.speechSynthesis.speak(utterance);
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    loadNewFact();
});
</script>
@endpush