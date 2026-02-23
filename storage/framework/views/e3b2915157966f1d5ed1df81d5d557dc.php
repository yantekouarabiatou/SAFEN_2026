<?php $__env->startSection('title', 'TOTCHEMEGNON - Le Bénin authentique, raconté par l\'IA'); ?>

<?php $__env->startPush('styles'); ?>
<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    /* Hero Section amélioré */
    .hero-section {
        position: relative;
        min-height: 100vh;
        display: flex;
        align-items: center;
        overflow: hidden;
    }

    .hero-slider {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 0;
    }

    .hero-slider .swiper-slide {
        position: relative;
    }

    .hero-slider .swiper-slide::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(0, 150, 57, 0.651) 0%, rgba(94, 16, 16, 0.9) 100%);
        z-index: 1;
    }

    .hero-slider .swiper-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    /* Floating animation pour la carte IA */
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }

    .floating-card {
        animation: float 3s ease-in-out infinite;
    }

    /* Carousels personnalisés */
    .product-carousel .swiper-slide,
    .artisan-carousel .swiper-slide {
        height: auto;
    }

    .swiper-button-next,
    .swiper-button-prev {
        background: white;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .swiper-button-next:after,
    .swiper-button-prev:after {
        font-size: 20px;
        color: var(--benin-green);
        font-weight: bold;
    }

    .swiper-pagination-bullet {
        width: 12px;
        height: 12px;
        background: var(--benin-green);
        opacity: 0.4;
    }

    .swiper-pagination-bullet-active {
        opacity: 1;
        background: var(--benin-yellow);
    }

    /* Parallax effect */
    .parallax-section {
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }

    /* Category cards avec hover effects */
    .category-card {
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .category-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .category-card:hover::before {
        left: 100%;
    }

    .category-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    /* Product card améliorée */
    .product-card-enhanced {
        position: relative;
        overflow: hidden;
        border-radius: 15px;
        transition: all 0.3s ease;
    }

    .product-card-enhanced .product-image {
        position: relative;
        overflow: hidden;
        height: 250px;
    }

    .product-card-enhanced .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .product-card-enhanced:hover .product-image img {
        transform: scale(1.15);
    }

    .product-card-enhanced .product-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.7) 0%, transparent 60%);
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        align-items: flex-end;
        padding: 20px;
    }

    .product-card-enhanced:hover .product-overlay {
        opacity: 1;
    }

    /* Testimonial carousel */
    .testimonial-card {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        height: 100%;
    }

    /* Counter animation */
    .counter {
        font-size: 3rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--benin-green) 0%, var(--benin-yellow) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Search bar améliorée */
    .search-bar-enhanced {
        background: white;
        border-radius: 50px;
        padding: 8px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    }

    .search-bar-enhanced input {
        border: none;
        background: transparent;
        padding: 12px 24px;
    }

    .search-bar-enhanced input:focus {
        outline: none;
        box-shadow: none;
    }

    /* Bento grid pour la section culture */
    .bento-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    .bento-item {
        border-radius: 20px;
        overflow: hidden;
        position: relative;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .bento-item:hover {
        transform: scale(1.05);
    }

    .bento-item:nth-child(1) {
        grid-column: span 2;
        grid-row: span 2;
    }

    .bento-item:nth-child(2) {
        grid-column: span 2;
    }

    .bento-item:nth-child(3) {
        grid-column: span 2;
    }

    @media (max-width: 768px) {
        .bento-grid {
            grid-template-columns: 1fr;
        }

        .bento-item:nth-child(1) {
            grid-column: span 1;
            grid-row: span 1;
        }

        .hero-section {
            min-height: 70vh;
        }

        .counter {
            font-size: 2rem;
        }

    }
    .testimonial-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 15px;
        padding: 1.5rem;
        height: 100%;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .testimonial-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 150, 57, 0.1);
        border-color: var(--benin-green);
    }

    .rating-stars {
        color: #FFD700;
        font-size: 1.1rem;
    }

    .testimonial-carousel {
        position: relative;
        padding: 0 40px;
    }

    .testimonial-carousel .swiper-button-next,
    .testimonial-carousel .swiper-button-prev {
        color: var(--benin-green);
        background: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .testimonial-carousel .swiper-button-next:after,
    .testimonial-carousel .swiper-button-prev:after {
        font-size: 1.2rem;
    }

    .testimonial-carousel .swiper-pagination-bullet {
        background: #dee2e6;
        opacity: 1;
    }

    .testimonial-carousel .swiper-pagination-bullet-active {
        background: var(--benin-green);
    }

    @media (max-width: 768px) {
        .testimonial-carousel {
            padding: 0 20px;
        }

        .testimonial-carousel .swiper-button-next,
        .testimonial-carousel .swiper-button-prev {
            display: none;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .testimonial-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 15px;
        padding: 1.5rem;
        height: 100%;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .testimonial-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 150, 57, 0.1);
        border-color: var(--benin-green);
    }

    .rating-stars {
        color: #FFD700;
        font-size: 1.1rem;
    }

    .testimonial-carousel {
        position: relative;
        padding: 0 40px;
    }

    .testimonial-carousel .swiper-button-next,
    .testimonial-carousel .swiper-button-prev {
        color: var(--benin-green);
        background: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .testimonial-carousel .swiper-button-next:after,
    .testimonial-carousel .swiper-button-prev:after {
        font-size: 1.2rem;
    }

    .testimonial-carousel .swiper-pagination-bullet {
        background: #dee2e6;
        opacity: 1;
    }

    .testimonial-carousel .swiper-pagination-bullet-active {
        background: var(--benin-green);
    }

    @media (max-width: 768px) {
        .testimonial-carousel {
            padding: 0 20px;
        }

        .testimonial-carousel .swiper-button-next,
        .testimonial-carousel .swiper-button-prev {
            display: none;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Section avec Slider -->
<section class="hero-section">
    <!-- Background Slider -->
    <div class="hero-slider swiper">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <img src="<?php echo e(asset('artisans/artisan3.jpg')); ?>" alt="Artisan béninois">
            </div>
            <div class="swiper-slide">
                <img src="<?php echo e(asset('dishes/ebaSauegombo.jpg')); ?>" alt="Artisanat traditionnel">
            </div>
            <div class="swiper-slide">
                <img src="<?php echo e(asset('products/tissu.jpg')); ?>" alt="Culture béninoise">
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="container hero-content">
        <div class="row align-items-center">
            <div class="col-lg-7" data-aos="fade-right">
                <div class="mb-3">
                    <span class="badge bg-benin-yellow text-charcoal px-3 py-2 rounded-pill">
                        🇧🇯 Propulsé par l'Intelligence Artificielle
                    </span>
                </div>

                <h1 class="display-3 fw-bold text-white mb-4">
                    L'<span class="text-benin-yellow">Artisanat Béninois</span><br>
                    à Portée de Clic
                </h1>

                <p class="lead text-white mb-4 fs-4" style="max-width: 600px;">
                    Découvrez, comprenez et acquérez l'artisanat authentique du Bénin grâce à notre plateforme intelligente.
                </p>

                <!-- Search Bar Améliorée -->
                <div class="search-bar-enhanced mb-4" style="max-width: 650px;">
                    <form action="<?php echo e(route('search')); ?>" method="GET">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-search fs-5 text-muted ms-3"></i>
                            <input type="text" class="form-control form-control-lg flex-grow-1"
                                   name="q"
                                   placeholder="Rechercher un artisan, produit, plat traditionnel..."
                                   aria-label="Recherche">
                            <button class="btn btn-benin-green rounded-pill px-4 me-2" type="submit">
                                <i class="bi bi-arrow-right me-2"></i> Rechercher
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Quick Stats avec animation -->
                <div class="row g-4 mt-2">
                    <div class="col-6 col-md-3">
                        <div class="counter" data-target="<?php echo e($stats['artisans']); ?>"><?php echo e($stats['artisans']); ?></div>
                        <small class="text-white-50 d-block">Artisans</small>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="counter" data-target="<?php echo e($stats['products']); ?>"><?php echo e($stats['products']); ?></div>
                        <small class="text-white-50 d-block">Produits</small>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="counter" data-target="<?php echo e($stats['dishes']); ?>"><?php echo e($stats['dishes']); ?></div>
                        <small class="text-white-50 d-block">Plats</small>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="counter" data-target="12">0</div>
                        <small class="text-white-50 d-block">Départements</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 d-none d-lg-block" data-aos="fade-left">
                <div class="card floating-card border-0 shadow-lg">
                    <div class="card-body text-center p-5">
                        <div class="rounded-circle bg-gradient d-inline-flex align-items-center justify-content-center mb-4"
                             style="width: 100px; height: 100px; background: linear-gradient(135deg, var(--benin-green), var(--navy));">
                            <i class="bi bi-robot text-dark" style="font-size: 3rem;"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Anansi - Assistant IA</h4>
                        <p class="text-muted mb-4">
                            Votre guide culturel intelligent. Posez vos questions sur le Bénin, découvrez des artisans,
                            explorez notre patrimoine.
                        </p>
                        <button class="btn btn-benin-green w-100 rounded-pill"
                                onclick="document.querySelector('.chatbot-btn').click()">
                            <i class="bi bi-chat-left-text me-2"></i> Parler à Anansi
                        </button>
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="bi bi-check-circle-fill text-success me-1"></i> Disponible 24/7
                                <i class="bi bi-translate text-info ms-2 me-1"></i> Multilingue
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section avec effets -->
<section class="py-5 bg-beige">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="badge bg-benin-green text-white px-3 py-2 rounded-pill mb-3">
                Explorez nos catégories
            </span>
            <h2 class="display-5 fw-bold text-charcoal mb-3">Découvrez le Bénin Authentique</h2>
            <p class="text-muted fs-5" style="max-width: 700px; margin: 0 auto;">
                Plongez dans la richesse culturelle béninoise à travers nos différentes catégories
            </p>
        </div>

        <div class="row g-4">
            <!-- Artisans Card -->
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="category-card card border-0 shadow-sm h-100">
                    <div class="card-body p-4 text-center">
                        <div class="mb-4">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                 style="width: 90px; height: 90px; background: linear-gradient(135deg, var(--benin-green), #00c04b);">
                                <i class="bi bi-tools text-white" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold mb-3">Artisans & Services</h4>
                        <p class="text-muted mb-4">
                            Trouvez des artisans qualifiés près de chez vous : tailleurs, mécaniciens, coiffeurs, menuisiers,commerçante...
                        </p>
                        <ul class="list-unstyled text-start mb-4 small text-muted">
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-benin-green me-2"></i> Géolocalisation précise</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-benin-green me-2"></i> Avis vérifiés</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-benin-green me-2"></i> Contact direct</li>
                        </ul>
                        <a href="<?php echo e(route('artisans.vue')); ?>" class="btn btn-benin-green w-100 rounded-pill">
                            <i class="bi bi-compass me-2"></i> Explorer les artisans
                        </a>
                    </div>
                </div>
            </div>

            <!-- Marketplace Card -->
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="category-card card border-0 shadow-sm h-100">
                    <div class="card-body p-4 text-center">
                        <div class="mb-4">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                 style="width: 90px; height: 90px; background: linear-gradient(135deg, var(--benin-yellow), #ffe14d);">
                                <i class="bi bi-palette text-charcoal" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold mb-3">Arts & Artisanat</h4>
                        <p class="text-muted mb-4">
                            Achetez des objets artisanaux authentiques directement auprès des créateurs béninois.
                        </p>
                        <ul class="list-unstyled text-start mb-4 small text-muted">
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-gold me-2"></i> Produits authentiques</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-gold me-2"></i> Histoire culturelle IA</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-gold me-2"></i> Livraison sécurisée</li>
                        </ul>
                        <a href="<?php echo e(route('products.index')); ?>" class="btn btn-benin-yellow w-100 rounded-pill text-charcoal">
                            <i class="bi bi-compass me-2"></i> Voir la marketplace
                        </a>
                    </div>
                </div>
            </div>

             <!-- Gastronomie Card -->
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="category-card card border-0 shadow-sm h-100">
                    <div class="card-body p-4 text-center">
                        <div class="mb-4">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                 style="width: 90px; height: 90px; background: linear-gradient(135deg, var(--benin-red), #ff2d47);">
                                <i class="bi bi-egg-fried text-white" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold mb-3">Gastronomie</h4>
                        <p class="text-muted mb-4">
                            Découvrez les saveurs authentiques du Bénin avec nos plats traditionnels et leurs histoires fascinantes.
                        </p>
                        <ul class="list-unstyled text-start mb-4 small text-muted">
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-benin-red me-2"></i> Recettes traditionnelles</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-benin-red me-2"></i> Audio prononciation</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-benin-red me-2"></i> Origine culturelle</li>
                        </ul>
                        <a href="<?php echo e(route('gastronomie.index')); ?>" class="btn btn-benin-red w-100 rounded-pill">
                            <i class="bi bi-compass me-2"></i> Découvrir les plats
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Carousel -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5" data-aos="fade-up">
            <div>
                <span class="badge bg-benin-yellow text-charcoal px-3 py-2 rounded-pill mb-2">
                    ✨ Sélection
                </span>
                <h2 class="display-6 fw-bold text-charcoal mb-2">Produits en Vedette</h2>
                <p class="text-muted">Découvrez notre sélection coup de cœur d'objets authentiques</p>
            </div>
            <a href="<?php echo e(route('products.index')); ?>" class="btn btn-outline-benin-green rounded-pill d-none d-md-inline-flex">
                Voir tous <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>

        <!-- Swiper Carousel -->
        <div class="product-carousel swiper" data-aos="fade-up" data-aos-delay="100">
            <div class="swiper-wrapper">
                <?php $__currentLoopData = $featuredProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="swiper-slide">
                    <div class="product-card-enhanced card border-0 shadow-sm h-100">
                        <div class="product-image">
                            <img src="<?php echo e($product->primaryImage->image_url ?? asset('images/default-product.jpg')); ?>"
                                 alt="<?php echo e($product->name); ?>">

                            <!-- Badges -->
                            <div class="position-absolute top-0 start-0 m-3">
                                <?php if($product->featured): ?>
                                    <span class="badge bg-benin-yellow text-charcoal rounded-pill px-3 py-2">
                                        <i class="bi bi-star-fill me-1"></i> Vedette
                                    </span>
                                <?php endif; ?>
                            </div>

                            <!-- Favorite Button -->
                            <button class="position-absolute top-0 end-0 m-3 btn btn-light rounded-circle"
                                    style="width: 40px; height: 40px;">
                                <i class="bi bi-heart"></i>
                            </button>

                            <!-- Overlay avec actions -->
                            <div class="product-overlay">
                                <div class="w-100">
                                    <div class="d-flex gap-2">
                                        <a href="<?php echo e(route('products.show', $product)); ?>"
                                           class="btn btn-light flex-grow-1">
                                            <i class="bi bi-eye me-1"></i> Voir
                                        </a>
                                        <button class="btn btn-benin-green"
                                                onclick="speakText('<?php echo e($product->name_local); ?>')">
                                            <i class="bi bi-volume-up"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="card-title mb-0 fw-bold"><?php echo e(Str::limit($product->name, 35)); ?></h6>
                            </div>

                            <p class="text-muted small mb-2">
                                <i class="bi bi-person me-1"></i> <?php echo e($product->artisan->user->name); ?>

                            </p>

                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-light text-muted me-2"><?php echo e($product->category); ?></span>
                                <small class="text-muted"><?php echo e($product->ethnic_origin); ?></small>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-benin-green fw-bold fs-5"><?php echo e($product->formatted_price); ?></span>
                                <span class="text-muted small">
                                    <i class="bi bi-eye me-1"></i> <?php echo e(rand(50, 500)); ?> vues
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Navigation -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>

            <!-- Pagination -->
            <div class="swiper-pagination mt-4"></div>
        </div>

        <!-- Mobile View All Button -->
        <div class="text-center mt-4 d-md-none">
            <a href="<?php echo e(route('products.index')); ?>" class="btn btn-outline-benin-green rounded-pill">
                Voir tous les produits <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Featured Artisans Carousel -->
<section class="py-5 bg-beige">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5" data-aos="fade-up">
            <div>
                <span class="badge bg-benin-green text-white px-3 py-2 rounded-pill mb-2">
                    🏆 Top Artisans
                </span>
                <h2 class="display-6 fw-bold text-charcoal mb-2">Artisans du Mois</h2>
                <p class="text-muted">Découvrez nos artisans les mieux notés et vérifiés</p>
            </div>
            <a href="<?php echo e(route('artisans.vue')); ?>" class="btn btn-outline-benin-green rounded-pill d-none d-md-inline-flex">
                Voir tous <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>

        <!-- Swiper Carousel -->
        <div class="artisan-carousel swiper" data-aos="fade-up" data-aos-delay="100">
            <div class="swiper-wrapper">
                <?php $__currentLoopData = $featuredArtisans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $artisan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="swiper-slide">
                    <div class="card border-0 shadow-sm h-100 overflow-hidden">
                        <div class="position-relative">
                            <img src="<?php echo e($artisan->photos->first()->photo_url ?? asset('images/default-artisan.jpg')); ?>"
                                 alt="<?php echo e($artisan->user->name); ?>"
                                 class="card-img-top"
                                 style="height: 250px; object-fit: cover;">

                            <!-- Verified Badge -->
                            <?php if($artisan->verified): ?>
                                <span class="position-absolute top-0 end-0 m-3 badge bg-benin-green rounded-pill px-3 py-2">
                                    <i class="bi bi-patch-check-fill me-1"></i> Vérifié
                                </span>
                            <?php endif; ?>

                            <!-- Availability indicator -->
                            <div class="position-absolute bottom-0 start-0 m-3">
                                <span class="badge bg-success rounded-pill px-3 py-2">
                                    <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i> Disponible
                                </span>
                            </div>
                        </div>

                        <div class="card-body text-center p-4">
                            <h5 class="card-title fw-bold mb-2"><?php echo e($artisan->user->name); ?></h5>
                            <p class="text-benin-green fw-semibold mb-3">
                                <i class="bi bi-tools me-1"></i> <?php echo e($artisan->craft_label); ?>

                            </p>

                            <!-- Rating -->
                            <div class="rating-stars mb-3">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <i class="bi <?php echo e($i <= floor($artisan->rating_avg) ? 'bi-star-fill' : 'bi-star'); ?>"></i>
                                <?php endfor; ?>
                                <span class="text-muted ms-2 small">(<?php echo e($artisan->rating_count); ?> avis)</span>
                            </div>

                            <!-- Location -->
                            <p class="text-muted small mb-3">
                                <i class="bi bi-geo-alt-fill text-benin-red me-1"></i>
                                <?php echo e($artisan->city); ?>, <?php echo e($artisan->neighborhood); ?>

                            </p>

                            <!-- Experience -->
                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="bi bi-calendar-check me-1"></i>
                                    <?php echo e($artisan->years_experience); ?>+ ans d'expérience
                                </small>
                            </div>

                            <!-- Actions -->
                            <div class="d-flex gap-2">
                                <a href="https://wa.me/<?php echo e($artisan->whatsapp); ?>" target="_blank"
                                   class="btn btn-success flex-fill rounded-pill">
                                    <i class="bi bi-whatsapp"></i>
                                </a>
                                <a href="tel:<?php echo e($artisan->phone); ?>"
                                   class="btn btn-outline-benin-green flex-fill rounded-pill">
                                    <i class="bi bi-telephone"></i>
                                </a>
                                <a href="<?php echo e(route('artisans.show', $artisan)); ?>"
                                   class="btn btn-benin-green flex-fill rounded-pill">
                                    Profil
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Navigation -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>

            <!-- Pagination -->
            <div class="swiper-pagination mt-4"></div>
        </div>
    </div>
</section>

<!-- Testimonials Carousel -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="badge bg-benin-red text-white px-3 py-2 rounded-pill mb-2">
                💬 Témoignages
            </span>
            <h2 class="display-6 fw-bold text-charcoal mb-2">Ce que disent nos utilisateurs</h2>
            <p class="text-muted">Découvrez les expériences de notre communauté</p>
        </div>

        <?php if($testimonials->count() > 0): ?>
            <div class="testimonial-carousel swiper" data-aos="fade-up" data-aos-delay="100">
                <div class="swiper-wrapper pb-5">
                    <?php $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimonial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            // Gérer les témoignages fallback et dynamiques
                            $userName = $testimonial->user->name ?? 'Utilisateur';
                            $userImage = $testimonial->user->avatar_url ?? asset('images/default-user.jpg');
                            $userRole = $testimonial->reviewable->craft_label ?? 'Client';
                            $userCity = $testimonial->reviewable->city ?? '';

                            // Si c'est un fallback, utiliser les données fallback
                            if (isset($testimonial->is_fallback) && $testimonial->is_fallback) {
                                $userImage = $testimonial->user->avatar_url;
                                $userRole = $testimonial->reviewable->craft_label;
                                $userCity = $testimonial->reviewable->city ?? '';
                            }
                        ?>

                        <div class="swiper-slide">
                            <div class="testimonial-card">
                                <div class="d-flex align-items-center mb-3">
                                    <img src="<?php echo e($userImage); ?>"
                                         alt="<?php echo e($userName); ?>"
                                         class="rounded-circle me-3"
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                    <div>
                                        <h6 class="mb-0 fw-bold"><?php echo e($userName); ?></h6>
                                        <small class="text-muted">
                                            <?php echo e($userRole); ?>

                                            <?php if($userCity): ?>
                                                • <?php echo e($userCity); ?>

                                            <?php endif; ?>
                                        </small>
                                    </div>
                                </div>
                                <div class="rating-stars mb-3">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <i class="bi bi-star<?php echo e($i <= $testimonial->rating ? '-fill' : ''); ?> text-warning"></i>
                                    <?php endfor; ?>
                                    <small class="text-muted ms-2"><?php echo e($testimonial->rating); ?>/5</small>
                                </div>
                                <p class="text-muted mb-0">
                                    "<?php echo e(Str::limit($testimonial->comment, 180)); ?>"
                                </p>
                                <?php if(isset($testimonial->created_at)): ?>
                                    <small class="text-benin-green mt-2 d-block">
                                        <i class="bi bi-calendar me-1"></i>
                                        <?php echo e($testimonial->created_at->format('d/m/Y')); ?>

                                    </small>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <!-- Pagination -->
                <div class="swiper-pagination"></div>

                <!-- Navigation -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        <?php else: ?>
            <div class="text-center py-4">
                <i class="bi bi-chat-quote fs-1 text-muted mb-3"></i>
                <p class="text-muted">Soyez le premier à partager votre expérience !</p>
                <a href="<?php echo e(route('artisans.index')); ?>" class="btn btn-benin-green">
                    Découvrir les artisans
                </a>
            </div>
        <?php endif; ?>

        <!-- CTA pour laisser un avis -->
        <?php if(auth()->guard()->check()): ?>
            <div class="text-center mt-5">
                <div class="card border-0 shadow-sm" style="max-width: 600px; margin: 0 auto;">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Partagez votre expérience</h5>
                        <p class="text-muted mb-3">
                            Votre avis aide notre communauté à grandir et permet aux artisans de s'améliorer.
                        </p>
                        <a href="<?php echo e(route('artisans.vue')); ?>" class="btn btn-benin-green">
                            <i class="bi bi-star me-2"></i>Laisser un avis
                        </a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center mt-5">
                <p class="text-muted mb-3">Connectez-vous pour partager votre expérience</p>
                <a href="<?php echo e(route('login')); ?>" class="btn btn-outline-benin-green me-2">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                </a>
                <a href="<?php echo e(route('register')); ?>" class="btn btn-benin-green">
                    <i class="bi bi-person-plus me-2"></i>S'inscrire
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>



<!-- Culture Section avec Bento Grid -->
<section class="py-5 parallax-section"
         style="background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.8)), url('<?php echo e(asset('products/tissu.jpg')); ?>');">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 text-white mb-4 mb-lg-0" data-aos="fade-right">
                <span class="badge bg-benin-yellow text-charcoal px-3 py-2 rounded-pill mb-3">
                    📚 Culture & Patrimoine
                </span>
                <h2 class="display-5 fw-bold mb-4">Découvrez l'Histoire du Bénin</h2>

                <div class="culture-fact card bg-dark border-benin-yellow border-2 mb-4">
                    <div class="card-body">
                        <h5 class="text-benin-yellow fw-bold mb-3" id="fact-title"></h5>
                        <p class="text-white-50 mb-3" id="fact-content"></p>
                        <button class="btn btn-outline-benin-yellow btn-sm rounded-pill" onclick="loadNewFact()">
                            <i class="bi bi-arrow-repeat me-2" style="color: aliceblue"></i>Autre anecdote
                        </button>
                    </div>
                </div>

                <!-- Quick Question to AI -->
                <div class="card bg-white bg-opacity-10 border-0 backdrop-blur">
                    <div class="card-body">
                        <h6 class="text-benin-yellow mb-3">
                            <i class="bi bi-robot me-2"></i> Posez une question à Anansi
                        </h6>
                        <div class="input-group">
                            <input type="text" class="form-control bg-dark text-white border-0"
                                   id="quick-question"
                                   placeholder="Ex: C'est quoi le Zangbeto ?">
                            <button class="btn btn-benin-green" onclick="askQuickQuestion()">
                                <i class="bi bi-send"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7" data-aos="fade-left">
                <div class="bento-grid">
                    <!-- Large item -->
                    <div class="bento-item bg-benin-green d-flex align-items-center justify-content-center p-5">
                        <div class="text-center text-white">
                            <i class="bi bi-globe fs-1 mb-3"></i>
                            <h3 class="fw-bold mb-2">12 Départements</h3>
                            <p class="mb-0">Diversité culturelle unique</p>
                        </div>
                    </div>

                    <!-- Medium items -->
                    <div class="bento-item bg-benin-yellow d-flex align-items-center justify-content-center p-4">
                        <div class="text-center text-charcoal">
                            <i class="bi bi-people fs-1 mb-2"></i>
                            <h4 class="fw-bold mb-1">50+ Ethnies</h4>
                            <small>Richesse patrimoniale</small>
                        </div>
                    </div>

                    <div class="bento-item bg-benin-red d-flex align-items-center justify-content-center p-4">
                        <div class="text-center text-white">
                            <i class="bi bi-translate fs-1 mb-2"></i>
                            <h4 class="fw-bold mb-1">60+ Langues</h4>
                            <small>Diversité linguistique</small>
                        </div>
                    </div>

                    <div class="bento-item bg-terracotta d-flex align-items-center justify-content-center p-4">
                        <div class="text-center text-white">
                            <i class="bi bi-award fs-1 mb-2"></i>
                            <h4 class="fw-bold mb-1">UNESCO</h4>
                            <small>Sites classés</small>
                        </div>
                    </div>

                    <div class="bento-item bg-navy d-flex align-items-center justify-content-center p-4">
                        <div class="text-center text-white">
                            <i class="bi bi-star fs-1 mb-2"></i>
                            <h4 class="fw-bold mb-1">Vaudou</h4>
                            <small>Berceau mondial</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5" style="background: linear-gradient(135deg, var(--benin-green) 0%, var(--navy) 100%);">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-8" data-aos="zoom-in">
                <span class="badge bg-benin-yellow text-charcoal px-4 py-2 rounded-pill mb-3 fs-6">
                    🚀 Rejoignez-nous
                </span>
                <h2 class="display-4 text-white fw-bold mb-4">
                    Prêt à Découvrir le Bénin Authentique ?
                </h2>
                <p class="text-white-50 fs-5 mb-5" style="max-width: 700px; margin: 0 auto;">
                    Rejoignez notre communauté et participez à la valorisation de l'artisanat béninois.
                    Que vous soyez artisan, amoureux de culture ou simple curieux.
                </p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="<?php echo e(route('register')); ?>" class="btn btn-benin-yellow btn-lg rounded-pill px-5">
                        <i class="bi bi-person-plus me-2"></i> Créer mon compte
                    </a>
                    <a href="<?php echo e(route('artisans.vue')); ?>" class="btn btn-outline-light btn-lg rounded-pill px-5">
                        <i class="bi bi-compass me-2"></i> Explorer
                    </a>
                </div>

                <!-- Trust badges -->
                <div class="mt-5 pt-4 border-top border-white border-opacity-25">
                    <div class="row g-4 text-white-50">
                        <div class="col-6 col-md-3">
                            <i class="bi bi-shield-check fs-3 text-benin-yellow mb-2"></i>
                            <div class="small">Paiements sécurisés</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <i class="bi bi-truck fs-3 text-benin-yellow mb-2"></i>
                            <div class="small">Livraison garantie</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <i class="bi bi-patch-check fs-3 text-benin-yellow mb-2"></i>
                            <div class="small">Artisans vérifiés</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <i class="bi bi-headset fs-3 text-benin-yellow mb-2"></i>
                            <div class="small">Support 24/7</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- AOS Animation Library -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<script>
// Initialize AOS
AOS.init({
    duration: 800,
    once: true,
    offset: 100
});

// Hero Background Slider
const heroSlider = new Swiper('.hero-slider', {
    loop: true,
    autoplay: {
        delay: 5000,
        disableOnInteraction: false,
    },
    effect: 'fade',
    fadeEffect: {
        crossFade: true
    },
    speed: 1500
});

// Product Carousel
const productCarousel = new Swiper('.product-carousel', {
    slidesPerView: 1,
    spaceBetween: 20,
    loop: true,
    autoplay: {
        delay: 3000,
        disableOnInteraction: false,
    },
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    breakpoints: {
        640: {
            slidesPerView: 2,
            spaceBetween: 20,
        },
        768: {
            slidesPerView: 3,
            spaceBetween: 25,
        },
        1024: {
            slidesPerView: 4,
            spaceBetween: 30,
        },
    }
});

// Artisan Carousel
const artisanCarousel = new Swiper('.artisan-carousel', {
    slidesPerView: 1,
    spaceBetween: 20,
    loop: true,
    autoplay: {
        delay: 4000,
        disableOnInteraction: false,
    },
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    breakpoints: {
        640: {
            slidesPerView: 2,
            spaceBetween: 20,
        },
        768: {
            slidesPerView: 3,
            spaceBetween: 25,
        },
        1024: {
            slidesPerView: 4,
            spaceBetween: 30,
        },
    }
});

// Testimonial Carousel
const testimonialCarousel = new Swiper('.testimonial-carousel', {
    slidesPerView: 1,
    spaceBetween: 30,
    loop: true,
    autoplay: {
        delay: 5000,
        disableOnInteraction: false,
    },
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },
    breakpoints: {
        768: {
            slidesPerView: 2,
            spaceBetween: 30,
        },
        1024: {
            slidesPerView: 3,
            spaceBetween: 30,
        },
    }
});

// Counter Animation
function animateCounter(element) {
    const target = parseInt(element.dataset.target);
    const duration = 2000;
    const step = target / (duration / 16);
    let current = 0;

    const timer = setInterval(() => {
        current += step;
        if (current >= target) {
            element.textContent = target + '+';
            clearInterval(timer);
        } else {
            element.textContent = Math.floor(current);
        }
    }, 16);
}

// Trigger counter animation when visible
const counters = document.querySelectorAll('.counter');
const observerOptions = {
    threshold: 0.5
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting && !entry.target.classList.contains('animated')) {
            animateCounter(entry.target);
            entry.target.classList.add('animated');
        }
    });
}, observerOptions);

