@extends('layouts.admin')

@section('title', 'Gestion des utilisateurs')

@section('content')
<div class="section-header">
    <h1>Utilisateurs</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item active">Utilisateurs</div>
    </div>
</div>

<div class="section-body">
    {{-- Statistiques --}}
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                    <i class="fas fa-users"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Total utilisateurs</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['total'] ?? 0 }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Actifs ce mois</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['active_this_month'] ?? 0 }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                    <i class="fas fa-hands"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Artisans</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['artisans'] ?? 0 }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Administrateurs</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['admins'] ?? 0 }}
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
                    <div class="card-header-action">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Ajouter un utilisateur
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.index') }}" method="GET" class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Recherche</label>
                                <input type="text" name="search" class="form-control" 
                                       value="{{ request('search') }}" placeholder="Nom, email...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Rôle</label>
                                <select name="role" class="form-control">
                                    <option value="">Tous</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                            {{ ucfirst($role->name) }}
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
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Date inscription</label>
                                <input type="date" name="registered_after" class="form-control" 
                                       value="{{ request('registered_after') }}">
                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="form-group">
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-search"></i> Filtrer
                                </button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-undo"></i> Réinitialiser
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Liste des utilisateurs --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Liste des utilisateurs ({{ $users->total() }})</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Utilisateur</th>
                                    <th>Rôle(s)</th>
                                    <th>Commandes</th>
                                    <th>Inscrit le</th>
                                    <th>Dernière activité</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $user->profile_photo_url ?? asset('admin-assets/img/avatar/avatar-1.png') }}" 
                                                 alt="{{ $user->name }}" 
                                                 class="rounded-circle mr-3" width="45" height="45" style="object-fit: cover;">
                                            <div>
                                                <strong>{{ $user->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $user->email }}</small>
                                                @if($user->phone)
                                                    <br><small class="text-muted"><i class="fas fa-phone"></i> {{ $user->phone }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @foreach($user->roles as $role)
                                            @switch($role->name)
                                                @case('admin')
                                                    <span class="badge badge-danger">Admin</span>
                                                    @break
                                                @case('artisan')
                                                    <span class="badge badge-warning">Artisan</span>
                                                    @break
                                                @case('vendor')
                                                    <span class="badge badge-info">Vendeur</span>
                                                    @break
                                                @default
                                                    <span class="badge badge-light">{{ ucfirst($role->name) }}</span>
                                            @endswitch
                                        @endforeach
                                        @if($user->roles->isEmpty())
                                            <span class="badge badge-secondary">Utilisateur</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-light">{{ $user->orders_count ?? $user->orders->count() }} commandes</span>
                                    </td>
                                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        @if($user->last_login_at)
                                            {{ \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() }}
                                        @else
                                            <span class="text-muted">Jamais</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.users.show', $user) }}" 
                                               class="btn btn-sm btn-info" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user) }}" 
                                               class="btn btn-sm btn-warning" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($user->id !== auth()->id())
                                            <button type="button" class="btn btn-sm btn-danger btn-delete" 
                                                    data-id="{{ $user->id }}" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="empty-state">
                                            <div class="empty-state-icon bg-light">
                                                <i class="fas fa-users"></i>
                                            </div>
                                            <h2>Aucun utilisateur</h2>
                                            <p class="lead">Aucun utilisateur ne correspond à vos critères.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($users->hasPages())
                <div class="card-footer">
                    {{ $users->withQueryString()->links() }}
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
        var userId = $(this).data('id');
        
        Swal.fire({
            title: 'Supprimer cet utilisateur ?',
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
                    url: '{{ route("admin.users.index") }}/' + userId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire('Supprimé!', 'L\'utilisateur a été supprimé.', 'success')
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
