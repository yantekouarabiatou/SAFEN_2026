<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    ArtisanController,
    GastronomieController,
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
    GastronomieController as ControllersGastronomieController,
    QuoteController,
    ReviewController,
    MessageController,
    NotificationController,
    ProfileController,
};
use App\Http\Controllers\Admin\DashboardController;
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
    Route::get('/', [ControllersGastronomieController::class, 'index'])->name('index');
    Route::get('/{dish}', [GastronomieController::class, 'show'])->name('show');
});

// ===== PRODUITS (PUBLIC) – PRIORITAIRE =====
Route::get('/products/index', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/{product}/reviews', [ProductController::class, 'reviews'])->name('products.reviews'); // si méthode existe

// ===== Artisans (public) =====
Route::get('/artisans', [ArtisanController::class, 'index'])->name('artisans.index');
Route::get('/artisans/{artisan}/reviews', [ArtisanController::class, 'reviews'])->name('artisans.reviews');
Route::get('/artisans/{artisan}', [ArtisanController::class, 'show'])->name('artisans.show');
Route::get('/artisan/vue', [ArtisanController::class, 'index'])->name('artisans.vue'); // alias

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
    Route::patch('cart/update/{item}', [CartController::class, 'update'])->name('cart.update.patch'); // ← nom différent    Route::delete('/{item}', 'remove')->name('remove');
    Route::delete('/', 'clear')->name('clear');
    Route::get('/count', 'getCartCount')->name('count');
    Route::post('/merge-session', 'mergeSessionCart')->name('merge-session');
    Route::get('/check/{productId}', 'checkProduct')->name('check');
    Route::patch('/update/{item}', 'update')->name('update');
    Route::delete('/remove/{item}', 'remove')->name('remove');
    Route::delete('/clear', 'clear')->name('clear');
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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| ROUTES NÉCESSITANT UNE AUTHENTIFICATION
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // ===== Déconnexion =====
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // ===== PROFIL UTILISATEUR (ProfileController - créé par Breeze) =====
    Route::prefix('profile')->name('profile.')->controller(ProfileController::class)->group(function () {
        Route::get('/', 'show')->name('show');
        Route::get('/edit', 'edit')->name('edit');
        Route::put('/', 'update')->name('update');
        Route::put('/password', 'changePassword')->name('password');
        Route::delete('/', 'destroy')->name('destroy');
    });

    // ===== FAVORIS =====
    Route::prefix('favorites')->name('favorites.')->controller(FavoriteController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{type?}', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::post('/toggle', 'toggle')->name('toggle');
        Route::delete('/{favorite}', 'destroy')->name('destroy');
    });

    // ===== COMMANDES =====
    Route::prefix('orders')->name('orders.')->controller(OrderController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{order}', 'show')->name('show');
        Route::get('/track', 'tracking')->name('tracking');
    });

    // ===== CHECKOUT / PAIEMENT =====
    Route::prefix('checkout')->name('checkout.')->controller(CheckoutController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/process', 'process')->name('process');
        Route::get('/payment/{order}', 'payment')->name('payment');
        Route::get('/success/{order}', 'success')->name('success');
        Route::get('/cancel', 'cancel')->name('cancel');
        Route::post('/calculate-delivery', 'calculateDelivery')->name('calculate-delivery');
    });
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

    // ===== DEVIS =====
    Route::prefix('quotes')->name('quotes.')->controller(QuoteController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/{quote}', 'show')->name('show');
        Route::put('/{quote}', 'update')->name('update');
        Route::post('/{quote}/accept', 'accept')->name('accept');
        Route::post('/{quote}/reject', 'reject')->name('reject');
    });

    // ===== AVIS =====
    Route::prefix('reviews')->name('reviews.')->controller(ReviewController::class)->group(function () {
        Route::post('/', 'store')->name('store');
        Route::put('/{review}', 'update')->name('update');
        Route::delete('/{review}', 'destroy')->name('destroy');
    });

    // ===== MESSAGES =====
    Route::prefix('messages')->name('messages.')->controller(MessageController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{user}', 'show')->name('show');
        Route::post('/{user}', 'send')->name('send');
        Route::delete('/{message}', 'destroy')->name('destroy');
        Route::post('/mark-all-read', 'markAllRead')->name('markAllRead');
        Route::delete('/clear-all', 'clearAll')->name('clearAll');
    });

    // ===== CONTACT (authentifié) =====
    Route::post('/contact/artisan/{artisan}', [ContactController::class, 'contactArtisan'])->name('contact.artisan');
    Route::post('/contact/vendor/{vendor}', [ContactController::class, 'contactVendor'])->name('contact.vendor');

    // ===== ÉVÉNEMENTS CULTURELS (inscription) =====
    Route::post('/evenements/{event}/subscribe', [CulturalEventController::class, 'subscribe'])->name('events.subscribe');
    Route::delete('/evenements/{event}/unsubscribe', [CulturalEventController::class, 'unsubscribe'])->name('events.unsubscribe');
    Route::post('/evenements/preferences', [CulturalEventController::class, 'updatePreferences'])->name('events.preferences');

    // ===== GESTION DES RESSOURCES (création, édition, suppression) =====
    Route::resource('artisans', ArtisanController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('products', ProductController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
    // ===== ROUTES ADMIN (approuver, rejeter, etc.) =====
    Route::middleware(['auth', 'role_or_permission:admin|super-admin'])->group(function () {
        Route::post('/admin/artisans/{artisan}/verify', [ArtisanController::class, 'verify'])->name('admin.artisans.verify');
        Route::post('/admin/products/{product}/feature', [ProductController::class, 'feature'])->name('admin.products.feature');
        Route::post('/admin/reviews/{review}/approve', [ReviewController::class, 'approve'])->name('admin.reviews.approve');
        Route::get('/admin/artisans/pending', [ArtisanController::class, 'pendingList'])->name('admin.artisans.pending');
        Route::post('/admin/artisans/{artisan}/approve', [ArtisanController::class, 'approve'])->name('admin.artisans.approve');
        Route::post('/admin/artisans/{artisan}/reject', [ArtisanController::class, 'reject'])->name('admin.artisans.reject');
    });
    Route::middleware(['auth'])->prefix('client')->name('client.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Client\DashboardController::class, 'index'])->name('dashboard');

        // Commandes
        Route::resource('orders', OrderController::class)->only(['index', 'show']);
        Route::get('/orders/tracking', [OrderController::class, 'tracking'])->name('orders.tracking');
        Route::resource('orders', OrderController::class)->only(['index', 'show']);
        Route::put('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
        // Devis
        Route::resource('quotes', QuoteController::class)->except(['destroy']);

        // Favoris
        Route::resource('favorites', FavoriteController::class)->only(['index', 'destroy']);
        Route::post('/favorites/toggle/{product}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

        // Messages / contacts
        Route::resource('messages', MessageController::class)->only(['index', 'show', 'store']);
        Route::get('/contacts/create/{artisan?}', [ContactController::class, 'create'])->name('contacts.create');
        Route::post('/contacts', [ContactController::class, 'store'])->name('contacts.store');
        Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');

        // Profil
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    });
    /* ===== COMMENTÉ : Contrôleurs manquants =====
    // DashboardController n'existe pas
    // Route::prefix('dashboard')->name('dashboard.')->controller(DashboardController::class)->group(function () {...});

    // ArtisanProfileController n'existe pas
    // Route::prefix('artisan')->name('artisan.')->controller(ArtisanProfileController::class)->group(function () {...});

    // NotificationController n'existe pas
     Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

    // UserController n'existe pas
    // Route::post('/user/online', [UserController::class, 'updateOnlineStatus'])->name('user.online');
    */
});

/*
|--------------------------------------------------------------------------
| ROUTES ADMIN (BACKOFFICE) – nécessite AdminDashboardController existant
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth']) // ← seulement auth, pas de can:...
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Ressources
        Route::resource('artisans', ArtisanController::class);
        Route::resource('products', ProductController::class);
        Route::resource('vendors', VendorController::class)->except(['index', 'show']);
        //Route::resource('dishes', DishController::class); // si vous avez un contrôleur
        //Route::resource('users', UserController::class);
        Route::resource('orders', OrderController::class);
        Route::resource('quotes', QuoteController::class);
        Route::resource('events', CulturalEventController::class);
        Route::resource('reviews', ReviewController::class);
        Route::resource('contacts', ContactController::class);

        // Analytics
        Route::get('/analytics', [DashboardController::class, 'analytics'])->name('analytics');

        // Paramètres (pour super-admin)
        // Route::prefix('settings')->name('settings.')->group(function () {
        //     Route::get('/general', [SettingsController::class, 'general'])->name('general');
        //     Route::get('/payment', [SettingsController::class, 'payment'])->name('payment');
        //     Route::get('/notifications', [SettingsController::class, 'notifications'])->name('notifications');
        // });

        // Rôles & permissions (si contrôleur existe)
        //Route::resource('roles', RoleController::class);

        Route::resource('orders', App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show', 'edit', 'update']);
        Route::post('orders/{order}/validate', [App\Http\Controllers\Admin\OrderController::class, 'validateOrder'])->name('orders.validate');
        Route::post('orders/{order}/reject', [App\Http\Controllers\Admin\OrderController::class, 'rejectOrder'])->name('orders.reject');
    });
Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
    //Route::resource('dishes', DishController::class)->only('create','store','show','update');
    Route::resource('vendors', AdminVendorController::class);
    Route::post('/dishes/quick-store', [AdminVendorController::class, 'quickStore'])->name('vendor.dishes.quick-store');

    // Détacher un plat du vendeur
    Route::delete('/dishes/{dish}/detach', [AdminVendorController::class, 'detach'])->name('vendor.dishes.detach');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
    //Route::resource('dishes', DishController::class)->only('create','store','show','update');
    Route::resource('vendors', AdminVendorController::class);
    Route::post('/dishes/quick-store', [AdminVendorController::class, 'quickStore'])->name('vendor.dishes.quick-store');

    // Détacher un plat du vendeur
    Route::delete('/dishes/{dish}/detach', [AdminVendorController::class, 'detach'])->name('vendor.dishes.detach');
});
Route::middleware(['auth'])->prefix('vendor')->name('vendor.')->group(function () {
    // ...
    Route::post('/dishes/quick-store', [AdminVendorController::class, 'quickStore'])->name('dishes.quick-store');
    Route::delete('/dishes/{dish}/detach', [AdminVendorController::class, 'detach'])->name('dishes.detach');
});
/*
|--------------------------------------------------------------------------
| ROUTES API (AJAX, services)
|--------------------------------------------------------------------------
*/
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/artisans/map', [ArtisanController::class, 'mapData'])->name('artisans.map');
    Route::get('/vendors/map', [VendorController::class, 'mapData'])->name('vendors.map');
    Route::get('/products/featured', [ProductController::class, 'featured'])->name('products.featured');
    Route::get('/dishes/featured', [GastronomieController::class, 'featured'])->name('dishes.featured');
    Route::get('/search/autocomplete', [SearchController::class, 'autocomplete'])->name('search.autocomplete');
    Route::post('/audio/generate', [ProductController::class, 'generateAudio'])->name('audio.generate');
    Route::post('/ai/description', [ProductController::class, 'generateDescription'])->name('ai.description');
    Route::post('/ai/translate', [ProductController::class, 'translate'])->name('ai.translate');
});

/*
|--------------------------------------------------------------------------
| FALLBACK
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return redirect()->route('home');
});

Route::get('/debug-role', function () {
    $user = auth()->user();
    if (!$user) return 'Non connecté';
    return [
        'email' => $user->email,
        'roles' => $user->getRoleNames(),
        'has_admin' => $user->hasRole('admin'),
        'has_super_admin' => $user->hasRole('super-admin'),
        'has_any_admin' => $user->hasAnyRole(['admin', 'super-admin']),
    ];
})->middleware('auth');
// Inclusion des fichiers supplémentaires – à décommenter une fois les contrôleurs créés
require __DIR__ . '/auth.php';
 //require __DIR__ . '/admin.php';
