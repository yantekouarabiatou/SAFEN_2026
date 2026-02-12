<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('title', 'Administration') - TOTCHEMEGNON Admin</title>
    
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
    <link rel="stylesheet" href="{{ asset('admin-assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    
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
        
        .bg-benin-green { background-color: var(--benin-green) !important; }
        .text-benin-green { color: var(--benin-green) !important; }
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
        
        .badge-benin { background-color: var(--benin-green); color: white; }
    </style>
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            
            {{-- Navbar --}}
            @include('admin.partials.navbar')
            
            {{-- Sidebar - Selon le rôle de l'utilisateur --}}
            @if(auth()->user()->hasRole('admin'))
                @include('admin.partials.sidebar')
            @elseif(auth()->user()->hasRole('artisan'))
                @include('admin.partials.sidebar-artisan')
            @elseif(auth()->user()->hasRole('vendor'))
                @include('admin.partials.sidebar-vendor')
            @else
                @include('admin.partials.sidebar-artisan')
            @endif
            
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
                    &copy; {{ date('Y') }} <a href="{{ url('/') }}">TOTCHEMEGNON</a> - Saveurs, Artisanat et Ethnies du Bénin
                </div>
                <div class="footer-right">
                    Version 1.0
                </div>
            </footer>
        </div>
    </div>
    
    <!-- General JS Scripts -->
    <script src="{{ asset('admin-assets/js/app.min.js') }}"></script>
    
    <!-- JS Libraries -->
    <script src="{{ asset('admin-assets/bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin-assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
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
    
    @stack('scripts')
</body>
</html>
