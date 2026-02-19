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
        $supportedLocales = ['en', 'fr', 'fon'];
        $segment = $request->segment(1);

        // Si le segment est une langue supportée, on l'utilise
        if (in_array($segment, $supportedLocales)) {
            App::setLocale($segment);
            Session::put('locale', $segment);
        } else {
            // Sinon, on utilise la session ou la langue par défaut
            $locale = Session::get('locale', config('app.locale', 'fr'));
            if (!in_array($locale, $supportedLocales)) {
                $locale = 'fr';
            }
            App::setLocale($locale);
        }

        return $next($request);
    }
}
