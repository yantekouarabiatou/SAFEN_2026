<?php

namespace App\Http\Controllers;

use App\Models\Artisan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ArtisanProfileController extends Controller
{
    /**
     * Afficher le profil d'un artisan.
     */
    public function show($id)
    {
        $artisan = Artisan::with(['user', 'products' => function ($query) {
            $query->where('is_active', true)->with('primaryImage');
        }, 'reviews.user'])->findOrFail($id);

        // Compter les produits et commandes
        $artisan->products_count = $artisan->products->count();
        $artisan->orders_count = $artisan->orders->count();
        $artisan->reviews_count = $artisan->reviews->count();

        // Calculer la note moyenne
        $artisan->rating_avg = $artisan->reviews->avg('rating') ?? 0;

        return view('artisans.profile.show', compact('artisan'));
    }

    /**
     * Afficher le formulaire d'édition du profil artisan.
     */
    public function edit($id)
    {
        $artisan = Artisan::with('user')->findOrFail($id);

        // Vérifier que l'utilisateur connecté est bien l'artisan ou un admin
        if (Auth::id() != $artisan->user_id && !Auth::user()->hasRole('admin|super-admin')) {
            abort(403);
        }

        return view('artisans.profile.edit', compact('artisan'));
    }

    /**
     * Mettre à jour le profil artisan.
     */
    public function update(Request $request, $id)
    {
        $artisan = Artisan::with('user')->findOrFail($id);

        // Vérifier que l'utilisateur connecté est bien l'artisan ou un admin
        if (Auth::id() != $artisan->user_id && !Auth::user()->hasRole('admin|super-admin')) {
            abort(403);
        }

        // Validation des données
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'languages_spoken' => 'nullable|array',
            'languages_spoken.*' => 'string|max:50',
            'business_name' => 'required|string|max:255',
            'craft' => 'required|string|max:255',
            'years_experience' => 'nullable|integer|min:0|max:70',
            'pricing_info' => 'nullable|string|max:500',
            'bio' => 'nullable|string',
            'city' => 'required|string|max:100',
            'neighborhood' => 'nullable|string|max:100',
            'whatsapp' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'verified' => 'boolean',
            'featured' => 'boolean',
            'visible' => 'boolean',
        ]);

        // Mettre à jour l'utilisateur
        $userData = [
            'nom' => $validatedData['nom'],
            'prenom' => $validatedData['prenom'],
            'telephone' => $validatedData['telephone'] ?? null,
        ];

        // Gérer la photo de profil
        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo si elle existe
            if ($artisan->user->photo) {
                Storage::delete($artisan->user->photo);
            }
            
            // Stocker la nouvelle photo
            $path = $request->file('photo')->store('photos', 'public');
            $userData['photo'] = $path;
        }

        $artisan->user->update($userData);

        // Mettre à jour l'artisan
        $artisanData = [
            'business_name' => $validatedData['business_name'],
            'craft' => $validatedData['craft'],
            'years_experience' => $validatedData['years_experience'] ?? null,
            'pricing_info' => $validatedData['pricing_info'] ?? null,
            'bio' => $validatedData['bio'] ?? null,
            'city' => $validatedData['city'],
            'neighborhood' => $validatedData['neighborhood'] ?? null,
            'whatsapp' => $validatedData['whatsapp'] ?? null,
            'phone' => $validatedData['phone'] ?? null,
            'latitude' => $validatedData['latitude'] ?? null,
            'longitude' => $validatedData['longitude'] ?? null,
            'languages_spoken' => $validatedData['languages_spoken'] ?? null,
            'verified' => $validatedData['verified'] ?? false,
            'featured' => $validatedData['featured'] ?? false,
            'visible' => $validatedData['visible'] ?? true,
        ];

        $artisan->update($artisanData);

        return redirect()->route('artisan.profile.show', $artisan->id)
            ->with('success', 'Profil mis à jour avec succès.');
    }

    /**
     * Supprimer le profil artisan.
     */
    public function destroy($id)
    {
        $artisan = Artisan::findOrFail($id);

        // Vérifier que l'utilisateur connecté est bien l'artisan ou un admin
        if (Auth::id() != $artisan->user_id && !Auth::user()->hasRole('admin|super-admin')) {
            abort(403);
        }

        // Supprimer la photo de profil si elle existe
        if ($artisan->user->photo) {
            Storage::delete($artisan->user->photo);
        }

        // Supprimer l'utilisateur (ce qui supprimera aussi l'artisan grâce aux clés étrangères en cascade)
        $artisan->user->delete();

        return redirect()->route('home')
            ->with('success', 'Votre profil artisan a été supprimé avec succès.');
    }

    /**
     * Changer le mot de passe de l'artisan.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Vérifier le mot de passe actuel
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        // Changer le mot de passe
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Mot de passe mis à jour avec succès.');
    }

    /**
     * Afficher la liste des produits de l'artisan.
     */
    public function products($id)
    {
        $artisan = Artisan::with(['products' => function ($query) {
            $query->where('is_active', true)->with('primaryImage');
        }])->findOrFail($id);

        $products = $artisan->products;

        return view('artisans.products.index', compact('artisan', 'products'));
    }

    /**
     * Afficher la liste des avis sur l'artisan.
     */
    public function reviews($id)
    {
        $artisan = Artisan::with(['reviews.user'])->findOrFail($id);

        $reviews = $artisan->reviews()->paginate(10);

        return view('artisan.reviews.index', compact('artisan', 'reviews'));
    }

    /**
     * Activer/désactiver le profil artisan.
     */
    public function toggleStatus($id)
    {
        $artisan = Artisan::findOrFail($id);

        // Seuls les admins peuvent faire cela
        if (!Auth::user()->hasRole('admin|super-admin')) {
            abort(403);
        }

        $artisan->visible = !$artisan->visible;
        $artisan->save();

        $message = $artisan->visible ? 'Profil activé.' : 'Profil désactivé.';

        return back()->with('success', $message);
    }
}