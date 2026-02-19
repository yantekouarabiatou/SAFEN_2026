<?php

namespace App\Http\Controllers;

use App\Models\Artisan;
use App\Models\Product;
use App\Models\Dish;
use App\Models\Product as ModelsProduct;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with(['artisan.user', 'primaryImage'])
            ->where('featured', true)
            ->where('stock_status', 'in_stock')
            ->limit(8)
            ->get();

        $featuredArtisans = Artisan::with(['user', 'photos'])
            ->where('featured', true)
            ->where('verified', true)
            ->orderBy('rating_avg', 'desc')
            ->limit(4)
            ->get();

        // Récupérer les témoignages dynamiques
        $testimonials = $this->getTestimonials();

        return view('home', compact('featuredProducts', 'featuredArtisans', 'testimonials'));
    }

    /**
     * Récupère les témoignages dynamiques avec fallback
     */
    private function getTestimonials()
    {
        // Essayer d'abord de récupérer des témoignages réels
        $realTestimonials = Review::with(['user', 'reviewable'])
            ->whereHas('user')
            ->whereHas('reviewable')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        // Si on a assez de témoignages, on les retourne
        if ($realTestimonials->count() >= 4) {
            return $realTestimonials;
        }

        // Sinon, on crée des témoignages fallback
        return $this->getFallbackTestimonials($realTestimonials);
    }

    /**
     * Génère des témoignages fallback
     */

    private function getFallbackTestimonials($realTestimonials)
    {
        // Si on a déjà des vrais témoignages, on les retourne
        if ($realTestimonials && $realTestimonials->count() > 0) {
            return $realTestimonials;
        }

        // Témoignages fallback
        $fallbackTestimonials = collect([
            [
                'id' => 1,
                'rating' => 5,
                'comment' => "Grâce à AFRI-HERITAGE, j'ai triplé ma clientèle ! Les touristes me trouvent facilement et je peux maintenant vendre mes plats traditionnels à l'international. Merci !",
                'created_at' => now()->subDays(30),

                'user' => [
                    'name' => 'Marie Koko',
                    'avatar_url' => asset('images/testimonials/testimonial1.jpg'),
                ],

                'reviewable' => [
                    'craft_label' => 'Cuisinière',
                    'city' => 'Cotonou',
                ],

                'is_fallback' => true,
            ],

            [
                'id' => 2,
                'rating' => 5,
                'comment' => "L'IA qui génère les descriptions culturelles de mes sculptures est incroyable ! Mes clients comprennent maintenant toute la symbolique de mon travail.",
                'created_at' => now()->subDays(25),

                'user' => [
                    'name' => 'Jean Soglo',
                    'avatar_url' => asset('images/testimonials/testimonial2.jpg'),
                ],

                'reviewable' => [
                    'craft_label' => 'Sculpteur',
                    'city' => 'Abomey',
                ],

                'is_fallback' => true,
            ],

            [
                'id' => 3,
                'rating' => 5,
                'comment' => "En tant que Béninoise de la diaspora, cette plateforme me permet de rester connectée à mes racines. Livraison impeccable !",
                'created_at' => now()->subDays(20),

                'user' => [
                    'name' => 'Sophie Djossou',
                    'avatar_url' => asset('images/testimonials/testimonial3.jpg'),
                ],

                'reviewable' => [
                    'craft_label' => 'Cliente',
                    'city' => 'Paris',
                ],

                'is_fallback' => true,
            ],

            [
                'id' => 4,
                'rating' => 4,
                'comment' => "Excellente plateforme ! Je reçois maintenant des clients grâce à la carte. Le système de notation m'a permis de bâtir ma réputation.",
                'created_at' => now()->subDays(15),

                'user' => [
                    'name' => 'Marc Azonhiho',
                    'avatar_url' => asset('images/testimonials/testimonial4.jpg'),
                ],

                'reviewable' => [
                    'craft_label' => 'Mécanicien',
                    'city' => 'Porto-Novo',
                ],

                'is_fallback' => true,
            ],
        ]);

        // Transformer chaque entrée en objet Review
        return $fallbackTestimonials->map(function ($item) {

            $review = new Review();

            // Champs simples
            $review->id         = $item['id'];
            $review->rating     = $item['rating'];
            $review->comment    = $item['comment'];
            $review->created_at = $item['created_at'];

            // Champs spéciaux en objets
            $review->user       = (object) $item['user'];
            $review->reviewable = (object) $item['reviewable'];

            // Flag fallback
            $review->is_fallback = true;

            return $review;
        });
    }



    /**
     * Récupère les derniers avis pour un artisan spécifique
     */
    public function getArtisanTestimonials(Artisan $artisan)
    {
        $testimonials = Review::where('reviewable_type', Artisan::class)
            ->where('reviewable_id', $artisan->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        return response()->json([
            'success' => true,
            'testimonials' => $testimonials,
            'average_rating' => $artisan->rating_avg,
            'total_reviews' => $artisan->rating_count,
        ]);
    }

    /**
     * Récupère les derniers avis pour un produit spécifique
     */
    public function getProductTestimonials(Product $product)
    {
        $testimonials = Review::where('reviewable_type', Product::class)
            ->where('reviewable_id', $product->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        return response()->json([
            'success' => true,
            'testimonials' => $testimonials,
            'average_rating' => $product->rating_avg,
            'total_reviews' => $product->rating_count,
        ]);
    }

    /**
     * Soumet un nouvel avis
     */
    public function submitTestimonial(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Veuillez vous connecter pour laisser un avis.'
            ], 401);
        }

        $validated = $request->validate([
            'reviewable_type' => 'required|string|in:App\Models\Artisan,App\Models\Product',
            'reviewable_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:500',
        ]);

        // Vérifier si l'utilisateur a déjà laissé un avis
        $existingReview = Review::where('user_id', $user->id)
            ->where('reviewable_type', $validated['reviewable_type'])
            ->where('reviewable_id', $validated['reviewable_id'])
            ->first();

        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => 'Vous avez déjà laissé un avis sur cet élément.'
            ], 400);
        }

        // Créer l'avis
        $review = Review::create([
            'user_id' => $user->id,
            'reviewable_type' => $validated['reviewable_type'],
            'reviewable_id' => $validated['reviewable_id'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        // Mettre à jour la note moyenne de l'élément
        $this->updateReviewableRating($validated['reviewable_type'], $validated['reviewable_id']);

        return response()->json([
            'success' => true,
            'message' => 'Votre avis a été publié avec succès !',
            'review' => $review->load('user'),
        ]);
    }

    /**
     * Met à jour la note moyenne d'un élément
     */
    private function updateReviewableRating($reviewableType, $reviewableId)
    {
        $model = $reviewableType::find($reviewableId);

        if ($model) {
            $reviews = Review::where('reviewable_type', $reviewableType)
                ->where('reviewable_id', $reviewableId);

            $averageRating = $reviews->avg('rating');
            $reviewCount = $reviews->count();

            $model->update([
                'rating_avg' => $averageRating,
                'rating_count' => $reviewCount,
            ]);
        }
    }

    public function about()
    {
        return view('pages.about');
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        // Recherche dans les artisans
        $artisans = Artisan::with('user')
            ->whereHas('user', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->orWhere('craft', 'like', "%{$query}%")
            ->orWhere('city', 'like', "%{$query}%")
            ->limit(10)
            ->get();

        // Recherche dans les produits
        $products = Product::with(['artisan.user', 'primaryImage'])
            ->where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->limit(10)
            ->get();

        // Recherche dans les plats
        $dishes = Dish::where('name', 'like', "%{$query}%")
            ->orWhere('name_local', 'like', "%{$query}%")
            ->limit(10)
            ->get();

        return view('pages.search', compact('query', 'artisans', 'products', 'dishes'));
    }

    public function dashboard()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return view('admin.dashboard');
        } elseif ($user->hasRole('artisan')) {
            return view('artisan.dashboard');
        }

        return view('client.dashboard');
    }

    public function culture()
    {
        return view('pages.culture');
    }
}
