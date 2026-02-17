<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\Dish;
use App\Models\DishImage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VendorController extends Controller
{

    /**
     * Liste des vendeurs (admin)
     */
    public function index(Request $request)
    {
        $vendors = Vendor::with(['user', 'dishes' => fn($q) => $q->with('images')])
            ->latest()
            ->paginate(15);

        return view('admin.vendors.index', compact('vendors'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        // Récupérer TOUS les utilisateurs du système, triés par nom
        $users = User::orderBy('name')->orderBy('email')->get();

        // OU si vous voulez filtrer par rôle spécifique :
        // $users = User::whereIn('role', ['vendor', 'admin'])->orderBy('nom')->orderBy('prenom')->get();

        $categories = Dish::$categoryLabels;

        return view('admin.vendors.create', compact('users', 'categories'));
    }

    /**
     * Enregistrement d'un nouveau vendeur + plats + images
     */
    public function store(Request $request)
    {
        // Log 1: Données brutes reçues
        Log::info('=== DÉBUT CRÉATION VENDEUR ===');
        Log::info('Données POST reçues:', $request->all());
        Log::info('Fichiers reçus:', $request->allFiles());

        $validated = $request->validate([
            'user_id'      => 'required|exists:users,id', // OBLIGATOIRE maintenant
            'name'         => 'required|string|max:255',
            'type'         => 'required|in:restaurant,maquis,street_vendor,market_stand,home_cook',
            'address'      => 'nullable|string|max:255',
            'city'         => 'nullable|string|max:100',
            'phone'        => 'nullable|string|max:20',
            'whatsapp'     => 'nullable|string|max:20',
            'description'  => 'nullable|string',
            'specialties'  => 'nullable|array',
            'specialties.*' => 'string|max:100',
            'logo'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            // Validation des plats (dynamique)
            'dishes'                  => 'nullable|array',
            'dishes.*.name'           => 'required_with:dishes|string|max:255',
            'dishes.*.name_local'     => 'nullable|string|max:255',
            'dishes.*.category'       => 'required_with:dishes|string|in:' . implode(',', array_keys(Dish::$categoryLabels)),
            'dishes.*.ethnic_origin'  => 'nullable|string|max:100',
            'dishes.*.region'         => 'nullable|string|max:100',
            'dishes.*.ingredients'    => 'nullable|string|max:1000',
            'dishes.*.description'    => 'nullable|string|max:1000',
            'dishes.*.price'          => 'required_with:dishes|numeric|min:0',
            'dishes.*.available'      => 'nullable|boolean',
            'dishes.*.notes'          => 'nullable|string|max:500',
            'dishes.*.images'         => 'nullable|string',
        ]);

        // Log 2: Données validées
        Log::info('Données validées:', $validated);

        try {
            DB::beginTransaction();
            Log::info('Transaction démarrée');

            // Logo
            if ($request->hasFile('logo')) {
                Log::info('Logo détecté, upload en cours...');
                $validated['logo'] = $request->file('logo')->store('vendors/logos', 'public');
                Log::info('Logo uploadé:', ['path' => $validated['logo']]);
            } else {
                Log::info('Aucun logo uploadé');
            }

            // Specialties
            $validated['specialties'] = $request->specialties ? json_encode($request->specialties) : null;
            Log::info('Specialties encodées:', ['specialties' => $validated['specialties']]);

            // Retirer les plats du tableau validated pour éviter l'erreur de masse assignment
            $dishesData = $validated['dishes'] ?? [];
            unset($validated['dishes']);

            // Créer le vendeur
            Log::info('Création du vendeur avec les données:', $validated);
            $vendor = Vendor::create($validated);
            Log::info('Vendeur créé avec succès:', ['vendor_id' => $vendor->id, 'vendor_name' => $vendor->name]);

            // Créer et associer les plats
            if (!empty($dishesData) && is_array($dishesData)) {
                Log::info('Nombre de plats à traiter:', ['count' => count($dishesData)]);

                foreach ($dishesData as $index => $dishData) {
                    Log::info("--- Traitement du plat #{$index} ---");
                    Log::info("Données du plat #{$index}:", $dishData);

                    // Vérification des champs obligatoires
                    if (empty($dishData['name']) || empty($dishData['category']) || empty($dishData['price'])) {
                        Log::warning("Plat #{$index} ignoré - champs manquants:", [
                            'name' => $dishData['name'] ?? 'MANQUANT',
                            'category' => $dishData['category'] ?? 'MANQUANT',
                            'price' => $dishData['price'] ?? 'MANQUANT',
                        ]);
                        continue;
                    }

                    // Préparer les ingrédients
                    $ingredients = [];
                    if (!empty($dishData['ingredients'])) {
                        if (is_string($dishData['ingredients'])) {
                            // Si c'est une chaîne, la convertir en tableau
                            $ingredients = array_map('trim', explode(',', $dishData['ingredients']));
                        } elseif (is_array($dishData['ingredients'])) {
                            $ingredients = $dishData['ingredients'];
                        }
                    }
                    Log::info("Ingrédients traités pour plat #{$index}:", $ingredients);

                    // Créer le plat
                    $dishCreateData = [
                        'name'           => $dishData['name'],
                        'name_local'     => $dishData['name_local'] ?? null,
                        'category'       => $dishData['category'],
                        'ethnic_origin'  => $dishData['ethnic_origin'] ?? null,
                        'region'         => $dishData['region'] ?? null,
                        'ingredients'    => $ingredients,
                        'description'    => $dishData['description'] ?? null,
                        'slug'           => Str::slug($dishData['name']) . '-' . uniqid(),
                    ];

                    Log::info("Création du plat #{$index} avec:", $dishCreateData);

                    try {
                        $dish = Dish::create($dishCreateData);
                        Log::info("Plat #{$index} créé avec succès:", ['dish_id' => $dish->id, 'dish_name' => $dish->name]);

                        // Pivot DishVendor
                        $pivotData = [
                            'price'     => $dishData['price'],
                            'available' => isset($dishData['available']) ? (bool)$dishData['available'] : true,
                            'notes'     => $dishData['notes'] ?? null,
                        ];

                        Log::info("Attachement du plat #{$index} au vendeur avec:", $pivotData);
                        $vendor->dishes()->attach($dish->id, $pivotData);
                        Log::info("Plat #{$index} attaché avec succès au vendeur");
                    } catch (\Exception $e) {
                        Log::error("Erreur lors de la création du plat #{$index}:", [
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString(),
                            'data' => $dishCreateData
                        ]);
                        throw $e;
                    }
                }

                Log::info('Tous les plats ont été traités');
            } else {
                Log::info('Aucun plat à créer');
            }

            DB::commit();
            Log::info('Transaction committée avec succès');
            Log::info('=== FIN CRÉATION VENDEUR (SUCCÈS) ===');

            return redirect()->route('admin.vendors.index')
                ->with('success', 'Vendeur et plats créés avec succès !');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('=== ERREUR LORS DE LA CRÉATION ===');
            Log::error('Message d\'erreur:', ['message' => $e->getMessage()]);
            Log::error('Fichier:', ['file' => $e->getFile()]);
            Log::error('Ligne:', ['line' => $e->getLine()]);
            Log::error('Trace complète:', ['trace' => $e->getTraceAsString()]);
            Log::error('=== FIN ERREUR ===');

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    /**
     * Affichage d'un vendeur
     */
    public function show(Vendor $vendor)
    {
        $vendor->load([
            'user',
            'dishes.images',
            'dishes' => fn($q) => $q->withCount('reviews')->withAvg('reviews', 'rating')
        ]);

        return view('admin.vendors.show', compact('vendor'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(Vendor $vendor)
    {
        $users = User::whereDoesntHave('vendor')
            ->orWhere('id', $vendor->user_id)
            ->orderBy('name')
            ->get();

        $categories = Dish::$categoryLabels;

        return view('admin.vendors.edit', compact('vendor', 'users', 'categories'));
    }

    /**
     * Mise à jour du vendeur
     */
    public function update(Request $request, Vendor $vendor)
    {
        $validated = $request->validate([
            'user_id'      => 'nullable|exists:users,id',
            'name'         => 'required|string|max:255',
            'type'         => 'required|in:restaurant,maquis,street_vendor,market_stand,home_cook',
            'address'      => 'nullable|string|max:255',
            'city'         => 'nullable|string|max:100',
            'phone'        => 'nullable|string|max:20',
            'whatsapp'     => 'nullable|string|max:20',
            'description'  => 'nullable|string',
            'specialties'  => 'nullable|array',
            'logo'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Logo
            if ($request->hasFile('logo')) {
                if ($vendor->logo) {
                    Storage::disk('public')->delete($vendor->logo);
                }
                $validated['logo'] = $request->file('logo')->store('vendors/logos', 'public');
            }

            $validated['specialties'] = isset($validated['specialties'])
                ? json_encode($validated['specialties'])
                : null;

            $vendor->update($validated);

            DB::commit();

            return redirect()->route('admin.vendors.index')
                ->with('success', 'Vendeur mis à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Suppression du vendeur
     */
    public function destroy(Vendor $vendor)
    {
        try {
            DB::beginTransaction();

            // Supprimer logo
            if ($vendor->logo) {
                Storage::disk('public')->delete($vendor->logo);
            }

            // Détacher plats (sans supprimer les plats eux-mêmes)
            $vendor->dishes()->detach();

            $vendor->delete();

            DB::commit();

            return redirect()->route('admin.vendors.index')
                ->with('success', 'Vendeur supprimé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
