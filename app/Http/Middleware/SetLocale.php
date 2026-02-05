<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Langues supportées
        $supportedLocales = ['en', 'fr', 'fon'];

        // Détecter la langue depuis l'URL (ex: /fr/artisans)
        $locale = $request->segment(1);

        if (in_array($locale, $supportedLocales)) {
            App::setLocale($locale);
            Session::put('locale', $locale);
        } else {
            // Si pas dans l'URL, utiliser la session ou la langue par défaut
            $locale = Session::get('locale', config('app.locale', 'fr'));
            if (!in_array($locale, $supportedLocales)) {
                $locale = 'fr'; // Langue par défaut
            }
            App::setLocale($locale);
        }

        return $next($request);
    }
}
