<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Vendor;
use Illuminate\Http\Request;

class GastronomieController extends Controller
{
    public function index(Request $request)
    {
        $query = Dish::with(['images', 'vendors']);

        // Filtre par catégorie
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // Filtre par région
        if ($request->filled('region')) {
            $query->byRegion($request->region);
        }

        // Recherche textuelle
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('name_local', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhereJsonContains('ingredients', $search);
            });
        }

        // Filtre par disponibilité de vendeurs
        if ($request->boolean('with_vendors')) {
            $query->has('vendors');
        }

        // Filtre par prix
        if ($request->filled('max_price')) {
            $query->whereHas('vendors', function($q) use ($request) {
                $q->where('dish_vendor.price', '<=', $request->max_price);
            });
        }

        // Tri
        switch ($request->input('sort', 'name')) {
            case 'popular':
                $query->popular();
                break;
            case 'newest':
                $query->latest();
                break;
            case 'price_low':
                $query->withAvailableVendors()
                    ->leftJoin('dish_vendor', 'dishes.id', '=', 'dish_vendor.dish_id')
                    ->select('dishes.*')
                    ->orderBy('dish_vendor.price', 'asc');
                break;
            case 'price_high':
                $query->withAvailableVendors()
                    ->leftJoin('dish_vendor', 'dishes.id', '=', 'dish_vendor.dish_id')
                    ->select('dishes.*')
                    ->orderBy('dish_vendor.price', 'desc');
                break;
            default:
                $query->orderBy('name');
        }

        $dishes = $query->paginate(12)->withQueryString();

        // Données pour les filtres
        $categories = array_keys(Dish::$categoryLabels);
        $regions = Dish::distinct('region')->pluck('region')->filter();

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
        $similarDishes = Dish::where(function($query) use ($dish) {
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
