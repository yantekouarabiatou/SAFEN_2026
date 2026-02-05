<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ArtisanController;
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
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CultureController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\LanguageController;

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

    // Paiement & Checkout (nécessite authentification)
    Route::middleware('auth')->group(function () {
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
        Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
        Route::get('/checkout/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');
    });

    // Devis
    Route::get('/quotes', [QuoteController::class, 'index'])->name('quotes.index');
    Route::post('/quotes', [QuoteController::class, 'store'])->name('quotes.store');
    Route::get('/quotes/{quote}', [QuoteController::class, 'show'])->name('quotes.show');
    Route::put('/quotes/{quote}', [QuoteController::class, 'update'])->name('quotes.update');
    Route::post('/quotes/{quote}/accept', [QuoteController::class, 'accept'])->name('quotes.accept');
    Route::post('/quotes/{quote}/reject', [QuoteController::class, 'reject'])->name('quotes.reject');

    // Avis
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
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
});

// Artisans CRUD
Route::get('/artisans', [ArtisanController::class, 'index'])->name('artisans.index');
Route::get('/artisans/create', [ArtisanController::class, 'create'])->name('artisans.create');
Route::post('/artisans', [ArtisanController::class, 'store'])->name('artisans.store');
Route::get('/artisans/{artisan}/edit', [ArtisanController::class, 'edit'])->name('artisans.edit');
Route::put('/artisans/{artisan}', [ArtisanController::class, 'update'])->name('artisans.update');
Route::delete('/artisans/{artisan}', [ArtisanController::class, 'destroy'])->name('artisans.destroy');
Route::get('/artisans/{artisan}', [ArtisanController::class, 'show'])->name('artisans.show');
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

// Route pour le chatbot
Route::post('/chatbot/send', [ChatbotController::class, 'send'])
    ->name('chatbot.send');
// Routes de fallback
Route::fallback(function () {
    return redirect()->route('home');
});

require __DIR__ . '/auth.php';
