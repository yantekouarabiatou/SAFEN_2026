@extends('layouts.admin')

@section('title', 'Gestion des commandes')

@section('content')
<div class="section-header">
    <h1>Commandes</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item">Commandes</div>
    </div>
</div>

<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Liste des commandes</h4>
                    <div class="card-header-form">
                        <div class="btn-group">
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">Toutes</a>
                            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="btn btn-warning">En attente</a>
                            <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="btn btn-success">Livrées</a>
                            <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="btn btn-danger">Annulées</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="orders-table">
                            <thead>
                                <tr>
                                    <th>N° commande</th>
                                    <th>Client</th>
                                    <th>Total</th>
                                    <th>Statut commande</th>
                                    <th>Statut paiement</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- DataTable remplira via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- DataTables CSS -->
@endpush

@push('scripts')
<!-- DataTables JS -->

<script>
$(function() {
    $('#orders-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.orders.index') }}",
            data: function (d) {
                // Ajouter le filtre status à la requête AJAX
                d.status = new URLSearchParams(window.location.search).get('status');
            }
        },
        columns: [
            { data: 'order_number', name: 'order_number' },
            { data: 'customer', name: 'customer' },
            { data: 'total', name: 'total' },
            { data: 'order_status', name: 'order_status' },
            { data: 'payment_status', name: 'payment_status' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json'
        }
    });
});
</script>
@endpush