counters.forEach(counter => observer.observe(counter));

// Culture Facts
const cultureFacts = [
    {
        title: "Le masque Guèlèdè 🎭",
        content: "Les masques Guèlèdè du peuple Yoruba sont classés au patrimoine immatériel de l'UNESCO. Ils célèbrent les femmes et la maternité lors de cérémonies spectaculaires."
    },
    {
        title: "La Route de l'Esclave 🛤️",
        content: "Ouidah était l'un des principaux ports de départ des esclaves vers les Amériques. Aujourd'hui, c'est un lieu de mémoire important avec la Porte du Non-Retour."
    },
    {
        title: "Le tissu Kente 🧵",
        content: "Bien qu'associé au Ghana, le tissage Kente est aussi pratiqué au Bénin, avec des motifs spécifiques à chaque ethnie et occasion cérémonielle."
    },
    {
        title: "Les Tata Somba 🏰",
        content: "Dans le nord du Bénin, les Tata Somba sont des maisons-forteresses en terre typiques du peuple Bétammaribé, inscrites au patrimoine mondial de l'UNESCO."
    },
    {
        title: "Le Berceau du Vaudou 🕯️",
        content: "Le Bénin est considéré comme le berceau du vaudou, religion traditionnelle toujours pratiquée par plus de 60% de la population et reconnue officiellement."
    },
    {
        title: "Les Amazones du Dahomey ⚔️",
        content: "Le royaume du Dahomey avait une armée de femmes guerrières redoutables, les Amazones, qui ont inspiré le film Black Panther."
    },
    {
        title: "Le Royaume d'Abomey 👑",
        content: "Abomey était la capitale du puissant royaume du Dahomey. Ses palais royaux sont classés au patrimoine mondial de l'UNESCO."
    }
];

