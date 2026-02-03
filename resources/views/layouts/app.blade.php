<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="AFRI-HERITAGE - Marketplace culturelle béninoise propulsée par l'IA">
    <meta name="author" content="AFRI-HERITAGE Bénin">

    <title>@yield('title', 'AFRI-HERITAGE Bénin')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

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
            --charcoal: #2C2C2C;
            --gold: #C9A962;
            --navy: #1E3A5F;
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

        .btn-outline-benin-yellow {
            border-color: var(--benin-yellow);
            color: var(--charcoal);
        }
        .btn-outline-benin-yellow:hover {
            background-color: var(--benin-yellow);
            color: var(--charcoal);
        }

        /* Navbar */
        .navbar-afri {
            background-color: white;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            padding: 1rem 0;
        }

        .navbar-afri .nav-link {
            color: var(--charcoal) !important;
            font-weight: 500;
            margin: 0 0.5rem;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .navbar-afri .nav-link:hover,
        .navbar-afri .nav-link.active {
            background-color: var(--benin-green);
            color: white !important;
        }

        .lang-selector .btn {
            border: 1px solid #dee2e6;
            color: var(--charcoal);
        }

        /* Cards */
        .card-artisan,
        .card-product {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-artisan:hover,
        .card-product:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Footer */
        .footer-afri {
            background: linear-gradient(135deg, var(--navy) 0%, var(--charcoal) 100%);
            color: white;
        }

        .footer-afri a {
            color: var(--beige);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-afri a:hover {
            color: var(--benin-yellow);
        }

        /* Chatbot */
        .chatbot-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--benin-green) 0%, var(--navy) 100%);
            border-radius: 50%;
            border: none;
            color: white;
            box-shadow: 0 5px 20px rgba(0, 150, 57, 0.3);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .chatbot-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 25px rgba(0, 150, 57, 0.4);
        }

        .chatbot-window {
            position: fixed;
            bottom: 100px;
            right: 30px;
            width: 350px;
            max-height: 500px;
            z-index: 1001;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border-radius: 16px;
            overflow: hidden;
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .audio-btn {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: var(--benin-green);
            border: none;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .audio-btn:hover {
            background-color: var(--navy);
            transform: scale(1.1);
        }

        /* Rating stars */
        .rating-stars {
            color: var(--benin-yellow);
        }

        /* Badges */
        .badge.bg-benin-green,
        .badge.bg-benin-yellow,
        .badge.bg-benin-red {
            color: white;
        }

        /* Hero sections */
        .hero-section {
            background: linear-gradient(rgba(44, 44, 44, 0.7), rgba(44, 44, 44, 0.9));
            color: white;
            padding: 4rem 0;
            position: relative;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .chatbot-window {
                width: calc(100vw - 40px);
                right: 20px;
                left: 20px;
            }

            .navbar-afri .nav-link {
                margin: 0.25rem 0;
                padding: 0.75rem 1rem !important;
            }
        }
    </style>

    <!-- Alpine.js pour l'interactivité -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    @include('partials.navbar')

    <!-- Page Content -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('partials.footer')

    <!-- Chatbot Widget -->
    @include('partials.chatbot')

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Google Maps API (pour les cartes) -->
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_KEY&libraries=places"></script>

    @stack('scripts')
</body>
</html>
