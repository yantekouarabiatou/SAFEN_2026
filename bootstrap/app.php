<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Sanctum (si API + SPA)
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        // Middleware pour la langue
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);

        // Alias middleware - ESSAYEZ LES DEUX VERSIONS
        // Version 1: Middleware (singulier)
        $middleware->alias([
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
            'role' => \App\Http\Middleware\Role::class, // Votre middleware personnalisé
        ]);

        // Version 2: Middlewares (pluriel) - décommentez si version 1 ne fonctionne pas
        // $middleware->alias([
        //     'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
        //     'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
        //     'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
        //     'role_or_permission' => \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class,
        // ]);

        // Throttle personnalisé chatbot
        $middleware->throttleApi('chatbot', 30);
    })
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->command('events:notify')
            ->dailyAt('09:00')
            ->timezone('Africa/Porto-Novo')
            ->description('Envoyer les notifications pour les événements culturels à venir');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
