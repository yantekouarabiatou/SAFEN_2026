@extends('layouts.admin')

@section('title', 'Liste des Vendeurs')

@push('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="{{ asset('admin-assets/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">

    <!-- Boutons DataTables (export) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">

    <style>
        .table th, .table td {
            vertical-align: middle !important;
        }

        .vendor-logo {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #e9ecef;
        }

        .vendor-logo-placeholder {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--benin-green), #006d40);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .status-approved {
            background-color: var(--benin-green) !important;
            color: white !important;
        }

        .status-pending {
            background-color: #ffc107 !important;
            color: #333 !important;
        }

        .status-rejected {
            background-color: var(--benin-red) !important;
            color: white !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.4rem 0.8rem !important;
            margin: 0 2px !important;
            border-radius: 6px !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--benin-green) !important;
            color: white !important;
            border-color: var(--benin-green) !important;
        }
    </style>
@endpush

@section('content')
    <div class="section-header">
        <h1>
            <i data-feather="users" class="mr-2"></i> Liste des Vendeurs
        </h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item active">Vendeurs</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Vendeurs enregistrés</h4>
                        <a href="{{ route('admin.vendors.create') }}" class="btn btn-benin-green btn-icon icon-left">
                            <i data-feather="plus-circle"></i> Ajouter un vendeur
                        </a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="vendorsTable" class="table table-striped table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Logo</th>
                                        <th>Nom</th>
                                        <th>Type</th>
                                        <th>Ville</th>
                                        <th>Téléphone</th>
                                        <th>Statut</th>
                                        <th>Plats</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vendors as $vendor)
                                        <tr>
                                            <td>
                                                @if($vendor->logo)
                                                    <img src="{{ Storage::url($vendor->logo) }}" alt="Logo" class="vendor-logo">
                                                @else
                                                    <div class="vendor-logo-placeholder">
                                                        {{ strtoupper(substr($vendor->name, 0, 2)) }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="font-weight-bold">{{ $vendor->name }}</div>
                                                @if($vendor->user)
                                                    <small class="text-muted">
                                                        {{ $vendor->user->prenom }} {{ $vendor->user->nom }}
                                                    </small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    {{ $vendor->type_label }}
                                                </span>
                                            </td>
                                            <td>{{ $vendor->city ?? '-' }}</td>
                                            <td>
                                                @if($vendor->phone)
                                                    <a href="tel:{{ $vendor->phone }}">{{ $vendor->phone }}</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($vendor->verified)
                                                    <span class="badge status-approved px-3 py-2">Vérifié</span>
                                                @else
                                                    <span class="badge status-pending px-3 py-2">En attente</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-info px-3 py-2">
                                                    {{ $vendor->dishes_count ?? $vendor->dishes->count() }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.vendors.show', $vendor) }}" 
                                                       class="btn btn-info" 
                                                       title="Voir">
                                                        <i data-feather="eye"></i>
                                                    </a>

                                                    <a href="{{ route('admin.vendors.edit', $vendor) }}" 
                                                       class="btn btn-warning" 
                                                       title="Modifier">
                                                        <i data-feather="edit"></i>
                                                    </a>

                                                    <button type="button" class="btn btn-danger delete-vendor-btn"
                                                            data-id="{{ $vendor->id }}"
                                                            data-name="{{ $vendor->name }}">
                                                        <i data-feather="trash-2"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

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
        $(document).ready(function() {
            let table = $('#vendorsTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json'
                },
                responsive: true,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copy',
                        text: '<i data-feather="copy"></i> Copier',
                        className: 'btn btn-secondary btn-sm'
                    },
                    {
                        extend: 'excel',
                        text: '<i data-feather="file-text"></i> Excel',
                        className: 'btn btn-success btn-sm'
                    },
                    {
                        extend: 'pdf',
                        text: '<i data-feather="file"></i> PDF',
                        className: 'btn btn-danger btn-sm'
                    },
                    {
                        extend: 'print',
                        text: '<i data-feather="printer"></i> Imprimer',
                        className: 'btn btn-info btn-sm'
                    }
                ],
                columnDefs: [
                    { orderable: false, targets: -1 } // Actions non triable
                ]
            });

            // Suppression avec confirmation
            $('.delete-vendor-btn').on('click', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');

                Swal.fire({
                    title: 'Supprimer ce vendeur ?',
                    text: `Le vendeur "${name}" sera supprimé définitivement.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Oui, supprimer',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route("admin.vendors.destroy", ":id") }}'.replace(':id', id),
                            type: 'DELETE',
                            data: { _token: '{{ csrf_token() }}' },
                            success: function() {
                                Swal.fire('Supprimé !', '', 'success').then(() => {
                                    location.reload();
                                });
                            },
                            error: function() {
                                Swal.fire('Erreur', 'Impossible de supprimer', 'error');
                            }
                        });
                    }
                });
            });

            // Rafraîchir Feather icons après chargement DataTables
            feather.replace();
        });
    </script>
@endpush