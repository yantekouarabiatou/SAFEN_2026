<?php

namespace App\Http\Controllers;

use App\Models\Artisan;
use Illuminate\Http\Request;

class ArtisanController extends Controller
{
    public function index(Request $request)
    {
        $query = Artisan::with(['user', 'photos'])
            ->where('visible', true);

        // Recherche texte
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filtre par métier
        if ($request->filled('craft')) {
            $query->where('craft', $request->craft);
        }

        // Filtre par ville
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        // Option : tri par proximité si coordonnées fournies
        if ($request->filled('latitude') && $request->filled('longitude')) {
            $lat = $request->latitude;
            $lng = $request->longitude;
            $radius = $request->input('radius', 50); // km, défaut 50

            $query->nearby($lat, $lng, $radius);
        } else {
            // Tri par défaut si pas de géoloc : les plus vus ou aléatoire
            $query->orderBy('views', 'desc');
        }

        $artisans = $query->paginate(12)->withQueryString(); // conserve les filtres dans la pagination

        // Pour les filtres du formulaire
        $crafts = Artisan::where('visible', true)
            ->select('craft')
            ->distinct()
            ->pluck('craft', 'craft')
            ->all();

        $cities = Artisan::where('visible', true)
            ->select('city')
            ->distinct()
            ->pluck('city', 'city')
            ->all();

        return view('artisans.vue', compact('artisans', 'crafts', 'cities'));
    }
    public function show(Artisan $artisan)
    {
        // Incrémente les vues
        $artisan->increment('views');

        // Charge les relations nécessaires
        $artisan->load([
            'user',
            'photos',
            'products.images',      // charge les images des produits
            'reviews.user'          // charge les avis + auteur
        ]);

        // Récupère des artisans similaires (même métier, même ville ou département proche)
        $similarArtisans = Artisan::where('id', '!=', $artisan->id)
            ->where(function ($query) use ($artisan) {
                $query->where('craft', $artisan->craft)
                    ->orWhere('city', $artisan->city);
            })
            ->where('visible', true)
            ->where('verified', true)           // optionnel : privilégier les vérifiés
            ->with('user', 'photos')            // pour afficher nom + photo
            ->take(4)                           // limite à 4 suggestions
            ->orderByRaw('RAND()')              // aléatoire pour variété
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
