@extends('layouts.admin')

@section('title', 'Tableau de bord')

@section('content')
<div class="section-header">
    <h1>
        @if(auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('admin'))
            <i class="fas fa-chart-line"></i> Tableau de bord Administrateur
        @elseif(auth()->user()->hasRole('artisan'))
            <i class="fas fa-palette"></i> Espace Artisan
        @elseif(auth()->user()->hasRole('vendor'))
            <i class="fas fa-store"></i> Espace Vendeur
        @else
            <i class="fas fa-user-circle"></i> Mon Espace Client
        @endif
    </h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active">
            <a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
        </div>
        <div class="breadcrumb-item">
            {{ now()->format('d/m/Y') }}
        </div>
    </div>
</div>

{{-- Message de bienvenue --}}
<div class="row">
    <div class="col-12">
        <div class="alert alert-light border-left-benin shadow-sm">
            <h5 class="alert-heading mb-2">
                <i class="fas fa-sun text-warning"></i> Bienvenue, {{ auth()->user()->name }} !
            </h5>
            <p class="mb-0 text-muted">
                @if(auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('admin'))
                    Voici un aperçu global de votre plateforme SAFEN.
                @elseif(auth()->user()->hasRole('artisan'))
                    Gérez vos produits artisanaux et suivez vos performances.
                @elseif(auth()->user()->hasRole('vendor'))
                    Gérez votre restaurant et vos plats du jour.
                @else
                    Découvrez nos artisans et produits authentiques du Bénin.
                @endif
            </p>
        </div>
    </div>
</div>

{{-- ==================== STATISTIQUES PAR RÔLE ==================== --}}
<div class="row">
    {{-- ==================== ADMIN / SUPER-ADMIN ==================== --}}
    @if(auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('admin'))

        {{-- Utilisateurs --}}
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-benin-green">
                    <i class="fas fa-users"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Utilisateurs</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($stats['total_users'] ?? 0) }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Produits --}}
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Produits</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($stats['total_products'] ?? 0) }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Artisans --}}
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon" style="background-color: var(--benin-yellow); color: #333;">
                    <i class="fas fa-palette"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Artisans</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($stats['total_artisans'] ?? 0) }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Commandes --}}
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Commandes</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($stats['total_orders'] ?? 0) }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Revenus totaux --}}
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Revenus totaux</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($stats['total_revenue'] ?? 0, 0, ',', ' ') }} <small>FCFA</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- En attente --}}
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>En attente</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($stats['pending_orders'] ?? 0) }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Vendeurs --}}
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-info">
                    <i class="fas fa-store"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Vendeurs</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($stats['total_vendors'] ?? 0) }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Événements --}}
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-secondary">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Événements</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($stats['total_events'] ?? 0) }}
                    </div>
                </div>
            </div>
        </div>

    {{-- ==================== ARTISAN ==================== --}}
    @elseif(auth()->user()->hasRole('artisan'))

        {{-- Mes produits --}}
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-benin-green">
                    <i class="fas fa-box-open"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Mes produits</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($stats['products'] ?? 0) }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Vues totales --}}
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-info">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Vues totales</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($stats['views'] ?? 0) }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Note moyenne --}}
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                    <i class="fas fa-star"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Note moyenne</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($stats['rating'] ?? 0, 1) }} / 5
                    </div>
                </div>
            </div>
        </div>

        {{-- Commandes --}}
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Commandes</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($stats['orders'] ?? 0) }}
                    </div>
                </div>
            </div>
        </div>

    {{-- ==================== VENDOR / RESTAURANT ==================== --}}
    @elseif(auth()->user()->hasRole('vendor'))

        {{-- Mes plats --}}
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                    <i class="fas fa-utensils"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Mes plats</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($stats['dishes'] ?? 0) }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Commandes --}}
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                    <i class="fas fa-receipt"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Commandes</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($stats['orders'] ?? 0) }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Note moyenne --}}
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                    <i class="fas fa-star"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Note moyenne</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($stats['rating'] ?? 0, 1) }} / 5
                    </div>
                </div>
            </div>
        </div>

        {{-- Revenus --}}
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-info">
                    <i class="fas fa-coins"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Revenus</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($stats['revenue'] ?? 0, 0, ',', ' ') }} <small>FCFA</small>
                    </div>
                </div>
            </div>
        </div>

    {{-- ==================== CLIENT ==================== --}}
    @else

        {{-- Mes commandes --}}
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-benin-green">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Mes commandes</h4>
                    </div>
                    <div class="card-body">
                        {{ auth()->user()->orders()->count() }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Mes favoris --}}
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                    <i class="fas fa-heart"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Mes favoris</h4>
                    </div>
                    <div class="card-body">
                        {{ auth()->user()->favorites()->count() }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Mes avis --}}
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                    <i class="fas fa-comment-dots"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Mes avis</h4>
                    </div>
                    <div class="card-body">
                        {{ auth()->user()->reviews()->count() }}
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

