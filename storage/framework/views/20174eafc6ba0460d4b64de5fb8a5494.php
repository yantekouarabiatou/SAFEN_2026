<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title><?php echo $__env->yieldContent('title', 'Administration'); ?> - TOTCHEMEGNON Admin</title>

    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('admin-assets/img/favicon.ico')); ?>">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Open+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Tes assets locaux – TOUJOURS avec asset() -->
    <link rel="stylesheet" href="<?php echo e(asset('admin-assets/css/app.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('admin-assets/css/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('admin-assets/css/components.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('admin-assets/css/custom.css')); ?>">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('admin-assets/bundles/datatables/datatables.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('admin-assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css')); ?>">

    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('admin-assets/bundles/sweetalert/sweetalert.css')); ?>">

    <!-- iziToast CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('admin-assets/bundles/izitoast/css/iziToast.min.css')); ?>">

    <!-- Select2 CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('admin-assets/bundles/select2/dist/css/select2.min.css')); ?>">

    <!-- sweetalert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Styles pushés depuis les vues -->
    <?php echo $__env->yieldPushContent('styles'); ?>

    <!-- Couleurs Bénin & Navbar Professionnelle (gardé intact) -->
    <style>
        :root {
            --benin-green: #008751;
            --benin-yellow: #FCD116;
            --benin-red: #E8112D;
            --benin-dark: #006d40;
            --navbar-height: 70px;
        }

        /* === Couleurs Bénin === */
        .bg-benin-green {
            background-color: var(--benin-green) !important;
        }

        .text-benin-green {
            color: var(--benin-green) !important;
        }

        .btn-benin-green {
            background-color: var(--benin-green);
            border-color: var(--benin-green);
            color: white;
        }

        .btn-benin-green:hover {
            background-color: var(--benin-dark);
            border-color: var(--benin-dark);
        }

        .sidebar-brand a {
            color: var(--benin-green) !important;
        }

        .main-sidebar .sidebar-menu li.active a {
            background-color: var(--benin-green) !important;
            color: #60686f !important;
        }

        .badge-benin-green {
            background-color: var(--benin-green);
            color: white;
        }

        /* === NAVBAR PROFESSIONNELLE === */
        .main-navbar {
            height: var(--navbar-height);
            padding: 0 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .main-navbar .form-inline {
            flex: 1;
            max-width: 600px;
        }

        .main-navbar .navbar-nav {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .main-navbar .navbar-nav.navbar-right {
            margin-left: auto;
            gap: 15px;
        }

        .nav-link-lg {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link-lg:hover {
            background-color: rgba(0, 135, 81, 0.1);
        }

        .nav-link-lg i {
            width: 20px;
            height: 20px;
        }

        /* Barre de recherche améliorée */
        .search-element {
            position: relative;
            width: 100%;
            max-width: 400px;
        }

        .search-element input {
            height: 42px;
            padding: 0 45px 0 18px;
            border-radius: 21px;
            border: 1px solid #e4e6ef;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .search-element input:focus {
            background-color: white;
            border-color: var(--benin-green);
            box-shadow: 0 0 0 3px rgba(0, 135, 81, 0.1);
        }

        .search-element .btn {
            position: absolute;
            right: 3px;
            top: 50%;
            transform: translateY(-50%);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: transparent;
            border: none;
            color: #6c757d;
            transition: all 0.3s ease;
        }

        .search-element .btn:hover {
            background-color: var(--benin-green);
            color: white;
        }

        /* Dropdowns notifications & messages */
        .dropdown-list-toggle {
            position: relative;
        }

        .dropdown-list-toggle .nav-link {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
        }

        .dropdown-list-toggle .nav-link:hover {
            background-color: rgba(0, 135, 81, 0.1);
        }

        /* Badge de compteur */
        .badge-sm {
            min-width: 20px;
            height: 20px;
            padding: 0 6px;
            font-size: 11px;
            font-weight: 600;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
            top: 3px !important;
            right: 3px !important;
        }

        /* Menu utilisateur amélioré */
        .nav-link-user {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 6px 12px 6px 6px !important;
            border-radius: 25px;
            transition: all 0.3s ease;
            min-height: 52px;
        }

        .nav-link-user:hover {
            background-color: rgba(0, 135, 81, 0.05);
        }

        .nav-link-user .rounded-circle,
        .nav-link-user .avatar-initial {
            width: 40px;
            height: 40px;
            border: 2px solid #e4e6ef;
            flex-shrink: 0;
        }

        .nav-link-user .d-lg-inline-block {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            line-height: 1.3;
            max-width: 150px;
        }

        .nav-link-user .d-lg-inline-block small {
            font-size: 11px;
            margin-top: 2px;
            opacity: 0.7;
        }

        /* Avatar avec initiales */
        .avatar-initial {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 15px;
            color: white;
            text-transform: uppercase;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        /* Dropdown menu amélioré */
        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
            border-radius: 12px;
            padding: 8px;
            margin-top: 8px !important;
            min-width: 260px;
        }

        .dropdown-menu.dropdown-menu-right {
            right: 0;
            left: auto;
        }

        .dropdown-title {
            padding: 12px 16px;
            font-weight: 600;
            font-size: 13px;
            color: #495057;
        }

        .dropdown-item {
            padding: 10px 16px;
            border-radius: 8px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
        }

        .dropdown-item:hover {
            background-color: rgba(0, 135, 81, 0.08);
            color: var(--benin-green);
        }

        .dropdown-item.has-icon i {
            width: 18px;
            height: 18px;
        }

        .dropdown-item.text-danger:hover {
            background-color: rgba(232, 17, 45, 0.08);
            color: #dc3545;
        }

        .dropdown-divider {
            margin: 8px 0;
            border-color: #e4e6ef;
        }

        /* Dropdown liste (messages/notifications) */
        .dropdown-list {
            min-width: 340px;
        }

        .dropdown-header {
            padding: 14px 20px;
            font-weight: 600;
            font-size: 14px;
            border-bottom: 1px solid #e4e6ef;
            background-color: #f8f9fa;
            border-radius: 12px 12px 0 0;
        }

        .dropdown-footer {
            padding: 12px 20px;
            border-top: 1px solid #e4e6ef;
            background-color: #f8f9fa;
            border-radius: 0 0 12px 12px;
        }

        .dropdown-footer a {
            color: var(--benin-green);
            font-weight: 500;
            font-size: 13px;
            text-decoration: none;
        }

        .dropdown-footer a:hover {
            text-decoration: underline;
        }

        .dropdown-list-content {
            max-height: 320px;
            overflow-y: auto;
            padding: 8px 0;
        }

        /* Scroll personnalisé */
        .dropdown-list-content::-webkit-scrollbar {
            width: 6px;
        }

        .dropdown-list-content::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .dropdown-list-content::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 3px;
        }

        .dropdown-list-content::-webkit-scrollbar-thumb:hover {
            background: #a0aec0;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .main-navbar {
                padding: 0 15px;
            }

            .search-element {
                max-width: 250px;
            }

            .nav-link-user .d-lg-inline-block {
                display: none !important;
            }

            .navbar-right {
                gap: 8px !important;
            }
        }

        @media (max-width: 767px) {
            .main-navbar {
                height: 60px;
            }

            .nav-link-lg {
                width: 38px;
                height: 38px;
            }

            .nav-link-user .avatar-initial,
            .nav-link-user .rounded-circle {
                width: 36px;
                height: 36px;
            }

            .dropdown-list {
                min-width: 300px;
            }
        }

        /* Animation des icônes */
        @keyframes bellRing {

            0%,
            100% {
                transform: rotate(0deg);
            }

            10%,
            30% {
                transform: rotate(-10deg);
            }

            20%,
            40% {
                transform: rotate(10deg);
            }
        }

        .notification-toggle:hover i {
            animation: bellRing 0.5s ease-in-out;
        }

        /* État actif */
        .navbar-nav li.active .nav-link {
            background-color: rgba(0, 135, 81, 0.1);
            color: var(--benin-green);
        }
    </style>
</head>

<body class="layout-1">
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>

            <!-- Navbar Professionnelle Optimisée -->
            <nav class="navbar navbar-expand-lg main-navbar sticky">
                <!-- Section Gauche: Actions + Recherche -->
                <div class="form-inline mr-auto">
                    <ul class="navbar-nav mr-3">
                        <!-- Bouton Sidebar -->
                        <li>
                            <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg collapse-btn" title="Menu">
                                <i data-feather="align-justify"></i>
                            </a>
                        </li>

                        <!-- Bouton Plein écran -->
                        <li>
                            <a href="#" class="nav-link nav-link-lg fullscreen-btn" title="Plein écran">
                                <i data-feather="maximize"></i>
                            </a>
                        </li>
                    </ul>

                    <!-- Barre de recherche -->
                    <div class="d-none d-md-block">
                        <form class="form-inline">
                            <div class="search-element">
                                <input class="form-control" type="search" placeholder="Rechercher..." aria-label="Search">
                                <button class="btn" type="submit">
                                    <i data-feather="search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Section Droite: Notifications + Profil -->
                <ul class="navbar-nav navbar-right">
                    <!-- Messages -->
                    <li class="dropdown dropdown-list-toggle">
                        <a href="#" data-toggle="dropdown" class="nav-link message-toggle" title="Messages">
                            <i data-feather="mail"></i>
                            <span class="badge badge-pill badge-danger badge-sm position-absolute" style="display:none;" id="msg-count">0</span>
                        </a>
                        <div class="dropdown-menu dropdown-list dropdown-menu-right">
                            <div class="dropdown-header">
                                <i data-feather="mail" style="width: 16px; height: 16px; margin-right: 8px;"></i>
                                Messages
                            </div>
                            <div class="dropdown-list-content dropdown-list-message" id="messages-list">
                                <div class="text-center py-4">
                                    <p class="text-muted mb-0">Chargement...</p>
                                </div>
                            </div>
                            <div class="dropdown-footer text-center">
                                <a href="<?php echo e(route('admin.messages.index')); ?>">Voir tous les messages</a>
                            </div>
                        </div>
                    </li>

                    <!-- Notifications -->
                    <li class="dropdown dropdown-list-toggle">
                        <a href="#" data-toggle="dropdown" class="nav-link notification-toggle" title="Notifications">
                            <i data-feather="bell"></i>
                            <span class="badge badge-pill badge-danger badge-sm position-absolute" style="display:none;" id="notif-count">0</span>
                        </a>
                        <div class="dropdown-menu dropdown-list dropdown-menu-right">
                            <div class="dropdown-header">
                                <i data-feather="bell" style="width: 16px; height: 16px; margin-right: 8px;"></i>
                                Notifications
                            </div>
                            <div class="dropdown-list-content dropdown-list-icons" id="notifications-list">
                                <div class="text-center py-4">
                                    <p class="text-muted mb-0">Chargement...</p>
                                </div>
                            </div>
                            <a href="#" onclick="return false;">Voir toutes les notifications</a>
                        </div>
                    </li>

                    <!-- Menu utilisateur -->
                    <li class="dropdown">
                        <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-user">
                            <?php
                            $user = auth()->user();
                            $initials = strtoupper(substr($user->prenom ?? 'U', 0, 1) . substr($user->nom ?? '', 0, 1));
                            $bgColor = substr(md5($user->email), 0, 6);
                            ?>

                            <?php if($user->profile_photo_url ?? false): ?>
                            <img alt="Profil" src="<?php echo e($user->profile_photo_url); ?>" class="rounded-circle">
                            <?php else: ?>
                            <div class="avatar-initial" style="background: linear-gradient(135deg, #<?php echo e(substr($bgColor,0,6)); ?>, #<?php echo e(substr($bgColor,2,6)); ?>);">
                                <?php echo e($initials); ?>

                            </div>
                            <?php endif; ?>

                            <div class="d-none d-lg-inline-block">
                                <strong><?php echo e($user->prenom ?? ''); ?> <?php echo e($user->nom ?? 'Utilisateur'); ?></strong>
                                <small class="d-block text-muted">
                                    <?php echo e(ucfirst($user->roles->first()->name ?? 'Membre')); ?>

                                </small>
                            </div>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right">
                            <div class="dropdown-title border-bottom pb-3 mb-2">
                                <strong><?php echo e($user->prenom ?? ''); ?> <?php echo e($user->nom ?? 'Utilisateur'); ?></strong>
                                <small class="d-block text-muted mt-1" style="font-size: 12px;"><?php echo e($user->email); ?></small>
                            </div>

                            <a href="#" class="dropdown-item has-icon">
                                <i data-feather="user"></i> Mon profil
                            </a>
                            <a href="#" class="dropdown-item has-icon">
                                <i data-feather="settings"></i> Paramètres
                            </a>

                            <div class="dropdown-divider"></div>

                            <a href="<?php echo e(url('/')); ?>" class="dropdown-item has-icon" target="_blank">
                                <i data-feather="external-link"></i> Voir le site public
                            </a>

                            <div class="dropdown-divider"></div>

                            <form method="POST" action="<?php echo e(route('logout')); ?>" style="margin: 0;">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="dropdown-item has-icon text-danger" style="border: none; background: none; width: 100%; text-align: left;">
                                    <i data-feather="log-out"></i> Déconnexion
                                </button>
                            </form>
                        </div>
                    </li>
                </ul>
            </nav>

            <!-- Sidebar conditionnelle -->
            <?php if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('super-admin')): ?>
            <?php echo $__env->make('admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php elseif(auth()->user()->hasRole('artisan')): ?>
            <?php echo $__env->make('admin.partials.sidebar-artisan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php elseif(auth()->user()->hasRole('vendor')): ?>
            <?php echo $__env->make('admin.partials.sidebar-vendor', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php else: ?>
            <?php echo $__env->make('admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>

            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-body">
                        <?php echo $__env->yieldContent('content'); ?>
                    </div>
                </section>
            </div>

            <!-- Footer -->
            <footer class="main-footer">
                <div class="footer-left">
                    © <?php echo e(date('Y')); ?> <a href="<?php echo e(url('/')); ?>">TOTCHEMEGNON</a> - Saveurs, Artisanat et Ethnies du Bénin
                </div>
                <div class="footer-right">
                    Version 1.0
                </div>
            </footer>
        </div>
    </div>

    <!-- General JS Scripts -->
    <script src="<?php echo e(asset('admin-assets/js/app.min.js')); ?>"></script>

    <!-- JS Libraries -->
    <script src="<?php echo e(asset('admin-assets/bundles/datatables/datatables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/bundles/sweetalert/sweetalert.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/bundles/izitoast/js/iziToast.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/bundles/select2/dist/js/select2.full.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/bundles/apexcharts/apexcharts.min.js')); ?>"></script>

    <!-- Feather Icons (HTTPS externe) -->
    <script src="https://unpkg.com/feather-icons"></script>

    <!-- Template JS -->
    <script src="<?php echo e(asset('admin-assets/js/scripts.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/js/custom.js')); ?>"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Initialisation Feather Icons -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
        });
    </script>

    <!-- CSRF global pour AJAX -->
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            }
        });
    </script>

    <!-- Scripts pushés par les vues -->
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html><?php /**PATH C:\xampp\htdocs\SAFEN_2026\resources\views/layouts/admin.blade.php ENDPATH**/ ?>