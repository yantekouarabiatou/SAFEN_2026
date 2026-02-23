<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    ArtisanController,
    ProductController,
    VendorController,
    CultureController,
    CulturalEventController,
    SearchController,
    LocationController,
    ChatbotController,
    ContactController,
    LanguageController,
    CartController,
    CheckoutController,
    OrderController,
    FavoriteController,
    FrontReviewController,
    GastronomieController,
    QuoteController,
    MessageController,
    NotificationController,
    ProfileController,
};
use App\Http\Controllers\Admin\ArtisanController as AdminArtisanController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VendorController as AdminVendorController;
use App\Http\Controllers\Auth\{
    RegisteredUserController,
    AuthenticatedSessionController
};

/*
|--------------------------------------------------------------------------
| ROUTES PUBLIQUES (sans authentification)
|--------------------------------------------------------------------------
*/

// ===== Langue =====
Route::get('/lang/{locale}', [LanguageController::class, 'switchLang'])->name('lang.switch');

// ===== Pages statiques =====
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/help', [HomeController::class, 'help'])->name('help');
Route::get('/legal', [HomeController::class, 'legal'])->name('legal');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');
Route::get('/terms', [HomeController::class, 'terms'])->name('terms');

// ===== Contact =====
Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// ===== Culture & Patrimoine =====
Route::prefix('culture')->name('culture.')->group(function () {
    Route::get('/', [CultureController::class, 'index'])->name('index');
    Route::get('/traditions', [CultureController::class, 'traditions'])->name('traditions');
    Route::get('/history', [CultureController::class, 'history'])->name('history');
    Route::get('/festivals', [CultureController::class, 'festivals'])->name('festivals');
    Route::get('/ethnies', [CultureController::class, 'ethnies'])->name('ethnies');
});

// ===== Événements culturels (public) =====
Route::get('/evenements', [CulturalEventController::class, 'index'])->name('events.index');
Route::get('/evenements/{event}', [CulturalEventController::class, 'show'])->name('events.show');

// ===== Gastronomie =====
Route::prefix('gastronomie')->name('gastronomie.')->group(function () {
    Route::get('/', [GastronomieController::class, 'index'])->name('index');
    Route::get('/{dish}', [GastronomieController::class, 'show'])->name('show');
});

// ===== Produits (public) =====
Route::get('/products/index', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}/reviews', [ProductController::class, 'reviews'])->name('products.reviews');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// ===== Artisans (public) =====
//Route::get('/artisans', [ArtisanController::class, 'index'])->name('artisans.index');
Route::get('/artisans/{artisan}/reviews', [ArtisanController::class, 'reviews'])->name('artisans.reviews');
Route::get('/artisans/{artisan}', [ArtisanController::class, 'show'])->name('artisans.show');
Route::get('/artisan/vue', [ArtisanController::class, 'index'])->name('artisans.vue');

// ===== Témoignages publics =====
Route::get('/artisan/{artisan}/testimonials', [HomeController::class, 'getArtisanTestimonials'])->name('artisan.testimonials');
Route::get('/product/{product}/testimonials', [HomeController::class, 'getProductTestimonials'])->name('product.testimonials');
Route::post('/testimonials/submit', [HomeController::class, 'submitTestimonial'])->name('testimonials.submit');

// ===== Vendeurs (public) =====
Route::prefix('vendors')->name('vendors.')->group(function () {
    Route::get('/', [VendorController::class, 'index'])->name('index');
    Route::get('/{vendor}', [VendorController::class, 'show'])->name('show');
    Route::get('/{vendor}/dishes', [VendorController::class, 'dishes'])->name('dishes');
});

// ===== Recherche =====
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/search/advanced', [SearchController::class, 'advanced'])->name('search.advanced');
Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');
Route::get('/search/ajax', [SearchController::class, 'ajax'])->name('search.ajax');

// ===== Géolocalisation =====
Route::get('/artisans/nearby', [LocationController::class, 'locateArtisans'])->name('artisans.nearby');
Route::get('/vendors/nearby', [LocationController::class, 'locateVendors'])->name('vendors.nearby');
Route::get('/map', [LocationController::class, 'map'])->name('map');
Route::get('/geolocate', [LocationController::class, 'geolocate'])->name('geolocate');

