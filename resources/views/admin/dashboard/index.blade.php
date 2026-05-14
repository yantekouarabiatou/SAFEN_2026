@extends('layouts.admin')

@section('title', 'Tableau de bord')

@push('styles')
<style>
/* ══════════════════════════════════════════════════════
   DASHBOARD TOTCHÉMÈGNON — Styles
   Palette : Bénin vert #008751 · or #FCD116 · rouge #E8112D
══════════════════════════════════════════════════════ */

/* Variables & base */
:root {
    --g-green: #008751;
    --g-green-dk: #005c38;
    --g-gold:  #FCD116;
    --g-red:   #E8112D;
    --g-dark:  #1a1d23;
    --g-surface: #f0f2f8;
    --g-card:  #ffffff;
    --g-border: rgba(0,0,0,.07);
    --radius: 16px;
    --shadow: 0 4px 24px rgba(0,0,0,.08);
    --shadow-hover: 0 8px 32px rgba(0,0,0,.13);
}

body { background: var(--g-surface); }

/* ── Welcome banner ──────────────────────────────── */
.dash-welcome {
    background: linear-gradient(135deg, var(--g-green-dk) 0%, var(--g-green) 55%, #00a862 100%);
    border-radius: var(--radius);
    padding: 32px 36px;
    color: #fff;
    position: relative;
    overflow: hidden;
    margin-bottom: 28px;
}
.dash-welcome::before {
    content: '';
    position: absolute;
    top: -40px; right: -40px;
    width: 240px; height: 240px;
    border-radius: 50%;
    background: rgba(255,255,255,.06);
}
.dash-welcome::after {
    content: '';
    position: absolute;
    bottom: -60px; right: 80px;
    width: 160px; height: 160px;
    border-radius: 50%;
    background: rgba(252,209,22,.12);
}
.dash-welcome-title {
    font-family: 'Montserrat', sans-serif;
    font-size: 1.7rem;
    font-weight: 800;
    margin: 0 0 6px;
    letter-spacing: -.3px;
}
.dash-welcome-sub {
    font-size: .92rem;
    opacity: .82;
    margin: 0;
}
.dash-welcome-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.25);
    border-radius: 20px;
    padding: 5px 14px;
    font-size: .78rem;
    font-weight: 600;
    letter-spacing: .5px;
    text-transform: uppercase;
    margin-bottom: 14px;
}
.dash-welcome-badge .dot {
    width: 7px; height: 7px;
    background: var(--g-gold);
    border-radius: 50%;
    animation: pulse-dot 1.8s ease-in-out infinite;
}
@keyframes pulse-dot {
    0%,100% { opacity:1; transform:scale(1); }
    50% { opacity:.5; transform:scale(.7); }
}
.dash-welcome-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 22px;
    position: relative; z-index: 1;
}
.btn-welcome {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 10px 20px;
    border-radius: 10px;
    font-size: .85rem;
    font-weight: 600;
    text-decoration: none;
    transition: all .25s;
}
.btn-welcome-primary {
    background: #fff;
    color: var(--g-green-dk);
}
.btn-welcome-primary:hover { background: var(--g-gold); color: #1a1d23; }
.btn-welcome-ghost {
    background: rgba(255,255,255,.15);
    color: #fff;
    border: 1px solid rgba(255,255,255,.3);
}
.btn-welcome-ghost:hover { background: rgba(255,255,255,.25); color: #fff; }

/* ── Cartes KPI ──────────────────────────────────── */
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 18px;
    margin-bottom: 28px;
}
@media(max-width:1200px){ .kpi-grid{ grid-template-columns: repeat(3,1fr); } }
@media(max-width:768px){  .kpi-grid{ grid-template-columns: repeat(2,1fr); } }
@media(max-width:480px){  .kpi-grid{ grid-template-columns: 1fr; } }

.kpi-card {
    background: var(--g-card);
    border-radius: var(--radius);
    padding: 22px 20px;
    box-shadow: var(--shadow);
    border: 1px solid var(--g-border);
    position: relative;
    overflow: hidden;
    transition: transform .25s, box-shadow .25s;
    cursor: default;
}
.kpi-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-hover);
}
.kpi-card::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 3px;
    border-radius: 0 0 var(--radius) var(--radius);
}
.kpi-card.kpi-green::after  { background: var(--g-green); }
.kpi-card.kpi-gold::after   { background: var(--g-gold); }
.kpi-card.kpi-red::after    { background: var(--g-red); }
.kpi-card.kpi-blue::after   { background: #3b82f6; }
.kpi-card.kpi-purple::after { background: #8b5cf6; }

.kpi-icon-wrap {
    width: 52px; height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 16px;
    font-size: 22px;
}
.kpi-green  .kpi-icon-wrap { background: rgba(0,135,81,.12); color: var(--g-green); }
.kpi-gold   .kpi-icon-wrap { background: rgba(252,209,22,.18); color: #b8960a; }
.kpi-red    .kpi-icon-wrap { background: rgba(232,17,45,.1); color: var(--g-red); }
.kpi-blue   .kpi-icon-wrap { background: rgba(59,130,246,.12); color: #3b82f6; }
.kpi-purple .kpi-icon-wrap { background: rgba(139,92,246,.12); color: #8b5cf6; }

.kpi-value {
    font-family: 'Montserrat', sans-serif;
    font-size: 2rem;
    font-weight: 800;
    color: #1a1d23;
    line-height: 1;
    margin-bottom: 5px;
}
.kpi-label {
    font-size: .8rem;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: .6px;
}
.kpi-trend {
    position: absolute;
    top: 16px; right: 16px;
    font-size: .72rem;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 20px;
}
.trend-up   { background: #d1fae5; color: #065f46; }
.trend-down { background: #fee2e2; color: #991b1b; }
.trend-neu  { background: #f3f4f6; color: #6b7280; }

/* ── Section headers ─────────────────────────────── */
.dash-section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
}
.dash-section-title {
    font-family: 'Montserrat', sans-serif;
    font-size: 1rem;
    font-weight: 700;
    color: #1a1d23;
    display: flex;
    align-items: center;
    gap: 9px;
}
.dash-section-title .section-dot {
    width: 8px; height: 8px;
    background: var(--g-green);
    border-radius: 50%;
}
.btn-view-all {
    font-size: .78rem;
    font-weight: 600;
    color: var(--g-green);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 14px;
    border-radius: 8px;
    border: 1px solid rgba(0,135,81,.25);
    transition: all .2s;
}
.btn-view-all:hover {
    background: var(--g-green);
    color: #fff;
    border-color: var(--g-green);
}

/* ── Carte générique ─────────────────────────────── */
.dash-card {
    background: var(--g-card);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    border: 1px solid var(--g-border);
    overflow: hidden;
}
.dash-card-body { padding: 22px; }
.dash-card-body-sm { padding: 16px 20px; }

/* ── Tableau commandes ───────────────────────────── */
.orders-table {
    width: 100%;
    border-collapse: collapse;
    font-size: .855rem;
}
.orders-table thead th {
    background: #f8fafc;
    padding: 11px 16px;
    font-size: .72rem;
    font-weight: 700;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: .7px;
    border-bottom: 1px solid #e5e7eb;
    white-space: nowrap;
}
.orders-table tbody td {
    padding: 12px 16px;
    border-bottom: 1px solid #f3f4f6;
    color: #374151;
    vertical-align: middle;
}
.orders-table tbody tr:last-child td { border-bottom: none; }
.orders-table tbody tr:hover td { background: #f9fafb; }
.order-num {
    font-weight: 700;
    color: var(--g-green);
    font-family: 'Montserrat', sans-serif;
}
.client-avatar-sm {
    width: 30px; height: 30px;
    border-radius: 50%;
    background: var(--g-green);
    color: #fff;
    font-size: .7rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-right: 8px;
    flex-shrink: 0;
}

/* ── Status badges ───────────────────────────────── */
.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: .72rem;
    font-weight: 700;
    white-space: nowrap;
}
.status-pill::before {
    content:'';
    width: 6px; height: 6px;
    border-radius: 50%;
    background: currentColor;
}
.status-pending    { background:#fef3c7; color:#92400e; }
.status-processing { background:#dbeafe; color:#1e40af; }
.status-shipped    { background:#ede9fe; color:#6d28d9; }
.status-completed,
.status-delivered  { background:#d1fae5; color:#065f46; }
.status-cancelled  { background:#fee2e2; color:#991b1b; }

/* ── Liste utilisateurs ──────────────────────────── */
.user-list-item {
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 12px 0;
    border-bottom: 1px solid #f3f4f6;
}
.user-list-item:last-child { border-bottom: none; }
.user-avatar {
    width: 42px; height: 42px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
    border: 2px solid #e5e7eb;
}
.user-avatar-fallback {
    width: 42px; height: 42px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: .85rem;
    color: #fff;
    flex-shrink: 0;
}
.user-list-name {
    font-weight: 600;
    color: #1a1d23;
    font-size: .875rem;
    line-height: 1.2;
}
.user-list-email {
    font-size: .75rem;
    color: #9ca3af;
    margin-top: 2px;
}
.user-list-time {
    margin-left: auto;
    font-size: .72rem;
    color: #9ca3af;
    white-space: nowrap;
}

/* ── Produits populaires ─────────────────────────── */
.popular-product {
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 11px 0;
    border-bottom: 1px solid #f3f4f6;
}
.popular-product:last-child { border-bottom: none; }
.popular-product-rank {
    width: 28px; height: 28px;
    border-radius: 8px;
    background: #f3f4f6;
    color: #6b7280;
    font-size: .72rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.popular-product-rank.rank-1 { background: var(--g-gold); color: #78350f; }
.popular-product-rank.rank-2 { background: #e5e7eb; color: #374151; }
.popular-product-rank.rank-3 { background: #fee0d0; color: #c2410c; }
.popular-product-info { flex: 1; min-width: 0; }
.popular-product-name {
    font-size: .83rem;
    font-weight: 600;
    color: #1a1d23;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.popular-product-price {
    font-size: .72rem;
    color: #9ca3af;
    margin-top: 2px;
}
.popular-product-sales {
    font-size: .78rem;
    font-weight: 700;
    color: var(--g-green);
    white-space: nowrap;
}

/* ── Quick actions ───────────────────────────────── */
.quick-actions-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}
.quick-action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 18px 12px;
    border-radius: 12px;
    border: 1.5px solid #e5e7eb;
    background: #fff;
    cursor: pointer;
    transition: all .22s;
    text-decoration: none;
    color: #374151;
    font-size: .78rem;
    font-weight: 600;
    text-align: center;
}
.quick-action-btn:hover {
    border-color: var(--g-green);
    background: rgba(0,135,81,.05);
    color: var(--g-green);
}
.quick-action-btn i {
    font-size: 1.5rem;
    width: auto; height: auto;
}

/* ── Activité récente ────────────────────────────── */
.activity-item {
    display: flex;
    gap: 13px;
    padding: 11px 0;
    border-bottom: 1px solid #f3f4f6;
    align-items: flex-start;
}
.activity-item:last-child { border-bottom: none; }
.activity-icon {
    width: 36px; height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .9rem;
    flex-shrink: 0;
    margin-top: 2px;
}
.activity-text {
    flex: 1;
    font-size: .82rem;
    color: #374151;
    line-height: 1.4;
}
.activity-time {
    font-size: .7rem;
    color: #9ca3af;
    margin-top: 3px;
}

/* ── Messages alerte ─────────────────────────────── */
.alert-contacts {
    background: linear-gradient(135deg, rgba(232,17,45,.07), rgba(232,17,45,.03));
    border: 1px solid rgba(232,17,45,.18);
    border-left: 4px solid var(--g-red);
    border-radius: var(--radius);
    padding: 16px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}

/* ── Chart wrapper ───────────────────────────────── */
#salesChart { min-height: 280px; }

/* ── Section page header ─────────────────────────── */
.section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
}
.section-header h1 {
    font-family: 'Montserrat', sans-serif;
    font-size: 1.4rem;
    font-weight: 800;
    color: #1a1d23;
    margin: 0;
}
.section-header-breadcrumb {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: .8rem;
    color: #9ca3af;
}
.section-header-breadcrumb .breadcrumb-item a { color: var(--g-green); text-decoration: none; font-weight: 600; }
</style>
@endpush

@section('content')

{{-- ── Section Header ────────────────────────────── --}}
<div class="section-header">
    <div>
        <h1>Tableau de bord</h1>
        <p class="text-muted mb-0" style="font-size:.82rem;">
            <i class="bi bi-calendar3 me-1"></i>
            {{ now()->isoFormat('dddd D MMMM YYYY') }}
        </p>
    </div>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        </div>
        <span>/</span>
        <div>Vue d'ensemble</div>
    </div>
</div>

{{-- ── Welcome Banner ─────────────────────────────── --}}
@php $adminUser = auth()->user(); @endphp
<div class="dash-welcome">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <div class="dash-welcome-badge">
                <span class="dot"></span>
                Administration TOTCHÉMÈGNON
            </div>
            <h2 class="dash-welcome-title">
                Bonjour, {{ $adminUser->prenom ?? $adminUser->name ?? 'Administrateur' }} 👋
            </h2>
            <p class="dash-welcome-sub">
                Bienvenue dans votre espace de pilotage. Voici un aperçu de l'activité de la plateforme aujourd'hui.
            </p>
            <div class="dash-welcome-actions">
                <a href="{{ route('admin.artisans.index') }}" class="btn-welcome btn-welcome-primary">
                    <i class="bi bi-pen-fill"></i> Gérer les artisans
                </a>
                <a href="{{ route('admin.orders.index') }}" class="btn-welcome btn-welcome-ghost">
                    <i class="bi bi-bag-check"></i> Voir les commandes
                </a>
                <a href="{{ url('/') }}" target="_blank" class="btn-welcome btn-welcome-ghost">
                    <i class="bi bi-globe2"></i> Site public
                </a>
            </div>
        </div>
        <div class="col-lg-4 d-none d-lg-flex justify-content-end align-items-center">
            {{-- Logo décoratif grand format --}}
            <svg width="140" height="140" viewBox="0 0 140 140" xmlns="http://www.w3.org/2000/svg" opacity=".18">
                <circle cx="70" cy="70" r="68" fill="none" stroke="#FCD116" stroke-width="2" stroke-dasharray="6 4"/>
                <circle cx="70" cy="70" r="56" fill="none" stroke="white" stroke-width="1" stroke-dasharray="3 6"/>
                <text x="70" y="92" font-family="Montserrat,Arial" font-weight="900" font-size="72" text-anchor="middle" fill="white">T</text>
            </svg>
        </div>
    </div>
</div>

{{-- ── Alerte nouveaux messages ─────────────────── --}}
@if($stats['new_contacts'] > 0)
<div class="alert-contacts">
    <div class="d-flex align-items-center gap-3">
        <div style="width:36px;height:36px;background:rgba(232,17,45,.12);border-radius:10px;display:flex;align-items:center;justify-content:center;color:var(--g-red);">
            <i class="bi bi-envelope-exclamation-fill"></i>
        </div>
        <div>
            <strong style="color:#991b1b;">{{ $stats['new_contacts'] }} nouveau{{ $stats['new_contacts'] > 1 ? 'x' : '' }} message{{ $stats['new_contacts'] > 1 ? 's' : '' }}</strong>
            <div style="font-size:.78rem;color:#6b7280;">En attente de réponse de votre part</div>
        </div>
    </div>
    <a href="{{ route('admin.contacts.index') }}" class="btn-view-all" style="border-color:rgba(232,17,45,.3);color:var(--g-red);">
        Voir <i class="bi bi-arrow-right"></i>
    </a>
</div>
@endif

{{-- ── KPI Cards ───────────────────────────────────── --}}
<div class="kpi-grid">

    {{-- Utilisateurs --}}
    <div class="kpi-card kpi-green">
        <span class="kpi-trend trend-up">Actif</span>
        <div class="kpi-icon-wrap">
            <i class="bi bi-people-fill"></i>
        </div>
        <div class="kpi-value">{{ number_format($stats['total_users']) }}</div>
        <div class="kpi-label">Utilisateurs</div>
    </div>

    {{-- Artisans --}}
    <div class="kpi-card kpi-gold">
        <span class="kpi-trend trend-neu">Inscrits</span>
        <div class="kpi-icon-wrap">
            <i class="bi bi-tools"></i>
        </div>
        <div class="kpi-value">{{ number_format($stats['total_artisans']) }}</div>
        <div class="kpi-label">Artisans</div>
    </div>

    {{-- Produits --}}
    <div class="kpi-card kpi-blue">
        <span class="kpi-trend trend-up">En ligne</span>
        <div class="kpi-icon-wrap">
            <i class="bi bi-bag-fill"></i>
        </div>
        <div class="kpi-value">{{ number_format($stats['total_products']) }}</div>
        <div class="kpi-label">Produits</div>
    </div>

    {{-- Commandes --}}
    <div class="kpi-card kpi-purple">
        @if($stats['pending_orders'] > 0)
            <span class="kpi-trend trend-down">{{ $stats['pending_orders'] }} en attente</span>
        @endif
        <div class="kpi-icon-wrap">
            <i class="bi bi-cart-check-fill"></i>
        </div>
        <div class="kpi-value">{{ number_format($stats['total_orders']) }}</div>
        <div class="kpi-label">Commandes</div>
    </div>

    {{-- Revenus --}}
    <div class="kpi-card kpi-red">
        <span class="kpi-trend trend-up">FCFA</span>
        <div class="kpi-icon-wrap">
            <i class="bi bi-currency-exchange"></i>
        </div>
        <div class="kpi-value" style="font-size:1.35rem;">{{ number_format($stats['total_revenue'], 0, ',', '\u{202F}') }}</div>
        <div class="kpi-label">Revenus totaux</div>
    </div>

</div>

{{-- ── Ligne 2 : Graphique + Actions rapides ──────── --}}
<div class="row g-4 mb-4">

    {{-- Graphique des ventes --}}
    <div class="col-lg-8">
        <div class="dash-card h-100">
            <div class="dash-card-body">
                <div class="dash-section-header">
                    <div class="dash-section-title">
                        <span class="section-dot"></span>
                        Ventes — 7 derniers jours
                    </div>
                    <div class="d-flex gap-3 align-items-center">
                        <span style="font-size:.72rem;color:#6b7280;">
                            <span style="display:inline-block;width:10px;height:3px;background:var(--g-green);border-radius:2px;vertical-align:middle;margin-right:4px;"></span>Ventes (FCFA)
                        </span>
                        <span style="font-size:.72rem;color:#6b7280;">
                            <span style="display:inline-block;width:10px;height:3px;background:var(--g-gold);border-radius:2px;vertical-align:middle;margin-right:4px;"></span>Commandes
                        </span>
                    </div>
                </div>
                <div id="salesChart"></div>
            </div>
        </div>
    </div>

    {{-- Actions rapides + Stats secondaires --}}
    <div class="col-lg-4">
        <div class="dash-card mb-3">
            <div class="dash-card-body">
                <div class="dash-section-title mb-3">
                    <span class="section-dot" style="background:var(--g-gold);"></span>
                    Actions rapides
                </div>
                <div class="quick-actions-grid">
                    <a href="{{ route('admin.artisans.create') }}" class="quick-action-btn">
                        <i class="bi bi-person-plus-fill" style="color:var(--g-green);"></i>
                        Ajouter artisan
                    </a>
                    <a href="{{ route('admin.products.create') }}" class="quick-action-btn">
                        <i class="bi bi-plus-circle-fill" style="color:#3b82f6;"></i>
                        Nouveau produit
                    </a>
                    <a href="{{ route('admin.dishes.create') }}" class="quick-action-btn">
                        <i class="bi bi-egg-fried" style="color:#f59e0b;"></i>
                        Ajouter plat
                    </a>
                    <a href="{{ route('admin.contacts.index') }}" class="quick-action-btn">
                        <i class="bi bi-chat-dots-fill" style="color:var(--g-red);"></i>
                        Messages
                    </a>
                    <a href="{{ route('admin.events.create') }}" class="quick-action-btn">
                        <i class="bi bi-calendar-plus-fill" style="color:#8b5cf6;"></i>
                        Événement
                    </a>
                    <a href="{{ route('admin.analytics') }}" class="quick-action-btn">
                        <i class="bi bi-bar-chart-fill" style="color:#0ea5e9;"></i>
                        Analytics
                    </a>
                </div>
            </div>
        </div>

        {{-- Plats & Vendeurs mini-stats --}}
        <div class="dash-card">
            <div class="dash-card-body-sm">
                <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:32px;height:32px;background:rgba(245,158,11,.12);border-radius:9px;display:flex;align-items:center;justify-content:center;color:#f59e0b;font-size:.9rem;">
                            <i class="bi bi-egg-fried"></i>
                        </div>
                        <span style="font-size:.83rem;font-weight:600;color:#374151;">Plats référencés</span>
                    </div>
                    <span style="font-family:'Montserrat',sans-serif;font-weight:800;color:#1a1d23;">{{ number_format($stats['total_dishes']) }}</span>
                </div>
                <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:32px;height:32px;background:rgba(16,185,129,.12);border-radius:9px;display:flex;align-items:center;justify-content:center;color:#10b981;font-size:.9rem;">
                            <i class="bi bi-shop"></i>
                        </div>
                        <span style="font-size:.83rem;font-weight:600;color:#374151;">Vendeurs actifs</span>
                    </div>
                    <span style="font-family:'Montserrat',sans-serif;font-weight:800;color:#1a1d23;">{{ number_format($stats['total_vendors']) }}</span>
                </div>
                <div class="d-flex align-items-center justify-content-between py-2">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:32px;height:32px;background:rgba(232,17,45,.1);border-radius:9px;display:flex;align-items:center;justify-content:center;color:var(--g-red);font-size:.9rem;">
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <span style="font-size:.83rem;font-weight:600;color:#374151;">Avis publiés</span>
                    </div>
                    <span style="font-family:'Montserrat',sans-serif;font-weight:800;color:#1a1d23;">{{ number_format($stats['total_reviews'] ?? 0) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Ligne 3 : Commandes + Nouveaux users ───────── --}}
<div class="row g-4 mb-4">

    {{-- Commandes récentes --}}
    <div class="col-lg-7">
        <div class="dash-card">
            <div class="dash-card-body">
                <div class="dash-section-header">
                    <div class="dash-section-title">
                        <span class="section-dot"></span>
                        Commandes récentes
                    </div>
                    <a href="{{ route('admin.orders.index') }}" class="btn-view-all">
                        Voir toutes <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>N° Commande</th>
                            <th>Client</th>
                            <th>Total</th>
                            <th>Statut</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        @php
                            $statusMap = [
                                'pending'    => ['label'=>'En attente',  'class'=>'status-pending'],
                                'processing' => ['label'=>'En cours',    'class'=>'status-processing'],
                                'shipped'    => ['label'=>'Expédié',     'class'=>'status-shipped'],
                                'delivered'  => ['label'=>'Livré',       'class'=>'status-completed'],
                                'completed'  => ['label'=>'Complété',    'class'=>'status-completed'],
                                'cancelled'  => ['label'=>'Annulé',      'class'=>'status-cancelled'],
                            ];
                            $s = $statusMap[$order->status] ?? ['label'=>$order->status,'class'=>'status-pending'];
                            $initials = strtoupper(substr($order->user->name ?? 'C', 0, 1));
                            $avatarBg = '#' . substr(md5($order->user->email ?? 'x'), 0, 6);
                        @endphp
                        <tr>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="order-num" style="text-decoration:none;">
                                    #{{ $order->order_number ?? str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                                </a>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="client-avatar-sm" style="background:{{ $avatarBg }};">{{ $initials }}</span>
                                    <span style="font-size:.83rem;font-weight:500;">{{ $order->user->name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td style="font-weight:700;color:#1a1d23;font-family:'Montserrat',sans-serif;font-size:.82rem;">
                                {{ number_format($order->total_amount, 0, ',', ' ') }} <span style="font-weight:400;color:#9ca3af;font-size:.7rem;">FCFA</span>
                            </td>
                            <td><span class="status-pill {{ $s['class'] }}">{{ $s['label'] }}</span></td>
                            <td style="font-size:.78rem;color:#9ca3af;">{{ $order->created_at->format('d/m/Y') }}<br><span style="font-size:.7rem;">{{ $order->created_at->format('H:i') }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align:center;padding:32px;color:#9ca3af;font-size:.85rem;">
                                <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:8px;opacity:.4;"></i>
                                Aucune commande pour le moment
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Nouveaux utilisateurs --}}
    <div class="col-lg-5">
        <div class="dash-card h-100">
            <div class="dash-card-body">
                <div class="dash-section-header">
                    <div class="dash-section-title">
                        <span class="section-dot" style="background:#3b82f6;"></span>
                        Nouveaux membres
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="btn-view-all">
                        Voir tous <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                @forelse($newUsers as $newUser)
                @php
                    $uInitials = strtoupper(substr($newUser->name, 0, 1));
                    $uBg = ['#008751','#3b82f6','#8b5cf6','#f59e0b','#E8112D'][($newUser->id ?? 0) % 5];
                @endphp
                <div class="user-list-item">
                    @if($newUser->profile_photo_url ?? false)
                        <img src="{{ $newUser->profile_photo_url }}" class="user-avatar" alt="{{ $newUser->name }}">
                    @else
                        <div class="user-avatar-fallback" style="background:{{ $uBg }};">{{ $uInitials }}</div>
                    @endif
                    <div>
                        <div class="user-list-name">{{ $newUser->name }}</div>
                        <div class="user-list-email">{{ $newUser->email }}</div>
                    </div>
                    <div class="user-list-time">{{ $newUser->created_at->diffForHumans() }}</div>
                </div>
                @empty
                <div class="text-center py-4" style="color:#9ca3af;font-size:.85rem;">
                    <i class="bi bi-person-x" style="font-size:2rem;display:block;margin-bottom:8px;opacity:.4;"></i>
                    Aucun nouvel utilisateur
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- ── Ligne 4 : Produits populaires + Activité ───── --}}
<div class="row g-4">

    {{-- Produits populaires --}}
    <div class="col-lg-6">
        <div class="dash-card">
            <div class="dash-card-body">
                <div class="dash-section-header">
                    <div class="dash-section-title">
                        <span class="section-dot" style="background:var(--g-gold);"></span>
                        Produits les plus vendus
                    </div>
                    <a href="{{ route('admin.products.index') }}" class="btn-view-all">
                        Voir tous <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                @forelse($popularProducts as $i => $product)
                <div class="popular-product">
                    <div class="popular-product-rank {{ $i === 0 ? 'rank-1' : ($i === 1 ? 'rank-2' : ($i === 2 ? 'rank-3' : '')) }}">
                        #{{ $i + 1 }}
                    </div>
                    <div class="popular-product-info">
                        <div class="popular-product-name">{{ $product->name }}</div>
                        <div class="popular-product-price">{{ number_format($product->price, 0, ',', ' ') }} FCFA</div>
                    </div>
                    <div class="popular-product-sales">
                        {{ $product->order_items_count }}
                        <span style="font-weight:400;color:#9ca3af;font-size:.7rem;"> ventes</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-4" style="color:#9ca3af;font-size:.85rem;">
                    <i class="bi bi-bag-x" style="font-size:2rem;display:block;margin-bottom:8px;opacity:.4;"></i>
                    Aucune donnée de vente
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Activité récente --}}
    <div class="col-lg-6">
        <div class="dash-card">
            <div class="dash-card-body">
                <div class="dash-section-header">
                    <div class="dash-section-title">
                        <span class="section-dot" style="background:#8b5cf6;"></span>
                        Activité récente
                    </div>
                </div>

                @php
                $activities = collect();
                foreach($newUsers->take(3) as $u) {
                    $activities->push(['type'=>'user','icon'=>'bi bi-person-check-fill','color'=>'rgba(59,130,246,.12)','iconColor'=>'#3b82f6','text'=>"<strong>{$u->name}</strong> s'est inscrit sur la plateforme",'time'=>$u->created_at->diffForHumans()]);
                }
                foreach($recentOrders->take(3) as $o) {
                    $activities->push(['type'=>'order','icon'=>'bi bi-bag-check-fill','color'=>'rgba(0,135,81,.12)','iconColor'=>'var(--g-green)','text'=>"Commande <strong>#".str_pad($o->id,4,'0',STR_PAD_LEFT)."</strong> passée par <strong>".($o->user->name??'Client')."</strong>",'time'=>$o->created_at->diffForHumans()]);
                }
                $activities = $activities->sortByDesc('time')->take(6);
                @endphp

                @forelse($activities as $act)
                <div class="activity-item">
                    <div class="activity-icon" style="background:{{ $act['color'] }};color:{{ $act['iconColor'] }};">
                        <i class="{{ $act['icon'] }}"></i>
                    </div>
                    <div>
                        <div class="activity-text">{!! $act['text'] !!}</div>
                        <div class="activity-time"><i class="bi bi-clock me-1"></i>{{ $act['time'] }}</div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4" style="color:#9ca3af;font-size:.85rem;">
                    <i class="bi bi-activity" style="font-size:2rem;display:block;margin-bottom:8px;opacity:.4;"></i>
                    Aucune activité récente
                </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    var salesData = @json($salesChart);

    var options = {
        chart: {
            type: 'area',
            height: 280,
            toolbar: { show: false },
            fontFamily: 'Montserrat, Open Sans, sans-serif',
            background: 'transparent',
            animations: { enabled: true, easing: 'easeinout', speed: 600 }
        },
        series: [
            { name: 'Ventes (FCFA)', data: salesData.map(i => i.total || 0) },
            { name: 'Commandes',     data: salesData.map(i => i.count || 0) }
        ],
        xaxis: {
            categories: salesData.map(i => i.date),
            labels: {
                formatter: function (v) {
                    if (!v) return '';
                    return new Date(v).toLocaleDateString('fr-FR', { day: '2-digit', month: '2-digit' });
                },
                style: { fontSize: '11px', colors: '#9ca3af' }
            },
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: [
            { title: { text: 'FCFA', style: { color: '#9ca3af', fontSize: '11px' } }, labels: { style: { colors: '#9ca3af', fontSize: '11px' } } },
            { opposite: true, title: { text: 'Cmd', style: { color: '#9ca3af', fontSize: '11px' } }, labels: { style: { colors: '#9ca3af', fontSize: '11px' } } }
        ],
        colors: ['#008751', '#FCD116'],
        fill: {
            type: 'gradient',
            gradient: { shadeIntensity: 1, opacityFrom: 0.45, opacityTo: 0.02, stops: [0, 100] }
        },
        stroke: { curve: 'smooth', width: [2.5, 2] },
        grid: { borderColor: '#f3f4f6', strokeDashArray: 4 },
        markers: { size: 4, colors: ['#008751', '#FCD116'], strokeColors: '#fff', strokeWidth: 2, hover: { size: 6 } },
        legend: { show: false },
        dataLabels: { enabled: false },
        tooltip: {
            shared: true,
            intersect: false,
            style: { fontSize: '12px', fontFamily: 'Montserrat' },
            y: {
                formatter: function (val, { seriesIndex }) {
                    if (seriesIndex === 0) return new Intl.NumberFormat('fr-FR').format(val) + ' FCFA';
                    return val + ' commande' + (val > 1 ? 's' : '');
                }
            }
        }
    };

    if (document.getElementById('salesChart')) {
        new ApexCharts(document.getElementById('salesChart'), options).render();
    }
});
</script>
@endpush
