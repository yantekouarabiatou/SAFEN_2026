@extends('layouts.admin')

@section('title', $product->name)

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
    /* ── Variables couleurs Bénin ──────────────────────── */
    :root {
        --green:      #008751;   /* vert Bénin */
        --green-lt:   #e6f4ed;
        --green-md:   #b8dfc9;
        --yellow:     #fcd116;   /* jaune Bénin */
        --yellow-lt:  #fffbe6;
        --yellow-md:  #fde87a;
        --red:        #e8112d;   /* rouge Bénin */
        --red-lt:     #fdecea;
        --red-md:     #f7a5ae;

        --ink:        #181a18;
        --muted:      #6b7068;
        --border:     #e4e8e2;
        --bg:         #f7f9f6;
        --card:       #ffffff;
        --radius:     14px;
        --shadow:     0 2px 18px rgba(0,80,40,.07);
        --shadow-md:  0 8px 40px rgba(0,80,40,.10);
    }

    body { background: var(--bg); font-family: 'DM Sans', sans-serif; color: var(--ink); }

    /* ── Hero image ────────────────────────────────────── */
    .product-hero {
        position: relative;
        border-radius: var(--radius);
        overflow: hidden;
        background: var(--ink);
        aspect-ratio: 1 / 1;
        cursor: zoom-in;
    }
    .product-hero img {
        width: 100%; height: 100%;
        object-fit: cover; opacity: .92;
        transition: transform .5s ease, opacity .3s;
    }
    .product-hero:hover img { transform: scale(1.04); opacity: 1; }

    .hero-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,.45) 25%, transparent 65%);
        pointer-events: none;
    }

    /* Bandeau statut stock */
    .stock-ribbon {
        position: absolute; top: 16px; left: 16px;
        padding: 5px 14px;
        border-radius: 100px;
        font-size: .72rem; font-weight: 700;
        letter-spacing: .07em; text-transform: uppercase;
        display: flex; align-items: center; gap: 6px;
        backdrop-filter: blur(8px);
    }
    .stock-ribbon.in_stock     { background: rgba(0,135,81,.85); color:#fff; }
    .stock-ribbon.out_of_stock { background: rgba(232,17,45,.85); color:#fff; }
    .stock-ribbon.preorder     { background: rgba(252,209,22,.9); color: var(--ink); }
    .stock-ribbon.made_to_order{ background: rgba(255,255,255,.85); color: var(--ink); border:1px solid var(--border); }

    /* Badge vedette */
    .featured-ribbon {
        position: absolute; top: 16px; right: 16px;
        background: var(--yellow);
        color: var(--ink);
        padding: 5px 13px;
        border-radius: 100px;
        font-size: .72rem; font-weight: 700;
        letter-spacing: .07em; text-transform: uppercase;
        display: flex; align-items: center; gap: 5px;
        box-shadow: 0 2px 10px rgba(252,209,22,.4);
    }

    /* ── Thumbnails ────────────────────────────────────── */
    .thumbs { display: flex; gap: 8px; margin-top: 10px; flex-wrap: wrap; }
    .thumbs img {
        width: 66px; height: 66px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid transparent;
        cursor: pointer; opacity: .7;
        transition: border-color .2s, opacity .2s, transform .2s;
    }
    .thumbs img.active,
    .thumbs img:hover {
        border-color: var(--green);
        opacity: 1; transform: translateY(-2px);
    }

    /* ── Cards ─────────────────────────────────────────── */
    .info-card {
        background: var(--card);
        border-radius: var(--radius);
        border: 1px solid var(--border);
        box-shadow: var(--shadow);
        padding: 26px 28px;
        margin-bottom: 20px;
        animation: fadeUp .4s ease both;
    }
    @keyframes fadeUp {
        from { opacity:0; transform:translateY(16px); }
        to   { opacity:1; transform:translateY(0); }
    }
    .info-card:nth-child(2) { animation-delay:.06s; }
    .info-card:nth-child(3) { animation-delay:.12s; }
    .info-card:nth-child(4) { animation-delay:.18s; }

    /* ── Section label ─────────────────────────────────── */
    .section-label {
        font-size: .68rem; font-weight: 700;
        letter-spacing: .13em; text-transform: uppercase;
        color: var(--muted);
        margin-bottom: 16px;
        display: flex; align-items: center; gap: 8px;
    }
    .section-label i { color: var(--green); }
    .section-label::after {
        content: ''; flex:1; height:1px; background: var(--border);
    }

    /* ── Product title & price ─────────────────────────── */
    .product-title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 2rem; font-weight: 700;
        color: var(--ink); line-height: 1.2;
        margin: 0 0 5px;
    }
    .product-subtitle {
        font-size: .92rem; color: var(--muted);
        font-style: italic; margin-bottom: 18px;
    }
    .product-price {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2rem; font-weight: 700;
        color: var(--green);
    }
    .product-price small {
        font-family: 'DM Sans', sans-serif;
        font-size: .82rem; font-weight: 500; color: var(--muted);
    }

    /* ── Artisan block ─────────────────────────────────── */
    .artisan-block {
        display: flex; align-items: center; gap: 12px;
        padding: 12px 16px;
        background: var(--green-lt);
        border: 1px solid var(--green-md);
        border-radius: 10px;
        margin: 16px 0;
        text-decoration: none;
        transition: background .2s;
    }
    .artisan-block:hover { background: #d2ece0; }
    .artisan-block img {
        width: 40px; height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--green);
    }
    .artisan-block .artisan-name {
        font-weight: 600; font-size: .92rem; color: var(--green);
    }
    .artisan-block .artisan-spec {
        font-size: .78rem; color: var(--muted);
    }

    /* ── Chips / badges ────────────────────────────────── */
    .chip-row { display: flex; flex-wrap: wrap; gap: 8px; margin: 10px 0; }
    .chip {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 5px 13px; border-radius: 100px;
        font-size: .78rem; font-weight: 500;
        border: 1px solid var(--border);
        background: var(--bg); color: var(--ink);
        transition: box-shadow .15s;
    }
    .chip:hover { box-shadow: 0 2px 8px rgba(0,0,0,.08); }
    .chip.green  { background: var(--green-lt); border-color: var(--green-md); color: var(--green); }
    .chip.yellow { background: var(--yellow-lt); border-color: var(--yellow-md); color: #7a6200; }
    .chip.red    { background: var(--red-lt); border-color: var(--red-md); color: var(--red); }

    /* ── Stats grid ────────────────────────────────────── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
    }
    .stat-box {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 16px 10px;
        text-align: center;
        transition: background .2s, transform .2s;
    }
    .stat-box:hover { background: #eef4ec; transform: translateY(-2px); }
    .stat-box .stat-icon { font-size: 1.4rem; margin-bottom: 6px; }
    .stat-box .stat-val  {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.5rem; font-weight: 700; color: var(--ink);
    }
    .stat-box .stat-lbl {
        font-size: .68rem; color: var(--muted);
        text-transform: uppercase; letter-spacing: .07em;
        margin-top: 2px;
    }

    /* ── Characteristics table ─────────────────────────── */
    .char-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
    }
    .char-item { }
    .char-label {
        font-size: .68rem; font-weight: 700;
        letter-spacing: .1em; text-transform: uppercase;
        color: var(--muted); margin-bottom: 4px;
    }
    .char-value { font-size: .92rem; color: var(--ink); line-height: 1.5; }

    .dim-box {
        display: inline-flex; gap: 10px;
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 8px 14px;
        font-size: .88rem;
    }
    .dim-box span { color: var(--muted); font-size: .75rem; }

    /* ── Material pills ────────────────────────────────── */
    .mat-pill {
        display: inline-flex; align-items: center;
        background: var(--yellow-lt);
        border: 1px solid var(--yellow-md);
        color: #6a5300;
        border-radius: 6px;
        padding: 3px 11px;
        font-size: .8rem; font-weight: 500;
        margin: 2px;
    }

    /* ── Text block ────────────────────────────────────── */
    .text-block {
        font-size: .93rem; color: #3a3d38; line-height: 1.75;
        margin: 0;
    }

    /* ── Divider ───────────────────────────────────────── */
    .soft-divider {
        border: none; border-top: 1px solid var(--border);
        margin: 20px 0;
    }

    /* ── Flag stripe décoration ────────────────────────── */
    .flag-stripe {
        height: 4px;
        background: linear-gradient(to right, var(--green) 33.3%, var(--yellow) 33.3% 66.6%, var(--red) 66.6%);
        border-radius: 100px;
        margin-bottom: 20px;
    }

    /* ── Action bar ────────────────────────────────────── */
    .action-bar {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 16px 24px;
        display: flex; align-items: center;
        justify-content: space-between;
        flex-wrap: wrap; gap: 12px;
        box-shadow: var(--shadow);
        animation: fadeUp .5s .2s ease both;
    }

    .btn-back {
        border: 1.5px solid var(--border);
        background: transparent; color: var(--ink);
        padding: 9px 20px; border-radius: 8px;
        font-size: .88rem; font-weight: 500;
        cursor: pointer; text-decoration: none;
        display: inline-flex; align-items: center; gap: 7px;
        transition: background .18s, border-color .18s;
    }
    .btn-back:hover { background: var(--bg); border-color: var(--muted); color: var(--ink); }

    .btn-edit {
        background: var(--green); color: #fff;
        padding: 9px 22px; border-radius: 8px;
        font-size: .88rem; font-weight: 600;
        cursor: pointer; text-decoration: none;
        display: inline-flex; align-items: center; gap: 7px;
        border: none;
        transition: opacity .18s, transform .15s;
        box-shadow: 0 3px 12px rgba(0,135,81,.25);
    }
    .btn-edit:hover { opacity: .88; transform: translateY(-1px); color:#fff; }

    .btn-delete {
        background: var(--red-lt); color: var(--red);
        padding: 9px 20px; border-radius: 8px;
        font-size: .88rem; font-weight: 600;
        cursor: pointer; border: 1.5px solid var(--red-md);
        display: inline-flex; align-items: center; gap: 7px;
        transition: background .18s;
    }
    .btn-delete:hover { background: #fbd3d8; }

    /* ── No image ──────────────────────────────────────── */
    .no-image {
        aspect-ratio: 1/1; background: var(--green-lt);
        border-radius: var(--radius);
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        color: var(--green); gap: 10px;
        border: 2px dashed var(--green-md);
    }

    /* ── Lightbox ──────────────────────────────────────── */
    #lightbox {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,.9); z-index: 9999;
        align-items: center; justify-content: center;
        cursor: zoom-out;
    }
    #lightbox img {
        max-width: 90vw; max-height: 88vh;
        border-radius: 10px;
        box-shadow: 0 20px 60px rgba(0,0,0,.5);
    }

    /* ── Responsive ────────────────────────────────────── */
    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: repeat(2,1fr); }
        .char-grid  { grid-template-columns: 1fr; }
        .product-title { font-size: 1.55rem; }
    }