// ===== Chatbot =====
Route::post('/chatbot/send', [ChatbotController::class, 'send'])->name('chatbot.send');
Route::get('/chatbot/history', [ChatbotController::class, 'history'])->name('chatbot.history');
Route::delete('/chatbot/clear', [ChatbotController::class, 'clear'])->name('chatbot.clear');

// ===== Panier (accès public) =====
Route::prefix('cart')->name('cart.')->controller(CartController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/add', 'add')->name('add');
    Route::patch('/update/{item}', 'update')->name('update');
    Route::delete('/remove/{item}', 'remove')->name('remove');
    Route::delete('/clear', 'clear')->name('clear');
    Route::get('/count', 'getCartCount')->name('count');
    Route::post('/merge-session', 'mergeSessionCart')->name('merge-session');
    Route::get('/check/{productId}', 'checkProduct')->name('check');
    Route::get('/deposit', 'getDepositAmount')->name('deposit');
});

// ===== Webhook FedaPay (public) =====
Route::post('/fedapay/callback', [CheckoutController::class, 'fedapayCallback'])->name('fedapay.callback');

/*
|--------------------------------------------------------------------------
| AUTHENTIFICATION (GUEST)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

/*
|--------------------------------------------------------------------------
| ROUTES NÉCESSITANT UNE AUTHENTIFICATION
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // ===== Déconnexion =====
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // ===== Notifications =====
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    // ===== Profil =====
    Route::prefix('profile')->name('profile.')->controller(ProfileController::class)->group(function () {
        Route::get('/', 'show')->name('show');
        Route::get('/edit', 'edit')->name('edit');
        Route::put('/', 'update')->name('update');
        Route::put('/password', 'changePassword')->name('password');
        Route::delete('/', 'destroy')->name('destroy');
    });

    // ===== Favoris =====
    Route::prefix('favorites')->name('favorites.')->controller(FavoriteController::class)->group(function () {
        Route::get('/{type?}', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::post('/toggle', 'toggle')->name('toggle');
        Route::delete('/{favorite}', 'destroy')->name('destroy');
    });

    // ===== Commandes =====
    Route::prefix('orders')->name('orders.')->controller(OrderController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/track', 'tracking')->name('tracking');
        Route::get('/{order}', 'show')->name('show');
    });

    // ===== Checkout / Paiement =====
    Route::prefix('checkout')->name('checkout.')->controller(CheckoutController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/process', 'process')->name('process');
        Route::get('/payment/{order}', 'payment')->name('payment');
        Route::get('/success/{order}', 'success')->name('success');
        Route::get('/cancel', 'cancel')->name('cancel');
        Route::post('/calculate-delivery', 'calculateDelivery')->name('calculate-delivery');
    });

    // ===== Devis =====
    Route::prefix('quotes')->name('quotes.')->controller(QuoteController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/{quote}', 'show')->name('show');
        Route::put('/{quote}', 'update')->name('update');
        Route::post('/{quote}/accept', 'accept')->name('accept');
        Route::post('/{quote}/reject', 'reject')->name('reject');
    });

  // ===== Avis =====
    Route::prefix('reviews')->name('reviews.')->controller(FrontReviewController::class)->group(function () {
    Route::get('/create', 'create')->name('create');
    Route::post('/', 'store')->name('store');
    Route::get('/{type}/{id}', 'index')->name('index');
    Route::get('/{review}', 'show')->name('show');
    Route::put('/{review}', 'update')->name('update');
    Route::delete('/{review}', 'destroy')->name('destroy');
});

    // Messages
Route::resource('messages', App\Http\Controllers\Admin\MessageController::class);
Route::post('messages/{message}/mark-read', [App\Http\Controllers\Admin\MessageController::class, 'markAsRead'])->name('messages.mark-read');
Route::post('messages/{message}/mark-unread', [App\Http\Controllers\Admin\MessageController::class, 'markAsUnread'])->name('messages.mark-unread');
Route::post('messages/{message}/reply', [App\Http\Controllers\Admin\MessageController::class, 'reply'])->name('messages.reply');
Route::post('messages/bulk-action', [App\Http\Controllers\Admin\MessageController::class, 'bulkAction'])->name('messages.bulk');
Route::get('messages-conversations', [App\Http\Controllers\Admin\MessageController::class, 'conversations'])->name('messages.conversations');

    // ===== Contact (authentifié) =====
    Route::post('/contact/artisan/{artisan}', [ContactController::class, 'contactArtisan'])->name('contact.artisan');
    Route::post('/contact/vendor/{vendor}', [ContactController::class, 'contactVendor'])->name('contact.vendor');

    // ===== Événements culturels (inscription) =====
    Route::post('/evenements/{event}/subscribe', [CulturalEventController::class, 'subscribe'])->name('events.subscribe');
    Route::delete('/evenements/{event}/unsubscribe', [CulturalEventController::class, 'unsubscribe'])->name('events.unsubscribe');
    Route::post('/evenements/preferences', [CulturalEventController::class, 'updatePreferences'])->name('events.preferences');

    // ===== GESTION DES RESSOURCES (création, édition, suppression) =====
    Route::resource('artisans', ArtisanController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('products', ProductController::class)->only(['store', 'edit', 'update', 'destroy']);

    // ===== Routes Admin spéciales (approbation, vérification) =====
    Route::middleware('role_or_permission:admin|super-admin')->group(function () {
        Route::post('/admin/artisans/{artisan}/verify', [ArtisanController::class, 'verify'])->name('admin.artisans.verify');
        Route::post('/admin/products/{product}/feature', [ProductController::class, 'feature'])->name('admin.products.feature');
        Route::get('/admin/artisans/pending', [ArtisanController::class, 'pendingList'])->name('admin.artisans.pending');
        Route::post('/admin/artisans/{artisan}/approve', [ArtisanController::class, 'approve'])->name('admin.artisans.approve');
        Route::post('/admin/artisans/{artisan}/reject', [ArtisanController::class, 'reject'])->name('admin.artisans.reject');
    });

    // ===== Espace Client =====
    Route::prefix('client')->name('client.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Client\DashboardController::class, 'index'])->name('dashboard');

        // Commandes
        Route::resource('orders', OrderController::class)->only(['index', 'show']);
        Route::get('/orders/tracking', [OrderController::class, 'tracking'])->name('orders.tracking');
        Route::put('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

        // Devis
        Route::resource('quotes', QuoteController::class)->except(['destroy']);

        // Favoris
        Route::resource('favorites', FavoriteController::class)->only(['index', 'destroy']);
        Route::post('/favorites/toggle/{product}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

        // Messages
        // Route::resource('message', ContactController::class)->only(['index', 'show', 'store']);
        // Route::get('/messages/create/{artisan?}', [ContactController::class, 'create'])->name('contacts.create');
        // Route::post('/messages', [ContactController::class, 'store'])->name('contacts.store');
        // Route::get('/messages', [ContactController::class, 'index'])->name('.index');
    });

    // ===== Espace Vendeur =====
    Route::prefix('vendor')->name('vendor.')->group(function () {
        Route::post('/dishes/quick-store', [VendorController::class, 'quickStore'])->name('dishes.quick-store');
        Route::delete('/dishes/{dish}/detach', [VendorController::class, 'detach'])->name('dishes.detach');
    });
});

/*
|--------------------------------------------------------------------------
| ROUTES ADMIN (BACKOFFICE)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/analytics', [DashboardController::class, 'analytics'])->name('analytics');

    // Ressources
    Route::resource('artisans', AdminArtisanController::class);
    Route::resource('products', AdminProductController::class);
    Route::resource('vendors', AdminVendorController::class);
    Route::resource('users', UserController::class);
    Route::resource('quotes', QuoteController::class);
    Route::resource('events', CulturalEventController::class);

    // REVIEWS
    Route::controller(App\Http\Controllers\Admin\ReviewController::class)->prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{review}', 'show')->name('show');
        Route::post('/{review}/approve', 'approve')->name('approve');
        Route::post('/{review}/reject', 'reject')->name('reject');
        Route::delete('/{review}', 'destroy')->name('destroy');
        Route::post('/bulk-action', 'bulkAction')->name('bulk');
    });

    // MESSAGES - AJOUTER ICI
    Route::resource('messages', App\Http\Controllers\Admin\MessageController::class);
    Route::post('messages/{message}/mark-read', [App\Http\Controllers\Admin\MessageController::class, 'markAsRead'])->name('messages.mark-read');
    Route::post('messages/{message}/mark-unread', [App\Http\Controllers\Admin\MessageController::class, 'markAsUnread'])->name('messages.mark-unread');
    Route::post('messages/{message}/reply', [App\Http\Controllers\Admin\MessageController::class, 'reply'])->name('messages.reply');
    Route::post('messages/bulk-action', [App\Http\Controllers\Admin\MessageController::class, 'bulkAction'])->name('messages.bulk');
    Route::get('messages-conversations', [App\Http\Controllers\Admin\MessageController::class, 'conversations'])->name('messages.conversations');

    Route::resource('contacts', App\Http\Controllers\Admin\ContactController::class);

    // Plats (vendeurs)
    Route::resource('dishes', App\Http\Controllers\Admin\DishController::class);
    Route::post('/dishes/quick-store', [AdminVendorController::class, 'quickStore'])->name('vendor.dishes.quick-store');
    Route::delete('/dishes/{dish}/detach', [AdminVendorController::class, 'detach'])->name('vendor.dishes.detach');

    // Commandes admin
    Route::resource('orders', App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show', 'edit', 'update']);
    Route::post('orders/{order}/validate', [App\Http\Controllers\Admin\OrderController::class, 'validateOrder'])->name('orders.validate');
    Route::post('orders/{order}/reject', [App\Http\Controllers\Admin\OrderController::class, 'rejectOrder'])->name('orders.reject');
});
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    // Gestion des devis (admin)
    Route::resource('quotes', QuoteController::class)->except(['create', 'store']);
    Route::post('quotes/{quote}/respond', [QuoteController::class, 'respond'])->name('quotes.respond');
    Route::post('quotes/{quote}/update-status', [QuoteController::class, 'updateStatus'])->name('quotes.update-status');
});
/*
|--------------------------------------------------------------------------
| ROUTES API (AJAX)
|--------------------------------------------------------------------------
*/
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/artisans/map', [ArtisanController::class, 'mapData'])->name('artisans.map');
    Route::get('/vendors/map', [VendorController::class, 'mapData'])->name('vendors.map');
    Route::get('/products/featured', [ProductController::class, 'featured'])->name('products.featured');
    //Route::get('/dishes/featured', [AppHttpControllersGastronomieController::class, 'featured'])->name('dishes.featured');
    Route::get('/search/autocomplete', [SearchController::class, 'autocomplete'])->name('search.autocomplete');
    Route::post('/audio/generate', [ProductController::class, 'generateAudio'])->name('audio.generate');
    Route::post('/ai/description', [ProductController::class, 'generateDescription'])->name('ai.description');
    Route::post('/ai/translate', [ProductController::class, 'translate'])->name('ai.translate');
});

Route::get('/admin/messages-test', function() {
    return view('admin.messages.test');
})->middleware('auth')->name('admin.messages.test');

/*
|--------------------------------------------------------------------------
| DEBUG (à supprimer en production)
|--------------------------------------------------------------------------
*/
Route::get('/debug-role', function () {
    $user = auth()->user();
    if (!$user) return 'Non connecté';
    return [
        'email'         => $user->email,
        'roles'         => $user->getRoleNames(),
        'has_admin'     => $user->hasRole('admin'),
        'has_super_admin' => $user->hasRole('super-admin'),
        'has_any_admin' => $user->hasAnyRole(['admin', 'super-admin']),
    ];
})->middleware('auth');

/*
|--------------------------------------------------------------------------
| FALLBACK
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return redirect()->route('home');
});

require __DIR__ . '/auth.php';
