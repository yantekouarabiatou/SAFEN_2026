<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(['artisan.user', 'artisan.photos', 'images'])
            ->when($request->search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('name_local', 'like', "%{$search}%");
            })
            ->when($request->category, function ($query, $category) {
                return $query->where('category', $category);
            })
            ->when($request->ethnic_origin, function ($query, $origin) {
                return $query->where('ethnic_origin', $origin);
            })
            ->when($request->material, function ($query, $material) {
                return $query->whereJsonContains('materials', $material);
            })
            ->when($request->min_price, function ($query, $min) {
                return $query->where('price', '>=', $min);
            })
            ->when($request->max_price, function ($query, $max) {
                return $query->where('price', '<=', $max);
            })
            ->when($request->sort, function ($query, $sort) {
                switch ($sort) {
                    case 'newest':
                        return $query->latest();
                    case 'price_asc':
                        return $query->orderBy('price', 'asc');
                    case 'price_desc':
                        return $query->orderBy('price', 'desc');
                    default: // popular
                        return $query->orderBy('views', 'desc');
                }
            }, function ($query) {
                return $query->orderBy('views', 'desc');
            })
            ->paginate(16);

        $user = auth()->user();
        $favoritedProductIds = $user ? $user->favorites()
            ->where('favoritable_type', 'App\\Models\\Product')
            ->pluck('favoritable_id')
            ->toArray() : [];

        $products->getCollection()->transform(function ($product) use ($favoritedProductIds) {
            $product->isFavorited = in_array($product->id, $favoritedProductIds);
            return $product;
        });
        $categories = Product::select('category')->distinct()->pluck('category');
        $ethnicOrigins = Product::select('ethnic_origin')->distinct()->pluck('ethnic_origin');
        $allMaterials = Product::whereNotNull('materials')->get()
            ->pluck('materials')->flatten()->unique()->values();

        return view('products.index', compact('products', 'categories', 'ethnicOrigins', 'allMaterials'));
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
