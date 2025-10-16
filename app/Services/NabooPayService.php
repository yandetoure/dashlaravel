<?php declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class NabooPayService
{
    private ?string $apiToken;
    private string $apiUrl;
    private int $timeout;

    public function __construct()
    {
        // Utiliser la même configuration que votre exemple existant
        $this->apiToken = config('services.naboopay.api_key') ?? config('naboopay.api_token');
        $this->apiUrl = config('services.naboopay.base_url') ?? config('naboopay.api_url', 'https://api.naboopay.com/api/v1');
        $this->timeout = config('naboopay.timeout', 30);
        
        // Vérifier que la configuration est correcte
        if (!$this->apiToken) {
            throw new \Exception('Token API NabooPay non configuré. Veuillez définir NABOOPAY_API_KEY dans votre fichier .env');
        }
    }

    /**
     * Créer une transaction de paiement
     */
    public function createTransaction(array $data): array
    {
        try {
            $payload = [
                'method_of_payment' => $data['payment_methods'] ?? ['WAVE', 'ORANGE_MONEY'],
                'products' => $data['products'],
                'success_url' => $data['success_url'] ?? config('naboopay.success_url'),
                'error_url' => $data['error_url'] ?? config('naboopay.error_url'),
                'is_escrow' => $data['is_escrow'] ?? false,
            ];

            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiToken,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])
                ->put($this->apiUrl . '/transaction/create-transaction', $payload);

            if ($response->successful()) {
                $responseData = $response->json();
                
                // Extraire l'URL de checkout de la réponse
                $checkoutUrl = null;
                if (isset($responseData['checkout_url'])) {
                    $checkoutUrl = $responseData['checkout_url'];
                } elseif (isset($responseData['payment_url'])) {
                    $checkoutUrl = $responseData['payment_url'];
                } elseif (isset($responseData['url'])) {
                    $checkoutUrl = $responseData['url'];
                } elseif (isset($responseData['data']['checkout_url'])) {
                    $checkoutUrl = $responseData['data']['checkout_url'];
                } elseif (isset($responseData['data']['payment_url'])) {
                    $checkoutUrl = $responseData['data']['payment_url'];
                }
                
                // Log pour debug
                Log::info('Transaction NabooPay créée avec succès', [
                    'checkout_url' => $checkoutUrl,
                    'transaction_id' => $responseData['transaction_id'] ?? $responseData['id'] ?? null,
                    'response_keys' => array_keys($responseData)
                ]);
                
                return [
                    'success' => true,
                    'data' => $responseData,
                    'checkout_url' => $checkoutUrl,
                    'transaction_id' => $responseData['transaction_id'] ?? $responseData['id'] ?? null
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'Erreur API NabooPay: ' . $response->body()
                ];
            }

        } catch (Exception $e) {
            Log::error('Erreur NabooPay createTransaction: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Erreur lors de la création de la transaction: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Récupérer les informations d'une transaction
     */
    public function getTransaction(string $transactionId): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiToken,
                    'Accept' => 'application/json'
                ])
                ->get($this->apiUrl . '/transaction/' . $transactionId);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'Erreur API NabooPay: ' . $response->body()
                ];
            }

        } catch (Exception $e) {
            Log::error('Erreur NabooPay getTransaction: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Erreur lors de la récupération de la transaction: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Récupérer l'historique des transactions
     */
    public function getTransactions(array $filters = []): array
    {
        try {
            $queryParams = http_build_query($filters);
            $url = $this->apiUrl . '/transaction/get-transactions';
            if ($queryParams) {
                $url .= '?' . $queryParams;
            }

            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiToken,
                    'Accept' => 'application/json'
                ])
                ->get($url);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'Erreur API NabooPay: ' . $response->body()
                ];
            }

        } catch (Exception $e) {
            Log::error('Erreur NabooPay getTransactions: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Erreur lors de la récupération des transactions: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Supprimer/Annuler une transaction
     */
    public function deleteTransaction(string $transactionId): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiToken,
                    'Accept' => 'application/json'
                ])
                ->delete($this->apiUrl . '/transaction/' . $transactionId);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'Erreur API NabooPay: ' . $response->body()
                ];
            }

        } catch (Exception $e) {
            Log::error('Erreur NabooPay deleteTransaction: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Erreur lors de la suppression de la transaction: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Récupérer les informations du compte
     */
    public function getAccountInfo(): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiToken,
                    'Accept' => 'application/json'
                ])
                ->get($this->apiUrl . '/account/info');

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'Erreur API NabooPay: ' . $response->body()
                ];
            }

        } catch (Exception $e) {
            Log::error('Erreur NabooPay getAccountInfo: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Erreur lors de la récupération des informations du compte: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Effectuer un cashout Wave
     */
    public function waveCashout(array $data): array
    {
        try {
            $payload = [
                'amount' => $data['amount'],
                'phone_number' => $data['phone_number'],
                'description' => $data['description'] ?? 'Retrait Wave'
            ];

            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiToken,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])
                ->post($this->apiUrl . '/cashout/wave', $payload);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'Erreur API NabooPay: ' . $response->body()
                ];
            }

        } catch (Exception $e) {
            Log::error('Erreur NabooPay waveCashout: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Erreur lors du cashout Wave: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Effectuer un cashout Orange Money
     */
    public function orangeMoneyCashout(array $data): array
    {
        try {
            $payload = [
                'amount' => $data['amount'],
                'phone_number' => $data['phone_number'],
                'description' => $data['description'] ?? 'Retrait Orange Money'
            ];

            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiToken,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])
                ->post($this->apiUrl . '/cashout/orange-money', $payload);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'Erreur API NabooPay: ' . $response->body()
                ];
            }

        } catch (Exception $e) {
            Log::error('Erreur NabooPay orangeMoneyCashout: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Erreur lors du cashout Orange Money: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Créer une transaction pour une réservation
     */
    public function createReservationTransaction($reservation): array
    {
        $trip = $reservation->trip;
        $client = $reservation->client;

        // Utiliser le champ 'tarif' pour le montant
        $amount = $reservation->tarif ?? $reservation->total_amount ?? 0;
        
        $products = [
            [
                'name' => 'Trajet ' . $trip->departure . ' - ' . $trip->destination,
                'category' => 'Transport',
                'amount' => (int) $amount, // Montant en XOF (pas de conversion en centimes pour NabooPay)
                'quantity' => 1,
                'description' => 'Réservation de transport - ' . $trip->departure . ' vers ' . $trip->destination . ' (' . $reservation->nb_personnes . ' personne(s))'
            ]
        ];

        // Utiliser HTTPS pour les URLs (requis par NabooPay)
        $baseUrl = config('app.url');
        if (strpos($baseUrl, 'http://') === 0) {
            $baseUrl = str_replace('http://', 'https://', $baseUrl);
        }
        
        $data = [
            'payment_methods' => ['WAVE', 'ORANGE_MONEY'],
            'products' => $products,
            'success_url' => $baseUrl . '/payment/success/' . $reservation->id,
            'error_url' => $baseUrl . '/payment/error/' . $reservation->id,
            'is_escrow' => true // Protection escrow pour les réservations
        ];

        return $this->createTransaction($data);
    }
}
