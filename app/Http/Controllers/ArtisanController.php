<?php

namespace App\Http\Controllers;

use App\Models\Artisan;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArtisanController extends Controller
{
    /**
     * Display a listing of the artisans.
     */
    public function index(Request $request)
    {
        // Initial query
        $query = Artisan::with(['user', 'photos'])
            ->where('visible', true);

        // Filter by craft
        if ($request->filled('craft')) {
            $query->where('craft', $request->craft);
        }

        // Filter by city
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        // Filter by verified status
        if ($request->filled('verified') && $request->verified == 'true') {
            $query->where('verified', true);
        }

        // Filter by rating
        if ($request->filled('rating_min')) {
            $query->where('rating_avg', '>=', $request->rating_min);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhere('business_name', 'like', "%{$search}%")
                ->orWhere('craft', 'like', "%{$search}%")
                ->orWhere('city', 'like', "%{$search}%")
                ->orWhere('neighborhood', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sort = $request->get('sort', 'rating_avg');
        $direction = $request->get('direction', 'desc');

        $query->orderBy($sort, $direction);

        // Get paginated results
        $artisans = $query->paginate(12);

        // Get available crafts and cities for filters
        $crafts = Artisan::distinct()->pluck('craft')->mapWithKeys(function($craft) {
            return [$craft => Artisan::$craftLabels[$craft] ?? $craft];
        })->sort();

        $cities = Artisan::distinct()->pluck('city')->sort();

        // Available view modes
        $viewMode = $request->get('view', 'grid'); // grid, list, map

        return view('artisans.index', compact('artisans', 'crafts', 'cities', 'viewMode'));
    }

    /**
     * Display the specified artisan.
     */
    public function show(Artisan $artisan)
    {
        // Increment views
        $artisan->increment('views');

        // Load relationships
        $artisan->load(['user', 'photos', 'products' => function($query) {
            $query->where('visible', true)->with('primaryImage');
        }, 'reviews' => function($query) {
            $query->with('user')->latest()->limit(5);
        }]);

        // Get similar artisans
        $similarArtisans = Artisan::where('craft', $artisan->craft)
            ->where('id', '!=', $artisan->id)
            ->where('verified', true)
            ->where('visible', true)
            ->with(['user', 'photos'])
            ->limit(4)
            ->get();

        return view('artisans.show', compact('artisan', 'similarArtisans'));
    }

    /**
     * Show the contact form for an artisan.
     */
    public function contact(Artisan $artisan)
    {
        return view('artisans.contact', compact('artisan'));
    }

    /**
     * Dashboard for artisans.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $artisan = $user->artisan;

        if (!$artisan) {
            abort(403, 'Vous n\'êtes pas un artisan.');
        }

        $stats = [
            'products_count' => $artisan->products()->count(),
            'total_views' => $artisan->views,
            'rating_avg' => $artisan->rating_avg,
            'rating_count' => $artisan->rating_count,
            'contacts_this_month' => 0, // À implémenter
        ];

        $recentProducts = $artisan->products()
            ->latest()
            ->limit(5)
            ->get();

        $recentReviews = $artisan->reviews()
            ->with('user')
            ->latest()
            ->limit(5)
            ->get();

        return view('artisans.dashboard', compact('artisan', 'stats', 'recentProducts', 'recentReviews'));
    }

    /**
     * Display artisan's products.
     */
    public function myProducts()
    {
        $user = Auth::user();
        $artisan = $user->artisan;

        if (!$artisan) {
            abort(403, 'Vous n\'êtes pas un artisan.');
        }

        $products = $artisan->products()
            ->with(['primaryImage', 'images'])
            ->latest()
            ->paginate(10);

        return view('artisans.products.index', compact('artisan', 'products'));
    }

    /**
     * Show form to create a new product.
     */
    public function createProduct()
    {
        $user = Auth::user();
        $artisan = $user->artisan;

        if (!$artisan) {
            abort(403, 'Vous n\'êtes pas un artisan.');
        }

        $categories = Product::getCategories();
        $materials = [
            'bois' => 'Bois',
            'bronze' => 'Bronze',
            'terre_cuite' => 'Terre cuite',
            'perles' => 'Perles',
            'cauris' => 'Cauris',
            'textile' => 'Textile',
            'vannerie' => 'Vannerie',
            'metal' => 'Métal',
            'autre' => 'Autre',
        ];

        return view('artisans.products.create', compact('artisan', 'categories', 'materials'));
    }

    /**
     * Store a newly created product.
     */
    public function storeProduct(Request $request)
    {
        $user = Auth::user();
        $artisan = $user->artisan;

        if (!$artisan) {
            abort(403, 'Vous n\'êtes pas un artisan.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_local' => 'nullable|string|max:255',
            'category' => 'required|string',
            'materials' => 'nullable|array',
            'price' => 'required|numeric|min:0',
            'stock_status' => 'required|in:in_stock,out_of_stock,preorder,made_to_order',
            'description' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
        ]);

        // Créer le produit
        $product = $artisan->products()->create([
            'name' => $validated['name'],
            'name_local' => $validated['name_local'],
            'category' => $validated['category'],
            'materials' => $validated['materials'] ?? [],
            'price' => $validated['price'],
            'currency' => 'XOF',
            'stock_status' => $validated['stock_status'],
            'description' => $validated['description'],
            'slug' => \Str::slug($validated['name'] . '-' . time()),
        ]);

        // Upload des images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');

                $product->images()->create([
                    'image_url' => $path,
                    'is_primary' => $index === 0,
                    'order' => $index,
                ]);
            }
        }

        // Générer la description culturelle avec IA (si activé)
        if ($request->has('generate_ai_description')) {
            // À implémenter: appeler le service IA
            // $aiDescription = app(AIService::class)->generateCulturalDescription($product);
            // $product->update(['description_cultural' => $aiDescription]);
        }

        return redirect()->route('artisan.products.index')
            ->with('success', 'Produit créé avec succès!');
    }

    /**
     * Show form to edit an artisan's profile.
     */
    public function editProfile()
    {
        $user = Auth::user();
        $artisan = $user->artisan;

        if (!$artisan) {
            abort(403, 'Vous n\'êtes pas un artisan.');
        }

        $crafts = Artisan::$craftLabels ?? [];
        $languages = [
            'fr' => 'Français',
            'fon' => 'Fon',
            'yoruba' => 'Yoruba',
            'bariba' => 'Bariba',
            'dendi' => 'Dendi',
            'anglais' => 'Anglais',
        ];

        return view('artisans.profile.edit', compact('artisan', 'crafts', 'languages'));
    }

    /**
     * Update the artisan's profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $artisan = $user->artisan;

        if (!$artisan) {
            abort(403, 'Vous n\'êtes pas un artisan.');
        }

        $validated = $request->validate([
            'business_name' => 'nullable|string|max:255',
            'craft' => 'required|string',
            'bio' => 'nullable|string|max:1000',
            'years_experience' => 'nullable|integer|min:0',
            'city' => 'required|string|max:255',
            'neighborhood' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'whatsapp' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'languages_spoken' => 'nullable|array',
            'pricing_info' => 'nullable|string|max:500',
            'photos' => 'nullable|array',
            'photos.*' => 'image|max:2048',
        ]);

        // Mettre à jour l'artisan
        $artisan->update($validated);

        // Mettre à jour les photos
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $photo) {
                $path = $photo->store('artisans', 'public');

                $artisan->photos()->create([
                    'photo_url' => $path,
                    'caption' => $request->input("photo_captions.{$index}"),
                    'order' => $index,
                ]);
            }
        }

        // Mettre à jour l'utilisateur
        $user->update([
            'name' => $request->input('user_name', $user->name),
            'phone' => $validated['phone'] ?? $user->phone,
            'city' => $validated['city'] ?? $user->city,
            'address' => $validated['address'] ?? $user->address,
        ]);

        return redirect()->route('artisan.dashboard')
            ->with('success', 'Profil mis à jour avec succès!');
    }

    /**
     * Display artisan statistics.
     */
    public function statistics()
    {
        $user = Auth::user();
        $artisan = $user->artisan;

        if (!$artisan) {
            abort(403, 'Vous n\'êtes pas un artisan.');
        }

        // Récupérer les statistiques
        $stats = [
            'total_views' => $artisan->views,
            'product_views' => $artisan->products()->sum('views'),
            'contacts_count' => 0, // À implémenter
            'rating_avg' => $artisan->rating_avg,
            'rating_count' => $artisan->rating_count,
        ];

        // Vue par mois (exemple)
        $monthlyViews = [
            'Jan' => 120,
            'Fév' => 150,
            'Mar' => 180,
            'Avr' => 200,
            'Mai' => 220,
            'Jun' => 250,
        ];

        // Produits les plus vus
        $popularProducts = $artisan->products()
            ->with('primaryImage')
            ->orderBy('views', 'desc')
            ->limit(5)
            ->get();

        return view('artisans.statistics', compact('artisan', 'stats', 'monthlyViews', 'popularProducts'));
    }
}
