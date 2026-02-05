@extends('layouts.admin')

@section('title', 'Tableau de bord Artisan')

@section('content')
<div class="section-header">
    <h1>Tableau de bord</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard.artisan') }}">Dashboard</a></div>
        <div class="breadcrumb-item">Artisan</div>
    </div>
</div>

<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-primary">
                <i class="fas fa-palette"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Produits actifs</h4>
                </div>
                <div class="card-body">
                    {{ $stats['products'] ?? 0 }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-danger">
                <i class="fas fa-eye"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Vues profil</h4>
                </div>
                <div class="card-body">
                    {{ $stats['views'] ?? 0 }}
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
                    {{ number_format($stats['rating'] ?? 0, 1) }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-success">
                <i class="fas fa-comments"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Contacts reçus</h4>
                </div>
                <div class="card-body">
                    {{ $stats['contacts'] ?? 0 }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 col-md-12 col-12 col-sm-12">
        <div class="card">
            <div class="card-header">
                <h4>Graphique de Performance</h4>
                <div class="card-header-action">
                    <div class="btn-group">
                        <a href="#" class="btn btn-primary">Semaine</a>
                        <a href="#" class="btn">Mois</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="performanceChart" height="182"></canvas>
                <div class="statistic-details mt-sm-4">
                    <div class="statistic-details-item">
                        <span class="text-muted"><span class="text-primary"><i class="fas fa-caret-up"></i></span> 7%</span>
                        <div class="detail-value">{{ $stats['products'] ?? 0 }}</div>
                        <div class="detail-name">Produits</div>
                    </div>
                    <div class="statistic-details-item">
                        <span class="text-muted"><span class="text-danger"><i class="fas fa-caret-down"></i></span> 23%</span>
                        <div class="detail-value">{{ $stats['views'] ?? 0 }}</div>
                        <div class="detail-name">Vues</div>
                    </div>
                    <div class="statistic-details-item">
                        <span class="text-muted"><span class="text-primary"><i class="fas fa-caret-up"></i></span> 9%</span>
                        <div class="detail-value">{{ number_format($stats['rating'] ?? 0, 1) }}</div>
                        <div class="detail-name">Note</div>
                    </div>
                    <div class="statistic-details-item">
                        <span class="text-muted"><span class="text-primary"><i class="fas fa-caret-up"></i></span> 19%</span>
                        <div class="detail-value">{{ $stats['contacts'] ?? 0 }}</div>
                        <div class="detail-name">Contacts</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-12 col-12 col-sm-12">
        <div class="card">
            <div class="card-header">
                <h4>Actions rapides</h4>
            </div>
            <div class="card-body">
                <ul class="list-unstyled list-unstyled-border">
                    <li class="media">
                        <a href="{{ route('products.create') }}" class="btn btn-primary btn-lg btn-block mb-2">
                            <i class="fas fa-plus mr-2"></i> Ajouter un produit
                        </a>
                    </li>
                    <li class="media">
                        @if(auth()->user()->artisan)
                        <a href="{{ route('artisans.edit', auth()->user()->artisan) }}" class="btn btn-info btn-lg btn-block mb-2">
                            <i class="fas fa-user-edit mr-2"></i> Modifier mon profil
                        </a>
                        @endif
                    </li>
                    <li class="media">
                        <a href="{{ route('dashboard.messages') }}" class="btn btn-warning btn-lg btn-block mb-2">
                            <i class="fas fa-envelope mr-2"></i> Mes messages
                        </a>
                    </li>
                    <li class="media">
                        <a href="{{ route('dashboard.artisan.orders') }}" class="btn btn-success btn-lg btn-block">
                            <i class="fas fa-shopping-cart mr-2"></i> Mes commandes
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4>Conseils</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-light">
                    <div class="alert-title">Augmentez vos ventes !</div>
                    <ul class="mb-0">
                        <li>Ajoutez des photos de qualité</li>
                        <li>Répondez rapidement aux messages</li>
                        <li>Mettez à jour vos stocks</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Mes produits récents</h4>
                <div class="card-header-action">
                    <a href="{{ route('dashboard.artisan.products') }}" class="btn btn-primary">
                        Voir tous <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Nom du produit</th>
                                <th>Prix</th>
                                <th>Stock</th>
                                <th>Vues</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentProducts ?? [] as $product)
                            <tr>
                                <td>
                                    <img src="{{ $product->images->first()->image_url ?? asset('images/default-product.jpg') }}" 
                                         alt="{{ $product->name }}" 
                                         width="50" 
                                         class="rounded">
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->formatted_price ?? number_format($product->price, 0, ',', ' ') . ' FCFA' }}</td>
                                <td>
                                    @if(($product->stock ?? 0) > 10)
                                        <span class="badge badge-success">En stock</span>
                                    @elseif(($product->stock ?? 0) > 0)
                                        <span class="badge badge-warning">Stock bas</span>
                                    @else
                                        <span class="badge badge-danger">Rupture</span>
                                    @endif
                                </td>
                                <td>{{ $product->views ?? 0 }}</td>
                                <td>
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-info" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-primary" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="empty-state">
                                        <div class="empty-state-icon bg-primary">
                                            <i class="fas fa-palette"></i>
                                        </div>
                                        <h2>Aucun produit</h2>
                                        <p class="lead">Vous n'avez pas encore ajouté de produits.</p>
                                        <a href="{{ route('products.create') }}" class="btn btn-primary mt-4">
                                            Ajouter un produit
                                        </a>
                                    </div>
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

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4>Produits populaires</h4>
            </div>
            <div class="card-body">
                <ul class="list-unstyled list-unstyled-border">
                    @forelse($popularProducts ?? [] as $product)
                    <li class="media">
                        <img class="mr-3 rounded" width="55" src="{{ $product->images->first()->image_url ?? asset('images/default-product.jpg') }}" alt="{{ $product->name }}">
                        <div class="media-body">
                            <div class="float-right">
                                <div class="font-weight-600 text-muted text-small">
                                    <i class="fas fa-eye"></i> {{ $product->views ?? 0 }} vues
                                </div>
                            </div>
                            <div class="media-title">{{ $product->name }}</div>
                            <div class="text-success font-weight-600">
                                {{ $product->formatted_price ?? number_format($product->price, 0, ',', ' ') . ' FCFA' }}
                            </div>
                        </div>
                    </li>
                    @empty
                    <li class="media">
                        <div class="media-body text-center py-3">
                            <p class="text-muted mb-0">Aucun produit populaire</p>
                        </div>
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4>Derniers avis</h4>
            </div>
            <div class="card-body">
                <ul class="list-unstyled list-unstyled-border">
                    @forelse(auth()->user()->artisan?->reviews()->latest()->take(3)->get() ?? [] as $review)
                    <li class="media">
                        <img class="mr-3 rounded-circle" width="50" src="{{ $review->user->avatar ?? asset('images/default-avatar.png') }}" alt="avatar">
                        <div class="media-body">
                            <div class="float-right text-warning">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <div class="media-title">{{ $review->user->name }}</div>
                            <span class="text-small text-muted">{{ Str::limit($review->comment, 80) }}</span>
                        </div>
                    </li>
                    @empty
                    <li class="media">
                        <div class="media-body text-center py-3">
                            <p class="text-muted mb-0">Aucun avis pour le moment</p>
                        </div>
                    </li>
                    @endforelse
                </ul>
                <div class="text-center pt-1 pb-1">
                    <a href="{{ route('dashboard.artisan.reviews') }}" class="btn btn-primary btn-lg btn-round">
                        Voir tous les avis
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('performanceChart');
    if (ctx) {
        var performanceChart = new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
                datasets: [{
                    label: 'Vues',
                    data: [45, 52, 38, 55, 62, 58],
                    borderColor: '#6777ef',
                    backgroundColor: 'rgba(103, 119, 239, 0.2)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Contacts',
                    data: [12, 15, 10, 18, 22, 20],
                    borderColor: '#fc544b',
                    backgroundColor: 'rgba(252, 84, 75, 0.2)',
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
});
</script>
@endpush
