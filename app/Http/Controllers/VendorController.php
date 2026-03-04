<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Dish;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * Liste des vendeurs avec filtres
     */
    public function index(Request $request)
    {
        $query = Vendor::withCount('dishes')
            ->withAvg('reviews', 'rating')
            ->with('user');

        // Filtres
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        if ($request->filled('specialty')) {
            $query->whereJsonContains('specialties', $request->specialty);
        }

        // Géolocalisation (recherche par proximité)
        if ($request->has('lat') && $request->has('lng')) {
            $lat = $request->lat;
            $lng = $request->lng;
            $radius = $request->radius ?? 20; // km

            $query->select('vendors.*')
                ->selectRaw(
                    "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) *
                      cos(radians(longitude) - radians(?)) + sin(radians(?)) *
                      sin(radians(latitude)))) AS distance",
                    [$lat, $lng, $lat]
                )
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->having('distance', '<=', $radius)
                ->orderBy('distance');
        }

        // Tri
        $sort = $request->input('sort', 'rating');
        switch ($sort) {
            case 'name':
                $query->orderBy('name');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'dishes':
                $query->orderBy('dishes_count', 'desc');
                break;
            case 'rating':
            default:
                $query->orderByDesc('reviews_avg_rating');
                break;
        }

        $vendors = $query->paginate(12)->withQueryString();

        // Données pour les filtres
        $types = Vendor::distinct('type')->pluck('type');
        $cities = Vendor::distinct('city')->pluck('city');

        return view('vendors.index', compact('vendors', 'types', 'cities'));
    }

    /**
     * Affichage d'un vendeur spécifique
     */ public function show(Vendor $vendor)
    {
        // Charger toutes les relations nécessaires
        $vendor->load([
            'user',
            'dishes.images',
            'reviews.user',
        ]);

        return view('vendors.show', compact('vendor'));
    }
    /**
     * Enregistrement d'un nouveau vendeur
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'type'          => 'required|string|max:100',
            'description'   => 'nullable|string',
            'address'       => 'required|string|max:255',
            'city'          => 'required|string|max:100',
            'neighborhood'  => 'nullable|string|max:100',
            'latitude'      => 'nullable|numeric|between:-90,90',
            'longitude'     => 'nullable|numeric|between:-180,180',
            'phone'         => 'required|string|max:20',
            'whatsapp'      => 'nullable|string|max:20',
            'opening_hours' => 'nullable|string|max:100',
        ]);

        // Associer à l'utilisateur connecté
        $validated['user_id'] = auth()->id();

        $vendor = Vendor::create($validated);

        return redirect()->route('vendors.show', $vendor)
            ->with('success', 'Votre profil vendeur a été créé avec succès !');
    }

    /**
     * Liste des plats d'un vendeur
     */
    public function dishes(Vendor $vendor)
    {
        $dishes = $vendor->dishes()
            ->with(['images', 'reviews'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->paginate(12);

        return view('vendors.dishes', compact('vendor', 'dishes'));
    }
}
