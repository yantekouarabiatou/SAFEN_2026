<div class="row g-4">

    <div class="col-lg-4 col-md-6">
        <div class="card shadow border-0 text-center py-5">
            <i class="fas fa-shopping-bag fa-4x text-success mb-3"></i>
            <h5>Mes commandes</h5>
            <h2 class="text-success">{{ $orders_count ?? 0 }}</h2>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="card shadow border-0 text-center py-5">
            <i class="fas fa-heart fa-4x text-danger mb-3"></i>
            <h5>Mes favoris</h5>
            <h2 class="text-danger">{{ $favorites_count ?? 0 }}</h2>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="card shadow border-0 text-center py-5">
            <i class="fas fa-comment-dots fa-4x text-warning mb-3"></i>
            <h5>Mes avis</h5>
            <h2 class="text-warning">{{ $reviews_count ?? 0 }}</h2>
        </div>
    </div>

</div>

<!-- Optionnel : un petit graphique d'activité -->
<div class="row g-4 mt-4">
    <div class="col-12">
        <div class="card shadow border-0">
            <div class="card-header bg-gradient-primary text-white">
                <i class="fas fa-history me-2"></i> Votre activité récente
            </div>
            <div class="card-body text-center py-5 text-muted">
                <i class="fas fa-chart-line fa-5x opacity-25 mb-3"></i>
                <p class="lead">Aucune donnée statistique détaillée pour le moment</p>
            </div>
        </div>
    </div>
</div>
