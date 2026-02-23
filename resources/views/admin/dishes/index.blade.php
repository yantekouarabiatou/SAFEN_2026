@extends('layouts.admin')

@section('title', 'Liste des Plats')

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

        .dish-img {
            width: 60px;
            height: 45px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #e9ecef;
        }

        .dish-img-placeholder {
            width: 60px;
            height: 45px;
            background: linear-gradient(135deg, var(--benin-green), #006d40);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-weight: bold;
            font-size: 1.2rem;
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

        .toggle-featured-label {
            cursor: pointer;
        }
    </style>
@endpush

@section('content')
    <div class="section-header">
        <h1>
            <i data-feather="coffee" class="mr-2"></i> Liste des Plats
        </h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item active">Plats</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Plats enregistrés</h4>
                        <a href="{{ route('admin.dishes.create') }}" class="btn btn-benin-green btn-icon icon-left">
                            <i data-feather="plus-circle"></i> Ajouter un plat
                        </a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="dishesTable" class="table table-striped table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Nom</th>
                                        <th>Catégorie</th>
                                        <th>Région</th>
                                        <th>Prix (FCFA)</th>
                                        <th>Préparation</th>
                                        <th>Vedette</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dishes as $dish)
                                        @php
                                            $image = $dish->images->where('is_primary', true)->first() ?? $dish->images->first();
                                        @endphp
                                        <tr>
                                            <td>
                                                @if($image)
                                                    <img src="{{ asset($image->image_url) }}"
                                                         alt="{{ $dish->name }}"
                                                         class="dish-img">
                                                @else
                                                    <div class="dish-img-placeholder">
                                                        {{ strtoupper(substr($dish->name, 0, 2)) }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="font-weight-bold">{{ $dish->name }}</div>
                                                @if($dish->name_local)
                                                    <small class="text-muted">{{ $dish->name_local }}</small>
                                                @endif
                                                @if($dish->vendor)
                                                    <br><small class="text-muted">
                                                        <i data-feather="store" style="width:12px;height:12px;"></i>
                                                        {{ $dish->vendor->name }}
                                                    </small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-info">{{ $dish->category }}</span>
                                            </td>
                                            <td>{{ $dish->region ?? '-' }}</td>
                                            <td>
                                                <span class="font-weight-bold">
                                                    {{ number_format($dish->price, 0, ',', ' ') }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($dish->preparation_time)
                                                    <i data-feather="clock" style="width:14px;height:14px;"></i>
                                                    {{ $dish->preparation_time }} min
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <label class="custom-switch toggle-featured-label mb-0">
                                                    <input type="checkbox"
                                                           class="custom-switch-input toggle-featured"
                                                           data-id="{{ $dish->id }}"
                                                           {{ $dish->featured ? 'checked' : '' }}>
                                                    <span class="custom-switch-indicator"></span>
                                                </label>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.dishes.show', $dish) }}"
                                                       class="btn btn-info"
                                                       title="Voir">
                                                        <i data-feather="eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.dishes.edit', $dish) }}"
                                                       class="btn btn-warning"
                                                       title="Modifier">
                                                        <i data-feather="edit"></i>
                                                    </a>
                                                    <button type="button"
                                                            class="btn btn-danger delete-dish-btn"
                                                            data-id="{{ $dish->id }}"
                                                            data-name="{{ $dish->name }}"
                                                            title="Supprimer">
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
        $(document).ready(function () {

            // ─── DataTable ────────────────────────────────────────────────────
            let table = $('#dishesTable').DataTable({
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
                    
                ],
                columnDefs: [
                    { orderable: false, targets: [0, 6, 7] } // Image, Vedette, Actions non triables
                ]
            });

            // ─── Toggle Vedette ───────────────────────────────────────────────
            $('#dishesTable').on('change', '.toggle-featured', function () {
                const dishId  = $(this).data('id');
                const featured = $(this).is(':checked') ? 1 : 0;
                const $toggle  = $(this);

                $.ajax({
                    url: '/admin/dishes/' + dishId + '/toggle-featured',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        featured: featured
                    },
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Mis à jour',
                            text: response.message ?? 'Statut vedette modifié.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    },
                    error: function () {
                        // Remettre le toggle à son état initial en cas d'erreur
                        $toggle.prop('checked', !featured);
                        Swal.fire('Erreur', 'Impossible de modifier le statut.', 'error');
                    }
                });
            });

            // ─── Suppression ──────────────────────────────────────────────────
            $('#dishesTable').on('click', '.delete-dish-btn', function () {
                const id   = $(this).data('id');
                const name = $(this).data('name');

                Swal.fire({
                    title: 'Supprimer ce plat ?',
                    text: `Le plat "${name}" sera supprimé définitivement.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Oui, supprimer',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route("admin.dishes.destroy", ":id") }}'.replace(':id', id),
                            type: 'DELETE',
                            data: { _token: '{{ csrf_token() }}' },
                            success: function () {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Supprimé !',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => location.reload());
                            },
                            error: function (xhr) {
                                Swal.fire('Erreur', xhr.responseJSON?.message ?? 'Impossible de supprimer.', 'error');
                            }
                        });
                    }
                });
            });

            // ─── Rafraîchir Feather icons après rendu DataTables ─────────────
            table.on('draw', function () {
                feather.replace();
            });

            feather.replace();
        });
    </script>
@endpush
