<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Auth\Events\Registered;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

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
            //'role' => $validated['role'],
            'city' => $validated['city'] ?? null,
            'language' => $validated['language'] ?? 'fr',
        ]);

        // Si c'est un artisan, créer automatiquement le profil artisan
        if ($user->role === 'artisan') {
            // Utilisez le namespace complet
            \App\Models\Artisan::create([
                'user_id' => $user->id,
                'business_name' => $user->name,
                'city' => $user->city ?? 'Cotonou',
                'verified' => false,
                'status' => 'pending', // Ajouté: statut en attente
                'visible' => false, // Ajouté: invisible par défaut
            ]);
        }

        event(new Registered($user));
        Auth::login($user);

        // Redirection selon le rôle
        if ($user->role === 'artisan') {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Bienvenue ! Complétez votre profil artisan pour commencer.');
        }

        return redirect()->route('home')
            ->with('success', 'Bienvenue sur TOTCHEMEGNON !');
    }
}
