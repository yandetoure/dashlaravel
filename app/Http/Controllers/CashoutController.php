<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\NabooPayService;

class CashoutController extends Controller
{
    private NabooPayService $nabooPayService;

    public function __construct(NabooPayService $nabooPayService)
    {
        $this->nabooPayService = $nabooPayService;
    }

    /**
     * Afficher la page de gestion des cashouts
     */
    public function index()
    {
        // Vérifier l'authentification admin ou agent
        $user = auth()->user();
        if (!$user->hasRole('admin') && !$user->hasRole('super-admin') && !$user->hasRole('agent')) {
            abort(403, 'Accès non autorisé. Vous devez être administrateur ou agent.');
        }

        $accountInfo = null;
        $error = null;

        // Récupérer les informations du compte NabooPay via le service
        try {
            $accountInfo = $this->nabooPayService->getAccountInfo();
            
            // Vérifier que les données sont valides
            if (!is_array($accountInfo) || !isset($accountInfo['balance'])) {
                $error = 'Données du compte invalides';
                $accountInfo = null;
            }
        } catch (\Exception $e) {
            $error = 'Erreur lors de la récupération des informations du compte: ' . $e->getMessage();
            Log::error('Erreur cashout index: ' . $e->getMessage());
            $accountInfo = null;
        }

        // Déterminer quelle vue utiliser selon le rôle
        $user = auth()->user();
        if ($user->hasRole('agent')) {
            return view('agent.cashout', compact('accountInfo', 'error'));
        } else {
        return view('admin.cashout', compact('accountInfo', 'error'));
        }
    }

