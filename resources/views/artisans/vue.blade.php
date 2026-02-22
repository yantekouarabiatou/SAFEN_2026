@extends('layouts.app')

@section('title', __('Liste des artisans'))

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
/* ── Variables couleurs béninoises ─────────────────────── */
:root {
    --green:       #008751;
    --green-d:     #006B40;
    --green-l:     #E6F4EE;
    --yellow:      #FFC107;
    --yellow-d:    #E6A800;
    --yellow-l:    #FFF8E1;
    --red:         #D32F2F;
    --red-d:       #B71C1C;
    --red-l:       #FDECEA;

    --dark:        #1A1A1A;
    --text:        #333333;
    --muted:       #777777;
    --border:      #E5E5E5;
    --bg:          #F8F9FA;
    --white:       #FFFFFF;

    --radius-sm:   6px;
    --radius-md:   12px;
    --radius-lg:   18px;
    --shadow:      0 2px 12px rgba(0,0,0,.07), 0 1px 3px rgba(0,0,0,.04);
    --shadow-hover:0 8px 28px rgba(0,135,81,.18), 0 2px 8px rgba(0,0,0,.08);
    --transition:  0.2s cubic-bezier(.4,0,.2,1);

    --font-display: 'Playfair Display', Georgia, serif;
    --font-body:    'DM Sans', system-ui, sans-serif;
}

body { background: var(--bg); font-family: var(--font-body); color: var(--text); }

/* ── Breadcrumb ────────────────────────────────────────── */
.breadcrumb-wrap {
    padding: .65rem 0;
    font-size: .8rem;
    color: var(--muted);
}
.breadcrumb-wrap a { color: var(--muted); text-decoration: none; }
.breadcrumb-wrap a:hover { color: var(--green); }
.breadcrumb-wrap .sep { margin: 0 .4rem; }

/* ── Header ────────────────────────────────────────────── */
.page-header {
    background: var(--green);
    padding: 2.5rem 0 2rem;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}
/* Bande tricolore béninoise en bas */
.page-header::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 5px;
    background: linear-gradient(90deg, var(--green) 33%, var(--yellow) 33% 66%, var(--red) 66%);
}
.page-header::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse at 80% 50%, rgba(255,193,7,.08) 0%, transparent 60%);
}
.page-header h1 {
    font-family: var(--font-display);
    font-size: clamp(1.7rem, 4vw, 2.5rem);
    font-weight: 700;
    color: #fff;
    margin: 0 0 .4rem;
    position: relative;
}
.page-header p { color: rgba(255,255,255,.75); margin: 0; font-size: .9rem; position: relative; }
.btn-become {
    background: var(--yellow);
    color: var(--dark);
    border: none;
    padding: .65rem 1.4rem;
    border-radius: var(--radius-md);
    font-weight: 700;
    font-size: .88rem;
    text-decoration: none;
    display: inline-flex; align-items: center; gap: .5rem;
    transition: background var(--transition), transform var(--transition);
    position: relative;
    white-space: nowrap;
}
.btn-become:hover { background: var(--yellow-d); color: var(--dark); transform: translateY(-2px); }

/* ── Filter panel ──────────────────────────────────────── */
.filter-panel {
    background: var(--white);
    border: 1px solid var(--border);
    border-top: 3px solid var(--green);
    border-radius: var(--radius-lg);
    padding: 1.4rem;
    box-shadow: var(--shadow);
    position: sticky;
    top: 1rem;
}
.filter-title {
    font-family: var(--font-display);
    font-size: 1rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0 0 1.1rem;
    padding-bottom: .75rem;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; gap: .5rem;
}
.filter-title i { color: var(--green); }

