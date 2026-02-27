@extends('layouts.app')

@section('title', $vendor->name . ' - Vendeur')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
:root {
    --g: #009639;
    --g-light: #00b847;
    --g-dark: #007a2e;
    --g-pale: #e8f5e9;
    --y: #FCD116;
    --y-dark: #e0b800;
    --r: #E8112D;
    --r-pale: #fdecea;
    --dark: #1a1a1a;
    --mid: #555;
    --light: #f7f8fa;
    --white: #fff;
    --border: #e8eaed;
    --shadow: 0 4px 24px rgba(0,0,0,0.08);
    --shadow-lg: 0 16px 48px rgba(0,0,0,0.14);
    --radius: 20px;
}

* { box-sizing: border-box; }

body { font-family: 'DM Sans', sans-serif; background: var(--light); color: var(--dark); }

/* ── HERO ── */
.vendor-hero {
    position: relative;
    background: var(--dark);
    min-height: 340px;
    overflow: hidden;
}
.vendor-hero-bg {
    position: absolute; inset: 0;
    background: linear-gradient(135deg, #0a2e17 0%, #1a1a1a 50%, #2e0a0a 100%);
}
.vendor-hero-bg::before {
    content: '';
    position: absolute; inset: 0;
    background-image:
        radial-gradient(circle at 20% 50%, rgba(0,150,57,0.3) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(232,17,45,0.2) 0%, transparent 40%),
        radial-gradient(circle at 60% 80%, rgba(252,209,22,0.15) 0%, transparent 40%);
}
.vendor-hero-pattern {
    position: absolute; inset: 0; opacity: 0.04;
    background-image: repeating-linear-gradient(
        45deg, #fff 0, #fff 1px, transparent 0, transparent 50%
    );
    background-size: 20px 20px;
}
.vendor-hero-content {
    position: relative; z-index: 2;
    padding: 3rem 0 2rem;
}

/* Avatar */
.vendor-avatar {
    width: 110px; height: 110px;
    border-radius: 50%;
    border: 4px solid var(--y);
    object-fit: cover;
    box-shadow: 0 0 0 6px rgba(252,209,22,0.2);
    flex-shrink: 0;
}
.vendor-avatar-initials {
    width: 110px; height: 110px;
    border-radius: 50%;
    border: 4px solid var(--y);
    box-shadow: 0 0 0 6px rgba(252,209,22,0.2);
    background: linear-gradient(135deg, var(--g), var(--g-dark));
    display: flex; align-items: center; justify-content: center;
    font-family: 'Playfair Display', serif;
    font-size: 2.2rem; font-weight: 900;
    color: var(--white);
    flex-shrink: 0;
}

.vendor-name {
    font-family: 'Playfair Display', serif;
    font-size: 2.2rem; font-weight: 900;
    color: var(--white);
    line-height: 1.1;
    margin: 0 0 8px;
}
.vendor-type-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(252,209,22,0.2);
    border: 1px solid rgba(252,209,22,0.5);
    color: var(--y);
    padding: 5px 14px;
    border-radius: 100px;
    font-size: 0.82rem; font-weight: 600;
    letter-spacing: 0.3px;
}
.vendor-verified {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(0,150,57,0.25);
    border: 1px solid rgba(0,150,57,0.5);
    color: #5ddb8a;
    padding: 5px 12px;
    border-radius: 100px;
    font-size: 0.8rem; font-weight: 600;
}
.vendor-rating-hero {
    display: flex; align-items: center; gap: 8px;
    color: rgba(255,255,255,0.85);
    font-size: 0.9rem;
}
.stars-row { color: var(--y); letter-spacing: 1px; }

/* ── MAIN LAYOUT ── */
.vendor-layout {
    max-width: 1200px; margin: 0 auto;
    padding: 2.5rem 1rem;
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 2rem;
}
@media (max-width: 991px) {
    .vendor-layout { grid-template-columns: 1fr; }
}

