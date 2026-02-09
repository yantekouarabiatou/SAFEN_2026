<?php

namespace App\Http\Controllers;

use App\Models\Artisan;
use App\Models\ChatLog;
use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Container\Attributes\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $data = [];

        // Vérifier si l'utilisateur est connecté
        if (!$user) {
            return redirect()->route('login');
        }

        // Dashboard artisan
        if ($user->role === 'artisan') {
            $artisan = $user->artisan;

            // Si l'artisan n'existe pas encore
            if (!$artisan) {
                return redirect()->route('artisans.create')
                    ->with('info', 'Veuillez compléter votre profil artisan.');
            }

            $data['stats'] = [
                'products' => $artisan->products()->count(),
                'views' => $artisan->views ?? 0,
                'rating' => $artisan->rating_avg ?? 0,
                'contacts' => $artisan->quotes()->where('status', 'pending')->count(),
            ];

            $data['recentProducts'] = $artisan->products()
                ->with('images')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            $data['popularProducts'] = $artisan->products()
                ->with('images')
                ->orderBy('views', 'desc')
                ->limit(5)
                ->get();

            return view('dashboard.artisan', $data);
        }

        // Dashboard client (rôle par défaut)
        $data['favorites'] = Favorite::where('user_id', $user->id)
            ->with(['favoritable'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Récupérer les produits récemment consultés
        $recentlyViewedIds = session()->get('recently_viewed', []);

        if (!empty($recentlyViewedIds)) {
            $data['recentViews'] = Product::whereIn('id', $recentlyViewedIds)
                ->with('images', 'artisan.user')
                ->limit(6)
                ->get();
        } else {
            $data['recentViews'] = collect();
        }

        return view('admin.dashboard.index', $data);
    }

    public function favorites()
    {
        $favorites = Favorite::where('user_id', auth()->id())
            ->with(['favoritable'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('dashboard.favorites', compact('favorites'));
    }

    public function requests()
    {
        // Historique des demandes de devis
        $requests = auth()->user()->quotes()
            ->with(['artisan.user', 'product'])
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
            'city' => 'nullable|string|max:100',
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

    public function artisan()
    {
        $user = auth()->user();
        $artisan = $user->artisan;

        $data['stats'] = [
            'products' => $artisan ? $artisan->products()->count() : 0,
            'views' => $artisan ? $artisan->views : 0,
            'rating' => $artisan ? $artisan->rating_avg : 0,
            'contacts' => 0,
        ];

        $data['recentProducts'] = $artisan ? $artisan->products()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get() : collect();

        $data['popularProducts'] = $artisan ? $artisan->products()
            ->orderBy('views', 'desc')
            ->limit(5)
            ->get() : collect();

        return view('dashboard.artisan', $data);
    }

    public function artisanProducts()
    {
        $user = auth()->user();
        $artisan = $user->artisan;

        $products = $artisan ? $artisan->products()->paginate(12) : collect();

        return view('dashboard.artisan-products', compact('products'));
    }

    public function artisanOrders()
    {
        $user = auth()->user();
        $artisan = $user->artisan;

        $orders = $artisan ? $artisan->orders()->orderBy('created_at', 'desc')->paginate(20) : collect();

        return view('dashboard.artisan-orders', compact('orders'));
    }

    public function artisanAnalytics()
    {
        $user = auth()->user();
        $artisan = $user->artisan;

        $data = [
            'totalViews' => $artisan ? $artisan->views : 0,
            'totalProducts' => $artisan ? $artisan->products()->count() : 0,
            'avgRating' => $artisan ? $artisan->rating_avg : 0,
        ];

        return view('dashboard.artisan-analytics', $data);
    }

    public function artisanReviews()
    {
        $user = auth()->user();
        $artisan = $user->artisan;

        $reviews = $artisan ? $artisan->reviews()->orderBy('created_at', 'desc')->paginate(20) : collect();

        return view('dashboard.artisan-reviews', compact('reviews'));
    }

    public function orders()
    {
        $orders = auth()->user()->orders()->orderBy('created_at', 'desc')->paginate(20);
        return view('dashboard.orders', compact('orders'));
    }

    public function messages()
    {
        $messages = auth()->user()->conversations()->orderBy('updated_at', 'desc')->paginate(20);
        return view('dashboard.messages', compact('messages'));
    }

    public function notifications()
    {
        $notifications = auth()->user()->notifications()->paginate(20);
        return view('dashboard.notifications', compact('notifications'));
    }

    public function settings()
    {
        return view('dashboard.settings');
    }

    public function vendor()
    {
        $user = auth()->user();
        $vendor = $user->vendor;

        $data = [
            'stats' => [
                'dishes' => $vendor ? $vendor->dishes()->count() : 0,
                'orders' => $vendor ? $vendor->orders()->count() : 0,
                'rating' => $vendor ? $vendor->rating_avg : 0,
            ],
        ];

        return view('dashboard.vendor', $data);
    }

    public function admin()
    {
        return view('dashboard.admin');
    }

    public function adminUsers()
    {
        return view('admin.users.index');
    }

    public function adminArtisans()
    {
        return view('admin.artisans.index');
    }

    public function adminProducts()
    {
        return view('admin.products.index');
    }

    public function adminOrders()
    {
        return view('admin.orders.index');
    }

    public function adminReviews()
    {
        return view('admin.reviews.index');
    }

    public function adminAnalytics()
    {
        return view('admin.analytics');
    }
}
