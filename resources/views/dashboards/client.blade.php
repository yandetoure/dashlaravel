<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Header simplifié -->
    {{-- <div class="bg-white shadow-lg border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Tableau de bord Client</h1>
                <p class="text-lg text-gray-600">Bienvenue, {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
            </div>
        </div>
    </div> --}}

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Section Statut Client et Points - En haut -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 mb-10">
            <div class="px-8 py-6 border-b border-gray-200">
                <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-user-crown text-purple-600 mr-3"></i>
                    Mon Statut Client
                </h3>
                <p class="text-gray-600 mt-1">Votre niveau de fidélité et vos points</p>
            </div>
            <div class="p-8">
                @php
                    $points = Auth::user()->points ?? 0;
                    if ($points < 100) {
                        $status = 'Client Standard';
                        $badgeColor = 'bg-gray-100 text-gray-800';
                        $iconColor = 'text-gray-600';
                        $progressColor = 'bg-gray-500';
                        $nextLevel = 100;
                        $nextStatus = 'Client Fidèle';
                    } elseif ($points <= 300) {
                        $status = 'Client Fidèle';
                        $badgeColor = 'bg-blue-100 text-blue-800';
                        $iconColor = 'text-blue-600';
                        $progressColor = 'bg-blue-500';
                        $nextLevel = 300;
                        $nextStatus = 'Client VIP';
                    } else {
                        $status = 'Client VIP';
                        $badgeColor = 'bg-purple-100 text-purple-800';
                        $iconColor = 'text-purple-600';
                        $progressColor = 'bg-purple-500';
                        $nextLevel = null;
                        $nextStatus = null;
                    }
                    $progressPercentage = $nextLevel ? min(($points / $nextLevel) * 100, 100) : 100;
                @endphp
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Statut actuel -->
                    <div class="text-center lg:text-left">
                        <div class="inline-flex items-center px-6 py-4 rounded-2xl text-xl font-bold {{ $badgeColor }} mb-4">
                            <i class="fas fa-crown {{ $iconColor }} mr-3 text-2xl"></i>
                            {{ $status }}
                        </div>
                        <div class="text-center lg:text-left">
                            <div class="text-4xl font-bold text-purple-600 mb-2">{{ $points }}</div>
                            <div class="text-lg text-gray-600">Points fidélité</div>
                        </div>
                    </div>
                    
                    <!-- Progression vers le niveau suivant -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        @if($nextLevel)
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Progression vers {{ $nextStatus }}</h4>
                            <div class="mb-4">
                                <div class="flex justify-between text-sm text-gray-600 mb-2">
                                    <span>{{ $points }} points</span>
                                    <span>{{ $nextLevel }} points</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="{{ $progressColor }} h-3 rounded-full transition-all duration-500" style="width: {{ $progressPercentage }}%"></div>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600">
                                Plus que <span class="font-semibold text-purple-600">{{ $nextLevel - $points }}</span> points pour devenir {{ $nextStatus }}
                            </p>
                        @else
                            <div class="text-center">
                                <i class="fas fa-trophy text-purple-600 text-4xl mb-4"></i>
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">Félicitations !</h4>
                                <p class="text-gray-600">Vous avez atteint le niveau maximum</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Avantages du statut -->
                <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                    @if($points < 100)
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <i class="fas fa-star text-gray-500 text-2xl mb-2"></i>
                            <div class="text-sm font-medium text-gray-700">Réservations standard</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 text-center opacity-50">
                            <i class="fas fa-percentage text-gray-400 text-2xl mb-2"></i>
                            <div class="text-sm text-gray-500">Réductions (100+ pts)</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 text-center opacity-50">
                            <i class="fas fa-crown text-gray-400 text-2xl mb-2"></i>
                            <div class="text-sm text-gray-500">Service VIP (300+ pts)</div>
                        </div>
                    @elseif($points <= 300)
                        <div class="bg-blue-50 rounded-lg p-4 text-center">
                            <i class="fas fa-star text-blue-600 text-2xl mb-2"></i>
                            <div class="text-sm font-medium text-blue-700">Réservations prioritaires</div>
                        </div>
                        <div class="bg-blue-50 rounded-lg p-4 text-center">
                            <i class="fas fa-percentage text-blue-600 text-2xl mb-2"></i>
                            <div class="text-sm font-medium text-blue-700">Réductions 5%</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 text-center opacity-50">
                            <i class="fas fa-crown text-gray-400 text-2xl mb-2"></i>
                            <div class="text-sm text-gray-500">Service VIP (300+ pts)</div>
                        </div>
                    @else
                        <div class="bg-purple-50 rounded-lg p-4 text-center">
                            <i class="fas fa-star text-purple-600 text-2xl mb-2"></i>
                            <div class="text-sm font-medium text-purple-700">Réservations VIP</div>
                        </div>
                        <div class="bg-purple-50 rounded-lg p-4 text-center">
                            <i class="fas fa-percentage text-purple-600 text-2xl mb-2"></i>
                            <div class="text-sm font-medium text-purple-700">Réductions 10%</div>
                        </div>
                        <div class="bg-purple-50 rounded-lg p-4 text-center">
                            <i class="fas fa-crown text-purple-600 text-2xl mb-2"></i>
                            <div class="text-sm font-medium text-purple-700">Service premium</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Stats Cards avec design amélioré -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <!-- Total Réservations -->
            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-calendar-check text-white text-lg"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Réservations</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_reservations'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Réservations Confirmées -->
            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-check-circle text-white text-lg"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Confirmées</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['confirmed_reservations'] }}</p>
                    </div>
                </div>
            </div>

            <!-- En Attente -->
            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-clock text-white text-lg"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">En Attente</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['pending_reservations'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Dépensé -->
            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-money-bill-wave text-white text-lg"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Dépensé</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_spent'], 0, ',', ' ') }} <span class="text-lg">FCFA</span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Rapides - Section principale -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 mb-10">
            <div class="px-8 py-6 border-b border-gray-200">
                <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-bolt text-blue-600 mr-3"></i>
                    Actions Rapides
                </h3>
                <p class="text-gray-600 mt-1">Accédez rapidement à vos fonctionnalités principales</p>
            </div>
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <a href="{{ route('reservations.clientcreate') }}" class="group bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-6 hover:from-blue-100 hover:to-blue-200 transition-all duration-300 transform hover:scale-105">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mb-4 group-hover:bg-blue-700 transition-colors">
                                <i class="fas fa-plus text-white text-xl"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">Nouvelle Réservation</h4>
                            <p class="text-sm text-gray-600">Réserver un transport</p>
                        </div>
                    </a>

                    <a href="{{ route('reservations.client.mes') }}" class="group bg-gradient-to-r from-green-50 to-green-100 rounded-xl p-6 hover:from-green-100 hover:to-green-200 transition-all duration-300 transform hover:scale-105">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center mb-4 group-hover:bg-green-700 transition-colors">
                                <i class="fas fa-list text-white text-xl"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">Mes Réservations</h4>
                            <p class="text-sm text-gray-600">Voir l'historique</p>
                        </div>
                    </a>

                    <a href="{{ route('invoices.index') }}" class="group bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl p-6 hover:from-purple-100 hover:to-purple-200 transition-all duration-300 transform hover:scale-105">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-16 h-16 bg-purple-600 rounded-full flex items-center justify-center mb-4 group-hover:bg-purple-700 transition-colors">
                                <i class="fas fa-file-invoice text-white text-xl"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">Mes Factures</h4>
                            <p class="text-sm text-gray-600">Consulter les factures</p>
                        </div>
                    </a>

                    <a href="{{ route('reservations.showCalendar') }}" class="group bg-gradient-to-r from-orange-50 to-orange-100 rounded-xl p-6 hover:from-orange-100 hover:to-orange-200 transition-all duration-300 transform hover:scale-105">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-16 h-16 bg-orange-600 rounded-full flex items-center justify-center mb-4 group-hover:bg-orange-700 transition-colors">
                                <i class="fas fa-calendar text-white text-xl"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">Calendrier</h4>
                            <p class="text-sm text-gray-600">Voir le planning</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Réservations Récentes - Section pleine largeur -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 mb-10">
            <div class="px-8 py-6 border-b border-gray-200">
                <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-history text-green-600 mr-3"></i>
                    Réservations Récentes
                </h3>
                <p class="text-gray-600 mt-1">Vos dernières réservations</p>
            </div>
            <div class="p-8">
                @if($recent_reservations->count() > 0)
                    <div class="space-y-4">
                        @foreach($recent_reservations as $reservation)
                            <div class="bg-gray-50 rounded-xl p-6 hover:bg-gray-100 transition-colors duration-200">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                    <div class="flex items-center space-x-4 mb-4 md:mb-0">
                                        <div class="flex-shrink-0">
                                            @if($reservation->status === 'Confirmée')
                                                <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                                            @elseif($reservation->status === 'En_attente')
                                                <div class="w-4 h-4 bg-yellow-500 rounded-full"></div>
                                            @else
                                                <div class="w-4 h-4 bg-red-500 rounded-full"></div>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-semibold text-gray-900">
                                                {{ $reservation->trip->departure }} → {{ $reservation->trip->destination }}
                                            </h4>
                                            <p class="text-sm text-gray-600">
                                                <i class="fas fa-calendar mr-2"></i>
                                                {{ \Carbon\Carbon::parse($reservation->date)->format('d/m/Y') }} à {{ $reservation->heure_ramassage }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                                            @if($reservation->status === 'Confirmée') bg-green-100 text-green-800
                                            @elseif($reservation->status === 'En_attente') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ $reservation->status }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6 text-center">
                        <a href="{{ route('reservations.client.mes') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                            Voir toutes les réservations
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-calendar-times text-gray-400 text-3xl"></i>
                        </div>
                        <h4 class="text-xl font-semibold text-gray-900 mb-2">Aucune réservation trouvée</h4>
                        <p class="text-gray-600 mb-6">Commencez par faire votre première réservation</p>
                        <a href="{{ route('reservations.clientcreate') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            Faire une réservation
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Prochaines Réservations -->
        @if($upcoming_reservations->count() > 0)
        <div class="bg-white rounded-xl shadow-lg border border-gray-100">
            <div class="px-8 py-6 border-b border-gray-200">
                <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-clock text-blue-600 mr-3"></i>
                    Prochaines Réservations
                </h3>
                <p class="text-gray-600 mt-1">Vos réservations à venir</p>
            </div>
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($upcoming_reservations as $reservation)
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-6 hover:from-blue-100 hover:to-blue-200 transition-all duration-300">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-2">
                                        {{ $reservation->trip->departure }} → {{ $reservation->trip->destination }}
                                    </h4>
                                    <div class="space-y-2">
                                        <p class="text-sm text-gray-600 flex items-center">
                                            <i class="fas fa-calendar mr-2 text-blue-600"></i>
                                            {{ \Carbon\Carbon::parse($reservation->date)->format('d/m/Y') }}
                                        </p>
                                        <p class="text-sm text-gray-600 flex items-center">
                                            <i class="fas fa-clock mr-2 text-blue-600"></i>
                                            {{ $reservation->heure_ramassage }}
                                        </p>
                                        <p class="text-sm text-gray-600 flex items-center">
                                            <i class="fas fa-users mr-2 text-blue-600"></i>
                                            {{ $reservation->nb_personnes }} personne(s)
                                        </p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-600 text-white">
                                    {{ $reservation->status }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
    .hover\:scale-105:hover {
        transform: scale(1.05);
    }
    
    .transition-all {
        transition: all 0.3s ease;
    }
    
    .shadow-lg {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    .hover\:shadow-xl:hover {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
</style>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection
