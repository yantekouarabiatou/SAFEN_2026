@extends('layouts.admin')

@section('title', 'Gestion des commandes')

@section('content')
<div class="section-header">
    <h1>Commandes</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item active">Commandes</div>
    </div>
</div>

<div class="section-body">
    {{-- Statistiques rapides --}}
    <div class="row">
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
                <div class="card-icon bg-info">
                    <i class="fas fa-cog"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>En traitement</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['processing'] ?? 0 }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>En livraison</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['shipped'] ?? 0 }}
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
                        <h4>Livrées</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['delivered'] ?? 0 }}
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
                    <form action="{{ route('admin.orders.index') }}" method="GET" class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Recherche</label>
                                <input type="text" name="search" class="form-control" 
                                       value="{{ request('search') }}" placeholder="N° commande, client...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Statut</label>
                                <select name="status" class="form-control">
                                    <option value="">Tous</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmée</option>
                                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>En traitement</option>
                                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Expédiée</option>
                                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Livrée</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulée</option>
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
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Paiement</label>
                                <select name="payment_status" class="form-control">
                                    <option value="">Tous</option>
                                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>En attente</option>
                                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Payé</option>
                                    <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Échoué</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="form-group">
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-search"></i> Filtrer
                                </button>
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-undo"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Liste des commandes --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Liste des commandes ({{ $orders->total() }})</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>N° Commande</th>
                                    <th>Client</th>
                                    <th>Articles</th>
                                    <th>Total</th>
                                    <th>Paiement</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                <tr>
                                    <td>
                                        <strong>#{{ $order->order_number ?? $order->id }}</strong>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $order->user->profile_photo_url ?? asset('admin-assets/img/avatar/avatar-1.png') }}" 
                                                 alt="{{ $order->user->name }}" 
                                                 class="rounded-circle mr-2" width="35" height="35">
                                            <div>
                                                <strong>{{ $order->user->name }}</strong>
                                                <br><small class="text-muted">{{ $order->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-light">{{ $order->items->count() }} article(s)</span>
                                    </td>
                                    <td>
                                        <strong>{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</strong>
                                    </td>
                                    <td>
                                        @switch($order->payment_status ?? 'pending')
                                            @case('paid')
                                                <span class="badge badge-success">Payé</span>
                                                @break
                                            @case('pending')
                                                <span class="badge badge-warning">En attente</span>
                                                @break
                                            @case('failed')
                                                <span class="badge badge-danger">Échoué</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        @switch($order->status)
                                            @case('pending')
                                                <span class="badge badge-warning">En attente</span>
                                                @break
                                            @case('confirmed')
                                                <span class="badge badge-info">Confirmée</span>
                                                @break
                                            @case('processing')
                                                <span class="badge badge-primary">En traitement</span>
                                                @break
                                            @case('shipped')
                                                <span class="badge badge-info">Expédiée</span>
                                                @break
                                            @case('delivered')
                                                <span class="badge badge-success">Livrée</span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge badge-danger">Annulée</span>
                                                @break
                                            @default
                                                <span class="badge badge-secondary">{{ $order->status }}</span>
                                        @endswitch
                                    </td>
                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.orders.show', $order) }}" 
                                               class="btn btn-sm btn-info" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-primary dropdown-toggle" 
                                                    data-toggle="dropdown" title="Changer le statut">
                                                <i class="fas fa-exchange-alt"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item update-status" href="#" data-id="{{ $order->id }}" data-status="pending">
                                                    <i class="fas fa-clock text-warning"></i> En attente
                                                </a>
                                                <a class="dropdown-item update-status" href="#" data-id="{{ $order->id }}" data-status="confirmed">
                                                    <i class="fas fa-check text-info"></i> Confirmée
                                                </a>
                                                <a class="dropdown-item update-status" href="#" data-id="{{ $order->id }}" data-status="processing">
                                                    <i class="fas fa-cog text-primary"></i> En traitement
                                                </a>
                                                <a class="dropdown-item update-status" href="#" data-id="{{ $order->id }}" data-status="shipped">
                                                    <i class="fas fa-shipping-fast text-info"></i> Expédiée
                                                </a>
                                                <a class="dropdown-item update-status" href="#" data-id="{{ $order->id }}" data-status="delivered">
                                                    <i class="fas fa-check-double text-success"></i> Livrée
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item update-status" href="#" data-id="{{ $order->id }}" data-status="cancelled">
                                                    <i class="fas fa-times text-danger"></i> Annulée
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="empty-state">
                                            <div class="empty-state-icon bg-light">
                                                <i class="fas fa-shopping-cart"></i>
                                            </div>
                                            <h2>Aucune commande</h2>
                                            <p class="lead">Aucune commande ne correspond à vos critères.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($orders->hasPages())
                <div class="card-footer">
                    {{ $orders->withQueryString()->links() }}
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
        var orderId = $(this).data('id');
        var status = $(this).data('status');
        
        $.ajax({
            url: '{{ route("admin.orders.index") }}/' + orderId + '/status',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                status: status
            },
            success: function(response) {
                Swal.fire('Succès!', 'Le statut a été mis à jour.', 'success')
                    .then(() => location.reload());
            },
            error: function(xhr) {
                Swal.fire('Erreur', xhr.responseJSON?.message || 'Une erreur est survenue.', 'error');
            }
        });
    });
});
</script>
@endpush
