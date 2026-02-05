@extends('layouts.app')

@section('title', 'Tableau de bord Artisan - AFRI-HERITAGE')

@push('styles')
<style>
    .dashboard-header {
        background: linear-gradient(135deg, var(--benin-green) 0%, var(--navy) 100%);
        color: white;
        padding: 3rem 0;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
        height: 100%;
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
    }

    .stat-change {
        font-size: 0.875rem;
        margin-left: 0.5rem;
    }

    .stat-change.positive {
        color: var(--benin-green);
    }

    .stat-change.negative {
        color: var(--benin-red);
    }

    .dashboard-nav {
        background: white;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .nav-link-dashboard {
        padding: 0.75rem 1rem;
        border-radius: 8px;
        color: var(--charcoal);
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .nav-link-dashboard:hover,
    .nav-link-dashboard.active {
        background: var(--benin-green);
        color: white;
    }

    .chart-container {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
        height: 300px;
    }

    .quick-action {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .quick-action:hover {
        background: var(--beige);
        transform: translateY(-3px);
    }

    .quick-action-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        margin-bottom: 1rem;
        background: var(--benin-green);
        color: white;
    }

    .product-list-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid var(--beige);
        transition: background 0.3s ease;
    }

    .product-list-item:hover {
        background: var(--beige);
    }

    .product-list-item:last-child {
        border-bottom: none;
    }

    .product-thumb {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        object-fit: cover;
        margin-right: 1rem;
    }

    .product-status {
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .status-in-stock {
        background: #d1fae5;
        color: #065f46;
    }

    .status-low-stock {
        background: #fef3c7;
        color: #92400e;
    }

    .status-out-of-stock {
        background: #fee2e2;
        color: #991b1b;
    }
</style>
@endpush

@section('content')
<!-- Header -->
<section class="dashboard-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="display-6 fw-bold mb-3">Tableau de bord</h1>
                <p class="mb-0">
                    Bienvenue, {{ auth()->user()->name }}
                    @if(auth()->user()->artisan)
                        <span class="badge bg-benin-yellow text-charcoal ms-2">Artisan vérifié</span>
                    @endif
                </p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('artisans.show', auth()->user()->artisan) }}"
                   class="btn btn-outline-light rounded-pill me-2">
                    Voir mon profil public
                </a>
                <a href="{{ route('products.create') }}" class="btn btn-benin-yellow text-charcoal rounded-pill">
                    <i class="bi bi-plus-circle me-2"></i> Ajouter un produit
                </a>
            </div>
        </div>
    </div>
</section>

