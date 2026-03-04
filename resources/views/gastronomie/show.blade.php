@extends('layouts.app')

@section('title', $dish->name . ' - Gastronomie Béninoise')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
:root {
    --g: #009639;
    --g2: #007a2e;
    --g-pale: #e8f5e9;
    --y: #FCD116;
    --y2: #e0b800;
    --r: #E8112D;
    --r2: #b80d22;
    --terra: #C4622D;
    --beige: #FAF3E8;
    --beige2: #F0E4CC;
    --ink: #1C1C1C;
    --mid: #5a5a5a;
    --border: #e8e0d0;
    --white: #fff;
    --shadow: 0 2px 20px rgba(0,0,0,0.07);
    --shadow-md: 0 8px 32px rgba(0,0,0,0.11);
    --shadow-lg: 0 20px 60px rgba(0,0,0,0.16);
}

*, *::before, *::after { box-sizing: border-box; }
body { font-family: 'Outfit', sans-serif; background: var(--beige); color: var(--ink); }

/* ══════════════════════════════════════
   HERO
══════════════════════════════════════ */
.dish-hero {
    position: relative;
    height: 100vh;
    min-height: 600px;
    max-height: 800px;
    overflow: hidden;
}

.dish-hero-image {
    width: 100%; height: 100%;
    object-fit: cover;
    transform-origin: center;
    animation: heroZoom 12s ease-in-out infinite alternate;
}

@keyframes heroZoom {
    from { transform: scale(1); }
    to   { transform: scale(1.06); }
}

.dish-hero-overlay {
    position: absolute; inset: 0;
    background:
        linear-gradient(to top, rgba(10,10,10,0.97) 0%, rgba(10,10,10,0.5) 45%, transparent 75%),
        linear-gradient(to right, rgba(0,0,0,0.3) 0%, transparent 60%);
}

/* Motif tribal décoratif */
.dish-hero-decor {
    position: absolute; bottom: 0; right: 0;
    width: 400px; height: 400px;
    opacity: 0.06;
    background-image: repeating-conic-gradient(
        var(--y) 0deg 10deg, transparent 10deg 20deg
    );
    border-radius: 50% 0 0 0;
}

.dish-hero-content {
    position: absolute; bottom: 0; left: 0; right: 0;
    z-index: 3; padding: 4rem 0 3.5rem;
}

/* Ligne colorée décorative */
.dish-hero-line {
    display: flex; gap: 6px; margin-bottom: 1.5rem;
}
.dish-hero-line span {
    height: 4px; border-radius: 4px;
    display: block;
}
.dish-hero-line span:nth-child(1) { width: 60px; background: var(--g); }
.dish-hero-line span:nth-child(2) { width: 30px; background: var(--y); }
.dish-hero-line span:nth-child(3) { width: 20px; background: var(--r); }

.dish-hero h1 {
    font-family: 'Cormorant Garamond', serif;
    color: white;
    font-size: clamp(2.8rem, 6vw, 5rem);
    font-weight: 700;
    line-height: 1.05;
    margin: 0 0 0.5rem;
    letter-spacing: -1px;
}

.dish-local-name {
    font-family: 'Cormorant Garamond', serif;
    color: var(--y);
    font-size: clamp(1.3rem, 3vw, 2rem);
    font-style: italic;
    font-weight: 400;
    margin-bottom: 1.5rem;
    display: flex; align-items: center; gap: 10px;
}

.hero-audio-btn {
    width: 52px; height: 52px;
    border-radius: 50%;
    border: 2px solid rgba(255,255,255,0.3);
    background: rgba(255,255,255,0.12);
    backdrop-filter: blur(10px);
    display: inline-flex; align-items: center; justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-left: 12px;
    vertical-align: middle;
    flex-shrink: 0;
}
.hero-audio-btn:hover {
    background: var(--y);
    border-color: var(--y);
    transform: scale(1.1);
}
.hero-audio-btn i { color: white; font-size: 1.3rem; }
.hero-audio-btn.playing { animation: audioPulse 1.5s infinite; background: var(--r); border-color: var(--r); }