</style>
@endpush

@section('content')

<div class="section-header">
    <h1 style="font-family:'Cormorant Garamond',serif;">{{ $product->name }}</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Produits</a></div>
        <div class="breadcrumb-item active">{{ $product->name }}</div>
    </div>
</div>

<div class="section-body">

{{-- Bandeau drapeau décoratif --}}
<div class="flag-stripe"></div>

<div class="row">

    {{-- ── Colonne gauche : visuels ──────────────────────── --}}
    <div class="col-lg-5 mb-4">

        @php
            $primaryImage = $product->images->where('is_primary', true)->first() ?? $product->images->first();
        @endphp

        @if($primaryImage)
            <div class="product-hero" onclick="openLightbox('{{ asset($primaryImage->image_url) }}')">
                <img id="hero-img" src="{{ asset($primaryImage->image_url) }}" alt="{{ $product->name }}">
                <div class="hero-overlay"></div>

                {{-- Statut stock --}}
                @php
                    $stockMap = [
                        'in_stock'      => ['label' => 'En stock',       'icon' => 'fa-check-circle'],
                        'out_of_stock'  => ['label' => 'Rupture de stock','icon' => 'fa-times-circle'],
                        'preorder'      => ['label' => 'Précommande',     'icon' => 'fa-clock'],
                        'made_to_order' => ['label' => 'Sur commande',    'icon' => 'fa-tools'],
                    ];
                    $stock = $stockMap[$product->stock_status] ?? null;
                @endphp
                @if($stock)
                    <div class="stock-ribbon {{ $product->stock_status }}">
                        <i class="fas {{ $stock['icon'] }}" style="font-size:.65rem;"></i>
                        {{ $stock['label'] }}
                    </div>
                @endif

                @if($product->featured)
                    <div class="featured-ribbon">
                        <i class="fas fa-star" style="font-size:.65rem;"></i> Vedette
                    </div>
                @endif
            </div>

            @if($product->images->count() > 1)
            <div class="thumbs">
                @foreach($product->images as $img)
                    <img src="{{ asset($img->image_url) }}"
                         alt="{{ $product->name }}"
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

        {{-- Stats --}}
        <div class="info-card mt-3" style="padding:20px 24px;">
            <div class="section-label"><i class="fas fa-chart-bar"></i> Statistiques</div>
            <div class="stats-grid">
                <div class="stat-box">
                    <div class="stat-icon" style="color:var(--muted)"><i class="fas fa-eye"></i></div>
                    <div class="stat-val">{{ number_format($product->views_count ?? 0) }}</div>
                    <div class="stat-lbl">Vues</div>
                </div>
                <div class="stat-box">
                    <div class="stat-icon" style="color:var(--red)"><i class="fas fa-heart"></i></div>
                    <div class="stat-val">{{ number_format($product->favorites_count ?? 0) }}</div>
                    <div class="stat-lbl">Favoris</div>
                </div>
                <div class="stat-box">
                    <div class="stat-icon" style="color:var(--green)"><i class="fas fa-shopping-bag"></i></div>
                    <div class="stat-val">{{ number_format($product->orders_count ?? 0) }}</div>
                    <div class="stat-lbl">Commandes</div>
                </div>
                <div class="stat-box">
                    <div class="stat-icon" style="color:var(--yellow)"><i class="fas fa-star"></i></div>
                    <div class="stat-val">{{ number_format($product->average_rating ?? 0, 1) }}</div>
                    <div class="stat-lbl">Note moy.</div>
                </div>
            </div>
        </div>

    </div>

    {{-- ── Colonne droite : infos ─────────────────────────── --}}
    <div class="col-lg-7">

        {{-- Titre & prix --}}
        <div class="info-card">
            <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
                <div>
                    <h1 class="product-title">{{ $product->name }}</h1>
                    @if($product->name_local)
                        <div class="product-subtitle">« {{ $product->name_local }} »</div>
                    @endif
                </div>
                <div class="product-price">
                    {{ number_format($product->price, 0, ',', ' ') }}
                    <small>FCFA</small>
                </div>
            </div>

            {{-- Catégories --}}
            <div class="chip-row">
                <span class="chip green"><i class="fas fa-tag fa-xs"></i> {{ $product->category }}</span>
                @if($product->subcategory)
                    <span class="chip green"><i class="fas fa-tag fa-xs"></i> {{ $product->subcategory }}</span>
                @endif
                @if($product->ethnic_origin)
                    <span class="chip yellow"><i class="fas fa-map-marker-alt fa-xs"></i> {{ $product->ethnic_origin }}</span>
                @endif
            </div>

            {{-- Artisan --}}
            <a href="{{ route('admin.artisans.show', $product->artisan) }}" class="artisan-block">
                <img src="{{ $product->artisan->user->profile_photo_url ?? asset('admin-assets/img/avatar/avatar-1.png') }}"
                     alt="{{ $product->artisan->user->name }}">
                <div>
                    <div class="artisan-name">{{ $product->artisan->user->name }}</div>
                    <div class="artisan-spec">{{ $product->artisan->specialty }}</div>
                </div>
                <i class="fas fa-external-link-alt ml-auto" style="color:var(--green);font-size:.8rem;"></i>
            </a>

            @if($product->description)
                <hr class="soft-divider">
                <p class="text-block">{{ $product->description }}</p>
            @endif
        </div>

        {{-- Signification culturelle --}}
        @if($product->description_cultural)
        <div class="info-card">
            <div class="section-label"><i class="fas fa-landmark"></i> Signification culturelle</div>
            <p class="text-block">{{ $product->description_cultural }}</p>
        </div>
        @endif

        {{-- Techniques de fabrication --}}
        @if($product->description_technical)
        <div class="info-card">
            <div class="section-label"><i class="fas fa-hammer"></i> Techniques de fabrication</div>
            <p class="text-block">{{ $product->description_technical }}</p>
        </div>
        @endif

        {{-- Caractéristiques --}}
        <div class="info-card">
            <div class="section-label"><i class="fas fa-ruler-combined"></i> Caractéristiques</div>

            @if($product->materials)
            @php
                $materials = is_array($product->materials)
                    ? $product->materials
                    : array_filter(array_map('trim', explode(',', $product->materials)));
            @endphp
            <div class="mb-16" style="margin-bottom:16px;">
                <div class="char-label">Matériaux</div>
                <div>
                    @foreach($materials as $mat)
                        <span class="mat-pill">{{ $mat }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="char-grid">
                @if($product->width || $product->height || $product->depth)
                <div class="char-item">
                    <div class="char-label">Dimensions</div>
                    <div class="dim-box">
                        @if($product->width)  <div><span>L</span> {{ $product->width }} cm</div>  @endif
                        @if($product->height) <div><span>H</span> {{ $product->height }} cm</div> @endif
                        @if($product->depth)  <div><span>P</span> {{ $product->depth }} cm</div>  @endif
                    </div>
                </div>
                @endif

                @if($product->weight)
                <div class="char-item">
                    <div class="char-label">Poids</div>
                    <div class="char-value">{{ $product->weight }} kg</div>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

{{-- ── Barre d'actions ────────────────────────────────── --}}
<div class="action-bar mt-2">
    <a href="{{ route('admin.products.index') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Retour à la liste
    </a>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('admin.products.edit', $product) }}" class="btn-edit">
            <i class="fas fa-edit"></i> Modifier le produit
        </a>
        <button type="button" class="btn-delete" onclick="confirmDelete()">
            <i class="fas fa-trash"></i> Supprimer
        </button>
    </div>
</div>

</div>{{-- /section-body --}}

{{-- Hidden delete form --}}
<form id="delete-form" action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display:none;">
    @csrf @method('DELETE')
</form>

{{-- Lightbox --}}
<div id="lightbox" onclick="closeLightbox()">
    <img id="lightbox-img" src="" alt="">
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

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeLightbox();
    });

    // ── Suppression ───────────────────────────────────────
    function confirmDelete() {
        Swal.fire({
            title: 'Supprimer ce produit ?',
            html: `Le produit <strong>{{ $product->name }}</strong> sera supprimé définitivement.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e8112d',
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
