<?php

namespace App\Http\Controllers;

use App\Models\Artisan;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArtisanController extends Controller
{
    public function index(Request $request)
    {
        $query = Artisan::with(['user', 'photos'])
            ->where('visible', true);

        // Filtres
        if ($request->has('craft') && $request->craft) {
            $query->where('craft', $request->craft);
        }

        if ($request->has('city') && $request->city) {
            $query->where('city', $request->city);
        }

        if ($request->has('rating') && $request->rating) {
            $query->where('rating_avg', '>=', $request->rating);
        }

        // Géolocalisation
        if ($request->has('lat') && $request->has('lng')) {
            $lat = $request->lat;
            $lng = $request->lng;
            $radius = $request->radius ?? 10;

            $query->select('*')
                ->selectRaw(
                    "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance",
                    [$lat, $lng, $lat]
                )
                ->having('distance', '<', $radius)
                ->orderBy('distance');
        } else {
            // Tri par défaut
            $sort = $request->sort ?? 'rating';
            switch ($sort) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'name':
                    $query->orderBy('business_name');
                    break;
                case 'rating':
                default:
                    $query->orderBy('rating_avg', 'desc');
                    break;
            }
        }

        // Vue (grille, liste, carte)
        $view = $request->view ?? 'grid';

        $artisans = $query->paginate(20);

        // Données pour les filtres
        $crafts = Artisan::distinct('craft')->pluck('craft');
        $cities = Artisan::distinct('city')->pluck('city');

        return view('artisans.index', compact('artisans', 'crafts', 'cities', 'view'));
    }

    public function show(Artisan $artisan)
    {
        if (!$artisan->visible && !auth()->check()) {
            abort(404);
        }

        $artisan->load(['user', 'photos', 'products' => function($query) {
            $query->where('stock_status', '!=', 'out_of_stock');
        }]);

        // Incrémenter les vues
        $artisan->increment('views');

        // Artisans similaires
        $similarArtisans = Artisan::where('craft', $artisan->craft)
            ->where('id', '!=', $artisan->id)
            ->where('visible', true)
            ->orderBy('rating_avg', 'desc')
            ->limit(4)
            ->get();

        return view('artisans.show', compact('artisan', 'similarArtisans'));
    }

    public function create()
    {
        return view('artisans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'craft' => 'required|string',
            'bio' => 'nullable|string',
            'years_experience' => 'nullable|integer|min:0',
            'city' => 'required|string',
            'neighborhood' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'whatsapp' => 'required|string',
            'phone' => 'nullable|string',
            'languages_spoken' => 'nullable|array',
            'pricing_info' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['languages_spoken'] = json_encode($validated['languages_spoken'] ?? []);

        $artisan = Artisan::create($validated);

        // Traitement des photos
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $photo) {
                $path = $photo->store('artisans/portfolio', 'public');

                $artisan->photos()->create([
                    'photo_url' => $path,
                    'caption' => $request->input("captions.$index"),
                    'order' => $index,
                ]);
            }
        }

        return redirect()->route('artisans.show', $artisan)
            ->with('success', 'Votre profil artisan a été créé avec succès !');
    }
}
