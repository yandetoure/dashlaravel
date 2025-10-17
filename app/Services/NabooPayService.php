<?php declare(strict_types=1); 

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;
use App\Models\User;

class NabooPayService
{
    protected $baseUrl;
    protected $apiKey;
    protected $returnUrl;
    protected $cashoutUrl;
    protected $webhookUrl;

    public function __construct()
    {
        $this->apiKey = config('services.naboopay.api_key');
        $this->baseUrl = config('services.naboopay.base_url', 'https://api.naboopay.com/api/v1');
        $this->returnUrl = config('services.naboopay.return_url');
        $this->cashoutUrl = 'https://api.naboopay.com/api/v1/cashout/wave';
        $this->webhookUrl = config('services.naboopay.webhook_url');
    }

    /**
     * Normalise un numéro de téléphone sénégalais pour qu'il commence par '+221'.
     *
     * @param string|null $phoneNumber
     * @return string|null
     */
    public function normalizePhoneNumber(?string $phoneNumber): ?string
    {
        if (empty($phoneNumber)) {
            return null;
        }

        // Supprime tous les espaces du numéro
        $cleanedPhoneNumber = str_replace(' ', '', $phoneNumber);

        // Vérifie si le numéro commence par "+221"
        if (!str_starts_with($cleanedPhoneNumber, '+221')) {
            // Si ce n'est pas le cas, ajoute "+221"
            // Supprime un éventuel '0' initial avant d'ajouter le préfixe
            return '+221' . ltrim($cleanedPhoneNumber, '0');
        }

        // Si ça commence déjà par "+221", utilise-le tel quel
        return $cleanedPhoneNumber;
    }

    public function createTransaction(array $transactionData)
    {
        Log::info('NabooPay - Envoi de la requête de création de transaction', [
            'url' => $this->baseUrl . '/transaction/create-transaction',
            'data' => $transactionData
        ]);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->put($this->baseUrl . '/transaction/create-transaction', $transactionData);

        $responseData = $response->json();
        
        Log::info('NabooPay - Réponse reçue', [
            'status' => $response->status(),
            'response' => $responseData,
            'headers' => $response->headers()
        ]);

        if (!$response->successful()) {
            Log::error('NabooPay - Erreur lors de la création de transaction', [
                'status' => $response->status(),
                'response' => $responseData,
                'body' => $response->body()
            ]);
        }

        return $responseData;
    }

    public function getTransactions()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->get($this->baseUrl . '/transaction/get-transactions');

