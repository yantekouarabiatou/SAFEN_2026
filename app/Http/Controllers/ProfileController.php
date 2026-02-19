<?php

namespace App\Http\Controllers;

use App\Models\Artisan;
use App\Models\User;
use App\Models\Vendor; // Si tu as un modèle Vendor
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Afficher le profil de l'utilisateur connecté (selon son rôle)
     */
    public function show()
    {
        $user = Auth::user();

        if ($user->hasRole('artisan')) {
            $artisan = $user->artisan()->with(['products' => fn($q) => $q->where('is_active', true)->with('primaryImage'), 'reviews.user'])->firstOrFail();

            $artisan->products_count = $artisan->products->count();
            $artisan->orders_count   = $artisan->orders()->count();
            $artisan->reviews_count  = $artisan->reviews->count();
            $artisan->rating_avg     = $artisan->reviews->avg('rating') ?? 0;

            return view('profiles.show', compact('user', 'artisan'));
        }

        if ($user->hasRole('vendor')) {
            $vendor = $user->vendor; // À adapter selon ton modèle

            $vendor->dishes_count   = $vendor->dishes()->count() ?? 0;
            $vendor->orders_count   = $vendor->orders()->count() ?? 0;
            $vendor->rating_avg     = $vendor->reviews()->avg('rating') ?? 0;

            return view('profiles.show', compact('user', 'vendor'));
        }

        // Par défaut : profil client
        $orders_count   = $user->orders()->count();
        $favorites_count = $user->favorites()->count();

        return view('profiles.show', compact('user', 'orders_count', 'favorites_count'));
    }

    /**
     * Afficher le formulaire d'édition du profil
     */
    public function edit()
    {
        $user = Auth::user();

        if ($user->hasRole('artisan')) {
            $artisan = $user->artisan;
            return view('profile.edit', compact('user', 'artisan'));
        }

        if ($user->hasRole('vendor')) {
            $vendor = $user->vendor;
            return view('profile.edit', compact('user', 'vendor'));
        }

        // Profil client
        return view('profile.edit', compact('user'));
    }

    /**
     * Mettre à jour le profil (général + spécifique au rôle)
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validation de base (commune à tous)
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone'      => 'nullable|string|max:20',
            'avatar'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // Champs spécifiques selon rôle
            'city'       => 'nullable|string|max:100',
            'bio'        => 'nullable|string',
        ]);

        // Mise à jour des données communes
        $userData = [
            'name'  => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
        ];

        // Gestion de l'avatar
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $userData['avatar'] = $path;
        }

        $user->update($userData);

        // Mise à jour spécifique selon le rôle
        if ($user->hasRole('artisan')) {
            $artisan = $user->artisan;

            $artisan->update($request->validate([
                'business_name'     => 'required|string|max:255',
                'craft'             => 'required|string|max:255',
                'whatsapp'          => 'nullable|string|max:20',
                'languages_spoken'  => 'nullable|array',
                'years_experience'  => 'nullable|integer|min:0',
                'pricing_info'      => 'nullable|string|max:500',
                'neighborhood'      => 'nullable|string|max:100',
            ]));
        }

        if ($user->hasRole('vendor')) {
            $vendor = $user->vendor;

            $vendor->update($request->validate([
                'business_name' => 'required|string|max:255',
                'specialty'     => 'nullable|string|max:255',
                'delivery_zones'=> 'nullable|array',
                // etc.
            ]));
        }

        return redirect()->route('profile.show')
            ->with('success', 'Profil mis à jour avec succès.');
    }

    /**
     * Changer le mot de passe (commun à tous)
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Mot de passe modifié avec succès.');
    }

    /**
     * Supprimer son compte (avec confirmation)
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Mot de passe incorrect.']);
        }

        // Supprimer les données associées selon le rôle
        if ($user->hasRole('artisan')) {
            $user->artisan()->delete();
        }

        if ($user->hasRole('vendor')) {
            $user->vendor()->delete();
        }

        $user->delete();

        Auth::logout();

        return redirect()->route('home')
            ->with('success', 'Votre compte a été supprimé.');
    }
}
