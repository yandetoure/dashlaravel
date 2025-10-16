@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- En-tête -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Gestion des Cashouts</h1>
            <p class="text-gray-600">Retirez vos fonds vers Wave ou Orange Money</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Informations du compte -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Informations du Compte NabooPay</h2>
                
                @if($accountInfo)
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Solde disponible:</span>
                            <span class="font-bold text-green-600">
                                {{ number_format($accountInfo['balance'] ?? 0, 0, ',', ' ') }} XOF
                            </span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Statut du compte:</span>
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                                {{ $accountInfo['status'] ?? 'Actif' }}
                            </span>
                        </div>
                        
                        @if(isset($accountInfo['account_id']))
                            <div class="flex justify-between">
                                <span class="text-gray-600">ID Compte:</span>
                                <span class="font-mono text-sm">{{ $accountInfo['account_id'] }}</span>
                            </div>
                        @endif
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Dernière mise à jour:</span>
                            <span class="text-sm">{{ now()->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                @else
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-red-800 font-medium">Erreur lors du chargement des informations</span>
                        </div>
                        <p class="text-red-700 text-sm mt-1">{{ $error ?? 'Erreur lors de la récupération des informations du compte' }}</p>
                    </div>
                @endif
                
                <div class="mt-4">
                    <button onclick="refreshAccountInfo()" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Actualiser
                    </button>
                </div>
            </div>

            <!-- Formulaire de retrait -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Effectuer un Retrait</h2>
                
                <form action="{{ route('admin.cashout.retirer') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Méthode de retrait</label>
                            <div class="space-y-2">
                                <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="method" value="wave" class="mr-3" required>
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-white font-bold text-sm">W</span>
                                        </div>
                                        <div>
                                            <div class="font-medium">Wave</div>
                                            <div class="text-sm text-gray-500">Retrait vers Wave (minimum 10 XOF)</div>
                                        </div>
                                    </div>
                                </label>
                                
                                <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="method" value="orange_money" class="mr-3" required>
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-white font-bold text-sm">O</span>
                                        </div>
                                        <div>
                                            <div class="font-medium">Orange Money</div>
                                            <div class="text-sm text-gray-500">Retrait vers Orange Money (minimum 10 XOF)</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Montant (XOF)</label>
                            <input type="number" 
                                   id="amount" 
                                   name="amount" 
                                   min="10" 
                                   max="{{ $accountInfo['balance'] ?? 0 }}" 
                                   step="1"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                            <p class="text-sm text-gray-500 mt-1">
                                Montant disponible: {{ number_format($accountInfo['balance'] ?? 0, 0, ',', ' ') }} XOF
                            </p>
                        </div>
                        
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">Numéro de téléphone</label>
                            <input type="tel" 
                                   id="phone_number" 
                                   name="phone_number" 
                                   placeholder="77 123 45 67"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                            <p class="text-sm text-gray-500 mt-1">Format: 77 123 45 67 (Sénégal)</p>
                        </div>
                        
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description (optionnel)</label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="3"
                                      placeholder="Description du retrait..."
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                        </div>
                        
                        <button type="submit" class="w-full bg-green-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-green-700 transition duration-200">
                            Effectuer le Retrait
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bouton de redirection directe -->
        <div class="mt-6 text-center">
            <a href="{{ route('admin.cashout.redirect') }}" 
               class="inline-flex items-center bg-purple-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-purple-700 transition duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                </svg>
                Retirer directement sur NabooPay
            </a>
        </div>

        <!-- Actions -->
        <div class="mt-6 flex justify-between">
            <a href="{{ route('dashboard.admin') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-200">
                Retour au Dashboard
            </a>
            
            <a href="{{ route('payments.history') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                Historique des Paiements
            </a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        {{ session('error') }}
    </div>
@endif

<script>
    function refreshAccountInfo() {
        window.location.reload();
    }
    
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
