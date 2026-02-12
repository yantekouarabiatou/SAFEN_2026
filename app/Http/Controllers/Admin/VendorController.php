<?php

namespace App\Http\Controllers\Admin;

use App\Models\Vendor;
use App\Models\Dish;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\DishImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class VendorController extends Controller
{
    public function create()
    {
        $dishes = Dish::orderBy('name')->get();

        return view('admin.vendors.create', compact('dishes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'type'          => 'required|string|max:100',
            'city'          => 'required|string|max:100',
            'address'       => 'nullable|string|max:255',
            'latitude'      => 'nullable|numeric',
            'longitude'     => 'nullable|numeric',
            'phone'         => 'required|string|max:20',
            'whatsapp'      => 'nullable|string|max:20',
            'description'   => 'nullable|string',
            'opening_hours' => 'nullable|string|max:100',
            'logo'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'dishes'        => 'nullable|array',
            'dishes.*'      => 'exists:dishes,id',
            'prices'        => 'nullable|array',
            'prices.*'      => 'numeric|min:0',
        ]);

        $validated['user_id'] = Auth::id();

        // Gestion du logo
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $path = $request->file('logo')->store('vendors/logos', 'public');
            $validated['logo'] = $path;
        }

        $vendor = Vendor::create($validated);

        // Associer les plats
        if ($request->filled('dishes')) {
            $attachData = [];
            foreach ($request->dishes as $dishId) {
                $attachData[$dishId] = [
                    'price'     => $request->prices[$dishId] ?? null,
                    'available' => true,
                    'notes'     => null,
                ];
            }
            $vendor->dishes()->attach($attachData);
        }

        return redirect()->route('admin.vendors.show', $vendor)
            ->with('success', 'Profil vendeur créé avec succès !');
    }

    public function edit(Vendor $vendor)
    {
        $this->authorize('update', $vendor); // ou vérifie que c'est le proprio

        $dishes = Dish::orderBy('name')->get();
        $selectedDishes = $vendor->dishes->pluck('id')->toArray();
        $prices = $vendor->dishes->pluck('pivot.price', 'id')->toArray();

        return view('admin.vendors.edit', compact('vendor', 'dishes', 'selectedDishes', 'prices'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        $this->authorize('update', $vendor);

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'type'          => 'required|string|max:100',
            'city'          => 'required|string|max:100',
            'address'       => 'nullable|string|max:255',
            'latitude'      => 'nullable|numeric',
            'longitude'     => 'nullable|numeric',
            'phone'         => 'required|string|max:20',
            'whatsapp'      => 'nullable|string|max:20',
            'description'   => 'nullable|string',
            'opening_hours' => 'nullable|string|max:100',
            'logo'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'dishes'        => 'nullable|array',
            'dishes.*'      => 'exists:dishes,id',
            'prices'        => 'nullable|array',
            'prices.*'      => 'numeric|min:0',
        ]);

        // Gestion du logo (remplacement si nouveau)
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            // Supprimer l'ancien si existe
            if ($vendor->logo) {
                Storage::disk('public')->delete($vendor->logo);
            }
            $path = $request->file('logo')->store('vendors/logos', 'public');
            $validated['logo'] = $path;
        }

        $vendor->update($validated);

        // Synchroniser les plats (remplace les anciens)
        if ($request->filled('dishes')) {
            $syncData = [];
            foreach ($request->dishes as $dishId) {
                $syncData[$dishId] = [
                    'price'     => $request->prices[$dishId] ?? null,
                    'available' => true,
                    'notes'     => null,
                ];
            }
            $vendor->dishes()->sync($syncData);
        } else {
            $vendor->dishes()->detach(); // retire tout si aucun plat sélectionné
        }

        return redirect()->route('admin.vendors.show', $vendor)
            ->with('success', 'Profil vendeur mis à jour avec succès !');
    }

    /**
     * Création rapide d'un plat depuis le formulaire du profil vendeur.
     * Retourne JSON pour mise à jour dynamique de la liste.
     */
    public function quickStore(Request $request)
    {
        $vendor = Auth::user()->vendor;

        if (!$vendor) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun profil vendeur trouvé.'
            ], 403);
        }

        // Validation des données du plat
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'name_local'    => 'nullable|string|max:255',
            'category'      => ['required', Rule::in(array_keys(Dish::$categoryLabels))],
            'ethnic_origin' => 'nullable|string|max:100',
            'region'        => 'nullable|string|max:100',
            'ingredients'   => 'required|string',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // Créer le plat
            $dish = Dish::create([
                'name'          => $validated['name'],
                'name_local'    => $validated['name_local'] ?? null,
                'slug'          => Str::slug($validated['name'] . '-' . uniqid()),
                'category'      => $validated['category'],
                'ethnic_origin' => $validated['ethnic_origin'] ?? null,
                'region'        => $validated['region'] ?? null,
                'ingredients'   => array_map('trim', explode(',', $validated['ingredients'])),
                'description'   => $validated['description'] ?? null,
                'views'         => 0,
            ]);

            // Associer au vendeur avec le prix
            $vendor->dishes()->attach($dish->id, [
                'price'     => $validated['price'],
                'available' => true,
                'notes'     => null,
            ]);

            // Gérer l'image si fournie
            $imageUrl = null;
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $path = $request->file('image')->store('dishes', 'public');
                DishImage::create([
                    'dish_id'   => $dish->id,
                    'image_url' => $path,
                    'order'     => 0,
                ]);
                $imageUrl = Storage::url($path);
            }

            DB::commit();

            // Retourner les informations nécessaires pour l'affichage dynamique
            return response()->json([
                'success' => true,
                'message' => 'Plat ajouté avec succès.',
                'dish'    => [
                    'id'         => $dish->id,
                    'name'       => $dish->name,
                    'name_local' => $dish->name_local,
                    'image'      => $imageUrl ? true : false,
                    'image_url'  => $imageUrl,
                ],
                'price'   => $validated['price'],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création : ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Détacher un plat du vendeur (supprimer la relation pivot).
     * Ne supprime pas le plat de la base de données.
     */

    public function detach(Dish $dish)
    {
        $vendor = Auth::user()->vendor;

        if (!$vendor) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun profil vendeur associé.'
            ], 403);
        }

        // Vérifier que le plat est bien lié à ce vendeur
        if (!$vendor->dishes()->where('dish_id', $dish->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Ce plat ne fait pas partie de votre catalogue.'
            ], 403);
        }

        // Détacher
        $vendor->dishes()->detach($dish->id);

        // Optionnel : supprimer le plat s'il n'est plus associé à aucun vendeur
        if ($dish->vendors()->count() === 0) {
            // Supprimer les images
            foreach ($dish->images as $image) {
                Storage::disk('public')->delete($image->image_url);
                $image->delete();
            }
            $dish->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Plat retiré de votre catalogue.'
        ]);
    }
}