.form-label-custom {
    display: block;
    font-size: .72rem;
    font-weight: 600;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: .05em;
    margin-bottom: .35rem;
}
.form-control-custom,
.form-select-custom {
    width: 100%;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    padding: .5rem .75rem;
    font-size: .875rem;
    font-family: var(--font-body);
    color: var(--text);
    background: var(--bg);
    transition: border-color var(--transition), box-shadow var(--transition);
    appearance: none; -webkit-appearance: none;
    box-sizing: border-box;
}
.form-control-custom:focus,
.form-select-custom:focus {
    outline: none;
    border-color: var(--green);
    box-shadow: 0 0 0 3px rgba(0,135,81,.12);
    background: var(--white);
}

.search-wrap { position: relative; }
.search-wrap .si {
    position: absolute; left: .7rem; top: 50%;
    transform: translateY(-50%);
    color: var(--muted); font-size: .85rem; pointer-events: none;
}
.search-wrap .form-control-custom { padding-left: 2rem; }

.select-wrap { position: relative; }
.select-wrap::after {
    content: '▾';
    position: absolute; right: .75rem; top: 50%;
    transform: translateY(-50%);
    color: var(--muted); pointer-events: none; font-size: .8rem;
}
.select-wrap .form-select-custom { padding-right: 2rem; }

.radius-label {
    font-size: .8rem; color: var(--green); font-weight: 700;
    margin-top: .3rem;
    display: flex; align-items: center; gap: .3rem;
}
.form-range { accent-color: var(--green); width: 100%; }

.btn-filter {
    width: 100%;
    background: var(--green); color: #fff;
    border: none;
    padding: .65rem 1rem;
    border-radius: var(--radius-sm);
    font-weight: 600; font-size: .875rem;
    font-family: var(--font-body);
    cursor: pointer;
    display: inline-flex; align-items: center; justify-content: center; gap: .5rem;
    transition: background var(--transition), transform var(--transition);
    margin-top: .25rem;
}
.btn-filter:hover { background: var(--green-d); transform: translateY(-1px); }

.btn-reset-filter {
    width: 100%; display: block;
    background: transparent; color: var(--muted);
    border: 1.5px solid var(--border);
    padding: .5rem 1rem;
    border-radius: var(--radius-sm);
    font-size: .8rem; font-family: var(--font-body);
    cursor: pointer;
    margin-top: .5rem;
    text-align: center; text-decoration: none;
    transition: all var(--transition);
}
.btn-reset-filter:hover { border-color: var(--red); color: var(--red); }

/* ── Results bar ───────────────────────────────────────── */
.results-bar {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: .75rem;
    margin-bottom: 1.25rem;
    padding: .85rem 1.1rem;
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    box-shadow: var(--shadow);
}
.results-count { font-size: .875rem; color: var(--muted); margin: 0; }
.results-count strong { color: var(--dark); font-weight: 700; font-size: 1rem; }
.badge-approved {
    display: inline-block;
    background: var(--green-l); color: var(--green);
    font-size: .7rem; font-weight: 700;
    padding: .15rem .55rem; border-radius: 100px;
    margin-left: .4rem; vertical-align: middle;
}

.view-toggle { display: flex; gap: .25rem; }
.view-toggle button {
    width: 34px; height: 34px;
    border: 1.5px solid var(--border);
    background: var(--white);
    border-radius: var(--radius-sm);
    color: var(--muted); cursor: pointer;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: .8rem;
    transition: all var(--transition);
}
.view-toggle button.active,
.view-toggle button:hover {
    border-color: var(--green); color: var(--green); background: var(--green-l);
}

/* ── Cards grid ────────────────────────────────────────── */
.artisans-grid {
    display: grid;
    gap: 1.15rem;
    grid-template-columns: repeat(auto-fill, minmax(265px, 1fr));
}
.artisans-grid.list-view { grid-template-columns: 1fr; }

/* ── Artisan card ──────────────────────────────────────── */
.artisan-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: box-shadow var(--transition), transform var(--transition), border-color var(--transition);
    display: flex; flex-direction: column;
}
.artisan-card:hover {
    box-shadow: var(--shadow-hover);
    transform: translateY(-3px);
    border-color: rgba(0,135,81,.25);
}