@keyframes audioPulse {
    0%, 100% { box-shadow: 0 0 0 0 rgba(232,17,45,0.5); }
    50%       { box-shadow: 0 0 0 14px rgba(232,17,45,0); }
}

.hero-badges {
    display: flex; gap: 10px; flex-wrap: wrap;
    margin-top: 1.5rem;
}
.hero-badge {
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.2);
    color: white;
    padding: 8px 18px;
    border-radius: 100px;
    font-size: 0.82rem;
    font-weight: 500;
    display: inline-flex; align-items: center; gap: 7px;
    transition: all 0.25s;
    letter-spacing: 0.2px;
}
.hero-badge:hover {
    background: rgba(252,209,22,0.2);
    border-color: rgba(252,209,22,0.5);
    transform: translateY(-2px);
}
.hero-badge i { font-size: 0.9rem; opacity: 0.85; }

/* Scroll indicator */
.scroll-hint {
    position: absolute; bottom: 2rem; right: 2rem;
    z-index: 4;
    display: flex; flex-direction: column; align-items: center; gap: 8px;
    color: rgba(255,255,255,0.5); font-size: 0.72rem;
    letter-spacing: 1px; text-transform: uppercase;
    animation: scrollBounce 2s ease-in-out infinite;
}
@keyframes scrollBounce {
    0%, 100% { transform: translateY(0); }
    50%       { transform: translateY(6px); }
}

/* ══════════════════════════════════════
   BREADCRUMB
══════════════════════════════════════ */
.dish-topbar {
    background: var(--white);
    border-bottom: 1px solid var(--border);
    padding: 14px 0;
    position: sticky; top: 0; z-index: 100;
    box-shadow: 0 2px 12px rgba(0,0,0,0.05);
}
.dish-topbar .container {
    display: flex; align-items: center; justify-content: space-between;
    gap: 1rem; flex-wrap: wrap;
}
.dish-breadcrumb { margin: 0; }
.dish-breadcrumb .breadcrumb { margin: 0; }
.dish-breadcrumb .breadcrumb-item a {
    color: var(--g); text-decoration: none;
    font-size: 0.83rem; font-weight: 600;
    transition: color 0.2s;
}
.dish-breadcrumb .breadcrumb-item a:hover { color: var(--r); }
.dish-breadcrumb .breadcrumb-item.active { font-size: 0.83rem; color: var(--mid); }
.back-button {
    display: inline-flex; align-items: center; gap: 7px;
    background: var(--ink); color: white;
    padding: 8px 18px; border-radius: 100px;
    text-decoration: none; font-size: 0.82rem; font-weight: 600;
    transition: all 0.25s;
    white-space: nowrap;
}
.back-button:hover {
    background: var(--r); color: white;
    transform: translateX(-3px);
}
.back-button i { transition: transform 0.25s; }
.back-button:hover i { transform: translateX(-3px); }

/* ══════════════════════════════════════
   LAYOUT
══════════════════════════════════════ */
.dish-body {
    max-width: 1240px; margin: 0 auto;
    padding: 3rem 1.25rem 5rem;
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 2.5rem;
    align-items: start;
}
@media (max-width: 991px) {
    .dish-body { grid-template-columns: 1fr; }
}

/* ══════════════════════════════════════
   SECTIONS
══════════════════════════════════════ */
.dish-section {
    background: var(--white);
    border-radius: 24px;
    padding: 2.5rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow);
    border: 1px solid rgba(0,0,0,0.04);
    position: relative;
    overflow: hidden;
    animation: sectionReveal 0.5s ease both;
}
.dish-section:nth-child(1) { animation-delay: 0.05s; }
.dish-section:nth-child(2) { animation-delay: 0.1s; }
.dish-section:nth-child(3) { animation-delay: 0.15s; }
.dish-section:nth-child(4) { animation-delay: 0.2s; }
.dish-section:nth-child(5) { animation-delay: 0.25s; }

