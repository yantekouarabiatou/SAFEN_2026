@extends('layouts.admin')

@section('title', 'Gestion des devis')

@push('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="{{ asset('admin-assets/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">

    <style>
        :root {
            --green:  #008751;
            --yellow: #fcd116;
            --red:    #e8112d;
            --border: #e4e8e2;
            --bg:     #f7f9f6;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.4rem 0.8rem !important;
            margin: 0 2px !important;
            border-radius: 6px !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--green) !important;
            color: white !important;
            border-color: var(--green) !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
            background: #e6f4ed !important;
            border-color: var(--green) !important;
            color: var(--green) !important;
        }
        .dt-buttons {
            margin-bottom: 0 !important;
        }
        .dt-button {
            margin-right: 4px !important;
        }
        /* Ajustement pour le scroll horizontal */
        .dataTables_wrapper .dataTables_scrollHead,
        .dataTables_wrapper .dataTables_scrollBody {
            border: none !important;
        }
        .dataTables_scrollBody table {
            border-collapse: separate;
            border-spacing: 0;
        }
        /* Pour que les badges restent lisibles */
        .badge {
            font-size: 0.8rem;
            padding: 0.4rem 0.6rem;
        }
        .avatar-initials-circle {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background-color: #008751; /* vert Benin */
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 14px;
    text-transform: uppercase;
    flex-shrink: 0;
}
/* Version plus grande pour la page de profil */
.profile-widget-picture.initials {
    width: 100px;
    height: 100px;
    font-size: 32px;
}
    </style>
@endpush

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

    {{-- Filtres externes (rafraîchissent la page) --}}
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

    {{-- Liste des devis avec DataTable --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Demandes de devis ({{ $quotes->total() }})</h4>
                </div>
                <div class="card-body">
                    {{-- Tableau sans .table-responsive (DataTable gère le scroll) --}}
                    <table id="quotesTable" class="table table-striped table-hover" style="width:100%">
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
                                        @php
                                            // Calcul des initiales (même logique)
                                            $clientName = $quote->user->name ?? $quote->name;
                                            $nameParts = explode(' ', trim($clientName));
                                            $initials = '';
                                            foreach ($nameParts as $part) {
                                                if (!empty($part)) {
                                                    $initials .= strtoupper(substr($part, 0, 1));
                                                }
                                            }
                                            if (strlen($initials) < 2) {
                                                $initials = strtoupper(substr($clientName, 0, 2));
                                            }
                                        @endphp

                                        @if($quote->user && $quote->user->avatar)
                                            <img src="{{ $quote->user->avatar_url }}"
                                                alt="{{ $clientName }}"
                                                class="rounded-circle mr-2" width="35" height="35">
                                        @else
                                            <div class="avatar-initials-circle mr-2"
                                                style="width:35px; height:35px; font-size:14px;">
                                                {{ $initials }}
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
                            {{-- DataTables affichera un message si vide, mais on laisse le tbody vide --}}
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- On retire la pagination Laravel --}}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('admin-assets/bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin-assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    <script>
    $(document).ready(function () {
        // Initialisation de DataTable
        const table = $('#quotesTable').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json' },
            responsive: false,      // ← désactive le responsive qui cache les colonnes
            scrollX: true,           // ← active le défilement horizontal
            scrollCollapse: true,
            dom: 'Bfrtip',
            buttons: [
                { extend: 'copy',  text: '<i class="fas fa-copy"></i> Copier',     className: 'btn btn-secondary btn-sm' },
                { extend: 'excel', text: '<i class="fas fa-file-excel"></i> Excel',  className: 'btn btn-success btn-sm'  },
                { extend: 'pdf',   text: '<i class="fas fa-file-pdf"></i> PDF',      className: 'btn btn-danger btn-sm'   },
            ],
            columnDefs: [
                { orderable: false, targets: [6] } // Colonne Actions non triable
            ],
            order: [[4, 'desc']] // Tri par date de demande par défaut
        });

        // Réattacher les événements après chaque redessin (pagination, recherche)
        table.on('draw', function () {
            feather.replace(); // si vous utilisez feather icons
        });

        // Gestionnaire pour le changement de statut via AJAX
        $('#quotesTable').on('click', '.update-status', function (e) {
            e.preventDefault();
            var quoteId = $(this).data('id');
            var status = $(this).data('status');

            $.ajax({
                url: '{{ route("admin.quotes.update-status", ":id") }}'.replace(':id', quoteId),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: status
                },
                success: function (response) {
                    iziToast.success({
                        title: 'Succès',
                        message: 'Statut mis à jour',
                        position: 'topRight'
                    });
                    location.reload(); // recharger pour voir le changement
                },
                error: function () {
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
