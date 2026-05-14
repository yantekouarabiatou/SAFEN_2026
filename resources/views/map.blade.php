@extends('layouts.app')

@section('title', 'Carte des artisans — TOTCHÉMÈGNON')

@push('styles')
{{-- Leaflet CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" crossorigin=""/>
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" crossorigin=""/>
<style>
/* ══════════════════════════════════════════════
   PAGE CARTE — TOTCHÉMÈGNON
══════════════════════════════════════════════ */
.map-page { background: #f0f4f8; min-height: 100vh; }

/* Hero compact */
.map-hero {
    background: linear-gradient(135deg, #005c38 0%, #008751 60%, #00a862 100%);
    padding: 28px 0 20px;
    color: #fff;
}
.map-hero h1 {
    font-family: 'Montserrat', sans-serif;
    font-size: 1.6rem;
    font-weight: 800;
    margin: 0 0 4px;
}
.map-hero p { font-size: .88rem; opacity: .82; margin: 0; }
.map-counter {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.25);
    border-radius: 20px;
    padding: 5px 14px;
    font-size: .78rem;
    font-weight: 600;
}

/* Barre filtres */
.map-filters {
    background: #fff;
    border-bottom: 1px solid #e5e7eb;
    padding: 12px 0;
    position: sticky;
    top: 70px;
    z-index: 900;
    box-shadow: 0 2px 8px rgba(0,0,0,.06);
}
.filter-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 14px;
    border-radius: 20px;
    border: 1.5px solid #e5e7eb;
    background: #fff;
    font-size: .8rem;
    font-weight: 500;
    color: #4b5563;
    cursor: pointer;
    transition: all .18s;
    white-space: nowrap;
}
.filter-btn:hover, .filter-btn.active {
    border-color: #008751;
    background: #008751;
    color: #fff;
}
.filter-select {
    padding: 7px 14px;
    border-radius: 20px;
    border: 1.5px solid #e5e7eb;
    background: #fff;
    font-size: .8rem;
    color: #4b5563;
    cursor: pointer;
    outline: none;
    transition: border-color .18s;
}
.filter-select:focus { border-color: #008751; }
.filter-search {
    position: relative;
    max-width: 240px;
}
.filter-search input {
    padding: 7px 14px 7px 36px;
    border-radius: 20px;
    border: 1.5px solid #e5e7eb;
    font-size: .8rem;
    width: 100%;
    outline: none;
    transition: border-color .18s;
}
.filter-search input:focus { border-color: #008751; }
.filter-search i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: .85rem;
}

/* Layout principal */
.map-layout {
    display: flex;
    height: calc(100vh - 220px);
    min-height: 520px;
}
@media(max-width:991px) {
    .map-layout { flex-direction: column; height: auto; }
    .map-container { height: 55vw; min-height: 320px; order: -1; }
    .map-sidebar { width: 100%; max-height: 380px; }
}

/* Sidebar liste */
.map-sidebar {
    width: 340px;
    flex-shrink: 0;
    background: #fff;
    border-right: 1px solid #e5e7eb;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #d1d5db #f9fafb;
}
.map-sidebar::-webkit-scrollbar { width: 5px; }
.map-sidebar::-webkit-scrollbar-track { background: #f9fafb; }
.map-sidebar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 3px; }

.sidebar-header {
    padding: 14px 16px 10px;
    border-bottom: 1px solid #f3f4f6;
    position: sticky;
    top: 0;
    background: #fff;
    z-index: 10;
}
.sidebar-count {
    font-size: .75rem;
    color: #9ca3af;
    font-weight: 500;
}

/* Carte artisan dans sidebar */
.artisan-card-sm {
    display: flex;
    gap: 12px;
    padding: 12px 14px;
    border-bottom: 1px solid #f3f4f6;
    cursor: pointer;
    transition: background .15s;
}
.artisan-card-sm:hover { background: #f9fafb; }
.artisan-card-sm.highlighted { background: #f0fdf4; border-left: 3px solid #008751; }

.artisan-avatar-map {
    width: 52px; height: 52px;
    border-radius: 12px;
    object-fit: cover;
    flex-shrink: 0;
    border: 2px solid #e5e7eb;
}
.artisan-avatar-fallback-map {
    width: 52px; height: 52px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; font-weight: 700; color: #fff;
    flex-shrink: 0;
}
.artisan-name-sm {
    font-weight: 700; font-size: .87rem; color: #1a1d23;
    display: flex; align-items: center; gap: 5px;
}
.artisan-craft-sm {
    font-size: .75rem; color: #6b7280;
    text-transform: capitalize; margin-top: 2px;
}
.artisan-city-sm {
    font-size: .72rem; color: #9ca3af;
    display: flex; align-items: center; gap: 3px; margin-top: 4px;
}
.artisan-stars-sm { color: #f59e0b; font-size: .7rem; letter-spacing: -1px; }

/* Carte Leaflet */
.map-container { position: relative; flex: 1; min-height: 0; }
#artisan-map { width: 100%; height: 100%; min-height: 500px; }

/* Popup Leaflet custom */
.lf-popup {
    font-family: 'Open Sans', sans-serif;
    min-width: 220px;
}
.lf-popup-header {
    display: flex; align-items: center; gap: 10px;
    margin-bottom: 10px;
}
.lf-popup-avatar {
    width: 44px; height: 44px;
    border-radius: 10px; object-fit: cover;
    border: 2px solid #e5e7eb; flex-shrink: 0;
}
.lf-popup-name {
    font-weight: 700; font-size: .88rem; color: #1a1d23;
    line-height: 1.2;
}
.lf-popup-craft {
    font-size: .73rem; color: #6b7280;
    text-transform: capitalize; margin-top: 2px;
}
.lf-popup-meta {
    font-size: .73rem; color: #6b7280;
    display: flex; align-items: center; gap: 4px; margin-bottom: 8px;
}
.lf-popup-stars { color: #f59e0b; font-size: .75rem; }
.lf-popup-actions {
    display: flex; gap: 6px; margin-top: 10px;
}
.lf-btn {
    flex: 1; padding: 7px; border-radius: 8px;
    font-size: .75rem; font-weight: 600;
    text-align: center; text-decoration: none;
    display: inline-flex; align-items: center; justify-content: center; gap: 4px;
    transition: opacity .15s;
}
.lf-btn-primary { background: #008751; color: #fff; }
.lf-btn-primary:hover { opacity: .88; color: #fff; }
.lf-btn-wa { background: #25d366; color: #fff; }
.lf-btn-wa:hover { opacity: .88; color: #fff; }
.verified-badge {
    display: inline-flex; align-items: center; gap: 3px;
    background: rgba(0,135,81,.1); color: #008751;
    border-radius: 10px; padding: 2px 7px; font-size: .67rem; font-weight: 700;
}

/* Marqueur custom SVG */
.marker-craft-icon { filter: drop-shadow(0 3px 6px rgba(0,0,0,.25)); }

/* Bouton localisation */
.btn-locate-me {
    position: absolute;
    bottom: 20px; right: 12px;
    z-index: 1000;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 10px 14px;
    font-size: .8rem;
    font-weight: 600;
    color: #008751;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0,0,0,.12);
    display: flex; align-items: center; gap: 6px;
    transition: all .2s;
}
.btn-locate-me:hover { background: #008751; color: #fff; }

/* Pastille compteur map */
.map-count-badge {
    position: absolute;
    top: 12px; left: 12px;
    z-index: 1000;
    background: #fff;
    border-radius: 10px;
    padding: 8px 12px;
    font-size: .78rem;
    font-weight: 600;
    color: #1a1d23;
    box-shadow: 0 4px 12px rgba(0,0,0,.12);
    display: flex; align-items: center; gap: 6px;
}
.map-count-badge .dot { width:8px;height:8px;background:#008751;border-radius:50%;animation:pulse-dot 1.8s infinite; }
@keyframes pulse-dot { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.4;transform:scale(.7)} }

/* Empty state */
.map-empty {
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    padding: 40px 20px; text-align: center; color: #9ca3af;
}
.map-empty i { font-size: 2.5rem; margin-bottom: 12px; opacity: .4; }
</style>
@endpush

@section('content')
<div class="map-page">

    {{-- ── Hero ──────────────────────────────────────────── --}}
    <div class="map-hero">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div>
                    <h1><i class="bi bi-geo-alt-fill me-2" style="color:#FCD116;"></i>Carte des artisans</h1>
                    <p>Découvrez les talents du Bénin, géolocalisés sur la carte</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <span class="map-counter">
                        <span class="fw-bold" id="hero-count">{{ $artisans->count() }}</span> artisans sur la carte
                    </span>
                    <a href="{{ route('artisans.vue') }}" class="btn btn-sm"
                       style="background:rgba(255,255,255,.15);color:#fff;border:1px solid rgba(255,255,255,.3);border-radius:20px;font-size:.78rem;">
                        <i class="bi bi-list-ul me-1"></i>Vue liste
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Filtres ──────────────────────────────────────── --}}
    <div class="map-filters">
        <div class="container">
            <div class="d-flex align-items-center gap-2 flex-wrap">

                {{-- Recherche --}}
                <div class="filter-search">
                    <i class="bi bi-search"></i>
                    <input type="text" id="search-input" placeholder="Nom, métier, ville…">
                </div>

                {{-- Filtre métier --}}
                <select class="filter-select" id="craft-filter">
                    <option value="">Tous les métiers</option>
                    @foreach($crafts as $craft)
                    <option value="{{ $craft }}">{{ ucfirst($craft) }}</option>
                    @endforeach
                </select>

                {{-- Filtre ville --}}
                <select class="filter-select" id="city-filter">
                    <option value="">Toutes les villes</option>
                    @foreach($cities as $city)
                    <option value="{{ $city }}">{{ $city }}</option>
                    @endforeach
                </select>

                {{-- Filtre vérifiés --}}
                <button class="filter-btn" id="filter-verified" data-active="0">
                    <i class="bi bi-patch-check-fill" style="color:#FCD116;"></i>
                    Vérifiés
                </button>

                {{-- Reset --}}
                <button class="filter-btn" id="filter-reset" style="border-color:#e5e7eb;color:#9ca3af;">
                    <i class="bi bi-x-circle"></i>Réinitialiser
                </button>

                <div class="ms-auto text-muted" style="font-size:.75rem;">
                    <span id="filter-count">{{ $artisans->count() }}</span> résultat(s)
                </div>
            </div>
        </div>
    </div>

    {{-- ── Layout carte + sidebar ────────────────────────── --}}
    <div class="map-layout">

        {{-- Sidebar liste --}}
        <div class="map-sidebar">
            <div class="sidebar-header">
                <div class="fw-semibold" style="font-size:.85rem;color:#1a1d23;">Artisans trouvés</div>
                <div class="sidebar-count" id="sidebar-count">{{ $artisans->count() }} résultats</div>
            </div>
            <div id="artisan-list">
                @foreach($artisans as $artisan)
                @php
                    $photo   = $artisan->photos->first()?->photo_url;
                    $colors  = ['#008751','#3b82f6','#8b5cf6','#f59e0b','#E8112D','#0ea5e9'];
                    $bg      = $colors[$artisan->id % count($colors)];
                    $initial = strtoupper(substr($artisan->business_name ?? $artisan->user->name ?? 'A', 0, 1));
                    $stars   = round($artisan->rating_avg ?? 0);
                @endphp
                <div class="artisan-card-sm" data-id="{{ $artisan->id }}"
                     data-lat="{{ $artisan->latitude }}" data-lng="{{ $artisan->longitude }}"
                     data-craft="{{ strtolower($artisan->craft) }}"
                     data-city="{{ $artisan->city }}"
                     data-verified="{{ $artisan->verified ? '1' : '0' }}"
                     data-name="{{ strtolower($artisan->business_name ?? $artisan->user->name) }}">
                    @if($photo && file_exists(public_path($photo)))
                        <img src="{{ asset($photo) }}" class="artisan-avatar-map" alt="{{ $artisan->business_name }}">
                    @else
                        <div class="artisan-avatar-fallback-map" style="background:{{ $bg }};">{{ $initial }}</div>
                    @endif
                    <div style="min-width:0;">
                        <div class="artisan-name-sm">
                            {{ $artisan->business_name ?? $artisan->user->name }}
                            @if($artisan->verified)
                                <i class="bi bi-patch-check-fill" style="color:#008751;font-size:.8rem;" title="Vérifié"></i>
                            @endif
                        </div>
                        <div class="artisan-craft-sm">{{ ucfirst($artisan->craft) }}</div>
                        <div class="artisan-city-sm">
                            <i class="bi bi-geo-alt" style="font-size:.65rem;"></i>{{ $artisan->city }}
                        </div>
                        @if($artisan->rating_avg > 0)
                        <div class="artisan-stars-sm mt-1">
                            @for($i=1;$i<=5;$i++){{ $i<=$stars ? '★' : '☆' }}@endfor
                            <span style="color:#9ca3af;font-size:.67rem;margin-left:3px;">{{ number_format($artisan->rating_avg,1) }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach

                <div class="map-empty" id="list-empty" style="display:none;">
                    <i class="bi bi-search"></i>
                    <div class="fw-semibold mb-1">Aucun artisan trouvé</div>
                    <div style="font-size:.8rem;">Modifiez vos filtres</div>
                </div>
            </div>
        </div>

        {{-- Carte Leaflet --}}
        <div class="map-container">
            <div id="artisan-map"></div>

            {{-- Badge compteur --}}
            <div class="map-count-badge">
                <span class="dot"></span>
                <span id="map-count">{{ $artisans->count() }}</span> sur la carte
            </div>

            {{-- Bouton Ma position --}}
            <button class="btn-locate-me" id="btn-locate">
                <i class="bi bi-crosshair2"></i> Ma position
            </button>
        </div>

    </div>
</div>
@endsection

@push('scripts')
{{-- Leaflet JS --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js" crossorigin=""></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── Données artisans depuis PHP ── */
    const ARTISANS = {!! $artisansJson !!};

    /* Couleurs par métier */
    const CRAFT_COLORS = {
        sculpteur:  '#008751',
        couturier:  '#3b82f6',
        tisserand:  '#8b5cf6',
        bijoutier:  '#f59e0b',
        potier:     '#E8112D',
        forgeron:   '#6b7280',
        musicien:   '#0ea5e9',
    };
    function craftColor(craft) {
        return CRAFT_COLORS[craft?.toLowerCase()] || '#008751';
    }

    /* ── Initialiser la carte centrée sur le Bénin ── */
    const map = L.map('artisan-map', {
        center: [9.3077, 2.3158],
        zoom: 7,
        zoomControl: true,
    });

    /* Fond de carte sobre */
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org">OpenStreetMap</a> &copy; <a href="https://carto.com">CARTO</a>',
        subdomains: 'abcd',
        maxZoom: 19,
    }).addTo(map);

    /* ── Cluster de marqueurs ── */
    const cluster = L.markerClusterGroup({
        showCoverageOnHover: false,
        maxClusterRadius: 50,
        iconCreateFunction: function (c) {
            const count = c.getChildCount();
            return L.divIcon({
                html: `<div style="background:#008751;color:#fff;width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.8rem;border:2px solid #FCD116;box-shadow:0 3px 10px rgba(0,135,81,.4);">${count}</div>`,
                className: '',
                iconSize: [36, 36],
            });
        },
    });

    /* ── Créer icône SVG par métier ── */
    function makeIcon(craft, verified) {
        const color = craftColor(craft);
        const ring  = verified ? '#FCD116' : '#fff';
        return L.divIcon({
            className: 'marker-craft-icon',
            html: `<svg width="36" height="42" viewBox="0 0 36 42" xmlns="http://www.w3.org/2000/svg">
                <path d="M18 0 C8.059 0 0 8.059 0 18 C0 31.5 18 42 18 42 C18 42 36 31.5 36 18 C36 8.059 27.941 0 18 0Z" fill="${color}"/>
                <circle cx="18" cy="17" r="10" fill="${ring}" opacity=".25"/>
                <circle cx="18" cy="17" r="7" fill="white"/>
                <text x="18" y="21" font-family="Arial" font-weight="900" font-size="9" text-anchor="middle" fill="${color}">${craft?.substring(0,3).toUpperCase() || '•'}</text>
            </svg>`,
            iconSize: [36, 42],
            iconAnchor: [18, 42],
            popupAnchor: [0, -42],
        });
    }

    /* ── Étoiles texte ── */
    function starsHtml(rating) {
        let s = '';
        for (let i = 1; i <= 5; i++) s += i <= Math.round(rating) ? '★' : '☆';
        return `<span style="color:#f59e0b;font-size:.8rem;">${s}</span>`;
    }

    /* ── Contenu popup ── */
    function popupContent(a) {
        const avatar = a.photo
            ? `<img src="${a.photo}" class="lf-popup-avatar" alt="${a.name}">`
            : `<div class="lf-popup-avatar" style="background:${craftColor(a.craft)};display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1.1rem;">${a.name.charAt(0).toUpperCase()}</div>`;
        const verified = a.verified
            ? `<span class="verified-badge"><i class="bi bi-patch-check-fill"></i>Vérifié</span>`
            : '';
        const wa = a.whatsapp
            ? `<a href="https://wa.me/${a.whatsapp}" target="_blank" class="lf-btn lf-btn-wa"><i class="bi bi-whatsapp"></i>WhatsApp</a>`
            : '';
        const rating = a.rating > 0
            ? `${starsHtml(a.rating)} <span style="color:#9ca3af;font-size:.72rem;">(${a.reviews} avis)</span>`
            : '<span style="color:#9ca3af;font-size:.72rem;">Pas encore d\'avis</span>';

        return `<div class="lf-popup">
            <div class="lf-popup-header">
                ${avatar}
                <div>
                    <div class="lf-popup-name">${a.name} ${verified}</div>
                    <div class="lf-popup-craft">${a.craft ? a.craft.charAt(0).toUpperCase() + a.craft.slice(1) : ''}</div>
                </div>
            </div>
            <div class="lf-popup-meta"><i class="bi bi-geo-alt" style="color:#E8112D;"></i>${a.city || ''}</div>
            <div class="lf-popup-meta">${rating}</div>
            ${a.bio ? `<div style="font-size:.73rem;color:#6b7280;margin-bottom:8px;line-height:1.4;">${a.bio}</div>` : ''}
            <div class="lf-popup-actions">
                <a href="${a.url}" class="lf-btn lf-btn-primary"><i class="bi bi-person-circle"></i>Voir profil</a>
                ${wa}
            </div>
        </div>`;
    }

    /* ── Ajouter les marqueurs ── */
    const markers = {};   // id → marker Leaflet

    ARTISANS.forEach(function (a) {
        if (!a.lat || !a.lng) return;
        const marker = L.marker([a.lat, a.lng], { icon: makeIcon(a.craft, a.verified) })
            .bindPopup(popupContent(a), { maxWidth: 260, className: 'lf-popup-wrap' });
        markers[a.id] = marker;
        cluster.addLayer(marker);
    });

    map.addLayer(cluster);

    /* Force Leaflet à recalculer la taille du conteneur */
    setTimeout(function () { map.invalidateSize(); }, 100);
    window.addEventListener('resize', function () { map.invalidateSize(); });

    /* ── Clic sur carte artisan sidebar → ouvre popup ── */
    document.querySelectorAll('.artisan-card-sm').forEach(function (card) {
        card.addEventListener('click', function () {
            const id  = parseInt(this.dataset.id);
            const lat = parseFloat(this.dataset.lat);
            const lng = parseFloat(this.dataset.lng);
            document.querySelectorAll('.artisan-card-sm').forEach(c => c.classList.remove('highlighted'));
            this.classList.add('highlighted');
            map.setView([lat, lng], 14, { animate: true });
            if (markers[id]) {
                cluster.zoomToShowLayer(markers[id], function () {
                    markers[id].openPopup();
                });
            }
        });
    });

    /* ── Filtres ── */
    let visibleArtisans = [...ARTISANS];

    function applyFilters() {
        const search   = document.getElementById('search-input').value.toLowerCase().trim();
        const craft    = document.getElementById('craft-filter').value.toLowerCase();
        const city     = document.getElementById('city-filter').value;
        const verified = document.getElementById('filter-verified').dataset.active === '1';

        visibleArtisans = ARTISANS.filter(function (a) {
            if (craft    && a.craft?.toLowerCase() !== craft)   return false;
            if (city     && a.city !== city)                    return false;
            if (verified && !a.verified)                        return false;
            if (search   && !a.name.toLowerCase().includes(search)
                         && !a.craft?.toLowerCase().includes(search)
                         && !a.city?.toLowerCase().includes(search))  return false;
            return true;
        });

        /* Mettre à jour marqueurs */
        cluster.clearLayers();
        const ids = new Set(visibleArtisans.map(a => a.id));
        ARTISANS.forEach(function (a) {
            if (ids.has(a.id) && markers[a.id]) cluster.addLayer(markers[a.id]);
        });

        /* Mettre à jour sidebar */
        document.querySelectorAll('.artisan-card-sm').forEach(function (card) {
            const id = parseInt(card.dataset.id);
            card.style.display = ids.has(id) ? '' : 'none';
        });

        const count = visibleArtisans.length;
        document.getElementById('filter-count').textContent = count;
        document.getElementById('sidebar-count').textContent = count + ' résultat(s)';
        document.getElementById('map-count').textContent = count;
        document.getElementById('hero-count').textContent = count;
        document.getElementById('list-empty').style.display = count === 0 ? 'flex' : 'none';
    }

    document.getElementById('search-input').addEventListener('input', applyFilters);
    document.getElementById('craft-filter').addEventListener('change', applyFilters);
    document.getElementById('city-filter').addEventListener('change', applyFilters);

    document.getElementById('filter-verified').addEventListener('click', function () {
        const active = this.dataset.active === '1';
        this.dataset.active = active ? '0' : '1';
        this.classList.toggle('active', !active);
        applyFilters();
    });

    document.getElementById('filter-reset').addEventListener('click', function () {
        document.getElementById('search-input').value = '';
        document.getElementById('craft-filter').value = '';
        document.getElementById('city-filter').value = '';
        const vBtn = document.getElementById('filter-verified');
        vBtn.dataset.active = '0';
        vBtn.classList.remove('active');
        applyFilters();
    });

    /* ── Géolocalisation utilisateur ── */
    document.getElementById('btn-locate').addEventListener('click', function () {
        if (!navigator.geolocation) {
            alert('Géolocalisation non supportée par votre navigateur.');
            return;
        }
        this.innerHTML = '<i class="bi bi-arrow-repeat"></i> Localisation…';
        const btn = this;
        navigator.geolocation.getCurrentPosition(
            function (pos) {
                const lat = pos.coords.latitude, lng = pos.coords.longitude;
                L.circle([lat, lng], { radius: 500, color: '#008751', fillOpacity: .15 }).addTo(map);
                L.marker([lat, lng], {
                    icon: L.divIcon({
                        html: '<div style="width:14px;height:14px;background:#008751;border:3px solid #fff;border-radius:50%;box-shadow:0 0 0 4px rgba(0,135,81,.3);"></div>',
                        className: '', iconSize: [14,14], iconAnchor: [7,7],
                    })
                }).addTo(map).bindPopup('<b>Vous êtes ici</b>').openPopup();
                map.setView([lat, lng], 11, { animate: true });
                btn.innerHTML = '<i class="bi bi-crosshair2"></i> Ma position';
            },
            function () {
                btn.innerHTML = '<i class="bi bi-crosshair2"></i> Ma position';
                alert('Impossible d\'obtenir votre position.');
            }
        );
    });

    /* ── Légende métiers ── */
    const legend = L.control({ position: 'bottomleft' });
    legend.onAdd = function () {
        const div = L.DomUtil.create('div');
        div.style.cssText = 'background:#fff;padding:10px 14px;border-radius:10px;box-shadow:0 4px 14px rgba(0,0,0,.12);font-family:"Open Sans",sans-serif;font-size:.72rem;min-width:130px;';
        div.innerHTML = '<div style="font-weight:700;color:#1a1d23;margin-bottom:7px;">Légende métiers</div>'
            + Object.entries(CRAFT_COLORS).map(([craft, color]) =>
                `<div style="display:flex;align-items:center;gap:7px;margin-bottom:4px;">
                    <span style="width:10px;height:10px;background:${color};border-radius:50%;flex-shrink:0;"></span>
                    <span style="text-transform:capitalize;color:#4b5563;">${craft}</span>
                 </div>`
            ).join('');
        return div;
    };
    legend.addTo(map);

});
</script>
@endpush
