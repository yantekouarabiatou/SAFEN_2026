@extends('layouts.admin')

@section('title', 'Mes Commandes')

@section('content')
<div class="section-header">
    <h1>Mes Commandes</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard.artisan') }}">Dashboard</a></div>
        <div class="breadcrumb-item">Mes Commandes</div>
    </div>
</div>

<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Liste des commandes</h4>
                    <div class="card-header-action">
                        <form class="form-inline">
                            <div class="input-group">
                                <select class="form-control" name="status">
                                    <option value="">Tous les statuts</option>
                                    <option value="pending">En attente</option>
                                    <option value="confirmed">Confirmée</option>
                                    <option value="processing">En cours</option>
                                    <option value="shipped">Expédiée</option>
                                    <option value="delivered">Livrée</option>
                                    <option value="cancelled">Annulée</option>
                                </select>
                                <div class="input-group-btn">
                                    <button class="btn btn-primary">Filtrer</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped" id="orders-table">
                            <thead>
                                <tr>
                                    <th>N° Commande</th>
                                    <th>Client</th>
                                    <th>Produit</th>
                                    <th>Quantité</th>
                                    <th>Total</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders ?? [] as $order)
                                <tr>
                                    <td>
                                        <strong>#{{ $order->order->order_number ?? $order->id }}</strong>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $order->order->user->avatar_url ?? asset('images/default-avatar.png') }}" 
                                                 alt="avatar" 
                                                 class="rounded-circle mr-2"
                                                 width="35">
                                            <div>
                                                <strong>{{ $order->order->user->name ?? 'Client' }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $order->order->user->email ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $order->product->images->first()->image_url ?? asset('images/default-product.jpg') }}" 
                                                 alt="{{ $order->product->name ?? 'Produit' }}" 
                                                 class="rounded mr-2"
                                                 width="40">
                                            <span>{{ $order->product->name ?? 'Produit supprimé' }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $order->quantity ?? 1 }}</td>
                                    <td class="font-weight-bold text-primary">
                                        {{ number_format(($order->price ?? 0) * ($order->quantity ?? 1), 0, ',', ' ') }} FCFA
                                    </td>
                                    <td>
                                        {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '-' }}
                                    </td>
                                    <td>
                                        @php
                                            $status = $order->order->status ?? 'pending';
                                            $statusLabels = [
                                                'pending' => ['label' => 'En attente', 'class' => 'warning'],
                                                'confirmed' => ['label' => 'Confirmée', 'class' => 'info'],
                                                'processing' => ['label' => 'En cours', 'class' => 'primary'],
                                                'shipped' => ['label' => 'Expédiée', 'class' => 'info'],
                                                'delivered' => ['label' => 'Livrée', 'class' => 'success'],
                                                'cancelled' => ['label' => 'Annulée', 'class' => 'danger'],
                                            ];
                                            $statusInfo = $statusLabels[$status] ?? ['label' => ucfirst($status), 'class' => 'secondary'];
                                        @endphp
                                        <span class="badge badge-{{ $statusInfo['class'] }}">
                                            {{ $statusInfo['label'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="#" 
                                               class="btn btn-sm btn-info" 
                                               title="Voir les détails"
                                               data-toggle="modal"
                                               data-target="#orderModal{{ $order->id }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(($order->order->status ?? 'pending') === 'pending')
                                            <button type="button" 
                                                    class="btn btn-sm btn-success" 
                                                    title="Confirmer">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="empty-state">
                                            <div class="empty-state-icon bg-warning">
                                                <i class="fas fa-shopping-cart"></i>
                                            </div>
                                            <h2>Aucune commande</h2>
                                            <p class="lead">Vous n'avez pas encore reçu de commandes.</p>
                                            <p>Partagez vos produits pour attirer des clients !</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($orders instanceof \Illuminate\Pagination\LengthAwarePaginator && $orders->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $orders->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
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
                        {{ collect($orders ?? [])->filter(fn($o) => ($o->order->status ?? '') === 'pending')->count() }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                    <i class="fas fa-spinner"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>En cours</h4>
                    </div>
                    <div class="card-body">
                        {{ collect($orders ?? [])->filter(fn($o) => in_array($o->order->status ?? '', ['confirmed', 'processing', 'shipped']))->count() }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Livrées</h4>
                    </div>
                    <div class="card-body">
                        {{ collect($orders ?? [])->filter(fn($o) => ($o->order->status ?? '') === 'delivered')->count() }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Annulées</h4>
                    </div>
                    <div class="card-body">
                        {{ collect($orders ?? [])->filter(fn($o) => ($o->order->status ?? '') === 'cancelled')->count() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    @if(count($orders ?? []) > 0)
    $('#orders-table').DataTable({
        "paging": false,
        "info": false,
        "order": [[5, "desc"]],
        "language": {
            "search": "Rechercher:",
            "zeroRecords": "Aucune commande trouvée",
        }
    });
    @endif
});
</script>
@endpush
