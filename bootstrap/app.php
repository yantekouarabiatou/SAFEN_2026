<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
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

        // Alias middleware personnalisés
        $middleware->alias([
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
        ]);

        // Throttle personnalisé chatbot (si tu veux)
        $middleware->throttleApi('chatbot', 30);
        // => 30 requêtes/minute (modifiable)

    })

    ->withSchedule(function (Schedule $schedule): void {

        // Notifications des événements culturels
        $schedule->command('events:notify')
            ->dailyAt('09:00')
            ->timezone('Africa/Porto-Novo')
            ->description('Envoyer les notifications pour les événements culturels à venir');

        // Optionnel : Exécuter aussi à 18h pour les événements de dernière minute
        // $schedule->command('events:notify')
        //     ->dailyAt('18:00')
        //     ->timezone('Africa/Porto-Novo');

    })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })

    ->create();
