<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ArtisanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ArtisanProfileController;
use App\Http\Controllers\GastronomieController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\CultureController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CulturalEventController;

// Routes pour changer la langue
Route::get('/lang/{locale}', [LanguageController::class, 'switchLang'])->name('lang.switch');

// Route de test multilangue
Route::get('/test', [TestController::class, 'index'])->name('test');

// Pages publiques
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/help', [HomeController::class, 'help'])->name('help');
Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/legal', [HomeController::class, 'legal'])->name('legal');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');
Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
// Routes pour les témoignages/avis
Route::get('/artisan/{artisan}/testimonials', [HomeController::class, 'getArtisanTestimonials'])->name('artisan.testimonials');
Route::get('/product/{product}/testimonials', [HomeController::class, 'getProductTestimonials'])->name('product.testimonials');
Route::post('/testimonials/submit', [HomeController::class, 'submitTestimonial'])->name('testimonials.submit');
// Culture & Patrimoine
Route::get('/culture', [CultureController::class, 'index'])->name('culture.index');
Route::get('/culture/traditions', [CultureController::class, 'traditions'])->name('culture.traditions');
Route::get('/culture/history', [CultureController::class, 'history'])->name('culture.history');
Route::get('/culture/festivals', [CultureController::class, 'festivals'])->name('culture.festivals');
Route::get('/culture/ethnies', [CultureController::class, 'ethnies'])->name('culture.ethnies');


Route::get('/artisans/{artisan}/reviews', [ArtisanController::class, 'reviews'])
    ->name('artisans.reviews');

// Gastronomie
Route::get('/gastronomie', [GastronomieController::class, 'index'])->name('gastronomie.index');
Route::get('/gastronomie/{dish}', [GastronomieController::class, 'show'])->name('gastronomie.show');

// Marketplace (Produits)
//Route::get('/products', [ProductController::class, 'index'])->name('products.index');
//Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
//Route::get('/products/{product}/reviews', [ProductController::class, 'reviews'])->name('products.reviews');

// Vendeurs & Restaurants
Route::get('/vendors', [VendorController::class, 'index'])->name('vendors.index');
Route::get('/vendors/{vendor}', [VendorController::class, 'show'])->name('vendors.show');
Route::get('/vendors/{vendor}/dishes', [VendorController::class, 'dishes'])->name('vendors.dishes');

// Recherche
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/search/advanced', [SearchController::class, 'advanced'])->name('search.advanced');
Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');

// Géolocalisation
Route::get('/artisans/nearby', [LocationController::class, 'locateArtisans'])->name('artisans.nearby');
Route::get('/vendors/nearby', [LocationController::class, 'locateVendors'])->name('vendors.nearby');
Route::get('/map', [LocationController::class, 'map'])->name('map');
Route::get('/geolocate', [LocationController::class, 'geolocate'])->name('geolocate');

// Chatbot
Route::post('/chatbot/send', [ChatbotController::class, 'send'])->name('chatbot.send');
Route::get('/chatbot/history', [ChatbotController::class, 'history'])->name('chatbot.history');
Route::delete('/chatbot/clear', [ChatbotController::class, 'clear'])->name('chatbot.clear');

