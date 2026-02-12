<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artisan;
use App\Models\Contact;
use App\Models\Dish;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use App\Models\Vendor;
use App\Models\CulturalEvent;
use App\Models\GuestOrder;
use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord principal.
     */
    public function index()
    {
        $user = Auth::user();

        // Logs de debug (optionnels, à retirer en production)
        Log::info('Accès au dashboard', [
            'user_id' => $user->id,
            'email'   => $user->email,
            'name'    => $user->name,
            'roles'   => $user->getRoleNames()->toArray(),
        ]);

        // Statistiques communes (tous les rôles)
        $commonStats = $this->getCommonStats();

        // Données spécifiques au rôle
        $roleSpecificData = $this->getRoleSpecificData($user);

        // Fusion des statistiques
        $stats = array_merge($commonStats, $roleSpecificData);

        // Données additionnelles pour la vue
        $viewData = [
            'stats'              => $stats,
            'user'              => $user,
            // Sécurisation : valeurs par défaut si non définies
            'pendingArtisans'   => $roleSpecificData['pendingArtisans']   ?? collect([]),
            'pendingProducts'   => $roleSpecificData['pendingProducts']   ?? collect([]),
            'unreadContacts'    => $roleSpecificData['unreadContacts']    ?? collect([]),
            'recentOrders'      => $roleSpecificData['recentOrders']      ?? collect([]),
            'registrationsChart'=> $roleSpecificData['registrationsChart']?? collect([]),
            'salesChart'        => $roleSpecificData['salesChart']        ?? collect([]),
        ];

        return view('admin.dashboard.index', $viewData);
    }

    /* ------------------------------------------------------------------
       STATISTIQUES COMMUNES (accessibles à tous les utilisateurs connectés)
    ------------------------------------------------------------------ */
    protected function getCommonStats(): array
    {
        // Ces stats peuvent être affichées sur le dashboard de n'importe quel rôle
        return [
            'total_orders'     => GuestOrder::count(),
            'pending_orders'   => GuestOrder::where('order_status', 'pending')->count() + GuestOrder::where('order_status', 'pending')->count(),
            'completed_orders' => GuestOrder::where('order_status', 'completed')->count() + GuestOrder::where('order_status', 'completed')->count(),
            'total_revenue'    => GuestOrder::where('order_status', 'completed')->sum('total_amount') + GuestOrder::where('order_status', 'completed')->sum('total_amount'),
            'unread_messages'  => Contact::where('status', 'unread')->count(),
            'total_reviews'    => Review::count(),
        ];
    }

    /* ------------------------------------------------------------------
       STATISTIQUES PAR RÔLE
    ------------------------------------------------------------------ */
    protected function getRoleSpecificData(User $user): array
    {
        if ($user->hasRole('super-admin')) {
            return $this->getSuperAdminStats();
        }

        if ($user->hasRole('admin')) {
            return $this->getAdminStats();
        }

        if ($user->hasRole('artisan')) {
            return $this->getArtisanStats($user);
        }

        if ($user->hasRole('vendor')) {
            return $this->getVendorStats($user);
        }

        // Par défaut : client ou tout autre rôle
        return $this->getClientStats($user);
    }

    /* ------------------------------------------------------------------
       SUPER ADMIN & ADMIN (statistiques globales)
    ------------------------------------------------------------------ */
    protected function getSuperAdminStats(): array
    {
        return [
            'total_users'        => User::count(),
            'new_users_today'    => User::whereDate('created_at', today())->count(),
            'new_users_week'     => User::where('created_at', '>=', now()->subDays(7))->count(),
            'total_artisans'     => Artisan::count(),
            'artisans_pending'   => Artisan::where('status', 'pending')->count(),
            'artisans_approved'  => Artisan::where('status', 'approved')->count(),
            'total_products'     => Product::count(),
            'products_pending'   => Product::where('status', 'pending')->count(),
            'total_vendors'      => Vendor::count(),
            'total_events'       => CulturalEvent::count(),
            'pending_quotes'     => Quote::where('status', 'pending')->count(),

            // Listes pour affichage rapide
            'pendingArtisans'    => Artisan::where('status', 'pending')->with('user')->latest()->take(8)->get(),
            'pendingProducts'    => Product::where('status', 'pending')->with('artisan')->latest()->take(8)->get(),
            'unreadContacts'     => Contact::where('read', false)->latest()->take(8)->get(),
            'recentOrders'       => Order::with('user')->latest()->take(10)->get(),

            // Graphiques
            'registrationsChart' => $this->getRegistrationsChart(),
            'salesChart'         => $this->getSalesChart(),
        ];
    }

    protected function getAdminStats(): array
    {
        // L'admin voit les mêmes stats que le super-admin, sauf peut-être quelques éléments sensibles
        // Ici on reprend les mêmes, mais vous pouvez filtrer si nécessaire
        return [
            'total_users'       => User::count(),
            'total_artisans'    => Artisan::where('status', 'approved')->count(),
            'total_products'    => Product::where('status', 'active')->count(),
            'total_vendors'     => Vendor::where('status', 'active')->count(),
            'total_dishes'      => Dish::where('status', 'active')->count(),
            'total_events'      => CulturalEvent::count(),
            'artisans_pending'  => Artisan::where('status', 'pending')->count(),
            'products_pending'  => Product::where('status', 'pending')->count(),

            'recentOrders'      => Order::with('user')->latest()->take(10)->get(),
            'newUsers'          => User::latest()->take(8)->get(),
            'popularProducts'   => Product::withCount('orderItems')->orderBy('views', 'desc')->take(8)->get(),
            'salesChart'        => $this->getSalesChart(),
        ];
    }

    /* ------------------------------------------------------------------
       ARTISAN
    ------------------------------------------------------------------ */
    protected function getArtisanStats(User $user): array
    {
        $artisan = $user->artisan;

        if (!$artisan) {
            return [
                'no_profile' => true,
                'message'    => 'Vous devez d\'abord créer votre profil artisan.',
            ];
        }

        return [
            'products_count'      => $artisan->products()->count(),
            'products_pending'    => $artisan->products()->where('status', 'pending')->count(),
            'orders_count'        => $artisan->orders()->count(),
            'pending_orders'      => $artisan->orders()->where('status', 'pending')->count(),
            'reviews_count'       => $artisan->reviews()->count(),
            'average_rating'      => round($artisan->reviews()->avg('rating'), 1) ?? 0,
            'total_revenue'       => $artisan->orders()->where('status', 'completed')->sum('total_amount'),
            'monthly_revenue'     => $artisan->orders()
                                    ->where('status', 'completed')
                                    ->whereMonth('created_at', now()->month)
                                    ->sum('total_amount'),

            'recentProducts'      => $artisan->products()->latest()->take(6)->get(),
            'recentOrders'        => $artisan->orders()->with('user')->latest()->take(6)->get(),
            'recentReviews'       => $artisan->reviews()->with('user')->latest()->take(6)->get(),
        ];
    }

    /* ------------------------------------------------------------------
       VENDEUR (Gastronomie)
    ------------------------------------------------------------------ */
    protected function getVendorStats(User $user): array
    {
        $vendor = $user->vendor;

        if (!$vendor) {
            return [
                'no_profile' => true,
                'message'    => 'Vous devez d\'abord créer votre profil vendeur.',
            ];
        }

        return [
            'dishes_count'      => $vendor->dishes()->count(),
            'orders_count'      => $vendor->orders()->count(),
            'pending_orders'    => $vendor->orders()->where('status', 'pending')->count(),
            'rating'            => round($vendor->reviews()->avg('rating'), 1) ?? 0,
            'total_revenue'     => $vendor->orders()->where('status', 'completed')->sum('total_amount'),
            'monthly_revenue'   => $vendor->orders()
                                    ->where('status', 'completed')
                                    ->whereMonth('created_at', now()->month)
                                    ->sum('total_amount'),

            'recentDishes'      => $vendor->dishes()->latest()->take(6)->get(),
            'recentOrders'      => $vendor->orders()->with('user')->latest()->take(6)->get(),
        ];
    }

    /* ------------------------------------------------------------------
       CLIENT
    ------------------------------------------------------------------ */
    protected function getClientStats(User $user): array
    {
        $recentViewsIds = session()->get('recent_views', []);

        return [
            'favorites_count'   => $user->favorites()->count(),
            'orders_count'      => $user->orders()->count(),
            'pending_orders'    => $user->orders()->where('order_status', 'pending')->count(),
            'reviews_count'     => $user->reviews()->count(),
            'recentViews'       => Product::whereIn('id', $recentViewsIds)->with('images')->take(6)->get(),
            'favorites'         => $user->favorites()->with('favoritable')->latest()->take(6)->get(),
            'recentOrders'      => $user->orders()->latest()->take(6)->get(),
        ];
    }

    /* ------------------------------------------------------------------
       GRAPHIQUES (Helpers)
    ------------------------------------------------------------------ */
    protected function getRegistrationsChart(int $days = 30)
    {
        $data = User::where('created_at', '>=', now()->subDays($days))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return $this->fillMissingDates($data, $days, 'count');
    }

    protected function getSalesChart(int $days = 30)
    {
        $data = Order::where('status', 'completed')
            ->where('created_at', '>=', now()->subDays($days))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return $this->fillMissingDates($data, $days);
    }

    /**
     * Remplit les dates manquantes dans les graphiques.
     */
    protected function fillMissingDates($collection, int $days = 30, string $valueField = 'total'): \Illuminate\Support\Collection
    {
        $dates = collect(range(0, $days - 1))
            ->map(fn($i) => now()->subDays($days - 1 - $i)->format('Y-m-d'));

        $dataByDate = $collection->keyBy('date');

        return $dates->map(function ($date) use ($dataByDate, $valueField) {
            $item = $dataByDate->get($date, (object) ['date' => $date]);
            $item->$valueField = $item->$valueField ?? 0;
            if ($valueField === 'total') {
                $item->count = $item->count ?? 0;
            }
            return $item;
        });
    }
}