    /**
     * Traiter la demande de retrait Wave
     */
    public function cashoutWave(Request $request)
    {
        // Vérifier l'authentification admin ou agent
        $user = auth()->user();
        if (!$user->hasRole('admin') && !$user->hasRole('super-admin') && !$user->hasRole('agent')) {
            abort(403, 'Accès non autorisé. Vous devez être administrateur ou agent.');
        }

        // Valider les données de la requête
        $request->validate([
            'amount' => 'required|numeric|min:10|max:2000000',
            'phone_number' => 'required|string|regex:/^[0-9+\-\s]+$/',
            'full_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255'
        ], [
            'amount.required' => 'Le montant est requis',
            'amount.numeric' => 'Le montant doit être un nombre',
            'amount.min' => 'Le montant minimum est de 10 FCFA',
            'amount.max' => 'Le montant maximum est de 2 000 000 FCFA',
            'phone_number.required' => 'Le numéro de téléphone est requis',
            'phone_number.regex' => 'Le format du numéro de téléphone est invalide',
            'full_name.required' => 'Le nom complet est requis',
        ]);

        try {
            $amount = (int) $request->amount;
            $phoneNumber = $request->phone_number;
            $fullName = $request->full_name;
            
            // Normaliser le numéro de téléphone
            $normalizedPhone = $this->normalizePhoneNumber($phoneNumber);
            if (!$normalizedPhone) {
                throw new \Exception('Numéro de téléphone invalide');
            }

            // Préparer les données pour l'API NabooPay
            $cashoutData = [
                'amount' => $amount,
                'phone_number' => $normalizedPhone,
                'description' => $request->description ?? 'Retrait Wave depuis l\'interface ' . $user->getRoleNames()->first(),
                'full_name' => $fullName
            ];

            $result = $this->nabooPayService->waveCashout($cashoutData);
            
            Log::info('Cashout Wave effectué', [
                'user_id' => $user->id,
                'user_role' => $user->getRoleNames()->first(),
                'amount' => $amount,
                'phone' => $normalizedPhone,
                'result' => $result
            ]);

            if ($result['success']) {
                $message = 'Cashout Wave effectué avec succès! Montant: ' . number_format($amount) . ' FCFA vers ' . $normalizedPhone;
                return back()->with('success', $message);
            } else {
                throw new \Exception($result['error']);
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors du cashout Wave', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'amount' => $request->amount ?? 'N/A',
                'phone' => $request->phone_number ?? 'N/A'
            ]);

            return back()->with('error', 'Erreur lors du cashout Wave: ' . $e->getMessage());
        }
    }

    /**
     * Traiter la demande de retrait Orange Money
     */
    public function cashoutOrangeMoney(Request $request)
    {
        // Vérifier l'authentification admin ou agent
        $user = auth()->user();
        if (!$user->hasRole('admin') && !$user->hasRole('super-admin') && !$user->hasRole('agent')) {
            abort(403, 'Accès non autorisé. Vous devez être administrateur ou agent.');
        }

        // Valider les données de la requête
        $request->validate([
            'amount' => 'required|numeric|min:10|max:500000',
            'phone_number' => 'required|string|regex:/^[0-9+\-\s]+$/',
            'full_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255'
        ], [
            'amount.required' => 'Le montant est requis',
            'amount.numeric' => 'Le montant doit être un nombre',
            'amount.min' => 'Le montant minimum est de 10 FCFA',
            'amount.max' => 'Le montant maximum est de 500 000 FCFA',
            'phone_number.required' => 'Le numéro de téléphone est requis',
            'phone_number.regex' => 'Le format du numéro de téléphone est invalide',
            'full_name.required' => 'Le nom complet est requis',
        ]);

        try {
            $amount = (int) $request->amount;
            $phoneNumber = $request->phone_number;
            $fullName = $request->full_name;
            
            // Normaliser le numéro de téléphone
            $normalizedPhone = $this->normalizePhoneNumber($phoneNumber);
            if (!$normalizedPhone) {
                throw new \Exception('Numéro de téléphone invalide');
            }
            
            // Préparer les données pour l'API NabooPay
            $cashoutData = [
                'amount' => $amount,
                'phone_number' => $normalizedPhone,
                'description' => $request->description ?? 'Retrait Orange Money depuis l\'interface ' . $user->getRoleNames()->first(),
                'full_name' => $fullName
            ];

            $result = $this->nabooPayService->orangeMoneyCashout($cashoutData);
            
            Log::info('Cashout Orange Money effectué', [
                'user_id' => $user->id,
                'user_role' => $user->getRoleNames()->first(),
                'amount' => $amount,
                'phone' => $normalizedPhone,
                'result' => $result
            ]);

            $message = 'Cashout Orange Money effectué avec succès! Montant: ' . number_format($amount) . ' FCFA vers ' . $normalizedPhone;
            return back()->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Erreur lors du cashout Orange Money', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'amount' => $request->amount ?? 'N/A',
                'phone' => $request->phone_number ?? 'N/A'
            ]);

            return back()->with('error', 'Erreur lors du cashout Orange Money: ' . $e->getMessage());
        }
    }

    /**
     * Normaliser un numéro de téléphone
     */
    private function normalizePhoneNumber(string $phoneNumber): ?string
    {
        // Supprimer tous les caractères non numériques sauf le +
        $cleaned = preg_replace('/[^\d+]/', '', $phoneNumber);
        
        // Si le numéro commence par +221, le garder tel quel
        if (str_starts_with($cleaned, '+221')) {
            return $cleaned;
        }
        
        // Si le numéro commence par 221, ajouter le +
        if (str_starts_with($cleaned, '221')) {
            return '+' . $cleaned;
        }
        
        // Si le numéro commence par 77, 78, 76, 70, ajouter +221
        if (preg_match('/^(77|78|76|70)\d{7}$/', $cleaned)) {
            return '+221' . $cleaned;
        }
        
        // Si le numéro fait 9 chiffres et commence par 7, ajouter +221
        if (preg_match('/^7\d{8}$/', $cleaned)) {
            return '+221' . $cleaned;
        }
        
        return null;
    }

    /**
     * Rediriger directement vers l'API NabooPay pour le cashout
     */
    public function redirectToNabooPay()
    {
        // Vérifier l'authentification admin ou agent
        $user = auth()->user();
        if (!$user->hasRole('admin') && !$user->hasRole('super-admin') && !$user->hasRole('agent')) {
            abort(403, 'Accès non autorisé. Vous devez être administrateur ou agent.');
        }

        // Rediriger vers l'API NabooPay
        return redirect('https://api.naboopay.com/api/v1/cashout/wave');
    }
}


