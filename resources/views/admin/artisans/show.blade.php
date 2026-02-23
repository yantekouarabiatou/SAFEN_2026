@extends('layouts.admin')

@section('title', $artisan->user->name)

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('admin-assets/bundles/datatables/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">

<style>
    :root {
        --green:     #008751;
        --green-lt:  #e6f4ed;
        --green-md:  #b8dfc9;
        --yellow:    #fcd116;
        --yellow-lt: #fffbe6;
        --yellow-md: #fde87a;
        --red:       #e8112d;
        --red-lt:    #fdecea;
        --red-md:    #f7a5ae;
        --ink:       #181a18;
        --muted:     #6b7068;
        --border:    #e4e8e2;
        --bg:        #f7f9f6;
        --card:      #ffffff;
        --radius:    14px;
        --shadow:    0 2px 18px rgba(0,80,40,.07);
    }

    body { background: var(--bg); font-family: 'DM Sans', sans-serif; color: var(--ink); }

    @keyframes fadeUp {
        from { opacity:0; transform:translateY(14px); }
        to   { opacity:1; transform:translateY(0); }
    }

    /* ── Flag stripe ───────────── */
    .flag-stripe {
        height: 4px;
        background: linear-gradient(to right, var(--green) 33.3%, var(--yellow) 33.3% 66.6%, var(--red) 66.6%);
        border-radius: 100px; margin-bottom: 20px;
    }

    /* ── Info cards ────────────── */
    .info-card {
        background: var(--card); border-radius: var(--radius);
        border: 1px solid var(--border); box-shadow: var(--shadow);
        margin-bottom: 20px; overflow: hidden;
        animation: fadeUp .4s ease both;
    }

    .section-label {
        font-size: .68rem; font-weight: 700; letter-spacing: .13em;
        text-transform: uppercase; color: var(--muted);
        display: flex; align-items: center; gap: 8px;
        padding: 20px 22px 0; margin-bottom: 14px;
    }
    .section-label i { color: var(--green); }
    .section-label::after { content:''; flex:1; height:1px; background:var(--border); }

    /* ── Hero profil ───────────── */
    .profile-hero {
        background: linear-gradient(135deg, var(--green) 0%, #005c38 100%);
        padding: 30px 24px 22px; text-align: center; position: relative; overflow: hidden;
    }
    .profile-hero::before {
        content: ''; position: absolute; inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none'%3E%3Cg fill='%23fff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    .profile-avatar {
        width: 100px; height: 100px; object-fit: cover; border-radius: 50%;
        border: 4px solid rgba(255,255,255,.3); box-shadow: 0 8px 24px rgba(0,0,0,.2);
        margin-bottom: 14px; position: relative; z-index: 1;
    }
    .profile-avatar-ph {
        width: 100px; height: 100px; background: rgba(255,255,255,.15);
        border: 4px solid rgba(255,255,255,.3); border-radius: 50%; color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-family: 'Cormorant Garamond', serif; font-size: 2.2rem; font-weight: 700;
        margin: 0 auto 14px; position: relative; z-index: 1;
    }
    .profile-name {
        font-family: 'Cormorant Garamond', serif; font-size: 1.5rem; font-weight: 700;
        color: #fff; margin: 0 0 4px; position: relative; z-index: 1;
    }
    .profile-craft {
        color: var(--yellow); font-size: .82rem; font-weight: 700;
        letter-spacing: .06em; text-transform: uppercase; position: relative; z-index: 1;
    }
    .profile-biz {
        color: rgba(255,255,255,.7); font-size: .82rem; margin-top: 4px;
        position: relative; z-index: 1;
    }

    /* ── Stats row ─────────────── */
    .stats-row { display: grid; grid-template-columns: repeat(3,1fr); border-top: 1px solid var(--border); }
    .stat-item {
        padding: 14px 10px; text-align: center;
        border-right: 1px solid var(--border); transition: background .2s;
    }
    .stat-item:last-child { border-right: none; }
    .stat-item:hover { background: var(--bg); }
    .stat-val { font-family: 'Cormorant Garamond', serif; font-size: 1.5rem; font-weight: 700; }
    .stat-lbl { font-size: .68rem; color: var(--muted); text-transform: uppercase; letter-spacing: .07em; }

    /* ── Sidebar actions ───────── */
    .sidebar-actions { padding: 0 22px 20px; display: flex; flex-direction: column; gap: 8px; }
    .btn-green-solid {
        background: var(--green); color: #fff; border: none; border-radius: 8px;
        padding: 9px 18px; font-size: .88rem; font-weight: 600;
        display: flex; align-items: center; justify-content: center; gap: 7px;
        text-decoration: none; cursor: pointer;
        box-shadow: 0 3px 10px rgba(0,135,81,.2); transition: opacity .18s;
    }
    .btn-green-solid:hover { opacity: .88; color: #fff; }
    .btn-outline-benin {
        background: transparent; color: var(--green);
        border: 1.5px solid var(--green-md); border-radius: 8px;
        padding: 8px 18px; font-size: .88rem; font-weight: 600;
        display: flex; align-items: center; justify-content: center; gap: 7px;
        text-decoration: none; cursor: pointer; transition: background .18s;
    }
    .btn-outline-benin:hover { background: var(--green-lt); color: var(--green); }
    .btn-danger-soft {
        background: var(--red-lt); color: var(--red);
        border: 1.5px solid var(--red-md); border-radius: 8px;
        padding: 8px 18px; font-size: .88rem; font-weight: 600;
        display: flex; align-items: center; justify-content: center; gap: 7px;
        cursor: pointer; transition: background .18s;
    }
    .btn-danger-soft:hover { background: #fbd3d8; }

    /* ── Contact list ──────────── */
    .contact-list { padding: 6px 22px 16px; }
    .contact-item {
        display: flex; align-items: flex-start; gap: 12px;
        padding: 10px 0; border-bottom: 1px solid var(--border);
    }
    .contact-item:last-child { border-bottom: none; }
    .contact-icon {
        width: 32px; height: 32px; background: var(--green-lt); border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        color: var(--green); font-size: .8rem; flex-shrink: 0; margin-top: 2px;
    }
    .contact-lbl { font-size: .7rem; color: var(--muted); text-transform: uppercase; letter-spacing: .07em; }
    .contact-val { font-size: .9rem; color: var(--ink); font-weight: 500; }

    /* ── Chips ─────────────────── */
    .chip {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 12px; border-radius: 100px; font-size: .78rem; font-weight: 600;
    }
    .chip.green  { background: var(--green-lt);  color: var(--green);  border: 1px solid var(--green-md); }
    .chip.yellow { background: var(--yellow-lt); color: #7a6200;       border: 1px solid var(--yellow-md); }
    .chip.red    { background: var(--red-lt);    color: var(--red);    border: 1px solid var(--red-md); }
    .chip.grey   { background: #f0ece4;          color: var(--muted);  border: 1px solid var(--border); }

    /* ── Status grid ───────────── */
    .status-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; padding: 6px 22px 20px; }
    .status-box { background: var(--bg); border: 1px solid var(--border); border-radius: 10px; padding: 12px 14px; }
    .status-box .s-lbl { font-size: .68rem; color: var(--muted); text-transform: uppercase; letter-spacing: .07em; margin-bottom: 6px; }

    /* ── Detail grid ───────────── */
    .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; padding: 0 22px 20px; }
    .field-full { grid-column: 1 / -1; }
    .field-lbl { font-size: .68rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: var(--muted); margin-bottom: 5px; }
    .field-val { font-size: .93rem; color: var(--ink); line-height: 1.55; }

    .pill-list { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 4px; }
    .pill { background: var(--yellow-lt); border: 1px solid var(--yellow-md); color: #6a5300; border-radius: 6px; padding: 3px 11px; font-size: .8rem; font-weight: 500; }
    .pill.green { background: var(--green-lt); border-color: var(--green-md); color: var(--green); }
    .pill.gold  { background: #fff8e6; border-color: #f0c84a; color: #7a5c00; }

    /* ── Galerie ───────────────── */
    .photo-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
        gap: 8px; padding: 0 22px 20px;
    }
    .photo-thumb {
        aspect-ratio: 1/1; border-radius: 8px; object-fit: cover;
        border: 2px solid transparent; cursor: zoom-in;
        transition: border-color .2s, transform .2s;
    }
    .photo-thumb:hover { border-color: var(--green); transform: scale(1.04); }

    /* ── Produits tableau scrollable ── */
    .table-scroll-wrap {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        padding: 0 22px 20px;
    }
    .table-scroll-wrap table { min-width: 650px; width: 100%; }
    .table-scroll-wrap .table th,
    .table-scroll-wrap .table td { vertical-align: middle !important; white-space: nowrap; }

    .prod-img-thumb {
        width: 44px; height: 44px; object-fit: cover;
        border-radius: 6px; border: 2px solid var(--border);
    }
    .prod-img-ph {
        width: 44px; height: 44px; background: var(--green-lt); border-radius: 6px;
        display: flex; align-items: center; justify-content: center;
        color: var(--green); font-weight: 700; font-size: .8rem;
    }

    /* Stock badges */
    .badge-in_stock      { background: var(--green)  !important; color: #fff !important; }
    .badge-out_of_stock  { background: var(--red)    !important; color: #fff !important; }
    .badge-preorder      { background: var(--yellow) !important; color: #1a1a18 !important; }
    .badge-made_to_order { background: #17a2b8       !important; color: #fff !important; }

    /* Statut approbation badges */
    .badge-approved { background: var(--green) !important; color: #fff !important; }
    .badge-pending  { background: var(--yellow)!important; color: #1a1a18 !important; }
    .badge-rejected { background: var(--red)   !important; color: #fff !important; }

    /* ── Avis ──────────────────── */
    .review-item {
        display: flex; gap: 12px; padding: 14px 22px;
        border-bottom: 1px solid var(--border);
    }
    .review-item:last-child { border-bottom: none; }
    .review-av {
        width: 40px; height: 40px; border-radius: 50%;
        object-fit: cover; flex-shrink: 0; border: 2px solid var(--border);
    }
    .review-av-ph {
        width: 40px; height: 40px; border-radius: 50%;
        background: var(--green-lt); color: var(--green);
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: .8rem; flex-shrink: 0;
    }
    .review-stars { color: var(--yellow); font-size: .75rem; letter-spacing: 1px; }

    /* ── Action bar ────────────── */
    .action-bar {
        background: var(--card); border: 1px solid var(--border);
        border-radius: var(--radius); padding: 16px 24px;
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 12px; box-shadow: var(--shadow);
        animation: fadeUp .5s .2s ease both; margin-top: 8px;
    }
    .btn-back {
        border: 1.5px solid var(--border); background: transparent; color: var(--ink);
        padding: 9px 20px; border-radius: 8px; font-size: .88rem; font-weight: 500;
        cursor: pointer; text-decoration: none;
        display: inline-flex; align-items: center; gap: 7px; transition: background .18s;
    }
    .btn-back:hover { background: var(--bg); border-color: var(--muted); color: var(--ink); }

    /* ── Lightbox ──────────────── */
    #lightbox {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,.9); z-index: 9999;
        align-items: center; justify-content: center; cursor: zoom-out;
    }
    #lightbox img { max-width: 90vw; max-height: 88vh; border-radius: 10px; }

    /* ── DataTables pagination ──── */
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: .4rem .8rem !important; margin: 0 2px !important; border-radius: 6px !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--green) !important; color: white !important; border-color: var(--green) !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
        background: #e6f4ed !important; border-color: var(--green) !important; color: var(--green) !important;
    }

    @media (max-width: 768px) {
        .detail-grid { grid-template-columns: 1fr; }
        .status-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="section-header">
    <h1 style="font-family:'Cormorant Garamond',serif;">{{ $artisan->user->name }}</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="{{ route('admin.artisans.index') }}">Artisans</a></div>
        <div class="breadcrumb-item active">{{ $artisan->user->name }}</div>
    </div>
</div>

<div class="section-body">
<div class="flag-stripe"></div>

<div class="row">

{{-- ══ COLONNE GAUCHE ═════════════════════════════ --}}
<div class="col-lg-4 mb-4">

    {{-- Hero profil --}}
    <div class="info-card">
        <div class="profile-hero">
            @php
                $primaryPhoto = $artisan->photos->where('is_primary', true)->first()
                             ?? $artisan->photos->first();
            @endphp
            @if($primaryPhoto)
                <img src="{{ asset($primaryPhoto->photo_url) }}"
                     alt="{{ $artisan->user->name }}"
                     class="profile-avatar">
            @else
                <div class="profile-avatar-ph">
                    {{ strtoupper(substr($artisan->user->name ?? 'A', 0, 2)) }}
                </div>
            @endif

            <div class="profile-name">{{ $artisan->user->name }}</div>

            {{-- craft_label : accessor correct (pas specialty) --}}
            <div class="profile-craft">{{ $artisan->craft_label }}</div>

            @if($artisan->business_name)
                <div class="profile-biz">{{ $artisan->business_name }}</div>
            @endif
        </div>

        {{-- Stats --}}
        <div class="stats-row">
            <div class="stat-item">
                <div class="stat-val">{{ $artisan->products->count() }}</div>
                <div class="stat-lbl">Produits</div>
            </div>
            <div class="stat-item">
                <div class="stat-val">{{ $artisan->reviews->count() }}</div>
                <div class="stat-lbl">Avis</div>
            </div>
            <div class="stat-item">
                {{-- rating_avg : colonne réelle (pas average_rating) --}}
                <div class="stat-val">{{ number_format($artisan->rating_avg ?? 0, 1) }}</div>
                <div class="stat-lbl">Note</div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="sidebar-actions mt-3">
            <a href="{{ route('admin.artisans.edit', $artisan) }}" class="btn-green-solid">
                <i class="fas fa-edit"></i> Modifier le profil
            </a>
            @if($artisan->isPending())
                <button type="button" class="btn-outline-benin" id="btn-approve"
                        data-id="{{ $artisan->id }}" data-name="{{ $artisan->user->name }}">
                    <i class="fas fa-check"></i> Approuver
                </button>
                <button type="button" class="btn-danger-soft" id="btn-reject"
                        data-id="{{ $artisan->id }}" data-name="{{ $artisan->user->name }}">
                    <i class="fas fa-times"></i> Rejeter
                </button>
            @endif
        </div>
    </div>

    {{-- Contact --}}
    <div class="info-card">
        <div class="section-label"><i class="fas fa-address-book"></i> Contact</div>
        <div class="contact-list">
            <div class="contact-item">
                <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                <div>
                    <div class="contact-lbl">Email</div>
                    <div class="contact-val">{{ $artisan->user->email }}</div>
                </div>
            </div>

            {{-- phone est sur artisan, pas sur user --}}
            @if($artisan->phone)
            <div class="contact-item">
                <div class="contact-icon"><i class="fas fa-phone"></i></div>
                <div>
                    <div class="contact-lbl">Téléphone</div>
                    <div class="contact-val">
                        <a href="tel:{{ $artisan->phone }}" style="color:var(--green);">{{ $artisan->phone }}</a>
                    </div>
                </div>
            </div>
            @endif

            @if($artisan->whatsapp)
            <div class="contact-item">
                <div class="contact-icon" style="background:#e8f9ef;color:#25D366;">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <div>
                    <div class="contact-lbl">WhatsApp</div>
                    <div class="contact-val">{{ $artisan->whatsapp }}</div>
                </div>
            </div>
            @endif

            {{-- location : accessor du modèle (neighborhood + city) --}}
            @if($artisan->location)
            <div class="contact-item">
                <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                <div>
                    <div class="contact-lbl">Localisation</div>
                    <div class="contact-val">{{ $artisan->location }}</div>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Statut --}}
    <div class="info-card">
        <div class="section-label"><i class="fas fa-shield-alt"></i> Statut</div>
        <div class="status-grid">
            <div class="status-box">
                <div class="s-lbl">Approbation</div>
                @php
                    $sMap = [
                        'approved' => ['label'=>'Approuvé',    'cls'=>'green'],
                        'pending'  => ['label'=>'En attente',  'cls'=>'yellow'],
                        'rejected' => ['label'=>'Rejeté',      'cls'=>'red'],
                    ];
                    $s = $sMap[$artisan->status] ?? ['label'=>$artisan->status,'cls'=>'grey'];
                @endphp
                {{-- status : colonne réelle (pas is_verified) --}}
                <span class="chip {{ $s['cls'] }}">{{ $s['label'] }}</span>
            </div>
            <div class="status-box">
                <div class="s-lbl">Visibilité</div>
                {{-- visible : colonne réelle --}}
                <span class="chip {{ $artisan->visible ? 'green' : 'grey' }}">
                    {{ $artisan->visible ? 'Visible' : 'Masqué' }}
                </span>
            </div>
            <div class="status-box">
                <div class="s-lbl">Vedette</div>
                <span class="chip {{ $artisan->featured ? 'yellow' : 'grey' }}">
                    {{ $artisan->featured ? '⭐ Oui' : 'Non' }}
                </span>
            </div>
            <div class="status-box">
                <div class="s-lbl">Expérience</div>
                {{-- years_experience : colonne réelle (pas experience_years) --}}
                <span class="chip green">
                    {{ $artisan->years_experience ? $artisan->years_experience.' ans' : '—' }}
                </span>
            </div>
        </div>

        @if($artisan->isRejected() && $artisan->rejection_reason)
        <div style="padding:0 22px 16px;">
            <div class="field-lbl">Motif du rejet</div>
            <div style="background:var(--red-lt);border:1px solid var(--red-md);border-radius:8px;padding:10px 14px;font-size:.88rem;color:var(--red);">
                {{ $artisan->rejection_reason }}
            </div>
        </div>
        @endif

        @if($artisan->approved_at)
        <div style="padding:0 22px 16px;font-size:.82rem;color:var(--muted);">
            <i class="fas fa-check-circle" style="color:var(--green);"></i>
            Approuvé le {{ $artisan->approved_at->format('d/m/Y à H:i') }}
            @if($artisan->approver) par <strong>{{ $artisan->approver->name }}</strong> @endif
        </div>
        @endif
    </div>

</div>

{{-- ══ COLONNE DROITE ════════════════════════════ --}}
<div class="col-lg-8">

    {{-- Bio --}}
    @if($artisan->bio)
    <div class="info-card">
        <div class="section-label"><i class="fas fa-user"></i> Biographie</div>
        <p style="padding:0 22px 20px;font-size:.93rem;color:#3a3d38;line-height:1.75;margin:0;">
            {{ $artisan->bio }}
        </p>
    </div>
    @endif

    {{-- Galerie photos --}}
    @if($artisan->photos->count() > 0)
    <div class="info-card">
        <div class="section-label"><i class="fas fa-images"></i> Galerie photos</div>
        <div class="photo-grid">
            @foreach($artisan->photos as $photo)
                <img src="{{ asset($photo->photo_url) }}"
                     alt="{{ $artisan->user->name }}"
                     class="photo-thumb"
                     onclick="openLightbox('{{ asset($photo->photo_url) }}')">
            @endforeach
        </div>
    </div>
    @endif

    {{-- Détails --}}
    <div class="info-card">
        <div class="section-label"><i class="fas fa-info-circle"></i> Détails</div>
        <div class="detail-grid">

            <div>
                <div class="field-lbl">Métier</div>
                {{-- craft_label : accessor (pas specialty) --}}
                <div class="field-val">
                    <span class="chip green">{{ $artisan->craft_label }}</span>
                </div>
            </div>

            <div>
                <div class="field-lbl">Expérience</div>
                {{-- years_experience : colonne réelle --}}
                <div class="field-val">{{ $artisan->years_experience ? $artisan->years_experience.' ans' : '—' }}</div>
            </div>

            @if($artisan->languages_spoken)
            <div class="field-full">
                <div class="field-lbl">Langues parlées</div>
                <div class="pill-list">
                    @foreach((array) $artisan->languages_spoken as $lang)
                        <span class="pill green">{{ $lang }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            @if($artisan->pricing_info)
            <div class="field-full">
                <div class="field-lbl">Informations tarifaires</div>
                <div class="field-val">{{ $artisan->pricing_info }}</div>
            </div>
            @endif

        </div>
    </div>

    {{-- ══ PRODUITS — tableau scrollable, 0 colonne cachée ══ --}}
    <div class="info-card">
        <div class="d-flex align-items-center justify-content-between" style="padding:20px 22px 0;">
            <div class="section-label" style="padding:0;margin:0;flex:1;">
                <i class="fas fa-box-open"></i>
                Produits ({{ $artisan->products->count() }})
                <span style="flex:1;height:1px;background:var(--border);margin-left:8px;"></span>
            </div>
            <a href="{{ route('admin.products.create') }}?artisan_id={{ $artisan->id }}"
               class="btn btn-sm btn-success ml-3" style="white-space:nowrap;">
                <i class="fas fa-plus"></i> Ajouter
            </a>
        </div>

        @if($artisan->products->count() > 0)

        {{-- Wrapper avec overflow-x:auto = scroll horizontal --}}
        {{-- responsive:false + scrollX:true dans DataTables = aucune colonne cachée --}}
        <div class="table-scroll-wrap">
            <table id="productsTable" class="table table-hover table-sm mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Image</th>
                        <th>Nom</th>
                        <th>Nom local</th>
                        <th>Catégorie</th>
                        <th>Prix (FCFA)</th>
                        <th>Stock</th>
                        <th>Vedette</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($artisan->products as $product)
                    @php
                        $img = $product->images->where('is_primary',true)->first() ?? $product->images->first();
                        $stockMap = [
                            'in_stock'      => ['label'=>'En stock',    'cls'=>'badge-in_stock'],
                            'out_of_stock'  => ['label'=>'Rupture',     'cls'=>'badge-out_of_stock'],
                            'preorder'      => ['label'=>'Précommande', 'cls'=>'badge-preorder'],
                            'made_to_order' => ['label'=>'Sur commande','cls'=>'badge-made_to_order'],
                        ];
                        $stock = $stockMap[$product->stock_status] ?? ['label'=>$product->stock_status,'cls'=>'badge-secondary'];
                    @endphp
                    <tr>
                        <td>
                            @if($img)
                                <img src="{{ asset($img->image_url) }}" alt="{{ $product->name }}" class="prod-img-thumb">
                            @else
                                <div class="prod-img-ph">{{ strtoupper(substr($product->name,0,2)) }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="font-weight-bold" style="font-size:.88rem;">{{ Str::limit($product->name,28) }}</span>
                        </td>
                        <td>
                            <span style="font-size:.83rem;color:var(--muted);font-style:italic;">
                                {{ $product->name_local ?? '—' }}
                            </span>
                        </td>
                        <td><span class="badge badge-info">{{ ucfirst($product->category) }}</span></td>
                        <td>
                            <span class="font-weight-bold" style="color:var(--green);white-space:nowrap;">
                                {{ number_format($product->price,0,',',' ') }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $stock['cls'] }} px-2 py-1">{{ $stock['label'] }}</span>
                        </td>
                        <td class="text-center">
                            @if($product->featured)
                                <span style="color:var(--yellow);font-size:1rem;" title="En vedette">★</span>
                            @else
                                <span style="color:var(--border);">☆</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.products.show', $product) }}"
                               class="btn btn-sm btn-info" title="Voir">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @else
        <div class="text-center py-5">
            <i class="fas fa-box-open fa-3x" style="color:var(--border);"></i>
            <p class="text-muted mt-3 mb-3">Cet artisan n'a pas encore de produits.</p>
            <a href="{{ route('admin.products.create') }}?artisan_id={{ $artisan->id }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Ajouter un produit
            </a>
        </div>
        @endif
    </div>

    {{-- Avis --}}
    <div class="info-card">
        <div class="section-label"><i class="fas fa-comments"></i> Derniers avis ({{ $artisan->reviews->count() }})</div>

        @forelse($artisan->reviews->take(5) as $review)
        <div class="review-item">
            @if($review->user->profile_photo_url ?? false)
                <img src="{{ $review->user->profile_photo_url }}" class="review-av" alt="">
            @else
                <div class="review-av-ph">{{ strtoupper(substr($review->user->name ?? 'U', 0, 2)) }}</div>
            @endif
            <div style="flex:1;">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="font-weight-bold" style="font-size:.88rem;">{{ $review->user->name ?? 'Anonyme' }}</span>
                    <div class="review-stars">
                        @for($i=1;$i<=5;$i++){{ $i<=$review->rating ? '★' : '☆' }}@endfor
                    </div>
                </div>
                <div style="font-size:.75rem;color:var(--muted);">{{ $review->created_at->diffForHumans() }}</div>
                <div style="font-size:.88rem;color:#3a3d38;margin-top:4px;line-height:1.6;">{{ $review->comment }}</div>
            </div>
        </div>
        @empty
        <div class="text-center py-4">
            <i class="fas fa-comments fa-2x" style="color:var(--border);"></i>
            <p class="text-muted mt-2 mb-0">Aucun avis pour le moment.</p>
        </div>
        @endforelse
    </div>

</div>
</div>

{{-- ══ Barre d'actions ══════════════════════════════════════ --}}
<div class="action-bar">
    <a href="{{ route('admin.artisans.index') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Retour à la liste
    </a>
    <div class="d-flex flex-wrap" style="gap:8px;">
        <a href="{{ route('admin.artisans.edit', $artisan) }}" class="btn-green-solid" style="text-decoration:none;">
            <i class="fas fa-edit"></i> Modifier
        </a>
        <button type="button" class="btn-danger-soft" onclick="confirmDelete()">
            <i class="fas fa-trash"></i> Supprimer
        </button>
    </div>
</div>

</div>{{-- /section-body --}}

<form id="delete-form" action="{{ route('admin.artisans.destroy', $artisan) }}" method="POST" style="display:none;">
    @csrf @method('DELETE')
</form>

<div id="lightbox" onclick="closeLightbox()" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.9);z-index:9999;align-items:center;justify-content:center;cursor:zoom-out;">
    <img id="lightbox-img" src="" alt="" style="max-width:90vw;max-height:88vh;border-radius:10px;">
</div>
@endsection

@push('scripts')
<script src="{{ asset('admin-assets/bundles/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('admin-assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>

<script>
$(document).ready(function () {

    // ── Tableau produits ──────────────────────────────────
    // responsive: false  → aucune colonne cachée
    // scrollX: true      → scroll horizontal au lieu de cacher
    @if($artisan->products->count() > 0)
    $('#productsTable').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json' },
        responsive: false,
        scrollX: true,
        dom: 'frtip',
        pageLength: 10,
        columnDefs: [
            { orderable: false, targets: [0, 6, 7] }
        ],
        order: [[1, 'asc']]
    });
    @endif

    // ── Lightbox ──────────────────────────────────────────
    window.openLightbox = function(src) {
        $('#lightbox-img').attr('src', src);
        $('#lightbox').css('display', 'flex');
        document.body.style.overflow = 'hidden';
    };
    window.closeLightbox = function() {
        $('#lightbox').hide();
        document.body.style.overflow = '';
    };
    $(document).keydown(function(e) { if (e.key === 'Escape') closeLightbox(); });

    // ── Approuver ─────────────────────────────────────────
    $('#btn-approve').on('click', function () {
        const id = $(this).data('id'), name = $(this).data('name');
        Swal.fire({
            title: 'Approuver cet artisan ?',
            html: `<strong>${name}</strong> sera visible sur la plateforme.`,
            icon: 'question', showCancelButton: true,
            confirmButtonColor: '#008751', cancelButtonColor: '#6c757d',
            confirmButtonText: 'Oui, approuver', cancelButtonText: 'Annuler',
            customClass: { popup: 'swal-benin-popup swal-benin-popup--success', confirmButton: 'swal-btn swal-btn--green' }
        }).then(result => {
            if (!result.isConfirmed) return;
            $.post('{{ url("admin/artisans") }}/' + id + '/approve', { _token: '{{ csrf_token() }}' })
                .done(() => Swal.fire({ icon:'success', title:'Approuvé !', timer:1600, showConfirmButton:false,
                    customClass:{ popup:'swal-benin-popup swal-benin-popup--success' }
                }).then(() => location.reload()))
                .fail(xhr => Swal.fire({ icon:'error', title:'Erreur', text: xhr.responseJSON?.message ?? 'Une erreur est survenue.' }));
        });
    });

    // ── Rejeter ───────────────────────────────────────────
    $('#btn-reject').on('click', function () {
        const id = $(this).data('id'), name = $(this).data('name');
        Swal.fire({
            title: 'Rejeter cet artisan ?',
            html: `<p style="font-size:.9rem;color:#6b7068;margin-bottom:10px;">Motif pour <strong>${name}</strong> (optionnel) :</p>
                   <textarea id="rej-reason" class="swal2-textarea" placeholder="Ex : Informations insuffisantes..." style="font-size:.88rem;resize:vertical;"></textarea>`,
            icon: 'warning', showCancelButton: true,
            confirmButtonColor: '#e8112d', cancelButtonColor: '#6c757d',
            confirmButtonText: 'Rejeter', cancelButtonText: 'Annuler',
            customClass: { popup: 'swal-benin-popup swal-benin-popup--error', confirmButton: 'swal-btn swal-btn--red' },
            preConfirm: () => document.getElementById('rej-reason').value
        }).then(result => {
            if (!result.isConfirmed) return;
            $.post('{{ url("admin/artisans") }}/' + id + '/reject', { _token: '{{ csrf_token() }}', reason: result.value })
                .done(() => Swal.fire({ icon:'success', title:'Rejeté', timer:1600, showConfirmButton:false,
                    customClass:{ popup:'swal-benin-popup swal-benin-popup--success' }
                }).then(() => location.reload()))
                .fail(xhr => Swal.fire({ icon:'error', title:'Erreur', text: xhr.responseJSON?.message ?? 'Une erreur est survenue.' }));
        });
    });

    // ── Supprimer ─────────────────────────────────────────
    window.confirmDelete = function () {
        Swal.fire({
            title: 'Supprimer cet artisan ?',
            html: `<strong>{{ $artisan->user->name }}</strong> et tous ses produits seront supprimés définitivement.`,
            icon: 'warning', showCancelButton: true,
            confirmButtonColor: '#e8112d', cancelButtonColor: '#6c757d',
            confirmButtonText: 'Oui, supprimer', cancelButtonText: 'Annuler',
            customClass: { popup: 'swal-benin-popup swal-benin-popup--error', confirmButton: 'swal-btn swal-btn--red' }
        }).then(result => { if (result.isConfirmed) $('#delete-form').submit(); });
    };
});
</script>
@endpush
