<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Vérifier si l'utilisateur est un admin/super-admin pour le rediriger vers l'admin
        if (Auth::user()->hasAnyRole(['super-admin', 'admin','artisan'])) {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        }

        // Pour les autres rôles (artisan, vendor, client), rediriger vers le dashboard général
        return redirect()->intended(route('home', absolute: false));

        // Alternative: rediriger vers la page d'accueil publique
        // return redirect()->intended(route('home', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
