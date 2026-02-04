@extends('layouts.app')

@section('title', $product->name . ' - AFRI-HERITAGE')

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

        /* Product Gallery avec Carousel */
        .product-gallery {
            position: sticky;
            top: 100px;
        }

        .main-carousel {
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            position: relative;
            height: 500px;
        }

        .carousel-inner {
            height: 100%;
            border-radius: 20px;
        }

        .carousel-item {
            height: 100%;
            transition: transform 0.6s ease-in-out;
        }

        .carousel-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Navigation du carousel */
        .carousel-control-prev,
        .carousel-control-next {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .main-carousel:hover .carousel-control-prev,
        .main-carousel:hover .carousel-control-next {
            opacity: 1;
        }

        .carousel-control-prev {
            left: 20px;
        }

        .carousel-control-next {
            right: 20px;
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-size: 70%;
            filter: invert(25%) sepia(89%) saturate(3849%) hue-rotate(338deg) brightness(94%) contrast(91%);
        }

        .carousel-control-prev:hover,
        .carousel-control-next:hover {
            background: var(--benin-red);
        }

        .carousel-control-prev:hover .carousel-control-prev-icon,
        .carousel-control-next:hover .carousel-control-next-icon {
            filter: invert(100%);
        }

        /* Indicateurs du carousel */
        .carousel-indicators {
            margin-bottom: 15px;
        }

        .carousel-indicators [data-bs-target] {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid white;
            background-color: rgba(255, 255, 255, 0.5);
            margin: 0 5px;
            transition: all 0.3s ease;
        }

        .carousel-indicators .active {
            background-color: var(--benin-red);
            transform: scale(1.2);
        }

        /* Navigation avec thumbnails */
        .thumbnails-carousel {
            margin-top: 1rem;
            position: relative;
        }

        .thumbnails-wrapper {
            display: flex;
            gap: 0.75rem;
            overflow-x: auto;
            padding: 10px;
            scroll-behavior: smooth;
        }

        .thumbnails-wrapper::-webkit-scrollbar {
            height: 6px;
        }

        .thumbnails-wrapper::-webkit-scrollbar-track {
            background: var(--beige);
            border-radius: 10px;
        }

        .thumbnails-wrapper::-webkit-scrollbar-thumb {
            background: var(--benin-red);
            border-radius: 10px;
        }

        .thumbnail-slide {
            width: 100px;
            height: 100px;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            border: 3px solid transparent;
            flex-shrink: 0;
            transition: all 0.3s ease;
            position: relative;
        }

        .thumbnail-slide.active {
            border-color: var(--benin-green);
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .thumbnail-slide:hover {
            border-color: var(--benin-red);
            transform: translateY(-3px);
        }

        .thumbnail-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .thumbnail-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .thumbnail-slide:hover .thumbnail-overlay {
            opacity: 1;
        }

        .thumbnail-overlay i {
            color: white;
            font-size: 1.5rem;
        }

        /* Auto-play controls */
        .carousel-autoplay-controls {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin-top: 1rem;
            padding: 10px;
            background: var(--beige);
            border-radius: 50px;
        }

        .autoplay-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            border: 2px solid var(--benin-green);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .autoplay-btn:hover {
            background: var(--benin-green);
            color: white;
            transform: scale(1.1);
        }

        .autoplay-btn i {
            font-size: 1rem;
        }

        .autoplay-speed {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: white;
            padding: 5px 15px;
            border-radius: 50px;
            border: 2px solid var(--beige);
        }

        .speed-btn {
            background: none;
            border: none;
            color: var(--charcoal);
            padding: 5px 10px;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .speed-btn.active {
            background: var(--benin-green);
            color: white;
        }

        .speed-btn:hover:not(.active) {
            background: var(--beige);
        }

        /* Animation pour le défilement automatique */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .carousel-item.active {
            animation: slideIn 0.6s ease;
        }

        /* Progress bar pour le défilement automatique */
        .carousel-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: rgba(255, 255, 255, 0.3);
            z-index: 10;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(to right, var(--benin-green), var(--benin-red));
            width: 0%;
            transition: width 0.1s linear;
        }

        /* Product Gallery */
        .product-gallery {
            position: sticky;
            top: 100px;
        }

        .main-image {
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }

        .main-image img {
            width: 100%;
            height: 500px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .main-image:hover img {
            transform: scale(1.05);
        }

        .thumbnails {
            display: flex;
            gap: 0.75rem;
            overflow-x: auto;
            padding-bottom: 0.5rem;
        }

        .thumbnails::-webkit-scrollbar {
            height: 6px;
        }

        .thumbnails::-webkit-scrollbar-track {
            background: var(--beige);
            border-radius: 10px;
        }

        .thumbnails::-webkit-scrollbar-thumb {
            background: var(--benin-red);
            border-radius: 10px;
        }

        .thumbnail {
            width: 100px;
            height: 100px;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            border: 3px solid transparent;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .thumbnail.active {
            border-color: var(--benin-green);
            transform: scale(1.05);
        }

        .thumbnail:hover {
            border-color: var(--benin-red);
        }

        .thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Action Cards */
        .action-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }

        .product-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--charcoal);
            margin-bottom: 1rem;
        }

        .local-name-display {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .local-name-display span {
            font-size: 1.3rem;
            color: var(--terracotta);
            font-style: italic;
            font-weight: 500;
        }

        .hero-audio-btn {
            background: linear-gradient(135deg, var(--benin-yellow) 0%, var(--terracotta) 100%);
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 6px 20px rgba(212, 119, 78, 0.4);
        }

        .hero-audio-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 25px rgba(212, 119, 78, 0.6);
        }

        .hero-audio-btn.playing {
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

        .hero-audio-btn i {
            color: white;
            font-size: 1.3rem;
        }

        /* Rating */
        .rating-section {
            display: flex;
            align-items: center;
            gap: 2rem;
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 2px solid var(--beige);
        }

        .rating-stars {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .rating-stars i {
            color: var(--benin-yellow);
            font-size: 1.2rem;
        }

        .rating-stars .text-muted {
            margin-left: 0.5rem;
        }

        /* Price Display */
        .price-section {
            background: linear-gradient(135deg, rgba(0, 150, 57, 0.1) 0%, rgba(245, 230, 211, 0.3) 100%);
            border-left: 5px solid var(--benin-green);
            padding: 1.5rem;
            border-radius: 15px;
            margin-bottom: 2rem;
        }

        .price-display {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--benin-green);
            margin-bottom: 0.5rem;
        }

        .price-subtitle {
            color: #666;
            font-size: 0.95rem;
        }

        /* Stock Alert */
        .stock-alert {
            padding: 1rem 1.5rem;
            border-radius: 15px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 2rem;
            font-weight: 600;
        }

        .stock-alert.in-stock {
            background: rgba(0, 150, 57, 0.1);
            color: var(--benin-green);
            border: 2px solid var(--benin-green);
        }

        .stock-alert.low-stock {
            background: rgba(212, 119, 78, 0.1);
            color: var(--terracotta);
            border: 2px solid var(--terracotta);
        }

        .stock-alert.out-of-stock {
            background: rgba(232, 17, 45, 0.1);
            color: var(--benin-red);
            border: 2px solid var(--benin-red);
        }

        /* Action Buttons */
        .action-buttons {
            display: grid;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .btn-primary-action {
            background: linear-gradient(135deg, var(--benin-green) 0%, #007a2e 100%);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }

        .btn-primary-action:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 150, 57, 0.4);
            color: white;
        }

        .btn-secondary-action {
            background: white;
            color: var(--benin-green);
            border: 2px solid var(--benin-green);
            padding: 1rem 2rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }

        .btn-secondary-action:hover {
            background: var(--benin-green);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 150, 57, 0.4);
        }

        /* Secondary Actions */
        .secondary-actions {
            display: flex;
            justify-content: center;
            gap: 1rem;
            padding-top: 1.5rem;
            border-top: 2px solid var(--beige);
        }

        .icon-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 2px solid var(--beige);
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .icon-btn:hover {
            border-color: var(--benin-red);
            background: var(--benin-red);
            color: white;
            transform: scale(1.1);
        }

        /* Artisan Card */
        .artisan-card {
            background: linear-gradient(135deg, rgba(0, 150, 57, 0.05) 0%, rgba(245, 230, 211, 0.3) 100%);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
        }

        .artisan-card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
        }

        .artisan-card h5 {
            color: var(--charcoal);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .artisan-card .craft-type {
            color: var(--benin-green);
            font-weight: 600;
            margin-bottom: 1rem;
        }

        /* Specifications */
        .specs-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-top: 2rem;
        }

        .spec-item {
            background: var(--beige);
            padding: 1.25rem;
            border-radius: 15px;
            transition: all 0.3s ease;
        }

        .spec-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        .spec-label {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 0.5rem;
        }

        .spec-value {
            font-weight: 700;
            color: var(--charcoal);
            font-size: 1.1rem;
        }

        /* Description Section */
        .description-section {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }

        .section-title {
            color: var(--charcoal);
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .section-title i {
            color: var(--benin-red);
            font-size: 1.5rem;
        }

        .section-title::after {
            content: '';
            flex: 1;
            height: 3px;
            background: linear-gradient(to right, var(--benin-red), transparent);
            border-radius: 10px;
        }

        /* Cultural Info */
        .cultural-info {
            background: linear-gradient(135deg, rgba(0, 150, 57, 0.1) 0%, rgba(232, 17, 45, 0.1) 100%);
            border-left: 5px solid var(--benin-red);
            padding: 1.5rem;
            border-radius: 15px;
            margin: 2rem 0;
        }

        .cultural-info h4 {
            color: var(--benin-red);
            font-weight: 700;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .cultural-info p {
            color: var(--charcoal);
            line-height: 1.8;
            margin: 0;
        }

        /* Delivery Info */
        .delivery-card ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .delivery-card li {
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--beige);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .delivery-card li:last-child {
            border-bottom: none;
        }

        .delivery-card i {
            color: var(--benin-green);
            font-size: 1.2rem;
        }

        /* Similar Products */
        .similar-products {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .similar-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .similar-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .similar-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .similar-card-body {
            padding: 1.25rem;
        }

        .similar-card-title {
            font-weight: 700;
            color: var(--charcoal);
            margin-bottom: 0.75rem;
        }

        .similar-card-title a {
            color: var(--charcoal);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .similar-card-title a:hover {
            color: var(--benin-red);
        }

        /* Back Button */
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--beige);
            color: var(--charcoal);
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-bottom: 2rem;
        }

        .back-button:hover {
            background: var(--benin-red);
            color: white;
            transform: translateX(-5px);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .product-gallery {
                position: relative;
                top: 0;
            }

            .main-image img {
                height: 400px;
            }

            .product-title {
                font-size: 2rem;
            }

            .specs-grid {
                grid-template-columns: 1fr;
            }
        }

        .artisan-avatar-container {
            width: 80px;
            height: 80px;
            margin: 0 auto 1rem;
            position: relative;
        }

        .artisan-avatar {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid var(--benin-green);
            box-shadow: 0 4px 12px rgba(0, 150, 57, 0.2);
        }

        .avatar-initials {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--benin-green) 0%, #007a2e 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: bold;
            font-family: 'Montserrat', sans-serif;
            border: 3px solid white;
            box-shadow: 0 4px 12px rgba(0, 150, 57, 0.3);
            text-transform: uppercase;
        }

        /* Optionnel : effet au survol */
        .artisan-card:hover .avatar-initials {
            transform: scale(1.08);
            transition: transform 0.3s ease;
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

        <!-- Back Button -->
        <a href="{{ route('products.index') }}" class="back-button">
            <i class="bi bi-arrow-left"></i>
            Retour à la liste
        </a>

        <div class="row">
            <!-- Gallery Column avec Carousel -->
            <div class="col-lg-7">
                <div class="product-gallery">
                    @if($product->images && $product->images->count() > 0)
                        <!-- Main Carousel -->
                        <div id="productCarousel" class="carousel slide main-carousel" data-bs-ride="carousel"
                            data-bs-interval="5000">
                            <!-- Indicateurs -->
                            @if($product->images->count() > 1)
                                <div class="carousel-indicators">
                                    @foreach($product->images as $index => $image)
                                        <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="{{ $index }}"
                                            class="{{ $index === 0 ? 'active' : '' }}" aria-label="Image {{ $index + 1 }}"></button>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Images du carousel -->
                            <div class="carousel-inner">
                                @foreach($product->images as $index => $image)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}" data-bs-interval="5000">
                                        <img src="{{ $image->full_url }}" class="d-block w-100"
                                            alt="{{ $product->name }} - Image {{ $index + 1 }}"
                                            onclick="openImageModal('{{ $image->full_url }}')">
                                        <!-- Progress bar pour le défilement automatique -->
                                        @if($index === 0)
                                            <div class="carousel-progress">
                                                <div class="progress-bar" id="carouselProgress"></div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <!-- Contrôles de navigation -->
                            @if($product->images->count() > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel"
                                    data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Précédent</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel"
                                    data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Suivant</span>
                                </button>
                            @endif
                        </div>

                        <!-- Thumbnails pour navigation -->
                        @if($product->images->count() > 1)
                            <div class="thumbnails-carousel">
                                <div class="thumbnails-wrapper" id="thumbnailsWrapper">
                                    @foreach($product->images as $index => $image)
                                        <div class="thumbnail-slide {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}"
                                            onclick="goToSlide({{ $index }})">
                                            <img src="{{ $image->full_url }}" alt="{{ $product->name }} - Miniature {{ $index + 1 }}">
                                            <div class="thumbnail-overlay">
                                                <i class="bi bi-zoom-in"></i>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Contrôles de défilement automatique -->
                                <div class="carousel-autoplay-controls">
                                    <button class="autoplay-btn" id="autoplayToggle" onclick="toggleAutoplay()"
                                        title="Activer/Désactiver le défilement automatique">
                                        <i class="bi bi-play-fill"></i>
                                    </button>

                                    <div class="autoplay-speed">
                                        <span style="font-size: 0.8rem; color: #666;">Vitesse:</span>
                                        <button class="speed-btn" data-speed="3000" onclick="changeSpeed(3000)">Lente</button>
                                        <button class="speed-btn active" data-speed="5000"
                                            onclick="changeSpeed(5000)">Normale</button>
                                        <button class="speed-btn" data-speed="2000" onclick="changeSpeed(2000)">Rapide</button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <!-- Image par défaut si aucune image -->
                        <div class="main-image">
                            <img src="{{ asset('images/default-product.jpg') }}" alt="{{ $product->name }}"
                                onclick="openImageModal('{{ asset('images/default-product.jpg') }}')">
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product Info Column (reste inchangé) -->
            <!-- Product Info Column -->
            <div class="col-lg-5">
                <div class="action-card">
                    <h1 class="product-title">{{ $product->name }}</h1>

                    <!-- Local Name with Audio -->
                    @if($product->name_local)
                        <div class="local-name-display">
                            <span><i class="bi bi-translate"></i> {{ $product->name_local }}</span>
                            @if($product->audio_url)
                                <button class="hero-audio-btn" onclick="playAudio(this, '{{ $product->audio_url }}')"
                                    title="Écouter la prononciation">
                                    <i class="bi bi-volume-up-fill"></i>
                                </button>
                            @endif
                        </div>
                    @endif

                    <!-- Rating & Views -->
                    <div class="rating-section">
                        <div class="rating-stars">
                            @php
                                $avgRating = $product->reviews->avg('rating') ?? 0;
                                $reviewCount = $product->reviews->count();
                            @endphp
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi {{ $i <= floor($avgRating) ? 'bi-star-fill' : 'bi-star' }}"></i>
                            @endfor
                            @if($reviewCount > 0)
                                <span class="text-muted">({{ $reviewCount }} avis)</span>
                            @endif
                        </div>
                        <div class="text-muted">
                            <i class="bi bi-eye-fill me-1"></i> {{ number_format($product->views) }} vues
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="price-section">
                        <div class="price-display">
                            {{ number_format($product->price, 0, ',', ' ') }} FCFA
                        </div>
                        <div class="price-subtitle">
                            <i class="bi bi-shield-check me-1"></i>
                            Prix fixe • Authenticité garantie
                        </div>
                    </div>

                    <!-- Stock Status -->
                    <div
                        class="stock-alert {{ $product->stock_status === 'in_stock' ? 'in-stock' : ($product->stock_status === 'low_stock' ? 'low-stock' : 'out-of-stock') }}">
                        <i
                            class="bi {{ $product->stock_status === 'in_stock' ? 'bi-check-circle-fill' : ($product->stock_status === 'low_stock' ? 'bi-exclamation-triangle-fill' : 'bi-x-circle-fill') }}"></i>
                        @if($product->stock_status === 'in_stock')
                            En stock • Livraison sous 3-5 jours
                        @elseif($product->stock_status === 'low_stock')
                            Plus que quelques pièces disponibles
                        @else
                            Rupture de stock • Commandes personnalisées possibles
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        @if($product->stock_status !== 'out_of_stock')
                            <button class="btn-primary-action" onclick="addToCart({{ $product->id }}, 1)">
                                <i class="bi bi-cart-plus-fill"></i> Ajouter au panier
                            </button>
                        @endif

                        <button class="btn-secondary-action" onclick="contactArtisan()">
                            <i class="bi bi-chat-left-text-fill"></i> Contacter l'artisan
                        </button>
                    </div>

                    <!-- Secondary Actions -->
                    <div class="secondary-actions">
                        <button class="icon-btn" onclick="toggleFavorite({{ $product->id }})" id="favorite-btn"
                            title="Favori">
                            <i class="bi bi-heart"></i>
                        </button>

                        <button class="icon-btn" onclick="shareProduct()" title="Partager">
                            <i class="bi bi-share"></i>
                        </button>

                        <button class="icon-btn" onclick="reportProduct({{ $product->id }})" title="Signaler">
                            <i class="bi bi-flag"></i>
                        </button>
                    </div>
                </div>

                <!-- Artisan Card -->
                @if($product->artisan)
                    <div class="action-card">
                        <div class="artisan-card">
                            <!-- Avatar / Initiales -->
                            <div class="artisan-avatar-container">
                                @if($product->artisan->photos && $product->artisan->photos->first())
                                    <img src="{{ Storage::url($product->artisan->photos->first()->photo_url) }}"
                                        alt="{{ $product->artisan->user->name ?? $product->artisan->business_name }}"
                                        class="artisan-avatar">
                                @else
                                    <!-- Affichage des initiales -->
                                    <div class="avatar-initials"
                                        title="{{ $product->artisan->user->name ?? $product->artisan->business_name }}">
                                        {{ strtoupper(substr($product->artisan->user->name ?? 'A', 0, 1)) .
                                        (str_word_count($product->artisan->user->name ?? '') > 1 
                                            ? strtoupper(substr(strrchr($product->artisan->user->name, ' '), 1, 1)) 
                                            : '') }}                                   
                                             </div>
                                @endif
                            </div>

                            <!-- Nom -->
                            <h5>
                                {{ $product->artisan->business_name ?? $product->artisan->user->name ?? 'Artisan' }}
                            </h5>

                            <!-- Métier -->
                            <div class="craft-type">
                                <i class="bi bi-tools me-1"></i>
                                {{ $product->artisan->craft_label ?? 'Artisan' }}
                            </div>

                            <!-- Note -->
                            <div class="rating-stars small mb-3">
                                @for($i = 1; $i <= 5; $i++)
                                    <i
                                        class="bi {{ $i <= floor($product->artisan->rating_avg ?? 0) ? 'bi-star-fill' : 'bi-star' }}"></i>
                                @endfor
                                <span class="text-muted ms-1">({{ $product->artisan->rating_count ?? 0 }})</span>
                            </div>

                            <!-- Boutons d'action -->
                            <div class="d-grid gap-2">
                                @if(Route::has('artisans.show'))
                                    <a href="{{ route('artisans.show', $product->artisan) }}"
                                        class="btn btn-outline-benin-green rounded-pill">
                                        Voir le profil
                                    </a>
                                @else
                                    <button class="btn btn-outline-benin-green rounded-pill" onclick="showArtisanInfo()">
                                        Voir les infos
                                    </button>
                                @endif

                                @if($product->artisan->whatsapp)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $product->artisan->whatsapp) }}"
                                        target="_blank" class="btn btn-success rounded-pill">
                                        <i class="bi bi-whatsapp me-2"></i> WhatsApp
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Delivery Info -->
                <div class="action-card delivery-card">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-truck me-2 text-benin-green"></i>
                        Livraison & Retours
                    </h5>
                    <ul>
                        <li>
                            <i class="bi bi-truck"></i>
                            <div>
                                <strong>Livraison :</strong> 3-5 jours ouvrables
                            </div>
                        </li>
                        <li>
                            <i class="bi bi-arrow-left-right"></i>
                            <div>
                                <strong>Retours :</strong> 14 jours après réception
                            </div>
                        </li>
                        <li>
                            <i class="bi bi-shield-check"></i>
                            <div>
                                <strong>Garantie :</strong> Authenticité garantie
                            </div>
                        </li>
                        <li>
                            <i class="bi bi-globe"></i>
                            <div>
                                <strong>International :</strong> Livraison mondiale
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Description & Details -->
        <div class="row mt-4">
            <div class="col-12">
                <!-- Cultural Description -->
                @if($product->description_cultural)
                    <div class="description-section">
                        <h2 class="section-title">
                            <i class="bi bi-book-fill"></i>
                            Histoire & Symbolique
                        </h2>
                        <div class="cultural-info">
                            <h4>
                                <i class="bi bi-info-circle-fill"></i>
                                Le saviez-vous ?
                            </h4>
                            <p>{!! nl2br(e($product->description_cultural)) !!}</p>
                        </div>
                    </div>
                @endif

                <!-- Specifications -->
                <div class="description-section">
                    <h2 class="section-title">
                        <i class="bi bi-list-check"></i>
                        Caractéristiques
                    </h2>
                    <div class="specs-grid">
                        @if($product->materials && count($product->materials) > 0)
                            <div class="spec-item">
                                <div class="spec-label">Matériaux</div>
                                <div class="spec-value">
                                    {{ implode(', ', $product->materials) }}
                                </div>
                            </div>
                        @endif

                        @if($product->width && $product->height)
                            <div class="spec-item">
                                <div class="spec-label">Dimensions</div>
                                <div class="spec-value">
                                    {{ $product->width }} ×
                                    {{ $product->height }}{{ $product->depth ? ' × ' . $product->depth : '' }} cm
                                </div>
                            </div>
                        @endif

                        @if($product->weight)
                            <div class="spec-item">
                                <div class="spec-label">Poids</div>
                                <div class="spec-value">{{ $product->weight }} kg</div>
                            </div>
                        @endif

                        <div class="spec-item">
                            <div class="spec-label">Origine ethnique</div>
                            <div class="spec-value">{{ $product->ethnic_origin }}</div>
                        </div>

                        <div class="spec-item">
                            <div class="spec-label">Catégorie</div>
                            <div class="spec-value">{{ $product->category_label }}</div>
                        </div>

                        <div class="spec-item">
                            <div class="spec-label">Disponibilité</div>
                            <div class="spec-value">
                                @if($product->stock_status === 'in_stock')
                                    <span class="text-success">En stock</span>
                                @elseif($product->stock_status === 'low_stock')
                                    <span style="color: var(--terracotta);">Stock bas</span>
                                @else
                                    <span class="text-danger">Rupture</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Similar Products -->
        @if(isset($similarProducts) && $similarProducts->count() > 0)
            <div class="row mt-5">
                <div class="col-12">
                    <h3 class="fw-bold mb-4">Produits similaires</h3>
                    <div class="similar-products">
                        @foreach($similarProducts as $similar)
                            <div class="similar-card">
                                @if($similar->images && $similar->images->first())
                                    <img src="{{ $similar->images->first()->image_url }}" alt="{{ $similar->name }}">
                                @else
                                    <img src="{{ asset('images/default-product.jpg') }}" alt="{{ $similar->name }}">
                                @endif
                                <div class="similar-card-body">
                                    <h6 class="similar-card-title">
                                        <a href="{{ route('products.show', $similar) }}">
                                            {{ Str::limit($similar->name, 30) }}
                                        </a>
                                    </h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold" style="color: var(--benin-green);">
                                            {{ number_format($similar->price, 0, ',', ' ') }} FCFA
                                        </span>
                                        <a href="{{ route('products.show', $similar) }}"
                                            class="btn btn-sm btn-benin-green rounded-pill">
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
        @if(isset($sameArtisanProducts) && $sameArtisanProducts->count() > 0)
            <div class="row mt-5">
                <div class="col-12">
                    <h3 class="fw-bold mb-4">Autres créations de {{ $product->artisan->user->name }}</h3>
                    <div class="similar-products">
                        @foreach($sameArtisanProducts as $other)
                            <div class="similar-card">
                                @if($other->images && $other->images->first())
                                    <img src="{{ $other->images->first()->image_url }}" alt="{{ $other->name }}">
                                @else
                                    <img src="{{ asset('images/default-product.jpg') }}" alt="{{ $other->name }}">
                                @endif
                                <div class="similar-card-body">
                                    <h6 class="similar-card-title">
                                        <a href="{{ route('products.show', $other) }}">
                                            {{ Str::limit($other->name, 30) }}
                                        </a>
                                    </h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold" style="color: var(--benin-green);">
                                            {{ number_format($other->price, 0, ',', ' ') }} FCFA
                                        </span>
                                        <a href="{{ route('products.show', $other) }}"
                                            class="btn btn-sm btn-benin-green rounded-pill">
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
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-body p-0 position-relative">
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3"
                        data-bs-dismiss="modal" style="z-index: 10;"></button>
                    <img id="modalImage" src="" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Variables pour le carousel
        let carouselInterval;
        let currentSpeed = 5000;
        let isAutoplayActive = true;
        let carouselInstance;

        // Initialisation du carousel quand le DOM est chargé
        document.addEventListener('DOMContentLoaded', function () {
            const carouselElement = document.getElementById('productCarousel');
            if (carouselElement) {
                carouselInstance = new bootstrap.Carousel(carouselElement, {
                    interval: currentSpeed,
                    wrap: true,
                    pause: 'hover'
                });

                // Mettre à jour la progress bar
                updateProgressBar();
            }

            // Synchroniser les thumbnails avec le carousel
            if (carouselElement) {
                carouselElement.addEventListener('slid.bs.carousel', function (event) {
                    updateActiveThumbnail(event.to);
                    updateProgressBar();
                    resetProgressBar();
                });
            }

            // Initialiser les thumbnails pour le défilement
            initializeThumbnailsScrolling();
        });

        // Fonction pour aller à une slide spécifique
        function goToSlide(index) {
            if (carouselInstance) {
                carouselInstance.to(index);
                updateActiveThumbnail(index);
                updateProgressBar();
                resetProgressBar();
            }
        }

        // Mettre à jour le thumbnail actif
        function updateActiveThumbnail(index) {
            document.querySelectorAll('.thumbnail-slide').forEach((thumb, i) => {
                thumb.classList.toggle('active', i === index);
            });
        }

        // Initialiser le défilement des thumbnails
        function initializeThumbnailsScrolling() {
            const wrapper = document.getElementById('thumbnailsWrapper');
            if (!wrapper) return;

            wrapper.addEventListener('wheel', function (e) {
                e.preventDefault();
                wrapper.scrollLeft += e.deltaY;
            });
        }

        // Ouvrir la modal d'image
        function openImageModal(imageUrl) {
            document.getElementById('modalImage').src = imageUrl;
            const modal = new bootstrap.Modal(document.getElementById('imageModal'));
            modal.show();
        }

        // Gestion du défilement automatique
        function toggleAutoplay() {
            const carouselElement = document.getElementById('productCarousel');
            const toggleBtn = document.getElementById('autoplayToggle');

            if (!carouselElement) return;

            if (isAutoplayActive) {
                // Désactiver le défilement automatique
                carouselElement.setAttribute('data-bs-ride', 'false');
                carouselInstance.pause();
                toggleBtn.innerHTML = '<i class="bi bi-pause-fill"></i>';
                toggleBtn.title = 'Activer le défilement automatique';
            } else {
                // Activer le défilement automatique
                carouselElement.setAttribute('data-bs-ride', 'carousel');
                carouselInstance.cycle();
                toggleBtn.innerHTML = '<i class="bi bi-play-fill"></i>';
                toggleBtn.title = 'Désactiver le défilement automatique';
                resetProgressBar();
            }

            isAutoplayActive = !isAutoplayActive;
        }

        // Changer la vitesse de défilement
        function changeSpeed(speed) {
            currentSpeed = speed;

            // Mettre à jour les boutons de vitesse
            document.querySelectorAll('.speed-btn').forEach(btn => {
                btn.classList.toggle('active', parseInt(btn.dataset.speed) === speed);
            });

            // Mettre à jour l'intervalle du carousel
            if (carouselInstance) {
                carouselInstance._config.interval = speed;

                if (isAutoplayActive) {
                    carouselInstance.pause();
                    carouselInstance.cycle();
                }

                resetProgressBar();
            }
        }

        // Mettre à jour la progress bar
        function updateProgressBar() {
            const progressBar = document.getElementById('carouselProgress');
            if (!progressBar) return;

            progressBar.style.width = '0%';
        }

        // Réinitialiser et démarrer la progress bar
        function resetProgressBar() {
            const progressBar = document.getElementById('carouselProgress');
            if (!progressBar || !isAutoplayActive) return;

            progressBar.style.width = '0%';

            // Animation de la progress bar
            let width = 0;
            const interval = 50; // Mise à jour toutes les 50ms
            const increment = (interval / currentSpeed) * 100;

            clearInterval(carouselInterval);

            carouselInterval = setInterval(() => {
                if (width >= 100) {
                    width = 0;
                    progressBar.style.width = '0%';
                } else {
                    width += increment;
                    progressBar.style.width = width + '%';
                }
            }, interval);
        }

        // Gestion de l'audio (votre fonction existante)
        let currentAudio = null;
        let currentButton = null;

        function playAudio(button, audioUrl) {
            // ... [votre code existant pour l'audio] ...
        }

        // ... [le reste de vos fonctions JavaScript existantes] ...

        // Démarrer le défilement automatique au chargement
        window.addEventListener('load', function () {
            if (isAutoplayActive) {
                resetProgressBar();
            }
        });

        // Pause au survol
        document.getElementById('productCarousel')?.addEventListener('mouseenter', function () {
            if (carouselInstance && isAutoplayActive) {
                carouselInstance.pause();
            }
        });

        document.getElementById('productCarousel')?.addEventListener('mouseleave', function () {
            if (carouselInstance && isAutoplayActive) {
                carouselInstance.cycle();
                resetProgressBar();
            }
        });

        // Touches clavier pour navigation
        document.addEventListener('keydown', function (e) {
            const carousel = document.getElementById('productCarousel');
            if (!carousel || document.activeElement.tagName === 'INPUT' || document.activeElement.tagName === 'TEXTAREA') {
                return;
            }

            switch (e.key) {
                case 'ArrowLeft':
                    e.preventDefault();
                    carouselInstance.prev();
                    break;
                case 'ArrowRight':
                    e.preventDefault();
                    carouselInstance.next();
                    break;
                case ' ':
                    e.preventDefault();
                    toggleAutoplay();
                    break;
            }
        });
    </script>
@endpush