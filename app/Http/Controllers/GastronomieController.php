<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Vendor;
use Illuminate\Http\Request;

class GastronomieController extends Controller
{
    public function index(Request $request)
    {
        $query = Dish::with('images');

        // Filtres par catégorie
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        // Filtres par région
        if ($request->has('region') && $request->region) {
            $query->where('region', $request->region);
        }

        // Recherche
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('name_local', 'like', "%$search%");
            });
        }

        // Tri
        $sort = $request->sort ?? 'name';
        switch ($sort) {
            case 'popular':
                $query->orderBy('views', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'name':
            default:
                $query->orderBy('name');
                break;
        }

        $dishes = $query->paginate(12);

        $categories = Dish::distinct('category')->pluck('category');
        $regions = Dish::distinct('region')->pluck('region');

        return view('gastronomie.index', compact('dishes', 'categories', 'regions'));
    }

    public function show(Dish $dish)
    {
        $dish->load(['images', 'vendors']);

        // Incrémenter les vues
        $dish->increment('views');

        // Plats similaires
        $similarDishes = Dish::where('category', $dish->category)
            ->where('id', '!=', $dish->id)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        // Vendeurs proches (géolocalisation)
        $nearbyVendors = $dish->vendors()
            ->when(request()->has('lat') && request()->has('lng'), function($query) {
                $lat = request()->lat;
                $lng = request()->lng;
                return $query->select('*')
                    ->selectRaw(
                        "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance",
                        [$lat, $lng, $lat]
                    )
                    ->orderBy('distance');
            })
            ->limit(10)
            ->get();

        return view('gastronomie.show', compact('dish', 'similarDishes', 'nearbyVendors'));
    }
}
