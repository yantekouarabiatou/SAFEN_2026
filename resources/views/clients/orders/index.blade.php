@extends('layouts.admin')

@section('title', 'Mes commandes')

@push('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Liste de mes commandes</h4>
                <div class="card-header-action">
                    <a href="{{ route('products.index') }}" class="btn btn-primary">
                        <i class="fas fa-shopping-cart"></i> Continuer mes achats
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped" id="orders-table">
                        <thead>
                            <tr>
                                <th>N° commande</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Statut</th>
                                <th>Paiement</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td><strong>#{{ $order->order_number ?? $order->id }}</strong></td>
                                <td data-order="{{ $order->created_at->timestamp }}">{{ $order->created_at->format('d/m/Y') }}</td>
                                <td>{{ $order->formatted_total }}</td>
                                <td>{!! $order->status_badge !!}</td>
                                <td>{!! $order->payment_status_badge !!}</td>
                                <td>
                                    <a href="{{ route('client.orders.show', $order) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Détails
                                    </a>
                                    @if($order->order_status === 'pending')
                                    <form action="{{ route('client.orders.cancel', $order) }}" method="POST" class="d-inline" onsubmit="return confirm('Annuler cette commande ?')">
                                        @csrf
                                        @method('PUT')
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-times"></i> Annuler</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="empty-state">
                                        <div class="empty-state-icon bg-info">
                                            <i class="fas fa-shopping-bag"></i>
                                        </div>
                                        <h2>Aucune commande</h2>
                                        <p class="lead">Vous n'avez pas encore passé de commande.</p>
                                        <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">
                                            Découvrir nos produits
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($orders, 'links'))
                    <div class="d-flex justify-content-center mt-3">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
    // Si le tableau n'est pas vide, on initialise DataTables
    if ($('#orders-table tbody tr').length > 1 || ($('#orders-table tbody tr').length === 1 && $('#orders-table tbody td').length > 1)) {
        $('#orders-table').DataTable({
            "paging": false,      // Désactive la pagination de DataTables car nous utilisons la pagination Laravel
            "info": false,        // Désactive l'affichage des informations "Showing X to Y of Z entries"
            "order": [[1, "desc"]], // Tri par défaut sur la colonne Date (index 1) en descendant
            "language": {
                "search": "Rechercher:",
                "zeroRecords": "Aucune commande trouvée",
                "emptyTable": "Aucune commande disponible",
                "lengthMenu": "Afficher _MENU_ éléments",
                "info": "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
                "infoEmpty": "Affichage 0 à 0 sur 0 éléments",
                "infoFiltered": "(filtré de _MAX_ éléments au total)",
                "paginate": {
                    "first": "Premier",
                    "last": "Dernier",
                    "next": "Suivant",
                    "previous": "Précédent"
                }
            }
        });
    }
});
</script>
@endpush