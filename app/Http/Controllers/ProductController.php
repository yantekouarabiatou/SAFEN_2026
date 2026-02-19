<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductController extends Controller
{
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

            Log::info('Produit ajouté avec succès', ['product_id' => $product->id]);

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
