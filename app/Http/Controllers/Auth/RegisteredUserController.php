<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Artisan;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:client,artisan'],
            'city' => ['nullable', 'string', 'max:100'],
            'language' => ['nullable', 'in:fr,en,fon'],
            'terms' => ['accepted'],
        ]);

        // Créer l'utilisateur
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'city' => $validated['city'] ?? null,
            'language' => $validated['language'] ?? 'fr',
        ]);

        // Si c'est un artisan, créer automatiquement le profil artisan
        if ($user->role === 'artisan') {
            Artisan::create([
                'user_id' => $user->id,
                'business_name' => $user->name, // Par défaut, même nom
                'city' => $user->city ?? 'Cotonou',
                'verified' => false, // À vérifier par admin
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        // Redirection selon le rôle
        if ($user->role === 'artisan') {
            return redirect()->route('dashboard.artisan')
                ->with('success', 'Bienvenue ! Complétez votre profil pour commencer.');
        }

        return redirect()->route('dashboard')
            ->with('success', 'Bienvenue sur SAFEN !');
    }
}
