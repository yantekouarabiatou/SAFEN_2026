@extends('layouts.admin')

@section('title', 'Rapport & Statistiques')

@section('content')
<div class="section-header">
    <h1>Rapport & Statistiques</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item active">Rapport & Statistiques</div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card card-statistic-1">
            <div class="card-icon bg-primary"><i class="fas fa-users"></i></div>
            <div class="card-wrap">
                <div class="card-header"><h4>Utilisateurs</h4></div>
                <div class="card-body">{{ number_format($stats['total_users']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-statistic-1">
            <div class="card-icon bg-success"><i class="fas fa-shopping-cart"></i></div>
            <div class="card-wrap">
                <div class="card-header"><h4>Commandes</h4></div>
                <div class="card-body">{{ number_format($stats['total_orders']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-statistic-1">
            <div class="card-icon bg-warning"><i class="fas fa-utensils"></i></div>
            <div class="card-wrap">
                <div class="card-header"><h4>Plats</h4></div>
                <div class="card-body">{{ number_format($stats['total_dishes']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-statistic-1">
            <div class="card-icon bg-info"><i class="fas fa-money-bill-wave"></i></div>
            <div class="card-wrap">
                <div class="card-header"><h4>Revenu</h4></div>
                <div class="card-body">{{ number_format($stats['total_revenue'], 0, ',', ' ') }} FCFA</div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h4>Ventes (30 derniers jours)</h4></div>
            <div class="card-body">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h4>Inscriptions (30 derniers jours)</h4></div>
            <div class="card-body">
                <canvas id="usersChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h4>Top 5 Produits</h4></div>
            <div class="card-body">
                <div class="mb-2 small text-muted">Trié par : {{ $productMetricLabel ?? 'Ventes' }}</div>
                <ul class="list-group">
                    @foreach($popularProducts as $product)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $product->name }}</strong>
                                @if($product->category)
                                    <br><small class="text-muted">{{ $product->category_label ?? $product->category }}</small>
                                @endif
                            </div>
                            <div class="text-right">
                                <span class="badge badge-success badge-pill">{{ $product->sales ?? 0 }} ventes</span>
                                <br>
                                <small class="text-muted">{{ number_format($product->revenue ?? 0, 0, ',', ' ') }} FCFA</small>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h4>Top 5 Artisans</h4></div>
            <div class="card-body">
                <div class="mb-2 small text-muted">Trié par : {{ $artisanMetricLabel ?? 'Articles vendus' }}</div>
                <ul class="list-group">
                    @foreach($topArtisans as $artisan)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $artisan->user->name ?? $artisan->business_name ?? 'Artisan' }}</strong>
                                    @if(optional($artisan->user)->email)
                                        <br><small class="text-muted">{{ $artisan->user->email }}</small>
                                    @elseif($artisan->phone)
                                        <br><small class="text-muted">{{ $artisan->phone }}</small>
                                    @endif
                                    @if($artisan->business_name)
                                        <br><small class="text-muted">Entreprise: {{ $artisan->business_name }}</small>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <span class="badge badge-primary badge-pill">{{ $artisan->orders_count ?? 0 }} articles</span>
                                    <br>
                                    <small class="text-muted">Inscrit le {{ $artisan->created_at->format('d/m/Y') }}</small>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Préparation des données pour le graphique des ventes
    const salesLabels = @json($salesChart->pluck('date'));
    const salesData = @json($salesChart->pluck('total'));
    const salesCount = @json($salesChart->pluck('count'));

    const usersLabels = @json($usersChart->pluck('date'));
    const usersData = @json($usersChart->pluck('count'));

    // Graphique des ventes
    new Chart(document.getElementById('salesChart'), {
        type: 'bar',
        data: {
            labels: salesLabels,
            datasets: [
                {
                    label: 'Revenu (FCFA)',
                    data: salesData,
                    backgroundColor: '#6777ef',
                },
                {
                    label: 'Commandes',
                    data: salesCount,
                    backgroundColor: '#28a745',
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Graphique des inscriptions
    new Chart(document.getElementById('usersChart'), {
        type: 'line',
        data: {
            labels: usersLabels,
            datasets: [{
                label: 'Inscriptions',
                data: usersData,
                backgroundColor: '#ffc107',
                borderColor: '#ffc107',
                fill: false,
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endpush
