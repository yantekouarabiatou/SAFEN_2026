<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FedaPayService
{
    private $baseUrl = 'https://api.fedapay.com';
    private $secretKey;
    private $publicKey;
    private $sandbox;

    public function __construct()
    {
        $this->secretKey = config('services.fedapay.secret_key');
        $this->publicKey = config('services.fedapay.public_key');
        $this->sandbox = config('services.fedapay.sandbox', true);
        
        if ($this->sandbox) {
            $this->baseUrl = 'https://sandbox-api.fedapay.com';
        }
    }

    /**
     * Créer une transaction FedaPay
     */
    public function createTransaction($orderNumber, $amount, $customerData, $description = null)
    {
        try {
            $payload = [
                'description' => $description ?? "Acompte commande #{$orderNumber}",
                'amount' => $amount,
                'currency' => ['iso' => 'XOF'], // Franc CFA
                'callback_url' => route('fedapay.callback'),
                'customer' => [
                    'firstname' => $customerData['firstname'] ?? '',
                    'lastname' => $customerData['lastname'] ?? '',
                    'email' => $customerData['email'],
                    'phone_number' => [
                        'number' => $customerData['phone'],
                        'country' => 'bj'
                    ]
                ],
                'custom_metadata' => [
                    'order_number' => $orderNumber,
                    'order_type' => 'deposit'
                ]
            ];

            $response = Http::withBasicAuth($this->secretKey, '')
                ->post("{$this->baseUrl}/v1/transactions", $payload);

            if ($response->failed()) {
                Log::error('FedaPay Transaction Error: ' . $response->body());
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la création de la transaction'
                ];
            }

            $transaction = $response->json()['data'] ?? null;

            if (!$transaction || !isset($transaction['id'])) {
                Log::error('FedaPay Transaction: Invalid response format');
                return [
                    'success' => false,
                    'message' => 'Réponse invalide du serveur FedaPay'
                ];
            }

            // Générer le token de paiement
            $tokenResponse = Http::withBasicAuth($this->secretKey, '')
                ->post("{$this->baseUrl}/v1/transactions/{$transaction['id']}/token");

            if ($tokenResponse->failed()) {
                Log::error('FedaPay Token Error: ' . $tokenResponse->body());
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la génération du token de paiement'
                ];
            }

            $token = $tokenResponse->json()['data'] ?? null;

            if (!$token || !isset($token['token'])) {
                Log::error('FedaPay Token: Invalid response format');
                return [
                    'success' => false,
                    'message' => 'Token invalide'
                ];
            }

            return [
                'success' => true,
                'transaction_id' => $transaction['id'],
                'token' => $token['token'],
                'url' => $token['url'] ?? null
            ];

        } catch (\Exception $e) {
            Log::error('FedaPay Transaction Error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Erreur lors de la création de la transaction: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Vérifier le statut d'une transaction
     */
    public function getTransaction($transactionId)
    {
        try {
            $response = Http::withBasicAuth($this->secretKey, '')
                ->get("{$this->baseUrl}/v1/transactions/{$transactionId}");

            if ($response->failed()) {
                Log::error('FedaPay Get Transaction Error: ' . $response->body());
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la récupération de la transaction'
                ];
            }

            $transaction = $response->json()['data'] ?? null;

            if (!$transaction) {
                Log::error('FedaPay Get Transaction: Invalid response format');
                return [
                    'success' => false,
                    'message' => 'Transaction non trouvée'
                ];
            }

            return [
                'success' => true,
                'status' => $transaction['status'] ?? null,
                'transaction' => $transaction
            ];
        } catch (\Exception $e) {
            Log::error('FedaPay Get Transaction Error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Vérifier la signature du webhook
     */
    public function verifyWebhookSignature($payload, $signature)
    {
        $secret = config('services.fedapay.webhook_secret');
        $computedSignature = hash_hmac('sha256', $payload, $secret);
        
        return hash_equals($computedSignature, $signature);
    }
}
