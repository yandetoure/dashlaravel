<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Invoice;
use App\Services\NabooPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    private NabooPayService $nabooPayService;

    public function __construct(NabooPayService $nabooPayService)
    {
        $this->nabooPayService = $nabooPayService;
    }

    /**
     * Afficher la page de paiement pour une réservation
     */
    public function showPaymentForm(Reservation $reservation)
    {
        // Vérifier que l'utilisateur peut payer cette réservation
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour effectuer un paiement.');
        }

        // Vérifier que la réservation appartient à l'utilisateur ou qu'il est admin/chauffeur
        $user = auth()->user();
        $canPay = false;

        if ($user->hasRole('admin') || $user->hasRole('super-admin')) {
            $canPay = true;
        } elseif ($user->hasRole('client') && $reservation->client_id === $user->id) {
            $canPay = true;
        } elseif ($user->hasRole('chauffeur')) {
            // Le chauffeur peut payer s'il est assigné à cette réservation
            $carDrivers = $user->car_drivers->pluck('id');
            if ($carDrivers->contains($reservation->cardriver_id)) {
                $canPay = true;
            }
        }

        if (!$canPay) {
            abort(403, 'Vous n\'êtes pas autorisé à payer cette réservation.');
        }

        // Vérifier que la réservation peut être payée
        if ($reservation->status !== 'Confirmée') {
            return back()->with('error', 'Cette réservation ne peut pas être payée.');
        }

        // Vérifier si une facture existe déjà
        $invoice = Invoice::where('reservation_id', $reservation->id)->first();
        if ($invoice && $invoice->status === 'payé') {
            return back()->with('info', 'Cette réservation a déjà été payée.');
        }

        return view('payments.form', compact('reservation', 'invoice'));
    }

    /**
     * Paiement direct - créer la transaction et rediriger vers NabooPay
     */
    public function payDirect(Reservation $reservation)
    {
        // Vérifier que l'utilisateur peut payer cette réservation
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour effectuer un paiement.');
        }

        $user = auth()->user();
        $canPay = false;

        if ($user->hasRole('admin') || $user->hasRole('super-admin')) {
            $canPay = true;
        } elseif ($user->hasRole('client') && $reservation->client_id === $user->id) {
            $canPay = true;
        } elseif ($user->hasRole('chauffeur')) {
            $carDrivers = $user->car_drivers->pluck('id');
            if ($carDrivers->contains($reservation->cardriver_id)) {
                $canPay = true;
            }
        }

        if (!$canPay) {
            abort(403, 'Vous n\'êtes pas autorisé à payer cette réservation.');
        }

        try {
            // Créer directement la transaction NabooPay
            $result = $this->nabooPayService->createReservationTransaction($reservation);

            if (!$result['success']) {
                return back()->withErrors(['error' => $result['error']]);
            }

            $checkoutUrl = $result['checkout_url'] ?? null;
            $transactionId = $result['transaction_id'] ?? null;

            if ($checkoutUrl) {
                // Créer ou mettre à jour la facture
                $amount = $reservation->tarif ?? $reservation->total_amount ?? 0;
                
                Invoice::updateOrCreate(
                    ['reservation_id' => $reservation->id],
                    [
                        'invoice_number' => 'INV-' . $reservation->id . '-' . time(),
                        'amount' => $amount,
                        'status' => 'en_attente',
                        'payment_method' => 'WAVE', // Méthode par défaut
                        'transaction_id' => $transactionId,
                        'payment_url' => $checkoutUrl,
                        'invoice_date' => now(),
                    ]
                );

                // Rediriger directement vers NabooPay
                return redirect($checkoutUrl);
            } else {
                Log::error('URL de checkout non trouvée dans la réponse NabooPay', [
                    'response' => $result,
                    'reservation_id' => $reservation->id
                ]);
                return back()->withErrors(['error' => 'URL de paiement non générée par NabooPay.']);
            }

        } catch (\Exception $e) {
            Log::error('Erreur paiement direct: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Erreur lors de la création du paiement: ' . $e->getMessage()]);
        }
    }

    /**
     * Créer une transaction de paiement NabooPay
     */
    public function createPayment(Request $request, Reservation $reservation)
    {
        $request->validate([
            'payment_method' => 'required|string|in:WAVE,ORANGE_MONEY,FREE_MONEY,BANK'
        ]);

        try {
            // Créer la transaction NabooPay
            $result = $this->nabooPayService->createReservationTransaction($reservation);

            if (!$result['success']) {
                return back()->withErrors(['error' => $result['error']]);
            }

            $transactionData = $result['data'];
            $checkoutUrl = $result['checkout_url'] ?? null;
            $transactionId = $result['transaction_id'] ?? null;

            // Utiliser le champ 'tarif' pour le montant (prix de livraison)
            $amount = $reservation->tarif ?? $reservation->total_amount ?? 0;
            
            // Créer ou mettre à jour la facture
            $invoice = Invoice::updateOrCreate(
                ['reservation_id' => $reservation->id],
                [
                    'invoice_number' => 'INV-' . $reservation->id . '-' . time(),
                    'amount' => $amount,
                    'status' => 'en_attente',
                    'payment_method' => $request->payment_method,
                    'transaction_id' => $transactionId,
                    'payment_url' => $checkoutUrl,
                    'invoice_date' => now(),
                ]
            );

            // Rediriger vers la page de paiement NabooPay
            if ($checkoutUrl) {
                return redirect($checkoutUrl);
            } else {
                // Log pour debug
                Log::error('URL de checkout non trouvée dans la réponse NabooPay', [
                    'response' => $result,
                    'reservation_id' => $reservation->id
                ]);
                return back()->withErrors(['error' => 'URL de paiement non générée par NabooPay.']);
            }

        } catch (\Exception $e) {
            Log::error('Erreur création paiement: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Erreur lors de la création du paiement: ' . $e->getMessage()]);
        }
    }

    /**
     * Page de succès après paiement
     */
    public function paymentSuccess(Request $request, Reservation $reservation)
    {
        try {
            // Récupérer les informations de la transaction depuis NabooPay
            if ($request->has('transaction_id')) {
                $result = $this->nabooPayService->getTransaction($request->transaction_id);
                
                if ($result['success']) {
                    $transactionData = $result['data'];
                    
                    // Mettre à jour la facture
                    $invoice = Invoice::where('reservation_id', $reservation->id)->first();
                    if ($invoice) {
                        $invoice->update([
                            'status' => 'payé',
                            'paid_at' => now(),
                            'transaction_data' => json_encode($transactionData)
                        ]);
                    }

                    // Mettre à jour le statut de la réservation si nécessaire
                    if ($reservation->status === 'Confirmée') {
                        $reservation->update(['status' => 'Payée']);
                    }

                    return view('payments.success', compact('reservation', 'invoice', 'transactionData'));
                }
            }

            return view('payments.success', compact('reservation'));

        } catch (\Exception $e) {
            Log::error('Erreur payment success: ' . $e->getMessage());
            return redirect()->route('reservations.show', $reservation)
                ->with('error', 'Erreur lors de la confirmation du paiement.');
        }
    }

    /**
     * Page d'erreur après paiement
     */
    public function paymentError(Request $request, Reservation $reservation)
    {
        $error = $request->get('error', 'Une erreur est survenue lors du paiement.');
        
        return view('payments.error', compact('reservation', 'error'));
    }

    /**
     * Webhook pour les notifications NabooPay
     */
    public function webhook(Request $request)
    {
        try {
            $data = $request->all();
            
            // Vérifier la signature du webhook si nécessaire
            // (selon la documentation NabooPay)
            
            Log::info('Webhook NabooPay reçu: ' . json_encode($data));

            // Vérifier que nous avons un transaction_id
            if (!isset($data['transaction_id'])) {
                Log::warning('Webhook NabooPay reçu sans transaction_id', ['data' => $data]);
                return response()->json(['status' => 'error', 'message' => 'transaction_id manquant'], 400);
            }

            $transactionId = $data['transaction_id'];
            
            // Récupérer les détails de la transaction depuis NabooPay
            $result = $this->nabooPayService->getTransaction($transactionId);
            
            if (!$result['success']) {
                Log::error('Impossible de récupérer la transaction NabooPay', [
                    'transaction_id' => $transactionId,
                    'error' => $result['error'] ?? 'Erreur inconnue'
                ]);
                return response()->json(['status' => 'error', 'message' => 'Transaction non trouvée'], 404);
            }

            $transactionData = $result['data'];
            $paymentStatus = $transactionData['status'] ?? 'unknown';
            
            Log::info('Statut de paiement NabooPay: ' . $paymentStatus, [
                'transaction_id' => $transactionId,
                'transaction_data' => $transactionData
            ]);
            
            // Trouver la facture correspondante
            $invoice = Invoice::where('transaction_id', $transactionId)->first();
            
            if (!$invoice) {
                Log::warning('Facture non trouvée pour la transaction', ['transaction_id' => $transactionId]);
                return response()->json(['status' => 'error', 'message' => 'Facture non trouvée'], 404);
            }

        // Mettre à jour la facture selon le statut de paiement
        $this->updateInvoiceStatus($invoice, $paymentStatus, $transactionData);
        
        // Mettre à jour la réservation si le paiement est réussi
        if (in_array($paymentStatus, ['paid', 'done', 'completed', 'success'])) {
            $this->updateReservationStatus($invoice->reservation);
            
            // Calculer et enregistrer les frais de transaction
            $this->calculateAndRecordFees($invoice, $transactionData);
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Erreur webhook NabooPay: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'data' => $request->all()
            ]);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Mettre à jour le statut de la facture
     */
    private function updateInvoiceStatus(Invoice $invoice, string $paymentStatus, array $transactionData)
    {
        $invoiceStatus = 'en_attente';
        $paidAt = null;

        // Déterminer le statut de la facture selon le statut de paiement NabooPay
        switch (strtolower($paymentStatus)) {
            case 'paid':
            case 'done':
            case 'completed':
            case 'success':
                $invoiceStatus = 'payé';
                $paidAt = now();
                break;
            case 'failed':
            case 'cancelled':
            case 'expired':
                $invoiceStatus = 'en_attente'; // Garder en attente pour permettre un nouveau paiement
                break;
            case 'pending':
            case 'processing':
                $invoiceStatus = 'en_attente';
                break;
            default:
                Log::warning('Statut de paiement NabooPay non reconnu', [
                    'payment_status' => $paymentStatus,
                    'invoice_id' => $invoice->id
                ]);
                $invoiceStatus = 'en_attente';
        }

        // Mettre à jour la facture
        $invoice->update([
            'status' => $invoiceStatus,
            'paid_at' => $paidAt,
            'transaction_data' => json_encode($transactionData)
        ]);

        Log::info('Facture mise à jour', [
            'invoice_id' => $invoice->id,
            'old_status' => $invoice->getOriginal('status'),
            'new_status' => $invoiceStatus,
            'payment_status' => $paymentStatus
        ]);
    }

    /**
     * Mettre à jour le statut de la réservation
     */
    private function updateReservationStatus(Reservation $reservation)
    {
        if (!$reservation) {
            Log::warning('Réservation non trouvée pour la mise à jour du statut');
            return;
        }

        // Ne mettre à jour que si la réservation est confirmée
        if ($reservation->status === 'Confirmée') {
            $reservation->update(['status' => 'Payée']);
            
            Log::info('Réservation mise à jour en Payée', [
                'reservation_id' => $reservation->id,
                'old_status' => 'Confirmée',
                'new_status' => 'Payée'
            ]);
        } else {
            Log::info('Réservation non mise à jour - statut actuel: ' . $reservation->status, [
                'reservation_id' => $reservation->id,
                'current_status' => $reservation->status
            ]);
        }
    }

    /**
     * Méthode de test pour simuler un webhook NabooPay
     * Cette méthode permet de tester le webhook sans attendre une vraie notification NabooPay
     */
    public function testWebhook(Request $request)
    {
        // Cette méthode ne devrait être accessible qu'en environnement de développement
        if (app()->environment('production')) {
            abort(403, 'Cette méthode n\'est disponible qu\'en développement');
        }

        $request->validate([
            'transaction_id' => 'required|string',
            'status' => 'required|string|in:paid,done,completed,success,failed,cancelled,expired,pending,processing'
        ]);

        // Simuler les données du webhook NabooPay
        $webhookData = [
            'transaction_id' => $request->transaction_id,
            'status' => $request->status,
            'amount' => $request->amount ?? 0,
            'currency' => $request->currency ?? 'XOF',
            'timestamp' => now()->toISOString()
        ];

        // Créer une nouvelle requête avec les données simulées
        $simulatedRequest = new Request($webhookData);

        // Appeler la méthode webhook avec les données simulées
        $response = $this->webhook($simulatedRequest);

        return response()->json([
            'message' => 'Webhook testé avec succès',
            'webhook_data' => $webhookData,
            'response' => $response->getData()
        ]);
    }

    /**
     * Calculer et enregistrer les frais de transaction
     */
    private function calculateAndRecordFees(Invoice $invoice, array $transactionData)
    {
        try {
            // Récupérer le montant total payé par le client
            $totalAmount = $transactionData['amount'] ?? $invoice->amount;
            
            // Calculer les frais NabooPay (généralement 2-3% selon la méthode de paiement)
            $paymentMethod = $transactionData['payment_method'] ?? 'unknown';
            $feeRate = $this->getFeeRate($paymentMethod);
            $feeAmount = $totalAmount * $feeRate;
            
            // Montant net reçu par le vendeur (après déduction des frais)
            $netAmount = $totalAmount - $feeAmount;
            
            // Enregistrer les informations de frais dans la facture
            $invoice->update([
                'total_amount_paid' => $totalAmount,
                'fee_amount' => $feeAmount,
                'net_amount_received' => $netAmount,
                'fee_rate' => $feeRate,
                'payment_method_used' => $paymentMethod
            ]);
            
            Log::info('Frais de transaction calculés', [
                'invoice_id' => $invoice->id,
                'total_amount' => $totalAmount,
                'fee_amount' => $feeAmount,
                'net_amount' => $netAmount,
                'fee_rate' => $feeRate,
                'payment_method' => $paymentMethod
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur calcul des frais: ' . $e->getMessage(), [
                'invoice_id' => $invoice->id,
                'transaction_data' => $transactionData
            ]);
        }
    }
    
    /**
     * Obtenir le taux de frais selon la méthode de paiement
     */
    private function getFeeRate(string $paymentMethod): float
    {
        return match(strtolower($paymentMethod)) {
            'wave' => 0.025, // 2.5%
            'orange_money' => 0.025, // 2.5%
            'free_money' => 0.02, // 2%
            'bank' => 0.015, // 1.5%
            default => 0.025 // 2.5% par défaut
        };
    }

    /**
     * Afficher l'historique des paiements pour un utilisateur
     */
    public function paymentHistory()
    {
        $user = auth()->user();
        $invoices = collect();

        if ($user->hasRole('admin') || $user->hasRole('super-admin')) {
            // Admin peut voir tous les paiements
            $invoices = Invoice::with('reservation.client')->orderBy('created_at', 'desc')->paginate(20);
        } elseif ($user->hasRole('client')) {
            // Client voit ses propres paiements
            $invoices = Invoice::whereHas('reservation', function($query) use ($user) {
                $query->where('client_id', $user->id);
            })->with('reservation')->orderBy('created_at', 'desc')->paginate(20);
        } elseif ($user->hasRole('chauffeur')) {
            // Chauffeur voit les paiements de ses réservations
            $carDrivers = $user->car_drivers->pluck('id');
            $invoices = Invoice::whereHas('reservation', function($query) use ($carDrivers) {
                $query->whereIn('cardriver_id', $carDrivers);
            })->with('reservation.client')->orderBy('created_at', 'desc')->paginate(20);
        }

        return view('payments.history', compact('invoices'));
    }
}
