<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="section-header">
    <h1>Tableau de bord</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></div>
    </div>
</div>

<div class="row">
    
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-primary">
                <i class="fas fa-users"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Utilisateurs</h4>
                </div>
                <div class="card-body">
                    <?php echo e(number_format($stats['total_users'])); ?>

                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-success">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Produits</h4>
                </div>
                <div class="card-body">
                    <?php echo e(number_format($stats['total_products'])); ?>

                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-warning">
                <i class="fas fa-utensils"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Plats</h4>
                </div>
                <div class="card-body">
                    <?php echo e(number_format($stats['total_dishes'])); ?>

                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-danger">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Commandes</h4>
                </div>
                <div class="card-body">
                    <?php echo e(number_format($stats['total_orders'])); ?>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon" style="background-color: var(--benin-green);">
                <i class="fas fa-paint-brush"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Artisans</h4>
                </div>
                <div class="card-body">
                    <?php echo e(number_format($stats['total_artisans'])); ?>

                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-info">
                <i class="fas fa-store"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Vendeurs</h4>
                </div>
                <div class="card-body">
                    <?php echo e(number_format($stats['total_vendors'])); ?>

                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-secondary">
                <i class="fas fa-clock"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>En attente</h4>
                </div>
                <div class="card-body">
                    <?php echo e(number_format($stats['pending_orders'])); ?>

                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon" style="background-color: #FCD116;">
                <i class="fas fa-coins" style="color: #333;"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Revenus</h4>
                </div>
                <div class="card-body">
                    <?php echo e(number_format($stats['total_revenue'], 0, ',', ' ')); ?> F
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4>Ventes des 7 derniers jours</h4>
            </div>
            <div class="card-body">
                <div id="salesChart"></div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-4">
        <div class="card gradient-bottom">
            <div class="card-header">
                <h4>Nouveaux utilisateurs</h4>
                <div class="card-header-action dropdown">
                    <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-primary">Voir tous</a>
                </div>
            </div>
            <div class="card-body" id="top-5-scroll">
                <ul class="list-unstyled list-unstyled-border">
                    <?php $__currentLoopData = $newUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="media">
                        <img class="mr-3 rounded-circle" width="50" src="<?php echo e($user->profile_photo_url ?? asset('admin-assets/img/users/user-1.png')); ?>" alt="<?php echo e($user->name); ?>">
                        <div class="media-body">
                            <div class="float-right text-primary"><?php echo e($user->created_at->diffForHumans()); ?></div>
                            <div class="media-title"><?php echo e($user->name); ?></div>
                            <span class="text-small text-muted"><?php echo e($user->email); ?></span>
                        </div>
                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4>Commandes récentes</h4>
                <div class="card-header-action">
                    <a href="<?php echo e(route('admin.orders.index')); ?>" class="btn btn-primary">Voir toutes</a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>N° Commande</th>
                                <th>Client</th>
                                <th>Total</th>
                                <th>Statut</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <a href="<?php echo e(route('admin.orders.show', $order)); ?>">
                                        #<?php echo e($order->order_number ?? $order->id); ?>

                                    </a>
                                </td>
                                <td><?php echo e($order->user->name ?? 'N/A'); ?></td>
                                <td><?php echo e(number_format($order->total_amount, 0, ',', ' ')); ?> FCFA</td>
                                <td>
                                    <?php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'processing' => 'info',
                                            'shipped' => 'primary',
                                            'delivered' => 'success',
                                            'completed' => 'success',
                                            'cancelled' => 'danger',
                                        ];
                                        $statusLabels = [
                                            'pending' => 'En attente',
                                            'processing' => 'En cours',
                                            'shipped' => 'Expédié',
                                            'delivered' => 'Livré',
                                            'completed' => 'Complété',
                                            'cancelled' => 'Annulé',
                                        ];
                                    ?>
                                    <span class="badge badge-<?php echo e($statusColors[$order->status] ?? 'secondary'); ?>">
                                        <?php echo e($statusLabels[$order->status] ?? $order->status); ?>

                                    </span>
                                </td>
                                <td><?php echo e($order->created_at->format('d/m/Y H:i')); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center">Aucune commande</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h4>Produits populaires</h4>
            </div>
            <div class="card-body">
                <?php $__currentLoopData = $popularProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="mb-4">
                    <div class="text-small float-right font-weight-bold text-muted">
                        <?php echo e($product->order_items_count); ?> ventes
                    </div>
                    <div class="font-weight-bold mb-1"><?php echo e(Str::limit($product->name, 25)); ?></div>
                    <div class="text-small text-muted"><?php echo e(number_format($product->price, 0, ',', ' ')); ?> FCFA</div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</div>


<?php if($stats['new_contacts'] > 0): ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-envelope text-danger"></i> <?php echo e($stats['new_contacts']); ?> nouveaux messages</h4>
                <div class="card-header-action">
                    <a href="<?php echo e(route('admin.contacts.index')); ?>" class="btn btn-primary">Voir tous</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique des ventes
    var salesData = <?php echo json_encode($salesChart, 15, 512) ?>;

    var options = {
        chart: {
            type: 'area',
            height: 300,
            toolbar: {
                show: false
            }
        },
        series: [{
            name: 'Ventes (FCFA)',
            data: salesData.map(item => item.total || 0)
        }, {
            name: 'Commandes',
            data: salesData.map(item => item.count || 0)
        }],
        xaxis: {
            categories: salesData.map(item => item.date),
            labels: {
                formatter: function(value) {
                    if (!value) return '';
                    const date = new Date(value);
                    return date.toLocaleDateString('fr-FR', { day: '2-digit', month: '2-digit' });
                }
            }
        },
        yaxis: [{
            title: {
                text: 'Ventes (FCFA)'
            }
        }, {
            opposite: true,
            title: {
                text: 'Commandes'
            }
        }],
        colors: ['#008751', '#FCD116'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.2,
            }
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        tooltip: {
            y: {
                formatter: function(value, { seriesIndex }) {
                    if (seriesIndex === 0) {
                        return new Intl.NumberFormat('fr-FR').format(value) + ' FCFA';
                    }
                    return value + ' commandes';
                }
            }
        }
    };

    if (document.getElementById('salesChart')) {
        var chart = new ApexCharts(document.getElementById('salesChart'), options);
        chart.render();
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SAFEN_2026\resources\views/admin/dashboard/index.blade.php ENDPATH**/ ?>