@keyframes sectionReveal {
    from { opacity: 0; transform: translateY(24px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* Accent coin décoratif */
.dish-section::before {
    content: '';
    position: absolute; top: 0; left: 0;
    width: 4px; height: 60px;
    border-radius: 0 0 4px 0;
    background: linear-gradient(to bottom, var(--r), var(--y));
}

.section-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.75rem; font-weight: 700;
    color: var(--ink);
    margin: 0 0 1.5rem;
    display: flex; align-items: center; gap: 12px;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--beige2);
}
.section-icon {
    width: 40px; height: 40px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    background: var(--beige2); flex-shrink: 0;
    font-size: 1rem;
}
.section-icon.red   { background: #fdecea; color: var(--r); }
.section-icon.green { background: var(--g-pale); color: var(--g); }
.section-icon.yellow{ background: #fff8e1; color: var(--y2); }
.section-icon.terra { background: #fdf0ea; color: var(--terra); }
.section-icon.ink   { background: #f0f0f0; color: var(--ink); }

/* ══════════════════════════════════════
   DESCRIPTION
══════════════════════════════════════ */
.dish-description {
    font-size: 1.05rem; line-height: 1.9;
    color: var(--mid);
    text-align: justify;
}

/* ══════════════════════════════════════
   HISTOIRE
══════════════════════════════════════ */
.cultural-box {
    background: linear-gradient(135deg, rgba(0,150,57,0.04), rgba(232,17,45,0.04));
    border-radius: 18px;
    padding: 2rem;
    border: 1px solid rgba(232,17,45,0.1);
    position: relative;
}
.cultural-box-header {
    display: flex; align-items: center; gap: 10px;
    margin-bottom: 1rem;
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.1rem; font-weight: 700;
    color: var(--r);
}
.cultural-box p {
    font-size: 1rem; line-height: 1.85;
    color: var(--mid); margin: 0;
}
/* Guillemet décoratif */
.cultural-box::before {
    content: '\201C';
    position: absolute; top: -10px; left: 20px;
    font-family: 'Cormorant Garamond', serif;
    font-size: 6rem; line-height: 1;
    color: var(--r); opacity: 0.08;
    pointer-events: none;
}

/* ══════════════════════════════════════
   INGRÉDIENTS
══════════════════════════════════════ */
.ingredients-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
}
.ingredient-item {
    background: var(--beige);
    border: 1.5px solid var(--beige2);
    border-radius: 14px;
    padding: 1rem 1.25rem;
    display: flex; align-items: center; gap: 10px;
    font-weight: 500; font-size: 0.9rem;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: default;
    position: relative; overflow: hidden;
}
.ingredient-item::after {
    content: '';
    position: absolute; inset: 0;
    background: linear-gradient(135deg, var(--g-pale), transparent);
    opacity: 0; transition: opacity 0.25s;
}
.ingredient-item:hover {
    border-color: var(--g);
    transform: translateY(-3px);
    box-shadow: 0 6px 18px rgba(0,150,57,0.12);
}
.ingredient-item:hover::after { opacity: 1; }
.ingredient-item i {
    color: var(--g); font-size: 1rem;
    flex-shrink: 0; position: relative; z-index: 1;
}
.ingredient-item span { position: relative; z-index: 1; color: var(--ink); }

/* ══════════════════════════════════════
   PRÉPARATION
══════════════════════════════════════ */
.preparation-steps { list-style: none; padding: 0; margin: 0; counter-reset: steps; }
.preparation-step {
    counter-increment: steps;
    display: flex; gap: 1.25rem; align-items: flex-start;
    padding: 1.25rem 0;
    border-bottom: 1px dashed var(--beige2);
    font-size: 1rem; line-height: 1.8;
    color: var(--mid);
    transition: all 0.2s;
}
.preparation-step:last-child { border-bottom: none; }
.preparation-step:hover { color: var(--ink); }
.step-num {
    min-width: 44px; height: 44px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--r), var(--terra));
    color: white;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 1rem;
    flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(232,17,45,0.3);
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.2rem;
}

/* ══════════════════════════════════════
   GALERIE
══════════════════════════════════════ */
.dish-gallery {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
}
.dish-gallery .gallery-item:first-child {
    grid-column: span 2; grid-row: span 2;
}
.gallery-item {
    border-radius: 16px; overflow: hidden;
    aspect-ratio: 1; cursor: pointer;
    position: relative;
}
.gallery-item img {
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}
.gallery-item:hover img { transform: scale(1.08); }
.gallery-overlay {
    position: absolute; inset: 0;
    background: rgba(0,0,0,0);
    display: flex; align-items: center; justify-content: center;
    transition: background 0.3s;
}
.gallery-item:hover .gallery-overlay { background: rgba(0,0,0,0.35); }
.gallery-overlay i {
    color: white; font-size: 2rem;
    opacity: 0; transform: scale(0.7);
    transition: all 0.3s;
}
.gallery-item:hover .gallery-overlay i { opacity: 1; transform: scale(1); }

/* ══════════════════════════════════════
   SIDEBAR
══════════════════════════════════════ */
.sidebar-section {
    background: var(--white);
    border-radius: 24px;
    padding: 2rem;
    margin-bottom: 1.5rem;
    box-shadow: var(--shadow);
    border: 1px solid rgba(0,0,0,0.04);
    animation: sectionReveal 0.5s ease both;
}
.sidebar-section:nth-child(1) { animation-delay: 0.1s; }
.sidebar-section:nth-child(2) { animation-delay: 0.15s; }
.sidebar-section:nth-child(3) { animation-delay: 0.2s; }

.sidebar-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.4rem; font-weight: 700;
    margin: 0 0 1.25rem;
    display: flex; align-items: center; gap: 10px;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid var(--beige2);
}

/* ══════════════════════════════════════
   OCCASIONS
══════════════════════════════════════ */
.occasions-list { display: flex; flex-wrap: wrap; gap: 10px; }
.occasion-tag {
    background: linear-gradient(135deg, var(--g), var(--g2));
    color: white;
    padding: 9px 18px;
    border-radius: 100px;
    font-size: 0.82rem; font-weight: 600;
    display: inline-flex; align-items: center; gap: 7px;
    box-shadow: 0 4px 14px rgba(0,150,57,0.3);
    transition: all 0.25s;
}
.occasion-tag:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,150,57,0.45);
}
.occasion-tag i { font-size: 0.78rem; }

