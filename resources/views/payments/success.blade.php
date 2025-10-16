@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Succès -->
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Paiement Réussi !</h1>
            <p class="text-gray-600 mb-6">Votre paiement a été traité avec succès.</p>
            
            @if(isset($transactionData))
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-gray-800 mb-2">Détails de la Transaction</h3>
                    <div class="text-sm text-gray-600 space-y-1">
                        <div>ID Transaction: <span class="font-mono">{{ $transactionData['transaction_id'] ?? 'N/A' }}</span></div>
                        <div>Montant: <span class="font-semibold">{{ number_format($reservation->total_amount, 0, ',', ' ') }} XOF</span></div>
                        <div>Statut: <span class="text-green-600 font-semibold">{{ $transactionData['status'] ?? 'Payé' }}</span></div>
                    </div>
                </div>
            @endif
            
            @if(isset($invoice))
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-blue-800 mb-2">Facture</h3>
                    <div class="text-sm text-blue-700 space-y-1">
                        <div>Numéro: <span class="font-mono">{{ $invoice->invoice_number }}</span></div>
                        <div>Date: <span>{{ $invoice->paid_at ? $invoice->paid_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}</span></div>
                    </div>
                </div>
            @endif
            
            <div class="space-y-3">
                <a href="{{ route('reservations.show', $reservation) }}" class="block w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 transition duration-200">
                    Voir la Réservation
                </a>
                
                <a href="{{ route('payments.history') }}" class="block w-full bg-gray-500 text-white py-3 px-4 rounded-lg font-medium hover:bg-gray-600 transition duration-200">
                    Historique des Paiements
                </a>
                
                @if(auth()->user()->hasRole('client'))
                    <a href="{{ route('dashboard.client') }}" class="block w-full bg-green-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-green-700 transition duration-200">
                        Retour au Dashboard
                    </a>
                @elseif(auth()->user()->hasRole('chauffeur'))
                    <a href="{{ route('dashboard.chauffeur') }}" class="block w-full bg-green-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-green-700 transition duration-200">
                        Retour au Dashboard
                    </a>
                @elseif(auth()->user()->hasRole('admin'))
                    <a href="{{ route('dashboard.admin') }}" class="block w-full bg-green-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-green-700 transition duration-200">
                        Retour au Dashboard
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
