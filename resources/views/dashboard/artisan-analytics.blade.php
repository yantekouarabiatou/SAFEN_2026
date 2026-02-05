@extends('layouts.admin')

@section('title', 'Analytics')

@section('content')
<div class="section-header">
    <h1>Analytics & Statistiques</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard.artisan') }}">Dashboard</a></div>
        <div class="breadcrumb-item">Analytics</div>
    </div>
</div>

<div class="section-body">
    <!-- Statistiques principales -->
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Total vues</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($totalViews ?? 0) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                    <i class="fas fa-box"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Total produits</h4>
                    </div>
                    <div class="card-body">
                        {{ $totalProducts ?? 0 }}
                    </div>
                </div>
            </div>
        </div>
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
                        {{ number_format($avgRating ?? 0, 1) }} / 5
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                    <i class="fas fa-heart"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Favoris</h4>
                    </div>
                    <div class="card-body">
                        {{ auth()->user()->artisan?->favorites()->count() ?? 0 }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4>Évolution des vues (30 derniers jours)</h4>
                    <div class="card-header-action">
                        <div class="btn-group">
                            <button class="btn btn-primary">30 jours</button>
                            <button class="btn btn-light">7 jours</button>
                            <button class="btn btn-light">Aujourd'hui</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="viewsChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4>Répartition par catégorie</h4>
                </div>
                <div class="card-body">
                    <canvas id="categoryChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Produits les plus vus -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4>Produits les plus vus</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>Vues</th>
                                    <th>Prix</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(auth()->user()->artisan?->products()->orderBy('views', 'desc')->take(5)->get() ?? [] as $product)
                                <tr>
                                    <td>
                                        <img src="{{ $product->images->first()->image_url ?? asset('images/default-product.jpg') }}" 
                                             alt="{{ $product->name }}" 
                                             width="40" 
                                             class="rounded mr-2">
                                        {{ Str::limit($product->name, 30) }}
                                    </td>
                                    <td>
                                        <span class="badge badge-primary">
                                            <i class="fas fa-eye"></i> {{ $product->views ?? 0 }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($product->price, 0, ',', ' ') }} FCFA</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Aucun produit</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4>Produits les mieux notés</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>Note</th>
                                    <th>Avis</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(auth()->user()->artisan?->products()->withCount('reviews')->orderBy('reviews_count', 'desc')->take(5)->get() ?? [] as $product)
                                <tr>
                                    <td>
                                        <img src="{{ $product->images->first()->image_url ?? asset('images/default-product.jpg') }}" 
                                             alt="{{ $product->name }}" 
                                             width="40" 
                                             class="rounded mr-2">
                                        {{ Str::limit($product->name, 30) }}
                                    </td>
                                    <td>
                                        <span class="text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= ($product->rating_avg ?? 0))
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $product->reviews_count ?? 0 }} avis</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Aucun produit</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance mensuelle -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Performance mensuelle</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-6">
                            <div class="text-center mb-3">
                                <div class="h1 mb-2 text-primary">
                                    <i class="fas fa-eye"></i>
                                </div>
                                <h5>Vues ce mois</h5>
                                <h3>{{ number_format(auth()->user()->artisan?->views ?? 0) }}</h3>
                                <span class="text-success">
                                    <i class="fas fa-arrow-up"></i> +15%
                                </span>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="text-center mb-3">
                                <div class="h1 mb-2 text-success">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <h5>Commandes</h5>
                                <h3>{{ auth()->user()->artisan?->orders()->count() ?? 0 }}</h3>
                                <span class="text-success">
                                    <i class="fas fa-arrow-up"></i> +8%
                                </span>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="text-center mb-3">
                                <div class="h1 mb-2 text-warning">
                                    <i class="fas fa-comments"></i>
                                </div>
                                <h5>Nouveaux avis</h5>
                                <h3>{{ auth()->user()->artisan?->reviews()->whereMonth('created_at', now()->month)->count() ?? 0 }}</h3>
                                <span class="text-success">
                                    <i class="fas fa-arrow-up"></i> +22%
                                </span>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="text-center mb-3">
                                <div class="h1 mb-2 text-danger">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <h5>Nouveaux favoris</h5>
                                <h3>{{ auth()->user()->artisan?->favorites()->whereMonth('created_at', now()->month)->count() ?? 0 }}</h3>
                                <span class="text-success">
                                    <i class="fas fa-arrow-up"></i> +12%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Graphique des vues
var ctx1 = document.getElementById('viewsChart');
if (ctx1) {
    new Chart(ctx1.getContext('2d'), {
        type: 'line',
        data: {
            labels: ['J-30', 'J-25', 'J-20', 'J-15', 'J-10', 'J-5', "Aujourd'hui"],
            datasets: [{
                label: 'Vues profil',
                data: [120, 150, 180, 160, 200, 220, 250],
                borderColor: '#6777ef',
                backgroundColor: 'rgba(103, 119, 239, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Vues produits',
                data: [80, 100, 120, 110, 140, 160, 180],
                borderColor: '#fc544b',
                backgroundColor: 'rgba(252, 84, 75, 0.1)',
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
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// Graphique par catégorie
var ctx2 = document.getElementById('categoryChart');
if (ctx2) {
    new Chart(ctx2.getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Sculpture', 'Tissage', 'Poterie', 'Bijoux', 'Autre'],
            datasets: [{
                data: [30, 25, 20, 15, 10],
                backgroundColor: [
                    '#6777ef',
                    '#fc544b',
                    '#ffa426',
                    '#3abaf4',
                    '#66d1d1'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}
</script>
@endpush
