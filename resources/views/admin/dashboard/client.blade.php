@extends('layouts.admin')

@section('title', 'Mon Espace Client')

@section('content')
<div class="section-header">
    <h1>
        <i class="fas fa-user-circle"></i> Mon Espace Client
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
                Découvrez nos artisans et produits authentiques du Bénin.
            </p>
        </div>
    </div>
</div>

<div class="row">
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
</div>

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
@endsection
