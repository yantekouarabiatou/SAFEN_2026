<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class FedaPayService
{
    protected string $secretKey;
    protected string $publicKey;
    protected string $environment;
    protected string $baseUrl;

    public function __construct()
    {
        $this->secretKey   = config('services.fedapay.secret_key');
        $this->publicKey   = config('services.fedapay.public_key');
        $this->environment = config('services.fedapay.environment', 'sandbox');
        $this->baseUrl     = $this->environment === 'sandbox'
            ? config('services.fedapay.sandbox_url')
            : config('services.fedapay.live_url');
    }

    // =========================================================================
    //  CRÉER UNE TRANSACTION
    // =========================================================================

    public function createTransaction(
        string $orderNumber,
        float  $amount,
        array  $customerData,
        string $description = ''
    ): array {
        try {
            $callbackUrl = route('checkout.fedapay.callback');
            $cancelUrl   = route('checkout.cancel');

            $payload = [
                'description'     => $description ?: "Commande #{$orderNumber}",
                'amount'          => (int) $amount,
                'currency'        => ['iso' => 'XOF'],
                'callback_url'    => $callbackUrl,
                'cancel_url'      => $cancelUrl,
                'custom_metadata' => ['order_number' => $orderNumber],
                'customer'        => [
                    'firstname' => $customerData['firstname'] ?? '',
                    'lastname'  => $customerData['lastname']  ?? '',
                    'email'     => $customerData['email']     ?? '',
                    'phone_number' => [
                        'number'  => $customerData['phone'] ?? '',
                        'country' => 'BJ',
                    ],
                ],
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type'  => 'application/json',
            ])->post("{$this->baseUrl}/transactions", $payload);

            if ($response->successful()) {
                $data        = $response->json();
                $transaction = $data['v1/transaction'] ?? $data['transaction'] ?? null;

                if (!$transaction) {
                    Log::error('FedaPay: structure de réponse inattendue', ['data' => $data]);
                    return ['success' => false, 'message' => 'Réponse FedaPay invalide.'];
                }

                // Générer le token de paiement
                $tokenResponse = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->secretKey,
                    'Content-Type'  => 'application/json',
                ])->post("{$this->baseUrl}/transactions/{$transaction['id']}/token");

                if ($tokenResponse->successful()) {
                    $tokenData = $tokenResponse->json();
                    $token     = $tokenData['token'] ?? null;

                    return [
                        'success'        => true,
                        'transaction_id' => $transaction['id'],
                        'token'          => $token,
                        'payment_url'    => "https://checkout" . ($this->environment === 'sandbox' ? '-sandbox' : '') . ".fedapay.com/checkout/v2/" . $token,
                    ];
                }

                return ['success' => false, 'message' => 'Impossible de générer le token de paiement.'];
            }

            $error = $response->json();
            Log::error('FedaPay createTransaction error', [
                'status' => $response->status(),
                'body'   => $error,
            ]);

            return [
                'success' => false,
                'message' => $error['message'] ?? 'Erreur FedaPay inconnue.',
            ];

        } catch (\Exception $e) {
            Log::error('FedaPay Exception: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Erreur de connexion à FedaPay.'];
        }
    }

    // =========================================================================
    //  RÉCUPÉRER UNE TRANSACTION
    // =========================================================================

    public function getTransaction(string|int $transactionId): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type'  => 'application/json',
            ])->get("{$this->baseUrl}/transactions/{$transactionId}");

            if ($response->successful()) {
                $data        = $response->json();
                $transaction = $data['v1/transaction'] ?? $data['transaction'] ?? null;

                if ($transaction) {
                    return ['success' => true, 'transaction' => (object) $transaction];
                }
            }

            return ['success' => false, 'message' => 'Transaction introuvable.'];

        } catch (\Exception $e) {
            Log::error('FedaPay getTransaction Exception: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // =========================================================================
    //  VÉRIFIER SIGNATURE WEBHOOK
    // =========================================================================

    public function verifyWebhookSignature(string $payload, ?string $signature): bool
    {
        if (!$signature) return false;

        $secret   = config('services.fedapay.webhook_secret');
        $computed = 'sha256=' . hash_hmac('sha256', $payload, $secret);

        return hash_equals($computed, $signature);
    }

    // =========================================================================
    //  INFOS ENVIRONNEMENT (utile pour debug)
    // =========================================================================

    public function isSandbox(): bool
    {
        return $this->environment === 'sandbox';
    }

    public function getPublicKey(): string
    {
        return $this->publicKey;
    }
}
