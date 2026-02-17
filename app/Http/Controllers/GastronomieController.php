<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Vendor;
use Illuminate\Http\Request;

class GastronomieController extends Controller
{

    public function index(Request $request)
    {
        // Builder de base avec relations essentielles (évite N+1)
        $query = Dish::query()
            ->with(['images' => fn($q) => $q->orderBy('order')]) // images triées
            ->withCount('vendors') // nombre de vendeurs (utile pour filtres et affichage)
            ->withMin('vendors', 'dish_vendor.price') // prix minimum
            ->withMax('vendors', 'dish_vendor.price'); // prix maximum

        // 1. Recherche textuelle (plus performante avec index si possible)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('name_local', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereJsonContains('ingredients', $search);
            });
        }

        // 2. Filtre par catégorie
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // 3. Filtre par région
        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }

        // 4. Filtre "avec vendeurs"
        if ($request->boolean('with_vendors')) {
            $query->has('vendors');
        }

        // 5. Filtre par prix maximum
        if ($request->filled('max_price') && is_numeric($request->max_price)) {
            $maxPrice = (float) $request->max_price;
            $query->whereHas('vendors', function ($q) use ($maxPrice) {
                $q->where('dish_vendor.price', '<=', $maxPrice);
            });
        }

        // 6. Tri (switch plus robuste)
        $sort = $request->input('sort', 'name');

        switch ($sort) {
            case 'popular':
                $query->orderBy('views', 'desc');
                break;

            case 'newest':
                $query->latest();
                break;

            case 'price_low':
                $query->orderBy('vendors_min_dish_vendor_price', 'asc');
                break;

            case 'price_high':
                $query->orderBy('vendors_max_dish_vendor_price', 'desc');
                break;

            case 'name':
            default:
                $query->orderBy('name');
                break;
        }

        // Pagination + conservation des paramètres GET
        $dishes = $query->paginate(10)->withQueryString();

        // Données pour les filtres (optimisé : pas de requêtes inutiles)
        $categories = array_keys(Dish::$categoryLabels ?? []);
        $regions = Dish::whereNotNull('region')
            ->distinct()
            ->orderBy('region')
            ->pluck('region')
            ->filter()
            ->values();

        return view('gastronomie.index', compact('dishes', 'categories', 'regions'));
    }

    public function show(Dish $dish)
    {
        $dish->load(['images', 'vendors.user']);

        // Incrémenter les vues
        $dish->increment('views');

        // Récupérer la géolocalisation de l'utilisateur si disponible
        $userLat = request()->input('lat');
        $userLng = request()->input('lng');

        // Vendeurs qui proposent ce plat
        $vendors = $dish->getNearbyVendors($userLat, $userLng, 50); // 50km de rayon

        // Plats similaires (même catégorie ou même région)
        $similarDishes = Dish::where(function ($query) use ($dish) {
            $query->where('category', $dish->category)
                ->orWhere('region', $dish->region);
        })
            ->where('id', '!=', $dish->id)
            ->with('images')
            ->inRandomOrder()
            ->limit(4)
            ->get();

        // Plats précédent et suivant
        $previousDish = Dish::where('id', '<', $dish->id)
            ->orderBy('id', 'desc')
            ->first();

        $nextDish = Dish::where('id', '>', $dish->id)
            ->orderBy('id', 'asc')
            ->first();

        return view('gastronomie.show', compact(
            'dish',
            'vendors',
            'similarDishes',
            'previousDish',
            'nextDish'
        ));
    }

    public function searchVendors(Request $request, Dish $dish)
    {
        $lat = $request->input('lat');
        $lng = $request->input('lng');
        $radius = $request->input('radius', 10); // Rayon par défaut: 10km

        $vendors = $dish->getNearbyVendors($lat, $lng, $radius);

        return response()->json([
            'success' => true,
            'count' => $vendors->count(),
            'vendors' => $vendors
        ]);
    }

    public function getVendorsByCity(Request $request, Dish $dish)
    {
        $city = $request->input('city');

        $vendors = $dish->vendors()
            ->wherePivot('available', true)
            ->where('city', $city)
            ->get();

        return response()->json([
            'success' => true,
            'count' => $vendors->count(),
            'vendors' => $vendors
        ]);
    }
}
