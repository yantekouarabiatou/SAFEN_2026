<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Dish;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $query = Vendor::query();

        // Filtres
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        if ($request->has('city') && $request->city) {
            $query->where('city', $request->city);
        }

        if ($request->has('specialty') && $request->specialty) {
            $query->whereJsonContains('specialties', $request->specialty);
        }

        // Géolocalisation
        if ($request->has('lat') && $request->has('lng')) {
            $lat = $request->lat;
            $lng = $request->lng;
            $radius = $request->radius ?? 5;

            $query->select('*')
                ->selectRaw(
                    "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance",
                    [$lat, $lng, $lat]
                )
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->having('distance', '<', $radius)
                ->orderBy('distance');
        }

        // Tri
        $sort = $request->sort ?? 'rating';
        switch ($sort) {
            case 'name':
                $query->orderBy('name');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'rating':
            default:
                $query->orderBy('rating_avg', 'desc');
                break;
        }

        $vendors = $query->paginate(20);

        $types = Vendor::distinct('type')->pluck('type');
        $cities = Vendor::distinct('city')->pluck('city');

        return view('vendors.index', compact('vendors', 'types', 'cities'));
    }

    public function show(Vendor $vendor)
    {
        $vendor->incrementViews();

        // Charger les plats
        $dishes = $vendor->dishes()->with('images')->paginate(12);

        // Vendeurs similaires
        $similarVendors = Vendor::where('type', $vendor->type)
            ->where('id', '!=', $vendor->id)
            ->where('city', $vendor->city)
            ->limit(4)
            ->get();

        return view('vendors.show', compact('vendor', 'dishes', 'similarVendors'));
    }

    public function create()
    {
        return view('vendors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'neighborhood' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'phone' => 'required|string',
            'whatsapp' => 'nullable|string',
            'email' => 'nullable|email',
            'specialties' => 'nullable|array',
            'opening_hours' => 'nullable|array'
        ]);

        if (auth()->check()) {
            $validated['user_id'] = auth()->id();
        }

        $validated['specialties'] = json_encode($validated['specialties'] ?? []);
        $validated['opening_hours'] = json_encode($validated['opening_hours'] ?? []);

        $vendor = Vendor::create($validated);

        return redirect()->route('vendors.show', $vendor)
            ->with('success', 'Votre profil vendeur a été créé avec succès !');
    }

    public function dishes(Vendor $vendor)
    {
        $dishes = $vendor->dishes()->with('images')->paginate(24);

        return view('vendors.dishes', compact('vendor', 'dishes'));
    }
}
