<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        // Récupère la locale depuis la session, le cookie, ou la configuration par défaut
        $locale = Session::get('locale', 
                   Cookie::get('locale', 
                   config('app.locale')));

        // Vérifie que c'est une locale supportée
        if (in_array($locale, ['fr', 'en', 'fon'])) {
            App::setLocale($locale);
        } else {
            // Si locale invalide, force le fallback
            App::setLocale(config('app.fallback_locale', 'fr'));
        }

        return $next($request);
    }
}
