@extends('layouts.admin')

@section('title', $dish->name)

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
    /* ── Variables ─────────────────────────────────────── */
    :root {
        --cream:   #faf8f4;
        --ink:     #1a1a18;
        --muted:   #7a7a72;
        --accent:  #c8762a;
        --accent2: #2d6a4f;
        --border:  #e8e4dc;
        --card-bg: #ffffff;
        --radius:  14px;
        --shadow:  0 2px 20px rgba(0,0,0,0.07);
        --shadow-md: 0 8px 40px rgba(0,0,0,0.10);
    }

    body { background: var(--cream); font-family: 'DM Sans', sans-serif; }

    /* ── Hero banner ───────────────────────────────────── */
    .dish-hero {
        position: relative;
        border-radius: var(--radius);
        overflow: hidden;
        background: var(--ink);
        aspect-ratio: 4/3;
        cursor: pointer;
    }

    .dish-hero img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: .88;
        transition: transform .5s ease, opacity .3s;
    }

    .dish-hero:hover img { transform: scale(1.03); opacity: .95; }

    .dish-hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(20,18,14,.65) 30%, transparent 70%);
    }

    .dish-hero-badge {
        position: absolute;
        top: 16px;
        right: 16px;
        background: var(--accent);
        color: #fff;
        font-family: 'DM Sans', sans-serif;
        font-weight: 600;
        font-size: .72rem;
        letter-spacing: .08em;
        text-transform: uppercase;
        padding: 6px 14px;
        border-radius: 100px;
        display: flex;
        align-items: center;
        gap: 5px;
        backdrop-filter: blur(6px);
        box-shadow: 0 2px 12px rgba(200,118,42,.35);
    }

    /* ── Thumbnails ────────────────────────────────────── */
    .thumbs { display: flex; gap: 8px; margin-top: 10px; flex-wrap: wrap; }
    .thumbs img {
        width: 68px;
        height: 52px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid transparent;
        cursor: pointer;
        transition: border-color .2s, transform .2s;
        opacity: .75;
    }
    .thumbs img.active,
    .thumbs img:hover { border-color: var(--accent); opacity: 1; transform: translateY(-2px); }

    /* ── Cards ─────────────────────────────────────────── */
    .info-card {
        background: var(--card-bg);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 28px;
        margin-bottom: 20px;
        border: 1px solid var(--border);
        animation: fadeUp .45s ease both;
    }

    .info-card + .info-card { animation-delay: .07s; }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(18px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .section-label {
        font-family: 'DM Sans', sans-serif;
        font-size: .7rem;
        font-weight: 600;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: var(--muted);
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-label::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--border);
    }

    /* ── Dish name ─────────────────────────────────────── */
    .dish-title {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 2.1rem;
        font-weight: 700;
        color: var(--ink);
        line-height: 1.2;
        margin: 0 0 6px;
    }

    .dish-subtitle {
        font-size: .95rem;
        color: var(--muted);
        font-style: italic;
        margin-bottom: 20px;
    }

    .dish-price {
        font-family: 'Playfair Display', serif;
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--accent);
    }

    .dish-price small {
        font-family: 'DM Sans', sans-serif;
        font-size: .85rem;
        font-weight: 500;
        color: var(--muted);
    }

    /* ── Meta chips ────────────────────────────────────── */
    .meta-chips { display: flex; flex-wrap: wrap; gap: 8px; margin: 18px 0; }

    .chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 13px;
        border-radius: 100px;
        font-size: .8rem;
        font-weight: 500;
        border: 1px solid var(--border);
        background: var(--cream);
        color: var(--ink);
        transition: box-shadow .15s;
    }

    .chip:hover { box-shadow: 0 2px 8px rgba(0,0,0,.08); }
    .chip.green  { background: #ecf7f1; border-color: #a8dbbf; color: #1e7245; }
    .chip.orange { background: #fdf3e8; border-color: #f5c98a; color: #a05c15; }
    .chip.red    { background: #fdecea; border-color: #f5a7a0; color: #b03228; }
    .chip.blue   { background: #e8f3fc; border-color: #a0c8f0; color: #1a5f8a; }

    /* ── Stats row ─────────────────────────────────────── */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        margin: 20px 0;
    }

    .stat-box {
        background: var(--cream);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 14px 12px;
        text-align: center;
        transition: background .2s;
    }

    .stat-box:hover { background: #f0ece4; }

    .stat-box .stat-val {
        font-family: 'Playfair Display', serif;
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--ink);
    }

    .stat-box .stat-lbl {
        font-size: .72rem;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: .06em;
        margin-top: 2px;
    }

    /* ── Text blocks ───────────────────────────────────── */
    .text-block {
        font-size: .93rem;
        color: #3a3a36;
        line-height: 1.75;
    }

    .field-group { margin-bottom: 20px; }
    .field-group:last-child { margin-bottom: 0; }

    .field-label {
        font-size: .72rem;
        font-weight: 600;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--muted);
        margin-bottom: 5px;
    }

    .field-value {
        font-size: .95rem;
        color: var(--ink);
        line-height: 1.6;
    }

    /* ── Vendors table ─────────────────────────────────── */
    .vendors-table { width: 100%; font-size: .88rem; border-collapse: collapse; }
    .vendors-table th {
        font-size: .7rem;
        font-weight: 600;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--muted);
        padding: 10px 14px;
        border-bottom: 1px solid var(--border);
        text-align: left;
    }
    .vendors-table td { padding: 12px 14px; border-bottom: 1px solid var(--border); color: var(--ink); }
    .vendors-table tr:last-child td { border-bottom: none; }
    .vendors-table tr:hover td { background: var(--cream); }

    /* ── Action bar ────────────────────────────────────── */
    .action-bar {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 18px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        box-shadow: var(--shadow);
        animation: fadeUp .55s ease both;
    }

    .btn-outline-ink {
        border: 1.5px solid var(--border);
        background: transparent;
        color: var(--ink);
        padding: 9px 20px;
        border-radius: 8px;
        font-size: .88rem;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: background .18s, border-color .18s;
    }
    .btn-outline-ink:hover { background: var(--cream); border-color: var(--muted); color: var(--ink); }

    .btn-accent {
        background: var(--accent2);
        color: #fff;
        padding: 9px 22px;
        border-radius: 8px;
        font-size: .88rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        border: none;
        transition: opacity .18s, transform .15s;
    }
    .btn-accent:hover { opacity: .88; transform: translateY(-1px); color: #fff; }

    .btn-danger-soft {
        background: #fdecea;
        color: #b03228;
        padding: 9px 20px;
        border-radius: 8px;
        font-size: .88rem;
        font-weight: 600;
        cursor: pointer;
        border: 1.5px solid #f5a7a0;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: background .18s;
    }
    .btn-danger-soft:hover { background: #fbd5d3; }

    /* ── Ingredient pills ──────────────────────────────── */
    .ingredient-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 8px;
    }
    .ingredient-pill {
        background: var(--cream);
        border: 1px solid var(--border);
        border-radius: 6px;
        padding: 4px 12px;
        font-size: .82rem;
        color: var(--ink);
    }

    /* ── Preparation steps ─────────────────────────────── */
    .prep-steps ol {
        padding-left: 0;
        list-style: none;
        counter-reset: step;
    }
    .prep-steps ol li {
        counter-increment: step;
        display: flex;
        gap: 14px;
        margin-bottom: 14px;
        font-size: .92rem;
        color: #3a3a36;
        line-height: 1.65;
    }
    .prep-steps ol li::before {
        content: counter(step);
        min-width: 28px;
        height: 28px;
        background: var(--accent);
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .75rem;
        font-weight: 700;
        flex-shrink: 0;
        margin-top: 2px;
    }

    /* ── No image placeholder ──────────────────────────── */
    .no-image {
        aspect-ratio: 4/3;
        background: #f0ece4;
        border-radius: var(--radius);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: var(--muted);
        gap: 10px;
    }

    /* ── Responsive ────────────────────────────────────── */
    @media (max-width: 768px) {
        .dish-title { font-size: 1.6rem; }
        .stats-row  { grid-template-columns: repeat(3, 1fr); }
        .action-bar { flex-direction: column; align-items: flex-start; }
    }
</style>
@endpush

@section('content')

{{-- Breadcrumb ─────────────────────────────────────────── --}}
<div class="section-header">
    <h1 style="font-family:'Playfair Display',serif;">{{ $dish->name }}</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="{{ route('admin.dishes.index') }}">Gastronomie</a></div>
        <div class="breadcrumb-item active">{{ $dish->name }}</div>
    </div>
</div>

<div class="section-body">
<div class="row">

    {{-- ── Colonne gauche : visuels + stats ─────────────────── --}}
    <div class="col-lg-5 mb-4">

        {{-- Hero image --}}
        @php
            $primaryImage = $dish->images->where('is_primary', true)->first() ?? $dish->images->first();
        @endphp

        @if($primaryImage)
            <div class="dish-hero" onclick="openLightbox('{{ asset($primaryImage->image_url) }}')">
                <img id="hero-img" src="{{ asset($primaryImage->image_url) }}" alt="{{ $dish->name }}">
                <div class="dish-hero-overlay"></div>
                @if($dish->featured)
                    <div class="dish-hero-badge">
                        <i class="fas fa-star" style="font-size:.65rem;"></i> Vedette
                    </div>
                @endif
            </div>

            @if($dish->images->count() > 1)
            <div class="thumbs">
                @foreach($dish->images as $img)
                    <img src="{{ asset($img->image_url) }}"
                         alt="{{ $dish->name }}"
                         class="{{ $img->is_primary ? 'active' : '' }}"
                         onclick="switchImage(this, '{{ asset($img->image_url) }}')">
                @endforeach
            </div>
            @endif
        @else
            <div class="no-image">
                <i class="fas fa-image fa-3x"></i>
                <span style="font-size:.88rem;">Aucune image disponible</span>
            </div>
        @endif

        {{-- Stats ─────────────────────────────────────────── --}}
        <div class="info-card mt-3" style="padding:20px 24px;">
            <div class="section-label"><i class="fas fa-info-circle"></i> Informations rapides</div>
            <div class="stats-row">
                <div class="stat-box">
                    <div class="stat-val">{{ $dish->preparation_time ?? '—' }}</div>
                    <div class="stat-lbl">Min. prépa</div>
                </div>
                <div class="stat-box">
                    <div class="stat-val">{{ $dish->serving_size ?? '—' }}</div>
                    <div class="stat-lbl">Portions</div>
                </div>
                <div class="stat-box">
                    <div class="stat-val">
                        @switch($dish->difficulty_level)
                            @case('easy')   ★☆☆ @break
                            @case('medium') ★★☆ @break
                            @case('hard')   ★★★ @break
                            @default        — @endswitch
                    </div>
                    <div class="stat-lbl">Difficulté</div>
                </div>
            </div>

            {{-- Dietary chips --}}
            <div class="meta-chips">
                @if($dish->is_vegetarian)
                    <span class="chip green"><i class="fas fa-leaf fa-xs"></i> Végétarien</span>
                @endif
                @if($dish->is_vegan)
                    <span class="chip green"><i class="fas fa-seedling fa-xs"></i> Végan</span>
                @endif
                @if($dish->is_gluten_free)
                    <span class="chip blue"><i class="fas fa-bread-slice fa-xs"></i> Sans gluten</span>
                @endif
                @if($dish->spice_level)
                    @switch($dish->spice_level)
                        @case('none')
                            <span class="chip"><i class="fas fa-pepper-hot fa-xs"></i> Non épicé</span>
                            @break
                        @case('mild')
                            <span class="chip orange"><i class="fas fa-pepper-hot fa-xs"></i> Peu épicé</span>
                            @break
                        @case('medium')
                            <span class="chip orange"><i class="fas fa-pepper-hot fa-xs"></i> Épicé</span>
                            @break
                        @case('hot')
                            <span class="chip red"><i class="fas fa-pepper-hot fa-xs"></i> Très épicé</span>
                            @break
                    @endswitch
                @endif
                <span class="chip"><i class="fas fa-tag fa-xs"></i> {{ $dish->category }}</span>
                @if($dish->region)
                    <span class="chip"><i class="fas fa-map-marker-alt fa-xs"></i> {{ $dish->region }}</span>
                @endif
            </div>
        </div>
    </div>

    {{-- ── Colonne droite : détails ───────────────────────── --}}
    <div class="col-lg-7">

        {{-- Nom & prix --}}
        <div class="info-card">
            <div class="d-flex align-items-start justify-content-between flex-wrap gap-2 mb-2">
                <div>
                    <h1 class="dish-title">{{ $dish->name }}</h1>
                    @if($dish->name_local)
                        <div class="dish-subtitle">« {{ $dish->name_local }} »</div>
                    @endif
                </div>
                <div class="dish-price">
                    {{ number_format($dish->price, 0, ',', ' ') }}
                    <small>FCFA</small>
                </div>
            </div>

            @if($dish->description)
                <p class="text-block mt-3 mb-0">{{ $dish->description }}</p>
            @endif
        </div>

        {{-- Signification culturelle --}}
        @if($dish->cultural_significance)
        <div class="info-card">
            <div class="section-label"><i class="fas fa-landmark"></i> Signification culturelle</div>
            <p class="text-block mb-0">{{ $dish->cultural_significance }}</p>
        </div>
        @endif

        {{-- Ingrédients --}}
        @if($dish->main_ingredients || $dish->secondary_ingredients)
        <div class="info-card">
            <div class="section-label"><i class="fas fa-list"></i> Ingrédients</div>

            @if($dish->main_ingredients)
            <div class="field-group">
                <div class="field-label">Principaux</div>
                <div class="ingredient-list">
                    @foreach(explode(',', $dish->main_ingredients) as $ing)
                        <span class="ingredient-pill">{{ trim($ing) }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            @if($dish->secondary_ingredients)
            <div class="field-group">
                <div class="field-label">Secondaires</div>
                <div class="ingredient-list">
                    @foreach(explode(',', $dish->secondary_ingredients) as $ing)
                        <span class="ingredient-pill" style="opacity:.75">{{ trim($ing) }}</span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @endif

        {{-- Préparation --}}
        @if($dish->preparation_method)
        <div class="info-card">
            <div class="section-label"><i class="fas fa-fire-alt"></i> Méthode de préparation</div>
            <div class="prep-steps">
                <ol>
                    @foreach(array_filter(explode("\n", $dish->preparation_method)) as $step)
                        @if(trim($step))
                            <li>{{ trim($step) }}</li>
                        @endif
                    @endforeach
                </ol>
            </div>
        </div>
        @endif

        {{-- Vendeurs --}}
        @if($dish->vendors && $dish->vendors->count() > 0)
        <div class="info-card">
            <div class="section-label"><i class="fas fa-store"></i> Vendeurs proposant ce plat</div>
            <table class="vendors-table">
                <thead>
                    <tr>
                        <th>Vendeur</th>
                        <th>Localisation</th>
                        <th>Prix</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dish->vendors as $vendor)
                    <tr>
                        <td>
                            <a href="{{ route('admin.vendors.show', $vendor) }}"
                               style="color:var(--accent2);font-weight:500;text-decoration:none;">
                                {{ $vendor->name }}
                            </a>
                        </td>
                        <td style="color:var(--muted)">{{ $vendor->location ?? '—' }}</td>
                        <td style="font-weight:600;">
                            {{ number_format($vendor->pivot->price ?? $dish->price, 0, ',', ' ') }} FCFA
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

    </div>
</div>

{{-- ── Barre d'actions ──────────────────────────────────── --}}
<div class="action-bar mt-2">
    <a href="{{ route('admin.dishes.index') }}" class="btn-outline-ink">
        <i class="fas fa-arrow-left"></i> Retour à la liste
    </a>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('admin.dishes.edit', $dish) }}" class="btn-accent">
            <i class="fas fa-edit"></i> Modifier le plat
        </a>
        <button type="button" class="btn-danger-soft" onclick="confirmDelete()">
            <i class="fas fa-trash"></i> Supprimer
        </button>
    </div>
</div>

</div>{{-- /section-body --}}

{{-- Hidden delete form --}}
<form id="delete-form" action="{{ route('admin.dishes.destroy', $dish) }}" method="POST" style="display:none;">
    @csrf @method('DELETE')
</form>

{{-- Lightbox simple --}}
<div id="lightbox"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.88);z-index:9999;
            align-items:center;justify-content:center;cursor:zoom-out;"
     onclick="closeLightbox()">
    <img id="lightbox-img" src="" alt="" style="max-width:90vw;max-height:88vh;border-radius:10px;box-shadow:0 20px 60px rgba(0,0,0,.5);">
</div>

@endsection

@push('scripts')
<script>
    // ── Image switch ──────────────────────────────────────
    function switchImage(thumb, src) {
        document.getElementById('hero-img').src = src;
        document.querySelectorAll('.thumbs img').forEach(t => t.classList.remove('active'));
        thumb.classList.add('active');
    }

    // ── Lightbox ──────────────────────────────────────────
    function openLightbox(src) {
        const lb = document.getElementById('lightbox');
        document.getElementById('lightbox-img').src = src;
        lb.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        document.getElementById('lightbox').style.display = 'none';
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });

    // ── Suppression ───────────────────────────────────────
    function confirmDelete() {
        Swal.fire({
            title: 'Supprimer ce plat ?',
            html: `Le plat <strong>{{ $dish->name }}</strong> sera supprimé définitivement.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#b03228',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler'
        }).then(result => {
            if (result.isConfirmed) {
                document.getElementById('delete-form').submit();
            }
        });
    }
</script>
@endpush