/* ══════════════════════════════════════
   RESTAURANTS / VENDORS
══════════════════════════════════════ */
.restaurant-card {
    background: var(--beige);
    border-radius: 18px;
    padding: 1.25rem;
    margin-bottom: 1rem;
    border: 1.5px solid var(--beige2);
    transition: all 0.25s;
    position: relative; overflow: hidden;
}
.restaurant-card::before {
    content: '';
    position: absolute; top: 0; left: 0; bottom: 0;
    width: 3px;
    background: linear-gradient(to bottom, var(--r), var(--g));
    border-radius: 3px 0 0 3px;
    transform: scaleY(0); transform-origin: top;
    transition: transform 0.3s ease;
}
.restaurant-card:hover::before { transform: scaleY(1); }
.restaurant-card:hover {
    border-color: rgba(232,17,45,0.2);
    box-shadow: var(--shadow-md);
    transform: translateX(4px);
}
.restaurant-card h5 {
    font-size: 0.95rem; font-weight: 700;
    color: var(--ink); margin: 0 0 0.75rem;
    display: flex; align-items: center; gap: 8px;
}
.restaurant-card h5 i { color: var(--r); }
.restaurant-info { display: flex; flex-direction: column; gap: 6px; }
.restaurant-info div {
    display: flex; align-items: center; gap: 8px;
    font-size: 0.82rem; color: var(--mid);
}
.restaurant-info i { color: var(--g); width: 16px; flex-shrink: 0; }
.vendor-profile-link {
    display: inline-flex; align-items: center; gap: 6px;
    margin-top: 10px;
    background: var(--ink);
    color: white;
    padding: 7px 14px;
    border-radius: 100px;
    font-size: 0.78rem; font-weight: 600;
    text-decoration: none;
    transition: all 0.2s;
}
.vendor-profile-link:hover { background: var(--g); color: white; }

