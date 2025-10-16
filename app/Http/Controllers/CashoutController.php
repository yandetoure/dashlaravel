<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class CashoutController extends Controller
{
    /**
     * Afficher la page de gestion des cashouts
     */
    public function index()
    {
        // Vérifier l'authentification admin
        if (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('super-admin')) {
            abort(403, 'Accès non autorisé');
        }

        $accountInfo = null;
        $error = null;

        // Récupérer les informations du compte NabooPay
        try {
            $apiToken = config('services.naboopay.api_key') ?? config('naboopay.api_token');
            
            if (!$apiToken) {
                $error = 'Token API NabooPay non configuré';
            } else {
                $apiUrl = config('services.naboopay.base_url') ?? config('naboopay.api_url', 'https://api.naboopay.com/api/v1');
                $response = Http::timeout(30)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $apiToken,
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json'
                    ])
                    ->get($apiUrl . '/account/info');

                if ($response->successful()) {
                    $accountInfo = $response->json();
                } else {
                    $error = 'Erreur lors de la récupération des informations du compte : ' . $response->body();
                }
            }
        } catch (\Exception $e) {
            $error = 'Erreur lors de la récupération des informations du compte: ' . $e->getMessage();
        }

        return view('admin.cashout', compact('accountInfo', 'error'));
    }

    /**
     * Traiter la demande de retrait
     */
    public function retirer(Request $request)
    {
        // Vérifier l'authentification admin
        if (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('super-admin')) {
            abort(403, 'Accès non autorisé');
        }

        // Valider les données de la requête
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'method' => 'required|string|in:wave,orange_money,mtn_money'
        ]);

        try {
            $apiToken = config('services.naboopay.api_key') ?? config('naboopay.api_token');
            
            if (!$apiToken) {
                return back()->withErrors(['error' => 'Token API NabooPay non configuré']);
            }

            // Préparer les données pour l'API NabooPay
            $cashoutData = [
                'amount' => $request->amount,
                'method' => $request->method,
                'user_id' => auth()->id(),
                'description' => 'Retrait depuis l\'interface admin'
            ];

            $apiUrl = config('services.naboopay.base_url') ?? config('naboopay.api_url', 'https://api.naboopay.com/api/v1');
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiToken,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])
                ->post($apiUrl . '/cashout/wave', $cashoutData);

            if ($response->successful()) {
                $result = $response->json();
                return back()->with('success', 'Demande de retrait envoyée avec succès. ID: ' . ($result['transaction_id'] ?? 'N/A'));
            } else {
                $error = 'Erreur lors de la demande de retrait : ' . $response->body();
                return back()->withErrors(['error' => $error]);
            }

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la demande de retrait: ' . $e->getMessage()]);
        }
    }

    /**
     * Rediriger directement vers l'API NabooPay pour le cashout
     */
    public function redirectToNabooPay()
    {
        // Vérifier l'authentification admin
        if (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('super-admin')) {
            abort(403, 'Accès non autorisé');
        }

        // Rediriger vers l'API NabooPay
        return redirect('https://api.naboopay.com/api/v1/cashout/wave');
    }
}


