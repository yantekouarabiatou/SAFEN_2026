@extends('layouts.admin')

@section('title', 'Espace Vendeur')

@section('content')
<div class="section-header">
    <h1>
        <i class="fas fa-store"></i> Espace Vendeur
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
                Gérez votre restaurant et vos plats du jour.
            </p>
        </div>
    </div>
</div>

<div class="row">
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
</div>

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
@endsection
