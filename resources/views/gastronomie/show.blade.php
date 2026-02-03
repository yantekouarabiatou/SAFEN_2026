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

    /* Hero Section du plat */
    .dish-hero {
        position: relative;
        height: 500px;
        overflow: hidden;
        border-radius: 0 0 30px 30px;
        margin-bottom: 3rem;
    }

    .dish-hero-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .dish-hero-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.5) 50%, transparent 100%);
        padding: 3rem 0 2rem;
    }

    .dish-hero-content {
        position: relative;
        z-index: 2;
    }

    .dish-hero h1 {
        color: white;
        font-size: 3rem;
        font-weight: 700;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        margin-bottom: 0.5rem;
    }

    .dish-local-name {
        color: var(--benin-yellow);
        font-size: 1.5rem;
        font-style: italic;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        margin-bottom: 1rem;
    }

    /* Audio Button dans le hero */
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
        box-shadow: 0 6px 20px rgba(212,119,78,0.4);
        margin-left: 1rem;
        vertical-align: middle;
    }

    .hero-audio-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 8px 25px rgba(212,119,78,0.6);
    }

    .hero-audio-btn.playing {
        animation: pulse 1.5s infinite;
    }

    .hero-audio-btn i {
        color: white;
        font-size: 1.3rem;
    }

    /* Badges du hero */
    .hero-badges {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        margin-top: 1.5rem;
    }

    .hero-badge {
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255,255,255,0.3);
        color: white;
        padding: 0.5rem 1.2rem;
        border-radius: 50px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .hero-badge i {
        font-size: 1.1rem;
    }

    /* Section principale */
    .dish-content-section {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
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

    /* Description */
    .dish-description {
        font-size: 1.1rem;
        line-height: 1.8;
        color: var(--charcoal);
        margin-bottom: 2rem;
    }

    /* Ingrédients */
    .ingredients-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .ingredient-item {
        background: var(--beige);
        padding: 1rem 1.25rem;
        border-radius: 15px;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .ingredient-item:hover {
        border-color: var(--benin-red);
        transform: translateX(5px);
    }

    .ingredient-item i {
        color: var(--benin-green);
        font-size: 1.2rem;
    }

    .ingredient-item span {
        color: var(--charcoal);
        font-weight: 500;
    }

    /* Préparation */
    .preparation-steps {
        counter-reset: step-counter;
        list-style: none;
        padding: 0;
    }

    .preparation-step {
        counter-increment: step-counter;
        position: relative;
        padding-left: 4rem;
        margin-bottom: 2rem;
        line-height: 1.8;
    }

    .preparation-step::before {
        content: counter(step-counter);
        position: absolute;
        left: 0;
        top: 0;
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, var(--benin-red) 0%, var(--terracotta) 100%);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.2rem;
        box-shadow: 0 4px 15px rgba(232,17,45,0.3);
    }

    /* Occasions */
    .occasions-list {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .occasion-tag {
        background: linear-gradient(135deg, var(--benin-green) 0%, #007a2e 100%);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 12px rgba(0,150,57,0.3);
        transition: all 0.3s ease;
    }

    .occasion-tag:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 18px rgba(0,150,57,0.5);
    }

    .occasion-tag i {
        font-size: 1.1rem;
    }

    /* Informations culturelles */
    .cultural-info {
        background: linear-gradient(135deg, rgba(0,150,57,0.1) 0%, rgba(232,17,45,0.1) 100%);
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

    /* Galerie d'images */
    .dish-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }

    .gallery-item {
        position: relative;
        border-radius: 15px;
        overflow: hidden;
        aspect-ratio: 1;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .gallery-item:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Où déguster */
    .restaurants-list {
        display: grid;
        gap: 1.5rem;
    }

    .restaurant-card {
        background: white;
        border: 2px solid var(--beige);
        border-radius: 15px;
        padding: 1.5rem;
        transition: all 0.3s ease;
    }

    .restaurant-card:hover {
        border-color: var(--benin-red);
        box-shadow: 0 6px 20px rgba(232,17,45,0.15);
        transform: translateY(-3px);
    }

    .restaurant-card h5 {
        color: var(--charcoal);
        font-weight: 700;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .restaurant-card h5 i {
        color: var(--benin-red);
    }

    .restaurant-info {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        color: #666;
        font-size: 0.95rem;
    }

    .restaurant-info i {
        color: var(--benin-green);
        width: 20px;
    }

    /* Navigation entre plats */
    .dish-navigation {
        display: flex;
        justify-content: space-between;
        gap: 2rem;
        margin-top: 3rem;
        flex-wrap: wrap;
    }

    .nav-dish-card {
        flex: 1;
        min-width: 250px;
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
    }

    .nav-dish-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }

    .nav-dish-image {
        height: 150px;
        overflow: hidden;
    }

    .nav-dish-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .nav-dish-card:hover .nav-dish-image img {
        transform: scale(1.1);
    }

    .nav-dish-content {
        padding: 1.25rem;
    }

    .nav-dish-label {
        color: var(--benin-red);
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .nav-dish-name {
        color: var(--charcoal);
        font-size: 1.1rem;
        font-weight: 700;
    }

    /* Stats */
    .dish-stats {
        display: flex;
        gap: 2rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid var(--beige);
        flex-wrap: wrap;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, var(--benin-red) 0%, var(--terracotta) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.3rem;
    }

    .stat-content strong {
        display: block;
        color: var(--charcoal);
        font-size: 1.3rem;
        font-weight: 700;
    }

    .stat-content span {
        color: #666;
        font-size: 0.9rem;
    }

    /* Bouton de retour */
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
    @media (max-width: 768px) {
        .dish-hero {
            height: 400px;
        }

        .dish-hero h1 {
            font-size: 2rem;
        }

        .dish-local-name {
            font-size: 1.2rem;
        }

        .dish-content-section {
            padding: 1.5rem;
        }

        .section-title {
            font-size: 1.4rem;
        }

        .ingredients-grid {
            grid-template-columns: 1fr;
        }

        .dish-navigation {
            flex-direction: column;
        }

        .dish-stats {
            gap: 1rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<div class="dish-hero">
    @if($dish->images->first())
        <img src="{{ $dish->images->first()->image_url }}"
             alt="{{ $dish->name }}"
             class="dish-hero-image">
    @else
        <img src="{{ asset('images/default-dish.jpg') }}"
             alt="{{ $dish->name }}"
             class="dish-hero-image">
    @endif

    <div class="dish-hero-overlay">
        <div class="container">
            <div class="dish-hero-content">
                <div class="d-flex align-items-center flex-wrap">
                    <h1>{{ $dish->name }}</h1>
                    @if($dish->audio_url)
                        <button class="hero-audio-btn"
                                onclick="playAudio(this, '{{ $dish->audio_url }}')"
                                title="Écouter la prononciation"
                                aria-label="Écouter la prononciation">
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
    <!-- Bouton de retour -->
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
                            <div class="gallery-item">
                                <img src="{{ $image->image_url }}" alt="{{ $dish->name }}">
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

            <!-- Où déguster -->
            @if($dish->restaurants && count($dish->restaurants) > 0)
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
                                            {{ $restaurant['address'] }}
                                        </div>
                                    @endif
                                    @if(isset($restaurant['phone']))
                                        <div>
                                            <i class="bi bi-telephone-fill"></i>
                                            {{ $restaurant['phone'] }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
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
                        <img src="{{ $dish->images->first()->image_url ?? asset('images/default-dish.jpg') }}"
                                 alt="{{ $dish->name }}"
                                 loading="lazy">
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
                        <img src="{{ $nextDish->images->first()->image_url ?? asset('images/default-dish.jpg') }}"
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
    // Si un audio est déjà en cours
    if (currentAudio && !currentAudio.paused) {
        currentAudio.pause();
        currentAudio.currentTime = 0;

        // Retirer l'état playing du bouton précédent
        if (currentButton) {
            currentButton.classList.remove('playing');
            currentButton.querySelector('i').classList.remove('bi-pause-fill');
            currentButton.querySelector('i').classList.add('bi-volume-up-fill');
        }

        // Si c'est le même bouton, on arrête ici
        if (currentButton === button) {
            currentAudio = null;
            currentButton = null;
            return;
        }
    }

    // Créer et jouer le nouvel audio
    currentAudio = new Audio(audioUrl);
    currentButton = button;

    // Ajouter l'état playing
    button.classList.add('playing');
    button.querySelector('i').classList.remove('bi-volume-up-fill');
    button.querySelector('i').classList.add('bi-pause-fill');

    currentAudio.play().catch(e => {
        console.error('Erreur de lecture audio:', e);
        button.classList.remove('playing');
        button.querySelector('i').classList.remove('bi-pause-fill');
        button.querySelector('i').classList.add('bi-volume-up-fill');
    });

    // Quand l'audio se termine
    currentAudio.addEventListener('ended', function() {
        button.classList.remove('playing');
        button.querySelector('i').classList.remove('bi-pause-fill');
        button.querySelector('i').classList.add('bi-volume-up-fill');
        currentAudio = null;
        currentButton = null;
    });

    // Vibration légère (si disponible)
    if ('vibrate' in navigator) {
        navigator.vibrate(50);
    }
}

// Lightbox pour la galerie
document.addEventListener('DOMContentLoaded', function() {
    const galleryItems = document.querySelectorAll('.gallery-item');

    galleryItems.forEach(item => {
        item.addEventListener('click', function() {
            const img = this.querySelector('img');
            // Créer une lightbox simple
            const lightbox = document.createElement('div');
            lightbox.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.9);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 9999;
                cursor: pointer;
            `;

            const lightboxImg = document.createElement('img');
            lightboxImg.src = img.src;
            lightboxImg.style.cssText = `
                max-width: 90%;
                max-height: 90%;
                border-radius: 10px;
                box-shadow: 0 10px 50px rgba(0,0,0,0.5);
            `;

            lightbox.appendChild(lightboxImg);
            document.body.appendChild(lightbox);

            lightbox.addEventListener('click', function() {
                document.body.removeChild(lightbox);
            });
        });
    });
});
</script>
@endpush
