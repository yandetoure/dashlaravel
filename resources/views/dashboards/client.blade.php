<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50">
    {{-- <div class="bg-white shadow-lg border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Tableau de bord Client</h1>
                <p class="text-lg text-gray-600">Bienvenue, {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
            </div>
        </div>
    </div> --}}

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <!-- Header intégré avec animation -->
        {{-- <div class="text-center mb-12 animate-fade-in">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-purple-600 to-blue-600 rounded-full mb-6 shadow-lg">
                <i class="fas fa-user text-white text-2xl"></i>
            </div>
            <h1 class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent mb-3">
                Tableau de bord Client
            </h1>
            <p class="text-xl text-gray-600">Bienvenue, <span class="font-semibold text-gray-800">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span></p>
        </div> --}}
        
        <!-- Section Statut Client et Points - Design premium -->
        <div class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-3xl shadow-2xl mb-12 overflow-hidden transform hover:scale-[1.02] transition-all duration-300">
            <div class="bg-white/10 backdrop-blur-sm">
                <div class="px-8 py-6 border-b border-white/20">
                    <h3 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-crown text-yellow-300 mr-3 animate-pulse"></i>
                        Mon Statut Client
                    </h3>
                    <p class="text-purple-100 mt-1">Votre niveau de fidélité et vos points</p>
                </div>
                <div class="p-8">
                    @php
                        $points = Auth::user()->points ?? 0;
                        if ($points < 100) {
                            $status = 'Client Standard';
                            $badgeColor = 'bg-gradient-to-r from-gray-400 to-gray-600';
                            $iconColor = 'text-gray-200';
                            $progressColor = 'bg-gradient-to-r from-gray-400 to-gray-500';
                            $nextLevel = 100;
                            $nextStatus = 'Client Fidèle';
                        } elseif ($points <= 300) {
                            $status = 'Client Fidèle';
                            $badgeColor = 'bg-gradient-to-r from-blue-400 to-blue-600';
                            $iconColor = 'text-blue-200';
                            $progressColor = 'bg-gradient-to-r from-blue-400 to-blue-500';
                            $nextLevel = 300;
                            $nextStatus = 'Client VIP';
                        } else {
                            $status = 'Client VIP';
                            $badgeColor = 'bg-gradient-to-r from-yellow-400 to-orange-500';
                            $iconColor = 'text-yellow-200';
                            $progressColor = 'bg-gradient-to-r from-yellow-400 to-orange-500';
                            $nextLevel = null;
                            $nextStatus = null;
                        }
                        $progressPercentage = $nextLevel ? min(($points / $nextLevel) * 100, 100) : 100;
                    @endphp
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Statut actuel -->
                        <div class="text-center lg:text-left">
                            <div class="inline-flex items-center px-8 py-4 rounded-2xl text-xl font-bold {{ $badgeColor }} mb-6 shadow-lg transform hover:scale-105 transition-transform">
                                <i class="fas fa-crown {{ $iconColor }} mr-3 text-2xl"></i>
                                {{ $status }}
                            </div>
                            <div class="text-center lg:text-left">
                                <div class="text-6xl font-bold text-white mb-2 animate-bounce">{{ $points }}</div>
                                <div class="text-xl text-purple-100">Points fidélité</div>
                            </div>
                        </div>
                        
                        <!-- Progression vers le niveau suivant -->
                        <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-6 border border-white/30">
                            @if($nextLevel)
                                <h4 class="text-lg font-semibold text-white mb-4">🎯 Progression vers {{ $nextStatus }}</h4>
                                <div class="mb-4">
                                    <div class="flex justify-between text-sm text-purple-100 mb-2">
                                        <span>{{ $points }} points</span>
                                        <span>{{ $nextLevel }} points</span>
                                    </div>
                                    <div class="w-full bg-white/30 rounded-full h-4 overflow-hidden">
                                        <div class="{{ $progressColor }} h-4 rounded-full transition-all duration-1000 ease-out shadow-lg" style="width: {{ $progressPercentage }}%"></div>
                                    </div>
                                </div>
                                <p class="text-sm text-purple-100">
                                    Plus que <span class="font-bold text-yellow-300 text-lg">{{ $nextLevel - $points }}</span> points pour devenir {{ $nextStatus }} 🚀
                                </p>
                            @else
                                <div class="text-center">
                                    <i class="fas fa-trophy text-yellow-300 text-5xl mb-4 animate-pulse"></i>
                                    <h4 class="text-xl font-semibold text-white mb-2">🎉 Félicitations !</h4>
                                    <p class="text-purple-100">Vous avez atteint le niveau maximum</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Avantages du statut avec design moderne -->
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                        @if($points < 100)
                            <div class="bg-white/20 backdrop-blur-sm rounded-xl p-6 text-center border border-white/30 hover:bg-white/30 transition-all">
                                <i class="fas fa-star text-white text-3xl mb-3"></i>
                                <div class="text-sm font-medium text-white">Réservations standard</div>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-center border border-white/20 opacity-60">
                                <i class="fas fa-percentage text-white/60 text-3xl mb-3"></i>
                                <div class="text-sm text-white/60">Réductions (100+ pts)</div>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-center border border-white/20 opacity-60">
                                <i class="fas fa-crown text-white/60 text-3xl mb-3"></i>
                                <div class="text-sm text-white/60">Service VIP (300+ pts)</div>
                            </div>
                        @elseif($points <= 300)
                            <div class="bg-white/20 backdrop-blur-sm rounded-xl p-6 text-center border border-white/30 hover:bg-white/30 transition-all">
                                <i class="fas fa-star text-white text-3xl mb-3"></i>
                                <div class="text-sm font-medium text-white">Réservations prioritaires</div>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm rounded-xl p-6 text-center border border-white/30 hover:bg-white/30 transition-all">
                                <i class="fas fa-percentage text-white text-3xl mb-3"></i>
                                <div class="text-sm font-medium text-white">Réductions 5%</div>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-center border border-white/20 opacity-60">
                                <i class="fas fa-crown text-white/60 text-3xl mb-3"></i>
                                <div class="text-sm text-white/60">Service VIP (300+ pts)</div>
                            </div>
                        @else
                            <div class="bg-white/20 backdrop-blur-sm rounded-xl p-6 text-center border border-white/30 hover:bg-white/30 transition-all">
                                <i class="fas fa-star text-white text-3xl mb-3"></i>
                                <div class="text-sm font-medium text-white">Réservations VIP</div>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm rounded-xl p-6 text-center border border-white/30 hover:bg-white/30 transition-all">
                                <i class="fas fa-percentage text-white text-3xl mb-3"></i>
                                <div class="text-sm font-medium text-white">Réductions 10%</div>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm rounded-xl p-6 text-center border border-white/30 hover:bg-white/30 transition-all">
                                <i class="fas fa-crown text-white text-3xl mb-3"></i>
                                <div class="text-sm font-medium text-white">Service premium</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards avec design glassmorphism -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <!-- Total Réservations -->
            <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-xl p-6 border border-white/20 hover:shadow-2xl hover:scale-105 transition-all duration-300 group">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-14 h-14 bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <i class="fas fa-calendar-check text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Réservations</p>
                        <p class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">{{ $stats['total_reservations'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Réservations Confirmées -->
            <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-xl p-6 border border-white/20 hover:shadow-2xl hover:scale-105 transition-all duration-300 group">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-14 h-14 bg-gradient-to-r from-green-500 to-green-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <i class="fas fa-check-circle text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Confirmées</p>
                        <p class="text-3xl font-bold bg-gradient-to-r from-green-600 to-green-800 bg-clip-text text-transparent">{{ $stats['confirmed_reservations'] }}</p>
                    </div>
                </div>
            </div>

            <!-- En Attente -->
            <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-xl p-6 border border-white/20 hover:shadow-2xl hover:scale-105 transition-all duration-300 group">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-14 h-14 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <i class="fas fa-clock text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">En Attente</p>
                        <p class="text-3xl font-bold bg-gradient-to-r from-yellow-600 to-orange-600 bg-clip-text text-transparent">{{ $stats['pending_reservations'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Dépensé -->
            <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-xl p-6 border border-white/20 hover:shadow-2xl hover:scale-105 transition-all duration-300 group">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-14 h-14 bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <i class="fas fa-money-bill-wave text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Dépensé</p>
                        <p class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-purple-800 bg-clip-text text-transparent">{{ number_format($stats['total_spent'], 0, ',', ' ') }} <span class="text-lg">FCFA</span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Rapides - Section principale avec design moderne -->
        <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/20 mb-12 overflow-hidden">
            <div class="px-8 py-6 bg-gradient-to-r from-blue-600/10 to-purple-600/10 border-b border-white/20">
                <h3 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent flex items-center">
                    <i class="fas fa-bolt text-blue-600 mr-3 animate-pulse"></i>
                    Actions Rapides
                </h3>
                <p class="text-gray-600 mt-1">Accédez rapidement à vos fonctionnalités principales</p>
            </div>
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <a href="{{ route('reservations.clientcreate') }}" class="group bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 hover:from-blue-100 hover:to-blue-200 transition-all duration-300 transform hover:scale-105 hover:shadow-xl border border-blue-200/50">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-16 h-16 bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg">
                                <i class="fas fa-plus text-white text-xl"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">Nouvelle Réservation</h4>
                            <p class="text-sm text-gray-600">Réserver un transport</p>
                        </div>
                    </a>

                    <a href="{{ route('reservations.client.mes') }}" class="group bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-6 hover:from-green-100 hover:to-green-200 transition-all duration-300 transform hover:scale-105 hover:shadow-xl border border-green-200/50">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-16 h-16 bg-gradient-to-r from-green-600 to-green-700 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg">
                                <i class="fas fa-list text-white text-xl"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">Mes Réservations</h4>
                            <p class="text-sm text-gray-600">Voir l'historique</p>
                        </div>
                    </a>

                    <a href="{{ route('invoices.index') }}" class="group bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-6 hover:from-purple-100 hover:to-purple-200 transition-all duration-300 transform hover:scale-105 hover:shadow-xl border border-purple-200/50">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-16 h-16 bg-gradient-to-r from-purple-600 to-purple-700 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg">
                                <i class="fas fa-file-invoice text-white text-xl"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">Mes Factures</h4>
                            <p class="text-sm text-gray-600">Consulter les factures</p>
                        </div>
                    </a>

                    <a href="{{ route('reservations.showCalendar') }}" class="group bg-gradient-to-br from-orange-50 to-orange-100 rounded-2xl p-6 hover:from-orange-100 hover:to-orange-200 transition-all duration-300 transform hover:scale-105 hover:shadow-xl border border-orange-200/50">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-16 h-16 bg-gradient-to-r from-orange-600 to-orange-700 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg">
                                <i class="fas fa-calendar text-white text-xl"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">Calendrier</h4>
                            <p class="text-sm text-gray-600">Voir le planning</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Réservations Récentes - Section pleine largeur avec design moderne -->
        <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/20 mb-12 overflow-hidden">
            <div class="px-8 py-6 bg-gradient-to-r from-green-600/10 to-blue-600/10 border-b border-white/20">
                <h3 class="text-2xl font-bold bg-gradient-to-r from-green-600 to-blue-600 bg-clip-text text-transparent flex items-center">
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
        <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
            <div class="px-8 py-6 bg-gradient-to-r from-blue-600/10 to-purple-600/10 border-b border-white/20">
                <h3 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent flex items-center">
                    <i class="fas fa-clock text-blue-600 mr-3"></i>
                    Prochaines Réservations
                </h3>
                <p class="text-gray-600 mt-1">Vos réservations à venir</p>
            </div>
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($upcoming_reservations as $reservation)
                        <div class="bg-gradient-to-br from-blue-50/80 to-purple-50/80 backdrop-blur-sm rounded-2xl p-6 hover:from-blue-100/80 hover:to-purple-100/80 transition-all duration-300 transform hover:scale-105 hover:shadow-xl border border-blue-200/30">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h4 class="text-lg font-semibold bg-gradient-to-r from-blue-700 to-purple-700 bg-clip-text text-transparent mb-3">
                                        {{ $reservation->trip->departure }} → {{ $reservation->trip->destination }}
                                    </h4>
                                    <div class="space-y-3">
                                        <p class="text-sm text-gray-600 flex items-center">
                                            <i class="fas fa-calendar mr-3 text-blue-600 w-4"></i>
                                            {{ \Carbon\Carbon::parse($reservation->date)->format('d/m/Y') }}
                                        </p>
                                        <p class="text-sm text-gray-600 flex items-center">
                                            <i class="fas fa-clock mr-3 text-blue-600 w-4"></i>
                                            {{ $reservation->heure_ramassage }}
                                        </p>
                                        <p class="text-sm text-gray-600 flex items-center">
                                            <i class="fas fa-users mr-3 text-blue-600 w-4"></i>
                                            {{ $reservation->nb_personnes }} personne(s)
                                        </p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg">
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
    /* Animations personnalisées */
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes bounce {
        0%, 20%, 53%, 80%, 100% {
            transform: translate3d(0,0,0);
        }
        40%, 43% {
            transform: translate3d(0, -8px, 0);
        }
        70% {
            transform: translate3d(0, -4px, 0);
        }
        90% {
            transform: translate3d(0, -2px, 0);
        }
    }
    
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: .7;
        }
    }
    
    .animate-fade-in {
        animation: fade-in 0.8s ease-out;
    }
    
    .animate-bounce {
        animation: bounce 2s infinite;
    }
    
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    /* Glassmorphism effects */
    .backdrop-blur-sm {
        backdrop-filter: blur(4px);
    }
    
    .backdrop-blur-lg {
        backdrop-filter: blur(16px);
    }
    
    /* Hover effects améliorés */
    .hover\:scale-105:hover {
        transform: scale(1.05);
    }
    
    .hover\:scale-110:hover {
        transform: scale(1.10);
    }
    
    .hover\:scale-\[1\.02\]:hover {
        transform: scale(1.02);
    }
    
    .transition-all {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Gradients de texte */
    .bg-clip-text {
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    /* Ombres personnalisées */
    .shadow-2xl {
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    
    /* Responsive design amélioré */
    @media (max-width: 640px) {
        .grid-cols-1 {
            gap: 1rem;
        }
        
        .p-8 {
            padding: 1.5rem;
        }
        
        .px-8 {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }
    }
    
    /* Effets de survol pour les cartes */
    .group:hover .group-hover\:scale-110 {
        transform: scale(1.1);
    }
    
    /* Animation de chargement pour les éléments */
    .animate-on-scroll {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.6s ease-out;
    }
    
    .animate-on-scroll.visible {
        opacity: 1;
        transform: translateY(0);
    }
</style>

<script>
    // Animation au scroll
    document.addEventListener('DOMContentLoaded', function() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);
        
        // Observer tous les éléments avec la classe animate-on-scroll
        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            observer.observe(el);
        });
        
        // Effet de parallaxe léger pour le background
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const parallax = document.querySelector('.min-h-screen');
            const speed = scrolled * 0.5;
            
            if (parallax) {
                parallax.style.transform = `translateY(${speed}px)`;
            }
        });
    });
</script>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection
