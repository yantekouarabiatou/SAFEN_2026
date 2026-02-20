<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

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
       //dd($products->first()); // ou mieux : dd($products->first()->images);
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
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_local' => 'nullable|string|max:255',
            'category' => 'required|string',
            'subcategory' => 'nullable|string',
            'ethnic_origin' => 'required|string',
            'materials' => 'required|array',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'stock_status' => 'required|in:in_stock,low_stock,out_of_stock',
            'width' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'depth' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'description' => 'nullable|string',
            'description_cultural' => 'nullable|string',
            'description_technical' => 'nullable|string',
        ]);

        // Récupérer l'artisan de l'utilisateur connecté
        $artisan = auth()->user()->artisan;

        if (!$artisan) {
            return redirect()->back()
                ->with('error', 'Vous devez avoir un profil artisan pour ajouter des produits.');
        }

        $validated['artisan_id'] = $artisan->id;
        $validated['materials'] = json_encode($validated['materials']);
        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);

        $product = Product::create($validated);

        // Traitement des images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products/images', 'public');

                $product->images()->create([
                    'image_url' => $path,
                    'is_primary' => $index === 0,
                    'order' => $index,
                ]);
            }
        }

        // Générer la description culturelle avec IA (asynchrone)
        // if (empty($validated['description_cultural'])) {
        //     dispatch(new \App\Jobs\GenerateCulturalDescription($product));
        // }

        return redirect()->route('products.show', $product)
            ->with('success', 'Produit ajouté avec succès !');
    }
}
