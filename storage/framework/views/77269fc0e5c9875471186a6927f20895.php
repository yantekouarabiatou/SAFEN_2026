

<?php $__env->startSection('title', 'Gestion des messages'); ?>

<?php $__env->startSection('content'); ?>
<div class="section-header">
    <h1>Messages</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></div>
        <div class="breadcrumb-item active">Messages</div>
    </div>
</div>

<div class="section-body">
    
    <div class="row" style="display: flex; flex-wrap: wrap;">
        <div class="col" style="flex: 1 1 180px; min-width: 160px;">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Total</h4>
                    </div>
                    <div class="card-body">
                        <?php echo e($stats['total']); ?>

                    </div>
                </div>
            </div>
        </div>
        <div class="col" style="flex: 1 1 180px; min-width: 160px;">
            <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                    <i class="fas fa-envelope-open"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Non lus</h4>
                    </div>
                    <div class="card-body">
                        <?php echo e($stats['unread']); ?>

                    </div>
                </div>
            </div>
        </div>
        <div class="col" style="flex: 1 1 180px; min-width: 160px;">
            <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Lus</h4>
                    </div>
                    <div class="card-body">
                        <?php echo e($stats['read']); ?>

                    </div>
                </div>
            </div>
        </div>
        <div class="col" style="flex: 1 1 180px; min-width: 160px;">
            <div class="card card-statistic-1">
                <div class="card-icon bg-info">
                    <i class="fas fa-comments"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Conversations</h4>
                    </div>
                    <div class="card-body">
                        <?php echo e($stats['conversations']); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row mt-0">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-filter"></i> Filtres</h4>
                    <div class="card-header-action">
                        <button class="btn btn-danger btn-sm" id="bulkDeleteBtn" style="display: none;">
                            <i class="fas fa-trash"></i> Supprimer la sélection
                        </button>
                        <a href="<?php echo e(route('admin.messages.conversations')); ?>" class="btn btn-info btn-sm ml-2">
                            <i class="fas fa-comments"></i> Voir conversations
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('admin.messages.index')); ?>" method="GET" class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Recherche</label>
                                <input type="text" name="search" class="form-control"
                                    value="<?php echo e(request('search')); ?>" placeholder="Message, utilisateur...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Statut</label>
                                <select name="status" class="form-control">
                                    <option value="">Tous</option>
                                    <option value="unread" <?php echo e(request('status') == 'unread' ? 'selected' : ''); ?>>Non lus</option>
                                    <option value="read" <?php echo e(request('status') == 'read' ? 'selected' : ''); ?>>Lus</option>
                                </select>
                            </div>
                        </div>
                        <!-- <div class="col-md-2">
                            <div class="form-group">
                                <label>Type</label>
                                <select name="type" class="form-control">
                                    <option value="">Tous</option>
                                    <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($type); ?>" <?php echo e(request('type') == $type ? 'selected' : ''); ?>>
                                            <?php echo e(ucfirst($type)); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div> -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Utilisateur</label>
                                <select name="user" class="form-control">
                                    <option value="">Tous</option>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->id); ?>" <?php echo e(request('user') == $user->id ? 'selected' : ''); ?>>
                                        <?php echo e($user->name); ?>

                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Filtrer
                                </button>
                                <a href="<?php echo e(route('admin.messages.index')); ?>" class="btn btn-secondary">
                                    <i class="fas fa-undo"></i> Réinitialiser
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row mt-0">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Liste des messages (<?php echo e($messages->total()); ?>)</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="messages-table">
                            <thead>
                                <tr>
                                    <th width="40">
                                        <div class="custom-checkbox custom-control">
                                            <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input" id="checkbox-all">
                                            <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                                        </div>
                                    </th>
                                    <th>Expéditeur</th>
                                    <th>Destinataire</th>
                                    <th>Message</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr id="message-<?php echo e($message->id); ?>" class="<?php echo e(!$message->read_at ? 'font-weight-bold' : ''); ?>">
                                    <td>
                                        <div class="custom-checkbox custom-control">
                                            <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input message-checkbox" value="<?php echo e($message->id); ?>" id="message-<?php echo e($message->id); ?>-cb">
                                            <label for="message-<?php echo e($message->id); ?>-cb" class="custom-control-label">&nbsp;</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if($message->sender && $message->sender->avatar): ?>
                                            <img src="<?php echo e($message->sender->avatar); ?>"
                                                alt="<?php echo e($message->sender->name); ?>"
                                                class="rounded-circle mr-2" width="35" height="35">
                                            <?php else: ?>
                                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mr-2"
                                                style="width: 35px; height: 35px; font-size: 14px;">
                                                <?php echo e($message->sender ? strtoupper(substr($message->sender->name, 0, 1)) : '?'); ?>

                                            </div>
                                            <?php endif; ?>
                                            <div>
                                                <strong><?php echo e($message->sender->name ?? 'Utilisateur supprimé'); ?></strong>
                                                <br>
                                                <small class="text-muted"><?php echo e($message->sender->email ?? ''); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if($message->receiver && $message->receiver->avatar): ?>
                                            <img src="<?php echo e($message->receiver->avatar); ?>"
                                                alt="<?php echo e($message->receiver->name); ?>"
                                                class="rounded-circle mr-2" width="35" height="35">
                                            <?php else: ?>
                                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mr-2"
                                                style="width: 35px; height: 35px; font-size: 14px;">
                                                <?php echo e($message->receiver ? strtoupper(substr($message->receiver->name, 0, 1)) : '?'); ?>

                                            </div>
                                            <?php endif; ?>
                                            <div>
                                                <strong><?php echo e($message->receiver->name ?? 'Utilisateur supprimé'); ?></strong>
                                                <br>
                                                <small class="text-muted"><?php echo e($message->receiver->email ?? ''); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="message-content" data-full="<?php echo e($message->message); ?>">
                                            <?php echo e(Str::limit($message->message, 50)); ?>

                                        </span>
                                        <?php if(strlen($message->message) > 50): ?>
                                        <br>
                                        <a href="#" class="text-primary read-more" data-content="<?php echo e($message->message); ?>">Lire plus</a>
                                        <?php endif; ?>
                                        <?php if($message->reference_id): ?>
                                        <br>
                                        <small class="text-info">
                                            <i class="fas fa-reply"></i> Réponse
                                        </small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?php echo e($message->type == 'reply' ? 'info' : 'secondary'); ?>">
                                            <?php echo e($message->type ?? 'standard'); ?>

                                        </span>
                                    </td>
                                    <td data-order="<?php echo e($message->created_at->timestamp); ?>">
                                        <?php echo e($message->created_at->format('d/m/Y')); ?><br>
                                        <small class="text-muted"><?php echo e($message->created_at->format('H:i')); ?></small>
                                    </td>
                                    <td>
                                        <?php if($message->read_at): ?>
                                        <span class="badge badge-success">Lu</span>
                                        <br>
                                        <small class="text-muted"><?php echo e($message->read_at->format('d/m H:i')); ?></small>
                                        <?php else: ?>
                                        <span class="badge badge-warning">Non lu</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo e(route('admin.messages.show', $message)); ?>"
                                                class="btn btn-sm btn-info mx-1 rounded"
                                                title="Voir détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
