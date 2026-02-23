

<?php $__env->startSection('title', 'Conversations'); ?>

<?php $__env->startSection('content'); ?>
<div class="section-header">
    <h1>Conversations</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="<?php echo e(route('admin.messages.index')); ?>">Messages</a></div>
        <div class="breadcrumb-item active">Conversations</div>
    </div>
</div>

<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Liste des conversations</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="conversations-table">
                            <thead>
                                <tr>
                                    <th>Participants</th>
                                    <th>Dernier message</th>
                                    <th>Date</th>
                                    <th>Messages</th>
                                    <th>Non lus</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conversation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php
                                            $participants = [$conversation->user1, $conversation->user2];
                                            ?>
                                            <?php $__currentLoopData = $participants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $participant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($participant): ?>
                                            <div class="d-flex align-items-center mr-3">
                                                <?php if($participant->avatar): ?>
                                                <img src="<?php echo e($participant->avatar); ?>"
                                                    alt="<?php echo e($participant->name); ?>"
                                                    class="rounded-circle mr-1" width="30" height="30">
                                                <?php else: ?>
                                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mr-1"
                                                    style="width: 30px; height: 30px; font-size: 12px;">
                                                    <?php echo e(strtoupper(substr($participant->name, 0, 1))); ?>

                                                </div>
                                                <?php endif; ?>
                                                <small><?php echo e($participant->name); ?></small>
                                            </div>
                                            <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if($conversation->last_message): ?>
                                        <?php echo e(Str::limit($conversation->last_message->message, 50)); ?>

                                        <?php else: ?>
                                        <em>Aucun message</em>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($conversation->last_message_at): ?>
                                        <?php echo e(\Carbon\Carbon::parse($conversation->last_message_at)->format('d/m/Y H:i')); ?>

                                        <?php else: ?>
                                        -
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center"><?php echo e($conversation->messages_count); ?></td>
                                    <td class="text-center">
                                        <?php if($conversation->unread_count > 0): ?>
                                        <span class="badge badge-danger"><?php echo e($conversation->unread_count); ?></span>
                                        <?php else: ?>
                                        <span class="badge badge-success">0</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('admin.messages.index', ['user' => $conversation->user1->id ?? $conversation->user2->id])); ?>"
                                            class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Voir
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="empty-state">
                                            <div class="empty-state-icon bg-light">
                                                <i class="fas fa-comments fa-3x text-muted"></i>
                                            </div>
                                            <h4>Aucune conversation</h4>
                                            <p class="text-muted">Il n'y a pas encore de conversations.</p>
                                        </div>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php if($conversations->hasPages()): ?>
                <div class="card-footer">
                    <?php echo e($conversations->links()); ?>

                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SAFEN_2026\resources\views/admin/messages/conversations.blade.php ENDPATH**/ ?>