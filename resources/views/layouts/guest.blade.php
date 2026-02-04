<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('title', 'AFRI-HERITAGE Bénin')">
    <meta name="author" content="AFRI-HERITAGE Bénin">

    <title>@yield('title', 'AFRI-HERITAGE Bénin')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/afri-heritage-icon.svg') }}">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Open+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        :root {
            --benin-green: #009639;
            --benin-yellow: #FCD116;
            --benin-red: #E8112D;
            --terracotta: #D4754E;
            --beige: #F5E6D3;
            --charcoal: #639e62;
            --gold: #C9A962;
            --navy: #5c2d19;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            color: var(--charcoal);
            background-color: var(--beige);
            line-height: 1.6;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
        }

        /* Couleurs personnalisées */
        .bg-benin-green { background-color: var(--benin-green) !important; }
        .bg-benin-yellow { background-color: var(--benin-yellow) !important; }
        .bg-benin-red { background-color: var(--benin-red) !important; }
        .bg-terracotta { background-color: var(--terracotta) !important; }
        .bg-beige { background-color: var(--beige) !important; }
        .bg-charcoal { background-color: var(--charcoal) !important; }
        .bg-gold { background-color: var(--gold) !important; }
        .bg-navy { background-color: var(--navy) !important; }

        .text-benin-green { color: var(--benin-green) !important; }
        .text-benin-yellow { color: var(--benin-yellow) !important; }
        .text-benin-red { color: var(--benin-red) !important; }
        .text-terracotta { color: var(--terracotta) !important; }
        .text-beige { color: var(--beige) !important; }
        .text-charcoal { color: var(--charcoal) !important; }
        .text-gold { color: var(--gold) !important; }
        .text-navy { color: var(--navy) !important; }

        .btn-benin-green {
            background-color: var(--benin-green);
            border-color: var(--benin-green);
            color: white;
        }
        .btn-benin-green:hover {
            background-color: #00782e;
            border-color: #00782e;
            color: white;
        }

        .btn-benin-yellow {
            background-color: var(--benin-yellow);
            border-color: var(--benin-yellow);
            color: var(--charcoal);
        }
        .btn-benin-yellow:hover {
            background-color: #e6b800;
            border-color: #e6b800;
            color: var(--charcoal);
        }

        .btn-benin-red {
            background-color: var(--benin-red);
            border-color: var(--benin-red);
            color: white;
        }
        .btn-benin-red:hover {
            background-color: #c00f27;
            border-color: #c00f27;
            color: white;
        }

        .btn-outline-benin-green {
            border-color: var(--benin-green);
            color: var(--benin-green);
        }
        .btn-outline-benin-green:hover {
            background-color: var(--benin-green);
            color: white;
        }

        /* Input focus states */
        .form-control:focus,
        .form-select:focus {
            border-color: var(--benin-green);
            box-shadow: 0 0 0 0.25rem rgba(0, 150, 57, 0.15);
        }

        /* Custom checkbox */
        .form-check-input:checked {
            background-color: var(--benin-green);
            border-color: var(--benin-green);
        }

        .form-check-input:focus {
            border-color: var(--benin-green);
            box-shadow: 0 0 0 0.25rem rgba(0, 150, 57, 0.15);
        }

        /* Links */
        a {
            transition: all 0.3s ease;
        }

        /* Card animations */
        .card {
            transition: all 0.3s ease;
        }

        /* Backdrop blur effect */
        .backdrop-blur {
            backdrop-filter: blur(10px);
        }
    </style>

    <!-- Alpine.js pour l'interactivité -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('styles')
</head>
<body>
    <!-- Page Content -->
    <main>
        @yield('content')
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
