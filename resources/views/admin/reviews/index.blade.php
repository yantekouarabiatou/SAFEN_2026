@extends('layouts.admin')

@section('title', 'Gestion des avis')

@section('content')
<div class="section-header">
    <h1>Avis clients</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item active">Avis</div>
    </div>
</div>

<div class="section-body">
    {{-- Statistiques --}}
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                    <i class="fas fa-star"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Total avis</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['total'] ?? 0 }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>En attente</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['pending'] ?? 0 }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                    <i class="fas fa-check"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Approuvés</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['approved'] ?? 0 }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-info">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Note moyenne</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($stats['average_rating'] ?? 0, 1) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Filtres --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-filter"></i> Filtres</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.reviews.index') }}" method="GET" class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Recherche</label>
                                <input type="text" name="search" class="form-control" 
                                       value="{{ request('search') }}" placeholder="Utilisateur, produit...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Type</label>
                                <select name="type" class="form-control">
                                    <option value="">Tous</option>
                                    <option value="product" {{ request('type') == 'product' ? 'selected' : '' }}>Produits</option>
                                    <option value="artisan" {{ request('type') == 'artisan' ? 'selected' : '' }}>Artisans</option>
                                    <option value="vendor" {{ request('type') == 'vendor' ? 'selected' : '' }}>Vendeurs</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Note</label>
                                <select name="rating" class="form-control">
                                    <option value="">Toutes</option>
                                    @for($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                                            {{ $i }} étoile{{ $i > 1 ? 's' : '' }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Statut</label>
                                <select name="status" class="form-control">
                                    <option value="">Tous</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approuvé</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejeté</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-group">
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-search"></i> Filtrer
                                </button>
                                <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-undo"></i> Réinitialiser
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Liste des avis --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Liste des avis ({{ $reviews->total() }})</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Utilisateur</th>
                                    <th>Concernant</th>
                                    <th>Note</th>
                                    <th>Commentaire</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reviews as $review)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $review->user->profile_photo_url ?? asset('admin-assets/img/avatar/avatar-1.png') }}" 
                                                 alt="{{ $review->user->name }}" 
                                                 class="rounded-circle mr-2" width="35" height="35">
                                            <div>
                                                <strong>{{ $review->user->name }}</strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($review->product)
                                            <span class="badge badge-info">Produit</span>
                                            <br>{{ Str::limit($review->product->name, 30) }}
                                        @elseif($review->artisan)
                                            <span class="badge badge-warning">Artisan</span>
                                            <br>{{ $review->artisan->user->name }}
                                        @elseif($review->vendor)
                                            <span class="badge badge-success">Vendeur</span>
                                            <br>{{ $review->vendor->name }}
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <small>({{ $review->rating }}/5)</small>
                                    </td>
                                    <td>{{ Str::limit($review->comment, 50) }}</td>
                                    <td>{{ $review->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        @switch($review->status ?? 'pending')
                                            @case('approved')
                                                <span class="badge badge-success">Approuvé</span>
                                                @break
                                            @case('pending')
                                                <span class="badge badge-warning">En attente</span>
                                                @break
                                            @case('rejected')
                                                <span class="badge badge-danger">Rejeté</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            @if(($review->status ?? 'pending') !== 'approved')
                                            <button type="button" class="btn btn-sm btn-success btn-approve" 
                                                    data-id="{{ $review->id }}" title="Approuver">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            @endif
                                            @if(($review->status ?? 'pending') !== 'rejected')
                                            <button type="button" class="btn btn-sm btn-danger btn-reject" 
                                                    data-id="{{ $review->id }}" title="Rejeter">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-secondary btn-delete" 
                                                    data-id="{{ $review->id }}" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="empty-state">
                                            <div class="empty-state-icon bg-light">
                                                <i class="fas fa-star"></i>
                                            </div>
                                            <h2>Aucun avis</h2>
                                            <p class="lead">Aucun avis ne correspond à vos critères.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($reviews->hasPages())
                <div class="card-footer">
                    {{ $reviews->withQueryString()->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Approuver
    $('.btn-approve').click(function() {
        var reviewId = $(this).data('id');
        
        $.ajax({
            url: '{{ route("admin.reviews.index") }}/' + reviewId + '/approve',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                iziToast.success({
                    title: 'Succès',
                    message: 'Avis approuvé',
                    position: 'topRight'
                });
                location.reload();
            },
            error: function() {
                iziToast.error({
                    title: 'Erreur',
                    message: 'Une erreur est survenue',
                    position: 'topRight'
                });
            }
        });
    });
    
    // Rejeter
    $('.btn-reject').click(function() {
        var reviewId = $(this).data('id');
        
        $.ajax({
            url: '{{ route("admin.reviews.index") }}/' + reviewId + '/reject',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                iziToast.success({
                    title: 'Succès',
                    message: 'Avis rejeté',
                    position: 'topRight'
                });
                location.reload();
            },
            error: function() {
                iziToast.error({
                    title: 'Erreur',
                    message: 'Une erreur est survenue',
                    position: 'topRight'
                });
            }
        });
    });
    
    // Supprimer
    $('.btn-delete').click(function() {
        var reviewId = $(this).data('id');
        
        Swal.fire({
            title: 'Supprimer cet avis ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("admin.reviews.index") }}/' + reviewId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire('Supprimé!', 'L\'avis a été supprimé.', 'success')
                            .then(() => location.reload());
                    },
                    error: function() {
                        Swal.fire('Erreur', 'Une erreur est survenue.', 'error');
                    }
                });
            }
        });
    });
});
</script>
@endpush
