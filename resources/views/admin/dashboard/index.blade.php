@extends('layouts.admin')

@section('title', 'Tableau de bord')

@section('content')
<div class="section-header">
    <h1>
        @if(auth()->user()->hasRole('admin'))
            Tableau de bord Administrateur
        @elseif(auth()->user()->hasRole('artisan'))
            Espace Artisan
        @elseif(auth()->user()->hasRole('vendor'))
            Espace Vendeur
        @else
            Mon Espace Client
        @endif
    </h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active">
            <a href="{{ route('dashboard') }}">Dashboard</a>
        </div>
    </div>
</div>

<div class="row">
    {{-- ==================== ADMIN ==================== --}}
    @if(auth()->user()->hasRole('admin'))
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-2 card-border-success">
                <div class="card-icon bg-benin-green">
                    <i class="fas fa-users"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Utilisateurs</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($stats['total_users']) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-2 card-border-primary">
                <div class="card-icon bg-info">
                    <i class="fas fa-store"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Produits</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($stats['total_products']) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-2 card-border-warning">
                <div class="card-icon" style="background-color: var(--benin-yellow); color: #333;">
                    <i class="fas fa-paint-brush"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Artisans</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($stats['total_artisans']) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-2 card-border-danger">
                <div class="card-icon bg-danger">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Commandes</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($stats['total_orders']) }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenus + commandes en attente -->
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-2">
                <div class="card-icon bg-success">
                    <i class="fas fa-coins"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Revenus</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($stats['total_revenue'], 0, ',', ' ') }} FCFA
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-2">
                <div class="card-icon bg-warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>En attente</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($stats['pending_orders']) }}
                    </div>
                </div>
            </div>
        </div>

    {{-- ==================== ARTISAN ==================== --}}
    @elseif(auth()->user()->hasRole('artisan'))
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-2 card-border-success">
                <div class="card-icon bg-benin-green">
                    <i class="fas fa-box"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Mes produits</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($stats['products']) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-2">
                <div class="card-icon bg-info">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Vues totales</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($stats['views']) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-2">
                <div class="card-icon bg-warning">
                    <i class="fas fa-star"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Note moyenne</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($stats['rating'], 1) }} / 5
                    </div>
                </div>
            </div>
        </div>

    {{-- ==================== VENDOR / RESTAURANT ==================== --}}
    @elseif(auth()->user()->hasRole('vendor'))
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-2 card-border-primary">
                <div class="card-icon bg-info">
                    <i class="fas fa-utensils"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Mes plats</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($stats['dishes']) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-2">
                <div class="card-icon bg-success">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Commandes</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($stats['orders']) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-2">
                <div class="card-icon bg-warning">
                    <i class="fas fa-star"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Note moyenne</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($stats['rating'], 1) }} / 5
                    </div>
                </div>
            </div>
        </div>

    {{-- ==================== CLIENT ==================== --}}
    @else
        <div class="col-lg-6 col-md-12">
            <div class="card card-statistic-2 card-border-success">
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

        <div class="col-lg-6 col-md-12">
            <div class="card card-statistic-2">
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
    @endif
</div>

{{-- Contenu spécifique par rôle --}}
@if(auth()->user()->hasRole('admin'))
    <!-- Graphiques + Commandes récentes + Nouveaux utilisateurs -->
    @include('admin.dashboard.partials.admin-content', ['stats' => $stats, 'salesChart' => $salesChart, 'newUsers' => $newUsers, 'recentOrders' => $recentOrders, 'popularProducts' => $popularProducts])

@elseif(auth()->user()->hasRole('artisan'))
    <!-- Produits récents + Produits populaires -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4>Mes derniers produits ajoutés</h4>
                </div>
                <div class="card-body">
                    @forelse($recentProducts as $product)
                        <div class="mb-3">
                            <strong>{{ $product->name }}</strong>
                            <div class="text-small text-muted">
                                {{ number_format($product->price, 0, ',', ' ') }} FCFA • {{ $product->views }} vues
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">Aucun produit ajouté récemment</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4>Produits les plus populaires</h4>
                </div>
                <div class="card-body">
                    @forelse($popularProducts as $product)
                        <div class="mb-3">
                            <strong>{{ $product->name }}</strong>
                            <div class="text-small text-muted">
                                {{ $product->views }} vues • {{ $product->order_items_count ?? 0 }} ventes
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">Aucun produit populaire pour le moment</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

@elseif(auth()->user()->hasRole('vendor'))
    <!-- Contenu spécifique vendor (à compléter selon tes besoins) -->
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Espace vendeur en cours de personnalisation...
            </div>
        </div>
    </div>

@else
    <!-- Contenu client -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4>Produits récemment consultés</h4>
                </div>
                <div class="card-body">
                    @if($recentViews->isNotEmpty())
                        <div class="row">
                            @foreach($recentViews as $product)
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100">
                                        <img src="{{ $product->images->first()->image_url ?? asset('images/default-product.jpg') }}"
                                             class="card-img-top" alt="{{ $product->name }}" style="height: 140px; object-fit: cover;">
                                        <div class="card-body">
                                            <h6 class="card-title">{{ Str::limit($product->name, 30) }}</h6>
                                            <p class="text-success fw-bold">
                                                {{ number_format($product->price, 0, ',', ' ') }} FCFA
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Aucun produit consulté récemment</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4>Mes favoris</h4>
                </div>
                <div class="card-body">
                    @if($favorites->isNotEmpty())
                        <ul class="list-unstyled">
                            @foreach($favorites->take(5) as $fav)
                                <li class="mb-2">
                                    <a href="{{ $fav->favoritable->url ?? '#' }}">
                                        {{ Str::limit($fav->favoritable->name ?? 'Produit', 35) }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        @if($favorites->count() > 5)
                            <a href="{{ route('favorites') }}" class="btn btn-sm btn-primary mt-2">
                                Voir tous ({{ $favorites->count() }})
                            </a>
                        @endif
                    @else
                        <p class="text-muted">Vous n'avez pas encore de favoris</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Graphique des ventes (seulement pour admin)
        @if(auth()->user()->hasRole('admin') && isset($salesChart))
        var salesData = @json($salesChart);

        var options = {
            chart: {
                type: 'area',
                height: 320,
                toolbar: { show: false }
            },
            series: [{
                name: 'Ventes (FCFA)',
                data: salesData.map(item => item.total || 0)
            }, {
                name: 'Commandes',
                data: salesData.map(item => item.count || 0)
            }],
            xaxis: {
                categories: salesData.map(item => item.date),
                labels: {
                    formatter: function(value) {
                        if (!value) return '';
                        const date = new Date(value);
                        return date.toLocaleDateString('fr-FR', { day: '2-digit', month: 'short' });
                    }
                }
            },
            yaxis: [{
                title: { text: 'Ventes (FCFA)' }
            }, {
                opposite: true,
                title: { text: 'Commandes' }
            }],
            colors: ['var(--benin-green)', 'var(--benin-yellow)'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.2,
                }
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            tooltip: {
                y: {
                    formatter: function(value, { seriesIndex }) {
                        if (seriesIndex === 0) {
                            return new Intl.NumberFormat('fr-FR').format(value) + ' FCFA';
                        }
                        return value + ' commandes';
                    }
                }
            }
        };

        var chart = new ApexCharts(document.getElementById('salesChart'), options);
        chart.render();
        @endif
    });
</script>
@endpush