/* ══════════════════════════════════════
   STATISTIQUES
══════════════════════════════════════ */
.stats-grid { display: flex; flex-direction: column; gap: 10px; }
.stat-row {
    display: flex; align-items: center; gap: 14px;
    background: var(--beige); border-radius: 14px;
    padding: 14px 16px;
    transition: all 0.2s;
}
.stat-row:hover { transform: translateX(4px); box-shadow: var(--shadow); }
.stat-ico {
    width: 42px; height: 42px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; flex-shrink: 0;
}
.stat-ico.red    { background: #fdecea; color: var(--r); }
.stat-ico.green  { background: var(--g-pale); color: var(--g); }
.stat-ico.yellow { background: #fff8e1; color: var(--y2); }
.stat-val { font-size: 1.2rem; font-weight: 700; color: var(--ink); line-height: 1; }
.stat-lbl { font-size: 0.75rem; color: var(--mid); margin-top: 1px; }

/* ══════════════════════════════════════
   NAVIGATION PLATS
══════════════════════════════════════ */
.dish-navigation {
    max-width: 1240px; margin: 0 auto;
    padding: 0 1.25rem 4rem;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
}
.nav-dish-separator {
    max-width: 1240px; margin: 0 auto;
    padding: 0 1.25rem 2rem;
    display: flex; align-items: center; gap: 1rem;
}
.nav-dish-separator::before,
.nav-dish-separator::after {
    content: ''; flex: 1;
    height: 1px; background: var(--border);
}
.nav-dish-separator span {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.1rem; color: var(--mid);
    white-space: nowrap;
}

.nav-dish-card {
    background: var(--white);
    border-radius: 20px; overflow: hidden;
    box-shadow: var(--shadow);
    text-decoration: none; color: inherit;
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex; flex-direction: column;
    border: 1px solid rgba(0,0,0,0.04);
}
.nav-dish-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-lg);
}
.nav-dish-image {
    height: 180px; overflow: hidden; position: relative;
}
.nav-dish-image img {
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}
.nav-dish-card:hover .nav-dish-image img { transform: scale(1.1); }
.nav-dish-image::after {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(to bottom, transparent 50%, rgba(0,0,0,0.25));
}
.nav-dish-content { padding: 1.25rem; }
.nav-dish-label {
    font-size: 0.72rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 1px;
    color: var(--r); margin-bottom: 6px;
    display: flex; align-items: center; gap: 6px;
}
.nav-dish-name {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.3rem; font-weight: 700;
    color: var(--ink); line-height: 1.3;
}

/* ══════════════════════════════════════
   LIGHTBOX
══════════════════════════════════════ */
.lightbox {
    position: fixed; inset: 0;
    background: rgba(0,0,0,0.93);
    display: flex; align-items: center; justify-content: center;
    z-index: 9999; cursor: pointer;
    animation: lbFadeIn 0.25s ease;
}
@keyframes lbFadeIn {
    from { opacity: 0; }
    to   { opacity: 1; }
}
.lightbox img {
    max-width: 90vw; max-height: 88vh;
    border-radius: 16px;
    box-shadow: 0 30px 80px rgba(0,0,0,0.7);
    animation: lbZoom 0.25s ease;
}
@keyframes lbZoom {
    from { transform: scale(0.88); opacity: 0; }
    to   { transform: scale(1);    opacity: 1; }
}
.lightbox-close {
    position: absolute; top: 20px; right: 24px;
    color: rgba(255,255,255,0.6); font-size: 2rem;
    cursor: pointer; transition: color 0.2s;
    line-height: 1;
}
.lightbox-close:hover { color: white; }

/* ══════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════ */
@media (max-width: 768px) {
    .dish-hero h1 { letter-spacing: -0.5px; }
    .dish-section, .sidebar-section { padding: 1.5rem; }
    .ingredients-grid { grid-template-columns: 1fr 1fr; }
    .dish-gallery { grid-template-columns: 1fr 1fr; }
    .dish-gallery .gallery-item:first-child { grid-column: span 2; grid-row: 1; }
    .dish-topbar .container { flex-direction: column; align-items: flex-start; }
}
@media (max-width: 480px) {
    .ingredients-grid { grid-template-columns: 1fr; }
    .dish-gallery { grid-template-columns: 1fr; }
    .dish-gallery .gallery-item:first-child { grid-column: 1; }
}
</style>
@endpush

@section('content')

