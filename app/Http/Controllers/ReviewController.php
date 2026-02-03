<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Models\Artisan;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reviewable_type' => 'required|in:product,artisan,vendor',
            'reviewable_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'image|max:2048',
        ]);

        // Vérifier si l'utilisateur peut laisser un avis
        if ($validated['reviewable_type'] === 'product') {
            $product = Product::findOrFail($validated['reviewable_id']);

            // Vérifier si l'utilisateur a acheté ce produit
            $hasPurchased = Auth::user()->orders()
                ->whereHas('items', function($query) use ($product) {
                    $query->where('product_id', $product->id);
                })
                ->where('status', '!=', 'cancelled')
                ->exists();

            if (!$hasPurchased) {
                return redirect()->back()
                    ->with('error', 'Vous devez avoir acheté ce produit pour laisser un avis.');
            }
        }

        // Vérifier si l'utilisateur a déjà laissé un avis
        $existingReview = Review::where('user_id', Auth::id())
            ->where('reviewable_type', $validated['reviewable_type'])
            ->where('reviewable_id', $validated['reviewable_id'])
            ->first();

        if ($existingReview) {
            return redirect()->back()
                ->with('error', 'Vous avez déjà laissé un avis pour cet élément.');
        }

        // Gérer l'upload des photos
        $photoUrls = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('reviews/photos', 'public');
                $photoUrls[] = $path;
            }
        }

        // Créer l'avis
        $review = Review::create([
            'user_id' => Auth::id(),
            'reviewable_type' => 'App\\Models\\' . ucfirst($validated['reviewable_type']),
            'reviewable_id' => $validated['reviewable_id'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'photos' => $photoUrls,
            'verified_purchase' => true,
            'approved' => true,
        ]);

        // Mettre à jour la note moyenne
        $this->updateAverageRating($validated['reviewable_type'], $validated['reviewable_id']);

        return redirect()->back()
            ->with('success', 'Votre avis a été publié avec succès !');
    }

    public function update(Request $request, Review $review)
    {
        // Vérifier que l'avis appartient à l'utilisateur
        if ($review->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'image|max:2048',
        ]);

        // Gérer l'upload des nouvelles photos
        $photoUrls = $review->photos ?? [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('reviews/photos', 'public');
                $photoUrls[] = $path;
            }
        }

        // Mettre à jour l'avis
        $review->update([
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'photos' => $photoUrls,
        ]);

        // Mettre à jour la note moyenne
        $this->updateAverageRating($review->reviewable_type, $review->reviewable_id);

        return redirect()->back()
            ->with('success', 'Votre avis a été mis à jour avec succès !');
    }

    public function destroy(Review $review)
    {
        // Vérifier que l'avis appartient à l'utilisateur ou que l'utilisateur est admin
        if ($review->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $reviewableType = $review->reviewable_type;
        $reviewableId = $review->reviewable_id;

        // Supprimer l'avis
        $review->delete();

        // Mettre à jour la note moyenne
        $this->updateAverageRating($reviewableType, $reviewableId);

        return redirect()->back()
            ->with('success', 'Votre avis a été supprimé avec succès.');
    }

    public function approve(Review $review)
    {
        // Seul l'admin peut approuver
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $review->update(['approved' => true]);

        return redirect()->back()
            ->with('success', 'Avis approuvé avec succès.');
    }

    protected function updateAverageRating($reviewableType, $reviewableId)
    {
        $model = $reviewableType::find($reviewableId);

        if ($model) {
            $reviews = Review::where('reviewable_type', $reviewableType)
                ->where('reviewable_id', $reviewableId)
                ->where('approved', true);

            $average = $reviews->avg('rating');
            $count = $reviews->count();

            $model->update([
                'rating_avg' => round($average, 2),
                'rating_count' => $count,
            ]);
        }
    }

    public function index(Request $request)
    {
        $type = $request->get('type', 'all');
        $status = $request->get('status', 'all');

        $query = Review::with(['user', 'reviewable'])
            ->where('approved', false);

        // Filtres
        if ($type !== 'all') {
            $query->where('reviewable_type', 'App\\Models\\' . ucfirst($type));
        }

        if ($status === 'reported') {
            $query->whereHas('reports');
        }

        $reviews = $query->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.reviews.index', compact('reviews', 'type', 'status'));
    }

    public function report(Request $request, Review $review)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        // Créer un signalement
        $review->reports()->create([
            'user_id' => Auth::id(),
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->back()
            ->with('success', 'Merci pour votre signalement. Notre équipe va examiner ce contenu.');
    }
}
