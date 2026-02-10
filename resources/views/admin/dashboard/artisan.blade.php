@extends('layouts.admin')

@section('title', 'Espace Artisan')

@section('content')
<div class="section-header">
    <h1>
        <i class="fas fa-palette"></i> Espace Artisan
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

<div class="row">
    <div class="col-12">
        <div class="alert alert-light border-left-benin shadow-sm">
            <h5 class="alert-heading mb-2">
                <i class="fas fa-sun text-warning"></i> Bienvenue, {{ auth()->user()->name }} !
            </h5>
            <p class="mb-0 text-muted">
                Gérez vos produits artisanaux et suivez vos performances.
            </p>
        </div>
    </div>
</div>

<div class="row">
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
</div>

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
@endsection
