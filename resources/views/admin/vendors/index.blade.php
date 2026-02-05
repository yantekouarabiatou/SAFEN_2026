@extends('layouts.admin')

@section('title', 'Gestion des vendeurs')

@section('content')
<div class="section-header">
    <h1>Vendeurs</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item active">Vendeurs</div>
    </div>
</div>

<div class="section-body">
    {{-- Filtres --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-filter"></i> Filtres</h4>
                    <div class="card-header-action">
                        <a href="{{ route('admin.vendors.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Ajouter un vendeur
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.vendors.index') }}" method="GET" class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Recherche</label>
                                <input type="text" name="search" class="form-control" 
                                       value="{{ request('search') }}" placeholder="Nom, localisation...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Type</label>
                                <select name="type" class="form-control">
                                    <option value="">Tous</option>
                                    <option value="restaurant" {{ request('type') == 'restaurant' ? 'selected' : '' }}>Restaurant</option>
                                    <option value="maquis" {{ request('type') == 'maquis' ? 'selected' : '' }}>Maquis</option>
                                    <option value="food_truck" {{ request('type') == 'food_truck' ? 'selected' : '' }}>Food Truck</option>
                                    <option value="traiteur" {{ request('type') == 'traiteur' ? 'selected' : '' }}>Traiteur</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Statut</label>
                                <select name="status" class="form-control">
                                    <option value="">Tous</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Vérifié</label>
                                <select name="verified" class="form-control">
                                    <option value="">Tous</option>
                                    <option value="1" {{ request('verified') == '1' ? 'selected' : '' }}>Oui</option>
                                    <option value="0" {{ request('verified') == '0' ? 'selected' : '' }}>Non</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="form-group">
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-search"></i> Filtrer
                                </button>
                                <a href="{{ route('admin.vendors.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-undo"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Liste des vendeurs --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Liste des vendeurs ({{ $vendors->total() }})</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Vendeur</th>
                                    <th>Type</th>
                                    <th>Localisation</th>
                                    <th>Plats</th>
                                    <th>Note</th>
                                    <th>Statut</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($vendors as $vendor)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $vendor->logo_url ? asset($vendor->logo_url) : asset('admin-assets/img/example-image.jpg') }}" 
                                                 alt="{{ $vendor->name }}" 
                                                 class="rounded mr-3" width="50" height="50" style="object-fit: cover;">
                                            <div>
                                                <strong>{{ $vendor->name }}</strong>
                                                @if($vendor->is_verified)
                                                    <i class="fas fa-check-circle text-primary" title="Vérifié"></i>
                                                @endif
                                                <br>
                                                <small class="text-muted">{{ $vendor->user->email ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ ucfirst($vendor->type ?? 'Restaurant') }}</span>
                                    </td>
                                    <td>{{ $vendor->location ?? '-' }}</td>
                                    <td>
                                        <span class="badge badge-light">{{ $vendor->dishes->count() }} plats</span>
                                    </td>
                                    <td>
                                        @if($vendor->average_rating)
                                            <span class="text-warning">
                                                <i class="fas fa-star"></i> {{ number_format($vendor->average_rating, 1) }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($vendor->is_active ?? true)
                                            <span class="badge badge-success">Actif</span>
                                        @else
                                            <span class="badge badge-secondary">Inactif</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.vendors.show', $vendor) }}" 
                                               class="btn btn-sm btn-info" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.vendors.edit', $vendor) }}" 
                                               class="btn btn-sm btn-warning" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger btn-delete" 
                                                    data-id="{{ $vendor->id }}" title="Supprimer">
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
                                                <i class="fas fa-store"></i>
                                            </div>
                                            <h2>Aucun vendeur</h2>
                                            <p class="lead">Aucun vendeur ne correspond à vos critères.</p>
                                            <a href="{{ route('admin.vendors.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus"></i> Ajouter un vendeur
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($vendors->hasPages())
                <div class="card-footer">
                    {{ $vendors->withQueryString()->links() }}
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
    $('.btn-delete').click(function() {
        var vendorId = $(this).data('id');
        
        Swal.fire({
            title: 'Supprimer ce vendeur ?',
            text: "Cette action est irréversible !",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("admin.vendors.index") }}/' + vendorId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire('Supprimé!', 'Le vendeur a été supprimé.', 'success')
                            .then(() => location.reload());
                    },
                    error: function(xhr) {
                        Swal.fire('Erreur', xhr.responseJSON?.message || 'Une erreur est survenue.', 'error');
                    }
                });
            }
        });
    });
});
</script>
@endpush
