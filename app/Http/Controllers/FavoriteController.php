<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = auth()->user()
            ->favorites()
            ->with('favoritable') // Charger le produit/artisan/etc.
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('favorites.index', compact('favorites'));
    }

    // Optionnel : ajouter un produit aux favoris
    public function store(Request $request)
    {
        $request->validate([
            'favoritable_id' => 'required',
            'favoritable_type' => 'required|in:App\\Models\\Product', // adapte selon tes modèles
        ]);

        $favorite = auth()->user()->favorites()->create([
            'favoritable_id' => $request->favoritable_id,
            'favoritable_type' => $request->favoritable_type,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Produit ajouté aux favoris',
            'count' => auth()->user()->favorites()->count(),
        ]);
    }

    // Optionnel : supprimer un favori
    public function destroy(Favorite $favorite)
    {
        // Vérifier que c'est bien le favori de l'utilisateur
        if ($favorite->user_id !== auth()->id()) {
            abort(403);
        }

        $favorite->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produit retiré des favoris',
            'count' => auth()->user()->favorites()->count(),
        ]);
    }
}
