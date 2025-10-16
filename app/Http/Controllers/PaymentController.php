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

            // Utiliser le champ 'tarif' pour le montant
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
                            'status' => 'payée',
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

            if (isset($data['transaction_id'])) {
                $result = $this->nabooPayService->getTransaction($data['transaction_id']);
                
                if ($result['success']) {
                    $transactionData = $result['data'];
                    
                    // Trouver la facture correspondante
                    $invoice = Invoice::where('transaction_id', $data['transaction_id'])->first();
                    
                    if ($invoice) {
                        // Mettre à jour le statut selon la réponse de NabooPay
                        $status = $transactionData['status'] ?? 'en_attente';
                        
                        if ($status === 'paid' || $status === 'done') {
                            $invoice->update([
                                'status' => 'payée',
                                'paid_at' => now(),
                                'transaction_data' => json_encode($transactionData)
                            ]);

                            // Mettre à jour la réservation
                            $reservation = $invoice->reservation;
                            if ($reservation && $reservation->status === 'Confirmée') {
                                $reservation->update(['status' => 'Payée']);
                            }
                        }
                    }
                }
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Erreur webhook NabooPay: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
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
