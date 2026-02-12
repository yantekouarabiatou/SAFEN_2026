<?php

namespace App\Http\Controllers;

use App\Models\Artisan;
use App\Models\ArtisanPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ArtisanController extends Controller
{
    public function index(Request $request)
    {
        $query = Artisan::with(['user', 'photos'])
            ->where('status', 'approved') // Seulement les approuvés
            ->where('visible', true); // Et visibles

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

            $query->whereNotNull('latitude')
                  ->whereNotNull('longitude')
                  ->selectRaw("*, (6371 * acos(cos(radians($lat)) * cos(radians(latitude)) * cos(radians(longitude) - radians($lng)) + sin(radians($lat)) * sin(radians(latitude)))) AS distance")
                  ->having('distance', '<', $radius)
                  ->orderBy('distance');
        } else {
            // Tri par défaut si pas de géoloc
            $query->orderBy('views', 'desc');
        }

        $artisans = $query->paginate(12)->withQueryString();

        return view('artisans.vue', compact('artisans')); // Changé de 'vue' à 'index'
    }

    public function show(Artisan $artisan)
    {
        // Vérifier si l'artisan est approuvé OU si l'utilisateur est l'auteur/admin
        if (!$artisan->isApproved()) {
            if (!Auth::check() || (Auth::id() !== $artisan->user_id && !Auth::user()->isAdmin())) {
                abort(404, 'Cet artisan n\'est pas encore approuvé ou n\'existe pas.');
            }
        }

        // Incrémente les vues seulement si approuvé
        if ($artisan->isApproved()) {
            $artisan->increment('views');
        }

        // Charge les relations nécessaires
        $artisan->load([
            'user',
            'photos',
            'products.images',
            'reviews.user'
        ]);

        // Récupère des artisans similaires
        $similarArtisans = Artisan::where('id', '!=', $artisan->id)
            ->where('status', 'approved')
            ->where('visible', true)
            ->where(function ($query) use ($artisan) {
                $query->where('craft', $artisan->craft)
                    ->orWhere('city', $artisan->city);
            })
            ->with('user', 'photos')
            ->take(4)
            ->inRandomOrder()
            ->get();

        return view('artisans.show', compact('artisan', 'similarArtisans'));
    }

    public function create()
    {
        // Vérifier si l'utilisateur a déjà un profil artisan
        if (Auth::user()->artisan) {
            return redirect()->route('artisans.edit', Auth::user()->artisan)
                ->with('info', 'Vous avez déjà un profil artisan. Vous pouvez le modifier ici.');
        }

        return view('artisans.create');
    }

    public function store(Request $request)
    {
        try {
            // Vérifier si l'utilisateur a déjà un profil artisan
            if (Auth::user()->artisan) {
                return redirect()->route('artisans.edit', Auth::user()->artisan)
                    ->with('error', 'Vous avez déjà un profil artisan.');
            }

            $validated = $request->validate([
                'business_name' => 'required|string|max:255',
                'craft' => 'required|string|in:forgeron,tisserand,potier,sculpteur,bijoutier,cordonnier,menuisier,autre',
                'description' => 'required|string|min:20|max:2000',
                'specialties' => 'nullable|string|max:500',
                'experience_years' => 'nullable|string|in:1-2,3-5,6-10,10+',
                'city' => 'required|string|max:100',
                'district' => 'nullable|string|max:100',
                'postal_code' => 'nullable|string|max:10',
                'address' => 'nullable|string|max:255',
                'phone' => 'required|string|max:20|regex:/^[0-9\s\-\+\(\)]{8,20}$/',
                'email' => 'nullable|email|max:255',
                'visible' => 'sometimes|boolean',
                'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            ], [
                'phone.regex' => 'Le numéro de téléphone n\'est pas valide.',
                'craft.in' => 'Veuillez sélectionner un métier valide.',
            ]);

            // Préparation des données
            $artisanData = [
                'user_id' => Auth::id(),
                'business_name' => $validated['business_name'],
                'craft' => $validated['craft'],
                'bio' => $validated['description'],
                'specialties' => $validated['specialties'] ?? null,
                'years_experience' => $this->parseExperienceYears($validated['experience_years'] ?? null),
                'city' => $validated['city'],
                'neighborhood' => $validated['district'] ?? null,
                'address' => $validated['address'] ?? null,
                'postal_code' => $validated['postal_code'] ?? null,
                'phone' => $validated['phone'],
                'visible' => $request->boolean('visible', true),
                'status' => 'pending', // Par défaut en attente
                'verified' => false,
                'featured' => false,
                'views' => 0,
            ];

            // Mettre à jour l'email de l'utilisateur si fourni
            if (!empty($validated['email'])) {
                Auth::user()->update(['email' => $validated['email']]);
            }

            // Créer l'artisan
            $artisan = Artisan::create($artisanData);

            // Traitement des photos
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $index => $photo) {
                    if ($photo->isValid()) {
                        // Générer un nom de fichier unique
                        $fileName = time() . '_' . uniqid() . '_' . $index . '.' . $photo->getClientOriginalExtension();

                        // Stocker l'image
                        $path = $photo->storeAs('artisans', $fileName, 'public');

                        // Créer l'entrée dans la table artisan_photos
                        ArtisanPhoto::create([
                            'artisan_id' => $artisan->id,
                            'photo_url' => $path,
                            'caption' => null,
                            'order' => $index,
                        ]);
                    }
                }
            }

            // Redirection avec message de succès
            return redirect()->route('artisans.index')
                ->with('success', 'Votre profil a été soumis avec succès ! Il sera examiné par notre équipe et vous serez notifié une fois approuvé.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in ArtisanController@store: ' . json_encode($e->errors()));
            return back()->withInput()->withErrors($e->errors());

        } catch (\Exception $e) {
            Log::error('Erreur création artisan: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            return back()->withInput()->with('error', 'Une erreur est survenue lors de la création du profil. Veuillez réessayer. Erreur: ' . $e->getMessage());
        }
    }

    public function edit(Artisan $artisan)
    {
        // Vérifier que l'utilisateur a le droit de modifier ce profil
        if (Auth::id() !== $artisan->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier ce profil.');
        }

        return view('artisans.edit', compact('artisan'));
    }

    public function update(Request $request, Artisan $artisan)
    {
        // Vérifier que l'utilisateur a le droit de modifier ce profil
        if (Auth::id() !== $artisan->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier ce profil.');
        }

        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'craft' => 'required|string',
            'bio' => 'nullable|string',
            'years_experience' => 'nullable|integer|min:0',
            'city' => 'required|string',
            'neighborhood' => 'nullable|string',
            'address' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'phone' => 'required|string',
            'email' => 'nullable|email',
            'visible' => 'sometimes|boolean',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // Mettre à jour les données de base
        $artisan->update([
            'business_name' => $validated['business_name'],
            'craft' => $validated['craft'],
            'bio' => $validated['bio'],
            'years_experience' => $validated['years_experience'],
            'city' => $validated['city'],
            'neighborhood' => $validated['neighborhood'],
            'address' => $validated['address'],
            'postal_code' => $validated['postal_code'],
            'phone' => $validated['phone'],
            'visible' => $request->boolean('visible', $artisan->visible),
        ]);

        // Mettre à jour l'email de l'utilisateur si fourni
        if (!empty($validated['email'])) {
            Auth::user()->update(['email' => $validated['email']]);
        }

        // Traitement des nouvelles photos
        if ($request->hasFile('photos')) {
            $currentPhotoCount = $artisan->photos()->count();

            foreach ($request->file('photos') as $index => $photo) {
                if ($photo->isValid()) {
                    // Générer un nom de fichier unique
                    $fileName = time() . '_' . uniqid() . '_' . ($currentPhotoCount + $index) . '.' . $photo->getClientOriginalExtension();

                    // Stocker l'image
                    $path = $photo->storeAs('artisans', $fileName, 'public');

                    // Créer l'entrée dans la table artisan_photos
                    ArtisanPhoto::create([
                        'artisan_id' => $artisan->id,
                        'photo_url' => $path,
                        'caption' => $request->input("captions.$index"),
                        'order' => $currentPhotoCount + $index,
                    ]);
                }
            }
        }

        return redirect()->route('artisans.show', $artisan)
            ->with('success', 'Votre profil a été mis à jour avec succès !');
    }

    public function destroy(Artisan $artisan)
    {
        // Vérifier que l'utilisateur a le droit de supprimer ce profil
        if (Auth::id() !== $artisan->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Vous n\'êtes pas autorisé à supprimer ce profil.');
        }

        // Supprimer les photos associées
        foreach ($artisan->photos as $photo) {
            // Supprimer le fichier physique
            Storage::disk('public')->delete($photo->photo_url);
            // Supprimer l'entrée en base
            $photo->delete();
        }

        $artisan->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Votre profil artisan a été supprimé.');
    }

    // Méthodes d'administration
    public function pendingList()
    {
        $this->authorize('manage-artisans'); // Assurez-vous d'avoir cette politique

        $pendingArtisans = Artisan::where('status', 'pending')
            ->with(['user', 'photos'])
            ->latest()
            ->paginate(20);

        return view('admin.artisans.pending', compact('pendingArtisans'));
    }

    public function approve(Artisan $artisan)
    {
        $this->authorize('manage-artisans');

        $artisan->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        // Optionnel : envoyer une notification à l'artisan
        // $artisan->user->notify(new ArtisanApproved($artisan));

        return back()->with('success', 'Artisan approuvé avec succès.');
    }

    public function reject(Request $request, Artisan $artisan)
    {
        $this->authorize('manage-artisans');

        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $artisan->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason,
            'rejected_at' => now(),
        ]);

        return back()->with('success', 'Artisan rejeté avec succès.');
    }

    /**
     * Convertir le range d'années d'expérience en entier
     */
    private function parseExperienceYears($experienceRange)
    {
        if (!$experienceRange) {
            return null;
        }

        switch ($experienceRange) {
            case '1-2':
                return 1;
            case '3-5':
                return 3;
            case '6-10':
                return 6;
            case '10+':
                return 10;
            default:
                return null;
        }
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
