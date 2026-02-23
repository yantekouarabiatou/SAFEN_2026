<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        // Statistiques
        $stats = [
            'total' => Review::count(),
            'pending' => Review::where('status', 'pending')->count(),
            'approved' => Review::where('status', 'approved')->count(),
            'rejected' => Review::where('status', 'rejected')->count(),
            'average_rating' => Review::where('status', 'approved')->avg('rating') ?? 0
        ];

        // Requête de base
        $query = Review::with(['user', 'reviewable']);

        // Filtre par recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('comment', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Filtre par type
        if ($request->filled('type')) {
            $type = $request->type;
            $query->where('reviewable_type', 'App\\Models\\' . ucfirst($type));
        }

        // Filtre par note
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Filtre par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Pagination avec 10 éléments par page
        $reviews = $query->latest()->paginate(10);

        return view('admin.reviews.index', compact('reviews', 'stats'));
    }

    public function show(Review $review)
    {
        $review->load(['user', 'reviewable']);
        return view('admin.reviews.show', compact('review'));
    }

    public function approve(Review $review)
    {
        $review->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id()
        ]);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Avis approuvé avec succès'
            ]);
        }

        return back()->with('success', 'Avis approuvé avec succès.');
    }

    public function reject(Request $request, Review $review)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $review->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason,
            'rejected_at' => now(),
            'rejected_by' => auth()->id()
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Avis rejeté'
            ]);
        }

        return back()->with('success', 'Avis rejeté.');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Avis supprimé avec succès'
            ]);
        }

        return redirect()->route('admin.reviews.index')->with('success', 'Avis supprimé avec succès.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,delete',
            'ids' => 'required|array',
            'ids.*' => 'exists:reviews,id'
        ]);

        $count = count($request->ids);

        switch ($request->action) {
            case 'approve':
                Review::whereIn('id', $request->ids)->update([
                    'status' => 'approved',
                    'approved_at' => now(),
                    'approved_by' => auth()->id()
                ]);
                $message = "$count avis approuvés avec succès";
                break;

            case 'reject':
                Review::whereIn('id', $request->ids)->update([
                    'status' => 'rejected',
                    'rejected_at' => now(),
                    'rejected_by' => auth()->id()
                ]);
                $message = "$count avis rejetés";
                break;

            case 'delete':
                Review::whereIn('id', $request->ids)->delete();
                $message = "$count avis supprimés";
                break;
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
}