/* ── CARDS ── */
.vcard {
    background: var(--white);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 1.75rem;
    margin-bottom: 1.5rem;
}
.vcard-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.15rem; font-weight: 700;
    color: var(--dark);
    margin-bottom: 1.25rem;
    display: flex; align-items: center; gap: 10px;
}
.vcard-title-icon {
    width: 34px; height: 34px;
    background: var(--g-pale);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    color: var(--g); font-size: 0.95rem;
    flex-shrink: 0;
}

/* ── INFOS CONTACT ── */
.info-item {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid var(--border);
}
.info-item:last-child { border-bottom: none; }
.info-icon {
    width: 36px; height: 36px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.9rem; flex-shrink: 0;
}
.info-icon.green { background: var(--g-pale); color: var(--g); }
.info-icon.yellow { background: #fff8e1; color: var(--y-dark); }
.info-icon.red { background: var(--r-pale); color: var(--r); }
.info-label { font-size: 0.75rem; color: var(--mid); margin-bottom: 1px; }
.info-value { font-weight: 500; font-size: 0.9rem; }

/* ── HORAIRES ── */
.hours-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 6px;
}
.hour-row {
    display: flex; justify-content: space-between;
    padding: 6px 10px;
    border-radius: 8px;
    background: var(--light);
    font-size: 0.82rem;
}
.hour-day { font-weight: 600; color: var(--dark); }
.hour-time { color: var(--g); font-weight: 500; }
.hour-closed { color: var(--r); font-weight: 500; }

/* ── SPÉCIALITÉS TAGS ── */
.tags-wrap { display: flex; flex-wrap: wrap; gap: 8px; }
.tag {
    background: var(--g-pale);
    color: var(--g-dark);
    border: 1px solid rgba(0,150,57,0.2);
    padding: 5px 14px;
    border-radius: 100px;
    font-size: 0.82rem; font-weight: 500;
}

