@extends('layouts.app')

@section('title', ($artisan->business_name ?? $artisan->user->name) . ' — TOTCHÉMÈGNON')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
<style>
/* ══════════════════════════════════════════
   PROFIL ARTISAN — TOTCHÉMÈGNON
══════════════════════════════════════════ */

/* ── Hero ── */
.ap-hero {
    background: linear-gradient(135deg, #005c38 0%, #008751 55%, #009a5c 100%);
    padding: 48px 0 0;
    position: relative;
    overflow: hidden;
}
.ap-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.06'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/svg%3E");
}
.ap-hero::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 60px;
    background: #f0f4f8;
    clip-path: ellipse(55% 100% at 50% 100%);
}

/* Avatar héro */
.ap-avatar-wrap {
    position: relative;
    display: inline-block;
    flex-shrink: 0;
}
.ap-avatar {
    width: 110px; height: 110px;
    border-radius: 20px;
    object-fit: cover;
    border: 4px solid rgba(255,255,255,.3);
    box-shadow: 0 12px 40px rgba(0,0,0,.3);
}
.ap-avatar-fallback {
    width: 110px; height: 110px;
    border-radius: 20px;
    display: flex; align-items: center; justify-content: center;
    font-size: 2.4rem; font-weight: 800; color: #008751;
    background: #fff;
    border: 4px solid rgba(255,255,255,.3);
    box-shadow: 0 12px 40px rgba(0,0,0,.3);
    flex-shrink: 0;
}
.ap-verified-badge {
    position: absolute;
    bottom: -6px; right: -6px;
    background: #FCD116;
    color: #1a1d23;
    border-radius: 50%;
    width: 28px; height: 28px;
    display: flex; align-items: center; justify-content: center;
    font-size: .8rem;
    box-shadow: 0 2px 8px rgba(0,0,0,.2);
}

