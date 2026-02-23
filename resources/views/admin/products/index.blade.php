@extends('layouts.admin')

@section('title', 'Gestion des produits')

@push('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="{{ asset('admin-assets/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">

    <!-- Boutons DataTables (export) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">

    <style>
        :root {
            --green:   #008751;
            --yellow:  #fcd116;
            --red:     #e8112d;
            --border:  #e4e8e2;
            --bg:      #f7f9f6;
        }

        .table th, .table td { vertical-align: middle !important; }

        /* ── Image produit ───────────────────────── */
        .product-img {
            width: 54px; height: 54px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid var(--border);
        }
        .product-img-placeholder {
            width: 54px; height: 54px;
            background: linear-gradient(135deg, var(--green), #006d40);
            color: white;
            display: flex; align-items: center; justify-content: center;
            border-radius: 8px;
            font-weight: 700; font-size: 1rem;
        }

        /* ── Badges stock ────────────────────────── */
        .badge-in_stock     { background: var(--green)  !important; color:#fff !important; }
        .badge-out_of_stock { background: var(--red)    !important; color:#fff !important; }
        .badge-preorder     { background: var(--yellow) !important; color:#1a1a18 !important; }
        .badge-made_to_order{ background: #17a2b8       !important; color:#fff !important; }

        /* ── DataTables pagination ───────────────── */
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

        /* ── Boutons export ──────────────────────── */
        .dt-buttons { margin-bottom: 0 !important; }
        .dt-button  { margin-right: 4px !important; }
    </style>
@endpush

@section('content')
<div class="section-header">
    <h1>Produits</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item active">Produits</div>
    </div>
</div>

<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Liste des produits</h4>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-benin-green btn-icon icon-left">
                        <i data-feather="plus-circle"></i> Ajouter un produit
                    </a>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="productsTable" class="table table-striped table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Nom</th>
                                    <th>Catégorie</th>
                                    <th>Artisan</th>
                                    <th>Prix (FCFA)</th>
                                    <th>Stock</th>
                                    <th>Vedette</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                <tr>
                                    {{-- Image --}}
                                    <td>
                                        @if($product->images->first())
                                            <img src="{{ asset($product->images->first()->image_url) }}"
                                                 alt="{{ $product->name }}"
                                                 class="product-img">
                                        @else
                                            <div class="product-img-placeholder">
                                                {{ strtoupper(substr($product->name, 0, 2)) }}
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Nom --}}
                                    <td>
                                        <div class="font-weight-bold">{{ Str::limit($product->name, 35) }}</div>
                                        @if($product->name_local)
                                            <small class="text-muted font-italic">{{ $product->name_local }}</small>
                                        @endif
                                    </td>

                                    {{-- Catégorie --}}
                                    <td>
                                        <span class="badge badge-primary">{{ ucfirst($product->category) }}</span>
                                        @if($product->subcategory)
                                            <br><small class="text-muted">{{ $product->subcategory }}</small>
                                        @endif
                                    </td>

                                    {{-- Artisan --}}
                                    <td>{{ $product->artisan->user->name ?? '—' }}</td>

                                    {{-- Prix --}}
                                    <td>
                                        <span class="font-weight-bold">
                                            {{ number_format($product->price, 0, ',', ' ') }}
                                        </span>
                                    </td>

                                    {{-- Stock --}}
                                    <td>
                                        @php
                                            $stockLabels = [
                                                'in_stock'      => 'En stock',
                                                'out_of_stock'  => 'Rupture',
                                                'preorder'      => 'Précommande',
                                                'made_to_order' => 'Sur commande',
                                            ];
                                        @endphp
                                        <span class="badge badge-{{ $product->stock_status }} px-3 py-2">
                                            {{ $stockLabels[$product->stock_status] ?? $product->stock_status }}
                                        </span>
                                    </td>

                                    {{-- Vedette toggle --}}
                                    <td class="text-center">
                                        <label class="custom-switch mb-0">
                                            <input type="checkbox"
                                                   class="custom-switch-input toggle-featured"
                                                   data-id="{{ $product->id }}"
                                                   {{ $product->featured ? 'checked' : '' }}>
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                    </td>

                                    {{-- Actions --}}
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.products.show', $product) }}"
                                               class="btn btn-info" title="Voir">
                                                <i data-feather="eye"></i>
                                            </a>
                                            <a href="{{ route('admin.products.edit', $product) }}"
                                               class="btn btn-warning" title="Modifier">
                                                <i data-feather="edit"></i>
                                            </a>
                                            <button type="button"
                                                    class="btn btn-danger delete-product-btn"
                                                    data-id="{{ $product->id }}"
                                                    data-name="{{ $product->name }}"
                                                    title="Supprimer">
                                                <i data-feather="trash-2"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                    {{-- DataTables gère l'état vide --}}
                                @endforelse
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

    <!-- DataTables Buttons -->
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    <script>
    $(document).ready(function () {

        // ── DataTable ─────────────────────────────────────────
        const table = $('#productsTable').DataTable({
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
                { orderable: false, targets: [0, 6, 7] } // Image, Vedette, Actions
            ],
            order: [[1, 'asc']] // Tri par nom par défaut
        });

        // Rafraîchir Feather après rendu DataTables
        table.on('draw', function () { feather.replace(); });
        feather.replace();

        // ── Toggle vedette ────────────────────────────────────
        $('#productsTable').on('change', '.toggle-featured', function () {
            const productId = $(this).data('id');
            const featured  = $(this).is(':checked') ? 1 : 0;
            const $toggle   = $(this);

            $.ajax({
                url: '{{ url("admin/products") }}/' + productId + '/toggle-featured',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}', featured: featured },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: featured ? '⭐ Mis en vedette' : 'Retiré des vedettes',
                        timer: 1400,
                        showConfirmButton: false,
                        iconColor: '#fcd116',
                        customClass: { popup: 'swal-benin-popup swal-benin-popup--success' }
                    });
                },
                error: function () {
                    $toggle.prop('checked', !featured); // rollback
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Impossible de modifier le statut vedette.',
                        confirmButtonColor: '#e8112d',
                        customClass: { popup: 'swal-benin-popup swal-benin-popup--error' }
                    });
                }
            });
        });

        // ── Suppression ───────────────────────────────────────
        $('#productsTable').on('click', '.delete-product-btn', function () {
            const id   = $(this).data('id');
            const name = $(this).data('name');

            Swal.fire({
                title: 'Supprimer ce produit ?',
                html: `Le produit <strong>${name}</strong> sera supprimé définitivement.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e8112d',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler',
                customClass: {
                    popup:         'swal-benin-popup swal-benin-popup--error',
                    title:         'swal-benin-title',
                    confirmButton: 'swal-btn swal-btn--red',
                }
            }).then(result => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.products.destroy", ":id") }}'.replace(':id', id),
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function () {
                            Swal.fire({
                                icon: 'success',
                                title: 'Supprimé !',
                                text: `${name} a été supprimé.`,
                                timer: 1800,
                                showConfirmButton: false,
                                customClass: { popup: 'swal-benin-popup swal-benin-popup--success' }
                            }).then(() => location.reload());
                        },
                        error: function (xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur',
                                text: xhr.responseJSON?.message ?? 'Impossible de supprimer ce produit.',
                                confirmButtonColor: '#e8112d',
                                customClass: { popup: 'swal-benin-popup swal-benin-popup--error' }
                            });
                        }
                    });
                }
            });
        });

    });
    </script>
@endpush
