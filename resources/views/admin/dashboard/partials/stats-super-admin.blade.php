<div class="row g-4">

    <!-- Cartes statistiques principales -->
    <div class="col-lg-3 col-md-6">
        <div class="card shadow border-0 h-100">
            <div class="card-body text-center py-4 bg-gradient-primary text-white rounded-top">
                <i class="fas fa-users fa-3x mb-3 opacity-75"></i>
                <h6 class="mb-1 text-white-75">Utilisateurs</h6>
                <h3 class="mb-0">{{ number_format($total_users ?? 0) }}</h3>
                <small>+{{ $new_users_today ?? 0 }} aujourd'hui</small>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card shadow border-0 h-100">
            <div class="card-body text-center py-4 bg-gradient-warning text-dark rounded-top">
                <i class="fas fa-palette fa-3x mb-3 opacity-75"></i>
                <h6 class="mb-1">Artisans</h6>
                <h3 class="mb-0">{{ number_format($total_artisans ?? 0) }}</h3>
                <small><span class="text-success">{{ $artisans_approved ?? 0 }}</span> / <span class="text-danger">{{ $artisans_pending ?? 0 }}</span></small>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card shadow border-0 h-100">
            <div class="card-body text-center py-4 bg-gradient-success text-white rounded-top">
                <i class="fas fa-shopping-cart fa-3x mb-3 opacity-75"></i>
                <h6 class="mb-1">Commandes</h6>
                <h3 class="mb-0">{{ number_format($total_orders ?? 0) }}</h3>
                <small>{{ $completed_orders ?? 0 }} terminées</small>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card shadow border-0 h-100">
            <div class="card-body text-center py-4 bg-gradient-info text-white rounded-top">
                <i class="fas fa-money-bill-wave fa-3x mb-3 opacity-75"></i>
                <h6 class="mb-1">Revenus</h6>
                <h3 class="mb-0">{{ number_format($total_revenue ?? 0, 0, ',', ' ') }} FCFA</h3>
            </div>
        </div>
    </div>

</div>

<!-- Graphiques -->
<div class="row g-4 mt-3">

    <!-- Inscriptions + Ventes -->
    <div class="col-lg-6">
        <div class="card shadow border-0">
            <div class="card-header bg-gradient-primary text-white">
                <i class="fas fa-users me-2"></i> Inscriptions (30 jours)
            </div>
            <div class="card-body">
                <canvas id="superRegistrationsChart" height="160"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow border-0">
            <div class="card-header bg-gradient-success text-white">
                <i class="fas fa-chart-bar me-2"></i> Ventes quotidiennes (30 jours)
            </div>
            <div class="card-body">
                <canvas id="superSalesChart" height="160"></canvas>
            </div>
        </div>
    </div>

</div>

<!-- Graphiques de répartition -->
<div class="row g-4 mt-3">
    <div class="col-lg-6">
        <div class="card shadow border-0">
            <div class="card-header bg-gradient-warning text-dark">
                <i class="fas fa-palette me-2"></i> Statut des artisans
            </div>
            <div class="card-body">
                <canvas id="artisansStatusChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow border-0">
            <div class="card-header bg-gradient-info text-white">
                <i class="fas fa-shopping-cart me-2"></i> Statut des commandes
            </div>
            <div class="card-body">
                <canvas id="ordersStatusChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    // Inscriptions
    const regCtx = document.getElementById('superRegistrationsChart')?.getContext('2d');
    if (regCtx) {
        new Chart(regCtx, {
            type: 'line',
            data: {
                labels: @json($registrationsChart->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))),
                datasets: [{
                    label: 'Inscriptions',
                    data: @json($registrationsChart->pluck('count')),
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78,115,223,0.12)',
                    tension: 0.4,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'top' } },
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    // Ventes
    const salesCtx = document.getElementById('superSalesChart')?.getContext('2d');
    if (salesCtx) {
        new Chart(salesCtx, {
            type: 'bar',
            data: {
                labels: @json($salesChart->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))),
                datasets: [{
                    label: 'Ventes (FCFA)',
                    data: @json($salesChart->pluck('total')),
                    backgroundColor: '#36b9cc',
                    borderColor: '#2c9faf',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'top' } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: v => v.toLocaleString('fr-FR') + ' FCFA' }
                    }
                }
            }
        });
    }

    // Statut artisans (doughnut)
    const artCtx = document.getElementById('artisansStatusChart')?.getContext('2d');
    if (artCtx) {
        new Chart(artCtx, {
            type: 'doughnut',
            data: {
                labels: ['Approuvés', 'En attente', 'Rejetés'],
                datasets: [{
                    data: [{{ $artisans_approved ?? 0 }}, {{ $artisans_pending ?? 0 }}, {{ Artisan::where('status', 'rejected')->count() ?? 0 }}],
                    backgroundColor: ['#1cc88a', '#f6c23e', '#e74a3b'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                cutout: '65%',
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }

    // Statut commandes
    const ordCtx = document.getElementById('ordersStatusChart')?.getContext('2d');
    if (ordCtx) {
        new Chart(ordCtx, {
            type: 'doughnut',
            data: {
                labels: ['En attente', 'Traitement', 'Terminées', 'Annulées'],
                datasets: [{
                    data: [
                        {{ $pending_orders ?? 0 }},
                        {{ Order::where('order_status', 'processing')->count() ?? 0 }},
                        {{ $completed_orders ?? 0 }},
                        {{ Order::where('order_status', 'cancelled')->count() ?? 0 }}
                    ],
                    backgroundColor: ['#f6c23e', '#4e73df', '#1cc88a', '#e74a3b'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                cutout: '65%',
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }
});
</script>
@endpush
