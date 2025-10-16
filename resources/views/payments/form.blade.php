@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- En-tête -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Paiement de la Réservation</h1>
            <p class="text-gray-600">Réservation #{{ $reservation->id }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Détails de la réservation -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Détails de la Réservation</h2>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Trajet:</span>
                        <span class="font-medium">{{ $reservation->trip->departure }} → {{ $reservation->trip->arrival }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Date:</span>
                        <span class="font-medium">{{ \Carbon\Carbon::parse($reservation->date)->format('d/m/Y') }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Heure:</span>
                        <span class="font-medium">{{ $reservation->heure_ramassage }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Passagers:</span>
                        <span class="font-medium">{{ $reservation->nombre_passagers }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Statut:</span>
                        <span class="px-2 py-1 rounded text-sm font-medium
                            @if($reservation->status === 'Confirmée') bg-green-100 text-green-800
                            @elseif($reservation->status === 'Payée') bg-blue-100 text-blue-800
                            @else bg-yellow-100 text-yellow-800
                            @endif">
                            {{ $reservation->status }}
                        </span>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="flex justify-between text-lg font-bold">
                        <span>Montant Total:</span>
                        <span class="text-green-600">{{ number_format($reservation->total_amount, 0, ',', ' ') }} XOF</span>
                    </div>
                </div>
            </div>

            <!-- Formulaire de paiement -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Méthode de Paiement</h2>
                
                @if($invoice && $invoice->status === 'payé')
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-green-800 font-medium">Cette réservation a déjà été payée</span>
                        </div>
                        <p class="text-green-700 text-sm mt-1">Facture #{{ $invoice->invoice_number }}</p>
                    </div>
                @else
                    <form action="{{ route('reservations.payment.create', $reservation) }}" method="POST">
                        @csrf
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Choisissez votre méthode de paiement</label>
                                <div class="space-y-2">
                                    <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                        <input type="radio" name="payment_method" value="WAVE" class="mr-3" required>
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                                                <span class="text-white font-bold text-sm">W</span>
                                            </div>
                                            <div>
                                                <div class="font-medium">Wave</div>
                                                <div class="text-sm text-gray-500">Paiement mobile Wave</div>
                                            </div>
                                        </div>
                                    </label>
                                    
                                    <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                        <input type="radio" name="payment_method" value="ORANGE_MONEY" class="mr-3" required>
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center mr-3">
                                                <span class="text-white font-bold text-sm">O</span>
                                            </div>
                                            <div>
                                                <div class="font-medium">Orange Money</div>
                                                <div class="text-sm text-gray-500">Paiement mobile Orange Money</div>
                                            </div>
                                        </div>
                                    </label>
                                    
                                    <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                        <input type="radio" name="payment_method" value="FREE_MONEY" class="mr-3" required>
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-3">
                                                <span class="text-white font-bold text-sm">F</span>
                                            </div>
                                            <div>
                                                <div class="font-medium">Free Money</div>
                                                <div class="text-sm text-gray-500">Portefeuille numérique</div>
                                            </div>
                                        </div>
                                    </label>
                                    
                                    <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                        <input type="radio" name="payment_method" value="BANK" class="mr-3" required>
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-gray-600 rounded-full flex items-center justify-center mr-3">
                                                <span class="text-white font-bold text-sm">B</span>
                                            </div>
                                            <div>
                                                <div class="font-medium">Virement Bancaire</div>
                                                <div class="text-sm text-gray-500">Transfert bancaire</div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            
                            @error('payment_method')
                                <div class="text-red-600 text-sm">{{ $message }}</div>
                            @enderror
                            
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <h4 class="font-medium text-blue-800">Protection Escrow</h4>
                                        <p class="text-blue-700 text-sm mt-1">
                                            Votre paiement est protégé jusqu'à la confirmation de livraison du service.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 transition duration-200">
                                Payer {{ number_format($reservation->total_amount, 0, ',', ' ') }} XOF
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-6 flex justify-between">
            <a href="{{ route('reservations.show', $reservation) }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-200">
                Retour à la réservation
            </a>
            
            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('super-admin'))
                <a href="{{ route('payments.history') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-200">
                    Historique des paiements
                </a>
            @endif
        </div>
    </div>
</div>

@if(session('error'))
    <div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        {{ session('error') }}
    </div>
@endif

@if(session('success'))
    <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        {{ session('success') }}
    </div>
@endif

<script>
    // Masquer les notifications après 5 secondes
    setTimeout(function() {
        const notifications = document.querySelectorAll('.fixed.top-4.right-4');
        notifications.forEach(notification => {
            notification.style.opacity = '0';
            notification.style.transition = 'opacity 0.5s';
            setTimeout(() => notification.remove(), 500);
        });
    }, 5000);
</script>
@endsection
