<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reviewable_type' => 'required|string',
            'reviewable_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:2000',
        ]);

        // Vérifier que le type est valide
        if (!in_array($validated['reviewable_type'], ['App\Models\Artisan', 'App\Models\Product'])) {
            return back()->with('error', 'Type invalide.');
        }

        // Vérifier si l'utilisateur a déjà posté un avis sur cet élément
        $existingReview = Review::where('user_id', Auth::id())
            ->where('reviewable_type', $validated['reviewable_type'])
            ->where('reviewable_id', $validated['reviewable_id'])
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Vous avez déjà posté un avis pour cet élément.');
        }

        // Créer l'avis
        $review = new Review();
        $review->user_id = Auth::id();
        $review->reviewable_type = $validated['reviewable_type'];
        $review->reviewable_id = $validated['reviewable_id'];
        $review->rating = $validated['rating'];
        $review->comment = $validated['comment'];
        $review->save();

        return back()->with('success', 'Votre avis a été enregistré.');
    }
}
