@extends('layouts.admin')

@section('title', 'Gestion des avis et évaluations')

@section('content')
<div class="section-header">
    <h1>Avis clients</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item active">Avis</div>
    </div>
</div>

<div class="section-body">
    {{-- Statistiques avec flexbox --}}
    <div class="row" style="display: flex; flex-wrap: wrap;">
        <div class="col" style="flex: 1 1 180px; min-width: 160px;">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                    <i class="fas fa-star"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Total</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['total'] }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col" style="flex: 1 1 180px; min-width: 160px;">
            <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>En attente</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['pending'] }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col" style="flex: 1 1 180px; min-width: 160px;">
            <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                    <i class="fas fa-check"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Approuvés</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['approved'] }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col" style="flex: 1 1 180px; min-width: 160px;">
            <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                    <i class="fas fa-times"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Rejetés</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['rejected'] }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col" style="flex: 1 1 180px; min-width: 160px;">
            <div class="card card-statistic-1">
                <div class="card-icon bg-info">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Note moy.</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($stats['average_rating'], 1) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Filtres --}}
    <div class="row mt-0">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-filter"></i> Filtres</h4>
                    <div class="card-header-action">
                        <button class="btn btn-danger btn-sm" id="bulkDeleteBtn" style="display: none;">
                            <i class="fas fa-trash"></i> Supprimer la sélection
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.reviews.index') }}" method="GET" class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Recherche</label>
                                <input type="text" name="search" class="form-control"
                                    value="{{ request('search') }}" placeholder="Utilisateur, commentaire...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Type</label>
                                <select name="type" class="form-control">
                                    <option value="">Tous</option>
                                    <option value="product" {{ request('type') == 'product' ? 'selected' : '' }}>Produits</option>
                                    <option value="artisan" {{ request('type') == 'artisan' ? 'selected' : '' }}>Artisans</option>
                                    <option value="vendor" {{ request('type') == 'vendor' ? 'selected' : '' }}>Vendeurs</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Statut</label>
                                <select name="status" class="form-control">
                                    <option value="">Tous</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approuvé</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejeté</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Filtrer
                                </button>
                                <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-undo"></i> Réinitialiser
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Liste des avis avec DataTables --}}
    <div class="row mt-0">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Liste des avis ({{ $reviews->total() }})</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="reviews-table">
                            <thead>
                                <tr>
                                    <th width="40">
                                        <div class="custom-checkbox custom-control">
                                            <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input" id="checkbox-all">
                                            <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                                        </div>
                                    </th>
                                    <th>Utilisateur</th>
                                    <th>Concernant</th>
                                    <th>Note</th>
                                    <th>Commentaire</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reviews as $review)
                                @php
                                $type = class_basename($review->reviewable_type);
                                $item = $review->reviewable;
                                $itemName = $item->name ?? $item->business_name ?? $item->user->name ?? 'N/A';
                                $commentLength = strlen($review->comment);
                                @endphp
                                <tr id="review-{{ $review->id }}">
                                    <td>
                                        <div class="custom-checkbox custom-control">
                                            <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input review-checkbox" value="{{ $review->id }}" id="review-{{ $review->id }}-cb">
                                            <label for="review-{{ $review->id }}-cb" class="custom-control-label">&nbsp;</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($review->user->avatar)
                                            <img src="{{ $review->user->avatar }}"
                                                alt="{{ $review->user->name }}"
                                                class="rounded-circle mr-2" width="35" height="35">
                                            @else
                                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mr-2"
                                                style="width: 35px; height: 35px; font-size: 14px;">
                                                {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                            </div>
                                            @endif
                                            <div>
                                                <strong>{{ $review->user->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $review->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $type == 'Product' ? 'info' : ($type == 'Artisan' ? 'warning' : 'success') }} mb-1">
                                            {{ $type }}
                                        </span>
                                        <br>
                                        <span title="{{ $itemName }}">
                                            {{ Str::limit($itemName, 20) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <=$review->rating)
                                                <i class="fas fa-star"></i>
                                                @else
                                                <i class="far fa-star"></i>
                                                @endif
                                                @endfor
                                        </div>
                                        <small class="text-muted">{{ $review->rating }}/5</small>
                                    </td>
                                    <td>
                                        <span class="review-comment" data-full="{{ $review->comment }}">
                                            {{ Str::limit($review->comment, 30) }}
                                        </span>
                                        @if($commentLength > 30)
                                        <br>
                                        <a href="#" class="text-primary read-more" data-comment="{{ $review->comment }}">Lire plus</a>
                                        @endif
                                    </td>
                                    <td data-order="{{ $review->created_at->timestamp }}">
                                        {{ $review->created_at->format('d/m/Y') }}<br>
                                        <small class="text-muted">{{ $review->created_at->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        @switch($review->status)
                                        @case('approved')
                                        <span class="badge badge-success">Approuvé</span>
                                        @break
                                        @case('pending')
                                        <span class="badge badge-warning">En attente</span>
                                        @break
                                        @case('rejected')
                                        <span class="badge badge-danger">Rejeté</span>
                                        @break
                                        @endswitch
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button type="button"
                                                class="btn btn-sm btn-info mx-1 rounded btn-view"
                                                data-view-info='{{ json_encode([
                    'user_name' => $review->user->name,
                    'user_email' => $review->user->email,
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'date' => $review->created_at->format('d/m/Y H:i'),
                    'item_type' => $type,
                    'item_name' => $itemName
                ]) }}'
                                                title="Voir détails">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            {{-- Bouton Approuver : actif uniquement si en attente --}}
                                            <button type="button"
                                                class="btn btn-sm {{ $review->status === 'pending' ? 'btn-success btn-approve' : 'btn-secondary' }} mx-1 rounded"
                                                data-id="{{ $review->id }}"
                                                {{ $review->status !== 'pending' ? 'disabled' : '' }}
                                                title="{{ $review->status === 'pending' ? 'Approuver' : 'Action non disponible' }}">
                                                <i class="fas fa-check"></i>
                                            </button>

                                            {{-- Bouton Rejeter : actif uniquement si en attente --}}
                                            <button type="button"
                                                class="btn btn-sm {{ $review->status === 'pending' ? 'btn-danger btn-reject' : 'btn-secondary' }} mx-1 rounded"
                                                data-id="{{ $review->id }}"
                                                {{ $review->status !== 'pending' ? 'disabled' : '' }}
                                                title="{{ $review->status === 'pending' ? 'Rejeter' : 'Action non disponible' }}">
                                                <i class="fas fa-times"></i>
                                            </button>

                                            <button type="button"
                                                class="btn btn-sm btn-secondary mx-1 rounded btn-delete"
                                                data-id="{{ $review->id }}"
                                                title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="empty-state">
                                            <div class="empty-state-icon bg-light">
                                                <i class="fas fa-star fa-3x text-muted"></i>
                                            </div>
                                            <h4 class="mt-3">Aucun avis trouvé</h4>
                                            <p class="text-muted">
                                                @if(request()->anyFilled(['search', 'type', 'status']))
                                                Aucun avis ne correspond à vos critères.
                                                @else
                                                Il n'y a pas encore d'avis.
                                                @endif
                                            </p>
                                        </div>
                                    </td>
                                </tr>
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

