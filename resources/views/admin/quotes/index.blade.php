@extends('layouts.admin')

@section('title', 'Gestion des devis')

@section('content')
<div class="section-header">
    <h1>Demandes de devis</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item active">Devis</div>
    </div>
</div>

<div class="section-body">
    {{-- Statistiques --}}
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                    <i class="fas fa-file-invoice"></i>
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
                <div class="card-icon bg-info">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Envoyés</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['sent'] ?? 0 }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                    <i class="fas fa-check-double"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Acceptés</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['accepted'] ?? 0 }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                    <i class="fas fa-times"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Refusés</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['rejected'] ?? 0 }}
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
                    <form action="{{ route('admin.quotes.index') }}" method="GET" class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Recherche</label>
                                <input type="text" name="search" class="form-control" 
                                       value="{{ request('search') }}" placeholder="N° devis, client...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Statut</label>
                                <select name="status" class="form-control">
                                    <option value="">Tous</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                                    <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Envoyé</option>
                                    <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepté</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Refusé</option>
                                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expiré</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Date début</label>
                                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Date fin</label>
                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="form-group">
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-search"></i> Filtrer
                                </button>
                                <a href="{{ route('admin.quotes.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-undo"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Liste des devis --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Demandes de devis ({{ $quotes->total() }})</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>N° Devis</th>
                                    <th>Client</th>
                                    <th>Produit/Service</th>
                                    <th>Budget estimé</th>
                                    <th>Date demande</th>
                                    <th>Statut</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($quotes as $quote)
                                <tr>
                                    <td>
                                        <strong>#{{ $quote->quote_number ?? $quote->id }}</strong>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($quote->user)
                                            <img src="{{ $quote->user->profile_photo_url ?? asset('admin-assets/img/avatar/avatar-1.png') }}" 
                                                 alt="{{ $quote->user->name ?? $quote->name }}" 
                                                 class="rounded-circle mr-2" width="35" height="35">
                                            <div>
                                                <strong>{{ $quote->user->name ?? $quote->name }}</strong>
                                                <br><small class="text-muted">{{ $quote->user->email ?? $quote->email }}</small>
                                            </div>
                                            @else
                                            <div>
                                                <strong>{{ $quote->name }}</strong>
                                                <br><small class="text-muted">{{ $quote->email }}</small>
                                            </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($quote->product)
                                            <span class="badge badge-info">Produit</span>
                                            {{ Str::limit($quote->product->name, 25) }}
                                        @elseif($quote->artisan)
                                            <span class="badge badge-warning">Commande perso.</span>
                                            {{ Str::limit($quote->description, 25) }}
                                        @else
                                            {{ Str::limit($quote->description ?? $quote->service_type, 30) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($quote->budget)
                                            {{ number_format($quote->budget, 0, ',', ' ') }} FCFA
                                        @else
                                            <span class="text-muted">Non spécifié</span>
                                        @endif
                                    </td>
                                    <td>{{ $quote->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        @switch($quote->status ?? 'pending')
                                            @case('pending')
                                                <span class="badge badge-warning">En attente</span>
                                                @break
                                            @case('sent')
                                                <span class="badge badge-info">Envoyé</span>
                                                @break
                                            @case('accepted')
                                                <span class="badge badge-success">Accepté</span>
                                                @break
                                            @case('rejected')
                                                <span class="badge badge-danger">Refusé</span>
                                                @break
                                            @case('expired')
                                                <span class="badge badge-secondary">Expiré</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.quotes.show', $quote) }}" 
                                               class="btn btn-sm btn-info" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-primary dropdown-toggle" 
                                                    data-toggle="dropdown" title="Changer le statut">
                                                <i class="fas fa-exchange-alt"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item update-status" href="#" data-id="{{ $quote->id }}" data-status="pending">
                                                    <i class="fas fa-clock text-warning"></i> En attente
                                                </a>
                                                <a class="dropdown-item update-status" href="#" data-id="{{ $quote->id }}" data-status="sent">
                                                    <i class="fas fa-paper-plane text-info"></i> Envoyé
                                                </a>
                                                <a class="dropdown-item update-status" href="#" data-id="{{ $quote->id }}" data-status="accepted">
                                                    <i class="fas fa-check text-success"></i> Accepté
                                                </a>
                                                <a class="dropdown-item update-status" href="#" data-id="{{ $quote->id }}" data-status="rejected">
                                                    <i class="fas fa-times text-danger"></i> Refusé
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="empty-state">
                                            <div class="empty-state-icon bg-light">
                                                <i class="fas fa-file-invoice"></i>
                                            </div>
                                            <h2>Aucun devis</h2>
                                            <p class="lead">Aucune demande de devis ne correspond à vos critères.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($quotes->hasPages())
                <div class="card-footer">
                    {{ $quotes->withQueryString()->links() }}
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
    $('.update-status').click(function(e) {
        e.preventDefault();
        var quoteId = $(this).data('id');
        var status = $(this).data('status');
        
        $.ajax({
            url: '{{ route("admin.quotes.index") }}/' + quoteId + '/status',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                status: status
            },
            success: function(response) {
                iziToast.success({
                    title: 'Succès',
                    message: 'Statut mis à jour',
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
});
</script>
@endpush
