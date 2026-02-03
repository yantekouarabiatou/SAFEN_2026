<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ArtisanController;
use App\Http\Controllers\GastronomieController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// Pages publiques
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/help', [HomeController::class, 'help'])->name('help');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/legal', [HomeController::class, 'legal'])->name('legal');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');
Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
Route::get('/culture', [HomeController::class, 'culture'])->name('culture.index');
// Artisans
Route::get('/artisans', [ArtisanController::class, 'index'])->name('artisans.index');
Route::get('/artisans/{artisan}', [ArtisanController::class, 'show'])->name('artisans.show');

// Gastronomie
Route::get('/gastronomie', [GastronomieController::class, 'index'])->name('gastronomie.index');
Route::get('/gastronomie/{dish}', [GastronomieController::class, 'show'])->name('gastronomie.show');

// Marketplace
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Chatbot
Route::post('/chatbot/send', [ChatbotController::class, 'send'])->name('chatbot.send');

// Recherche
Route::get('/search', [HomeController::class, 'search'])->name('search');

// Authentification (Breeze)
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