@push('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<style>
    /* Ajustements pour DataTables */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 15px;
    }

    .dataTables_wrapper .dataTables_info {
        padding-top: 15px;
    }

    .dataTables_wrapper .dataTables_paginate {
        padding-top: 15px;
    }

    table.dataTable thead th {
        border-bottom: 2px solid #dee2e6 !important;
    }

    /* Style pour les badges dans DataTables */
    .badge {
        padding: 5px 10px;
        font-size: 12px;
    }

    /* Style pour les boutons d'action */
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }

    /* Style pour la pagination */
    .dataTables_paginate {
        display: flex;
        justify-content: flex-end;
        margin-top: 20px;
    }

    .dataTables_paginate .pagination {
        margin: 0;
        border-radius: 4px;
    }

    .dataTables_paginate .paginate_button {
        margin: 0 2px;
    }

    .dataTables_paginate .paginate_button a {
        border: 1px solid #dee2e6;
        padding: 8px 12px;
        border-radius: 4px;
        color: #6777ef;
        background: #fff;
        transition: all 0.3s;
    }

    .dataTables_paginate .paginate_button a:hover {
        background: #6777ef;
        color: #fff;
        border-color: #6777ef;
    }

    .dataTables_paginate .paginate_button.active a {
        background: #6777ef;
        color: #fff;
        border-color: #6777ef;
    }

    .dataTables_paginate .paginate_button.disabled a {
        color: #6c757d;
        pointer-events: none;
        background: #f8f9fa;
        border-color: #dee2e6;
    }

    /* Style pour les informations de pagination */
    .dataTables_info {
        color: #6c757d;
        font-size: 14px;
        padding-top: 10px !important;
    }

    /* Style pour le sélecteur de longueur */
    .dataTables_length select {
        border: 1px solid #dee2e6;
        border-radius: 4px;
        padding: 5px;
        margin: 0 5px;
    }

    /* Style pour la recherche DataTables */
    .dataTables_filter input {
        border: 1px solid #dee2e6;
        border-radius: 4px;
        padding: 5px 10px;
        margin-left: 5px;
    }

    .dataTables_filter input:focus {
        outline: none;
        border-color: #6777ef;
        box-shadow: 0 0 0 2px rgba(103, 119, 239, 0.2);
    }

    /* Style pour "Lire plus" */
    .read-more {
        font-size: 11px;
        text-decoration: none;
    }

    .read-more:hover {
        text-decoration: underline;
    }
