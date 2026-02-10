<div class="row g-4">

    <div class="col-lg-3 col-md-6">
        <div class="card shadow border-0 text-center py-4 bg-light-success">
            <i class="fas fa-box-open fa-3x text-success mb-3"></i>
            <h6>Mes produits</h6>
            <h2 class="text-success">{{ $products_count ?? 0 }}</h2>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card shadow border-0 text-center py-4 bg-light-info">
            <i class="fas fa-eye fa-3x text-info mb-3"></i>
            <h6>Vues totales</h6>
            <h2 class="text-info">{{ number_format($views ?? 0) }}</h2>
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
        <div class="card shadow border-0 text-center py-4 bg-light-primary">
            <i class="fas fa-shopping-cart fa-3x text-primary mb-3"></i>
            <h6>Commandes</h6>
            <h2 class="text-primary">{{ $orders_count ?? 0 }}</h2>
        </div>
    </div>

</div>

<div class="row g-4 mt-4">
    <div class="col-12">
        <div class="card shadow border-0">
            <div class="card-header bg-gradient-success text-white">
                <i class="fas fa-chart-line me-2"></i> Évolution de vos ventes
            </div>
            <div class="card-body">
                <canvas id="artisanSalesChart" height="180"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Si vous avez préparé un chart de ventes pour l'artisan, ajoutez-le ici
    // Sinon, vous pouvez réutiliser un chart simple comme pour admin
</script>
@endpush
