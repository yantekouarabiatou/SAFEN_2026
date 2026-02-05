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
            Session::put('locale', $locale);
            App::setLocale($locale);
        }

        // Rediriger vers la page précédente ou l'accueil
        return redirect()->back();
    }
}