// Authentification (Breeze)
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    // Déconnexion
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/profile', [DashboardController::class, 'profile'])->name('dashboard.profile');
    Route::post('/dashboard/profile', [DashboardController::class, 'updateProfile']);
    Route::get('/dashboard/favorites', [DashboardController::class, 'favorites'])->name('dashboard.favorites');
    Route::get('/dashboard/orders', [DashboardController::class, 'orders'])->name('dashboard.orders');
    Route::get('/dashboard/messages', [DashboardController::class, 'messages'])->name('dashboard.messages');
    Route::get('/dashboard/notifications', [DashboardController::class, 'notifications'])->name('dashboard.notifications');
    Route::get('/dashboard/settings', [DashboardController::class, 'settings'])->name('dashboard.settings');

    // Favoris
    Route::post('/favorites/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/favorites/{type?}', [FavoriteController::class, 'index'])->name('favorites.index');

    // Panier (accessible sans authentification)
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/{item}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{item}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');
    Route::post('/cart/merge-session', [CartController::class, 'mergeSessionCart'])->name('cart.merge-session');
    Route::get('/cart/check/{productId}', [CartController::class, 'checkProduct'])->name('cart.check');
    Route::patch('/cart/update/{item}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{item}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    // Paiement & Checkout (nécessite authentification)
    Route::prefix('checkout')->name('checkout.')->controller(CheckoutController::class)->group(function () {

        Route::get('/', 'index')->name('index');
        Route::post('/process', 'process')->name('process');           // ← celle-ci manquait
        Route::get('/payment/{order}', 'payment')->name('payment');
        Route::get('/success/{order}', 'success')->name('success');
        Route::get('/cancel', 'cancel')->name('cancel');

        // AJAX
        Route::post('/calculate-delivery', 'calculateDelivery')->name('calculate-delivery');
    });
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])
        ->name('checkout.success');
    // Groupe des routes authentifiées (ajoute ceci si ce n'est pas déjà dans un groupe auth)
    Route::middleware('auth')->group(function () {

        // Dashboard client (si tu en as un)
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // === ROUTES POUR LES COMMANDES DU CLIENT ===
        Route::prefix('orders')->name('orders.')->group(function () {
            // Liste des commandes de l'utilisateur connecté
            Route::get('/', [OrderController::class, 'index'])->name('index');

            // Détails d'une commande spécifique
            Route::get('/{order}', [OrderController::class, 'show'])->name('show');

            // Optionnel : suivi de commande (tracking)
            Route::get('/track', [OrderController::class, 'tracking'])->name('tracking');
        });
    });
    Route::get('/orders/track', [OrderController::class, 'tracking'])
        ->name('orders.tracking');
    // FedaPay Webhook Callback (webhook public, pas besoin d'authentification)
    Route::post('/fedapay/callback', [CheckoutController::class, 'fedapayCallback'])->name('fedapay.callback');

    // Devis
    Route::get('/quotes', [QuoteController::class, 'index'])->name('quotes.index');
    Route::post('/quotes', [QuoteController::class, 'store'])->name('quotes.store');
    Route::get('/quotes/{quote}', [QuoteController::class, 'show'])->name('quotes.show');
    Route::put('/quotes/{quote}', [QuoteController::class, 'update'])->name('quotes.update');
    Route::post('/quotes/{quote}/accept', [QuoteController::class, 'accept'])->name('quotes.accept');
    Route::post('/quotes/{quote}/reject', [QuoteController::class, 'reject'])->name('quotes.reject');

    // Avis
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Messages
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user}', [MessageController::class, 'send'])->name('messages.send');
    Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
    Route::post('/messages/mark-all-read', [MessageController::class, 'markAllRead'])->name('messages.markAllRead');
    Route::delete('/messages/clear-all', [MessageController::class, 'clearAll'])->name('messages.clearAll');

    // Contact (utilisateurs connectés)
    Route::post('/contact/artisan/{artisan}', [ContactController::class, 'contactArtisan'])->name('contact.artisan');
    Route::post('/contact/vendor/{vendor}', [ContactController::class, 'contactVendor'])->name('contact.vendor');

    // Routes pour artisans

    Route::get('/dashboard/artisan', [DashboardController::class, 'artisan'])->name('dashboard.artisan');
    Route::get('/dashboard/artisan/products', [DashboardController::class, 'artisanProducts'])->name('dashboard.artisan.products');
    Route::get('/dashboard/artisan/orders', [DashboardController::class, 'artisanOrders'])->name('dashboard.artisan.orders');
    Route::get('/dashboard/artisan/analytics', [DashboardController::class, 'artisanAnalytics'])->name('dashboard.artisan.analytics');
    Route::get('/dashboard/artisan/reviews', [DashboardController::class, 'artisanReviews'])->name('dashboard.artisan.reviews');
});