</style>
@endpush

@push('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialisation de DataTables
        var table = $('#reviews-table').DataTable({
            "columnDefs": [{
                    "sortable": false,
                    "targets": [0, 7]
                }, // Les colonnes checkbox et actions non triables
                {
                    "type": "date",
                    "targets": [5]
                } // La colonne date comme type date
            ],
            "order": [
                [5, "desc"]
            ], // Tri par date décroissante par défaut
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json" // Traduction en français
            },
            "pageLength": 10,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Tous"]
            ],
            "autoWidth": false,
            "drawCallback": function() {
                // Réattacher les événements après chaque redessinage
                attachEvents();
            }
        });

        // Fonction pour attacher tous les événements
        function attachEvents() {
            // Gestion des checkboxes
            $("[data-checkboxes]").each(function() {
                var me = $(this),
                    group = me.data('checkboxes'),
                    role = me.data('checkbox-role');

                me.off('change').on('change', function() {
                    var all = $('[data-checkboxes="' + group + '"]:not([data-checkbox-role="dad"])'),
                        checked = $('[data-checkboxes="' + group + '"]:not([data-checkbox-role="dad"]):checked'),
                        dad = $('[data-checkboxes="' + group + '"][data-checkbox-role="dad"]'),
                        total = all.length,
                        checked_length = checked.length;

                    if (role == 'dad') {
                        if (me.is(':checked')) {
                            all.prop('checked', true);
                        } else {
                            all.prop('checked', false);
                        }
                    } else {
                        if (checked_length >= total) {
                            dad.prop('checked', true);
                        } else {
                            dad.prop('checked', false);
                        }
                    }
                    toggleBulkDelete();
                });
            });

            // Lire plus
            $('.read-more').off('click').on('click', function(e) {
                e.preventDefault();
                var comment = $(this).data('comment');

                Swal.fire({
                    title: 'Commentaire',
                    html: `<div style="max-height: 300px; overflow-y: auto; text-align: left; padding: 10px;">${comment}</div>`,
                    confirmButtonText: 'Fermer',
                    confirmButtonColor: '#6777ef',
                    width: '500px'
                });
            });

            // Voir les détails
            $('.btn-view').off('click').on('click', function() {
                var data = $(this).data('view-info');

                if (data) {
                    var stars = '';
                    for (var i = 1; i <= 5; i++) {
                        stars += i <= data.rating ? '★' : '☆';
                    }

                    Swal.fire({
                        title: 'Détail de l\'avis',
                        html: `
                        <div style="text-align: left; font-size: 13px;">
                            <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
                                <tr>
                                    <td style="padding: 4px; font-weight: bold; width: 80px;">De :</td>
                                    <td style="padding: 4px;">${data.user_name}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 4px; font-weight: bold;">Email :</td>
                                    <td style="padding: 4px; font-size: 12px;">${data.user_email}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 4px; font-weight: bold;">Note :</td>
                                    <td style="padding: 4px;">
                                        <span style="color: #ffc107; font-size: 14px;">${stars}</span> 
                                        <span style="font-size: 12px;">(${data.rating}/5)</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 4px; font-weight: bold;">À propos :</td>
                                    <td style="padding: 4px; font-size: 12px;">
                                        [${data.item_type}] ${data.item_name}
                                    </td>
                                </tr>
                            </table>
                            
                            <div style="margin-top: 5px;">
                                <div style="font-weight: bold; margin-bottom: 5px; font-size: 13px;">Commentaire :</div>
                                <div style="background: #f8f9fa; padding: 10px; border-radius: 5px; border-left: 3px solid #6777ef; max-height: 150px; overflow-y: auto; font-size: 12px;">
                                    ${data.comment}
                                </div>
                            </div>
                            
                            <div style="margin-top: 10px; text-align: right; color: #6c757d; font-size: 11px;">
                                <i class="fas fa-calendar-alt"></i> ${data.date}
                            </div>
                        </div>
                    `,
                        confirmButtonText: 'Fermer',
                        confirmButtonColor: '#6777ef',
                        width: '450px',
                        padding: '15px'
                    });
                }
            });

            // Approuver
            $('.btn-approve').off('click').on('click', function() {
                var reviewId = $(this).data('id');

                Swal.fire({
                    title: 'Approuver cet avis ?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Oui, approuver',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/admin/reviews/${reviewId}/approve`,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire('Approuvé!', response.message, 'success')
                                    .then(() => location.reload());
                            },
                            error: function(xhr) {
                                Swal.fire('Erreur', xhr.responseJSON?.message || 'Une erreur est survenue', 'error');
                            }
                        });
                    }
                });
            });

            // Rejeter
            $('.btn-reject').off('click').on('click', function() {
                var reviewId = $(this).data('id');

                Swal.fire({
                    title: 'Rejeter cet avis ?',
                    input: 'textarea',
                    inputLabel: 'Raison du rejet',
                    inputPlaceholder: 'Expliquez pourquoi cet avis est rejeté...',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Rejeter',
                    cancelButtonText: 'Annuler',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'La raison du rejet est requise'
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/admin/reviews/${reviewId}/reject`,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                reason: result.value
                            },
                            success: function(response) {
                                Swal.fire('Rejeté!', response.message, 'success')
                                    .then(() => location.reload());
                            },
                            error: function(xhr) {
                                Swal.fire('Erreur', xhr.responseJSON?.message || 'Une erreur est survenue', 'error');
                            }
                        });
                    }
                });
            });

            // Supprimer
            $('.btn-delete').off('click').on('click', function() {
                var reviewId = $(this).data('id');

                Swal.fire({
                    title: 'Supprimer cet avis ?',
                    text: "Cette action est irréversible !",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Oui, supprimer',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/admin/reviews/${reviewId}`,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                table.row($('#review-' + reviewId)).remove().draw();
                                Swal.fire('Supprimé!', response.message, 'success');
                            },
                            error: function(xhr) {
                                Swal.fire('Erreur', xhr.responseJSON?.message || 'Une erreur est survenue', 'error');
                            }
                        });
                    }
                });
            });
        }

        function toggleBulkDelete() {
            $('#bulkDeleteBtn').toggle($('.review-checkbox:checked').length > 0);
        }

        // Suppression en masse
        $('#bulkDeleteBtn').click(function() {
            var ids = $('.review-checkbox:checked').map(function() {
                return $(this).val();
            }).get();

            if (ids.length === 0) return;

            Swal.fire({
                title: `Supprimer ${ids.length} avis ?`,
                text: "Cette action est irréversible !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/reviews/bulk-action',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            action: 'delete',
                            ids: ids
                        },
                        success: function(response) {
                            Swal.fire('Supprimés!', response.message, 'success')
                                .then(() => location.reload());
                        },
                        error: function(xhr) {
                            Swal.fire('Erreur', xhr.responseJSON?.message || 'Une erreur est survenue', 'error');
                        }
                    });
                }
            });
        });

        // Attacher les événements au chargement initial
        attachEvents();
    });
</script>
@endpush