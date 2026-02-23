<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Models\Artisan;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontReviewController extends Controller
{
    /**
     * Afficher le formulaire pour créer un avis
     */
    public function create(Request $request)
    {
        // Récupérer les paramètres pour pré-sélectionner un élément
        $type = $request->get('type');
        $id = $request->get('id');

        // Récupérer les données pour les sélecteurs
        $products = Product::where('status', 'active')->get();
        $artisans = Artisan::where('status', 'approved')->with('user')->get();
        $vendors = Vendor::all();

        return view('reviews.create', compact('products', 'artisans', 'vendors', 'type', 'id'));
    }

    /**
     * Enregistrer un nouvel avis
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reviewable_type' => 'required|string|in:App\Models\Artisan,App\Models\Product,App\Models\Vendor',
            'reviewable_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:2000',
            'anonymous' => 'nullable|boolean',
            'terms' => 'required|accepted'
        ]);

        // Vérifier si l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Veuillez vous connecter pour laisser un avis.')
                ->withInput();
        }

        // Vérifier si l'utilisateur a déjà posté un avis sur cet élément
        $existingReview = Review::where('user_id', Auth::id())
            ->where('reviewable_type', $validated['reviewable_type'])
            ->where('reviewable_id', $validated['reviewable_id'])
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Vous avez déjà posté un avis pour cet élément.')->withInput();
        }

        // Créer l'avis
        $review = new Review();
        $review->user_id = Auth::id();
        $review->reviewable_type = $validated['reviewable_type'];
        $review->reviewable_id = $validated['reviewable_id'];
        $review->rating = $validated['rating'];
        $review->comment = $validated['comment'];
        $review->status = 'pending'; // En attente de modération

        // Si l'utilisateur veut être anonyme, on pourrait stocker une information
        // mais garder l'ID utilisateur pour le traçage
        if ($request->has('anonymous')) {
            // Vous pouvez ajouter un champ 'is_anonymous' dans votre table reviews
            // $review->is_anonymous = true;
        }

        $review->save();

        // Rediriger avec un message de succès
        return redirect()->back()->with('success', 'Merci ! Votre avis a été soumis et sera publié après modération.');
    }

    /**
     * Afficher les avis pour un élément spécifique
     */
    public function index(Request $request)
    {
        $type = $request->get('type');
        $id = $request->get('id');

        if ($type && $id) {
            $model = 'App\\Models\\' . ucfirst($type);
            $reviews = Review::with('user')
                ->where('reviewable_type', $model)
                ->where('reviewable_id', $id)
                ->where('status', 'approved')
                ->latest()
                ->paginate(10);

            $item = $model::find($id);

            return view('reviews.index', compact('reviews', 'item', 'type'));
        }

        return redirect()->route('home');
    }

    /**
     * Afficher les détails d'un avis
     */
    public function show(Review $review)
    {
        if ($review->status !== 'approved' && Auth::id() !== $review->user_id && !Auth::user()?->isAdmin()) {
            abort(404);
        }

        return view('reviews.show', compact('review'));
    }

    /**
     * Mettre à jour un avis (pour l'utilisateur)
     */
    public function update(Request $request, Review $review)
    {
        // Vérifier que l'utilisateur est le propriétaire
        if (Auth::id() !== $review->user_id) {
            abort(403);
        }

        // Vérifier que l'avis est toujours modifiable (pas trop ancien, pas encore approuvé)
        if ($review->status !== 'pending') {
            return back()->with('error', 'Cet avis n\'est plus modifiable.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:2000',
        ]);

        $review->update($validated);

        return back()->with('success', 'Votre avis a été mis à jour.');
    }

    /**
     * Supprimer un avis
     */
    public function destroy(Review $review)
    {
        // Vérifier que l'utilisateur est le propriétaire ou un admin
        if (Auth::id() !== $review->user_id && !Auth::user()?->isAdmin()) {
            abort(403);
        }

        $review->delete();

        return back()->with('success', 'Avis supprimé avec succès.');
    }
}
