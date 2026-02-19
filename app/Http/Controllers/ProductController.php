<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()
            ->with(['images', 'artisan.user'])           // Charger l'artisan + son user
            ->whereHas('artisan', function ($q) {        // ← IMPORTANT : seulement artisans approuvés
                $q->where('status', 'approved');
            });

        // Recherche textuelle
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('name_local', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtre par catégorie
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filtre par origine ethnique
        if ($request->filled('ethnic_origin')) {
            $query->where('ethnic_origin', $request->ethnic_origin);
        }

        // Filtre par ville (nouveau)
        if ($request->filled('city')) {
            $query->whereHas('artisan', function ($q) use ($request) {
                $q->where('city', $request->city);
            });
        }

        // Filtre par prix
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Tri
        $sort = $request->input('sort', 'popular');
        switch ($sort) {
            case 'newest':
                $query->latest();
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
            default:
                $query->orderBy('views', 'desc');
                break;
        }

        $products = $query->paginate(16)->withQueryString();

        // Données pour les filtres
        $categories     = Product::select('category')->distinct()->pluck('category');
        $ethnicOrigins  = Product::select('ethnic_origin')->distinct()->pluck('ethnic_origin');

        // Villes des artisans approuvés (uniquement ceux qui ont des produits)
        $cities = Product::whereHas('artisan', function ($q) {
            $q->where('status', 'approved');
        })
            ->with('artisan')
            ->get()
            ->pluck('artisan.city')
            ->filter()
            ->unique()
            ->values();

        return view('products.index', compact(
            'products',
            'categories',
            'ethnicOrigins',
            'cities'
        ));
    }

    public function show(Product $product)
    {
        $product->increment('views');
        $product->load(['artisan.user', 'artisan.photos', 'images', 'reviews.user']);

        // Produits similaires
        $similarProducts = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->with(['images', 'artisan'])
            ->take(4)
            ->get();

        // Autres produits du même artisan
        $sameArtisanProducts = Product::where('artisan_id', $product->artisan_id)
            ->where('id', '!=', $product->id)
            ->with(['images'])
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'similarProducts', 'sameArtisanProducts'));
    }


    public function create()
    {
        // Vérifier que l'utilisateur a un profil artisan
        if (!auth()->user()->artisan) {
            return redirect()->route('dashboard')
                ->with('error', 'Vous devez avoir un profil artisan pour ajouter des produits.');
        }

        return view('products.create');
    }

    public function store(Request $request)
    {
        // LOG DE DÉBOGAGE SIMPLE
        \Log::info('========== METHODE STORE APPELEE ==========');
        \Log::info('URL: ' . $request->fullUrl());
        \Log::info('METHOD: ' . $request->method());
        \Log::info('USER: ' . (auth()->check() ? auth()->user()->email : 'NON CONNECTE'));

        // Rediriger vers une page de test pour voir si on arrive ici
        return redirect()->route('dashboard')->with('info', 'Méthode store atteinte !');
        // Log pour déboguer
        Log::info('Tentative d\'ajout de produit', [
            'user_id' => auth()->id(),
            'method' => $request->method(),
            'url' => $request->url(),
            'data' => $request->except('_token')
        ]);

        // Vérification de l'authentification
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Récupérer l'artisan de l'utilisateur connecté
        $artisan = auth()->user()->artisan;

        if (!$artisan) {
            return redirect()->back()
                ->with('error', 'Vous devez avoir un profil artisan pour ajouter des produits.')
                ->withInput();
        }

        // Validation adaptée à votre formulaire
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'nullable|string',
            'material' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'production_time' => 'nullable|string|max:255',
            'dimensions' => 'nullable|string|max:255',
            'weight' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'customizable' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'images' => 'required|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        try {
            // Préparer les données pour l'insertion
            $productData = [
                'artisan_id' => $artisan->id,
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']) . '-' . uniqid(),
                'description' => $validated['description'],
                'category' => $validated['category'] ?? null,
                'material' => $validated['material'] ?? null,
                'price' => $validated['price'],
                'stock' => $validated['stock'] ?? 1,
                'production_time' => $validated['production_time'] ?? null,
                'dimensions' => $validated['dimensions'] ?? null,
                'weight' => $validated['weight'] ?? null,
                'color' => $validated['color'] ?? null,
                'customizable' => $request->has('customizable'),
                'is_active' => $request->has('is_active'),
            ];

            // Créer le produit
            $product = Product::create($productData);

            // Traitement des images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products/' . $product->id, 'public');

                    // Si vous avez une table product_images
                    $product->images()->create([
                        'image_path' => $path,
                        'is_primary' => $index === 0,
                        'order' => $index
                    ]);
                }
            }

        // Générer la description culturelle avec IA (asynchrone)
        // if (empty($validated['description_cultural'])) {
        //     dispatch(new \App\Jobs\GenerateCulturalDescription($product));
        // }

            return redirect()->route('products.show', $product)
                ->with('success', 'Produit ajouté avec succès !');
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'ajout du produit', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de l\'ajout du produit.')
                ->withInput();
        }
    }
}