/* Zone photo */
.card-photo {
    position: relative;
    height: 180px;
    overflow: hidden;
    background: var(--green-l);
    flex-shrink: 0;
}
.card-photo img {
    width: 100%; height: 100%;
    object-fit: cover;
    display: block;
    transition: transform .4s ease;
}
.artisan-card:hover .card-photo img { transform: scale(1.05); }

.photo-fallback {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    font-family: var(--font-display);
    font-size: 4rem; font-weight: 700;
    color: var(--green); opacity: .25;
}

/* Badges sur la photo */
.badge-craft {
    position: absolute; top: .7rem; left: .7rem;
    background: var(--green); color: #fff;
    font-size: .67rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .05em;
    padding: .2rem .6rem; border-radius: 100px;
    display: flex; align-items: center; gap: .25rem;
}
.badge-distance {
    position: absolute; top: .7rem; right: .7rem;
    background: rgba(0,0,0,.58); backdrop-filter: blur(6px);
    color: #fff;
    font-size: .68rem; font-weight: 600;
    padding: .2rem .55rem; border-radius: 100px;
    display: flex; align-items: center; gap: .25rem;
}
.badge-verified {
    position: absolute; bottom: .6rem; right: .6rem;
    background: var(--green); color: #fff;
    font-size: .67rem; font-weight: 700;
    padding: .2rem .6rem; border-radius: 100px;
    display: flex; align-items: center; gap: .25rem;
}

