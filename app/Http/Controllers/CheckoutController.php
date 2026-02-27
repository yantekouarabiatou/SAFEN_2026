<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\GuestOrder;
use App\Models\Product;
use App\Services\BeninCommunesService;
use App\Services\DeliveryService;
use App\Services\FedaPayService;
use App\Notifications\OrderPlacedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class CheckoutController extends Controller
{
    protected $fedaPayService;

    public function __construct(FedaPayService $fedaPayService)
    {
        $this->fedaPayService = $fedaPayService;
    }

    public function cancel()
    {
        return redirect()->route('cart.index')
            ->with('error', 'Le paiement a été annulé.');
    }

    public function index()
    {
        $cart = Cart::getOrCreateCart();

        if ($cart->items->count() === 0) {
            return redirect()->route('cart.index')
                ->with('error', 'Votre panier est vide.');
        }

        $cart->load(['items.product.images', 'items.product.artisan']);

        $subtotal    = $cart->total;
        $deliveryFee = 2000; // frais par défaut (sera recalculé via AJAX)
        $total       = $subtotal + $deliveryFee;

        $depositPercentage = 30;
        $depositAmount     = GuestOrder::calculateDeposit($total, $depositPercentage);
        $remainingAmount   = $total - $depositAmount;

        // ✅ Communes groupées par département pour Select2
        $communesGrouped = BeninCommunesService::getAllGrouped();

        return view('checkout.index', compact(
            'cart',
            'deliveryFee',
            'subtotal',
            'total',
            'depositAmount',
            'remainingAmount',
            'depositPercentage',
            'communesGrouped'   // ← nouveau
        ));
    }

    // ── Route AJAX pour recalculer les frais ──────────────────────────────────────
    public function calculateDelivery(Request $request)
    {
        $request->validate([
            'city'     => 'required|string',
            'subtotal' => 'required|numeric',
        ]);

        $commune     = $request->city;
        $subtotal    = (float) $request->subtotal;
        $deliveryFee = BeninCommunesService::getDeliveryFee($commune);
        $total       = $subtotal + $deliveryFee;
        $deposit     = GuestOrder::calculateDeposit($total, 30);
        $remaining   = $total - $deposit;

        return response()->json([
            'success'  => true,
            'delivery' => [
                'fee'                => $deliveryFee,
                'formatted_fee'      => number_format($deliveryFee, 0, ',', ' ') . ' FCFA',
                'estimated_delivery' => $this->getDeliveryEstimate($commune),
            ],
            'formatted_total'     => number_format($total,     0, ',', ' ') . ' FCFA',
            'formatted_deposit'   => number_format($deposit,   0, ',', ' ') . ' FCFA',
            'formatted_remaining' => number_format($remaining, 0, ',', ' '),
        ]);
    }

    private function getDeliveryEstimate(string $commune): string
    {
        $sameDay  = ['Cotonou'];
        $oneDay   = ['Abomey-Calavi', 'Sèmè-Podji', 'Porto-Novo', 'Ouidah'];
        $twoDays  = ['Bohicon', 'Abomey', 'Lokossa', 'Grand-Popo', 'Allada'];

        if (in_array($commune, $sameDay))  return 'Aujourd\'hui';
        if (in_array($commune, $oneDay))   return '1 jour ouvrable';
        if (in_array($commune, $twoDays))  return '2 jours ouvrables';
        return '3 à 5 jours ouvrables';
    }

    public function store(Request $request)
    {
        return $this->process($request);
    }
    /**
     * Calculer les frais de livraison en AJAX
     */

    public function process(Request $request)
    {
        $validated = $request->validate([
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
            'guest_phone' => 'required|string|max:20',
            'guest_address' => 'required|string|max:500',
            'guest_city' => 'required|string|max:100',
            'customer_notes' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:mtn_money,moov_money,bank_transfer,cash_on_delivery', // ← ICI
        ]);

        try {
            DB::beginTransaction();

            $cart = Cart::getOrCreateCart();

            if ($cart->items->count() === 0) {
                return back()->with('error', 'Votre panier est vide.');
            }

            // Calculer les frais de livraison
            $subtotal = $cart->total;
            $deliveryDetails = DeliveryService::getDeliveryDetails($validated['guest_city'], $subtotal);
            $deliveryFee = $deliveryDetails['fee'];
            $total = $subtotal + $deliveryFee;

            // Calculer l'acompte (30%)
            $depositPercentage = 30;
            $depositAmount = GuestOrder::calculateDeposit($total, $depositPercentage);
            $remainingAmount = $total - $depositAmount;

            // Préparer les items de la commande
            $orderItems = $cart->items->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'product_name_local' => $item->product->name_local,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'subtotal' => $item->quantity * $item->price,
                    'artisan_id' => $item->product->artisan_id,
                    'artisan_name' => $item->product->artisan->business_name ?? $item->product->artisan->user->name ?? 'N/A',
                    'image' => $item->product->images->first()->image_url ?? null,
                ];
            })->toArray();

            // Créer la commande
            $order = GuestOrder::create([
                'order_number' => GuestOrder::generateOrderNumber(),
                'user_id' => auth()->id(),
                'guest_name' => $validated['guest_name'],
                'guest_email' => $validated['guest_email'],
                'guest_phone' => $validated['guest_phone'],
                'guest_address' => $validated['guest_address'],
                'guest_city' => $validated['guest_city'],
                'guest_country' => 'Bénin',
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'delivery_distance' => $deliveryDetails['distance'],
                'delivery_estimate' => $deliveryDetails['estimated_delivery'],
                'total_amount' => $total,
                'deposit_amount' => $depositAmount,
                'deposit_percentage' => $depositPercentage,
                'remaining_amount' => $remainingAmount,
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'order_items' => $orderItems,
                'customer_notes' => $validated['customer_notes'] ?? null,
            ]);

            // Si paiement FedaPay, créer la transaction
            if ($validated['payment_method'] === 'fedapay') {
                // Séparer le nom en prénom et nom
                $nameParts = explode(' ', $validated['guest_name'], 2);

                $customerData = [
                    'firstname' => $nameParts[0] ?? '',
                    'lastname' => $nameParts[1] ?? '',
                    'email' => $validated['guest_email'],
                    'phone' => $validated['guest_phone']
                ];

                $fedaPayResult = $this->fedaPayService->createTransaction(
                    $order->order_number,
                    $depositAmount,
                    $customerData,
                    "Acompte commande AFRI-HERITAGE #{$order->order_number}"
                );

                if (!$fedaPayResult['success']) {
                    DB::rollBack();
                    return back()->withInput()
                        ->with('error', 'Erreur lors de la création du paiement: ' . $fedaPayResult['message']);
                }

                // Enregistrer les détails de la transaction
                $order->update([
                    'fedapay_transaction_id' => $fedaPayResult['transaction_id'],
                    'fedapay_token' => $fedaPayResult['token']
                ]);
            }

            // Envoyer les notifications
            $this->sendOrderNotifications($order);

            // Vider le panier
            $cart->items()->delete();
            $cart->updateTotals();

            DB::commit();

            // Redirection selon le mode de paiement
            if ($validated['payment_method'] === 'fedapay') {
                return redirect()->route('checkout.payment', $order)
                    ->with('success', 'Commande créée ! Procédez au paiement de l\'acompte.');
            } else {
                return redirect()->route('checkout.success', $order)
                    ->with('success', 'Commande enregistrée ! Vous paierez à la livraison.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur création commande: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return back()->withInput()
                ->with('error', 'Une erreur est survenue. Veuillez réessayer.');
        }
    }

    public function payment(GuestOrder $order)
    {
        if ($order->payment_method !== 'fedapay') {
            return redirect()->route('checkout.success', $order);
        }

        if (!$order->fedapay_token) {
            return redirect()->route('checkout.index')
                ->with('error', 'Token de paiement invalide.');
        }

        return view('checkout.payment', compact('order'));
    }

    /**
     * Callback FedaPay
     */
    public function fedapayCallback(Request $request)
    {
        try {
            $transactionId = $request->input('id');

            if (!$transactionId) {
                Log::error('FedaPay Callback: No transaction ID');
                return redirect()->route('home')
                    ->with('error', 'Transaction invalide.');
            }

            // Récupérer les détails de la transaction
            $result = $this->fedaPayService->getTransaction($transactionId);

            if (!$result['success']) {
                Log::error('FedaPay Callback Error: ' . $result['message']);
                return redirect()->route('home')
                    ->with('error', 'Erreur lors de la vérification du paiement.');
            }

            $transaction = $result['transaction'];
            $orderNumber = $transaction->custom_metadata['order_number'] ?? null;

            if (!$orderNumber) {
                Log::error('FedaPay Callback: No order number in metadata');
                return redirect()->route('home')
                    ->with('error', 'Commande non trouvée.');
            }

            $order = GuestOrder::where('order_number', $orderNumber)->first();

            if (!$order) {
                Log::error('FedaPay Callback: Order not found - ' . $orderNumber);
                return redirect()->route('home')
                    ->with('error', 'Commande non trouvée.');
            }

            // Mettre à jour le statut selon la transaction
            if ($transaction->status === 'approved') {
                $order->update([
                    'payment_status' => 'partial',
                    'payment_reference' => $transactionId,
                    'deposit_paid_at' => now(),
                    'order_status' => 'confirmed'
                ]);

                // Notification de confirmation
                $this->sendPaymentConfirmationNotification($order);

                return redirect()->route('checkout.success', $order)
                    ->with('success', 'Paiement réussi ! Votre commande est confirmée.');
            } elseif ($transaction->status === 'declined') {
                $order->update([
                    'payment_status' => 'failed',
                    'order_status' => 'cancelled'
                ]);

                return redirect()->route('checkout.index')
                    ->with('error', 'Le paiement a échoué. Veuillez réessayer.');
            }

            return redirect()->route('checkout.payment', $order)
                ->with('info', 'Paiement en cours de traitement...');
        } catch (\Exception $e) {
            Log::error('FedaPay Callback Exception: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return redirect()->route('home')
                ->with('error', 'Une erreur est survenue.');
        }
    }

    /**
     * Webhook FedaPay
     */
    public function fedapayWebhook(Request $request)
    {
        try {
            $payload = $request->getContent();
            $signature = $request->header('X-FedaPay-Signature');

            if (!$this->fedaPayService->verifyWebhookSignature($payload, $signature)) {
                Log::error('FedaPay Webhook: Invalid signature');
                return response()->json(['error' => 'Invalid signature'], 403);
            }

            $event = json_decode($payload, true);

            Log::info('FedaPay Webhook received', ['event' => $event]);

            if ($event['entity'] === 'transaction' && isset($event['object'])) {
                $transaction = $event['object'];
                $orderNumber = $transaction['custom_metadata']['order_number'] ?? null;

                if ($orderNumber) {
                    $order = GuestOrder::where('order_number', $orderNumber)->first();

                    if ($order) {
                        if ($transaction['status'] === 'approved') {
                            $order->update([
                                'payment_status' => 'partial',
                                'payment_reference' => $transaction['id'],
                                'deposit_paid_at' => now(),
                                'order_status' => 'confirmed'
                            ]);

                            $this->sendPaymentConfirmationNotification($order);
                        }
                    }
                }
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('FedaPay Webhook Exception: ' . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    private function sendOrderNotifications(GuestOrder $order)
    {
        try {
            // Notifier le client
            Notification::route('mail', $order->guest_email)
                ->notify(new OrderPlacedNotification($order, 'customer'));

            // Notifier les admins
            $admins = \App\Models\User::whereHas('roles', function ($q) {
                $q->where('name', 'admin');
            })->get();

            foreach ($admins as $admin) {
                $admin->notify(new OrderPlacedNotification($order, 'admin'));
            }

            // Notifier les artisans concernés
            $artisanIds = collect($order->order_items)
                ->pluck('artisan_id')
                ->filter()
                ->unique();

            foreach ($artisanIds as $artisanId) {
                $artisan = \App\Models\Artisan::find($artisanId);
                if ($artisan && $artisan->user) {
                    $artisan->user->notify(new OrderPlacedNotification($order, 'artisan'));
                }
            }

            Log::info('Notifications commande envoyées', ['order_number' => $order->order_number]);
        } catch (\Exception $e) {
            Log::error('Erreur envoi notifications: ' . $e->getMessage());
        }
    }

    private function sendPaymentConfirmationNotification(GuestOrder $order)
    {
        Notification::route('mail', $order->guest_email)
            ->notify(new \App\Notifications\PaymentReceivedNotification($order));
    }

    public function success(GuestOrder $order)
    {
        return view('checkout.success', compact('order'));
    }
}