{{-- ═══════════════ HERO ═══════════════ --}}
<section class="dish-hero">
    @if($dish->images->first())
        <img src="{{ asset($dish->images->first()->image_url) }}" alt="{{ $dish->name }}" class="dish-hero-image">
    @else
        <img src="{{ asset('dishes/Bénin.jpg') }}" alt="{{ $dish->name }}" class="dish-hero-image">
    @endif

    <div class="dish-hero-overlay"></div>
    <div class="dish-hero-decor"></div>

    <div class="dish-hero-content">
        <div class="container">
            <div class="dish-hero-line">
                <span></span><span></span><span></span>
            </div>

            <div class="d-flex align-items-center flex-wrap gap-2">
                <h1>{{ $dish->name }}</h1>
                @if($dish->audio_url)
                    <button class="hero-audio-btn"
                        onclick="playAudio(this, '{{ asset($dish->audio_url) }}')"
                        title="Écouter la prononciation">
                        <i class="bi bi-volume-up-fill"></i>
                    </button>
                @endif
            </div>

            @if($dish->name_local)
                <div class="dish-local-name">
                    <i class="bi bi-translate" style="opacity:0.7;"></i>
                    {{ $dish->name_local }}
                </div>
            @endif

            <div class="hero-badges">
                <span class="hero-badge">
                    <i class="bi bi-tag-fill"></i>{{ $dish->category_label }}
                </span>
                <span class="hero-badge">
                    <i class="bi bi-people-fill"></i>{{ $dish->ethnic_origin }}
                </span>
                <span class="hero-badge">
                    <i class="bi bi-geo-alt-fill"></i>{{ $dish->region }}
                </span>
            </div>
        </div>
    </div>

    <div class="scroll-hint">
        <i class="bi bi-chevron-down"></i>
        <span>Défiler</span>
    </div>
</section>

{{-- ═══════════════ TOPBAR ═══════════════ --}}
<div class="dish-topbar">
    <div class="container">
        <nav class="dish-breadcrumb" aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('gastronomie.index') }}">Gastronomie</a></li>
                <li class="breadcrumb-item active">{{ Str::limit($dish->name, 30) }}</li>
            </ol>
        </nav>
        <a href="{{ route('gastronomie.index') }}" class="back-button">
            <i class="bi bi-arrow-left"></i> Retour à la liste
        </a>
    </div>
</div>