/* Corps de la carte */
.card-body {
    padding: 1rem 1.1rem;
    flex: 1; display: flex; flex-direction: column; gap: .5rem;
}
.artisan-name {
    font-family: var(--font-display);
    font-size: 1.05rem; font-weight: 700;
    color: var(--dark); margin: 0;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.artisan-location {
    font-size: .8rem; color: var(--muted);
    display: flex; align-items: center; gap: .3rem; margin: 0;
}
.artisan-location i { color: var(--green); font-size: .75rem; }

.stars-wrap { display: flex; align-items: center; gap: .4rem; }
.stars { display: flex; gap: 1px; }
.star { font-size: .8rem; color: #DDD; }
.star.on { color: var(--yellow); }
.rating-count { font-size: .75rem; color: var(--muted); }

.artisan-bio {
    font-size: .83rem; color: var(--muted); line-height: 1.55;
    flex: 1; margin: 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.exp-tag {
    display: inline-flex; align-items: center; gap: .3rem;
    font-size: .72rem; font-weight: 600;
    color: var(--yellow-d); background: var(--yellow-l);
    padding: .2rem .6rem; border-radius: 100px; width: fit-content;
}

/* Pied de carte */
.card-footer-actions {
    padding: .85rem 1.1rem;
    border-top: 1px solid var(--border);
    display: flex; gap: .5rem;
}
.btn-wa {
    width: 38px; height: 38px; flex-shrink: 0;
    background: #25D366; color: #fff;
    border-radius: var(--radius-sm);
    display: inline-flex; align-items: center; justify-content: center;
    text-decoration: none; font-size: .95rem;
    transition: background var(--transition), transform var(--transition);
}
.btn-wa:hover { background: #1EB558; color: #fff; transform: scale(1.05); }

.btn-profile {
    flex: 1; height: 38px;
    background: var(--green); color: #fff;
    border-radius: var(--radius-sm);
    display: inline-flex; align-items: center; justify-content: center; gap: .4rem;
    text-decoration: none; font-size: .84rem; font-weight: 600;
    transition: background var(--transition), transform var(--transition);
}
.btn-profile:hover { background: var(--green-d); color: #fff; transform: translateY(-1px); }

/* ── LIST VIEW ─────────────────────────────────────────── */
.list-view .artisan-card { flex-direction: row; }
.list-view .card-photo { width: 160px; height: auto; border-radius: 0; flex-shrink: 0; }
.list-view .card-footer-actions {
    flex-direction: column; justify-content: center;
    width: 130px; flex-shrink: 0;
    border-top: none; border-left: 1px solid var(--border);
}
@media (max-width: 600px) {
    .list-view .artisan-card { flex-direction: column; }
    .list-view .card-photo { width: 100%; height: 160px; }
    .list-view .card-footer-actions { width: 100%; flex-direction: row; border-left: none; border-top: 1px solid var(--border); }
}

/* ── Empty state ───────────────────────────────────────── */
.empty-state {
    text-align: center; padding: 4rem 2rem;
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    grid-column: 1 / -1;
}
.empty-icon { font-size: 3.5rem; margin-bottom: 1rem; }
.empty-state h3 { font-family: var(--font-display); font-size: 1.3rem; color: var(--dark); margin-bottom: .5rem; }
.empty-state p { color: var(--muted); font-size: .88rem; margin-bottom: 1.25rem; }
.btn-reset-empty {
    display: inline-flex; align-items: center; gap: .4rem;
    background: var(--green); color: #fff;
    padding: .6rem 1.5rem; border-radius: var(--radius-sm);
    text-decoration: none; font-weight: 600; font-size: .875rem;
    transition: background var(--transition);
}
.btn-reset-empty:hover { background: var(--green-d); color: #fff; }

/* ── PAGINATION ────────────────────────────────────────── */
.pagination-wrap {
    margin-top: 2.5rem;
    display: flex; flex-direction: column; align-items: center; gap: .6rem;
    padding-bottom: 2.5rem;
}

/* Reset et override total Bootstrap 5 */
.pagination-wrap nav ul.pagination,
nav[aria-label] ul.pagination {
    display: flex !important;
    flex-wrap: wrap !important;
    gap: .25rem !important;
    justify-content: center !important;
    align-items: center !important;
    padding: 0 !important;
    margin: 0 !important;
    list-style: none !important;
}
.pagination-wrap nav ul.pagination li,
nav[aria-label] ul.pagination li {
    display: inline-flex !important;
}
.pagination-wrap nav ul.pagination li a,
.pagination-wrap nav ul.pagination li span,
nav[aria-label] ul.pagination li a,
nav[aria-label] ul.pagination li span {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    min-width: 38px !important;
    height: 38px !important;
    padding: 0 .7rem !important;
    border-radius: var(--radius-sm) !important;
    border: 1.5px solid var(--border) !important;
    background: var(--white) !important;
    color: var(--text) !important;
    font-size: .84rem !important;
    font-weight: 500 !important;
    font-family: var(--font-body) !important;
    text-decoration: none !important;
    transition: all var(--transition) !important;
    line-height: 1 !important;
    cursor: pointer !important;
}
.pagination-wrap nav ul.pagination li a:hover,
nav[aria-label] ul.pagination li a:hover {
    border-color: var(--green) !important;
    color: var(--green) !important;
    background: var(--green-l) !important;
}
/* Page active */
.pagination-wrap nav ul.pagination li.active span,
.pagination-wrap nav ul.pagination li.active a,
nav[aria-label] ul.pagination li.active span,
nav[aria-label] ul.pagination li.active a {
    background: var(--green) !important;
    border-color: var(--green) !important;
    color: #fff !important;
    font-weight: 700 !important;
    box-shadow: 0 2px 8px rgba(0,135,81,.3) !important;
}
/* Désactivé */
.pagination-wrap nav ul.pagination li.disabled span,
.pagination-wrap nav ul.pagination li.disabled a,
nav[aria-label] ul.pagination li.disabled span,
nav[aria-label] ul.pagination li.disabled a {
    opacity: .4 !important;
    pointer-events: none !important;
    background: var(--bg) !important;
    cursor: default !important;
}

.pagination-info {
    font-size: .78rem; color: var(--muted); text-align: center;
}
.pagination-info strong { color: var(--green); }

/* ── Carte Leaflet ─────────────────────────────────────── */
#map {
    height: 420px;
    border-radius: var(--radius-lg);
    overflow: hidden;
    display: none;
    margin-bottom: 1.25rem;
    border: 1px solid var(--border);
    box-shadow: var(--shadow);
}
</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
<div class="container">
    <nav class="breadcrumb-wrap">
        <a href="{{ route('home') }}"><i class="bi bi-house-fill me-1"></i> {{ __('messages.home') }}</a>
        <span class="sep">/</span>
        <span>{{ __('Liste des Artisans') }}</span>
    </nav>
</div>

{{-- Header --}}
<div class="page-header">
    <div class="container d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <h1>{{ __('artisans.title') }}</h1>
            <p>{{ __('Liste des artisans') }}</p>
        </div>
        <a href="{{ route('admin.artisans.create') }}" class="btn-become">
            <i class="bi bi-hammer me-1"></i>
            {{ __('artisans.become_artisan') ?? 'Devenir artisan' }}
        </a>
    </div>
</div>

<div class="container">
    <div class="row g-4">

        {{-- ── Sidebar filtres ──────────────────────────────── --}}
        <div class="col-lg-3">
            <form method="GET" action="{{ route('artisans.index') }}">

                @if(request('latitude') && request('longitude'))
                    <input type="hidden" name="latitude"  value="{{ request('latitude') }}">
                    <input type="hidden" name="longitude" value="{{ request('longitude') }}">
                @endif

                <div class="filter-panel">
                    <p class="filter-title">
                        <i class="bi bi-sliders me-1"></i> {{ __('messages.filter') }}
                    </p>

                    {{-- Recherche --}}
                    <div class="mb-3">
                        <label class="form-label-custom">{{ __('messages.search') }}</label>
                        <div class="search-wrap">
                            <i class="bi bi-search si"></i>
                            <input type="text" name="search"
                                   class="form-control-custom"
                                   value="{{ request('search') }}"
                                   placeholder="Nom, métier…">
                        </div>
                    </div>

                    {{-- Métier --}}
                    <div class="mb-3">
                        <label class="form-label-custom">{{ __('artisans.filter_by_craft') }}</label>
                        <div class="select-wrap">
                            <select name="craft" class="form-select-custom">
                                <option value="">{{ __('artisans.all_crafts') }}</option>
                                @foreach(__('artisans.crafts') as $key => $craft)
                                    <option value="{{ $key }}" {{ request('craft') == $key ? 'selected' : '' }}>
                                        {{ $craft }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Ville --}}
                    <div class="mb-3">
                        <label class="form-label-custom">{{ __('artisans.filter_by_city') }}</label>
                        <div class="select-wrap">
                            <select name="city" class="form-select-custom">
                                <option value="">{{ __('artisans.all_cities') }}</option>
                                @foreach(__('artisans.cities') as $key => $city)
                                    <option value="{{ $key }}" {{ request('city') == $key ? 'selected' : '' }}>
                                        {{ $city }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Rayon (géolocalisation) --}}
                    @if(request('latitude') && request('longitude'))
                        <div class="mb-3">
                            <label class="form-label-custom">{{ __('artisans.radius') ?? 'Rayon (km)' }}</label>
                            <input type="range" name="radius" class="form-range"
                                   min="5" max="200" step="5"
                                   value="{{ request('radius', 50) }}"
                                   oninput="document.getElementById('rv').textContent=this.value">
                            <p class="radius-label">
                                <i class="bi bi-geo-alt-fill me-1"></i>
                                <span id="rv">{{ request('radius', 50) }}</span> km
                            </p>
                        </div>
                    @endif

                    <button type="submit" class="btn-filter">
                        <i class="bi bi-search me-1"></i> {{ __('messages.filter') }}
                    </button>
                    <a href="{{ route('artisans.index') }}" class="btn-reset-filter">
                        <i class="bi bi-x-lg me-1"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>

        {{-- ── Contenu principal ────────────────────────────── --}}
        <div class="col-lg-9">

            {{-- Barre résultats --}}
            <div class="results-bar">
                <p class="results-count">
                    <strong>{{ $artisans->total() }}</strong>
                    {{ __('artisans.found_artisans') ?? 'artisans trouvés' }}
                    <span class="badge-approved">{{ __('artisans.approved') ?? 'Vérifiés' }}</span>
                </p>
                <div class="view-toggle">
                    <button id="btn-grid" class="active" title="{{ __('artisans.view_grid') }}">
                        <i class="bi bi-grid-3x3-gap-fill"></i>
                    </button>
                    <button id="btn-list" title="{{ __('artisans.view_list') }}">
                        <i class="bi bi-list-ul"></i>
                    </button>
                    <button id="btn-map" title="{{ __('artisans.view_map') }}">
                        <i class="bi bi-map-fill"></i>
                    </button>
                </div>
            </div>

            {{-- Carte Leaflet (masquée par défaut) --}}
            <div id="map"></div>

            {{-- Grille des artisans --}}
            <div class="artisans-grid" id="artisans-grid">

                @forelse($artisans as $artisan)
                    @php
                        /*
                         * Récupération de la première photo via la relation photos (ArtisanPhoto).
                         * On utilise l'accessor getFullUrlAttribute() → $photo->full_url
                         * qui retourne asset('storage/' . $this->photo_url)
                         */
                        $firstPhoto = $artisan->photos->first();
                        $photoUrl   = $firstPhoto ? $firstPhoto->full_url : null;
                        $initial    = strtoupper(substr($artisan->user->name ?? 'A', 0, 1));
                    @endphp

                    <div class="artisan-card"
                         data-lat="{{ $artisan->latitude ?? '' }}"
                         data-lng="{{ $artisan->longitude ?? '' }}"
                         data-name="{{ e($artisan->user->name ?? '') }}">

                        {{-- ── Zone photo ───────────────────────── --}}
                        <div class="card-photo">
                            @if($photoUrl)
                                <img
                                    src="{{ $photoUrl }}"
                                    alt="Photo de {{ $artisan->user->name }}"
                                    loading="lazy"
                                    onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                {{-- Fallback si l'image ne charge pas --}}
                                <div class="photo-fallback" style="display:none;">{{ $initial }}</div>
                            @else
                                <div class="photo-fallback">{{ $initial }}</div>
                            @endif

                            {{-- Badge métier --}}
                            <span class="badge-craft">
                                <i class="bi bi-tools me-1"></i>
                                {{ $artisan->craft_label }}
                            </span>

                            {{-- Distance --}}
                            @if(request('latitude') && request('longitude') && isset($artisan->distance))
                                <span class="badge-distance">
                                    <i class="bi bi-cursor-fill me-1"></i>
                                    {{ round($artisan->distance, 1) }} km
                                </span>
                            @endif

                            {{-- Vérifié --}}
                            @if($artisan->verified)
                                <span class="badge-verified">
                                    <i class="bi bi-patch-check-fill me-1"></i>
                                    {{ __('artisans.verified') }}
                                </span>
                            @endif
                        </div>

                        {{-- ── Corps ────────────────────────────── --}}
                        <div class="card-body">
                            <h3 class="artisan-name">{{ $artisan->user->name }}</h3>

                            <p class="artisan-location">
                                <i class="bi bi-geo-alt-fill me-1"></i>
                                {{ $artisan->city }}
                                @if($artisan->neighborhood)&bull; {{ $artisan->neighborhood }}@endif
                            </p>

                            <div class="stars-wrap">
                                <div class="stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star-fill star {{ $i <= round($artisan->rating ?? 0) ? 'on' : '' }}"></i>
                                    @endfor
                                </div>
                                <span class="rating-count">({{ $artisan->rating_count ?? 0 }})</span>
                            </div>

                            <p class="artisan-bio">
                                {{ $artisan->bio ?? 'Artisan passionné et expérimenté.' }}
                            </p>

                            @if($artisan->years_experience)
                                <span class="exp-tag">
                                    <i class="bi bi-clock-fill me-1"></i>
                                    {{ $artisan->years_experience }}+ ans d'expérience
                                </span>
                            @endif
                        </div>

                        {{-- ── Actions ──────────────────────────── --}}
                        <div class="card-footer-actions">
                            @if($artisan->whatsapp)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $artisan->whatsapp) }}"
                                   class="btn-wa" target="_blank" rel="noopener" title="Contacter sur WhatsApp">
                                    <i class="bi bi-whatsapp me-1"></i>
                                </a>
                            @endif
                            <a href="{{ route('artisans.show', $artisan->id) }}" class="btn-profile">
                                <i class="bi bi-person-fill me-1"></i>
                                {{ __('views.view_profile') }}
                            </a>
                        </div>
                    </div>

                @empty
                    <div class="empty-state">
                        <div class="empty-icon">🔨</div>
                        <h3>{{ __('views.no_artisans_found') }}</h3>
                        <p>{{ __('views.modify_search') }}</p>
                        <a href="{{ route('artisans.index') }}" class="btn-reset-empty">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Réinitialiser les filtres
                        </a>
                    </div>
                @endforelse

            </div>{{-- /artisans-grid --}}

            {{-- ── Pagination ────────────────────────────────── --}}
            @if($artisans->hasPages())
                <div class="pagination-wrap">
                    {{ $artisans->withQueryString()->links('pagination::bootstrap-5') }}
                    <p class="pagination-info">
                        Page <strong>{{ $artisans->currentPage() }}</strong>
                        sur <strong>{{ $artisans->lastPage() }}</strong>
                        &mdash; <strong>{{ $artisans->total() }}</strong> artisans au total
                    </p>
                </div>
            @endif

        </div>{{-- /col-lg-9 --}}
    </div>{{-- /row --}}
</div>{{-- /container --}}

@endsection

@push('scripts')
<script>
/* ── View toggle ──────────────────────────────────────── */
const grid  = document.getElementById('artisans-grid');
const mapEl = document.getElementById('map');
const btnG  = document.getElementById('btn-grid');
const btnL  = document.getElementById('btn-list');
const btnM  = document.getElementById('btn-map');

function setActive(btn) {
    [btnG, btnL, btnM].forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}

btnG.addEventListener('click', () => {
    setActive(btnG);
    grid.classList.remove('list-view');
    grid.style.display = 'grid';
    mapEl.style.display = 'none';
});

btnL.addEventListener('click', () => {
    setActive(btnL);
    grid.classList.add('list-view');
    grid.style.display = 'grid';
    mapEl.style.display = 'none';
});

btnM.addEventListener('click', () => {
    setActive(btnM);
    grid.style.display = 'none';
    mapEl.style.display = 'block';
    initMap();
});

/* ── Carte Leaflet (chargement paresseux) ─────────────── */
let mapInstance = null;
function initMap() {
    if (mapInstance) return;

    const css  = document.createElement('link');
    css.rel    = 'stylesheet';
    css.href   = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
    document.head.appendChild(css);

    const js   = document.createElement('script');
    js.src     = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
    js.onload  = () => {
        mapInstance = L.map('map').setView([9.3077, 2.3158], 7); // Centre Bénin
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(mapInstance);

        const icon = L.divIcon({
            className: '',
            html: '<div style="width:13px;height:13px;background:#008751;border:2.5px solid #fff;border-radius:50%;box-shadow:0 2px 6px rgba(0,0,0,.3)"></div>',
            iconSize: [13,13], iconAnchor: [6,6],
        });

        document.querySelectorAll('.artisan-card[data-lat]').forEach(card => {
            const lat = parseFloat(card.dataset.lat);
            const lng = parseFloat(card.dataset.lng);
            if (lat && lng) {
                L.marker([lat, lng], { icon })
                 .addTo(mapInstance)
                 .bindPopup(`<strong style="font-family:serif;font-size:.95rem">${card.dataset.name}</strong>`);
            }
        });
    };
    document.head.appendChild(js);
}
</script>
@endpush