/* ── PLATS DU VENDEUR ── */
.dish-row {
    display: flex; align-items: center; gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid var(--border);
    text-decoration: none; color: inherit;
    transition: all 0.2s;
}
.dish-row:last-child { border-bottom: none; }
.dish-row:hover { background: var(--light); margin: 0 -1.75rem; padding-left: 1.75rem; padding-right: 1.75rem; border-radius: 12px; }
.dish-thumb {
    width: 56px; height: 56px; border-radius: 12px;
    object-fit: cover; flex-shrink: 0;
}
.dish-thumb-placeholder {
    width: 56px; height: 56px; border-radius: 12px;
    background: linear-gradient(135deg, var(--g-pale), #c8e6c9);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem; flex-shrink: 0;
}
.dish-name { font-weight: 600; font-size: 0.9rem; margin-bottom: 2px; }
.dish-price {
    display: inline-flex; align-items: center;
    background: var(--g-pale); color: var(--g-dark);
    padding: 2px 10px; border-radius: 100px;
    font-size: 0.78rem; font-weight: 700;
}
.dish-unavailable {
    display: inline-flex; align-items: center; gap: 4px;
    color: var(--r); font-size: 0.75rem;
}

/* ── AVIS ── */
.rating-big {
    text-align: center; padding: 1rem;
    background: linear-gradient(135deg, var(--g-pale), #fff);
    border-radius: 14px; margin-bottom: 1rem;
}
.rating-number {
    font-family: 'Playfair Display', serif;
    font-size: 3.5rem; font-weight: 900;
    color: var(--g); line-height: 1;
}
.review-card {
    padding: 14px;
    background: var(--light);
    border-radius: 14px;
    margin-bottom: 10px;
}
.reviewer-avatar {
    width: 38px; height: 38px; border-radius: 50%;
    background: linear-gradient(135deg, var(--g), var(--g-dark));
    display: flex; align-items: center; justify-content: center;
    color: white; font-weight: 700; font-size: 0.8rem;
    flex-shrink: 0;
}

/* ── CARTE LEAFLET ── */
#vendorMap {
    height: 320px;
    border-radius: 14px;
    overflow: hidden;
    border: 2px solid var(--border);
}
.map-address-bar {
    display: flex; align-items: center; gap: 10px;
    background: var(--g-pale);
    border-radius: 12px;
    padding: 10px 14px;
    margin-bottom: 1rem;
    font-size: 0.85rem;
}
.map-address-bar i { color: var(--g); font-size: 1.1rem; }

/* ── BOUTONS ── */
.btn-whatsapp-vd {
    background: #25D366;
    color: white; border: none;
    padding: 12px 20px;
    border-radius: 14px;
    font-weight: 600; font-size: 0.9rem;
    display: flex; align-items: center; gap: 8px;
    text-decoration: none;
    transition: all 0.2s;
    width: 100%;
}
.btn-whatsapp-vd:hover { background: #1dba58; color: white; transform: translateY(-1px); }
.btn-call-vd {
    background: var(--g-pale);
    color: var(--g-dark); border: 2px solid rgba(0,150,57,0.2);
    padding: 12px 20px;
    border-radius: 14px;
    font-weight: 600; font-size: 0.9rem;
    display: flex; align-items: center; gap: 8px;
    text-decoration: none;
    transition: all 0.2s;
    width: 100%;
}
.btn-call-vd:hover { background: var(--g); color: white; border-color: var(--g); }
.btn-direction {
    background: var(--dark);
    color: white; border: none;
    padding: 12px 20px;
    border-radius: 14px;
    font-weight: 600; font-size: 0.9rem;
    display: flex; align-items: center; gap: 8px;
    cursor: pointer;
    transition: all 0.2s;
    width: 100%;
}
.btn-direction:hover { background: #333; transform: translateY(-1px); }

/* ── STATUT OUVERT/FERMÉ ── */
.status-open {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(0,150,57,0.12);
    color: var(--g); border: 1px solid rgba(0,150,57,0.3);
    padding: 4px 12px; border-radius: 100px;
    font-size: 0.8rem; font-weight: 600;
}
.status-open::before {
    content: ''; width: 7px; height: 7px; border-radius: 50%;
    background: var(--g);
    animation: pulse-green 1.5s infinite;
}
.status-closed {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--r-pale); color: var(--r);
    border: 1px solid rgba(232,17,45,0.2);
    padding: 4px 12px; border-radius: 100px;
    font-size: 0.8rem; font-weight: 600;
}
.status-closed::before {
    content: ''; width: 7px; height: 7px; border-radius: 50%;
    background: var(--r);
}
@keyframes pulse-green {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.4; transform: scale(1.3); }
}

/* ── BREADCRUMB ── */
.vd-breadcrumb {
    background: rgba(255,255,255,0.08);
    padding: 10px 0;
    position: relative; z-index: 2;
}
.vd-breadcrumb .breadcrumb { margin: 0; }
.vd-breadcrumb .breadcrumb-item a { color: rgba(255,255,255,0.6); text-decoration: none; font-size: 0.82rem; }
.vd-breadcrumb .breadcrumb-item.active { color: var(--y); font-size: 0.82rem; }
.vd-breadcrumb .breadcrumb-item+.breadcrumb-item::before { color: rgba(255,255,255,0.3); }

/* ── ANIMATIONS ── */
.vcard { animation: fadeUp 0.4s ease both; }
.vcard:nth-child(1) { animation-delay: 0.05s; }
.vcard:nth-child(2) { animation-delay: 0.1s; }
.vcard:nth-child(3) { animation-delay: 0.15s; }
.vcard:nth-child(4) { animation-delay: 0.2s; }

@keyframes fadeUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ── LEAFLET CUSTOM MARKER ── */
.vendor-marker-pin {
    width: 40px; height: 40px;
    background: var(--g);
    border-radius: 50% 50% 50% 0;
    transform: rotate(-45deg);
    border: 3px solid white;
    box-shadow: 0 4px 14px rgba(0,150,57,0.5);
}
</style>
@endpush

@section('content')

{{-- ══════════════════════ HERO ══════════════════════ --}}
<section class="vendor-hero">
    <div class="vendor-hero-bg">
        <div class="vendor-hero-pattern"></div>
    </div>

    {{-- Breadcrumb --}}
    <div class="vd-breadcrumb">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('gastronomie.index') }}">Gastronomie</a></li>
                    <li class="breadcrumb-item active">{{ Str::limit($vendor->name, 30) }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="vendor-hero-content">
        <div class="container">
            <div class="d-flex align-items-center gap-4 flex-wrap">

                {{-- Avatar ou initiales --}}
                @if($vendor->logo)
                    <img src="{{ $vendor->logo_url }}" alt="{{ $vendor->name }}" class="vendor-avatar">
                @else
                    <div class="vendor-avatar-initials">
                        {{ strtoupper(substr($vendor->name, 0, 1)) }}{{ strtoupper(substr(strstr($vendor->name, ' ') ?: ' X', 1, 1)) }}
                    </div>
                @endif

                <div>
                    <div class="d-flex align-items-center gap-3 flex-wrap mb-2">
                        <h1 class="vendor-name">{{ $vendor->name }}</h1>
                        @if($vendor->verified)
                            <span class="vendor-verified">
                                <i class="bi bi-patch-check-fill"></i> Vérifié
                            </span>
                        @endif
                    </div>

                    <div class="d-flex align-items-center gap-3 flex-wrap mb-3">
                        <span class="vendor-type-badge">
                            <i class="bi bi-shop"></i>
                            {{ $vendor->type_label }}
                        </span>
                        @if($vendor->city)
                            <span style="color:rgba(255,255,255,0.7); font-size:0.88rem;">
                                <i class="bi bi-geo-alt-fill me-1" style="color:#FCD116;"></i>
                                {{ $vendor->city }}
                            </span>
                        @endif
                    </div>

                    @if($vendor->rating_avg > 0)
                    <div class="vendor-rating-hero">
                        <span class="stars-row">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= round($vendor->rating_avg) ? '-fill' : ($i - 0.5 <= $vendor->rating_avg ? '-half' : '') }}"></i>
                            @endfor
                        </span>
                        <strong style="color:white;">{{ number_format($vendor->rating_avg, 1) }}</strong>
                        <span style="color:rgba(255,255,255,0.5);">({{ $vendor->rating_count }} avis)</span>
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════ CONTENU ══════════════════════ --}}
<div class="vendor-layout">

    {{-- ═══════ COLONNE GAUCHE ═══════ --}}
    <div>

        {{-- Description --}}
        @if($vendor->description)
        <div class="vcard">
            <div class="vcard-title">
                <div class="vcard-title-icon"><i class="bi bi-file-text"></i></div>
                À propos
            </div>
            <p style="color:var(--mid); line-height:1.75; margin:0;">{{ $vendor->description }}</p>
        </div>
        @endif

        {{-- Infos de contact --}}
        <div class="vcard">
            <div class="vcard-title">
                <div class="vcard-title-icon"><i class="bi bi-person-lines-fill"></i></div>
                Informations de contact
            </div>

            @if($vendor->address || $vendor->city)
            <div class="info-item">
                <div class="info-icon green"><i class="bi bi-geo-alt-fill"></i></div>
                <div>
                    <div class="info-label">Adresse</div>
                    <div class="info-value">{{ $vendor->address }}{{ $vendor->city ? ', ' . $vendor->city : '' }}</div>
                </div>
            </div>
            @endif

            @if($vendor->phone)
            <div class="info-item">
                <div class="info-icon green"><i class="bi bi-telephone-fill"></i></div>
                <div>
                    <div class="info-label">Téléphone</div>
                    <a href="tel:{{ $vendor->phone }}" class="info-value" style="color:var(--g); text-decoration:none;">
                        {{ $vendor->phone }}
                    </a>
                </div>
            </div>
            @endif

            @if($vendor->whatsapp)
            <div class="info-item">
                <div class="info-icon green"><i class="bi bi-whatsapp"></i></div>
                <div>
                    <div class="info-label">WhatsApp</div>
                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $vendor->whatsapp) }}" target="_blank"
                       class="info-value" style="color:#25D366; text-decoration:none;">
                        {{ $vendor->whatsapp }}
                    </a>
                </div>
            </div>
            @endif

            @if($vendor->user && $vendor->user->email)
            <div class="info-item">
                <div class="info-icon yellow"><i class="bi bi-envelope-fill"></i></div>
                <div>
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $vendor->user->email }}</div>
                </div>
            </div>
            @endif
        </div>

        {{-- Spécialités --}}
        @if($vendor->specialties && count($vendor->specialties) > 0)
        <div class="vcard">
            <div class="vcard-title">
                <div class="vcard-title-icon"><i class="bi bi-stars"></i></div>
                Spécialités
            </div>
            <div class="tags-wrap">
                @foreach($vendor->specialties as $spec)
                    <span class="tag">{{ $spec }}</span>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Horaires --}}
        @if($vendor->opening_hours)
        <div class="vcard">
            <div class="vcard-title">
                <div class="vcard-title-icon"><i class="bi bi-clock-fill"></i></div>
                Horaires d'ouverture
            </div>
            @php
                $hours = is_array($vendor->opening_hours)
                    ? $vendor->opening_hours
                    : json_decode($vendor->opening_hours, true) ?? [];
                $days = ['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche'];
                $dayKeys = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
            @endphp
            <div class="hours-grid">
                @foreach($days as $i => $day)
                    @php $key = $dayKeys[$i]; $val = $hours[$key] ?? null; @endphp
                    <div class="hour-row">
                        <span class="hour-day">{{ $day }}</span>
                        @if($val && $val !== 'closed')
                            <span class="hour-time">{{ $val }}</span>
                        @else
                            <span class="hour-closed">Fermé</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Plats proposés --}}
        @if($vendor->dishes && $vendor->dishes->count() > 0)
        <div class="vcard">
            <div class="vcard-title">
                <div class="vcard-title-icon"><i class="bi bi-bowl-hot"></i></div>
                Plats proposés
                <span style="margin-left:auto; background:var(--g-pale); color:var(--g); padding:2px 10px; border-radius:100px; font-size:0.78rem; font-weight:700;">
                    {{ $vendor->dishes->count() }}
                </span>
            </div>
            @foreach($vendor->dishes as $dish)
            <a href="{{ route('gastronomie.show', $dish) }}" class="dish-row">
                @if($dish->images->first())
                    <img src="{{ asset($dish->images->first()->image_url) }}"
                         alt="{{ $dish->name }}" class="dish-thumb">
                @else
                    <div class="dish-thumb-placeholder">🍽️</div>
                @endif
                <div class="flex-grow-1">
                    <div class="dish-name">{{ $dish->name }}</div>
                    @if($dish->name_local)
                        <div style="font-size:0.78rem; color:var(--mid);">{{ $dish->name_local }}</div>
                    @endif
                    <div class="mt-1">
                        @if($dish->pivot && $dish->pivot->price)
                            <span class="dish-price">
                                {{ number_format($dish->pivot->price, 0, ',', ' ') }} FCFA
                            </span>
                        @endif
                        @if($dish->available && !$dish->pivot->available)
                            <span class="dish-unavailable ms-2">
                                <i class="bi bi-x-circle-fill"></i> Indisponible
                            </span>
                        @endif
                    </div>
                </div>
                <i class="bi bi-chevron-right" style="color:var(--border);"></i>
            </a>
            @endforeach
        </div>
        @endif

        {{-- Avis --}}
        <div class="vcard">
            <div class="vcard-title">
                <div class="vcard-title-icon"><i class="bi bi-chat-square-quote"></i></div>
                Avis clients
            </div>

            @if($vendor->rating_avg > 0)
            <div class="rating-big">
                <div class="rating-number">{{ number_format($vendor->rating_avg, 1) }}</div>
                <div class="stars-row mt-1" style="font-size:1.2rem;">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="bi bi-star{{ $i <= round($vendor->rating_avg) ? '-fill' : '' }}"></i>
                    @endfor
                </div>
                <div style="font-size:0.82rem; color:var(--mid); margin-top:4px;">
                    Basé sur {{ $vendor->rating_count }} avis
                </div>
            </div>
            @endif

            @forelse($vendor->reviews()->latest()->take(5)->get() as $review)
            <div class="review-card">
                <div class="d-flex align-items-center gap-10 mb-2" style="gap:10px;">
                    <div class="reviewer-avatar">
                        {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="flex-grow-1">
                        <div style="font-weight:600; font-size:0.88rem;">{{ $review->user->name ?? 'Anonyme' }}</div>
                        <div style="color:var(--y-dark); font-size:0.75rem;">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                            @endfor
                        </div>
                    </div>
                    <small style="color:var(--mid); font-size:0.75rem;">
                        {{ $review->created_at->diffForHumans() }}
                    </small>
                </div>
                @if($review->comment)
                <p style="margin:0; font-size:0.85rem; color:var(--mid); line-height:1.6;">
                    {{ $review->comment }}
                </p>
                @endif
            </div>
            @empty
            <div style="text-align:center; padding:2rem; color:var(--mid);">
                <i class="bi bi-chat-square-dots" style="font-size:2rem; display:block; margin-bottom:8px;"></i>
                Aucun avis pour le moment
            </div>
            @endforelse
        </div>

    </div>

    {{-- ═══════ COLONNE DROITE (SIDEBAR) ═══════ --}}
    <div>

        {{-- Boutons contact --}}
        <div class="vcard">
            <div class="vcard-title">
                <div class="vcard-title-icon" style="background:#e8f5e9;"><i class="bi bi-send-fill" style="color:var(--g);"></i></div>
                Contacter
            </div>
            <div class="d-flex flex-column gap-3">
                @if($vendor->whatsapp)
                <a href="https://wa.me/{{ preg_replace('/\D/', '', $vendor->whatsapp) }}"
                   target="_blank" class="btn-whatsapp-vd">
                    <i class="bi bi-whatsapp fs-5"></i>
                    Envoyer un message WhatsApp
                </a>
                @endif

                @if($vendor->phone)
                <a href="tel:{{ $vendor->phone }}" class="btn-call-vd">
                    <i class="bi bi-telephone-fill fs-5"></i>
                    Appeler le vendeur
                </a>
                @endif

                @if($vendor->latitude && $vendor->longitude)
                <button class="btn-direction" onclick="getDirections()">
                    <i class="bi bi-signpost-2-fill fs-5"></i>
                    Obtenir l'itinéraire
                </button>
                @endif
            </div>
        </div>

        {{-- Carte de localisation --}}
        @if($vendor->latitude && $vendor->longitude)
        <div class="vcard">
            <div class="vcard-title">
                <div class="vcard-title-icon"><i class="bi bi-map-fill"></i></div>
                Localisation exacte
            </div>

            <div class="map-address-bar">
                <i class="bi bi-pin-map-fill"></i>
                <span>{{ $vendor->address ?? '' }}{{ $vendor->city ? ($vendor->address ? ', ' : '') . $vendor->city : '' }}</span>
            </div>

            <div id="vendorMap"></div>

            <p style="font-size:0.72rem; color:var(--mid); margin:8px 0 0; text-align:center;">
                © <a href="https://www.openstreetmap.org" target="_blank" style="color:var(--g);">OpenStreetMap</a>
            </p>
        </div>
        @else
        <div class="vcard" style="text-align:center; padding:2rem;">
            <i class="bi bi-geo-alt" style="font-size:2.5rem; color:var(--border); display:block; margin-bottom:8px;"></i>
            <p style="color:var(--mid); margin:0; font-size:0.88rem;">Localisation non renseignée</p>
        </div>
        @endif

        {{-- Stats rapides --}}
        <div class="vcard">
            <div class="vcard-title">
                <div class="vcard-title-icon"><i class="bi bi-bar-chart-fill"></i></div>
                En un coup d'œil
            </div>
            <div class="d-flex flex-column gap-2">
                @if($vendor->dishes && $vendor->dishes->count() > 0)
                <div class="info-item">
                    <div class="info-icon green"><i class="bi bi-bowl-hot"></i></div>
                    <div>
                        <div class="info-label">Plats au menu</div>
                        <div class="info-value">{{ $vendor->dishes->count() }} plat{{ $vendor->dishes->count() > 1 ? 's' : '' }}</div>
                    </div>
                </div>
                @endif
                @if($vendor->rating_count > 0)
                <div class="info-item">
                    <div class="info-icon yellow"><i class="bi bi-star-fill"></i></div>
                    <div>
                        <div class="info-label">Note globale</div>
                        <div class="info-value">{{ number_format($vendor->rating_avg, 1) }}/5 · {{ $vendor->rating_count }} avis</div>
                    </div>
                </div>
                @endif
                @if($vendor->verified)
                <div class="info-item">
                    <div class="info-icon green"><i class="bi bi-patch-check-fill"></i></div>
                    <div>
                        <div class="info-label">Statut</div>
                        <div class="info-value" style="color:var(--g);">Vendeur vérifié ✓</div>
                    </div>
                </div>
                @endif
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

@if($vendor->latitude && $vendor->longitude)
<script>
    (function () {
        const lat  = {{ $vendor->latitude }};
        const lng  = {{ $vendor->longitude }};
        const name = @json($vendor->name);
        const addr = @json(($vendor->address ?? '') . ($vendor->city ? ', ' . $vendor->city : ''));

        const map = L.map('vendorMap', { zoomControl: true, scrollWheelZoom: false })
                     .setView([lat, lng], 16);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap',
            maxZoom: 19,
        }).addTo(map);

        // Marqueur personnalisé vert
        const icon = L.divIcon({
            html: `<div style="
                background:#009639; width:40px; height:40px;
                border-radius:50% 50% 50% 0; transform:rotate(-45deg);
                border:3px solid white;
                box-shadow:0 4px 14px rgba(0,150,57,0.5);
            "></div>`,
            className: '',
            iconSize: [40, 40],
            iconAnchor: [20, 40],
            popupAnchor: [0, -44],
        });

        L.marker([lat, lng], { icon })
         .addTo(map)
         .bindPopup(`
            <div style="min-width:180px; padding:4px; font-family:'DM Sans',sans-serif;">
                <strong style="font-size:14px; color:#1a1a1a;">${name}</strong><br>
                <span style="color:#555; font-size:12px;">📍 ${addr}</span>
            </div>
        `).openPopup();

        // Itinéraire
        window.getDirections = function () {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    pos => window.open(
                        `https://www.google.com/maps/dir/${pos.coords.latitude},${pos.coords.longitude}/${lat},${lng}`,
                        '_blank'
                    ),
                    () => window.open(`https://www.google.com/maps/dir//${lat},${lng}`, '_blank')
                );
            } else {
                window.open(`https://www.google.com/maps?q=${lat},${lng}`, '_blank');
            }
        };
    })();
</script>
@else
<script>
    window.getDirections = function () {};
</script>
@endif
@endpush
