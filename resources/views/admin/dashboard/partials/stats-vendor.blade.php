<div class="row g-4">

    <div class="col-lg-3 col-md-6">
        <div class="card shadow border-0 text-center py-4 bg-light-primary">
            <i class="fas fa-utensils fa-3x text-primary mb-3"></i>
            <h6>Mes plats</h6>
            <h2 class="text-primary">{{ $dishes_count ?? 0 }}</h2>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card shadow border-0 text-center py-4 bg-light-success">
            <i class="fas fa-receipt fa-3x text-success mb-3"></i>
            <h6>Commandes reçues</h6>
            <h2 class="text-success">{{ $orders_count ?? 0 }}</h2>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card shadow border-0 text-center py-4 bg-light-warning">
            <i class="fas fa-star fa-3x text-warning mb-3"></i>
            <h6>Note moyenne</h6>
            <h2 class="text-warning">{{ number_format($rating ?? 0, 1) }} / 5</h2>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card shadow border-0 text-center py-4 bg-light-danger">
            <i class="fas fa-coins fa-3x text-danger mb-3"></i>
            <h6>Revenus</h6>
            <h3 class="text-danger">{{ number_format($total_revenue ?? 0, 0, ',', ' ') }} FCFA</h3>
        </div>
    </div>

</div>

<div class="row g-4 mt-4">
    <div class="col-12">
        <div class="card shadow border-0">
            <div class="card-header bg-gradient-info text-white">
                <i class="fas fa-chart-bar me-2"></i> Ventes récentes
            </div>
            <div class="card-body">
                <canvas id="vendorSalesChart" height="180"></canvas>
            </div>
        </div>
    </div>
</div>
