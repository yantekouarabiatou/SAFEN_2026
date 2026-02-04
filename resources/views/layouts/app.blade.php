<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="AFRI-HERITAGE - Marketplace culturelle béninoise propulsée par l'IA">
    <meta name="author" content="AFRI-HERITAGE Bénin">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'AFRI-HERITAGE Bénin')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Open+Sans:wght@300;400;500;600&display=swap"
        rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        :root {
            --benin-green: #009639;
            --benin-yellow: #FCD116;
            --benin-red: #E8112D;
            --terracotta: #D4754E;
            --beige: #F5E6D3;
            --charcoal: #2c2c2c;
            --gold: #C9A962;
            --navy: #1E3A5F;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            color: var(--charcoal);
            background-color: var(--beige);
            line-height: 1.6;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
        }

        /* Couleurs personnalisées */
        .bg-benin-green {
            background-color: var(--benin-green) !important;
        }

        .bg-benin-yellow {
            background-color: var(--benin-yellow) !important;
        }

        .bg-benin-red {
            background-color: var(--benin-red) !important;
        }

        .bg-terracotta {
            background-color: var(--terracotta) !important;
        }

        .bg-beige {
            background-color: var(--beige) !important;
        }

        .bg-charcoal {
            background-color: var(--charcoal) !important;
        }

        .bg-gold {
            background-color: var(--gold) !important;
        }

        .bg-navy {
            background-color: var(--navy) !important;
        }

        .text-benin-green {
            color: var(--benin-green) !important;
        }

        .text-benin-yellow {
            color: var(--benin-yellow) !important;
        }

        .text-benin-red {
            color: var(--benin-red) !important;
        }

        .text-terracotta {
            color: var(--terracotta) !important;
        }

        .text-beige {
            color: var(--beige) !important;
        }

        .text-charcoal {
            color: var(--charcoal) !important;
        }

        .text-gold {
            color: var(--gold) !important;
        }

        .text-navy {
            color: var(--navy) !important;
        }

        .btn-benin-green {
            background-color: var(--benin-green);
            border-color: var(--benin-green);
            color: white;
        }

        .btn-benin-green:hover {
            background-color: #00782e;
            border-color: #00782e;
            color: white;
        }

        .btn-benin-yellow {
            background-color: var(--benin-yellow);
            border-color: var(--benin-yellow);
            color: var(--charcoal);
        }

        .btn-benin-yellow:hover {
            background-color: #e6b800;
            border-color: #e6b800;
            color: var(--charcoal);
        }

        .btn-benin-red {
            background-color: var(--benin-red);
            border-color: var(--benin-red);
            color: white;
        }

        .btn-benin-red:hover {
            background-color: #c00f27;
            border-color: #c00f27;
            color: white;
        }

        .btn-outline-benin-green {
            border-color: var(--benin-green);
            color: var(--benin-green);
        }

        .btn-outline-benin-green:hover {
            background-color: var(--benin-green);
            color: white;
        }

        .btn-outline-benin-yellow {
            border-color: var(--benin-yellow);
            color: var(--charcoal);
        }

        .btn-outline-benin-yellow:hover {
            background-color: var(--benin-yellow);
            color: var(--charcoal);
        }

        /* Navbar */
        .navbar-afri {
            background-color: white;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            padding: 1rem 0;
        }

        .navbar-afri .nav-link {
            color: var(--charcoal) !important;
            font-weight: 500;
            margin: 0 0.5rem;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .navbar-afri .nav-link:hover,
        .navbar-afri .nav-link.active {
            background-color: var(--benin-green);
            color: white !important;
        }

        .lang-selector .btn {
            border: 1px solid #dee2e6;
            color: var(--charcoal);
        }

        /* Cards */
        .card-artisan,
        .card-product {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-artisan:hover,
        .card-product:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Footer */
        .footer-afri {
            background: linear-gradient(135deg, var(--navy) 0%, var(--charcoal) 100%);
            color: white;
        }

        .footer-afri a {
            color: var(--beige);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-afri a:hover {
            color: var(--benin-yellow);
        }

        /* Chatbot */
        .chatbot-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--benin-green) 0%, var(--navy) 100%);
            border-radius: 50%;
            border: none;
            color: white;
            box-shadow: 0 5px 20px rgba(0, 150, 57, 0.3);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .chatbot-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 25px rgba(0, 150, 57, 0.4);
        }

        .chatbot-window {
            position: fixed;
            bottom: 100px;
            right: 30px;
            width: 350px;
            max-height: 500px;
            z-index: 1001;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border-radius: 16px;
            overflow: hidden;
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .audio-btn {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: var(--benin-green);
            border: none;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .audio-btn:hover {
            background-color: var(--navy);
            transform: scale(1.1);
        }

        /* Rating stars */
        .rating-stars {
            color: var(--benin-yellow);
        }

        /* Badges */
        .badge.bg-benin-green,
        .badge.bg-benin-yellow,
        .badge.bg-benin-red {
            color: white;
        }

        /* Hero sections */
        .hero-section {
            background: linear-gradient(rgba(44, 44, 44, 0.7), rgba(44, 44, 44, 0.9));
            color: white;
            padding: 4rem 0;
            position: relative;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .chatbot-window {
                width: calc(100vw - 40px);
                right: 20px;
                left: 20px;
            }

            .navbar-afri .nav-link {
                margin: 0.25rem 0;
                padding: 0.75rem 1rem !important;
            }
        }

        /* Animations et effets avancés pour la page gastronomie */

        /* Animation de chargement fluide */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dish-card {
            animation: fadeInUp 0.6s ease backwards;
        }

        .dish-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .dish-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .dish-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .dish-card:nth-child(4) {
            animation-delay: 0.4s;
        }

        .dish-card:nth-child(5) {
            animation-delay: 0.5s;
        }

        .dish-card:nth-child(6) {
            animation-delay: 0.6s;
        }

        .dish-card:nth-child(7) {
            animation-delay: 0.7s;
        }

        .dish-card:nth-child(8) {
            animation-delay: 0.8s;
        }

        /* Effet d'ondulation pour les boutons audio */
        @keyframes ripple {
            0% {
                box-shadow: 0 0 0 0 rgba(212, 119, 78, 0.7),
                    0 0 0 0 rgba(212, 119, 78, 0.7);
            }

            50% {
                box-shadow: 0 0 0 10px rgba(212, 119, 78, 0),
                    0 0 0 0 rgba(199, 69, 33, 0.7);
            }

            100% {
                box-shadow: 0 0 0 10px rgba(212, 119, 78, 0),
                    0 0 0 20px rgba(212, 119, 78, 0);
            }
        }

        .audio-btn.playing::before {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            border-radius: 50%;
            animation: ripple 1.5s infinite;
        }

        /* Effet de brillance sur les cartes au survol */
        @keyframes shine {
            0% {
                left: -100%;
            }

            50% {
                left: 100%;
            }

            100% {
                left: 100%;
            }
        }

        .dish-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 50%;
            height: 100%;
            background: linear-gradient(90deg,
                    transparent,
                    rgba(255, 255, 255, 0.3),
                    transparent);
            transition: left 0.7s;
            pointer-events: none;
        }

        .dish-card:hover::after {
            animation: shine 1.5s;
        }

        /* Badge avec effet de rebond */
        @keyframes bounceIn {
            0% {
                transform: scale(0);
                opacity: 0;
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .ethnic-badge,
        .ingredient-tag {
            animation: bounceIn 0.5s ease backwards;
        }

        .ethnic-badge:nth-child(1) {
            animation-delay: 0.7s;
        }

        .ethnic-badge:nth-child(2) {
            animation-delay: 0.8s;
        }

        /* Effet de glissement pour les tabs */
        .category-tab {
            position: relative;
            z-index: 1;
        }

        .category-tab::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 50%;
            width: 0;
            height: 3px;
            background: var(--benin-red);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .category-tab.active::after {
            width: 80%;
        }

        /* Indicateur de lecture audio */
        @keyframes soundWave {

            0%,
            100% {
                height: 4px;
            }

            50% {
                height: 12px;
            }
        }

        .audio-btn.playing .sound-wave {
            display: flex;
            gap: 2px;
            align-items: center;
            height: 16px;
        }

        .audio-btn.playing .sound-wave span {
            width: 2px;
            background: white;
            border-radius: 2px;
            animation: soundWave 0.8s ease-in-out infinite;
        }

        .audio-btn.playing .sound-wave span:nth-child(1) {
            animation-delay: 0s;
        }

        .audio-btn.playing .sound-wave span:nth-child(2) {
            animation-delay: 0.2s;
        }

        .audio-btn.playing .sound-wave span:nth-child(3) {
            animation-delay: 0.4s;
        }

        /* Tooltip pour le bouton audio */
        .audio-btn {
            position: relative;
        }

        .audio-btn::before {
            content: 'Écouter';
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%) translateY(-5px);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.7rem;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s ease;
        }

        .audio-btn:hover::before {
            opacity: 1;
            transform: translateX(-50%) translateY(-8px);
        }

        /* Effet de compteur pour les vues */
        @keyframes countUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .views-count {
            animation: countUp 0.5s ease backwards;
            animation-delay: 1s;
        }

        /* Amélioration du focus pour l'accessibilité */
        .audio-btn:focus,
        .btn-discover:focus,
        .category-tab:focus {
            outline: 3px solid var(--benin-yellow);
            outline-offset: 2px;
        }

        /* Mode sombre (optionnel) */
        @media (prefers-color-scheme: dark) {
            .dish-card {
                background: #1a1a1a;
            }

            .dish-name a {
                color: #e0e0e0;
            }

            .ethnic-badge {
                background: rgba(245, 230, 211, 0.2);
                color: var(--beige);
            }
        }

        /* Responsive amélioré */
        @media (max-width: 576px) {
            .dish-card {
                animation-delay: 0s !important;
            }

            .dish-name {
                flex-direction: column;
                align-items: flex-start;
            }

            .audio-btn {
                align-self: flex-end;
                margin-top: 0.5rem;
            }
        }

        /* Effet de parallaxe léger sur l'image */
        @media (min-width: 992px) {
            .dish-image {
                overflow: visible;
            }

            .dish-card:hover .dish-image img {
                transform: scale(1.1) translateZ(0);
            }
        }

        /* Transition fluide pour les filtres */
        .dish-grid {
            transition: opacity 0.3s ease;
        }

        .dish-grid.filtering {
            opacity: 0.5;
        }

        /* Skeleton loading pour un chargement optimal */
        .skeleton {
            background: linear-gradient(90deg,
                    var(--beige) 25%,
                    #e8dcc8 50%,
                    var(--beige) 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }
    </style>

    <!-- Alpine.js pour l'interactivité -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
     <script>
        /**
 * Script avancé pour la page Gastronomie
 * Gestion de l'audio, animations, et interactions utilisateur
 */

class GastronomieManager {
    constructor() {
        this.currentAudio = null;
        this.currentButton = null;
        this.audioContext = null;
        this.isPlaying = false;

        this.init();
    }

    init() {
        this.setupAudioButtons();
        this.setupFilterAnimations();
        this.setupScrollAnimations();
        this.setupSearchEnhancements();
        this.setupKeyboardNavigation();
        this.setupAudioVisualization();
    }

    /**
     * Configuration des boutons audio avec visualisation
     */
    setupAudioButtons() {
        document.querySelectorAll('.audio-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();

                const audioUrl = button.getAttribute('onclick').match(/'([^']+)'/)[1];
                this.toggleAudio(button, audioUrl);
            });

            // Accessibilité clavier
            button.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    button.click();
                }
            });
        });
    }

    /**
     * Gestion intelligente de la lecture audio
     */
    toggleAudio(button, audioUrl) {
        // Arrêter l'audio en cours s'il existe
        if (this.currentAudio && !this.currentAudio.paused) {
            this.stopAudio();

            // Si c'est le même bouton, on arrête ici
            if (this.currentButton === button) {
                return;
            }
        }

        // Démarrer le nouvel audio
        this.playAudio(button, audioUrl);
    }

    /**
     * Lecture de l'audio avec feedback visuel
     */
    playAudio(button, audioUrl) {
        this.currentAudio = new Audio(audioUrl);
        this.currentButton = button;
        this.isPlaying = true;

        // Précharger l'audio
        this.currentAudio.preload = 'auto';

        // Ajouter les classes visuelles
        button.classList.add('playing');
        const icon = button.querySelector('i');
        if (icon) {
            icon.classList.remove('bi-volume-up-fill');
            icon.classList.add('bi-pause-fill');
        }

        // Ajouter un indicateur de chargement
        this.showLoadingState(button);

        // Événements audio
        this.currentAudio.addEventListener('loadeddata', () => {
            this.hideLoadingState(button);
        });

        this.currentAudio.addEventListener('playing', () => {
            this.hideLoadingState(button);
            this.createSoundWave(button);
        });

        this.currentAudio.addEventListener('ended', () => {
            this.stopAudio();
        });

        this.currentAudio.addEventListener('error', (e) => {
            console.error('Erreur de lecture audio:', e);
            this.stopAudio();
            this.showAudioError(button);
        });

        // Démarrer la lecture
        this.currentAudio.play().catch(e => {
            console.error('Impossible de lire l\'audio:', e);
            this.stopAudio();
            this.showAudioError(button);
        });

        // Vibration légère (si disponible)
        if ('vibrate' in navigator) {
            navigator.vibrate(50);
        }
    }

    /**
     * Arrêter la lecture audio
     */
    stopAudio() {
        if (this.currentAudio) {
            this.currentAudio.pause();
            this.currentAudio.currentTime = 0;
            this.currentAudio = null;
        }

        if (this.currentButton) {
            this.currentButton.classList.remove('playing');
            const icon = this.currentButton.querySelector('i');
            if (icon) {
                icon.classList.remove('bi-pause-fill');
                icon.classList.add('bi-volume-up-fill');
            }
            this.removeSoundWave(this.currentButton);
            this.currentButton = null;
        }

        this.isPlaying = false;
    }

    /**
     * Créer une visualisation d'onde sonore
     */
    createSoundWave(button) {
        const existing = button.querySelector('.sound-wave');
        if (existing) return;

        const wave = document.createElement('div');
        wave.className = 'sound-wave';
        wave.innerHTML = '<span></span><span></span><span></span>';

        // Remplacer temporairement l'icône
        const icon = button.querySelector('i');
        if (icon) {
            icon.style.display = 'none';
        }

        button.appendChild(wave);
    }

    /**
     * Supprimer la visualisation d'onde sonore
     */
    removeSoundWave(button) {
        const wave = button.querySelector('.sound-wave');
        if (wave) {
            wave.remove();
        }

        const icon = button.querySelector('i');
        if (icon) {
            icon.style.display = 'inline';
        }
    }

    /**
     * Afficher l'état de chargement
     */
    showLoadingState(button) {
        const spinner = document.createElement('div');
        spinner.className = 'spinner-border spinner-border-sm text-light';
        spinner.setAttribute('role', 'status');
        spinner.innerHTML = '<span class="visually-hidden">Chargement...</span>';

        const icon = button.querySelector('i');
        if (icon) {
            icon.style.display = 'none';
        }

        button.appendChild(spinner);
    }

    /**
     * Masquer l'état de chargement
     */
    hideLoadingState(button) {
        const spinner = button.querySelector('.spinner-border');
        if (spinner) {
            spinner.remove();
        }
    }

    /**
     * Afficher une erreur audio
     */
    showAudioError(button) {
        const icon = button.querySelector('i');
        if (icon) {
            icon.style.display = 'inline';
            icon.classList.add('text-danger');

            setTimeout(() => {
                icon.classList.remove('text-danger');
            }, 2000);
        }

        // Notification toast (si disponible)
        this.showToast('Erreur lors de la lecture audio', 'error');
    }

    /**
     * Animation des filtres
     */
    setupFilterAnimations() {
        const categoryTabs = document.querySelectorAll('.category-tab');

        categoryTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const dishGrid = document.querySelector('.dish-grid');
                if (dishGrid) {
                    dishGrid.classList.add('filtering');

                    setTimeout(() => {
                        dishGrid.classList.remove('filtering');
                    }, 300);
                }
            });
        });
    }

    /**
     * Animations au scroll avec Intersection Observer
     */
    setupScrollAnimations() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '0';
                    entry.target.style.transform = 'translateY(20px)';

                    setTimeout(() => {
                        entry.target.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, 100);

                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observer toutes les cartes
        document.querySelectorAll('.dish-card').forEach(card => {
            observer.observe(card);
        });
    }

    /**
     * Amélioration de la recherche
     */
    setupSearchEnhancements() {
        const searchInput = document.querySelector('input[name="search"]');

        if (!searchInput) return;

        let searchTimeout;
        let lastSearch = searchInput.value;

        // Recherche avec debounce
        searchInput.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);

            const value = e.target.value;

            // Indicateur visuel de recherche
            if (value.length > 0 && value !== lastSearch) {
                searchInput.style.borderColor = 'var(--benin-yellow)';

                searchTimeout = setTimeout(() => {
                    searchInput.style.borderColor = '';
                    lastSearch = value;
                    // Auto-submit optionnel
                    // document.getElementById('searchForm').submit();
                }, 800);
            }
        });

        // Effacer la recherche avec Escape
        searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                searchInput.value = '';
                searchInput.focus();
            }
        });

        // Suggestions de recherche (optionnel)
        this.setupSearchSuggestions(searchInput);
    }

    /**
     * Suggestions de recherche
     */
    setupSearchSuggestions(input) {
        const suggestions = [
            'Amiwo', 'Akassa', 'Aloko', 'Tchoucoutou',
            'Atassi', 'Wagashi', 'Pâte rouge', 'Fonio'
        ];

        // Créer un datalist pour l'autocomplétion
        const datalist = document.createElement('datalist');
        datalist.id = 'dish-suggestions';

        suggestions.forEach(suggestion => {
            const option = document.createElement('option');
            option.value = suggestion;
            datalist.appendChild(option);
        });

        input.setAttribute('list', 'dish-suggestions');
        input.parentElement.appendChild(datalist);
    }

    /**
     * Navigation au clavier
     */
    setupKeyboardNavigation() {
        document.addEventListener('keydown', (e) => {
            // Raccourci: Ctrl/Cmd + K pour focus sur la recherche
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                const searchInput = document.querySelector('input[name="search"]');
                if (searchInput) {
                    searchInput.focus();
                    searchInput.select();
                }
            }

            // Espace pour arrêter l'audio en cours
            if (e.key === ' ' && this.isPlaying && document.activeElement.tagName !== 'INPUT') {
                e.preventDefault();
                this.stopAudio();
            }
        });
    }

    /**
     * Configuration avancée de la visualisation audio (Web Audio API)
     */
    setupAudioVisualization() {
        try {
            this.audioContext = new (window.AudioContext || window.webkitAudioContext)();
        } catch (e) {
            console.log('Web Audio API non disponible');
        }
    }

    /**
     * Afficher une notification toast
     */
    showToast(message, type = 'info') {
        // Vérifier si Bootstrap toast est disponible
        const toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            console.log(message);
            return;
        }

        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type === 'error' ? 'danger' : 'success'} border-0`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;

        toastContainer.appendChild(toast);

        // Initialiser et afficher le toast (si Bootstrap est disponible)
        if (typeof bootstrap !== 'undefined') {
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();

            // Supprimer après fermeture
            toast.addEventListener('hidden.bs.toast', () => {
                toast.remove();
            });
        }
    }
}

// Initialisation au chargement du DOM
document.addEventListener('DOMContentLoaded', () => {
    window.gastronomieManager = new GastronomieManager();

    // Smooth scroll pour les ancres
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Lazy loading des images
    if ('loading' in HTMLImageElement.prototype) {
        document.querySelectorAll('img[loading="lazy"]').forEach(img => {
            img.src = img.dataset.src || img.src;
        });
    } else {
        // Fallback pour les navigateurs anciens
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js';
        document.body.appendChild(script);
    }
});

// Fonction globale pour la compatibilité avec l'ancien code
function playAudio(button, audioUrl) {
    if (window.gastronomieManager) {
        window.gastronomieManager.toggleAudio(button, audioUrl);
    }
}

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
     </script>
    @stack('styles')
</head>

<body>
    <!-- Navbar -->
    @include('partials.navbar')

    <!-- Page Content -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('partials.footer')

    <!-- Chatbot Widget -->
    @include('partials.chatbot')

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Google Maps API (pour les cartes) -->
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_KEY&libraries=places"></script>

    @stack('scripts')
</body>

</html>
