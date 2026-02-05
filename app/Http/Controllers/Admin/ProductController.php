<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artisan;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['artisan.user', 'images']);

        // Filtres
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('artisan')) {
            $query->where('artisan_id', $request->artisan);
        }

        if ($request->filled('stock_status')) {
            $query->where('stock_status', $request->stock_status);
        }

        $products = $query->latest()->paginate(15);
        
        $categories = Product::distinct()->pluck('category');
        $artisans = Artisan::with('user')->get();

        return view('admin.products.index', compact('products', 'categories', 'artisans'));
    }

    public function create()
    {
        $artisans = Artisan::with('user')->get();
        $categories = [
            'masque' => 'Masque',
            'sculpture' => 'Sculpture',
            'tissu' => 'Tissu',
            'bijou' => 'Bijou',
            'instrument' => 'Instrument',
            'decoration' => 'Décoration',
            'peinture' => 'Peinture',
            'vannerie' => 'Vannerie',
            'poterie' => 'Poterie',
            'cuisine' => 'Ustensile de cuisine',
            'autre' => 'Autre',
        ];

        return view('admin.products.create', compact('artisans', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'artisan_id' => 'required|exists:artisans,id',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'stock_status' => 'required|in:in_stock,out_of_stock,preorder,made_to_order',
            'images.*' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $product = Product::create([
                'name' => $request->name,
                'name_local' => $request->name_local,
                'artisan_id' => $request->artisan_id,
                'category' => $request->category,
                'subcategory' => $request->subcategory,
                'ethnic_origin' => $request->ethnic_origin,
                'materials' => $request->materials ? json_encode(explode(',', $request->materials)) : null,
                'price' => $request->price,
                'currency' => $request->currency ?? 'XOF',
                'stock_status' => $request->stock_status,
                'width' => $request->width,
                'height' => $request->height,
                'depth' => $request->depth,
                'weight' => $request->weight,
                'description' => $request->description,
                'description_cultural' => $request->description_cultural,
                'description_technical' => $request->description_technical,
                'slug' => Str::slug($request->name) . '-' . uniqid(),
                'featured' => $request->boolean('featured'),
            ]);

            // Upload des images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_url' => $path,
                        'is_primary' => $index === 0,
                        'order' => $index,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Produit créé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la création: ' . $e->getMessage());
        }
    }

    public function show(Product $product)
    {
        $product->load(['artisan.user', 'images', 'reviews.user']);
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $product->load(['artisan', 'images']);
        $artisans = Artisan::with('user')->get();
        $categories = [
            'masque' => 'Masque',
            'sculpture' => 'Sculpture',
            'tissu' => 'Tissu',
            'bijou' => 'Bijou',
            'instrument' => 'Instrument',
            'decoration' => 'Décoration',
            'peinture' => 'Peinture',
            'vannerie' => 'Vannerie',
            'poterie' => 'Poterie',
            'cuisine' => 'Ustensile de cuisine',
            'autre' => 'Autre',
        ];

        return view('admin.products.edit', compact('product', 'artisans', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'artisan_id' => 'required|exists:artisans,id',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'stock_status' => 'required|in:in_stock,out_of_stock,preorder,made_to_order',
            'images.*' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $product->update([
                'name' => $request->name,
                'name_local' => $request->name_local,
                'artisan_id' => $request->artisan_id,
                'category' => $request->category,
                'subcategory' => $request->subcategory,
                'ethnic_origin' => $request->ethnic_origin,
                'materials' => $request->materials ? json_encode(explode(',', $request->materials)) : null,
                'price' => $request->price,
                'currency' => $request->currency ?? 'XOF',
                'stock_status' => $request->stock_status,
                'width' => $request->width,
                'height' => $request->height,
                'depth' => $request->depth,
                'weight' => $request->weight,
                'description' => $request->description,
                'description_cultural' => $request->description_cultural,
                'description_technical' => $request->description_technical,
                'featured' => $request->boolean('featured'),
            ]);

            // Upload des nouvelles images
            if ($request->hasFile('images')) {
                $lastOrder = $product->images()->max('order') ?? -1;
                foreach ($request->file('images') as $image) {
                    $lastOrder++;
                    $path = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_url' => $path,
                        'is_primary' => false,
                        'order' => $lastOrder,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Produit mis à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        try {
            // Supprimer les images
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_url);
            }
            
            $product->delete();

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Produit supprimé avec succès.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    public function deleteImage($imageId)
    {
        $image = ProductImage::findOrFail($imageId);
        Storage::disk('public')->delete($image->image_url);
        $image->delete();

        return response()->json(['success' => true]);
    }

    public function toggleFeatured(Product $product)
    {
        $product->update(['featured' => !$product->featured]);
        return response()->json(['success' => true, 'featured' => $product->featured]);
    }
}
