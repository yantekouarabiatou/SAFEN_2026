<?php

namespace App\Http\Controllers;

use App\Models\Artisan;
use App\Models\ChatLog;
use App\Models\Product;
use App\Models\Favorite;
use Illuminate\Container\Attributes\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $data = [];

        if ($user->hasRole('artisan')) {
            $artisan = $user->artisan;

            $data['stats'] = [
                'products' => $artisan->products()->count(),
                'views' => $artisan->views,
                'rating' => $artisan->rating_avg,
                'contacts' => 0, // À implémenter avec système de contact
            ];

            $data['recentProducts'] = $artisan->products()
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            $data['popularProducts'] = $artisan->products()
                ->orderBy('views', 'desc')
                ->limit(5)
                ->get();

            return view('dashboard.artisan', $data);
        }

        // Dashboard client
        $data['favorites'] = Favorite::where('user_id', $user->id)
            ->with(['favoritable'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $data['recentViews'] = Product::whereIn('id', session()->get('recently_viewed', []))
            ->with('images')
            ->limit(6)
            ->get();

        return view('dashboard.client', $data);
    }

    public function favorites()
    {
        $favorites = Favorite::where('user_id', auth()->id())
            ->with(['favoritable'])
            ->paginate(20);

        return view('dashboard.favorites', compact('favorites'));
    }

    public function requests()
    {
        // Historique des demandes de devis/contacts
        $requests = auth()->user()->requests()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('dashboard.requests', compact('requests'));
    }

    public function profile()
    {
        return view('dashboard.profile');
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'phone' => 'nullable|string',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $user = auth()->user();
        $user->update($validated);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
            $user->save();
        }

        return redirect()->route('dashboard.profile')
            ->with('success', 'Profil mis à jour avec succès !');
    }

    public function messages()
    {
        // Rediriger vers la page de messages principale
        return redirect()->route('messages.index');
    }
}
