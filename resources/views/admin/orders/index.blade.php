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
                                @foreach($orders as $order)
                                    <tr>
                                        <td><a href="{{ route('admin.orders.show', $order->id) }}">{{ $order->order_number }}</a></td>
                                        <td>
                                            @if($order->user_id)
                                                {{ $order->user->name ?? 'Utilisateur #' . $order->user_id }}
                                            @else
                                                {{ $order->guest_name ?? 'Anonyme' }}
                                            @endif
                                        </td>
                                        <td>{{ $order->formatted_total }}</td>
                                        <td>{!! app('App\\Http\\Controllers\\Admin\\OrderController')->getStatusBadge($order->status ?? $order->order_status) !!}</td>
                                        <td>{!! app('App\\Http\\Controllers\\Admin\\OrderController')->getPaymentStatusBadge($order->payment_status ?? $order->payment_status) !!}</td>
                                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @php $orderStatus = $order->status ?? $order->order_status; @endphp
                                            @if($orderStatus === 'pending')
                                                <form action="{{ route('admin.orders.validate', $order->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" title="Valider" onclick="return confirm('Valider cette commande ?')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.orders.reject', $order->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Refuser" onclick="return confirm('Refuser cette commande ?')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-sm btn-warning" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{-- Pagination handled by DataTables (server-side) --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="{{ asset('admin-assets/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">

    <!-- Boutons DataTables (export) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">

    <style>
        .dataTables_wrapper .dataTables_paginate .paginate_button { padding: 0.3rem 0.6rem !important; margin: 0 2px !important; border-radius:6px !important; }
        .dt-buttons { margin-bottom: 0.5rem !important; }
    </style>
@endpush

@push('scripts')
    <!-- DataTables JS -->
    <script src="{{ asset('admin-assets/bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin-assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>

    <!-- DataTables Buttons (export) -->
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    <script>
    $(function() {
        $('#orders-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                { extend: 'copy', text: '<i data-feather="copy"></i> Copier', className: 'btn btn-secondary btn-sm' },
                { extend: 'excel', text: '<i data-feather="file-text"></i> Excel', className: 'btn btn-success btn-sm' },
                { extend: 'pdf', text: '<i data-feather="file"></i> PDF', className: 'btn btn-danger btn-sm' }
            ],
            ajax: {
                url: "{{ route('admin.orders.index') }}",
                data: function (d) {
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
            language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json' }
        });

        // Refresh feather icons after DataTable renders buttons
        $(document).on('draw.dt', function() { if (window.feather) feather.replace(); });
    });
    </script>
@endpush