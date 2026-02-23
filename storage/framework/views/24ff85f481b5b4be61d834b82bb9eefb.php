

<?php $__env->startSection('title', 'Détail du message'); ?>

<?php $__env->startSection('content'); ?>
<div class="section-header">
    <h1>Détail du message</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="<?php echo e(route('admin.messages.index')); ?>">Messages</a></div>
        <div class="breadcrumb-item active">Détail</div>
    </div>
</div>

<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Informations du message</h4>
                    <div class="card-header-action">
                        <a href="<?php echo e(route('admin.messages.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <h6 class="card-title">Expéditeur</h6>
                                    <div class="d-flex align-items-center">
                                        <?php if($message->sender && $message->sender->avatar): ?>
                                            <img src="<?php echo e($message->sender->avatar); ?>" 
                                                 alt="<?php echo e($message->sender->name); ?>" 
                                                 class="rounded-circle mr-3" width="50" height="50">
                                        <?php else: ?>
                                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mr-3" 
                                                 style="width: 50px; height: 50px; font-size: 18px;">
                                                <?php echo e($message->sender ? strtoupper(substr($message->sender->name, 0, 1)) : '?'); ?>

                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <h5><?php echo e($message->sender->name ?? 'Utilisateur supprimé'); ?></h5>
                                            <p class="mb-0"><?php echo e($message->sender->email ?? ''); ?></p>
                                            <small class="text-muted">Rôle: <?php echo e($message->sender->role ?? 'Utilisateur'); ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <h6 class="card-title">Destinataire</h6>
                                    <div class="d-flex align-items-center">
                                        <?php if($message->receiver && $message->receiver->avatar): ?>
                                            <img src="<?php echo e($message->receiver->avatar); ?>" 
                                                 alt="<?php echo e($message->receiver->name); ?>" 
                                                 class="rounded-circle mr-3" width="50" height="50">
                                        <?php else: ?>
                                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mr-3" 
                                                 style="width: 50px; height: 50px; font-size: 18px;">
                                                <?php echo e($message->receiver ? strtoupper(substr($message->receiver->name, 0, 1)) : '?'); ?>

                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <h5><?php echo e($message->receiver->name ?? 'Utilisateur supprimé'); ?></h5>
                                            <p class="mb-0"><?php echo e($message->receiver->email ?? ''); ?></p>
                                            <small class="text-muted">Rôle: <?php echo e($message->receiver->role ?? 'Utilisateur'); ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Message</h5>
                                    <div class="card-header-action">
                                        <?php if(!$message->read_at): ?>
                                            <button class="btn btn-success btn-sm" id="markAsRead">
                                                <i class="fas fa-check"></i> Marquer comme lu
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-warning btn-sm" id="markAsUnread">
                                                <i class="fas fa-envelope"></i> Marquer comme non lu
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <small class="text-muted">Envoyé le <?php echo e($message->created_at->format('d/m/Y à H:i')); ?></small>
                                        <?php if($message->read_at): ?>
                                            <br>
                                            <small class="text-muted">Lu le <?php echo e($message->read_at->format('d/m/Y à H:i')); ?></small>
                                        <?php endif; ?>
                                        <?php if($message->type): ?>
                                            <br>
                                            <span class="badge badge-info">Type: <?php echo e($message->type); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="p-4 bg-light rounded">
                                        <?php echo e($message->message); ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if($message->reference_id): ?>
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="card bg-info-light">
                                <div class="card-header">
                                    <h6><i class="fas fa-reply"></i> Ce message est une réponse</h6>
                                </div>
                                <div class="card-body">
                                    <a href="<?php echo e(route('admin.messages.show', $message->reference_id)); ?>" class="btn btn-info">
                                        <i class="fas fa-eye"></i> Voir le message original
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($message->replies && $message->replies->count() > 0): ?>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Réponses (<?php echo e($message->replies->count()); ?>)</h5>
                                </div>
                                <div class="card-body">
                                    <?php $__currentLoopData = $message->replies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reply): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="mb-3 p-3 <?php echo e($reply->sender_id == auth()->id() ? 'bg-light text-right' : 'bg-info-light'); ?> rounded">
                                        <strong><?php echo e($reply->sender->name ?? 'Inconnu'); ?></strong>
                                        <small class="text-muted ml-2"><?php echo e($reply->created_at->format('d/m H:i')); ?></small>
                                        <p class="mb-0 mt-2"><?php echo e($reply->message); ?></p>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Répondre</h5>
                                </div>
                                <div class="card-body">
                                    <form id="replyForm">
                                        <?php echo csrf_field(); ?>
                                        <div class="form-group">
                                            <label>Votre réponse</label>
                                            <textarea name="content" class="form-control" rows="5" placeholder="Écrivez votre réponse..."></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-paper-plane"></i> Envoyer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Marquer comme lu
    $('#markAsRead').click(function() {
        $.ajax({
            url: '<?php echo e(route("admin.messages.mark-read", $message)); ?>',
            type: 'POST',
            data: { _token: '<?php echo e(csrf_token()); ?>' },
            success: function(response) {
                Swal.fire('Succès!', response.message, 'success')
                    .then(() => location.reload());
            },
            error: function(xhr) {
                Swal.fire('Erreur', xhr.responseJSON?.message || 'Une erreur est survenue', 'error');
            }
        });
    });

    // Marquer comme non lu
    $('#markAsUnread').click(function() {
        $.ajax({
            url: '<?php echo e(route("admin.messages.mark-unread", $message)); ?>',
            type: 'POST',
            data: { _token: '<?php echo e(csrf_token()); ?>' },
            success: function(response) {
                Swal.fire('Succès!', response.message, 'success')
                    .then(() => location.reload());
            },
            error: function(xhr) {
                Swal.fire('Erreur', xhr.responseJSON?.message || 'Une erreur est survenue', 'error');
            }
        });
    });

    // Répondre
    $('#replyForm').submit(function(e) {
        e.preventDefault();
        
        var content = $('textarea[name="content"]').val();
        
        if (!content) {
            Swal.fire('Erreur', 'Le message ne peut pas être vide', 'error');
            return;
        }
        
        $.ajax({
            url: '<?php echo e(route("admin.messages.reply", $message)); ?>',
            type: 'POST',
            data: {
                _token: '<?php echo e(csrf_token()); ?>',
                content: content
            },
            success: function(response) {
                Swal.fire('Envoyé!', response.message, 'success')
                    .then(() => {
                        $('textarea[name="content"]').val('');
                        location.reload();
                    });
            },
            error: function(xhr) {
                Swal.fire('Erreur', xhr.responseJSON?.message || 'Une erreur est survenue', 'error');
            }
        });
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SAFEN_2026\resources\views/admin/messages/show.blade.php ENDPATH**/ ?>