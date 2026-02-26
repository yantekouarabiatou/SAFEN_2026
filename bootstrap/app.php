<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\SetLocale;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Groupe "web" : middlewares appliqués aux routes web
        $middleware->web(append: [
            // Vérification CSRF (important pour les formulaires POST)
            \App\Http\Middleware\VerifyCsrfToken::class,

            // Ton middleware pour gérer la langue (doit être après CSRF si tu utilises des sessions)
            SetLocale::class,
        ]);

        // Groupe "api" : middlewares pour les routes API
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        // Alias pour utiliser dans les routes ou controllers (ex: ->middleware('role:admin'))
        $middleware->alias([
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
            'role'     => \App\Http\Middleware\RoleMiddleware::class,
            'admin'    => \App\Http\Middleware\AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();