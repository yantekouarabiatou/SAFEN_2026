<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Artisan;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['artisan.user', 'images'])
            ->where('stock_status', '!=', 'out_of_stock');

        // Filtres
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        if ($request->has('ethnic_origin') && $request->ethnic_origin) {
            $query->where('ethnic_origin', $request->ethnic_origin);
        }

        if ($request->has('material') && $request->material) {
            $query->whereJsonContains('materials', $request->material);
        }

        if ($request->has('min_price') && $request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && $request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->has('artisan') && $request->artisan) {
            $query->where('artisan_id', $request->artisan);
        }

        // Recherche
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('name_local', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
            });
        }

        // Tri
        $sort = $request->sort ?? 'popular';
        switch ($sort) {
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
            default:
                $query->orderBy('views', 'desc');
                break;
        }

        $products = $query->paginate(16);

        // Données pour les filtres
        $categories = Product::distinct('category')->pluck('category');
        $ethnicOrigins = Product::distinct('ethnic_origin')->pluck('ethnic_origin');
        $artisans = Artisan::whereHas('products')->pluck('business_name', 'id');

        // Extraire tous les matériaux uniques
        $allMaterials = Product::whereNotNull('materials')->pluck('materials')->flatten()->unique();

        return view('products.index', compact('products', 'categories', 'ethnicOrigins', 'artisans', 'allMaterials'));
    }

    public function show(Product $product)
    {
        if ($product->stock_status === 'out_of_stock' && !auth()->check()) {
            abort(404);
        }

        $product->load(['artisan.user', 'artisan.photos', 'images']);

        // Incrémenter les vues
        $product->increment('views');

        // Produits similaires
        $similarProducts = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->where('stock_status', '!=', 'out_of_stock')
            ->inRandomOrder()
            ->limit(6)
            ->get();

        // Produits du même artisan
        $sameArtisanProducts = Product::where('artisan_id', $product->artisan_id)
            ->where('id', '!=', $product->id)
            ->where('stock_status', '!=', 'out_of_stock')
            ->limit(4)
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
        if (empty($validated['description_cultural'])) {
            dispatch(new \App\Jobs\GenerateCulturalDescription($product));
        }

        return redirect()->route('products.show', $product)
            ->with('success', 'Produit ajouté avec succès !');
    }
}
