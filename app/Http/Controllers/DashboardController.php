<?php

namespace App\Http\Controllers;

use App\Models\Artisan;
use App\Models\Product;
use App\Models\Favorite;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
     public function index()
    {
        $user = auth()->user();
        $data = [];

        // ✅ ADMIN - Utilise la méthode isAdmin() que vous avez déjà
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // ✅ ARTISAN - Utilise la méthode isArtisan() que vous avez déjà
        if ($user->isArtisan()) {
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

        // ✅ VENDOR - Vérifier le rôle vendor
        if ($user->role === 'vendor') {
            return redirect()->route('dashboard.vendor');
        }

        // ✅ CLIENT (par défaut)
        $data['favorites'] = Favorite::where('user_id', $user->id)
            ->with(['favoritable'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $data['recentViews'] = Product::whereIn('id', session()->get('recently_viewed', []))
            ->with('images')
            ->limit(6)
            ->get();

        $data['orders'] = $user->orders()->orderBy('created_at', 'desc')->limit(5)->get();
        $data['messages'] = $user->conversations()->with('lastMessage')->limit(5)->get();
        // $data['notifications'] = $user->notifications()->limit(5)->get();
        $data['notifications'] = collect([]);

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