function loadNewFact() {
    const fact = cultureFacts[Math.floor(Math.random() * cultureFacts.length)];
    document.getElementById('fact-title').textContent = fact.title;
    document.getElementById('fact-content').textContent = fact.content;
}

function askQuickQuestion() {
    const question = document.getElementById('quick-question').value;
    if (question.trim()) {
        document.querySelector('.chatbot-btn').click();

        setTimeout(() => {
            const chatbotData = Alpine.$data(document.querySelector('[x-data="chatbot()"]'));
            if (chatbotData) {
                chatbotData.input = question;
                chatbotData.sendMessage();
                document.getElementById('quick-question').value = '';
            }
        }, 500);
    }
}

// Text-to-Speech function
function speakText(text) {
    if ('speechSynthesis' in window) {
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = 'fr-FR';
        window.speechSynthesis.speak(utterance);
    } else {
        // Fallback: call backend for audio generation
        console.log('Synthèse vocale non supportée, utiliser ElevenLabs API');
    }
}

    // Initialiser le carousel de témoignages
    const testimonialSwiper = new Swiper('.testimonial-carousel', {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
            },
            1024: {
                slidesPerView: 3,
            },
        }
    });

// Load initial fact
document.addEventListener('DOMContentLoaded', function() {
    loadNewFact();
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SAFEN_2026\resources\views/home.blade.php ENDPATH**/ ?>