@extends('layouts.admin')

@section('title', 'Gestion des artisans')

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

        .table th, .table td { vertical-align: middle !important; }

        .artisan-avatar {
            width: 46px; height: 46px;
            object-fit: cover; border-radius: 50%;
            border: 2px solid var(--border); flex-shrink: 0;
        }
        .artisan-avatar-placeholder {
            width: 46px; height: 46px;
            background: linear-gradient(135deg, var(--green), #006d40);
            color: white; display: flex; align-items: center;
            justify-content: center; border-radius: 50%;
            font-weight: 700; font-size: .95rem; flex-shrink: 0;
        }

        .badge-approved { background: var(--green)  !important; color: #fff !important; }
        .badge-pending  { background: var(--yellow) !important; color: #1a1a18 !important; }
        .badge-rejected { background: var(--red)    !important; color: #fff !important; }

        .rating-stars { color: var(--yellow); font-size: .8rem; letter-spacing: 1px; }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.4rem 0.8rem !important; margin: 0 2px !important; border-radius: 6px !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--green) !important; color: white !important; border-color: var(--green) !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
            background: #e6f4ed !important; border-color: var(--green) !important; color: var(--green) !important;
        }
        .dt-buttons { margin-bottom: 0 !important; }
        .dt-button  { margin-right: 4px !important; }

        /* Ajustement pour le scroll horizontal */
        .dataTables_wrapper .dataTables_scrollHead,
        .dataTables_wrapper .dataTables_scrollBody {
            border: none !important;
        }
        .dataTables_scrollBody table {
            border-collapse: separate;
            border-spacing: 0;
        }
    </style>
@endpush

@section('content')
<div class="section-header">
    <h1>Artisans</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item active">Artisans</div>
    </div>
</div>

<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Liste des artisans</h4>
                    <a href="{{ route('admin.artisans.create') }}" class="btn btn-benin-green btn-icon icon-left">
                        <i data-feather="plus-circle"></i> Ajouter un artisan
                    </a>
                </div>

                <div class="card-body">
                    {{-- Suppression de la div .table-responsive pour éviter la double barre --}}
                    <table id="artisansTable" class="table table-striped table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>Artisan</th>
                                <th>Métier</th>
                                <th>Localisation</th>
                                <th>Expérience</th>
                                <th>Produits</th>
                                <th>Note</th>
                                <th>Statut</th>
                                <th>Vedette</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($artisans as $artisan)
                            <tr>
                                {{-- Artisan --}}
                                <td>
                                    <div class="d-flex align-items-center">
                                        @php
                                            $photo = $artisan->photos->where('is_primary', true)->first()
                                                  ?? $artisan->photos->first();
                                        @endphp
                                        @if($photo)
                                            <img src="{{ asset($photo->photo_url) }}"
                                                 alt="{{ $artisan->user->name }}"
                                                 class="artisan-avatar mr-2">
                                        @else
                                            <div class="artisan-avatar-placeholder mr-2">
                                                {{ strtoupper(substr($artisan->user->name ?? 'A', 0, 2)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-weight-bold">{{ $artisan->user->name ?? '—' }}</div>
                                            <small class="text-muted">{{ $artisan->user->email ?? '' }}</small>
                                            @if($artisan->business_name)
                                                <br><small class="text-muted font-italic">{{ $artisan->business_name }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Métier : craft_label (accessor du modèle) --}}
                                <td>
                                    <span class="badge badge-info px-2 py-1">
                                        {{ $artisan->craft_label }}
                                    </span>
                                </td>

                                {{-- Localisation : location (accessor du modèle = neighborhood + city) --}}
                                <td>{{ $artisan->location ?? '—' }}</td>

                                {{-- Expérience : years_experience (colonne réelle) --}}
                                <td>
                                    @if($artisan->years_experience)
                                        {{ $artisan->years_experience }} ans
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                {{-- Produits --}}
                                <td class="text-center">
                                    <span class="badge badge-light px-2 py-1">
                                        {{ $artisan->products_count ?? $artisan->products->count() }}
                                    </span>
                                </td>

                                {{-- Note : rating_avg (colonne réelle) --}}
                                <td>
                                    @php $rating = round($artisan->rating_avg ?? 0); @endphp
                                    <div class="rating-stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            {{ $i <= $rating ? '★' : '☆' }}
                                        @endfor
                                    </div>
                                    <small class="text-muted">
                                        {{ number_format($artisan->rating_avg ?? 0, 1) }}
                                        ({{ $artisan->rating_count ?? 0 }})
                                    </small>
                                </td>

                                {{-- Statut : status (colonne réelle) --}}
                                <td>
                                    @php
                                        $statusLabels = [
                                            'approved' => 'Approuvé',
                                            'pending'  => 'En attente',
                                            'rejected' => 'Rejeté',
                                        ];
                                    @endphp
                                    <span class="badge badge-{{ $artisan->status }} px-3 py-2">
                                        {{ $statusLabels[$artisan->status] ?? $artisan->status }}
                                    </span>
                                </td>

                                {{-- Vedette : featured (colonne réelle) --}}
                                <td class="text-center">
                                    <label class="custom-switch mb-0">
                                        <input type="checkbox"
                                               class="custom-switch-input toggle-featured"
                                               data-id="{{ $artisan->id }}"
                                               {{ $artisan->featured ? 'checked' : '' }}>
                                        <span class="custom-switch-indicator"></span>
                                    </label>
                                </td>

                                {{-- Actions --}}
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.artisans.show', $artisan) }}"
                                           class="btn btn-info" title="Voir">
                                            <i data-feather="eye"></i>
                                        </a>
                                        <a href="{{ route('admin.artisans.edit', $artisan) }}"
                                           class="btn btn-warning" title="Modifier">
                                            <i data-feather="edit"></i>
                                        </a>

                                        {{-- Approuver / Rejeter uniquement si pending --}}
                                        @if($artisan->isPending())
                                            <button type="button"
                                                    class="btn btn-success approve-btn"
                                                    data-id="{{ $artisan->id }}"
                                                    data-name="{{ $artisan->user->name }}"
                                                    title="Approuver">
                                                <i data-feather="check"></i>
                                            </button>
                                            <button type="button"
                                                    class="btn btn-secondary reject-btn"
                                                    data-id="{{ $artisan->id }}"
                                                    data-name="{{ $artisan->user->name }}"
                                                    title="Rejeter">
                                                <i data-feather="x"></i>
                                            </button>
                                        @endif

                                        <button type="button"
                                                class="btn btn-danger delete-artisan-btn"
                                                data-id="{{ $artisan->id }}"
                                                data-name="{{ $artisan->user->name }}"
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

        const table = $('#artisansTable').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json' },
            responsive: false,      // ← Désactive le mode responsive (plus de colonnes cachées)
            scrollX: true,           // ← Active le défilement horizontal
            scrollCollapse: true,
            dom: 'Bfrtip',
            buttons: [
                { extend: 'copy',  text: '<i data-feather="copy"></i> Copier',     className: 'btn btn-secondary btn-sm' },
                { extend: 'excel', text: '<i data-feather="file-text"></i> Excel',  className: 'btn btn-success btn-sm'  },
                { extend: 'pdf',   text: '<i data-feather="file"></i> PDF',         className: 'btn btn-danger btn-sm'   },
            ],
            columnDefs: [
                { orderable: false, targets: [7, 8] }   // Colonnes Vedette et Actions non triables
            ],
            order: [[0, 'asc']]
        });

        table.on('draw', function () { feather.replace(); });
        feather.replace();

        // ── Toggle vedette ────────────────────────────────────
        $('#artisansTable').on('change', '.toggle-featured', function () {
            const id      = $(this).data('id');
            const featured = $(this).is(':checked') ? 1 : 0;
            const $toggle  = $(this);

            $.ajax({
                url: '{{ url("admin/artisans") }}/' + id + '/toggle-featured',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}', featured: featured },
                success: function () {
                    Swal.fire({
                        icon: 'success',
                        title: featured ? '⭐ Mis en vedette' : 'Retiré des vedettes',
                        timer: 1400, showConfirmButton: false, iconColor: '#fcd116',
                        customClass: { popup: 'swal-benin-popup swal-benin-popup--success' }
                    });
                },
                error: function () {
                    $toggle.prop('checked', !featured);
                    Swal.fire({ icon: 'error', title: 'Erreur',
                        text: 'Impossible de modifier le statut vedette.',
                        customClass: { popup: 'swal-benin-popup swal-benin-popup--error' }
                    });
                }
            });
        });

        // ── Approuver ─────────────────────────────────────────
        $('#artisansTable').on('click', '.approve-btn', function () {
            const id = $(this).data('id'), name = $(this).data('name');

            Swal.fire({
                title: 'Approuver cet artisan ?',
                html: `<strong>${name}</strong> sera visible sur la plateforme.`,
                icon: 'question', showCancelButton: true,
                confirmButtonColor: '#008751', cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui, approuver', cancelButtonText: 'Annuler',
                customClass: { popup: 'swal-benin-popup swal-benin-popup--success',
                               title: 'swal-benin-title', confirmButton: 'swal-btn swal-btn--green' }
            }).then(result => {
                if (!result.isConfirmed) return;
                $.ajax({
                    url: '{{ url("admin/artisans") }}/' + id + '/approve',
                    type: 'POST', data: { _token: '{{ csrf_token() }}' },
                    success: () => Swal.fire({ icon: 'success', title: 'Approuvé !',
                        timer: 1600, showConfirmButton: false,
                        customClass: { popup: 'swal-benin-popup swal-benin-popup--success' }
                    }).then(() => location.reload()),
                    error: xhr => Swal.fire({ icon: 'error', title: 'Erreur',
                        text: xhr.responseJSON?.message ?? 'Une erreur est survenue.',
                        customClass: { popup: 'swal-benin-popup swal-benin-popup--error' }
                    })
                });
            });
        });

        // ── Rejeter ───────────────────────────────────────────
        $('#artisansTable').on('click', '.reject-btn', function () {
            const id = $(this).data('id'), name = $(this).data('name');

            Swal.fire({
                title: 'Rejeter cet artisan ?',
                html: `<p style="font-size:.9rem;color:#6b7068;margin-bottom:10px;">
                           Motif du rejet pour <strong>${name}</strong> (optionnel) :
                       </p>
                       <textarea id="rejection-reason" class="swal2-textarea"
                           placeholder="Ex : Informations insuffisantes..."
                           style="font-size:.88rem;resize:vertical;"></textarea>`,
                icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#e8112d', cancelButtonColor: '#6c757d',
                confirmButtonText: 'Rejeter', cancelButtonText: 'Annuler',
                customClass: { popup: 'swal-benin-popup swal-benin-popup--error',
                               title: 'swal-benin-title', confirmButton: 'swal-btn swal-btn--red' },
                preConfirm: () => document.getElementById('rejection-reason').value
            }).then(result => {
                if (!result.isConfirmed) return;
                $.ajax({
                    url: '{{ url("admin/artisans") }}/' + id + '/reject',
                    type: 'POST', data: { _token: '{{ csrf_token() }}', reason: result.value },
                    success: () => Swal.fire({ icon: 'success', title: 'Rejeté',
                        timer: 1600, showConfirmButton: false,
                        customClass: { popup: 'swal-benin-popup swal-benin-popup--success' }
                    }).then(() => location.reload()),
                    error: xhr => Swal.fire({ icon: 'error', title: 'Erreur',
                        text: xhr.responseJSON?.message ?? 'Une erreur est survenue.',
                        customClass: { popup: 'swal-benin-popup swal-benin-popup--error' }
                    })
                });
            });
        });

        // ── Supprimer ─────────────────────────────────────────
        $('#artisansTable').on('click', '.delete-artisan-btn', function () {
            const id = $(this).data('id'), name = $(this).data('name');

            Swal.fire({
                title: 'Supprimer cet artisan ?',
                html: `<strong>${name}</strong> et tous ses produits seront supprimés définitivement.`,
                icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#e8112d', cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui, supprimer', cancelButtonText: 'Annuler',
                customClass: { popup: 'swal-benin-popup swal-benin-popup--error',
                               title: 'swal-benin-title', confirmButton: 'swal-btn swal-btn--red' }
            }).then(result => {
                if (!result.isConfirmed) return;
                $.ajax({
                    url: '{{ route("admin.artisans.destroy", ":id") }}'.replace(':id', id),
                    type: 'DELETE', data: { _token: '{{ csrf_token() }}' },
                    success: () => Swal.fire({ icon: 'success', title: 'Supprimé !',
                        text: `${name} a été supprimé.`, timer: 1800, showConfirmButton: false,
                        customClass: { popup: 'swal-benin-popup swal-benin-popup--success' }
                    }).then(() => location.reload()),
                    error: xhr => Swal.fire({ icon: 'error', title: 'Erreur',
                        text: xhr.responseJSON?.message ?? 'Impossible de supprimer cet artisan.',
                        customClass: { popup: 'swal-benin-popup swal-benin-popup--error' }
                    })
                });
            });
        });
    });
    </script>
@endpush