{{-- ═══════════════ BODY ═══════════════ --}}
<div class="dish-body">

    {{-- ══ COLONNE PRINCIPALE ══ --}}
    <div>

        {{-- Description --}}
        @if($dish->description)
        <div class="dish-section">
            <h2 class="section-title">
                <span class="section-icon terra"><i class="bi bi-file-text-fill"></i></span>
                Description
            </h2>
            <div class="dish-description">{!! nl2br(e($dish->description)) !!}</div>
        </div>
        @endif

        {{-- Histoire & Culture --}}
        @if($dish->history)
        <div class="dish-section">
            <h2 class="section-title">
                <span class="section-icon red"><i class="bi bi-book-fill"></i></span>
                Histoire & Culture
            </h2>
            <div class="cultural-box">
                <div class="cultural-box-header">
                    <i class="bi bi-lightbulb-fill"></i>
                    Le saviez-vous ?
                </div>
                <p>{!! nl2br(e($dish->history)) !!}</p>
            </div>
        </div>
        @endif

        {{-- Ingrédients --}}
        @if($dish->ingredients && count($dish->ingredients) > 0)
        <div class="dish-section">
            <h2 class="section-title">
                <span class="section-icon green"><i class="bi bi-basket-fill"></i></span>
                Ingrédients
                <span style="margin-left:auto; background:var(--g-pale); color:var(--g); padding:3px 12px; border-radius:100px; font-size:0.78rem; font-weight:700; font-family:'Outfit',sans-serif;">
                    {{ count($dish->ingredients) }}
                </span>
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

        {{-- Préparation --}}
        @if($dish->preparation)
        <div class="dish-section">
            <h2 class="section-title">
                <span class="section-icon red"><i class="bi bi-fire"></i></span>
                Préparation
            </h2>
            <ol class="preparation-steps">
                @php $stepNum = 0; @endphp
                @foreach(explode("\n", $dish->preparation) as $step)
                    @if(trim($step))
                        @php $stepNum++; @endphp
                        <li class="preparation-step">
                            <div class="step-num">{{ $stepNum }}</div>
                            <div>{{ trim($step) }}</div>
                        </li>
                    @endif
                @endforeach
            </ol>
        </div>
        @endif

        {{-- Galerie --}}
        @if($dish->images->count() > 1)
        <div class="dish-section">
            <h2 class="section-title">
                <span class="section-icon ink"><i class="bi bi-images"></i></span>
                Galerie
            </h2>
            <div class="dish-gallery">
                @foreach($dish->images as $image)
                    <div class="gallery-item" onclick="openLightbox('{{ asset($image->image_url) }}')">
                        <img src="{{ asset($image->image_url) }}" alt="{{ $dish->name }}">
                        <div class="gallery-overlay">
                            <i class="bi bi-zoom-in"></i>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>

    {{-- ══ SIDEBAR ══ --}}
    <div>

        {{-- Occasions --}}
        @if($dish->occasions)
        <div class="sidebar-section">
            <h3 class="sidebar-title">
                <span class="section-icon green" style="width:32px;height:32px;border-radius:9px;">
                    <i class="bi bi-calendar-event-fill" style="font-size:0.85rem;"></i>
                </span>
                Occasions
            </h3>
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

        {{-- Où déguster - Vendors --}}
        @if($vendors && $vendors->count() > 0)
        <div class="sidebar-section">
            <h3 class="sidebar-title">
                <span class="section-icon red" style="width:32px;height:32px;border-radius:9px;">
                    <i class="bi bi-shop" style="font-size:0.85rem;"></i>
                </span>
                Où déguster
                <span style="margin-left:auto; background:#fdecea; color:var(--r); padding:2px 10px; border-radius:100px; font-size:0.75rem; font-weight:700;">
                    {{ $vendors->count() }}
                </span>
            </h3>
            @foreach($vendors as $vendor)
            <div class="restaurant-card">
                <h5>
                    <i class="bi bi-pin-map-fill"></i>
                    {{ $vendor->business_name ?? $vendor->user->name ?? $vendor->name }}
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
                        <i class="bi bi-tag-fill"></i>
                        <span style="color:var(--g); font-weight:700;">
                            {{ number_format($vendor->pivot->price, 0, ',', ' ') }} FCFA
                        </span>
                    </div>
                    @endif
                    @if($vendor->pivot)
                    <div>
                        @if($vendor->pivot->available)
                            <i class="bi bi-check-circle-fill" style="color:var(--g);"></i>
                            <span style="color:var(--g); font-weight:600;">Disponible</span>
                        @else
                            <i class="bi bi-x-circle-fill" style="color:var(--r);"></i>
                            <span style="color:var(--mid);">Non disponible</span>
                        @endif
                    </div>
                    @endif
                    @if($vendor->distance)
                    <div>
                        <i class="bi bi-signpost-2-fill"></i>
                        <span>À {{ number_format($vendor->distance, 1) }} km</span>
                    </div>
                    @endif
                </div>
                @if($vendor->user)
                <a href="{{ route('vendors.show', $vendor) }}" class="vendor-profile-link">
                    Voir le profil <i class="bi bi-arrow-right"></i>
                </a>
                @endif
            </div>
            @endforeach
        </div>

        {{-- Fallback restaurants statiques --}}
        @elseif($dish->restaurants && count($dish->restaurants) > 0)
        <div class="sidebar-section">
            <h3 class="sidebar-title">
                <span class="section-icon red" style="width:32px;height:32px;border-radius:9px;">
                    <i class="bi bi-shop" style="font-size:0.85rem;"></i>
                </span>
                Où déguster
            </h3>
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
            @endforeach
        </div>
        @endif

        {{-- Statistiques --}}
        <div class="sidebar-section">
            <h3 class="sidebar-title">
                <span class="section-icon yellow" style="width:32px;height:32px;border-radius:9px;">
                    <i class="bi bi-graph-up" style="font-size:0.85rem;"></i>
                </span>
                Statistiques
            </h3>
            <div class="stats-grid">
                <div class="stat-row">
                    <div class="stat-ico red"><i class="bi bi-eye-fill"></i></div>
                    <div>
                        <div class="stat-val">{{ number_format($dish->views) }}</div>
                        <div class="stat-lbl">Vues totales</div>
                    </div>
                </div>
                @if($dish->ingredients && count($dish->ingredients) > 0)
                <div class="stat-row">
                    <div class="stat-ico green"><i class="bi bi-basket-fill"></i></div>
                    <div>
                        <div class="stat-val">{{ count($dish->ingredients) }}</div>
                        <div class="stat-lbl">Ingrédients</div>
                    </div>
                </div>
                @endif
                @if($vendors && $vendors->count() > 0)
                <div class="stat-row">
                    <div class="stat-ico yellow"><i class="bi bi-shop"></i></div>
                    <div>
                        <div class="stat-val">{{ $vendors->count() }}</div>
                        <div class="stat-lbl">Vendeurs</div>
                    </div>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