<?php if(!$message->read_at): ?>
    
    <button type="button"
        class="btn btn-sm btn-success mx-1 rounded btn-mark-read"
        data-id="<?php echo e($message->id); ?>"
        title="Marquer comme lu">
        <i class="fas fa-check"></i>
    </button>
<?php else: ?>
    
    <button type="button"
        class="btn btn-sm btn-secondary mx-1 rounded"
        disabled
        title="Déjà lu">
        <i class="fas fa-check"></i>
    </button>
<?php endif; ?>

                                            <button type="button"
                                                class="btn btn-sm btn-primary mx-1 rounded btn-reply"
                                                data-id="<?php echo e($message->id); ?>"
                                                data-sender="<?php echo e($message->sender->name ?? 'Utilisateur'); ?>"
                                                title="Répondre">
                                                <i class="fas fa-reply"></i>
                                            </button>

                                            <button type="button"
                                                class="btn btn-sm btn-secondary mx-1 rounded btn-delete"
                                                data-id="<?php echo e($message->id); ?>"
                                                title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="empty-state">
                                            <div class="empty-state-icon bg-light">
                                                <i class="fas fa-envelope fa-3x text-muted"></i>
                                            </div>
                                            <h4 class="mt-3">Aucun message trouvé</h4>
                                            <p class="text-muted">
                                                <?php if(request()->anyFilled(['search', 'status', 'type', 'user'])): ?>
                                                Aucun message ne correspond à vos critères.
                                                <?php else: ?>
                                                Il n'y a pas encore de messages.
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<style>
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

    /* Style pour les messages non lus */
    .font-weight-bold {
        font-weight: 600 !important;
        background-color: #f8f9ff;
    }

    /* Badges */
    .badge {
        padding: 5px 10px;
        font-size: 12px;
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
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialisation de DataTables
        var table = $('#messages-table').DataTable({
            "columnDefs": [{
                    "sortable": false,
                    "targets": [0, 7]
                }, // Checkbox et actions non triables
                {
                    "type": "date",
                    "targets": [5]
                } // Date
            ],
            "order": [
                [5, "desc"]
            ], // Tri par date décroissante
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json"
            },
            "pageLength": 10,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Tous"]
            ],
            "autoWidth": false,
            "drawCallback": function() {
                attachEvents();
            }
        });

        function attachEvents() {
            // Checkboxes
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
                var content = $(this).data('content');

                Swal.fire({
                    title: 'Message complet',
                    html: `<div style="max-height: 300px; overflow-y: auto; text-align: left; padding: 10px;">${content}</div>`,
                    confirmButtonText: 'Fermer',
                    confirmButtonColor: '#6777ef',
                    width: '500px'
                });
            });

            // Marquer comme lu
            $('.btn-mark-read').off('click').on('click', function() {
                var messageId = $(this).data('id');

                Swal.fire({
                    title: 'Marquer comme lu ?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Oui',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/admin/messages/${messageId}/mark-read`,
                            type: 'POST',
                            data: {
                                _token: '<?php echo e(csrf_token()); ?>'
                            },
                            success: function(response) {
                                Swal.fire('Succès!', response.message, 'success')
                                    .then(() => location.reload());
                            },
                            error: function(xhr) {
                                Swal.fire('Erreur', xhr.responseJSON?.message || 'Une erreur est survenue', 'error');
                            }
                        });
                    }
                });
            });

            // Marquer comme non lu
            $('.btn-mark-unread').off('click').on('click', function() {
                var messageId = $(this).data('id');

                Swal.fire({
                    title: 'Marquer comme non lu ?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#ffc107',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Oui',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/admin/messages/${messageId}/mark-unread`,
                            type: 'POST',
                            data: {
                                _token: '<?php echo e(csrf_token()); ?>'
                            },
                            success: function(response) {
                                Swal.fire('Succès!', response.message, 'success')
                                    .then(() => location.reload());
                            },
                            error: function(xhr) {
                                Swal.fire('Erreur', xhr.responseJSON?.message || 'Une erreur est survenue', 'error');
                            }
                        });
                    }
                });
            });

            // Répondre
            $('.btn-reply').off('click').on('click', function() {
                var messageId = $(this).data('id');
                var senderName = $(this).data('sender');

                Swal.fire({
                    title: `Répondre à ${senderName}`,
                    input: 'textarea',
                    inputLabel: 'Votre message',
                    inputPlaceholder: 'Écrivez votre réponse...',
                    showCancelButton: true,
                    confirmButtonColor: '#6777ef',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Envoyer',
                    cancelButtonText: 'Annuler',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Le message ne peut pas être vide'
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/admin/messages/${messageId}/reply`,
                            type: 'POST',
                            data: {
                                _token: '<?php echo e(csrf_token()); ?>',
                                content: result.value
                            },
                            success: function(response) {
                                Swal.fire('Envoyé!', response.message, 'success')
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
                var messageId = $(this).data('id');

                Swal.fire({
                    title: 'Supprimer ce message ?',
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
                            url: `/admin/messages/${messageId}`,
                            type: 'DELETE',
                            data: {
                                _token: '<?php echo e(csrf_token()); ?>'
                            },
                            success: function(response) {
                                table.row($('#message-' + messageId)).remove().draw();
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
            $('#bulkDeleteBtn').toggle($('.message-checkbox:checked').length > 0);
        }

        // Actions groupées
        $('#bulkDeleteBtn').click(function() {
            var ids = $('.message-checkbox:checked').map(function() {
                return $(this).val();
            }).get();

            if (ids.length === 0) return;

            Swal.fire({
                title: 'Actions groupées',
                html: `
                <p>Que voulez-vous faire avec les ${ids.length} messages sélectionnés ?</p>
                <div class="d-flex justify-content-center mt-3">
                    <button class="btn btn-success mx-1" id="bulk-mark-read">
                        <i class="fas fa-check"></i> Marquer lus
                    </button>
                    <button class="btn btn-warning mx-1" id="bulk-mark-unread">
                        <i class="fas fa-envelope"></i> Marquer non lus
                    </button>
                    <button class="btn btn-danger mx-1" id="bulk-delete-confirm">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
                </div>
            `,
                showConfirmButton: false,
                showCancelButton: true,
                cancelButtonText: 'Annuler',
                didOpen: () => {
                    $('#bulk-mark-read').click(function() {
                        performBulkAction('mark-read', ids);
                    });
                    $('#bulk-mark-unread').click(function() {
                        performBulkAction('mark-unread', ids);
                    });
                    $('#bulk-delete-confirm').click(function() {
                        performBulkAction('delete', ids);
                    });
                }
            });
        });

        function performBulkAction(action, ids) {
            Swal.close();

            $.ajax({
                url: '/admin/messages/bulk-action',
                type: 'POST',
                data: {
                    _token: '<?php echo e(csrf_token()); ?>',
                    action: action,
                    ids: ids
                },
                success: function(response) {
                    Swal.fire('Succès!', response.message, 'success')
                        .then(() => location.reload());
                },
                error: function(xhr) {
                    Swal.fire('Erreur', xhr.responseJSON?.message || 'Une erreur est survenue', 'error');
                }
            });
        }

        attachEvents();
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SAFEN_2026\resources\views/admin/messages/index.blade.php ENDPATH**/ ?>