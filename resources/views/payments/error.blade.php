@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Erreur -->
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Erreur de Paiement</h1>
            <p class="text-gray-600 mb-6">{{ $error }}</p>
            
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-yellow-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h4 class="font-medium text-yellow-800">Que faire maintenant ?</h4>
                        <ul class="text-yellow-700 text-sm mt-1 list-disc list-inside">
                            <li>Vérifiez votre solde sur votre compte mobile money</li>
                            <li>Assurez-vous que votre numéro de téléphone est correct</li>
                            <li>Réessayez le paiement</li>
                            <li>Contactez le support si le problème persiste</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="space-y-3">
                <a href="{{ route('reservations.payment', $reservation) }}" class="block w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 transition duration-200">
                    Réessayer le Paiement
                </a>
                
                <a href="{{ route('reservations.show', $reservation) }}" class="block w-full bg-gray-500 text-white py-3 px-4 rounded-lg font-medium hover:bg-gray-600 transition duration-200">
                    Retour à la Réservation
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
