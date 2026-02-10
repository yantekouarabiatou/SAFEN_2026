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
        // Sanctum pour les requêtes API/SPA
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        // Middleware pour la langue (SetLocale) – bien configuré
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);

        // Alias pour les middlewares de Spatie Permission (CORRIGÉ : namespace au singulier)
        $middleware->alias([
            'role'               => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission'         => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);

        // Throttle personnalisé chatbot (optionnel)
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
