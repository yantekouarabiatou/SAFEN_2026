<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="TOTCHEMEGNON - Marketplace culturelle béninoise propulsée par l'IA">
    <meta name="author" content="TOTCHEMEGNON Bénin">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'TOTCHEMEGNON Bénin')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('products/vodoun.jpg') }}">

    <!-- ① Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ② Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- ③ Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Open+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- ④ Select2 CSS uniquement -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

    <!-- Custom CSS global -->
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

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Open Sans', sans-serif;
            color: var(--charcoal);
            background-color: var(--beige);
            line-height: 1.6;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
        }

        /* Couleurs */
        .bg-benin-green  { background-color: var(--benin-green)  !important; }
        .bg-benin-yellow { background-color: var(--benin-yellow) !important; }
        .bg-benin-red    { background-color: var(--benin-red)    !important; }
        .bg-terracotta   { background-color: var(--terracotta)   !important; }
        .bg-beige        { background-color: var(--beige)        !important; }
        .bg-charcoal     { background-color: var(--charcoal)     !important; }
        .bg-gold         { background-color: var(--gold)         !important; }
        .bg-navy         { background-color: var(--navy)         !important; }

        .text-benin-green  { color: var(--benin-green)  !important; }
        .text-benin-yellow { color: var(--benin-yellow) !important; }
        .text-benin-red    { color: var(--benin-red)    !important; }
        .text-terracotta   { color: var(--terracotta)   !important; }
        .text-beige        { color: var(--beige)        !important; }
        .text-charcoal     { color: var(--charcoal)     !important; }
        .text-gold         { color: var(--gold)         !important; }
        .text-navy         { color: var(--navy)         !important; }

        /* Boutons */
        .btn-benin-green  { background-color: var(--benin-green);  border-color: var(--benin-green);  color: white; }
        .btn-benin-green:hover  { background-color: #00782e; border-color: #00782e; color: white; }

        .btn-benin-yellow { background-color: var(--benin-yellow); border-color: var(--benin-yellow); color: var(--charcoal); }
        .btn-benin-yellow:hover { background-color: #e6b800; border-color: #e6b800; color: var(--charcoal); }

        .btn-benin-red    { background-color: var(--benin-red);    border-color: var(--benin-red);    color: white; }
        .btn-benin-red:hover    { background-color: #c00f27; border-color: #c00f27; color: white; }

        .btn-outline-benin-green { border-color: var(--benin-green); color: var(--benin-green); }
        .btn-outline-benin-green:hover { background-color: var(--benin-green); color: white; }

        .btn-outline-benin-yellow { border-color: var(--benin-yellow); color: var(--charcoal); }
        .btn-outline-benin-yellow:hover { background-color: var(--benin-yellow); color: var(--charcoal); }

        /* Navbar */
        .navbar-afri {
            background-color: white;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            padding: 1rem 0;
        }
        .navbar-afri .nav-link {
            color: var(--charcoal) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .navbar-afri .nav-link:hover,
        .navbar-afri .nav-link.active {
            background-color: var(--benin-green);
            color: white !important;
        }
        .lang-selector .btn { border: 1px solid #dee2e6; color: var(--charcoal); }

        /* Cards */
        .card-artisan, .card-product {
            border: none; border-radius: 15px; overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-artisan:hover, .card-product:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        /* Footer */
        .footer-afri { background: linear-gradient(135deg, var(--navy) 0%, var(--charcoal) 100%); color: white; }
        .footer-afri a { color: var(--beige); text-decoration: none; transition: color 0.3s ease; }
        .footer-afri a:hover { color: var(--benin-yellow); }

        /* Chatbot */
        .chatbot-btn {
            position: fixed; bottom: 30px; right: 30px;
            width: 60px; height: 60px;
            background: linear-gradient(135deg, var(--benin-green) 0%, var(--navy) 100%);
            border-radius: 50%; border: none; color: white;
            box-shadow: 0 5px 20px rgba(0,150,57,0.3);
            z-index: 1000; transition: all 0.3s ease;
        }
        .chatbot-btn:hover { transform: scale(1.1); box-shadow: 0 8px 25px rgba(0,150,57,0.4); }

        .chatbot-window {
            position: fixed; bottom: 100px; right: 30px;
            width: 350px; max-height: 500px;
            z-index: 1001; box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            border-radius: 16px; overflow: hidden;
            animation: slideUp 0.3s ease;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .audio-btn {
            width: 32px; height: 32px; border-radius: 50%;
            background-color: var(--benin-green); border: none; color: white;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.3s ease; position: relative;
        }
        .audio-btn:hover { background-color: var(--navy); transform: scale(1.1); }

        /* Rating */
        .rating-stars { color: var(--benin-yellow); }

        /* Hero */
        .hero-section {
            background: linear-gradient(rgba(44,44,44,0.7), rgba(44,44,44,0.9));
            color: white; padding: 4rem 0; position: relative;
        }

        /* Animations gastronomie */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .dish-card { animation: fadeInUp 0.6s ease backwards; }
        .dish-card:nth-child(1) { animation-delay: 0.1s; }
        .dish-card:nth-child(2) { animation-delay: 0.2s; }
        .dish-card:nth-child(3) { animation-delay: 0.3s; }
        .dish-card:nth-child(4) { animation-delay: 0.4s; }
        .dish-card:nth-child(5) { animation-delay: 0.5s; }
        .dish-card:nth-child(6) { animation-delay: 0.6s; }
        .dish-card:nth-child(7) { animation-delay: 0.7s; }
        .dish-card:nth-child(8) { animation-delay: 0.8s; }

        @keyframes ripple {
            0%   { box-shadow: 0 0 0 0 rgba(212,119,78,0.7), 0 0 0 0 rgba(212,119,78,0.7); }
            50%  { box-shadow: 0 0 0 10px rgba(212,119,78,0), 0 0 0 0 rgba(199,69,33,0.7); }
            100% { box-shadow: 0 0 0 10px rgba(212,119,78,0), 0 0 0 20px rgba(212,119,78,0); }
        }
        .audio-btn.playing::before {
            content: ''; position: absolute;
            top: -5px; left: -5px; right: -5px; bottom: -5px;
            border-radius: 50%; animation: ripple 1.5s infinite;
        }

        .dish-card::after {
            content: ''; position: absolute; top: 0; left: -100%;
            width: 50%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.7s; pointer-events: none;
        }
        .dish-card:hover::after { animation: shine 1.5s; }
        @keyframes shine { 0% { left: -100%; } 50% { left: 100%; } 100% { left: 100%; } }

        @keyframes bounceIn {
            0%   { transform: scale(0); opacity: 0; }
            50%  { transform: scale(1.1); }
            100% { transform: scale(1); opacity: 1; }
        }
        .ethnic-badge, .ingredient-tag { animation: bounceIn 0.5s ease backwards; }
        .ethnic-badge:nth-child(1) { animation-delay: 0.7s; }
        .ethnic-badge:nth-child(2) { animation-delay: 0.8s; }

        .category-tab { position: relative; z-index: 1; }
        .category-tab::after {
            content: ''; position: absolute; bottom: -2px; left: 50%;
            width: 0; height: 3px; background: var(--benin-red);
            transition: all 0.3s ease; transform: translateX(-50%);
        }
        .category-tab.active::after { width: 80%; }

        @keyframes soundWave { 0%, 100% { height: 4px; } 50% { height: 12px; } }
        .audio-btn.playing .sound-wave { display: flex; gap: 2px; align-items: center; height: 16px; }
        .audio-btn.playing .sound-wave span {
            width: 2px; background: white; border-radius: 2px;
            animation: soundWave 0.8s ease-in-out infinite;
        }
        .audio-btn.playing .sound-wave span:nth-child(1) { animation-delay: 0s; }
        .audio-btn.playing .sound-wave span:nth-child(2) { animation-delay: 0.2s; }
        .audio-btn.playing .sound-wave span:nth-child(3) { animation-delay: 0.4s; }

        .audio-btn::before {
            content: 'Écouter'; position: absolute; bottom: 100%; left: 50%;
            transform: translateX(-50%) translateY(-5px);
            background: rgba(0,0,0,0.8); color: white;
            padding: 4px 8px; border-radius: 4px; font-size: 0.7rem;
            white-space: nowrap; opacity: 0; pointer-events: none;
            transition: all 0.3s ease;
        }
        .audio-btn:hover::before { opacity: 1; transform: translateX(-50%) translateY(-8px); }

        .dish-grid { transition: opacity 0.3s ease; }
        .dish-grid.filtering { opacity: 0.5; }

        .skeleton {
            background: linear-gradient(90deg, var(--beige) 25%, #e8dcc8 50%, var(--beige) 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }
        @keyframes loading {
            0%   { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .chatbot-window { width: calc(100vw - 40px); right: 20px; left: 20px; }
            .navbar-afri .nav-link { margin: 0.25rem 0; padding: 0.75rem 1rem !important; }
        }
        @media (max-width: 576px) {
            .dish-card { animation-delay: 0s !important; }
            .dish-name { flex-direction: column; align-items: flex-start; }
            .audio-btn { align-self: flex-end; margin-top: 0.5rem; }
        }
        @media (prefers-color-scheme: dark) {
            .dish-card { background: #1a1a1a; }
            .dish-name a { color: #e0e0e0; }
            .ethnic-badge { background: rgba(245,230,211,0.2); color: var(--beige); }
        }

        /* Accessibilité */
        .audio-btn:focus, .btn-discover:focus, .category-tab:focus {
            outline: 3px solid var(--benin-yellow); outline-offset: 2px;
        }
    </style>

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

    {{-- ═══════════════════════════════════════════════════════
         SCRIPTS — ordre impératif :
         1. jQuery (une seule fois)
         2. Bootstrap Bundle (inclut Popper → dropdowns)
         3. Select2
         4. Alpine.js
         5. Scripts locaux
         6. @stack('scripts') des vues enfants
    ════════════════════════════════════════════════════════ --}}

    <!-- ① jQuery (une seule fois) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
            crossorigin="anonymous"></script>

    <!-- ② Bootstrap Bundle (Popper inclus — OBLIGATOIRE pour les dropdowns) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- ③ Select2 (après jQuery) -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- ④ Alpine.js (defer pour ne pas bloquer) -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- ⑤ Scripts locaux -->
    <script src="{{ asset('admin-assets/js/app.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/scripts.js') }}"></script>
    <script src="{{ asset('admin-assets/js/custom.js') }}"></script>

    <!-- ⑥ Script gastronomie (global car utilisé sur plusieurs pages) -->
    <script>
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
        setupAudioButtons() {
            document.querySelectorAll('.audio-btn').forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault(); e.stopPropagation();
                    const match = button.getAttribute('onclick')?.match(/'([^']+)'/);
                    if (match) this.toggleAudio(button, match[1]);
                });
                button.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); button.click(); }
                });
            });
        }
        toggleAudio(button, audioUrl) {
            if (this.currentAudio && !this.currentAudio.paused) {
                this.stopAudio();
                if (this.currentButton === button) return;
            }
            this.playAudio(button, audioUrl);
        }
        playAudio(button, audioUrl) {
            this.currentAudio = new Audio(audioUrl);
            this.currentButton = button;
            this.isPlaying = true;
            this.currentAudio.preload = 'auto';
            button.classList.add('playing');
            const icon = button.querySelector('i');
            if (icon) { icon.classList.remove('bi-volume-up-fill'); icon.classList.add('bi-pause-fill'); }
            this.showLoadingState(button);
            this.currentAudio.addEventListener('loadeddata', () => this.hideLoadingState(button));
            this.currentAudio.addEventListener('playing', () => { this.hideLoadingState(button); this.createSoundWave(button); });
            this.currentAudio.addEventListener('ended', () => this.stopAudio());
            this.currentAudio.addEventListener('error', () => { this.stopAudio(); this.showAudioError(button); });
            this.currentAudio.play().catch(() => { this.stopAudio(); this.showAudioError(button); });
            if ('vibrate' in navigator) navigator.vibrate(50);
        }
        stopAudio() {
            if (this.currentAudio) { this.currentAudio.pause(); this.currentAudio.currentTime = 0; this.currentAudio = null; }
            if (this.currentButton) {
                this.currentButton.classList.remove('playing');
                const icon = this.currentButton.querySelector('i');
                if (icon) { icon.classList.remove('bi-pause-fill'); icon.classList.add('bi-volume-up-fill'); }
                this.removeSoundWave(this.currentButton);
                this.currentButton = null;
            }
            this.isPlaying = false;
        }
        createSoundWave(button) {
            if (button.querySelector('.sound-wave')) return;
            const wave = document.createElement('div');
            wave.className = 'sound-wave';
            wave.innerHTML = '<span></span><span></span><span></span>';
            const icon = button.querySelector('i');
            if (icon) icon.style.display = 'none';
            button.appendChild(wave);
        }
        removeSoundWave(button) {
            const wave = button.querySelector('.sound-wave');
            if (wave) wave.remove();
            const icon = button.querySelector('i');
            if (icon) icon.style.display = 'inline';
        }
        showLoadingState(button) {
            const spinner = document.createElement('div');
            spinner.className = 'spinner-border spinner-border-sm text-light';
            spinner.setAttribute('role', 'status');
            spinner.innerHTML = '<span class="visually-hidden">Chargement...</span>';
            const icon = button.querySelector('i');
            if (icon) icon.style.display = 'none';
            button.appendChild(spinner);
        }
        hideLoadingState(button) {
            const spinner = button.querySelector('.spinner-border');
            if (spinner) spinner.remove();
        }
        showAudioError(button) {
            const icon = button.querySelector('i');
            if (icon) { icon.style.display = 'inline'; icon.classList.add('text-danger'); setTimeout(() => icon.classList.remove('text-danger'), 2000); }
        }
        setupFilterAnimations() {
            document.querySelectorAll('.category-tab').forEach(tab => {
                tab.addEventListener('click', () => {
                    const grid = document.querySelector('.dish-grid');
                    if (grid) { grid.classList.add('filtering'); setTimeout(() => grid.classList.remove('filtering'), 300); }
                });
            });
        }
        setupScrollAnimations() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '0';
                        entry.target.style.transform = 'translateY(20px)';
                        setTimeout(() => {
                            entry.target.style.transition = 'all 0.6s cubic-bezier(0.4,0,0.2,1)';
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0)';
                        }, 100);
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
            document.querySelectorAll('.dish-card').forEach(card => observer.observe(card));
        }
        setupSearchEnhancements() {
            const searchInput = document.querySelector('input[name="search"]');
            if (!searchInput) return;
            let searchTimeout, lastSearch = searchInput.value;
            searchInput.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                const value = e.target.value;
                if (value.length > 0 && value !== lastSearch) {
                    searchInput.style.borderColor = 'var(--benin-yellow)';
                    searchTimeout = setTimeout(() => { searchInput.style.borderColor = ''; lastSearch = value; }, 800);
                }
            });
            searchInput.addEventListener('keydown', (e) => { if (e.key === 'Escape') { searchInput.value = ''; searchInput.focus(); } });
            const suggestions = ['Amiwo','Akassa','Aloko','Tchoucoutou','Atassi','Wagashi','Pâte rouge','Fonio'];
            const datalist = document.createElement('datalist');
            datalist.id = 'dish-suggestions';
            suggestions.forEach(s => { const o = document.createElement('option'); o.value = s; datalist.appendChild(o); });
            input.setAttribute('list', 'dish-suggestions');
            input.parentElement.appendChild(datalist);
        }
        setupKeyboardNavigation() {
            document.addEventListener('keydown', (e) => {
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    const si = document.querySelector('input[name="search"]');
                    if (si) { si.focus(); si.select(); }
                }
                if (e.key === ' ' && this.isPlaying && document.activeElement.tagName !== 'INPUT') {
                    e.preventDefault(); this.stopAudio();
                }
            });
        }
        setupAudioVisualization() {
            try { this.audioContext = new (window.AudioContext || window.webkitAudioContext)(); }
            catch(e) {}
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        window.gastronomieManager = new GastronomieManager();
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });
    });

    function playAudio(button, audioUrl) {
        if (window.gastronomieManager) window.gastronomieManager.toggleAudio(button, audioUrl);
    }
    function filterByCategory(category) {
        const url = new URL(window.location.href);
        if (category) url.searchParams.set('category', category);
        else url.searchParams.delete('category');
        url.searchParams.delete('page');
        window.location.href = url.toString();
    }
    </script>

    <!-- ⑦ Scripts des vues enfants -->
    @stack('scripts')
</body>

</html>