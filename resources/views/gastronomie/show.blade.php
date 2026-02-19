@extends('layouts.app')

@section('title', $dish->name . ' - Gastronomie Béninoise')

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

        /* Hero Section du plat amélioré */
        .dish-hero {
            position: relative;
            height: 600px;
            overflow: hidden;
            margin-bottom: 4rem;
        }

        .dish-hero-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(0.7);
            transition: transform 0.5s ease;
        }

        .dish-hero:hover .dish-hero-image {
            transform: scale(1.05);
        }

        .dish-hero-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.95) 0%, rgba(0, 0, 0, 0.6) 40%, transparent 100%);
            padding: 4rem 0 3rem;
        }

        .dish-hero-content {
            position: relative;
            z-index: 2;
        }

        .dish-hero h1 {
            color: white;
            font-size: 3.5rem;
            font-weight: 800;
            text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.7);
            margin-bottom: 1rem;
            letter-spacing: -0.5px;
        }

        .dish-local-name {
            color: var(--benin-yellow);
            font-size: 1.8rem;
            font-style: italic;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        /* Audio Button dans le hero */
        .hero-audio-btn {
            background: linear-gradient(135deg, var(--benin-yellow) 0%, var(--terracotta) 100%);
            border: 3px solid rgba(255, 255, 255, 0.3);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 8px 25px rgba(212, 119, 78, 0.5);
            margin-left: 1.5rem;
            vertical-align: middle;
            position: relative;
        }

        .hero-audio-btn::before {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            border-radius: 50%;
            border: 2px solid var(--benin-yellow);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .hero-audio-btn:hover::before {
            opacity: 1;
            animation: rippleEffect 1s infinite;
        }

        @keyframes rippleEffect {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            100% {
                transform: scale(1.3);
                opacity: 0;
            }
        }

        .hero-audio-btn:hover {
            transform: scale(1.15);
            box-shadow: 0 12px 35px rgba(212, 119, 78, 0.7);
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
                transform: scale(1.2);
            }
        }

        .hero-audio-btn i {
            color: white;
            font-size: 1.5rem;
        }

        /* Badges du hero améliorés */
        .hero-badges {
            display: flex;
            gap: 1.25rem;
            flex-wrap: wrap;
            margin-top: 2rem;
        }

        .hero-badge {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 2px solid rgba(255, 255, 255, 0.25);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
        }

        .hero-badge:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        .hero-badge i {
            font-size: 1.3rem;
        }

        /* Navigation */
        .dish-breadcrumb {
            background: white;
            padding: 1.25rem 2rem;
            border-radius: 50px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
            display: inline-block;
        }

        .dish-breadcrumb .breadcrumb {
            margin: 0;
            background: transparent;
            padding: 0;
        }

        .dish-breadcrumb .breadcrumb-item a {
            color: var(--benin-green);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .dish-breadcrumb .breadcrumb-item a:hover {
            color: var(--benin-red);
        }

        /* Section principale améliorée */
        .dish-content-section {
            background: white;
            border-radius: 25px;
            padding: 3rem;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            margin-bottom: 2.5rem;
            transition: all 0.3s ease;
        }

        .dish-content-section:hover {
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }

        .section-title {
            color: var(--charcoal);
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            position: relative;
            padding-bottom: 1rem;
        }

        .section-title i {
            color: var(--benin-red);
            font-size: 1.8rem;
            background: linear-gradient(135deg, var(--benin-red) 0%, var(--terracotta) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--benin-red), var(--benin-yellow));
            border-radius: 10px;
        }

        /* Description améliorée */
        .dish-description {
            font-size: 1.15rem;
            line-height: 2;
            color: var(--charcoal);
            margin-bottom: 2rem;
            text-align: justify;
        }

        /* Ingrédients améliorés */
        .ingredients-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2rem;
        }

        .ingredient-item {
            background: linear-gradient(135deg, var(--beige) 0%, #ede3d1 100%);
            padding: 1.25rem 1.5rem;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .ingredient-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.5s;
        }

        .ingredient-item:hover::before {
            left: 100%;
        }

        .ingredient-item:hover {
            border-color: var(--benin-green);
            transform: translateX(8px);
            box-shadow: 0 6px 20px rgba(0, 150, 57, 0.2);
        }

        .ingredient-item i {
            color: var(--benin-green);
            font-size: 1.4rem;
            min-width: 24px;
        }

        .ingredient-item span {
            color: var(--charcoal);
            font-weight: 600;
            font-size: 1rem;
        }

        /* Préparation améliorée */
        .preparation-steps {
            counter-reset: step-counter;
            list-style: none;
            padding: 0;
        }

        .preparation-step {
            counter-increment: step-counter;
            position: relative;
            padding-left: 5rem;
            margin-bottom: 2.5rem;
            line-height: 1.9;
            font-size: 1.05rem;
            color: var(--charcoal);
        }

        .preparation-step::before {
            content: counter(step-counter);
            position: absolute;
            left: 0;
            top: 0;
            width: 55px;
            height: 55px;
            background: linear-gradient(135deg, var(--benin-red) 0%, var(--terracotta) 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.4rem;
            box-shadow: 0 6px 20px rgba(232, 17, 45, 0.4);
            border: 3px solid white;
        }

        .preparation-step:hover::before {
            transform: scale(1.1) rotate(5deg);
            transition: transform 0.3s ease;
        }

        /* Occasions améliorées */
        .occasions-list {
            display: flex;
            flex-wrap: wrap;
            gap: 1.25rem;
        }

        .occasion-tag {
            background: linear-gradient(135deg, var(--benin-green) 0%, #007a2e 100%);
            color: white;
            padding: 1rem 2rem;
            border-radius: 50px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            box-shadow: 0 6px 18px rgba(0, 150, 57, 0.4);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 1rem;
            position: relative;
            overflow: hidden;
        }

        .occasion-tag::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.4s, height 0.4s;
        }

        .occasion-tag:hover::before {
            width: 200%;
            height: 200%;
        }

        .occasion-tag:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 150, 57, 0.6);
        }

        .occasion-tag i {
            font-size: 1.2rem;
        }

        /* Informations culturelles améliorées */
        .cultural-info {
            background: linear-gradient(135deg, rgba(0, 150, 57, 0.08) 0%, rgba(232, 17, 45, 0.08) 100%);
            border-left: 6px solid var(--benin-red);
            padding: 2rem;
            border-radius: 20px;
            margin: 2.5rem 0;
            position: relative;
        }

        .cultural-info::before {
            content: '"';
            position: absolute;
            top: -10px;
            left: 20px;
            font-size: 5rem;
            color: var(--benin-red);
            opacity: 0.1;
            font-family: Georgia, serif;
        }

        .cultural-info h4 {
            color: var(--benin-red);
            font-weight: 800;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.3rem;
        }

        .cultural-info p {
            color: var(--charcoal);
            line-height: 1.9;
            margin: 0;
            font-size: 1.05rem;
        }

        /* Galerie d'images améliorée */
        .dish-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 2.5rem;
        }

        .gallery-item {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            aspect-ratio: 1;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .gallery-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.6), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 1;
        }

        .gallery-item:hover::before {
            opacity: 1;
        }

        .gallery-item::after {
            content: '\F33E';
            font-family: 'bootstrap-icons';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            color: white;
            font-size: 3rem;
            z-index: 2;
            transition: transform 0.3s ease;
        }

        .gallery-item:hover::after {
            transform: translate(-50%, -50%) scale(1);
        }

        .gallery-item:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.25);
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .gallery-item:hover img {
            transform: scale(1.1);
        }

        /* Où déguster amélioré */
        .restaurants-list {
            display: grid;
            gap: 1.75rem;
        }

        .restaurant-card {
            background: white;
            border: 2px solid var(--beige);
            border-radius: 20px;
            padding: 2rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .restaurant-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 0;
            background: linear-gradient(to bottom, var(--benin-red), var(--benin-green));
            transition: height 0.3s ease;
        }

        .restaurant-card:hover::before {
            height: 100%;
        }

        .restaurant-card:hover {
            border-color: var(--benin-red);
            box-shadow: 0 10px 30px rgba(232, 17, 45, 0.2);
            transform: translateX(5px);
        }

        .restaurant-card h5 {
            color: var(--charcoal);
            font-weight: 800;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.2rem;
        }

        .restaurant-card h5 i {
            color: var(--benin-red);
            font-size: 1.4rem;
        }

        .restaurant-info {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            color: #666;
            font-size: 1rem;
        }

        .restaurant-info div {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .restaurant-info i {
            color: var(--benin-green);
            width: 24px;
            font-size: 1.1rem;
        }

        /* Navigation entre plats améliorée */
        .dish-navigation {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2.5rem;
            margin-top: 4rem;
        }

        .nav-dish-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            color: inherit;
            position: relative;
        }

        .nav-dish-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, var(--benin-red) 0%, var(--benin-green) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 0;
        }

        .nav-dish-card:hover::before {
            opacity: 0.05;
        }

        .nav-dish-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }

        .nav-dish-image {
            height: 200px;
            overflow: hidden;
            position: relative;
        }

        .nav-dish-image::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, transparent, rgba(0, 0, 0, 0.3));
        }

        .nav-dish-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .nav-dish-card:hover .nav-dish-image img {
            transform: scale(1.15);
        }

        .nav-dish-content {
            padding: 1.75rem;
            position: relative;
            z-index: 1;
        }

        .nav-dish-label {
            color: var(--benin-red);
            font-size: 0.9rem;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            letter-spacing: 0.5px;
        }

        .nav-dish-name {
            color: var(--charcoal);
            font-size: 1.3rem;
            font-weight: 800;
            line-height: 1.4;
        }

        /* Stats améliorées */
        .dish-stats {
            display: flex;
            gap: 2.5rem;
            margin-top: 2.5rem;
            padding-top: 2.5rem;
            border-top: 3px solid var(--beige);
            flex-wrap: wrap;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.5rem;
            background: var(--beige);
            border-radius: 15px;
            transition: all 0.3s ease;
        }

        .stat-item:hover {
            background: linear-gradient(135deg, var(--beige) 0%, #ede3d1 100%);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--benin-red) 0%, var(--terracotta) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 6px 20px rgba(232, 17, 45, 0.3);
        }

        .stat-content strong {
            display: block;
            color: var(--charcoal);
            font-size: 1.5rem;
            font-weight: 800;
        }

        .stat-content span {
            color: #666;
            font-size: 0.95rem;
            font-weight: 600;
        }

        /* Bouton de retour amélioré */
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            background: white;
            color: var(--charcoal);
            padding: 1rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-bottom: 2.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border: 2px solid var(--beige);
        }

        .back-button:hover {
            background: linear-gradient(135deg, var(--benin-red) 0%, var(--terracotta) 100%);
            color: white;
            transform: translateX(-8px);
            box-shadow: 0 6px 20px rgba(232, 17, 45, 0.3);
            border-color: var(--benin-red);
        }

        .back-button i {
            transition: transform 0.3s ease;
        }

        .back-button:hover i {
            transform: translateX(-4px);
        }

        /* Lightbox amélioré */
        .lightbox {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.95);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            cursor: pointer;
            opacity: 0;
            animation: fadeIn 0.3s forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }

        .lightbox img {
            max-width: 90%;
            max-height: 90%;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.8);
            animation: zoomIn 0.3s;
        }

        @keyframes zoomIn {
            from {
                transform: scale(0.8);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Responsive */
        @media (max-width: 992px) {
            .dish-hero {
                height: 500px;
            }

            .dish-hero h1 {
                font-size: 2.5rem;
            }
        }

        @media (max-width: 768px) {
            .dish-hero {
                height: 400px;
            }

            .dish-hero h1 {
                font-size: 2rem;
            }

            .dish-local-name {
                font-size: 1.3rem;
            }

            .dish-content-section {
                padding: 2rem;
            }

            .section-title {
                font-size: 1.5rem;
            }

            .ingredients-grid {
                grid-template-columns: 1fr;
            }

            .preparation-step {
                padding-left: 4rem;
            }

            .preparation-step::before {
                width: 45px;
                height: 45px;
            }

            .dish-stats {
                gap: 1.5rem;
            }

            .hero-audio-btn {
                width: 50px;
                height: 50px;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Hero Section -->
    <div class="dish-hero">
        @if($dish->images->first())
            <img src="{{ asset($dish->images->first()->image_url) }}" alt="{{ $dish->name }}" class="dish-hero-image">
        @else
            <img src="{{ asset('images/default-dish.jpg') }}" alt="{{ $dish->name }}" class="dish-hero-image">
        @endif

        <div class="dish-hero-overlay">
            <div class="container">
                <div class="dish-hero-content">
                    <div class="d-flex align-items-center flex-wrap">
                        <h1>{{ $dish->name }}</h1>
                        @if($dish->audio_url)
                            <button class="hero-audio-btn" onclick="playAudio(this, '{{ asset($dish->audio_url) }}')"
                                title="Écouter la prononciation" aria-label="Écouter la prononciation">
                                <i class="bi bi-volume-up-fill"></i>
                            </button>
                        @endif
                    </div>

                    @if($dish->name_local)
                        <div class="dish-local-name">
                            <i class="bi bi-translate"></i> {{ $dish->name_local }}
                        </div>
                    @endif

                    <div class="hero-badges">
                        <span class="hero-badge">
                            <i class="bi bi-tag-fill"></i>
                            {{ $dish->category_label }}
                        </span>
                        <span class="hero-badge">
                            <i class="bi bi-people-fill"></i>
                            {{ $dish->ethnic_origin }}
                        </span>
                        <span class="hero-badge">
                            <i class="bi bi-geo-alt-fill"></i>
                            {{ $dish->region }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Breadcrumb & Back Button -->
        <div class="dish-breadcrumb">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('gastronomie.index') }}">Gastronomie</a></li>
                    <li class="breadcrumb-item active">{{ Str::limit($dish->name, 30) }}</li>
                </ol>
            </nav>
        </div>

        <a href="{{ route('gastronomie.index') }}" class="back-button">
            <i class="bi bi-arrow-left"></i>
            Retour à la liste
        </a>

        <div class="row">
            <div class="col-lg-8">
                <!-- Description -->
                @if($dish->description)
                    <div class="dish-content-section">
                        <h2 class="section-title">
                            <i class="bi bi-file-text-fill"></i>
                            Description
                        </h2>
                        <div class="dish-description">
                            {!! nl2br(e($dish->description)) !!}
                        </div>
                    </div>
                @endif

                <!-- Histoire & Culture -->
                @if($dish->history)
                    <div class="dish-content-section">
                        <h2 class="section-title">
                            <i class="bi bi-book-fill"></i>
                            Histoire & Culture
                        </h2>
                        <div class="cultural-info">
                            <h4>
                                <i class="bi bi-info-circle-fill"></i>
                                Le saviez-vous ?
                            </h4>
                            <p>{!! nl2br(e($dish->history)) !!}</p>
                        </div>
                    </div>
                @endif

                <!-- Ingrédients -->
                @if($dish->ingredients && count($dish->ingredients) > 0)
                    <div class="dish-content-section">
                        <h2 class="section-title">
                            <i class="bi bi-basket-fill"></i>
                            Ingrédients
                        </h2>
                        <div class="ingredients-grid">
                            @foreach($dish->ingredients as $ingredient)
                                <div class="ingredient-item">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span>{{ $ingredient }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Préparation -->
                @if($dish->preparation)
                    <div class="dish-content-section">
                        <h2 class="section-title">
                            <i class="bi bi-fire"></i>
                            Préparation
                        </h2>
                        <ol class="preparation-steps">
                            @foreach(explode("\n", $dish->preparation) as $step)
                                @if(trim($step))
                                    <li class="preparation-step">{{ trim($step) }}</li>
                                @endif
                            @endforeach
                        </ol>
                    </div>
                @endif

                <!-- Galerie d'images -->
                @if($dish->images->count() > 1)
                    <div class="dish-content-section">
                        <h2 class="section-title">
                            <i class="bi bi-images"></i>
                            Galerie
                        </h2>
                        <div class="dish-gallery">
                            @foreach($dish->images as $image)
                                <div class="gallery-item" onclick="openLightbox('{{ asset($image->image_url) }}')">
                                    <img src="{{ asset($image->image_url) }}" alt="{{ $dish->name }}">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-4">
                <!-- Occasions -->
                @if($dish->occasions)
                    <div class="dish-content-section">
                        <h2 class="section-title">
                            <i class="bi bi-calendar-event-fill"></i>
                            Occasions
                        </h2>
                        <div class="occasions-list">
                            @foreach(explode(',', $dish->occasions) as $occasion)
                                <span class="occasion-tag">
                                    <i class="bi bi-star-fill"></i>
                                    {{ trim($occasion) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Où déguster - Vendors réels -->
                @if($vendors && $vendors->count() > 0)
                    <div class="dish-content-section">
                        <h2 class="section-title">
                            <i class="bi bi-shop"></i>
                            Où déguster ({{ $vendors->count() }})
                        </h2>
                        <div class="restaurants-list">
                            @foreach($vendors as $vendor)
                                <div class="restaurant-card">
                                    <h5>
                                        <i class="bi bi-pin-map-fill"></i>
                                        {{ $vendor->business_name ?? $vendor->user->name }}
                                    </h5>
                                    <div class="restaurant-info">
                                        @if($vendor->address)
                                            <div>
                                                <i class="bi bi-geo-alt-fill"></i>
                                                <span>{{ $vendor->address }}, {{ $vendor->city }}</span>
                                            </div>
                                        @endif

                                        @if($vendor->phone)
                                            <div>
                                                <i class="bi bi-telephone-fill"></i>
                                                <span>{{ $vendor->phone }}</span>
                                            </div>
                                        @endif

                                        @if($vendor->pivot && $vendor->pivot->price)
                                            <div>
                                                <i class="bi bi-currency-dollar"></i>
                                                <span
                                                    class="fw-bold text-success">{{ number_format($vendor->pivot->price, 0, ',', ' ') }}
                                                    FCFA</span>
                                            </div>
                                        @endif

                                        @if($vendor->pivot && $vendor->pivot->available)
                                            <div>
                                                <i class="bi bi-check-circle-fill text-success"></i>
                                                <span class="text-success">Disponible</span>
                                            </div>
                                        @else
                                            <div>
                                                <i class="bi bi-x-circle-fill text-danger"></i>
                                                <span class="text-muted">Non disponible actuellement</span>
                                            </div>
                                        @endif

                                        @if($vendor->distance)
                                            <div>
                                                <i class="bi bi-sign-turn-right"></i>
                                                <span>À {{ number_format($vendor->distance, 1) }} km</span>
                                            </div>
                                        @endif
                                    </div>

                                    @if($vendor->user)
                                      <a href="{{ route('vendors.show', $vendor) }}">Voir le profil</a>                                            <i class="bi bi-arrow-right-circle"></i>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @elseif($dish->restaurants && count($dish->restaurants) > 0)
                    <!-- Fallback sur les restaurants statiques si pas de vendors -->
                    <div class="dish-content-section">
                        <h2 class="section-title">
                            <i class="bi bi-shop"></i>
                            Où déguster
                        </h2>
                        <div class="restaurants-list">
                            @foreach($dish->restaurants as $restaurant)
                                <div class="restaurant-card">
                                    <h5>
                                        <i class="bi bi-pin-map-fill"></i>
                                        {{ $restaurant['name'] ?? 'Restaurant' }}
                                    </h5>
                                    <div class="restaurant-info">
                                        @if(isset($restaurant['address']))
                                            <div>
                                                <i class="bi bi-geo-alt-fill"></i>
                                                <span>{{ $restaurant['address'] }}</span>
                                            </div>
                                        @endif
                                        @if(isset($restaurant['phone']))
                                            <div>
                                                <i class="bi bi-telephone-fill"></i>
                                                <span>{{ $restaurant['phone'] }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach>
                        </div>
                    </div>
                @endif

                <!-- Statistiques -->
                <div class="dish-content-section">
                    <h2 class="section-title">
                        <i class="bi bi-graph-up"></i>
                        Statistiques
                    </h2>
                    <div class="dish-stats">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="bi bi-eye-fill"></i>
                            </div>
                            <div class="stat-content">
                                <strong>{{ number_format($dish->views) }}</strong>
                                <span>Vues</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation entre plats -->
        @if(isset($previousDish) || isset($nextDish))
            <div class="dish-navigation">
                @if(isset($previousDish))
                    <a href="{{ route('gastronomie.show', $previousDish) }}" class="nav-dish-card">
                        <div class="nav-dish-image">
                            <img src="{{ asset($previousDish->images->first()->image_url ?? 'images/default-dish.jpg') }}"
                                alt="{{ $previousDish->name }}">
                        </div>
                        <div class="nav-dish-content">
                            <div class="nav-dish-label">
                                <i class="bi bi-arrow-left"></i>
                                Plat précédent
                            </div>
                            <div class="nav-dish-name">{{ $previousDish->name }}</div>
                        </div>
                    </a>
                @endif

                @if(isset($nextDish))
                    <a href="{{ route('gastronomie.show', $nextDish) }}" class="nav-dish-card">
                        <div class="nav-dish-image">
                            <img src="{{ asset($nextDish->images->first()->image_url ?? 'images/default-dish.jpg') }}"
                                alt="{{ $nextDish->name }}">
                        </div>
                        <div class="nav-dish-content">
                            <div class="nav-dish-label">
                                Plat suivant
                                <i class="bi bi-arrow-right"></i>
                            </div>
                            <div class="nav-dish-name">{{ $nextDish->name }}</div>
                        </div>
                    </a>
                @endif
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        // Gestion de l'audio avec état visuel
        let currentAudio = null;
        let currentButton = null;

        function playAudio(button, audioUrl) {
            if (currentAudio && !currentAudio.paused) {
                currentAudio.pause();
                currentAudio.currentTime = 0;

                if (currentButton) {
                    currentButton.classList.remove('playing');
                    currentButton.querySelector('i').classList.remove('bi-pause-fill');
                    currentButton.querySelector('i').classList.add('bi-volume-up-fill');
                }

                if (currentButton === button) {
                    currentAudio = null;
                    currentButton = null;
                    return;
                }
            }

            currentAudio = new Audio(audioUrl);
            currentButton = button;

            button.classList.add('playing');
            button.querySelector('i').classList.remove('bi-volume-up-fill');
            button.querySelector('i').classList.add('bi-pause-fill');

            currentAudio.play().catch(e => {
                console.error('Erreur de lecture audio:', e);
                button.classList.remove('playing');
                button.querySelector('i').classList.remove('bi-pause-fill');
                button.querySelector('i').classList.add('bi-volume-up-fill');
            });

            currentAudio.addEventListener('ended', function () {
                button.classList.remove('playing');
                button.querySelector('i').classList.remove('bi-pause-fill');
                button.querySelector('i').classList.add('bi-volume-up-fill');
                currentAudio = null;
                currentButton = null;
            });

            if ('vibrate' in navigator) {
                navigator.vibrate(50);
            }
        }

        // Lightbox amélioré
        function openLightbox(imageUrl) {
            const lightbox = document.createElement('div');
            lightbox.className = 'lightbox';

            const img = document.createElement('img');
            img.src = imageUrl;
            img.alt = 'Image agrandie';

            lightbox.appendChild(img);
            document.body.appendChild(lightbox);

            // Fermer au clic
            lightbox.addEventListener('click', function () {
                lightbox.style.opacity = '0';
                setTimeout(() => {
                    document.body.removeChild(lightbox);
                }, 300);
            });

            // Fermer avec Escape
            document.addEventListener('keydown', function escapeHandler(e) {
                if (e.key === 'Escape' && document.querySelector('.lightbox')) {
                    lightbox.click();
                    document.removeEventListener('keydown', escapeHandler);
                }
            });
        }
    </script>
@endpush
