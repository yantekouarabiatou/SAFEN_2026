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

        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('specialty', 'like', "%{$search}%");
        }

        if ($request->filled('specialty')) {
            $query->where('specialty', $request->specialty);
        }

        if ($request->filled('status')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        $artisans = $query->latest()->paginate(15);
        
        $specialties = Artisan::distinct()->pluck('specialty');

        return view('admin.artisans.index', compact('artisans', 'specialties'));
    }

    public function create()
    {
        return view('admin.artisans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'specialty' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'experience_years' => 'nullable|integer|min:0',
            'photos.*' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();

        try {
            // Créer l'utilisateur
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password ?? 'password123'),
                'phone' => $request->phone,
            ]);

            // Assigner le rôle artisan
            $user->assignRole('artisan');

            // Créer l'artisan
            $artisan = Artisan::create([
                'user_id' => $user->id,
                'specialty' => $request->specialty,
                'bio' => $request->bio,
                'location' => $request->location,
                'experience_years' => $request->experience_years,
            ]);

            // Upload des photos
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $index => $photo) {
                    $path = $photo->store('artisans', 'public');
                    ArtisanPhoto::create([
                        'artisan_id' => $artisan->id,
                        'photo_url' => $path,
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
                ->with('error', 'Erreur lors de la création: ' . $e->getMessage());
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