<div class="container py-4">
    <!-- Navigation -->
    <div class="dashboard-nav">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('dashboard') }}" class="nav-link-dashboard active">
                <i class="bi bi-speedometer2 me-2"></i> Tableau de bord
            </a>
            <a href="{{ route('dashboard.artisan.products') }}" class="nav-link-dashboard">
                <i class="bi bi-palette me-2"></i> Mes produits
            </a>
            <a href="{{ route('dashboard.orders') }}" class="nav-link-dashboard">
                <i class="bi bi-cart me-2"></i> Commandes
            </a>
            <a href="{{ route('dashboard.messages') }}" class="nav-link-dashboard">
                <i class="bi bi-chat-left-text me-2"></i> Messages
            </a>
            <a href="{{ route('dashboard.reviews') }}" class="nav-link-dashboard">
                <i class="bi bi-star me-2"></i> Avis
            </a>
            <a href="{{ route('dashboard.profile') }}" class="nav-link-dashboard">
                <i class="bi bi-person me-2"></i> Profil
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: #d1fae5; color: #065f46;">
                    <i class="bi bi-palette"></i>
                </div>
                <div class="stat-number">{{ $stats['products'] }}</div>
                <div class="text-muted">Produits actifs</div>
                <div class="stat-change positive">
                    <i class="bi bi-arrow-up"></i> 12% ce mois
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: #dbeafe; color: #1e40af;">
                    <i class="bi bi-eye"></i>
                </div>
                <div class="stat-number">{{ $stats['views'] }}</div>
                <div class="text-muted">Vues profil</div>
                <div class="stat-change positive">
                    <i class="bi bi-arrow-up"></i> 24% ce mois
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: #fef3c7; color: #92400e;">
                    <i class="bi bi-star"></i>
                </div>
                <div class="stat-number">{{ number_format($stats['rating'], 1) }}</div>
                <div class="text-muted">Note moyenne</div>
                <div class="stat-change positive">
                    <i class="bi bi-arrow-up"></i> 0.2 ce mois
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: #e0e7ff; color: #3730a3;">
                    <i class="bi bi-chat-left-text"></i>
                </div>
                <div class="stat-number">{{ $stats['contacts'] }}</div>
                <div class="text-muted">Contacts reçus</div>
                <div class="stat-change positive">
                    <i class="bi bi-arrow-up"></i> 8% ce mois
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row g-4">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Performance Chart -->
            <div class="chart-container mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Performance mensuelle</h5>
                    <select class="form-select form-select-sm w-auto">
                        <option>30 derniers jours</option>
                        <option>3 derniers mois</option>
                        <option>Cette année</option>
                    </select>
                </div>
                <canvas id="performanceChart"></canvas>
            </div>

            <!-- Recent Products -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="fw-bold mb-0">Produits récents</h5>
                </div>
                <div class="card-body p-0">
                    @forelse($recentProducts as $product)
                        <div class="product-list-item">
                            <img src="{{ $product->images->first()->image_url ?? asset('images/default-product.jpg') }}"
                                 alt="{{ $product->name }}"
                                 class="product-thumb">
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">{{ $product->name }}</h6>
                                <div class="d-flex align-items-center">
                                    <span class="text-benin-green fw-bold me-3">
                                        {{ $product->formatted_price }}
                                    </span>
                                    <span class="product-status status-{{ $product->stock_status }}">
                                        @if($product->stock_status === 'in_stock')
                                            En stock
                                        @elseif($product->stock_status === 'low_stock')
                                            Stock bas
                                        @else
                                            Rupture
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="text-end">
                                <small class="text-muted d-block mb-1">
                                    <i class="bi bi-eye"></i> {{ $product->views }} vues
                                </small>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('products.show', $product) }}"
                                       class="btn btn-outline-benin-green">
                                        Voir
                                    </a>
                                    <a href="{{ route('products.edit', $product) }}"
                                       class="btn btn-outline-secondary">
                                        Modifier
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-palette fs-1 text-muted mb-3"></i>
                            <p class="text-muted mb-0">Aucun produit ajouté</p>
                        </div>
                    @endforelse
                </div>
                <div class="card-footer bg-white border-0 text-center">
                    <a href="{{ route('dashboard.artisan.products') }}" class="btn btn-benin-green rounded-pill">
                        <i class="bi bi-grid me-2"></i> Gérer tous les produits
                    </a>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="row g-3 mb-4">
                <div class="col-6">
                    <div class="quick-action" onclick="window.location.href='{{ route('products.create') }}'">
                        <div class="quick-action-icon">
                            <i class="bi bi-plus-lg"></i>
                        </div>
                        <h6 class="fw-bold mb-2">Ajouter produit</h6>
                        <p class="text-muted small mb-0">Nouveau produit</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="quick-action" onclick="window.location.href='{{ route('artisans.edit', auth()->user()->artisan) }}'">
                        <div class="quick-action-icon" style="background: var(--navy);">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <h6 class="fw-bold mb-2">Modifier profil</h6>
                        <p class="text-muted small mb-0">Profil public</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="quick-action" onclick="window.location.href='{{ route('dashboard.messages') }}'">
                        <div class="quick-action-icon" style="background: var(--benin-red);">
                            <i class="bi bi-chat-left-text"></i>
                        </div>
                        <h6 class="fw-bold mb-2">Messages</h6>
                        <p class="text-muted small mb-0">{{ $stats['contacts'] }} non lus</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="quick-action" onclick="window.location.href='{{ route('dashboard.orders') }}'">
                        <div class="quick-action-icon" style="background: var(--benin-yellow); color: var(--charcoal);">
                            <i class="bi bi-cart"></i>
                        </div>
                        <h6 class="fw-bold mb-2">Commandes</h6>
                        <p class="text-muted small mb-0">À traiter</p>
                    </div>
                </div>
            </div>

            <!-- Popular Products -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0">
                    <h5 class="fw-bold mb-0">Produits populaires</h5>
                </div>
                <div class="card-body">
                    @forelse($popularProducts as $product)
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ $product->images->first()->image_url ?? asset('images/default-product.jpg') }}"
                                 alt="{{ $product->name }}"
                                 class="rounded me-3"
                                 style="width: 50px; height: 50px; object-fit: cover;">
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">{{ Str::limit($product->name, 25) }}</h6>
                                <div class="d-flex justify-content-between">
                                    <span class="text-benin-green fw-bold">
                                        {{ $product->formatted_price }}
                                    </span>
                                    <small class="text-muted">
                                        <i class="bi bi-eye"></i> {{ $product->views }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center mb-0">Aucun produit populaire</p>
                    @endforelse
                </div>
            </div>

            <!-- Tips -->
            <div class="card border-0 shadow-sm bg-benin-green text-white">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-lightbulb me-2"></i> Conseils pour augmenter vos ventes
                    </h6>
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-2">✓ Ajoutez des photos de qualité</li>
                        <li class="mb-2">✓ Répondez rapidement aux messages</li>
                        <li class="mb-2">✓ Mettez à jour les stocks régulièrement</li>
                        <li class="mb-2">✓ Partagez vos créations sur les réseaux</li>
                        <li>✓ Demandez des avis à vos clients</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Performance Chart
const ctx = document.getElementById('performanceChart').getContext('2d');
const performanceChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['1', '5', '10', '15', '20', '25', '30'],
        datasets: [{
            label: 'Vues',
            data: [12, 19, 15, 25, 22, 30, 28],
            borderColor: 'var(--benin-green)',
            backgroundColor: 'rgba(0, 150, 57, 0.1)',
            tension: 0.4,
            fill: true
        }, {
            label: 'Contacts',
            data: [5, 8, 6, 12, 10, 15, 14],
            borderColor: 'var(--navy)',
            backgroundColor: 'rgba(30, 58, 95, 0.1)',
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
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                }
            },
            x: {
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                }
            }
        }
    }
});
</script>
@endpush
