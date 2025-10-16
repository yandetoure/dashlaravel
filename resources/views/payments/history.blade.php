@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- En-tête -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Historique des Paiements</h1>
            <p class="text-gray-600">Consultez l'historique de tous vos paiements</p>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                    <select class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">Tous les statuts</option>
                        <option value="payé">Payé</option>
                        <option value="en_attente">En attente</option>
                        <option value="échoué">Échoué</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Méthode de paiement</label>
                    <select class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">Toutes les méthodes</option>
                        <option value="WAVE">Wave</option>
                        <option value="ORANGE_MONEY">Orange Money</option>
                        <option value="FREE_MONEY">Free Money</option>
                        <option value="BANK">Virement Bancaire</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                    <input type="date" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
            </div>
        </div>

        <!-- Tableau des paiements -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Facture
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Réservation
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Montant
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Méthode
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($invoices as $invoice)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $invoice->invoice_number }}
                                    </div>
                                    @if($invoice->transaction_id)
                                        <div class="text-sm text-gray-500">
                                            TX: {{ $invoice->transaction_id }}
                                        </div>
                                    @endif
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        #{{ $invoice->reservation->id }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $invoice->reservation->trip->departure }} → {{ $invoice->reservation->trip->arrival }}
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ number_format($invoice->amount, 0, ',', ' ') }} XOF
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @switch($invoice->payment_method)
                                            @case('WAVE')
                                                <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center mr-2">
                                                    <span class="text-white font-bold text-xs">W</span>
                                                </div>
                                                <span class="text-sm text-gray-900">Wave</span>
                                                @break
                                            @case('ORANGE_MONEY')
                                                <div class="w-6 h-6 bg-orange-500 rounded-full flex items-center justify-center mr-2">
                                                    <span class="text-white font-bold text-xs">O</span>
                                                </div>
                                                <span class="text-sm text-gray-900">Orange Money</span>
                                                @break
                                            @case('FREE_MONEY')
                                                <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center mr-2">
                                                    <span class="text-white font-bold text-xs">F</span>
                                                </div>
                                                <span class="text-sm text-gray-900">Free Money</span>
                                                @break
                                            @case('BANK')
                                                <div class="w-6 h-6 bg-gray-600 rounded-full flex items-center justify-center mr-2">
                                                    <span class="text-white font-bold text-xs">B</span>
                                                </div>
                                                <span class="text-sm text-gray-900">Virement</span>
                                                @break
                                            @default
                                                <span class="text-sm text-gray-900">{{ $invoice->payment_method }}</span>
                                        @endswitch
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($invoice->status === 'payé') bg-green-100 text-green-800
                                        @elseif($invoice->status === 'en_attente') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $invoice->created_at->format('d/m/Y H:i') }}
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('reservations.show', $invoice->reservation) }}" 
                                           class="text-blue-600 hover:text-blue-900">
                                            Voir
                                        </a>
                                        
                                        @if($invoice->status === 'payé')
                                            <a href="{{ route('invoices.download', $invoice) }}" 
                                               class="text-green-600 hover:text-green-900">
                                                PDF
                                            </a>
                                        @endif
                                        
                                        @if($invoice->status === 'en_attente' && $invoice->payment_url)
                                            <a href="{{ $invoice->payment_url }}" 
                                               class="text-orange-600 hover:text-orange-900">
                                                Payer
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-lg font-medium">Aucun paiement trouvé</p>
                                        <p class="text-sm">Vous n'avez pas encore effectué de paiements.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($invoices->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $invoices->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
