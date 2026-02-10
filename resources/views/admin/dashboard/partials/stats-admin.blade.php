<div class="row g-4">

    <div class="col-lg-3 col-md-6">
        <div class="card shadow border-0 text-center py-4">
            <i class="fas fa-users fa-3x text-primary mb-3"></i>
            <h6>Utilisateurs</h6>
            <h3>{{ number_format($total_users ?? 0) }}</h3>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card shadow border-0 text-center py-4">
            <i class="fas fa-palette fa-3x text-warning mb-3"></i>
            <h6>Artisans approuvés</h6>
            <h3>{{ number_format($total_artisans ?? 0) }}</h3>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card shadow border-0 text-center py-4">
            <i class="fas fa-box fa-3x text-success mb-3"></i>
            <h6>Produits actifs</h6>
            <h3>{{ number_format($total_products ?? 0) }}</h3>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card shadow border-0 text-center py-4">
            <i class="fas fa-money-bill-wave fa-3x text-info mb-3"></i>
            <h6>Revenus</h6>
            <h3>{{ number_format($total_revenue ?? 0, 0, ',', ' ') }} FCFA</h3>
        </div>
    </div>

</div>

<div class="row g-4 mt-4">
    <div class="col-12">
        <div class="card shadow border-0">
            <div class="card-header bg-gradient-success text-white">
                <i class="fas fa-chart-bar me-2"></i> Ventes (30 derniers jours)
            </div>
            <div class="card-body">
                <canvas id="adminSalesChart" height="180"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const adminSales = document.getElementById('adminSalesChart')?.getContext('2d');
    if (adminSales) {
        new Chart(adminSales, {
            type: 'bar',
            data: {
                labels: @json($salesChart->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))),
                datasets: [{
                    label: 'Ventes',
                    data: @json($salesChart->pluck('total')),
                    backgroundColor: '#1cc88a',
                    borderColor: '#17a673',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, ticks: { callback: v => v.toLocaleString('fr-FR') + ' FCFA' } }
                }
            }
        });
    }
</script>
@endpush