{{-- ═══════════════ NAVIGATION PLATS ═══════════════ --}}
@if(isset($previousDish) || isset($nextDish))
<div class="nav-dish-separator">
    <span>Continuer la découverte</span>
</div>
<div class="dish-navigation">
    @if(isset($previousDish))
    <a href="{{ route('gastronomie.show', $previousDish) }}" class="nav-dish-card">
        <div class="nav-dish-image">
            <img src="{{ asset($previousDish->images->first()->image_url ?? 'images/default-dish.jpg') }}"
                 alt="{{ $previousDish->name }}">
        </div>
        <div class="nav-dish-content">
            <div class="nav-dish-label">
                <i class="bi bi-arrow-left"></i> Plat précédent
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
                Plat suivant <i class="bi bi-arrow-right"></i>
            </div>
            <div class="nav-dish-name">{{ $nextDish->name }}</div>
        </div>
    </a>
    @endif
</div>
@endif

@endsection

@push('scripts')
<script>
// ── AUDIO ────────────────────────────────────────────────────────────────────
let currentAudio = null, currentButton = null;

function playAudio(button, audioUrl) {
    if (currentAudio && !currentAudio.paused) {
        currentAudio.pause(); currentAudio.currentTime = 0;
        if (currentButton) {
            currentButton.classList.remove('playing');
            currentButton.querySelector('i').className = 'bi bi-volume-up-fill';
        }
        if (currentButton === button) { currentAudio = null; currentButton = null; return; }
    }
    currentAudio = new Audio(audioUrl);
    currentButton = button;
    button.classList.add('playing');
    button.querySelector('i').className = 'bi bi-pause-fill';
    currentAudio.play().catch(() => {
        button.classList.remove('playing');
        button.querySelector('i').className = 'bi bi-volume-up-fill';
    });
    currentAudio.addEventListener('ended', () => {
        button.classList.remove('playing');
        button.querySelector('i').className = 'bi bi-volume-up-fill';
        currentAudio = null; currentButton = null;
    });
    if ('vibrate' in navigator) navigator.vibrate(40);
}

// ── LIGHTBOX ─────────────────────────────────────────────────────────────────
function openLightbox(imageUrl) {
    const lb = document.createElement('div');
    lb.className = 'lightbox';

    const close = document.createElement('span');
    close.className = 'lightbox-close';
    close.innerHTML = '&times;';

    const img = document.createElement('img');
    img.src = imageUrl; img.alt = '';

    lb.append(close, img);
    document.body.appendChild(lb);
    document.body.style.overflow = 'hidden';

    const dismiss = () => {
        lb.style.opacity = '0';
        lb.style.transition = 'opacity 0.2s';
        setTimeout(() => { lb.remove(); document.body.style.overflow = ''; }, 220);
    };
    lb.addEventListener('click', dismiss);
    img.addEventListener('click', e => e.stopPropagation());

    document.addEventListener('keydown', function esc(e) {
        if (e.key === 'Escape') { dismiss(); document.removeEventListener('keydown', esc); }
    });
}
</script>
@endpush