        return $response->json();
    }

    public function deleteTransaction(string $orderId)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->delete($this->baseUrl . '/transaction/delete-transaction', [
            'order_id' => $orderId,
        ]);

        return $response->json();
    }

    public function getTransactionStatus(string $orderId)
    {
        Log::info('NabooPay - Vérification du statut de transaction', [
            'order_id' => $orderId
        ]);

        // Tester plusieurs endpoints possibles pour le statut
        $endpoints = [
            '/transaction/' . $orderId,
            '/transaction/get-transaction',
            '/transaction/status',
            '/transactions/' . $orderId,
            '/payment/status'
        ];

        foreach ($endpoints as $endpoint) {
            try {
                Log::info('NabooPay - Test endpoint statut: ' . $endpoint);
                
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])->get($this->baseUrl . $endpoint);

                Log::info('NabooPay - Réponse endpoint statut ' . $endpoint, [
                    'status_code' => $response->status(),
                    'response' => $response->json()
                ]);

                if ($response->successful()) {
                    Log::info('NabooPay - Succès avec endpoint statut: ' . $endpoint);
                    return $response->json();
                }
            } catch (\Exception $e) {
                Log::warning('NabooPay - Erreur endpoint statut ' . $endpoint, [
                    'error' => $e->getMessage()
                ]);
                continue;
            }
        }

        // Si aucun endpoint ne fonctionne, essayer avec POST
        Log::info('NabooPay - Test POST pour statut');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post($this->baseUrl . '/transaction/get-transaction', [
            'order_id' => $orderId,
        ]);

        Log::info('NabooPay - Réponse POST statut', [
            'status_code' => $response->status(),
            'response' => $response->json()
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Impossible de récupérer le statut de la transaction. Order ID: ' . $orderId);
    }

    /**
     * Récupérer les informations du compte et le solde
     */
    public function getAccountInfo()
    {
        try {
            Log::info('NabooPay - Tentative de récupération des informations du compte', [
                'api_key' => substr($this->apiKey, 0, 10) . '...',
                'base_url' => $this->baseUrl
            ]);

            // Selon la documentation NabooPay, l'endpoint /account/ doit être appelé avec GET
            Log::info('NabooPay - Test GET /account/ (selon documentation officielle)');
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->get($this->baseUrl . '/account/');

            Log::info('NabooPay - Réponse GET /account/', [
                'status_code' => $response->status(),
                'response' => $response->json(),
                'headers' => $response->headers()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('NabooPay - Succès avec GET /account/', [
                    'data' => $data
                ]);
                return $data;
            }

            // Essayer aussi sans le slash final
            Log::info('NabooPay - Test GET /account (sans slash final)');
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->get($this->baseUrl . '/account');

            Log::info('NabooPay - Réponse GET /account', [
                'status_code' => $response->status(),
                'response' => $response->json()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('NabooPay - Succès avec GET /account', [
                    'data' => $data
                ]);
                return $data;
            }

            // Si POST ne fonctionne pas, essayer d'autres endpoints avec GET
            $endpoints = [
                '/account/info',
                '/account/balance',
                '/merchant/account',
                '/user/account',
                '/balance',
                '/wallet'
            ];

            foreach ($endpoints as $endpoint) {
                try {
                    Log::info('NabooPay - Test endpoint GET: ' . $endpoint);
                    
                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $this->apiKey,
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json'
                    ])->get($this->baseUrl . $endpoint);

                    Log::info('NabooPay - Réponse endpoint ' . $endpoint, [
                        'status_code' => $response->status(),
                        'response' => $response->json(),
                        'headers' => $response->headers()
                    ]);

                    if ($response->successful()) {
                        $data = $response->json();
                        Log::info('NabooPay - Succès avec endpoint: ' . $endpoint, [
                            'data' => $data
                        ]);
                        return $data;
                    }
                } catch (\Exception $e) {
                    Log::warning('NabooPay - Échec endpoint ' . $endpoint, [
                        'error' => $e->getMessage()
                    ]);
                    continue;
                }
            }

            // Dernière tentative : essayer de calculer le solde à partir des transactions
            Log::info('NabooPay - Tentative de calcul du solde à partir des transactions');
            try {
                $transactions = $this->getTransactions();
                if (isset($transactions['data']) && is_array($transactions['data'])) {
                    $balance = 0;
                    foreach ($transactions['data'] as $transaction) {
                        if (isset($transaction['transaction_status']) && $transaction['transaction_status'] ==='SUCCESS') {
                            $balance += $transaction['amount'] ?? 0;
                        }
                    }
                    
                    Log::info('NabooPay - Solde calculé à partir des transactions', [
                        'balance' => $balance,
                        'transactions_count' => count($transactions['data'])
                    ]);
                    
                    return [
                        'balance' => $balance,
                        'account_id' => 'calculated_from_transactions',
                        'currency' => 'XOF',
                        'status' => 'active',
                        'calculated' => true
                    ];
                }
            } catch (\Exception $transactionError) {
                Log::warning('NabooPay - Impossible de calculer le solde à partir des transactions', [
                    'error' => $transactionError->getMessage()
                ]);
            }

            throw new \Exception('Aucun endpoint ne fonctionne pour récupérer les informations du compte. Dernière réponse: ' . $response->body());
            
        } catch (\Exception $e) {
            Log::error('NabooPay - Erreur lors de la récupération des informations du compte', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Gère la notification de webhook de Naboopay.
     * C'est ici que l'état du paiement est mis à jour dans la DB et le cashout est déclenché.
     *
     * @param array $data Les données reçues du webhook de Naboopay.
     * @return array Résultat du traitement du webhook (succès/erreur).
     */
    public function handleWebhook(array $data): array
    {
        Log::info("NabooPayService: Webhook Naboopay reçu:", $data);

        $invoiceToken = $data["order_id"] ?? null;
        $status = $data["transaction_status"] ?? null;

        if (empty($invoiceToken) || empty($status)) {
            Log::error("NabooPayService: Champs obligatoires manquants dans les données du webhook Naboopay. Données reçues:", $data);
            return ["success" => false, "error" => "Champs obligatoires manquants."];
        }

        // Tente de trouver la transaction par le numéro de transaction de Naboopay ou par l'ID de transaction Laravel
        $transaction = Transaction::where('order_id', $invoiceToken)
                                ->orWhere('id', $invoiceToken)
                                ->first();

        if (!$transaction) {
            Log::error("NabooPayService: Transaction non trouvée pour le jeton Naboopay/référence : " . $invoiceToken);
            return ["success" => false, "error" => "Transaction non trouvée."];
        }

        $transaction->load('user');
        
        $userPhoneNumber = null;
        $userFullName = null;
        $amountPaid = $data['amount'] ?? $transaction->amount;

        // Tente de charger les informations de l'utilisateur pour le cashout
        try {
            if ($transaction->user) {
                $user = $transaction->user;
                $rawPhoneNumber = $user->phone_number;
                $userPhoneNumber = $this->normalizePhoneNumber($rawPhoneNumber);
                $userFullName = $user->first_name . ' ' . $user->last_name;
                Log::info("NabooPayService: Données de l'utilisateur pour cashout: Téléphone brut=" . ($rawPhoneNumber ?? 'N/A') . ", Normalisé=" . ($userPhoneNumber ?? 'N/A') . ", Nom=" . $userFullName);
            } else {
                Log::warning("NabooPayService: Impossible de récupérer l'utilisateur pour la transaction ID: " . $transaction->id . ". Cashout non tenté.");
            }
        } catch (\Exception $e) {
            Log::error("NabooPayService: Erreur lors de la récupération des infos de l'utilisateur pour le cashout: " . $e->getMessage(), ['exception' => $e, 'transaction_id' => $transaction->id]);
            $userPhoneNumber = null;
            $userFullName = null;
        }

        switch ($status) {
            case "paid":
            case "completed":
            case "success":
            case "done":
                $transaction->transaction_status = "SUCCESS";
                $transaction->save();
                Log::info("NabooPayService: Paiement terminé ('" . $status . "') pour l'ID: " . $transaction->id . ". Mise à jour de la DB.");

                // --- DÉCLENCHEMENT DU CASHOUT VERS L'UTILISATEUR ---
                if ($userPhoneNumber && $userFullName && $amountPaid > 0) {
                    Log::info("NabooPayService: Tentative de cashout vers " . $userPhoneNumber . " pour le montant " . $amountPaid . " XOF.");
                    $cashoutResult = $this->performCashout($userPhoneNumber, $amountPaid, $userFullName);

                    if ($cashoutResult['success']) {
                        Log::info("NabooPayService: Cashout vers Wave réussi pour la transaction " . $transaction->id . ". Réponse: ", $cashoutResult);
                        return ["success" => true, "message" => "Paiement mis à jour et cashout initié.", "cashout_status" => $cashoutResult['status']];
                    } else {
                        Log::error("NabooPayService: Échec du cashout vers Wave pour la transaction " . $transaction->id . ". Erreur: " . $cashoutResult['error'] . ". Réponse: ", $cashoutResult['response'] ?? []);
                        return ["success" => true, "message" => "Paiement mis à jour, mais cashout échoué.", "cashout_error" => $cashoutResult['error']];
                    }
                } else {
                    Log::warning("NabooPayService: Conditions de cashout non remplies pour la transaction " . $transaction->id . ". Numéro, nom ou montant manquant/nul. (Numéro: " . ($userPhoneNumber ?? 'N/A') . ", Nom: " . ($userFullName ?? 'N/A') . ", Montant: " . $amountPaid . "). Cashout non tenté.");
                    return ["success" => true, "message" => "Paiement mis à jour, mais cashout non tenté (données manquantes)."];
                }
                break;

            case "failed":
                $transaction->transaction_status = "FAILED";
                Log::info("NabooPayService: Paiement échoué pour l'ID: " . $transaction->id . ". Mise à jour de la DB.");
                $transaction->save();
                break;

            case "pending":
                $transaction->transaction_status = "PENDING";
                Log::info("NabooPayService: Paiement en attente pour l'ID: " . $transaction->id . ". Mise à jour de la DB.");
                $transaction->save();
                break;

            case "cancel":
            case "cancelled":
                $transaction->transaction_status = "CANCELLED";
                Log::info("NabooPayService: Paiement annulé pour l'ID: " . $transaction->id . ". Mise à jour de la DB.");
                $transaction->save();
                break;

            default:
                Log::warning("NabooPayService: Statut Naboopay inconnu: " . $status . " pour l'ID de transaction: " . $transaction->id);
                return ["success" => false, "error" => "Statut Naboopay inconnu."];
        }

        return ["success" => true, "message" => "Paiement mis à jour avec succès."];
    }

    /**
     * Effectue un appel à l'API de cashout de Naboopay pour transférer des fonds.
     *
     * @param string $phoneNumber Le numéro de téléphone Wave du destinataire (format +221XXXXXXXXX).
     * @param float $amount Le montant à décaisser en XOF (sera converti en entier si nécessaire).
     * @param string $fullName Le nom complet du titulaire du compte Wave.
     * @return array Résultat de l'opération de cashout.
     */
    public function performCashout(string $phoneNumber, float $amount, string $fullName): array
    {
        $cashoutData = [
            "full_name" => $fullName,
            "amount" => (int) $amount, // L'API Wave attend un entier (minor units, ex: 50000 pour 50000 XOF)
            "phone_number" => $phoneNumber
        ];

        $headers = [
            "Authorization" => "Bearer " . $this->apiKey,
            "Content-Type" => "application/json",
            "Accept" => "application/json"
        ];

        Log::info("NabooPayService: Envoi de la requête de cashout à Naboopay:", $cashoutData);

        try {
            $response = Http::withHeaders($headers)->post($this->cashoutUrl, $cashoutData);
            $jsonResponse = $response->json();

            Log::info("NabooPayService: Réponse de l'API Cashout Naboopay:", $jsonResponse);

            if ($response->successful()) {
                // L'API de cashout renvoie un statut (pending, paid, done)
                $cashoutStatus = $jsonResponse['status'] ?? 'unknown';
                if (in_array($cashoutStatus, ['pending', 'paid', 'done'])) { // 'paid' et 'done' sont des statuts de succès finaux ou intermédiaires
                    return ["success" => true, "status" => $cashoutStatus, "response" => $jsonResponse];
                } else {
                    return ["success" => false, "error" => "Statut de cashout inattendu de Naboopay: " . $cashoutStatus, "response" => $jsonResponse];
                }
            } else {
                return ["success" => false, "error" => "Erreur API Cashout (" . $response->status() . "): " . ($jsonResponse['message'] ?? 'Erreur inconnue'), "response" => $jsonResponse];
            }
        } catch (\Exception $e) {
            Log::error("NabooPayService: Exception lors de l'appel à l'API de cashout: " . $e->getMessage(), ['exception' => $e]);
            return ["success" => false, "error" => "Exception lors du cashout: " . $e->getMessage()];
        }
    }

    /**
     * Effectuer un cashout vers Wave (méthode publique pour compatibilité)
     */
    public function cashOutToWave($amount, $phoneNumber)
    {
        try {
            $normalizedPhone = $this->normalizePhoneNumber($phoneNumber);
            if (!$normalizedPhone) {
                throw new \Exception('Numéro de téléphone invalide');
            }

            $result = $this->performCashout($normalizedPhone, (float)$amount, 'Utilisateur');
            
            if ($result['success']) {
                return $result['response'] ?? ['status' => 'success'];
            } else {
                throw new \Exception($result['error']);
            }
        } catch (\Exception $e) {
            Log::error('NabooPay - Erreur cashout Wave', [
                'error' => $e->getMessage(),
                'amount' => $amount,
                'phone' => $phoneNumber
            ]);
            throw $e;
        }
    }

    /**
     * Effectuer un cashout vers Orange Money
     */
    public function cashOutToOrangeMoney($amount, $phoneNumber)
    {
        try {
            $data = [
                'amount' => (int) $amount,
                'phone_number' => $phoneNumber
            ];

            Log::info('NabooPay - Tentative de cashout Orange Money', [
                'url' => $this->baseUrl . '/cashout',
                'data' => $data
            ]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->post($this->baseUrl . '/cashout', $data);

            Log::info('NabooPay - Réponse cashout Orange Money', [
                'status_code' => $response->status(),
                'response' => $response->json()
            ]);

            if ($response->successful()) {
                return $response->json();
            } else {
                throw new \Exception('Erreur lors du cashout Orange Money: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('NabooPay - Erreur cashout Orange Money', [
                'error' => $e->getMessage(),
                'amount' => $amount,
                'phone' => $phoneNumber
            ]);
            throw $e;
        }
    }

    // Méthodes pour compatibilité avec l'ancien système
    public function waveCashout(array $data): array
    {
        try {
            $normalizedPhone = $this->normalizePhoneNumber($data['phone_number']);
            if (!$normalizedPhone) {
                throw new \Exception('Numéro de téléphone invalide');
            }

            $result = $this->performCashout($normalizedPhone, (float)$data['amount'], $data['full_name'] ?? 'Utilisateur');
            
            if ($result['success']) {
                return [
                    'success' => true,
                    'data' => $result['response']
                ];
            } else {
                return [
                    'success' => false,
                    'error' => $result['error']
                ];
            }
        } catch (\Exception $e) {
            Log::error('NabooPay - Erreur waveCashout', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            return [
                'success' => false,
                'error' => 'Erreur lors du cashout Wave: ' . $e->getMessage()
            ];
        }
    }

    public function orangeMoneyCashout(array $data): array
    {
        try {
            $normalizedPhone = $this->normalizePhoneNumber($data['phone_number']);
            if (!$normalizedPhone) {
                throw new \Exception('Numéro de téléphone invalide');
            }

            $result = $this->cashOutToOrangeMoney($data['amount'], $normalizedPhone);
            
            return [
                'success' => true,
                'data' => $result
            ];
        } catch (\Exception $e) {
            Log::error('NabooPay - Erreur orangeMoneyCashout', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            return [
                'success' => false,
                'error' => 'Erreur lors du cashout Orange Money: ' . $e->getMessage()
            ];
        }
    }

    // Méthodes pour compatibilité avec les réservations
    public function createReservationTransaction($reservation): array
    {
        try {
            $trip = $reservation->trip;
            $amount = $reservation->tarif ?? $reservation->total_amount ?? 0;
            
            $products = [
                [
                    'name' => 'Prix de livraison - ' . $trip->departure . ' vers ' . $trip->destination,
                    'category' => 'Transport',
                    'amount' => (int) $amount, // Prix de livraison en XOF
                    'quantity' => 1,
                    'description' => 'Prix de livraison pour réservation de transport - ' . $trip->departure . ' vers ' . $trip->destination . ' (' . $reservation->nb_personnes . ' personne(s), ' . $reservation->nb_valises . ' valise(s))'
                ]
            ];

            $baseUrl = config('app.url');
            if (str_contains($baseUrl, 'localhost') || str_contains($baseUrl, '127.0.0.1')) {
                $baseUrl = request()->getSchemeAndHttpHost();
            }

            $data = [
                'method_of_payment' => ['WAVE', 'ORANGE_MONEY'],
                'products' => $products,
                'success_url' => $baseUrl . '/payment/success/' . $reservation->id,
                'error_url' => $baseUrl . '/payment/error/' . $reservation->id,
                'is_escrow' => false, // Pas d'escrow - paiement direct
                'webhook_url' => $baseUrl . '/webhook/naboopay', // Ajouter le webhook
                'fee_payer' => 'seller', // Frais prélevés sur le vendeur (plateforme)
                'customer_info' => [
                    'name' => $reservation->client->first_name . ' ' . $reservation->client->last_name,
                    'email' => $reservation->client->email,
                    'phone' => $reservation->client->phone_number
                ],
                'metadata' => [
                    'reservation_id' => $reservation->id,
                    'client_id' => $reservation->client_id,
                    'trip_id' => $trip->id
                ]
            ];

            return $this->createTransaction($data);
        } catch (\Exception $e) {
            Log::error('NabooPay - Erreur createReservationTransaction', [
                'error' => $e->getMessage(),
                'reservation_id' => $reservation->id ?? 'N/A'
            ]);
            return [
                'success' => false,
                'error' => 'Erreur lors de la création de la transaction: ' . $e->getMessage()
            ];
        }
    }
}