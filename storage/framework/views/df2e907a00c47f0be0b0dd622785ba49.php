

<?php $__env->startSection('title', 'Messages de contact'); ?>

<?php $__env->startSection('content'); ?>
<div class="section-header">
    <h1>Messages de contact</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></div>
        <div class="breadcrumb-item active">Contacts</div>
    </div>
</div>

<div class="section-body">
    
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Non lus</h4>
                    </div>
                    <div class="card-body">
                        <?php echo e($stats['unread'] ?? 0); ?>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-info">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>En attente</h4>
                    </div>
                    <div class="card-body">
                        <?php echo e($stats['pending'] ?? 0); ?>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                    <i class="fas fa-check"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Résolus</h4>
                    </div>
                    <div class="card-body">
                        <?php echo e($stats['resolved'] ?? 0); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-filter"></i> Filtres</h4>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('admin.contacts.index')); ?>" method="GET" class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Recherche</label>
                                <input type="text" name="search" class="form-control" 
                                       value="<?php echo e(request('search')); ?>" placeholder="Nom, email, sujet...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Statut</label>
                                <select name="status" class="form-control">
                                    <option value="">Tous</option>
                                    <option value="unread" <?php echo e(request('status') == 'unread' ? 'selected' : ''); ?>>Non lu</option>
                                    <option value="read" <?php echo e(request('status') == 'read' ? 'selected' : ''); ?>>Lu</option>
                                    <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>En attente</option>
                                    <option value="resolved" <?php echo e(request('status') == 'resolved' ? 'selected' : ''); ?>>Résolu</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Date début</label>
                                <input type="date" name="date_from" class="form-control" value="<?php echo e(request('date_from')); ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Date fin</label>
                                <input type="date" name="date_to" class="form-control" value="<?php echo e(request('date_to')); ?>">
                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="form-group">
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-search"></i> Filtrer
                                </button>
                                <a href="<?php echo e(route('admin.contacts.index')); ?>" class="btn btn-secondary">
                                    <i class="fas fa-undo"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Messages (<?php echo e($contacts->total()); ?>)</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Expéditeur</th>
                                    <th>Sujet</th>
                                    <th>Extrait</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $contacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="<?php echo e(!$contact->is_read ? 'table-warning' : ''); ?>">
                                    <td>
                                        <strong><?php echo e($contact->name); ?></strong>
                                        <br><small class="text-muted"><?php echo e($contact->email); ?></small>
                                        <?php if($contact->phone): ?>
                                        <br><small class="text-muted"><i class="fas fa-phone"></i> <?php echo e($contact->phone); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if(!$contact->is_read): ?>
                                            <span class="badge badge-warning mr-1">Nouveau</span>
                                        <?php endif; ?>
                                        <?php echo e($contact->subject ?? 'Sans sujet'); ?>

                                    </td>
                                    <td><?php echo e(Str::limit($contact->message, 60)); ?></td>
                                    <td><?php echo e($contact->created_at->format('d/m/Y H:i')); ?></td>
                                    <td>
                                        <?php switch($contact->status ?? 'pending'):
                                            case ('resolved'): ?>
                                                <span class="badge badge-success">Résolu</span>
                                                <?php break; ?>
                                            <?php case ('pending'): ?>
                                                <span class="badge badge-warning">En attente</span>
                                                <?php break; ?>
                                            <?php default: ?>
                                                <span class="badge badge-secondary"><?php echo e($contact->status); ?></span>
                                        <?php endswitch; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="<?php echo e(route('admin.contacts.show', $contact)); ?>" 
                                               class="btn btn-sm btn-info" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-success btn-resolve" 
                                                    data-id="<?php echo e($contact->id); ?>" title="Marquer comme résolu">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger btn-delete" 
                                                    data-id="<?php echo e($contact->id); ?>" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="empty-state">
                                            <div class="empty-state-icon bg-light">
                                                <i class="fas fa-envelope-open"></i>
                                            </div>
                                            <h2>Aucun message</h2>
                                            <p class="lead">Aucun message ne correspond à vos critères.</p>
                                        </div>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php if($contacts->hasPages()): ?>
                <div class="card-footer">
                    <?php echo e($contacts->withQueryString()->links()); ?>

                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // Marquer comme résolu
    $('.btn-resolve').click(function() {
        var contactId = $(this).data('id');
        
        $.ajax({
            url: '<?php echo e(route("admin.contacts.index")); ?>/' + contactId + '/status',
            type: 'POST',
            data: {
                _token: '<?php echo e(csrf_token()); ?>',
                status: 'resolved'
            },
            success: function(response) {
                iziToast.success({
                    title: 'Succès',
                    message: 'Message marqué comme résolu',
                    position: 'topRight'
                });
                location.reload();
            },
            error: function() {
                iziToast.error({
                    title: 'Erreur',
                    message: 'Une erreur est survenue',
                    position: 'topRight'
                });
            }
        });
    });
    
    // Supprimer
    $('.btn-delete').click(function() {
        var contactId = $(this).data('id');
        
        Swal.fire({
            title: 'Supprimer ce message ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?php echo e(route("admin.contacts.index")); ?>/' + contactId,
                    type: 'DELETE',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>'
                    },
                    success: function(response) {
                        Swal.fire('Supprimé!', 'Le message a été supprimé.', 'success')
                            .then(() => location.reload());
                    },
                    error: function() {
                        Swal.fire('Erreur', 'Une erreur est survenue.', 'error');
                    }
                });
            }
        });
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SAFEN_2026\resources\views/admin/contacts/index.blade.php ENDPATH**/ ?>