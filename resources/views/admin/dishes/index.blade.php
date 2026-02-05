@extends('layouts.admin')

@section('title', 'Gestion de la gastronomie')

@section('content')
<div class="section-header">
    <h1>Gastronomie</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item active">Gastronomie</div>
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
                        <a href="{{ route('admin.dishes.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Ajouter un plat
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.dishes.index') }}" method="GET" class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Recherche</label>
                                <input type="text" name="search" class="form-control" 
                                       value="{{ request('search') }}" placeholder="Nom du plat...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Catégorie</label>
                                <select name="category" class="form-control">
                                    <option value="">Toutes</option>
                                    @foreach($categories as $key => $label)
                                        <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Région</label>
                                <select name="region" class="form-control">
                                    <option value="">Toutes</option>
                                    @foreach($regions as $region)
                                        <option value="{{ $region }}" {{ request('region') == $region ? 'selected' : '' }}>
                                            {{ $region }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Vedette</label>
                                <select name="featured" class="form-control">
                                    <option value="">Tous</option>
                                    <option value="1" {{ request('featured') == '1' ? 'selected' : '' }}>Oui</option>
                                    <option value="0" {{ request('featured') == '0' ? 'selected' : '' }}>Non</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="form-group">
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-search"></i> Filtrer
                                </button>
                                <a href="{{ route('admin.dishes.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-undo"></i> Réinitialiser
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Liste des plats --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Liste des plats ({{ $dishes->total() }})</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Plat</th>
                                    <th>Catégorie</th>
                                    <th>Région</th>
                                    <th>Prix</th>
                                    <th>Préparation</th>
                                    <th>Vedette</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dishes as $dish)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @php
                                                $image = $dish->images->where('is_primary', true)->first() ?? $dish->images->first();
                                            @endphp
                                            <img src="{{ $image ? asset($image->image_url) : asset('admin-assets/img/example-image.jpg') }}" 
                                                 alt="{{ $dish->name }}" 
                                                 class="rounded mr-3" width="60" height="45" style="object-fit: cover;">
                                            <div>
                                                <strong>{{ $dish->name }}</strong>
                                                @if($dish->name_local)
                                                    <br><small class="text-muted">{{ $dish->name_local }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-info">{{ $dish->category }}</span></td>
                                    <td>{{ $dish->region ?? '-' }}</td>
                                    <td>
                                        <span class="font-weight-bold">{{ number_format($dish->price, 0, ',', ' ') }} FCFA</span>
                                    </td>
                                    <td>
                                        @if($dish->preparation_time)
                                            <i class="fas fa-clock"></i> {{ $dish->preparation_time }} min
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <label class="custom-switch">
                                            <input type="checkbox" class="custom-switch-input toggle-featured" 
                                                   data-id="{{ $dish->id }}" {{ $dish->featured ? 'checked' : '' }}>
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.dishes.show', $dish) }}" 
                                               class="btn btn-sm btn-info" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.dishes.edit', $dish) }}" 
                                               class="btn btn-sm btn-warning" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger btn-delete" 
                                                    data-id="{{ $dish->id }}" title="Supprimer">
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
                                                <i class="fas fa-utensils"></i>
                                            </div>
                                            <h2>Aucun plat</h2>
                                            <p class="lead">Aucun plat ne correspond à vos critères.</p>
                                            <a href="{{ route('admin.dishes.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus"></i> Ajouter un plat
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($dishes->hasPages())
                <div class="card-footer">
                    {{ $dishes->withQueryString()->links() }}
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
    // Toggle featured
    $('.toggle-featured').change(function() {
        var dishId = $(this).data('id');
        var featured = $(this).is(':checked') ? 1 : 0;
        
        $.ajax({
            url: '{{ route("admin.dishes.index") }}/' + dishId + '/toggle-featured',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                featured: featured
            },
            success: function(response) {
                iziToast.success({
                    title: 'Succès',
                    message: response.message,
                    position: 'topRight'
                });
            },
            error: function() {
                iziToast.error({
                    title: 'Erreur',
                    message: 'Une erreur est survenue.',
                    position: 'topRight'
                });
            }
        });
    });
    
    // Delete
    $('.btn-delete').click(function() {
        var dishId = $(this).data('id');
        
        Swal.fire({
            title: 'Supprimer ce plat ?',
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
                    url: '{{ route("admin.dishes.index") }}/' + dishId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire('Supprimé!', 'Le plat a été supprimé.', 'success')
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