.ap-name {
    font-family: 'Montserrat', sans-serif;
    font-size: 1.65rem; font-weight: 800;
    color: #fff; margin: 0 0 4px;
    line-height: 1.2;
}
.ap-craft-pill {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(255,255,255,.18);
    border: 1px solid rgba(255,255,255,.25);
    border-radius: 20px;
    padding: 4px 14px;
    font-size: .78rem; font-weight: 600;
    color: #FCD116;
    text-transform: capitalize;
}
.ap-meta-item {
    display: flex; align-items: center; gap: 5px;
    font-size: .8rem; color: rgba(255,255,255,.8);
}
.ap-stars { color: #FCD116; font-size: .95rem; letter-spacing: -1px; }

/* Actions hero */
.ap-action-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 18px; border-radius: 12px;
    font-size: .82rem; font-weight: 700;
    text-decoration: none; cursor: pointer; border: none;
    transition: all .18s;
    white-space: nowrap;
}
.ap-btn-wa  { background: #25d366; color: #fff; }
.ap-btn-wa:hover  { background: #1fb95a; color: #fff; transform: translateY(-1px); }
.ap-btn-call { background: rgba(255,255,255,.15); color: #fff; border: 1.5px solid rgba(255,255,255,.3); }
.ap-btn-call:hover { background: rgba(255,255,255,.25); color: #fff; }
.ap-btn-devis { background: #E8112D; color: #fff; }
.ap-btn-devis:hover { background: #c00f27; color: #fff; transform: translateY(-1px); }
.ap-btn-share { background: rgba(255,255,255,.12); color: rgba(255,255,255,.85); border: 1px solid rgba(255,255,255,.2); }
.ap-btn-share:hover { background: rgba(255,255,255,.2); color: #fff; }

/* ── Bande stats ── */
.ap-stats-strip {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,.08);
    padding: 18px 24px;
    margin: 28px 0 0;
    display: flex;
    gap: 0;
    position: relative;
    z-index: 2;
}
.ap-stat {
    flex: 1;
    text-align: center;
    padding: 0 12px;
    border-right: 1px solid #f3f4f6;
}
.ap-stat:last-child { border-right: none; }
.ap-stat-value {
    font-family: 'Montserrat', sans-serif;
    font-size: 1.4rem; font-weight: 800;
    color: #1a1d23; line-height: 1;
}
.ap-stat-label { font-size: .7rem; color: #9ca3af; margin-top: 4px; text-transform: uppercase; letter-spacing: .5px; }

/* ── Tabs ── */
.ap-tabs {
    border-bottom: 2px solid #e5e7eb;
    display: flex; gap: 0;
    background: #fff;
    border-radius: 12px 12px 0 0;
    overflow-x: auto;
}
.ap-tab {
    padding: 14px 20px;
    font-size: .82rem; font-weight: 600;
    color: #6b7280; cursor: pointer;
    border: none; background: none;
    border-bottom: 3px solid transparent;
    margin-bottom: -2px;
    white-space: nowrap;
    transition: all .18s;
}
.ap-tab:hover { color: #008751; }
.ap-tab.active { color: #008751; border-bottom-color: #008751; }

/* ── Contenu sections ── */
.ap-section { background: #fff; border-radius: 0 0 16px 16px; padding: 28px; }
.ap-section-title {
    font-family: 'Montserrat', sans-serif;
    font-size: 1rem; font-weight: 700;
    color: #1a1d23; margin: 0 0 16px;
    display: flex; align-items: center; gap: 8px;
}
.ap-section-title::before {
    content: '';
    width: 4px; height: 18px;
    background: linear-gradient(#008751, #FCD116);
    border-radius: 2px;
    flex-shrink: 0;
}

/* Bio */
.ap-bio { font-size: .9rem; color: #4b5563; line-height: 1.75; }

/* Spécialités */
.ap-spec-tag {
    display: inline-block;
    background: rgba(0,135,81,.1); color: #008751;
    border-radius: 20px; padding: 4px 12px;
    font-size: .75rem; font-weight: 600;
    margin: 3px 4px 3px 0;
}
.ap-lang-tag {
    display: inline-block;
    background: #f3f4f6; color: #4b5563;
    border-radius: 20px; padding: 4px 12px;
    font-size: .75rem; font-weight: 500;
    margin: 3px 4px 3px 0;
}

/* Info grid */
.ap-info-grid {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 14px; margin-top: 20px;
}
.ap-info-item {
    background: #f9fafb; border-radius: 12px;
    padding: 14px 16px;
}
.ap-info-icon { font-size: 1.1rem; color: #008751; margin-bottom: 6px; }
.ap-info-value { font-weight: 700; font-size: .9rem; color: #1a1d23; }
.ap-info-label { font-size: .7rem; color: #9ca3af; margin-top: 2px; }

/* ── Portfolio / Galerie ── */
.portfolio-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
}
@media(max-width:576px) { .portfolio-grid { grid-template-columns: repeat(2, 1fr); } }

.portfolio-item {
    position: relative; border-radius: 12px; overflow: hidden;
    cursor: pointer; aspect-ratio: 1;
    background: #f3f4f6;
}
.portfolio-item img {
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform .4s ease;
}
.portfolio-item:hover img { transform: scale(1.07); }
.portfolio-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,.6) 0%, transparent 60%);
    opacity: 0; transition: opacity .3s;
    display: flex; align-items: flex-end;
    padding: 12px;
}
.portfolio-item:hover .portfolio-overlay { opacity: 1; }
.portfolio-overlay span { font-size: .75rem; color: #fff; font-weight: 600; }

/* ── Lightbox ── */
.lbx-backdrop {
    display: none;
    position: fixed; inset: 0;
    background: rgba(0,0,0,.92);
    z-index: 2000;
    align-items: center; justify-content: center;
}
.lbx-backdrop.open { display: flex; }
.lbx-img { max-width: 90vw; max-height: 85vh; border-radius: 12px; object-fit: contain; }
.lbx-close {
    position: fixed; top: 20px; right: 20px;
    background: rgba(255,255,255,.15); color: #fff;
    border: none; border-radius: 50%;
    width: 40px; height: 40px;
    font-size: 1.2rem; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
}
.lbx-close:hover { background: rgba(255,255,255,.25); }
.lbx-nav {
    position: fixed; top: 50%; transform: translateY(-50%);
    background: rgba(255,255,255,.15); color: #fff;
    border: none; border-radius: 50%;
    width: 44px; height: 44px;
    font-size: 1.2rem; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: background .2s;
}
.lbx-nav:hover { background: rgba(255,255,255,.3); }
.lbx-prev { left: 16px; }
.lbx-next { right: 16px; }
.lbx-caption {
    position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%);
    color: rgba(255,255,255,.8); font-size: .8rem; text-align: center;
}

/* ── Avis ── */
.rating-global {
    display: flex; align-items: center; gap: 24px;
    padding: 20px; background: #f9fafb; border-radius: 14px;
    margin-bottom: 24px;
}
.rating-score {
    text-align: center; flex-shrink: 0;
}
.rating-score .big { font-size: 3rem; font-weight: 800; color: #1a1d23; line-height: 1; }
.rating-score .stars { color: #f59e0b; font-size: 1rem; }
.rating-score .count { font-size: .72rem; color: #9ca3af; }
.rating-bars { flex: 1; }
.rating-bar-row {
    display: flex; align-items: center; gap: 8px; margin-bottom: 5px;
}
.rating-bar-row .label { font-size: .72rem; color: #6b7280; width: 30px; flex-shrink: 0; }
.rating-bar-track {
    flex: 1; height: 7px; background: #e5e7eb; border-radius: 4px; overflow: hidden;
}
.rating-bar-fill { height: 100%; background: #f59e0b; border-radius: 4px; transition: width .5s; }
.rating-bar-row .pct { font-size: .7rem; color: #9ca3af; width: 28px; text-align: right; flex-shrink: 0; }

.review-card {
    border-radius: 12px; border: 1px solid #f3f4f6;
    padding: 16px 18px; margin-bottom: 14px;
    background: #fff; transition: box-shadow .15s;
}
.review-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.06); }
.review-avatar {
    width: 40px; height: 40px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: .85rem; font-weight: 700; color: #fff;
    flex-shrink: 0;
}
.review-name { font-weight: 700; font-size: .88rem; color: #1a1d23; }
.review-stars { color: #f59e0b; font-size: .8rem; letter-spacing: -1px; }
.review-date { font-size: .7rem; color: #9ca3af; }
.review-text { font-size: .85rem; color: #4b5563; margin-top: 8px; line-height: 1.6; }

/* Formulaire avis */
.stars-input { display: flex; gap: 6px; flex-direction: row-reverse; justify-content: flex-end; }
.stars-input input { display: none; }
.stars-input label { font-size: 1.8rem; color: #d1d5db; cursor: pointer; transition: color .15s; }
.stars-input input:checked ~ label,
.stars-input label:hover,
.stars-input label:hover ~ label { color: #f59e0b; }

/* ── Produits ── */
.ap-product-card {
    border-radius: 14px; overflow: hidden;
    border: 1px solid #f3f4f6;
    transition: all .2s;
    background: #fff;
}
.ap-product-card:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(0,0,0,.08); }
.ap-product-card img { width: 100%; height: 180px; object-fit: cover; }
.ap-product-body { padding: 14px; }
.ap-product-name { font-weight: 700; font-size: .88rem; color: #1a1d23; margin-bottom: 6px; }
.ap-product-price { font-size: 1rem; font-weight: 800; color: #008751; }

/* ── Carte mini ── */
#ap-mini-map { width: 100%; height: 340px; border-radius: 14px; }

/* ── Sidebar contact ── */
.ap-contact-card {
    background: #fff; border-radius: 16px;
    box-shadow: 0 8px 30px rgba(0,0,0,.09);
    padding: 24px;
    position: sticky; top: 80px;
}
.ap-contact-title {
    font-family: 'Montserrat', sans-serif;
    font-size: .95rem; font-weight: 800; color: #1a1d23; margin-bottom: 16px;
    display: flex; align-items: center; gap: 8px;
}
.ap-contact-btn {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    width: 100%; padding: 13px;
    border-radius: 12px; border: none;
    font-weight: 700; font-size: .88rem;
    cursor: pointer; text-decoration: none;
    transition: all .2s; margin-bottom: 10px;
}
.cb-wa  { background: #25d366; color: #fff; }
.cb-wa:hover  { background: #1db954; color: #fff; }
.cb-tel { background: #f0fdf4; color: #008751; border: 1.5px solid #bbf7d0; }
.cb-tel:hover { background: #dcfce7; color: #008751; }
.cb-devis { background: linear-gradient(135deg, #E8112D, #b50d24); color: #fff; }
.cb-devis:hover { opacity: .9; color: #fff; }
.cb-share { background: #f9fafb; color: #6b7280; border: 1.5px solid #e5e7eb; }
.cb-share:hover { background: #f3f4f6; color: #4b5563; }

.ap-pricing-box {
    background: #f9fafb; border-radius: 12px; padding: 14px;
    margin-top: 16px;
}
.ap-pricing-box .label { font-size: .72rem; color: #9ca3af; text-transform: uppercase; letter-spacing: .5px; font-weight: 600; margin-bottom: 6px; }
.ap-pricing-box .value { font-size: .85rem; color: #4b5563; }

/* Artisans similaires */
.ap-similar-card {
    display: flex; align-items: center; gap: 12px;
    padding: 12px 0; border-bottom: 1px solid #f3f4f6;
}
.ap-similar-card:last-child { border-bottom: none; }
.ap-similar-avatar {
    width: 48px; height: 48px; border-radius: 12px;
    object-fit: cover; flex-shrink: 0;
}
.ap-similar-fallback {
    width: 48px; height: 48px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; color: #fff; font-size: .9rem; flex-shrink: 0;
}
.ap-similar-name { font-weight: 700; font-size: .83rem; color: #1a1d23; text-decoration: none; }
.ap-similar-name:hover { color: #008751; }
.ap-similar-craft { font-size: .72rem; color: #9ca3af; }

/* ── Mobile sticky bar ── */
.ap-mobile-bar {
    display: none;
    position: fixed; bottom: 0; left: 0; right: 0;
    background: #fff; border-top: 1px solid #e5e7eb;
    padding: 10px 16px 14px;
    z-index: 500;
    gap: 8px;
}
@media(max-width:991px) {
    .ap-mobile-bar { display: flex; }
    .ap-contact-card { display: none; }
    body { padding-bottom: 80px; }
}
.ap-mobile-bar-btn {
    flex: 1; padding: 11px 8px;
    border-radius: 10px; border: none;
    font-weight: 700; font-size: .8rem;
    display: flex; align-items: center; justify-content: center; gap: 5px;
    cursor: pointer; text-decoration: none;
}

/* ── Page wrapper ── */
.ap-page { background: #f0f4f8; min-height: 100vh; }
.ap-content { padding: 32px 0 60px; }
</style>
@endpush

@section('content')
@php
    $name     = $artisan->business_name ?? $artisan->user->name;
    $initial  = strtoupper(substr($name, 0, 1));
    $photo    = $artisan->photos->first();
    $photoUrl = $photo ? (file_exists(public_path($photo->photo_url)) ? asset($photo->photo_url) : null) : null;
    $colors   = ['#008751','#3b82f6','#8b5cf6','#f59e0b','#E8112D','#0ea5e9'];
    $avatarBg = $colors[$artisan->id % count($colors)];
    $starsFull= round($artisan->rating_avg ?? 0);
    $photos   = $artisan->photos;

    // Répartition des notes
    $reviewsByRating = $artisan->reviews->groupBy('rating');
    $totalReviews    = $artisan->reviews->count();
@endphp

<div class="ap-page">

    {{-- ── Hero ──────────────────────────────────────────── --}}
    <div class="ap-hero">
        <div class="container">
            <div class="row align-items-end g-4 pb-10" style="padding-bottom:50px;">

                {{-- Gauche: avatar + infos --}}
                <div class="col-lg-7">
                    <div class="d-flex align-items-start gap-4 mb-4">
                        {{-- Avatar --}}
                        <div class="ap-avatar-wrap">
                            @if($photoUrl)
                                <img src="{{ $photoUrl }}" class="ap-avatar" alt="{{ $name }}">
                            @else
                                <div class="ap-avatar-fallback" style="background: #fff; color: {{ $avatarBg }};">{{ $initial }}</div>
                            @endif
                            @if($artisan->verified)
                                <div class="ap-verified-badge" title="Artisan vérifié">
                                    <i class="bi bi-patch-check-fill"></i>
                                </div>
                            @endif
                        </div>

                        <div style="min-width:0;">
                            <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                                <span class="ap-craft-pill">
                                    <i class="bi bi-tools"></i>
                                    {{ ucfirst($artisan->craft ?? '') }}
                                </span>
                                @if($artisan->verified)
                                    <span class="ap-craft-pill" style="background:rgba(252,209,22,.2);color:#FCD116;border-color:rgba(252,209,22,.3);">
                                        <i class="bi bi-patch-check-fill"></i> Vérifié
                                    </span>
                                @endif
                            </div>

                            <h1 class="ap-name">{{ $name }}</h1>

                            <div class="d-flex flex-wrap gap-3 mt-3">
                                @if($artisan->city)
                                <div class="ap-meta-item">
                                    <i class="bi bi-geo-alt-fill" style="color:#E8112D;"></i>
                                    {{ $artisan->city }}{{ $artisan->neighborhood ? ' · '.$artisan->neighborhood : '' }}
                                </div>
                                @endif
                                @if($artisan->rating_avg > 0)
                                <div class="ap-meta-item">
                                    <span class="ap-stars">
                                        @for($i=1;$i<=5;$i++){{ $i<=$starsFull?'★':'☆' }}@endfor
                                    </span>
                                    <span style="color:#FCD116;font-weight:700;">{{ number_format($artisan->rating_avg,1) }}</span>
                                    <span>({{ $artisan->rating_count }} avis)</span>
                                </div>
                                @endif
                                @if($artisan->years_experience)
                                <div class="ap-meta-item">
                                    <i class="bi bi-calendar-check"></i> {{ $artisan->years_experience }}+ ans d'expérience
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex flex-wrap gap-2">
                        @if($artisan->whatsapp)
                            <a href="https://wa.me/{{ preg_replace('/\D/','',$artisan->whatsapp) }}" target="_blank" class="ap-action-btn ap-btn-wa">
                                <i class="bi bi-whatsapp"></i> WhatsApp
                            </a>
                        @endif
                        @if($artisan->phone)
                            <a href="tel:{{ $artisan->phone }}" class="ap-action-btn ap-btn-call">
                                <i class="bi bi-telephone-fill"></i> Appeler
                            </a>
                        @endif
                        <button class="ap-action-btn ap-btn-devis" data-bs-toggle="modal" data-bs-target="#quoteModal">
                            <i class="bi bi-chat-left-text-fill"></i> Demander un devis
                        </button>
                        <button class="ap-action-btn ap-btn-share" onclick="shareArtisan()">
                            <i class="bi bi-share-fill"></i> Partager
                        </button>
                    </div>
                </div>

                {{-- Droite: ministat vues --}}
                <div class="col-lg-5 text-lg-end">
                    <div class="ap-meta-item justify-content-lg-end">
                        <i class="bi bi-eye" style="color:#FCD116;"></i>
                        <span>{{ number_format($artisan->views) }} vues</span>
                        @if($artisan->created_at)
                            &nbsp;·&nbsp; Membre depuis {{ $artisan->created_at->translatedFormat('M Y') }}
                        @endif
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('map') }}" class="ap-action-btn ap-btn-call d-inline-flex" style="font-size:.75rem;padding:7px 14px;">
                            <i class="bi bi-map"></i> Voir sur la carte
                        </a>
                    </div>
                </div>

            </div>

            {{-- Stats strip --}}
            <div class="ap-stats-strip">
                <div class="ap-stat">
                    <div class="ap-stat-value" style="color:#008751;">{{ $artisan->rating_avg > 0 ? number_format($artisan->rating_avg,1) : '–' }}</div>
                    <div class="ap-stat-label">Note moyenne</div>
                </div>
                <div class="ap-stat">
                    <div class="ap-stat-value">{{ $artisan->rating_count }}</div>
                    <div class="ap-stat-label">Avis clients</div>
                </div>
                <div class="ap-stat">
                    <div class="ap-stat-value">{{ $artisan->photos->count() }}</div>
                    <div class="ap-stat-label">Photos</div>
                </div>
                <div class="ap-stat">
                    <div class="ap-stat-value">{{ $artisan->products->count() }}</div>
                    <div class="ap-stat-label">Produits</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Contenu principal ──────────────────────────────── --}}
    <div class="ap-content">
        <div class="container">
            <div class="row g-4">

                {{-- Colonne principale --}}
                <div class="col-lg-8">

                    {{-- Tabs --}}
                    <div class="ap-tabs" id="apTabList" role="tablist">
                        <button class="ap-tab active" data-tab="about">
                            <i class="bi bi-person-lines-fill me-1"></i> À propos
                        </button>
                        <button class="ap-tab" data-tab="portfolio">
                            <i class="bi bi-images me-1"></i> Portfolio ({{ $photos->count() }})
                        </button>
                        <button class="ap-tab" data-tab="reviews">
                            <i class="bi bi-star-half me-1"></i> Avis ({{ $totalReviews }})
                        </button>
                        @if($artisan->products->count() > 0)
                        <button class="ap-tab" data-tab="products">
                            <i class="bi bi-basket me-1"></i> Produits ({{ $artisan->products->count() }})
                        </button>
                        @endif
                        <button class="ap-tab" data-tab="location">
                            <i class="bi bi-geo-alt-fill me-1"></i> Localisation
                        </button>
                    </div>

                    {{-- ── À propos ── --}}
                    <div class="ap-section" id="tab-about">
                        @if($artisan->bio)
                            <div class="ap-section-title">Biographie</div>
                            <p class="ap-bio">{{ $artisan->bio }}</p>
                        @endif

                        <div class="row g-3 mt-1">
                            @if($artisan->specialties)
                            @php
                                $specs = is_array($artisan->specialties)
                                    ? $artisan->specialties
                                    : (json_decode($artisan->specialties, true) ?: [$artisan->specialties]);
                            @endphp
                            <div class="col-md-6">
                                <div class="ap-section-title">Spécialités</div>
                                @foreach($specs as $spec)
                                    <span class="ap-spec-tag">{{ $spec }}</span>
                                @endforeach
                            </div>
                            @endif

                            @if($artisan->languages_spoken)
                            @php
                                $langs = is_array($artisan->languages_spoken)
                                    ? $artisan->languages_spoken
                                    : (json_decode($artisan->languages_spoken, true) ?: []);
                            @endphp
                            @if(count($langs) > 0)
                            <div class="col-md-6">
                                <div class="ap-section-title">Langues parlées</div>
                                @foreach($langs as $lang)
                                    <span class="ap-lang-tag">{{ $lang }}</span>
                                @endforeach
                            </div>
                            @endif
                            @endif
                        </div>

                        <div class="ap-info-grid mt-4">
                            @if($artisan->city)
                            <div class="ap-info-item">
                                <div class="ap-info-icon"><i class="bi bi-geo-alt-fill"></i></div>
                                <div class="ap-info-value">{{ $artisan->city }}</div>
                                <div class="ap-info-label">Ville</div>
                            </div>
                            @endif
                            @if($artisan->years_experience)
                            <div class="ap-info-item">
                                <div class="ap-info-icon"><i class="bi bi-calendar-check"></i></div>
                                <div class="ap-info-value">{{ $artisan->years_experience }}+ ans</div>
                                <div class="ap-info-label">Expérience</div>
                            </div>
                            @endif
                            @if($artisan->phone)
                            <div class="ap-info-item">
                                <div class="ap-info-icon"><i class="bi bi-telephone-fill"></i></div>
                                <div class="ap-info-value">{{ $artisan->phone }}</div>
                                <div class="ap-info-label">Téléphone</div>
                            </div>
                            @endif
                            @if($artisan->pricing_info)
                            <div class="ap-info-item">
                                <div class="ap-info-icon"><i class="bi bi-cash-coin"></i></div>
                                <div class="ap-info-value" style="font-size:.8rem;">{{ Str::limit($artisan->pricing_info, 40) }}</div>
                                <div class="ap-info-label">Tarifs</div>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- ── Portfolio ── --}}
                    <div class="ap-section" id="tab-portfolio" style="display:none;">
                        @if($photos->count() > 0)
                            <div class="portfolio-grid">
                                @foreach($photos as $idx => $ph)
                                @php $pUrl = file_exists(public_path($ph->photo_url)) ? asset($ph->photo_url) : null; @endphp
                                @if($pUrl)
                                <div class="portfolio-item" onclick="openLightbox({{ $idx }})">
                                    <img src="{{ $pUrl }}" alt="{{ $ph->caption ?? 'Photo '.$idx }}" loading="lazy">
                                    <div class="portfolio-overlay">
                                        <span>{{ $ph->caption ?: 'Voir en grand' }}</span>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-images fs-1 text-muted d-block mb-3" style="opacity:.3;"></i>
                                <div class="fw-semibold text-muted mb-1">Aucune photo</div>
                                <div class="text-muted" style="font-size:.82rem;">L'artisan n'a pas encore ajouté de photos</div>
                            </div>
                        @endif
                    </div>

                    {{-- ── Avis ── --}}
                    <div class="ap-section" id="tab-reviews" style="display:none;">

                        {{-- Rating global --}}
                        @if($totalReviews > 0)
                        <div class="rating-global">
                            <div class="rating-score">
                                <div class="big">{{ number_format($artisan->rating_avg, 1) }}</div>
                                <div class="stars">
                                    @for($i=1;$i<=5;$i++){{ $i<=$starsFull?'★':'☆' }}@endfor
                                </div>
                                <div class="count">{{ $totalReviews }} avis</div>
                            </div>
                            <div class="rating-bars">
                                @for($star=5; $star>=1; $star--)
                                @php
                                    $cnt = isset($reviewsByRating[$star]) ? $reviewsByRating[$star]->count() : 0;
                                    $pct = $totalReviews > 0 ? round($cnt/$totalReviews*100) : 0;
                                @endphp
                                <div class="rating-bar-row">
                                    <span class="label">{{ $star }}★</span>
                                    <div class="rating-bar-track">
                                        <div class="rating-bar-fill" style="width:{{ $pct }}%;"></div>
                                    </div>
                                    <span class="pct">{{ $pct }}%</span>
                                </div>
                                @endfor
                            </div>
                        </div>
                        @endif

                        {{-- Liste des avis --}}
                        @forelse($artisan->reviews->where('status','approved') as $review)
                        @php
                            $rUser  = $review->user;
                            $rStars = $review->rating;
                            $rBg    = $colors[$rUser->id % count($colors)];
                            $rInit  = strtoupper(substr($rUser->name, 0, 1));
                        @endphp
                        <div class="review-card">
                            <div class="d-flex align-items-center gap-3 mb-1">
                                @if($rUser->avatar ?? false)
                                    <img src="{{ $rUser->avatar }}" class="review-avatar" alt="{{ $rUser->name }}" style="border-radius:50%;">
                                @else
                                    <div class="review-avatar" style="background:{{ $rBg }};">{{ $rInit }}</div>
                                @endif
                                <div>
                                    <div class="review-name">{{ $rUser->name }}</div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="review-stars">
                                            @for($i=1;$i<=5;$i++){{ $i<=$rStars?'★':'☆' }}@endfor
                                        </span>
                                        <span class="review-date">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                            @if($review->comment)
                                <p class="review-text mb-0">{{ $review->comment }}</p>
                            @endif
                        </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="bi bi-chat-square-text fs-1 d-block mb-3" style="color:#d1d5db;"></i>
                                <div class="fw-semibold text-muted mb-1">Aucun avis pour le moment</div>
                                <div class="text-muted" style="font-size:.82rem;">Soyez le premier à donner votre avis</div>
                            </div>
                        @endforelse

                        {{-- Formulaire avis --}}
                        @auth
                            @if(auth()->id() !== $artisan->user_id)
                            <div class="mt-4 p-4" style="background:#f9fafb;border-radius:14px;">
                                <div class="ap-section-title">Laisser un avis</div>
                                <form action="{{ route('reviews.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="reviewable_type" value="App\Models\Artisan">
                                    <input type="hidden" name="reviewable_id" value="{{ $artisan->id }}">
                                    <input type="hidden" name="anonymous" value="0">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold" style="font-size:.85rem;">Votre note</label>
                                        <div class="stars-input">
                                            @for($i=5;$i>=1;$i--)
                                            <input type="radio" id="sr{{ $i }}" name="rating" value="{{ $i }}" required>
                                            <label for="sr{{ $i }}">★</label>
                                            @endfor
                                        </div>
                                        @error('rating')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold" style="font-size:.85rem;">Votre commentaire</label>
                                        <textarea name="comment" rows="3" class="form-control" required
                                            placeholder="Partagez votre expérience avec cet artisan…"></textarea>
                                        @error('comment')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="form-check mb-3">
                                        <input type="checkbox" name="terms" id="terms" class="form-check-input" value="1" required>
                                        <label for="terms" class="form-check-label" style="font-size:.82rem;">
                                            J'accepte les conditions d'utilisation
                                        </label>
                                    </div>
                                    <button type="submit" class="btn btn-sm px-4 py-2"
                                        style="background:#008751;color:#fff;border-radius:10px;font-weight:700;">
                                        Publier mon avis
                                    </button>
                                </form>
                            </div>
                            @endif
                        @else
                            <div class="text-center mt-3 p-3" style="background:#f9fafb;border-radius:12px;">
                                <a href="{{ route('login') }}" class="text-decoration-none fw-semibold" style="color:#008751;font-size:.85rem;">
                                    <i class="bi bi-box-arrow-in-right me-1"></i>Connectez-vous pour laisser un avis
                                </a>
                            </div>
                        @endauth
                    </div>

                    {{-- ── Produits ── --}}
                    @if($artisan->products->count() > 0)
                    <div class="ap-section" id="tab-products" style="display:none;">
                        <div class="row g-3">
                            @foreach($artisan->products as $product)
                            <div class="col-md-6 col-6">
                                <div class="ap-product-card">
                                    @php $pImg = $product->images->first()?->full_url ?? null; @endphp
                                    @if($pImg)
                                        <img src="{{ $pImg }}" alt="{{ $product->name }}">
                                    @else
                                        <div style="height:180px;background:#f3f4f6;display:flex;align-items:center;justify-content:center;">
                                            <i class="bi bi-image fs-1" style="color:#d1d5db;"></i>
                                        </div>
                                    @endif
                                    <div class="ap-product-body">
                                        <div class="ap-product-name">{{ Str::limit($product->name, 40) }}</div>
                                        <div class="d-flex align-items-center justify-content-between mt-2">
                                            <span class="ap-product-price">{{ $product->formatted_price }}</span>
                                            <a href="{{ route('products.show', $product) }}"
                                               class="btn btn-sm px-3" style="background:#008751;color:#fff;border-radius:8px;font-size:.75rem;font-weight:700;">
                                                Voir
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- ── Localisation ── --}}
                    <div class="ap-section" id="tab-location" style="display:none;">
                        @if($artisan->latitude && $artisan->longitude)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="ap-section-title mb-0">Localisation</div>
                                <button onclick="getDirections()" class="btn btn-sm px-3 py-2"
                                    style="background:#f0fdf4;color:#008751;border:1.5px solid #bbf7d0;border-radius:10px;font-weight:700;font-size:.78rem;">
                                    <i class="bi bi-signpost-2 me-1"></i>Itinéraire
                                </button>
                            </div>
                            <p class="text-muted mb-3" style="font-size:.85rem;">
                                <i class="bi bi-geo-alt-fill me-1" style="color:#E8112D;"></i>
                                {{ $artisan->city }}{{ $artisan->neighborhood ? ' · '.$artisan->neighborhood : '' }}
                            </p>
                            <div id="ap-mini-map"></div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-geo-alt fs-1 d-block mb-3" style="color:#d1d5db;"></i>
                                <div class="fw-semibold text-muted mb-1">Localisation non disponible</div>
                                <div class="text-muted" style="font-size:.82rem;">Cet artisan n'a pas encore renseigné sa position</div>
                            </div>
                        @endif
                    </div>

                </div>{{-- fin col-lg-8 --}}

                {{-- Sidebar --}}
                <div class="col-lg-4">
                    <div class="ap-contact-card">
                        <div class="ap-contact-title">
                            <i class="bi bi-person-check-fill" style="color:#008751;"></i>
                            Contacter {{ Str::limit($name, 18) }}
                        </div>

                        @if($artisan->whatsapp)
                            <a href="https://wa.me/{{ preg_replace('/\D/','',$artisan->whatsapp) }}" target="_blank" class="ap-contact-btn cb-wa">
                                <i class="bi bi-whatsapp fs-5"></i> Message WhatsApp
                            </a>
                        @endif

                        @if($artisan->phone)
                            <a href="tel:{{ $artisan->phone }}" class="ap-contact-btn cb-tel">
                                <i class="bi bi-telephone-fill"></i> {{ $artisan->phone }}
                            </a>
                        @endif

                        <button class="ap-contact-btn cb-devis" data-bs-toggle="modal" data-bs-target="#quoteModal">
                            <i class="bi bi-chat-left-text-fill"></i> Demander un devis
                        </button>

                        <button class="ap-contact-btn cb-share" onclick="shareArtisan()">
                            <i class="bi bi-share-fill"></i> Partager le profil
                        </button>

                        @if($artisan->pricing_info)
                        <div class="ap-pricing-box">
                            <div class="label">Tarifs indicatifs</div>
                            <div class="value">{{ $artisan->pricing_info }}</div>
                        </div>
                        @endif

                        @if($artisan->rating_avg > 0)
                        <div class="text-center mt-3 pt-3" style="border-top:1px solid #f3f4f6;">
                            <div style="font-size:1.8rem;font-weight:800;color:#1a1d23;line-height:1;">
                                {{ number_format($artisan->rating_avg, 1) }}
                            </div>
                            <div style="color:#f59e0b;font-size:1rem;">
                                @for($i=1;$i<=5;$i++){{ $i<=$starsFull?'★':'☆' }}@endfor
                            </div>
                            <div style="font-size:.72rem;color:#9ca3af;">{{ $artisan->rating_count }} avis clients</div>
                        </div>
                        @endif
                    </div>

                    {{-- Artisans similaires --}}
                    @if($similarArtisans->count() > 0)
                    <div class="ap-contact-card mt-3">
                        <div class="ap-contact-title">
                            <i class="bi bi-people-fill" style="color:#008751;"></i>
                            Artisans similaires
                        </div>
                        @foreach($similarArtisans as $sim)
                        @php
                            $simName   = $sim->business_name ?? $sim->user->name ?? 'Artisan';
                            $simPhoto  = $sim->photos->first();
                            $simPhotoUrl = $simPhoto && file_exists(public_path($simPhoto->photo_url)) ? asset($simPhoto->photo_url) : null;
                            $simBg     = $colors[$sim->id % count($colors)];
                            $simInit   = strtoupper(substr($simName, 0, 1));
                        @endphp
                        <div class="ap-similar-card">
                            @if($simPhotoUrl)
                                <img src="{{ $simPhotoUrl }}" class="ap-similar-avatar" alt="{{ $simName }}">
                            @else
                                <div class="ap-similar-fallback" style="background:{{ $simBg }};">{{ $simInit }}</div>
                            @endif
                            <div style="min-width:0;">
                                <a href="{{ route('artisans.show', $sim) }}" class="ap-similar-name d-block">
                                    {{ Str::limit($simName, 22) }}
                                </a>
                                <div class="ap-similar-craft">{{ ucfirst($sim->craft ?? '') }} · {{ $sim->city }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    {{-- Mobile sticky bar --}}
    <div class="ap-mobile-bar">
        @if($artisan->whatsapp)
            <a href="https://wa.me/{{ preg_replace('/\D/','',$artisan->whatsapp) }}" target="_blank"
               class="ap-mobile-bar-btn" style="background:#25d366;color:#fff;">
                <i class="bi bi-whatsapp"></i> WhatsApp
            </a>
        @endif
        <button class="ap-mobile-bar-btn" style="background:#E8112D;color:#fff;"
                data-bs-toggle="modal" data-bs-target="#quoteModal">
            <i class="bi bi-chat-left-text-fill"></i> Devis
        </button>
        @if($artisan->phone)
            <a href="tel:{{ $artisan->phone }}" class="ap-mobile-bar-btn"
               style="background:#f0fdf4;color:#008751;border:1.5px solid #bbf7d0;">
                <i class="bi bi-telephone-fill"></i> Appel
            </a>
        @endif
    </div>

</div>

{{-- ── Modal Devis ── --}}
<div class="modal fade" id="quoteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Demande de devis</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('quotes.store') }}" method="POST">
                @csrf
                <input type="hidden" name="artisan_id" value="{{ $artisan->id }}">
                <div class="modal-body pt-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.85rem;">Description du projet *</label>
                        <textarea name="description" class="form-control" rows="4" required
                            placeholder="Décrivez précisément votre besoin…"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.85rem;">Budget estimé (FCFA)</label>
                        <input type="number" name="budget" class="form-control" placeholder="Optionnel">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn px-5" style="background:#008751;color:#fff;font-weight:700;border-radius:10px;">Envoyer</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Lightbox --}}
<div class="lbx-backdrop" id="lightbox" onclick="closeLightboxOnBackdrop(event)">
    <button class="lbx-close" onclick="closeLightbox()"><i class="bi bi-x-lg"></i></button>
    <button class="lbx-nav lbx-prev" onclick="lbxNav(-1)"><i class="bi bi-chevron-left"></i></button>
    <img class="lbx-img" id="lbx-img" src="" alt="">
    <button class="lbx-nav lbx-next" onclick="lbxNav(1)"><i class="bi bi-chevron-right"></i></button>
    <div class="lbx-caption" id="lbx-caption"></div>
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

<script>
/* ── Photos pour le lightbox ── */
const LBX_PHOTOS = @json($photos->map(fn($p) => [
    'url'     => file_exists(public_path($p->photo_url)) ? asset($p->photo_url) : null,
    'caption' => $p->caption ?? '',
])->filter(fn($p) => $p['url'])->values());

let lbxIdx = 0;

function openLightbox(idx) {
    lbxIdx = idx;
    renderLbx();
    document.getElementById('lightbox').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    document.getElementById('lightbox').classList.remove('open');
    document.body.style.overflow = '';
}
function closeLightboxOnBackdrop(e) {
    if (e.target.id === 'lightbox') closeLightbox();
}
function lbxNav(dir) {
    lbxIdx = (lbxIdx + dir + LBX_PHOTOS.length) % LBX_PHOTOS.length;
    renderLbx();
}
function renderLbx() {
    const p = LBX_PHOTOS[lbxIdx];
    if (!p) return;
    document.getElementById('lbx-img').src = p.url;
    document.getElementById('lbx-caption').textContent = p.caption
        ? p.caption + ' (' + (lbxIdx+1) + '/' + LBX_PHOTOS.length + ')'
        : (lbxIdx+1) + ' / ' + LBX_PHOTOS.length;
}
document.addEventListener('keydown', function(e) {
    const lb = document.getElementById('lightbox');
    if (!lb.classList.contains('open')) return;
    if (e.key === 'ArrowLeft')  lbxNav(-1);
    if (e.key === 'ArrowRight') lbxNav(1);
    if (e.key === 'Escape')     closeLightbox();
});

/* ── Tabs ── */
document.querySelectorAll('.ap-tab').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.ap-tab').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const tab = this.dataset.tab;
        document.querySelectorAll('[id^="tab-"]').forEach(s => s.style.display = 'none');
        document.getElementById('tab-' + tab).style.display = '';

        if (tab === 'location') {
            setTimeout(initMiniMap, 150);
        }
    });
});

/* ── Mini-carte ── */
let miniMapInst = null;

function initMiniMap() {
    const lat = {{ $artisan->latitude ?? 'null' }};
    const lng = {{ $artisan->longitude ?? 'null' }};
    if (!lat || !lng) return;
    if (miniMapInst) { miniMapInst.invalidateSize(); return; }

    miniMapInst = L.map('ap-mini-map').setView([lat, lng], 15);

    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap &copy; CARTO',
        subdomains: 'abcd', maxZoom: 19,
    }).addTo(miniMapInst);

    const icon = L.divIcon({
        html: `<svg width="36" height="42" viewBox="0 0 36 42" xmlns="http://www.w3.org/2000/svg">
            <path d="M18 0C8.06 0 0 8.06 0 18c0 13.5 18 24 18 24s18-10.5 18-24C36 8.06 27.94 0 18 0z" fill="#008751"/>
            <circle cx="18" cy="17" r="9" fill="white"/>
            <text x="18" y="21" font-family="Arial" font-weight="900" font-size="9" text-anchor="middle" fill="#008751">
                {{ strtoupper(substr($artisan->craft ?? 'A', 0, 3)) }}
            </text>
        </svg>`,
        className: '',
        iconSize: [36, 42], iconAnchor: [18, 42], popupAnchor: [0, -44],
    });

    L.marker([lat, lng], { icon })
        .addTo(miniMapInst)
        .bindPopup(`<strong>{{ addslashes($name) }}</strong><br>
            <span style="color:#008751;font-size:.78rem;">{{ ucfirst($artisan->craft ?? '') }}</span><br>
            <span style="color:#666;font-size:.75rem;">{{ $artisan->city }}</span>`)
        .openPopup();
}

/* ── Partager ── */
function shareArtisan() {
    if (navigator.share) {
        navigator.share({
            title: '{{ addslashes($name) }} — TOTCHÉMÈGNON',
            text: 'Découvrez cet artisan béninois sur TOTCHÉMÈGNON',
            url: window.location.href,
        });
    } else {
        navigator.clipboard.writeText(window.location.href).then(function() {
            const t = document.createElement('div');
            t.className = 'toast align-items-center text-bg-success border-0 position-fixed bottom-0 end-0 m-3';
            t.setAttribute('role','alert');
            t.innerHTML = '<div class="d-flex"><div class="toast-body"><i class="bi bi-check-circle me-2"></i>Lien copié !</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>';
            document.body.appendChild(t);
            new bootstrap.Toast(t, { delay: 2000 }).show();
            setTimeout(() => t.remove(), 3000);
        });
    }
}

/* ── Itinéraire ── */
function getDirections() {
    const lat = {{ $artisan->latitude ?? 'null' }};
    const lng = {{ $artisan->longitude ?? 'null' }};
    if (!lat || !lng) return;
    const dest = lat + ',' + lng;
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(pos) {
                const origin = pos.coords.latitude + ',' + pos.coords.longitude;
                window.open('https://www.google.com/maps/dir/' + origin + '/' + dest, '_blank');
            },
            function() { window.open('https://www.google.com/maps/dir//' + dest, '_blank'); }
        );
    } else {
        window.open('https://www.google.com/maps?q=' + dest, '_blank');
    }
}
</script>
@endpush
