@extends('layouts.admin')

@section('title', 'Gestion des artisans')

@section('content')
<div class="section-header">
    <h1>Artisans</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item active">Artisans</div>
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
                        <a href="{{ route('admin.artisans.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Ajouter un artisan
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.artisans.index') }}" method="GET" class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Recherche</label>
                                <input type="text" name="search" class="form-control" 
                                       value="{{ request('search') }}" placeholder="Nom, spécialité...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Spécialité</label>
                                <select name="specialty" class="form-control">
                                    <option value="">Toutes</option>
                                    @foreach($specialties as $specialty)
                                        <option value="{{ $specialty }}" {{ request('specialty') == $specialty ? 'selected' : '' }}>
                                            {{ $specialty }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Localisation</label>
                                <select name="location" class="form-control">
                                    <option value="">Toutes</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location }}" {{ request('location') == $location ? 'selected' : '' }}>
                                            {{ $location }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Statut</label>
                                <select name="status" class="form-control">
                                    <option value="">Tous</option>
                                    <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Vérifié</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="form-group">
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-search"></i> Filtrer
                                </button>
                                <a href="{{ route('admin.artisans.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-undo"></i> Réinitialiser
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Liste des artisans --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Liste des artisans ({{ $artisans->total() }})</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Artisan</th>
                                    <th>Spécialité</th>
                                    <th>Localisation</th>
                                    <th>Expérience</th>
                                    <th>Produits</th>
                                    <th>Note</th>
                                    <th>Statut</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($artisans as $artisan)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @php
                                                $photo = $artisan->photos->where('is_primary', true)->first() ?? $artisan->photos->first();
                                            @endphp
                                            <img src="{{ $photo ? asset($photo->photo_url) : asset('admin-assets/img/avatar/avatar-1.png') }}" 
                                                 alt="{{ $artisan->user->name }}" 
                                                 class="rounded-circle mr-3" width="45" height="45" style="object-fit: cover;">
                                            <div>
                                                <strong>{{ $artisan->user->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $artisan->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $artisan->specialty }}</span>
                                    </td>
                                    <td>{{ $artisan->location ?? '-' }}</td>
                                    <td>{{ $artisan->experience_years ?? 0 }} ans</td>
                                    <td>
                                        <span class="badge badge-light">{{ $artisan->products_count ?? $artisan->products->count() }} produits</span>
                                    </td>
                                    <td>
                                        @if($artisan->average_rating)
                                            <span class="text-warning">
                                                <i class="fas fa-star"></i> {{ number_format($artisan->average_rating, 1) }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($artisan->is_verified)
                                            <span class="badge badge-success"><i class="fas fa-check"></i> Vérifié</span>
                                        @else
                                            <span class="badge badge-warning">En attente</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.artisans.show', $artisan) }}" 
                                               class="btn btn-sm btn-info" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.artisans.edit', $artisan) }}" 
                                               class="btn btn-sm btn-warning" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger btn-delete" 
                                                    data-id="{{ $artisan->id }}" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="empty-state">
                                            <div class="empty-state-icon bg-light">
                                                <i class="fas fa-hands"></i>
                                            </div>
                                            <h2>Aucun artisan</h2>
                                            <p class="lead">Aucun artisan ne correspond à vos critères.</p>
                                            <a href="{{ route('admin.artisans.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus"></i> Ajouter un artisan
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($artisans->hasPages())
                <div class="card-footer">
                    {{ $artisans->withQueryString()->links() }}
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
        var artisanId = $(this).data('id');
        
        Swal.fire({
            title: 'Supprimer cet artisan ?',
            text: "Cette action supprimera également tous ses produits !",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("admin.artisans.index") }}/' + artisanId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire('Supprimé!', 'L\'artisan a été supprimé.', 'success')
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
