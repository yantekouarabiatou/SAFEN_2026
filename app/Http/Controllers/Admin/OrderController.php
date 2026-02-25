<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GuestOrder;
use App\Models\Order;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    /**
     * Affiche la liste des commandes.
     */
    public function index(Request $request)
    {
        // Si c'est une requête AJAX pour DataTables
        if ($request->ajax()) {
            return $this->getOrdersData($request);
        }
        
        // Sinon, afficher la vue normale avec pagination
        // Support both regular orders and guest orders. Prefer regular `Order` model.
        $query = Order::query()->with('user');
        
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        $orders = $query->orderByDesc('created_at')->paginate(15);
        
        return view('admin.orders.index', compact('orders'));
    }
    
    /**
     * Récupère les données pour DataTables
     */
    private function getOrdersData(Request $request)
    {
        $query = Order::with('user');

        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        return DataTables::of($query)
            ->addColumn('customer', function ($order) {
                return $order->user->name ?? ($order->guest_name ?? 'Client invité');
            })
            ->addColumn('order_number', function ($order) {
                return '<a href="' . route('admin.orders.show', $order->id) . '">#' . $order->order_number . '</a>';
            })
            ->addColumn('total', function ($order) {
                // Use formatted total if available
                return $order->formatted_total ?? ($order->getFormattedTotalAttribute() ?? number_format($order->total ?? 0, 0, ',', ' ') . ' FCFA');
            })
            ->addColumn('order_status', function ($order) {
                return $this->getStatusBadge($order->status ?? $order->order_status);
            })
            ->addColumn('payment_status', function ($order) {
                return $this->getPaymentStatusBadge($order->payment_status ?? $order->payment_status);
            })
            ->addColumn('created_at', function ($order) {
                return $order->created_at ? $order->created_at->format('d/m/Y H:i') : '';
            })
            ->addColumn('action', function ($order) {
                return '
                    <a href="' . route('admin.orders.show', $order->id) . '" class="btn btn-sm btn-info" title="Voir">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="' . route('admin.orders.edit', $order->id) . '" class="btn btn-sm btn-warning" title="Modifier">
                        <i class="fas fa-edit"></i>
                    </a>
                ';
            })
            ->rawColumns(['order_number', 'order_status', 'payment_status', 'action'])
            ->make(true);
    }

    /**
     * Retourne le badge HTML pour le statut de commande.
     */
    public function getStatusBadge($status)
    {
        $badgeClass = match ($status) {
            'pending'    => 'badge-warning',   // jaune
            'confirmed'  => 'badge-success',   // vert
            'processing' => 'badge-success',   // vert
            'shipped'    => 'badge-success',   // vert
            'delivered'  => 'badge-success',   // vert
            'cancelled'  => 'badge-danger',    // rouge
            default      => 'badge-secondary'
        };

        $labels = [
            'pending'    => 'En attente',
            'confirmed'  => 'Confirmée',
            'processing' => 'En préparation',
            'shipped'    => 'Expédiée',
            'delivered'  => 'Livrée',
            'cancelled'  => 'Annulée',
        ];

        $label = $labels[$status] ?? $status;

        return '<span class="badge ' . $badgeClass . '">' . $label . '</span>';
    }

    /**
     * Badge pour le statut de paiement.
     */
    public function getPaymentStatusBadge($status)
    {
        $badgeClass = match ($status) {
            'pending' => 'badge-warning',
            'partial' => 'badge-info',
            'paid'    => 'badge-success',
            'failed'  => 'badge-danger',
            default   => 'badge-secondary'
        };

        $labels = [
            'pending' => 'En attente',
            'partial' => 'Acompte versé',
            'paid'    => 'Payée',
            'failed'  => 'Échouée',
        ];

        return '<span class="badge ' . $badgeClass . '">' . ($labels[$status] ?? $status) . '</span>';
    }

    public function show($id)
    {
        // Try to find as regular Order first, fallback to GuestOrder
        $order = Order::with('user')->find($id) ?? GuestOrder::with('user')->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function edit($id)
    {
        $order = Order::find($id) ?? GuestOrder::findOrFail($id);
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::find($id) ?? GuestOrder::findOrFail($id);
        
        $validated = $request->validate([
            'order_status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,partial,paid,failed',
            'admin_notes' => 'nullable|string',
        ]);

        // Map request fields to model fields (Order uses 'status')
        if ($order instanceof Order && isset($validated['order_status'])) {
            $validated['status'] = $validated['order_status'];
            unset($validated['order_status']);
        }

        $order->update($validated);

        return redirect()->route('admin.orders.index')->with('success', 'Commande mise à jour.');
    }

    public function validateOrder($id)
    {
        $order = Order::find($id) ?? GuestOrder::findOrFail($id);
        if ($order instanceof Order) {
            $order->status = 'confirmed';
        } else {
            $order->order_status = 'confirmed';
        }
        $order->save();

        return redirect()->back()->with('success', 'Commande validée.');
    }

    public function rejectOrder($id)
    {
        $order = Order::find($id) ?? GuestOrder::findOrFail($id);
        if ($order instanceof Order) {
            $order->status = 'cancelled';
        } else {
            $order->order_status = 'cancelled';
        }
        $order->save();

        return redirect()->back()->with('success', 'Commande refusée/annulée.');
    }
}