// Dans toutes les routes nécessitant connexion
Route::middleware(['auth'])->group(function () {
    // Devis
    Route::post('/quotes', [QuoteController::class, 'store'])->name('quotes.store');

    // Favoris
    Route::post('/favorites', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{favorite}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');

    // Avis/Reviews
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // Panier
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
});

// Artisans CRUD
Route::get('/artisans', [ArtisanController::class, 'index'])->name('artisans.index');
Route::get('/artisans/create', [ArtisanController::class, 'create'])->name('artisans.create');
Route::post('/artisans', [ArtisanController::class, 'store'])->name('artisans.store');
Route::get('/artisans/{artisan}', [ArtisanController::class, 'show'])->name('artisans.show');
Route::get('/artisans/{artisan}/edit', [ArtisanController::class, 'edit'])->name('artisans.edit');
Route::put('/artisans/{artisan}', [ArtisanController::class, 'update'])->name('artisans.update');
Route::delete('/artisans/{artisan}', [ArtisanController::class, 'destroy'])->name('artisans.destroy');
// Products CRUD
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
Route::get('products/index', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Routes pour vendeurs
Route::get('/vendors/create', [VendorController::class, 'create'])->name('vendors.create');
Route::post('/vendors', [VendorController::class, 'store'])->name('vendors.store');
Route::get('/vendors/{vendor}/edit', [VendorController::class, 'edit'])->name('vendors.edit');
Route::put('/vendors/{vendor}', [VendorController::class, 'update'])->name('vendors.update');
Route::delete('/vendors/{vendor}', [VendorController::class, 'destroy'])->name('vendors.destroy');

Route::get('/dashboard/vendor', [DashboardController::class, 'vendor'])->name('dashboard.vendor');

// Routes pour administrateurs
Route::get('/admin', [DashboardController::class, 'admin'])->name('admin.dashboard');
Route::get('/admin/users', [DashboardController::class, 'adminUsers'])->name('admin.users');
Route::get('/admin/artisans', [DashboardController::class, 'adminArtisans'])->name('admin.artisans');
Route::get('/admin/products', [DashboardController::class, 'adminProducts'])->name('admin.products');
Route::get('/admin/orders', [DashboardController::class, 'adminOrders'])->name('admin.orders');
Route::get('/admin/reviews', [DashboardController::class, 'adminReviews'])->name('admin.reviews');
Route::get('/admin/analytics', [DashboardController::class, 'adminAnalytics'])->name('admin.analytics');

Route::post('/admin/artisans/{artisan}/verify', [ArtisanController::class, 'verify'])->name('admin.artisans.verify');
Route::post('/admin/products/{product}/feature', [ProductController::class, 'feature'])->name('admin.products.feature');
Route::post('/admin/reviews/{review}/approve', [ReviewController::class, 'approve'])->name('admin.reviews.approve');


// Routes API pour AJAX
Route::prefix('api')->group(function () {
    Route::get('/artisans/map', [ArtisanController::class, 'mapData'])->name('api.artisans.map');
    Route::get('/vendors/map', [VendorController::class, 'mapData'])->name('api.vendors.map');
    Route::get('/products/featured', [ProductController::class, 'featured'])->name('api.products.featured');
    Route::get('/dishes/featured', [GastronomieController::class, 'featured'])->name('api.dishes.featured');
    Route::get('/search/autocomplete', [SearchController::class, 'autocomplete'])->name('api.search.autocomplete');

    // Audio pronunciation
    Route::post('/audio/generate', [ProductController::class, 'generateAudio'])->name('api.audio.generate');

    // IA Services
    Route::post('/ai/description', [ProductController::class, 'generateDescription'])->name('api.ai.description');
    Route::post('/ai/translate', [ProductController::class, 'translate'])->name('api.ai.translate');
});

Route::get('/artisan/vue', [ArtisanController::class, 'index'])->name('artisans.vue');
Route::get('/artisans/create', [ArtisanController::class, 'create'])->name('artisans.create');

// Route pour le chatbot
Route::post('/chatbot/send', [ChatbotController::class, 'send'])
    ->name('chatbot.send');
// Routes de fallback
Route::fallback(function () {
    return redirect()->route('home');
});
// Routes pour les profils artisans
Route::prefix('artisan')->name('artisan.')->group(function () {
    // Profil
    Route::get('profile/{id}', [ArtisanProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/{id}/edit', [ArtisanProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile/{id}', [ArtisanProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile/{id}', [ArtisanProfileController::class, 'destroy'])->name('profile.destroy');

    // Mot de passe
    Route::post('profile/change-password', [ArtisanProfileController::class, 'changePassword'])->name('profile.change-password');

    // Produits
    Route::get('profile/{id}/products', [ArtisanProfileController::class, 'products'])->name('products.index');

    // Avis
    Route::get('profile/{id}/reviews', [ArtisanProfileController::class, 'reviews'])->name('reviews.index');

    // Activation/désactivation
    Route::put('profile/{id}/toggle-status', [ArtisanProfileController::class, 'toggleStatus'])->name('profile.toggle-status');
});
// Dans web.php - ajouter cette route
Route::get('/cart/deposit', [CartController::class, 'getDepositAmount'])->name('cart.deposit');
// Routes pour la navbar
Route::get('/search', 'SearchController@index')->name('search');
Route::get('/search/ajax', 'SearchController@ajax')->name('search.ajax');
Route::post('/notifications/mark-all-read', 'NotificationController@markAllRead')->name('notifications.markAllRead');
Route::post('/notifications/mark-as-read', 'NotificationController@markAsRead')->name('notifications.markAsRead');
Route::post('/user/online', 'UserController@updateOnlineStatus')->name('user.online');

// routes/web.php

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/artisans/pending', [ArtisanController::class, 'pendingList'])->name('artisans.pending');
    Route::post('/artisans/{artisan}/approve', [ArtisanController::class, 'approve'])->name('artisans.approve');
    Route::post('/artisans/{artisan}/reject', [ArtisanController::class, 'reject'])->name('artisans.reject');
});

// Routes protégées par authentification
Route::middleware(['auth'])->group(function () {
    // Dashboard principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Sections du dashboard
    Route::get('/dashboard/favorites', [ControllersDashboardController::class, 'favorites'])->name('dashboard.favorites');
    Route::get('/dashboard/requests', [DashboardController::class, 'requests'])->name('dashboard.requests');
    Route::get('/dashboard/orders', [DashboardController::class, 'orders'])->name('dashboard.orders');
    Route::get('/dashboard/messages', [DashboardController::class, 'messages'])->name('dashboard.messages');
    Route::get('/dashboard/profile', [DashboardController::class, 'profile'])->name('dashboard.profile');
    Route::post('/dashboard/profile/update', [DashboardController::class, 'updateProfile'])->name('dashboard.profile.update');

    // Dashboard artisan (si nécessaire)
    Route::get('/dashboard/artisan', [DashboardController::class, 'artisan'])->name('dashboard.artisan');
});

Route::middleware('auth')->group(function () {


    // Favoris de l'utilisateur connecté
    Route::get('/favorites', [FavoriteController::class, 'index'])
        ->name('favorites');

    // Optionnel : routes pour ajouter/supprimer un favori (AJAX ou POST)
    Route::post('/favorites', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{favorite}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
});

Route::middleware('auth')->group(function () {
    // Profil
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// routes/web.php

Route::get('/evenements', [CulturalEventController::class, 'index'])->name('events.index');
Route::get('/evenements/{event}', [CulturalEventController::class, 'show'])->name('events.show');

Route::middleware('auth')->group(function () {
    Route::post('/evenements/{event}/subscribe', [CulturalEventController::class, 'subscribe'])
        ->name('events.subscribe');
    Route::delete('/evenements/{event}/unsubscribe', [CulturalEventController::class, 'unsubscribe'])
        ->name('events.unsubscribe');
    Route::post('/evenements/preferences', [CulturalEventController::class, 'updatePreferences'])
        ->name('events.preferences');
});

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth'])
    ->group(function () {

        Route::get('/', fn() => redirect()->route('admin.dashboard'))->name('index');

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Routes protégées admin + super-admin
        Route::middleware('role:admin|super-admin')->group(function () {

            Route::get('/analytics', [DashboardController::class, 'analytics'])->name('analytics');

            Route::middleware('role:super-admin')->group(function () {
                Route::get('/super-dashboard', [DashboardController::class, 'superAdminDashboard'])->name('super-dashboard');
                Route::resource('roles', RoleController::class);

                Route::prefix('settings')->name('settings.')->group(function () {
                    Route::get('general', [SettingController::class, 'general'])->name('general');
                    Route::post('general', [SettingController::class, 'saveGeneral'])->name('general.save');
                });
            });

            Route::resource('artisans', ArtisanController::class);
            // ... toutes les autres ressources
        });

        Route::middleware('role:artisan')->group(function () {
            Route::get('/artisan/dashboard', [ArtisanController::class, 'dashboard'])->name('artisan.dashboard');
        });
    });
// Routes publiques
Route::resource('artisans', ArtisanController::class)->except(['destroy']);
require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
