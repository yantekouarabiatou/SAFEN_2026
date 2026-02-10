<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('title', 'Administration') - SAFEN Admin</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('admin-assets/css/app.min.css') }}">
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('admin-assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/components.css') }}">
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="{{ asset('admin-assets/css/custom.css') }}">
    <link rel='shortcut icon' type='image/x-icon' href='{{ asset('admin-assets/img/favicon.ico') }}' />

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="{{ asset('admin-assets/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('admin-assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">

    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="{{ asset('admin-assets/bundles/sweetalert/sweetalert.css') }}">

    <!-- iziToast CSS -->
    <link rel="stylesheet" href="{{ asset('admin-assets/bundles/izitoast/css/iziToast.min.css') }}">

    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{ asset('admin-assets/bundles/select2/dist/css/select2.min.css') }}">

    @stack('styles')

    <style>
        :root {
            --benin-green: #008751;
            --benin-yellow: #FCD116;
            --benin-red: #E8112D;
        }

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
            background-color: #006741;
            border-color: #006741;
            color: white;
        }

        .sidebar-brand a {
            color: var(--benin-green) !important;
        }

        .main-sidebar .sidebar-menu li.active a {
            background-color: var(--benin-green);
        }

        .badge-benin {
            background-color: var(--benin-green);
            color: white;
        }

        /* Styles pour les profils artisans */
        .artisan-theme {
            --color-success: #28a745;
            --color-warning: #ffc107;
            --color-danger: #dc3545;
            --color-success-light: rgba(40, 167, 69, 0.1);
            --color-warning-light: rgba(255, 193, 7, 0.1);
            --color-danger-light: rgba(220, 53, 69, 0.1);
        }

        /* Cartes avec bordure colorée */
        .card-border-success {
            border-top: 3px solid var(--color-success) !important;
        }

        .card-border-warning {
            border-top: 3px solid var(--color-warning) !important;
        }

        .card-border-danger {
            border-top: 3px solid var(--color-danger) !important;
        }

        /* Boutons avec dégradé */
        .btn-gradient-success {
            background: linear-gradient(135deg, var(--color-success) 0%, #218838 100%) !important;
            border: none;
            color: white;
        }

        .btn-gradient-warning {
            background: linear-gradient(135deg, var(--color-warning) 0%, #e0a800 100%) !important;
            border: none;
            color: #212529;
        }

        .btn-gradient-danger {
            background: linear-gradient(135deg, var(--color-danger) 0%, #c82333 100%) !important;
            border: none;
            color: white;
        }

        /* Badges artisan */
        .badge-artisan-success {
            background-color: var(--color-success) !important;
            color: white;
        }

        .badge-artisan-warning {
            background-color: var(--color-warning) !important;
            color: #212529;
        }

        .badge-artisan-danger {
            background-color: var(--color-danger) !important;
            color: white;
        }

        /* Arrière-plans avec couleur subtile */
        .bg-success-light {
            background-color: var(--color-success-light) !important;
        }

        .bg-warning-light {
            background-color: var(--color-warning-light) !important;
        }

        .bg-danger-light {
            background-color: var(--color-danger-light) !important;
        }

        /* Icônes dans les cercles */
        .icon-circle-sm {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* Animation pour les cartes produit */
        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        .pulse-hover:hover {
            animation: pulse 0.5s ease-in-out;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .artisan-profile-photo {
                width: 100px !important;
                height: 100px !important;
                font-size: 30px !important;
            }

            .cover-image {
                height: 150px !important;
            }
        }

        /* Styles pour la navbar améliorée */
        .glass-navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Dark mode */
        .dark-mode .glass-navbar {
            background: rgba(30, 30, 30, 0.95) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Animations des icônes */
        .animated-icon {
            transition: all 0.3s ease;
        }

        .hover-scale:hover .animated-icon {
            transform: scale(1.2);
        }

        /* Boutons de notification */
        .badge-notification {
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 0.7rem;
            min-width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
            border: 2px solid white;
        }

        .dark-mode .badge-notification {
            border-color: #2a2a2a;
        }

        /* Animation pulse pour les badges */
        @keyframes pulse {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
            }

            70% {
                transform: scale(1.05);
                box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
            }

            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
            }
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        /* Champs de recherche améliorés */
        .search-form {
            position: relative;
        }

        .search-input {
            border-radius: 25px;
            padding-left: 40px;
            padding-right: 45px;
            border: 1px solid #e3e6f0;
            background: rgba(255, 255, 255, 0.9);
            transition: all 0.3s;
            width: 300px !important;
        }

        .search-input:focus {
            width: 400px !important;
            box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1);
            border-color: #4e73df;
        }

        .search-btn {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: #6c757d;
            z-index: 2;
        }

        .search-btn:hover {
            color: #4e73df;
        }

        /* Dropdown améliorés */
        .dropdown-messages,
        .dropdown-notifications {
            min-width: 350px;
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        .dropdown-header {
            padding: 15px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .dropdown-item {
            padding: 12px 20px;
            border-bottom: 1px solid #f8f9fc;
            transition: all 0.2s;
        }

        .dropdown-item:hover {
            background: #f8f9fc;
            transform: translateX(5px);
        }

        .dropdown-item-unread {
            background: rgba(78, 115, 223, 0.05);
        }

        .notification-unread {
            border-left: 3px solid #4e73df;
        }

        /* Avatar utilisateur */
        .user-avatar {
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .user-avatar:hover {
            transform: scale(1.1);
            border-color: #4e73df;
        }

        .online-status {
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            border: 2px solid white;
        }

        .dark-mode .online-status {
            border-color: #2a2a2a;
        }

        .user-name {
            font-weight: 600;
            margin-left: 10px;
        }

        /* User dropdown */
        .user-dropdown {
            min-width: 280px;
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .dropdown-user-avatar {
            width: 60px;
            height: 60px;
        }

        .dropdown-user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .dropdown-item .fas {
            width: 20px;
            text-align: center;
            margin-right: 10px;
        }

        /* Indicateur de présence en ligne */
        .is-online {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            border: 2px solid white;
        }

        /* Animation du dropdown */
        @keyframes pullDown {
            0% {
                transform: translateY(-10px);
                opacity: 0;
            }

            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .pullDown {
            animation: pullDown 0.3s ease-out;
        }

        /* Mode sombre */
        .dark-mode .search-input {
            background: rgba(50, 50, 50, 0.9);
            border-color: #444;
            color: #fff;
        }

        .dark-mode .dropdown-item:hover {
            background: #333;
        }

        .dark-mode .dropdown-messages,
        .dark-mode .dropdown-notifications,
        .dark-mode .user-dropdown {
            background: #2a2a2a;
            color: #fff;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .search-input {
                width: 200px !important;
            }

            .search-input:focus {
                width: 250px !important;
            }

            .dropdown-messages,
            .dropdown-notifications {
                min-width: 280px;
                margin-right: 10px;
            }
        }

        /* Scrollbar personnalisée pour les dropdowns */
        .dropdown-list-content::-webkit-scrollbar {
            width: 5px;
        }

        .dropdown-list-content::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .dropdown-list-content::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        .dropdown-list-content::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Styles pour la navbar */
        .search-results {
            max-height: 400px;
            overflow-y: auto;
        }

        /* Animation pour les badges */
        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
        }

        .animate-pulse {
            animation: pulse 2s infinite;
        }

        /* Transitions pour les dropdowns */
        [x-cloak] {
            display: none !important;
        }

        /* Style pour les résultats de recherche */
        .search-result-item {
            @apply p-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors;
        }

        .search-result-item:not(:last-child) {
            @apply border-b border-gray-100 dark:border-gray-700;
        }
    </style>
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            {{-- Navbar --}}
            <div class="navbar-bg">
                @include('admin.partials.navbar')
            </div>

           {{-- Sidebar - Selon le rôle de l'utilisateur --}}
            @include('admin.partials.sidebar')
            {{-- Main Content --}}
            <div class="main-content">
                <section class="section">
                    <div class="section-body">
                        @yield('content')
                    </div>
                </section>
            </div>

            {{-- Footer --}}
            <footer class="main-footer">
                <div class="footer-left">
                    &copy; {{ date('Y') }} <a href="{{ url('/') }}">SAFEN</a> - Saveurs et Artisanat du Fon et des
                    Ethnies du Nord
                </div>
                <div class="footer-right">
                    Version 1.0
                </div>
            </footer>
        </div>
    </div>

    <!-- General JS Scripts -->
    <!-- Dans layouts/admin.blade.php, dans @push('scripts') ou directement dans <head> -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script> <!-- optionnel mais beau -->
    <script src="{{ asset('admin-assets/js/app.min.js') }}"></script>

    <!-- JS Libraries -->
    <script src="{{ asset('admin-assets/bundles/datatables/datatables.min.js') }}"></script>
    <script
        src="{{ asset('admin-assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin-assets/bundles/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('admin-assets/bundles/izitoast/js/iziToast.min.js') }}"></script>
    <script src="{{ asset('admin-assets/bundles/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('admin-assets/bundles/apexcharts/apexcharts.min.js') }}"></script>

    <!-- Template JS File -->
    <script src="{{ asset('admin-assets/js/scripts.js') }}"></script>
    <!-- Custom JS File -->
    <script src="{{ asset('admin-assets/js/custom.js') }}"></script>

    <script>
        // CSRF Token for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        // Success notification
        @if(session('success'))
            iziToast.success({
                title: 'Succès',
                message: '{{ session('success') }}',
                position: 'topRight'
            });
        @endif

        // Error notification
        @if(session('error'))
            iziToast.error({
                title: 'Erreur',
                message: '{{ session('error') }}',
                position: 'topRight'
            });
        @endif
    </script>

    <script>
        $(document).ready(function () {
            // Mode sombre/clair
            const themeToggle = $('#themeToggle');
            const body = $('body');

            // Vérifier le thème sauvegardé
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark') {
                body.addClass('dark-mode');
                themeToggle.find('.dark-icon').addClass('d-none');
                themeToggle.find('.light-icon').removeClass('d-none');
            }

            // Basculer le thème
            themeToggle.click(function (e) {
                e.preventDefault();
                body.toggleClass('dark-mode');

                if (body.hasClass('dark-mode')) {
                    localStorage.setItem('theme', 'dark');
                    themeToggle.find('.dark-icon').addClass('d-none');
                    themeToggle.find('.light-icon').removeClass('d-none');
                } else {
                    localStorage.setItem('theme', 'light');
                    themeToggle.find('.light-icon').addClass('d-none');
                    themeToggle.find('.dark-icon').removeClass('d-none');
                }
            });

            // Recherche en temps réel
            let searchTimeout;
            $('#globalSearch').on('input', function () {
                clearTimeout(searchTimeout);
                const query = $(this).val();

                if (query.length < 2) {
                    $('#searchResults').empty();
                    return;
                }

                searchTimeout = setTimeout(() => {
                    performSearch(query);
                }, 300);
            });

            function performSearch(query) {
                $.ajax({
                    url: '{{ route("search.ajax") }}',
                    method: 'GET',
                    data: { q: query },
                    beforeSend: function () {
                        $('#searchResults').html(`
                    <div class="search-loading p-3">
                        <div class="text-center">
                            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                            <span class="ml-2">Recherche...</span>
                        </div>
                    </div>
                `).show();
                    },
                    success: function (response) {
                        if (response.html) {
                            $('#searchResults').html(response.html).show();
                        } else {
                            $('#searchResults').html(`
                        <div class="p-3">
                            <div class="text-center text-muted">
                                <i class="fas fa-search fa-lg mb-2"></i>
                                <p class="mb-0">Aucun résultat trouvé</p>
                            </div>
                        </div>
                    `).show();
                        }
                    },
                    error: function () {
                        $('#searchResults').html(`
                    <div class="p-3">
                        <div class="text-center text-danger">
                            <i class="fas fa-exclamation-circle fa-lg mb-2"></i>
                            <p class="mb-0">Erreur de recherche</p>
                        </div>
                    </div>
                `).show();
                    }
                });
            }

            // Cacher les résultats en cliquant ailleurs
            $(document).click(function (e) {
                if (!$(e.target).closest('.search-element').length) {
                    $('#searchResults').hide();
                }
            });

            // Marquer toutes les notifications comme lues
            $('.mark-all-read').click(function (e) {
                e.preventDefault();
                const url = $(this).data('url');

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function () {
                        $('.notification-unread').removeClass('notification-unread');
                        $('.badge-notification').remove();
                        showToast('Toutes les notifications ont été marquées comme lues', 'success');
                    }
                });
            });

            // Marquer une notification comme lue en cliquant dessus
            $('.dropdown-item[data-notification-id]').click(function () {
                const notificationId = $(this).data('notification-id');

                $.ajax({
                    url: '{{ route("notifications.markAsRead") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        notification_id: notificationId
                    }
                });
            });

            // Plein écran
            $('.fullscreen-btn').click(function (e) {
                e.preventDefault();
                const elem = document.documentElement;

                if (!document.fullscreenElement) {
                    if (elem.requestFullscreen) {
                        elem.requestFullscreen();
                    } else if (elem.webkitRequestFullscreen) {
                        elem.webkitRequestFullscreen();
                    } else if (elem.msRequestFullscreen) {
                        elem.msRequestFullscreen();
                    }
                    $(this).find('i').data('feather', 'minimize');
                    feather.replace();
                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    } else if (document.webkitExitFullscreen) {
                        document.webkitExitFullscreen();
                    } else if (document.msExitFullscreen) {
                        document.msExitFullscreen();
                    }
                    $(this).find('i').data('feather', 'maximize');
                    feather.replace();
                }
            });

            // Confirmation de déconnexion
            $('#logoutForm button').click(function (e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Déconnexion',
                    text: 'Êtes-vous sûr de vouloir vous déconnecter ?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Oui, déconnecter',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#logoutForm').submit();
                    }
                });
            });

            // Mettre à jour le statut en ligne
            function updateOnlineStatus() {
                $.ajax({
                    url: '{{ route("user.online") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    }
                });
            }

            // Mettre à jour le statut toutes les minutes
            setInterval(updateOnlineStatus, 60000);

            // Fonction pour afficher les toasts
            function showToast(message, type = 'info') {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });

                Toast.fire({
                    icon: type,
                    title: message
                });
            }

            // Initialiser Feather Icons
            feather.replace();
        });
    </script>
    <script>$(document).ready(function () {
            // Mode sombre/clair
            const themeToggle = $('#themeToggle');
            const body = $('body');

            // Vérifier le thème sauvegardé
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark') {
                body.addClass('dark-mode');
                themeToggle.find('.dark-icon').addClass('d-none');
                themeToggle.find('.light-icon').removeClass('d-none');
            }

            // Basculer le thème
            themeToggle.click(function (e) {
                e.preventDefault();
                body.toggleClass('dark-mode');

                if (body.hasClass('dark-mode')) {
                    localStorage.setItem('theme', 'dark');
                    themeToggle.find('.dark-icon').addClass('d-none');
                    themeToggle.find('.light-icon').removeClass('d-none');
                } else {
                    localStorage.setItem('theme', 'light');
                    themeToggle.find('.light-icon').addClass('d-none');
                    themeToggle.find('.dark-icon').removeClass('d-none');
                }
            });

            // Recherche en temps réel
            let searchTimeout;
            $('#globalSearch').on('input', function () {
                clearTimeout(searchTimeout);
                const query = $(this).val();

                if (query.length < 2) {
                    $('#searchResults').empty();
                    return;
                }

                searchTimeout = setTimeout(() => {
                    performSearch(query);
                }, 300);
            });

            function performSearch(query) {
                $.ajax({
                    url: '{{ route("search.ajax") }}',
                    method: 'GET',
                    data: { q: query },
                    beforeSend: function () {
                        $('#searchResults').html(`
                    <div class="search-loading p-3">
                        <div class="text-center">
                            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                            <span class="ml-2">Recherche...</span>
                        </div>
                    </div>
                `).show();
                    },
                    success: function (response) {
                        if (response.html) {
                            $('#searchResults').html(response.html).show();
                        } else {
                            $('#searchResults').html(`
                        <div class="p-3">
                            <div class="text-center text-muted">
                                <i class="fas fa-search fa-lg mb-2"></i>
                                <p class="mb-0">Aucun résultat trouvé</p>
                            </div>
                        </div>
                    `).show();
                        }
                    },
                    error: function () {
                        $('#searchResults').html(`
                    <div class="p-3">
                        <div class="text-center text-danger">
                            <i class="fas fa-exclamation-circle fa-lg mb-2"></i>
                            <p class="mb-0">Erreur de recherche</p>
                        </div>
                    </div>
                `).show();
                    }
                });
            }

            // Cacher les résultats en cliquant ailleurs
            $(document).click(function (e) {
                if (!$(e.target).closest('.search-element').length) {
                    $('#searchResults').hide();
                }
            });

            // Marquer toutes les notifications comme lues
            $('.mark-all-read').click(function (e) {
                e.preventDefault();
                const url = $(this).data('url');

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function () {
                        $('.notification-unread').removeClass('notification-unread');
                        $('.badge-notification').remove();
                        showToast('Toutes les notifications ont été marquées comme lues', 'success');
                    }
                });
            });

            // Marquer une notification comme lue en cliquant dessus
            $('.dropdown-item[data-notification-id]').click(function () {
                const notificationId = $(this).data('notification-id');

                $.ajax({
                    url: '{{ route("notifications.markAsRead") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        notification_id: notificationId
                    }
                });
            });

            // Plein écran
            $('.fullscreen-btn').click(function (e) {
                e.preventDefault();
                const elem = document.documentElement;

                if (!document.fullscreenElement) {
                    if (elem.requestFullscreen) {
                        elem.requestFullscreen();
                    } else if (elem.webkitRequestFullscreen) {
                        elem.webkitRequestFullscreen();
                    } else if (elem.msRequestFullscreen) {
                        elem.msRequestFullscreen();
                    }
                    $(this).find('i').data('feather', 'minimize');
                    feather.replace();
                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    } else if (document.webkitExitFullscreen) {
                        document.webkitExitFullscreen();
                    } else if (document.msExitFullscreen) {
                        document.msExitFullscreen();
                    }
                    $(this).find('i').data('feather', 'maximize');
                    feather.replace();
                }
            });

            // Confirmation de déconnexion
            $('#logoutForm button').click(function (e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Déconnexion',
                    text: 'Êtes-vous sûr de vouloir vous déconnecter ?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Oui, déconnecter',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#logoutForm').submit();
                    }
                });
            });

            // Mettre à jour le statut en ligne
            function updateOnlineStatus() {
                $.ajax({
                    url: '{{ route("user.online") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    }
                });
            }

            // Mettre à jour le statut toutes les minutes
            setInterval(updateOnlineStatus, 60000);

            // Fonction pour afficher les toasts
            function showToast(message, type = 'info') {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });

                Toast.fire({
                    icon: type,
                    title: message
                });
            }

            // Initialiser Feather Icons
            feather.replace();
        });</script>
    @stack('scripts')
</body>

</html>