{{-- ==================== CONTENU SPÉCIFIQUE PAR RÔLE ==================== --}}

{{-- ADMIN --}}
@if(auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('admin'))

    <div class="row">
        {{-- Graphique des ventes --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-chart-area"></i> Évolution des ventes (30 derniers jours)</h4>
                    <div class="card-header-action">
                        <a href="{{ route('admin.analytics') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-chart-line"></i> Voir analytics
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="180"></canvas>
                </div>
            </div>
        </div>

        {{-- Actions rapides --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-bolt"></i> Actions rapides</h4>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('admin.artisans.create') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-plus-circle text-success"></i> Ajouter un artisan
                        </a>
                        <a href="{{ route('admin.products.create') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-plus-circle text-primary"></i> Ajouter un produit
                        </a>
                        <a href="{{ route('admin.vendors.create') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-plus-circle text-info"></i> Ajouter un vendeur
                        </a>
                        <a href="{{ route('admin.events.create') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-plus-circle text-warning"></i> Créer un événement
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-shopping-cart text-danger"></i> Gérer les commandes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Commandes récentes --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-shopping-cart"></i> Commandes récentes</h4>
                    <div class="card-header-action">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-primary btn-sm">Voir tout</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-md">
                            <thead>
                                <tr>
                                    <th>Commande</th>
                                    <th>Client</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders ?? [] as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}" class="font-weight-bold">
                                            #{{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td>{{ $order->user->name ?? 'N/A' }}</td>
                                    <td>{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</td>
                                    <td>
                                        @if($order->status == 'pending')
                                            <span class="badge badge-warning">En attente</span>
                                        @elseif($order->status == 'completed')
                                            <span class="badge badge-success">Complétée</span>
                                        @elseif($order->status == 'cancelled')
                                            <span class="badge badge-danger">Annulée</span>
                                        @else
                                            <span class="badge badge-info">{{ ucfirst($order->status) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-2"></i>
                                        <p>Aucune commande récente</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Produits populaires --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-fire"></i> Produits populaires</h4>
                    <div class="card-header-action">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-primary btn-sm">Voir tout</a>
                    </div>
                </div>
                <div class="card-body">
                    @forelse($popularProducts ?? [] as $product)
                    <div class="media mb-3 align-items-center">
                        <img src="{{ $product->images->first()->image_url ?? asset('images/default-product.jpg') }}"
                             alt="{{ $product->name }}"
                             class="mr-3 rounded"
                             style="width: 60px; height: 60px; object-fit: cover;">
                        <div class="media-body">
                            <h6 class="mt-0 mb-1">
                                <a href="{{ route('admin.products.show', $product) }}">
                                    {{ Str::limit($product->name, 35) }}
                                </a>
                            </h6>
                            <div class="text-small text-muted">
                                <i class="fas fa-eye"></i> {{ $product->views ?? 0 }} vues •
                                <i class="fas fa-shopping-cart"></i> {{ $product->order_items_count ?? 0 }} ventes
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="font-weight-bold text-benin-green">
                                {{ number_format($product->price, 0, ',', ' ') }} FCFA
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-box-open fa-2x mb-2"></i>
                        <p>Aucun produit populaire</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Nouveaux utilisateurs --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-user-plus"></i> Nouveaux utilisateurs</h4>
                    <div class="card-header-action">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-sm">Voir tout</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-md">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Rôle</th>
                                    <th>Inscrit le</th>
                                    <th>Statut</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($newUsers ?? [] as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->hasRole('artisan'))
                                            <span class="badge badge-warning">Artisan</span>
                                        @elseif($user->hasRole('vendor'))
                                            <span class="badge badge-info">Vendeur</span>
                                        @else
                                            <span class="badge badge-secondary">Client</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($user->status == 'active')
                                            <span class="badge badge-success">Actif</span>
                                        @else
                                            <span class="badge badge-danger">Inactif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="fas fa-users fa-2x mb-2"></i>
                                        <p>Aucun nouvel utilisateur</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

{{-- ARTISAN --}}
@elseif(auth()->user()->hasRole('artisan'))

    <div class="row">
        {{-- Mes derniers produits --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-box"></i> Mes derniers produits ajoutés</h4>
                    <div class="card-header-action">
                        <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Ajouter un produit
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @forelse($recentProducts ?? [] as $product)
                    <div class="media mb-3 align-items-center">
                        <img src="{{ $product->images->first()->image_url ?? asset('images/default-product.jpg') }}"
                             alt="{{ $product->name }}"
                             class="mr-3 rounded"
                             style="width: 80px; height: 80px; object-fit: cover;">
                        <div class="media-body">
                            <h6 class="mt-0 mb-1">
                                <a href="{{ route('products.show', $product) }}">{{ $product->name }}</a>
                            </h6>
                            <div class="text-small text-muted mb-2">
                                <i class="fas fa-eye"></i> {{ $product->views ?? 0 }} vues •
                                <i class="fas fa-shopping-cart"></i> {{ $product->order_items_count ?? 0 }} ventes
                            </div>
                            <div class="font-weight-bold text-benin-green">
                                {{ number_format($product->price, 0, ',', ' ') }} FCFA
                            </div>
                        </div>
                        <div class="text-right">
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-box-open fa-3x mb-3"></i>
                        <p class="mb-3">Vous n'avez pas encore ajouté de produits</p>
                        <a href="{{ route('products.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Ajouter votre premier produit
                        </a>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Produits populaires --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-fire"></i> Mes produits populaires</h4>
                </div>
                <div class="card-body">
                    @forelse($popularProducts ?? [] as $product)
                    <div class="mb-3 pb-3 border-bottom">
                        <h6 class="mb-1">
                            <a href="{{ route('products.show', $product) }}">
                                {{ Str::limit($product->name, 30) }}
                            </a>
                        </h6>
                        <div class="text-small text-muted">
                            <i class="fas fa-eye text-info"></i> {{ $product->views ?? 0 }} vues
                        </div>
                        <div class="text-small text-muted">
                            <i class="fas fa-shopping-cart text-success"></i> {{ $product->order_items_count ?? 0 }} ventes
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-chart-line fa-2x mb-2"></i>
                        <p class="mb-0">Aucune statistique disponible</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Statistiques rapides --}}
            <div class="card mt-3">
                <div class="card-header">
                    <h4><i class="fas fa-chart-pie"></i> Vos statistiques</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Total ventes</span>
                            <strong>{{ number_format($stats['total_sales'] ?? 0) }}</strong>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Revenus totaux</span>
                            <strong class="text-benin-green">{{ number_format($stats['total_revenue'] ?? 0, 0, ',', ' ') }} FCFA</strong>
                        </div>
                    </div>
                    <div class="mb-0">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Note moyenne</span>
                            <strong>
                                <i class="fas fa-star text-warning"></i> {{ number_format($stats['rating'] ?? 0, 1) }}/5
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

{{-- VENDOR --}}
@elseif(auth()->user()->hasRole('vendor'))

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-utensils"></i> Mes plats récents</h4>
                    <div class="card-header-action">
                        <a href="{{ route('dishes.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Ajouter un plat
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(isset($recentDishes) && $recentDishes->isNotEmpty())
                        @foreach($recentDishes as $dish)
                        <div class="media mb-3 align-items-center">
                            <img src="{{ $dish->image_url ?? asset('images/default-dish.jpg') }}"
                                 alt="{{ $dish->name }}"
                                 class="mr-3 rounded"
                                 style="width: 80px; height: 80px; object-fit: cover;">
                            <div class="media-body">
                                <h6 class="mt-0 mb-1">{{ $dish->name }}</h6>
                                <div class="text-small text-muted">
                                    {{ Str::limit($dish->description, 60) }}
                                </div>
                                <div class="font-weight-bold text-benin-green mt-1">
                                    {{ number_format($dish->price, 0, ',', ' ') }} FCFA
                                </div>
                            </div>
                            <div class="text-right">
                                <a href="{{ route('dishes.edit', $dish) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    @else
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-utensils fa-3x mb-3"></i>
                        <p class="mb-3">Vous n'avez pas encore ajouté de plats</p>
                        <a href="{{ route('dishes.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Ajouter votre premier plat
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-chart-line"></i> Performances</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Commandes du mois</span>
                            <strong>{{ number_format($stats['monthly_orders'] ?? 0) }}</strong>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Revenus du mois</span>
                            <strong class="text-benin-green">{{ number_format($stats['monthly_revenue'] ?? 0, 0, ',', ' ') }} FCFA</strong>
                        </div>
                    </div>
                    <div class="mb-0">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Note moyenne</span>
                            <strong>
                                <i class="fas fa-star text-warning"></i> {{ number_format($stats['rating'] ?? 0, 1) }}/5
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

{{-- CLIENT --}}
@else

    <div class="row">
        {{-- Produits récemment consultés --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-history"></i> Produits récemment consultés</h4>
                </div>
                <div class="card-body">
                    @if(isset($recentViews) && $recentViews->isNotEmpty())
                        <div class="row">
                            @foreach($recentViews as $product)
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100 shadow-sm">
                                        <img src="{{ $product->images->first()->image_url ?? asset('images/default-product.jpg') }}"
                                             class="card-img-top"
                                             alt="{{ $product->name }}"
                                             style="height: 160px; object-fit: cover;">
                                        <div class="card-body">
                                            <h6 class="card-title mb-2">
                                                <a href="{{ route('products.show', $product) }}">
                                                    {{ Str::limit($product->name, 30) }}
                                                </a>
                                            </h6>
                                            <p class="text-success font-weight-bold mb-2">
                                                {{ number_format($product->price, 0, ',', ' ') }} FCFA
                                            </p>
                                            <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-benin-green btn-block">
                                                <i class="fas fa-eye"></i> Voir
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-search fa-3x mb-3"></i>
                            <p class="mb-3">Vous n'avez consulté aucun produit récemment</p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary">
                                <i class="fas fa-shopping-bag"></i> Découvrir nos produits
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sidebar client --}}
        <div class="col-lg-4">
            {{-- Mes favoris --}}
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-heart"></i> Mes favoris</h4>
                    <div class="card-header-action">
                        <a href="{{ route('favorites') }}" class="btn btn-primary btn-sm">Voir tout</a>
                    </div>
                </div>
                <div class="card-body">
                    @if(isset($favorites) && $favorites->isNotEmpty())
                        <ul class="list-unstyled">
                            @foreach($favorites->take(5) as $fav)
                                <li class="mb-2 pb-2 border-bottom">
                                    <a href="{{ $fav->favoritable ? route('products.show', $fav->favoritable) : '#' }}" class="d-flex align-items-center">
                                        <i class="fas fa-heart text-danger mr-2"></i>
                                        <span>{{ Str::limit($fav->favoritable->name ?? 'Produit', 30) }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        @if($favorites->count() > 5)
                            <a href="{{ route('favorites') }}" class="btn btn-sm btn-primary btn-block mt-2">
                                Voir tous ({{ $favorites->count() }})
                            </a>
                        @endif
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-heart-broken fa-2x mb-2"></i>
                            <p class="mb-0">Aucun favori pour le moment</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Mes dernières commandes --}}
            <div class="card mt-3">
                <div class="card-header">
                    <h4><i class="fas fa-shopping-cart"></i> Mes commandes</h4>
                    <div class="card-header-action">
                        <a href="{{ route('orders.index') }}" class="btn btn-primary btn-sm">Voir tout</a>
                    </div>
                </div>
                <div class="card-body">
                    @if(auth()->user()->orders()->count() > 0)
                        @foreach(auth()->user()->orders()->latest()->take(3)->get() as $order)
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">
                                        <a href="{{ route('orders.show', $order) }}">#{{ $order->order_number }}</a>
                                    </h6>
                                    <div class="text-small text-muted">
                                        {{ $order->created_at->format('d/m/Y') }}
                                    </div>
                                </div>
                                <div class="text-right">
                                    @if($order->status == 'pending')
                                        <span class="badge badge-warning">En attente</span>
                                    @elseif($order->status == 'completed')
                                        <span class="badge badge-success">Complétée</span>
                                    @else
                                        <span class="badge badge-info">{{ ucfirst($order->status) }}</span>
                                    @endif
                                    <div class="text-small font-weight-bold mt-1">
                                        {{ number_format($order->total_amount, 0, ',', ' ') }} FCFA
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                            <p class="mb-0">Aucune commande</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif

@endsection

{{-- ==================== STYLES PERSONNALISÉS ==================== --}}
@push('styles')
<style>
    .border-left-benin {
        border-left: 4px solid var(--benin-green);
    }

    .card-statistic-1 {
        transition: all 0.3s ease;
    }

    .card-statistic-1:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .btn-benin-green {
        background-color: var(--benin-green);
        border-color: var(--benin-green);
        color: white;
    }

    .btn-benin-green:hover {
        background-color: #007a5a;
        border-color: #007a5a;
        color: white;
    }
</style>
@endpush

{{-- ==================== SCRIPTS ==================== --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Graphique des ventes (seulement pour admin)
        @if((auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('admin')) && isset($salesChart))
        var salesData = @json($salesChart);

        var ctx = document.getElementById('salesChart');
        if (ctx) {
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: salesData.map(item => {
                        const date = new Date(item.date);
                        return date.toLocaleDateString('fr-FR', { day: '2-digit', month: 'short' });
                    }),
                    datasets: [{
                        label: 'Ventes (FCFA)',
                        data: salesData.map(item => item.total || 0),
                        backgroundColor: 'rgba(0, 150, 136, 0.1)',
                        borderColor: 'var(--benin-green)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'Commandes',
                        data: salesData.map(item => item.count || 0),
                        backgroundColor: 'rgba(252, 196, 25, 0.1)',
                        borderColor: 'var(--benin-yellow)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    var label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.datasetIndex === 0) {
                                        label += new Intl.NumberFormat('fr-FR').format(context.parsed.y) + ' FCFA';
                                    } else {
                                        label += context.parsed.y + ' commandes';
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('fr-FR').format(value);
                                }
                            }
                        }
                    }
                }
            });
        }
        @endif
    });
</script>
@endpush
