<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switchLang($locale)
    {
        // Langues supportées
        $supportedLocales = ['en', 'fr', 'fon'];

        if (in_array($locale, $supportedLocales)) {
            // Sauvegarder la locale dans la session
            Session::put('locale', $locale);
            // Définir la locale pour l'application
            App::setLocale($locale);
        }

        // Rediriger vers la page précédente ou l'accueil
        return redirect()->back()->withCookie(cookie('locale', $locale, 60 * 24 * 365));
    }
}
