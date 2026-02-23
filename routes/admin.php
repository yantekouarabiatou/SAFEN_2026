<?php

use App\Http\Controllers\Admin\ArtisanController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DishController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\QuoteController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VendorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');

    // Artisans
    Route::resource('artisans', ArtisanController::class);
    Route::delete('artisans/photo/{photo}', [ArtisanController::class, 'deletePhoto'])->name('artisans.photo.delete');

    // Products
    Route::resource('products', ProductController::class);
    Route::delete('products/image/{image}', [ProductController::class, 'deleteImage'])->name('products.image.delete');
    Route::post('products/{product}/toggle-featured', [ProductController::class, 'toggleFeatured'])->name('products.toggle-featured');

    // Dishes
    Route::resource('dishes', DishController::class);
    Route::delete('dishes/image/{image}', [DishController::class, 'deleteImage'])->name('dishes.image.delete');

    // Vendors
    Route::resource('vendors', VendorController::class);

    // Orders
    Route::resource('orders', OrderController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
    Route::post('orders/{order}/validate', [OrderController::class, 'validateOrder'])->name('orders.validate');
    Route::post('orders/{order}/reject', [OrderController::class, 'rejectOrder'])->name('orders.reject');

    // Quotes
    Route::resource('quotes', QuoteController::class)->only(['index', 'show', 'destroy']);
    Route::patch('quotes/{quote}/status', [QuoteController::class, 'updateStatus'])->name('quotes.status');

    // Users
    Route::resource('users', UserController::class);

    // Reviews
    Route::resource('reviews', ReviewController::class)->only(['index', 'show', 'destroy']);
    Route::post('reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('reviews/{review}/reject', [ReviewController::class, 'reject'])->name('reviews.reject');

    // Messages
    Route::resource('messages', \App\Http\Controllers\Admin\MessageController::class)->only(['index', 'show', 'destroy']);

    // Contacts
    Route::resource('contacts', ContactController::class)->only(['index', 'show', 'destroy']);
    Route::patch('contacts/{contact}/status', [ContactController::class, 'updateStatus'])->name('contacts.status');
});
