<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artisan;
use App\Models\ArtisanPhoto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ArtisanController extends Controller
{
    public function index(Request $request)
    {
        $query = Artisan::with(['user', 'photos', 'products']);

        // ── Recherche (nom / email utilisateur ou métier artisan) ──
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                    ->orWhere('craft', 'like', "%{$search}%")
                    ->orWhere('business_name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        // ── Filtre par métier (craft) ──────────────────────────────
        if ($request->filled('craft')) {
            $query->where('craft', $request->craft);
        }

        // ── Filtre par statut (colonne sur artisans, pas sur users) ─
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // ── Filtre par ville ───────────────────────────────────────
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        $artisans = $query->latest()->paginate(15);

        // Listes pour les selects — colonne "craft" et non "specialty"
        $crafts = Artisan::distinct()->pluck('craft')->filter()->sort()->values();
        $cities = Artisan::distinct()->pluck('city')->filter()->sort()->values();

        return view('admin.artisans.index', compact('artisans', 'crafts', 'cities'));
    }


    public function create()
    {
        // Récupère les utilisateurs qui n'ont PAS encore de profil artisan
        $users = User::doesntHave('artisan')->get();

        return view('admin.artisans.create', compact('users'));
    }

    public function store(Request $request)
    {
        $rules = [
            // Si création d'un nouvel utilisateur
            'user_id' => 'nullable|exists:users,id',
            'name'    => 'required_without:user_id|string|max:255',
            'email'   => 'required_without:user_id|email|unique:users,email',
            'phone'   => 'nullable|string|max:20',
            'password' => 'required_without:user_id|string|min:8|confirmed',
            // Champs artisan
            'craft'   => 'required|string|max:255',        // anciennement 'specialty'
            'years_experience' => 'nullable|integer|min:0', // anciennement 'experience_years'
            'city'    => 'nullable|string|max:255',        // anciennement 'location'
            'neighborhood' => 'nullable|string|max:255',
            'bio'     => 'nullable|string',
            'workshop_story' => 'nullable|string',
            'techniques' => 'nullable|string',
            'materials' => 'nullable|string',
            'certifications' => 'nullable|string',
            'availability' => 'nullable|in:available,busy,vacation',
            'accepts_custom_orders' => 'boolean',
            'photos.*' => 'nullable|image|max:2048',
        ];

        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
            // 1. Créer ou récupérer l'utilisateur
            if ($request->filled('user_id')) {
                $user = User::findOrFail($request->user_id);
            } else {
                $user = User::create([
                    'name'     => $request->name,
                    'email'    => $request->email,
                    'phone'    => $request->phone,
                    'password' => Hash::make($request->password),
                ]);
                $user->assignRole('artisan');
            }

            // 2. Créer le profil artisan
            $artisan = Artisan::create([
                'user_id'        => $user->id,
                'craft'          => $request->craft,
                'years_experience' => $request->years_experience,
                'city'           => $request->city,
                'neighborhood'   => $request->neighborhood,
                'bio'            => $request->bio,
                'workshop_story' => $request->workshop_story,
                'techniques'     => $request->techniques,
                'materials'      => $request->materials,
                'certifications' => $request->certifications,
                'availability'   => $request->availability ?? 'available',
                'accepts_custom_orders' => $request->boolean('accepts_custom_orders'),
                'status'         => 'pending', // Par défaut en attente de validation
            ]);

            // 3. Upload des photos
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $index => $photo) {
                    $path = $photo->store('artisans', 'public');
                    ArtisanPhoto::create([
                        'artisan_id' => $artisan->id,
                        'photo_url'  => $path,
                        'is_primary' => $index === 0,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.artisans.index')
                ->with('success', 'Artisan créé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Erreur : ' . $e->getMessage());
        }
    }
    public function show(Artisan $artisan)
    {
        $artisan->load(['user', 'photos', 'products.images', 'reviews']);
        return view('admin.artisans.show', compact('artisan'));
    }

    public function edit(Artisan $artisan)
    {
        $artisan->load(['user', 'photos']);
        return view('admin.artisans.edit', compact('artisan'));
    }

    public function update(Request $request, Artisan $artisan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $artisan->user_id,
            'phone' => 'nullable|string|max:20',
            'specialty' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'experience_years' => 'nullable|integer|min:0',
            'photos.*' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();

        try {
            // Mettre à jour l'utilisateur
            $artisan->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);

            // Mettre à jour l'artisan
            $artisan->update([
                'specialty' => $request->specialty,
                'bio' => $request->bio,
                'location' => $request->location,
                'experience_years' => $request->experience_years,
            ]);

            // Upload des nouvelles photos
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $path = $photo->store('artisans', 'public');
                    ArtisanPhoto::create([
                        'artisan_id' => $artisan->id,
                        'photo_url' => $path,
                        'is_primary' => false,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.artisans.index')
                ->with('success', 'Artisan mis à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
        }
    }

    public function destroy(Artisan $artisan)
    {
        try {
            // Supprimer les photos
            foreach ($artisan->photos as $photo) {
                Storage::disk('public')->delete($photo->photo_url);
            }

            $user = $artisan->user;
            $artisan->delete();
            $user->delete();

            return redirect()
                ->route('admin.artisans.index')
                ->with('success', 'Artisan supprimé avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    public function deletePhoto($photoId)
    {
        $photo = ArtisanPhoto::findOrFail($photoId);
        Storage::disk('public')->delete($photo->photo_url);
        $photo->delete();

        return response()->json(['success' => true]);
    }
}
