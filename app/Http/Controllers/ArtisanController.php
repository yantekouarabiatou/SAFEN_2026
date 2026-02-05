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

        return view('artisans.vue', compact('artisans'));
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
            'description' => 'required|string',
            'specialties' => 'nullable|string',
            'experience_years' => 'nullable|string',
            'city' => 'required|string',
            'district' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'required|string',
            'email' => 'nullable|email',
            'visible' => 'boolean',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        // Mapping des champs du formulaire vers les champs de la base de données
        $artisanData = [
            'user_id' => auth()->id(),
            'business_name' => $validated['business_name'],
            'craft' => $validated['craft'],
            'bio' => $validated['description'], // description -> bio
            'years_experience' => $this->parseExperienceYears($validated['experience_years']), // experience_years -> years_experience
            'city' => $validated['city'],
            'neighborhood' => $validated['district'], // district -> neighborhood
            'phone' => $validated['phone'],
            'visible' => $request->boolean('visible', true),
        ];

        // Ajouter l'email à l'utilisateur si fourni
        if (!empty($validated['email'])) {
            $user = auth()->user();
            $user->update(['email' => $validated['email']]);
        }

        $artisan = Artisan::create($artisanData);

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

    public function edit(Artisan $artisan)
    {
        // Vérifier que l'utilisateur a le droit de modifier ce profil
        if (auth()->id() !== $artisan->user_id && !auth()->user()->isAdmin()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier ce profil.');
        }

        return view('artisans.edit', compact('artisan'));
    }

    public function update(Request $request, Artisan $artisan)
    {
        // Vérifier que l'utilisateur a le droit de modifier ce profil
        if (auth()->id() !== $artisan->user_id && !auth()->user()->isAdmin()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier ce profil.');
        }

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

        $validated['languages_spoken'] = json_encode($validated['languages_spoken'] ?? []);

        $artisan->update($validated);

        // Traitement des nouvelles photos
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $photo) {
                $path = $photo->store('artisans/portfolio', 'public');

                $artisan->photos()->create([
                    'photo_url' => $path,
                    'caption' => $request->input("captions.$index"),
                    'order' => $artisan->photos()->count() + $index,
                ]);
            }
        }

        return redirect()->route('artisans.show', $artisan)
            ->with('success', 'Votre profil a été mis à jour avec succès !');
    }

    public function destroy(Artisan $artisan)
    {
        // Vérifier que l'utilisateur a le droit de supprimer ce profil
        if (auth()->id() !== $artisan->user_id && !auth()->user()->isAdmin()) {
            abort(403, 'Vous n\'êtes pas autorisé à supprimer ce profil.');
        }

        $artisan->delete();

        return redirect()->route('dashboard.artisan')
            ->with('success', 'Votre profil artisan a été supprimé.');
    }
}
