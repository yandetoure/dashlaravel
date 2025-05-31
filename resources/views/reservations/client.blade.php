<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50">
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        
        <!-- Header avec design moderne -->
        <div class="text-center mb-8 animate-fade-in">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-green-600 to-blue-600 rounded-full mb-4 shadow-lg">
                <i class="fas fa-calendar-check text-white text-lg"></i>
            </div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-green-600 to-blue-600 bg-clip-text text-transparent mb-2">
                Mes Réservations
            </h1>
            <p class="text-lg text-gray-600">Consultez l'historique de vos trajets réservés</p>
        </div>

        <!-- Barre de recherche et filtres -->
        <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 mb-8 overflow-hidden">
            <div class="p-6">
                <div class="flex flex-col md:flex-row gap-3 items-center">
                    <div class="relative flex-grow">
                        <input type="text" id="searchInput" placeholder="Rechercher une réservation..." 
                               class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-3 focus:ring-blue-500/20 focus:border-blue-500 transition-all bg-white/80 backdrop-blur-sm">
                        <i class="fas fa-search absolute left-3 top-4 text-gray-400"></i>
                    </div>
                    <div class="flex gap-2">
                        <select id="statusFilter" class="px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-3 focus:ring-blue-500/20 focus:border-blue-500 transition-all bg-white/80 backdrop-blur-sm">
                            <option value="">Tous les statuts</option>
                            <option value="Confirmée">Confirmées</option>
                            <option value="En_attente">En attente</option>
                            <option value="Annulée">Annulées</option>
                        </select>
                        <button class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all transform hover:scale-105 shadow-lg">
                            <i class="fas fa-filter mr-2"></i>Filtrer
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques rapides -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            @php
                $totalReservations = $reservations->total();
                $confirmedCount = $reservations->where('status', 'Confirmée')->count();
                $pendingCount = $reservations->where('status', 'En_attente')->count();
                $cancelledCount = $reservations->where('status', 'Annulée')->count();
            @endphp
            
            <div class="bg-white/80 backdrop-blur-lg rounded-xl shadow-lg p-4 border border-white/20 hover:shadow-xl hover:scale-105 transition-all duration-300 group">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                        <i class="fas fa-calendar-check text-white text-sm"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total</p>
                        <p class="text-xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">{{ $totalReservations }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-lg rounded-xl shadow-lg p-4 border border-white/20 hover:shadow-xl hover:scale-105 transition-all duration-300 group">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-green-600 rounded-lg flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                        <i class="fas fa-check-circle text-white text-sm"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Confirmées</p>
                        <p class="text-xl font-bold bg-gradient-to-r from-green-600 to-green-800 bg-clip-text text-transparent">{{ $confirmedCount }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-lg rounded-xl shadow-lg p-4 border border-white/20 hover:shadow-xl hover:scale-105 transition-all duration-300 group">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-lg flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                        <i class="fas fa-clock text-white text-sm"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">En attente</p>
                        <p class="text-xl font-bold bg-gradient-to-r from-yellow-600 to-orange-600 bg-clip-text text-transparent">{{ $pendingCount }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-lg rounded-xl shadow-lg p-4 border border-white/20 hover:shadow-xl hover:scale-105 transition-all duration-300 group">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-red-600 rounded-lg flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                        <i class="fas fa-times-circle text-white text-sm"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Annulées</p>
                        <p class="text-xl font-bold bg-gradient-to-r from-red-600 to-red-800 bg-clip-text text-transparent">{{ $cancelledCount }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des réservations -->
        @if($reservations->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4" id="reservationsList">
            @foreach($reservations as $reservation)
            <div class="reservation-card bg-white/90 backdrop-blur-sm rounded-xl shadow-lg border border-gray-200/50 overflow-hidden hover:shadow-xl transition-all duration-300 group" 
                 data-status="{{ $reservation->status }}" 
                 data-search="{{ strtolower($reservation->trip->departure ?? '') }} {{ strtolower($reservation->trip->destination ?? '') }} {{ strtolower($reservation->adresse_rammassage) }}">
                
                <!-- Header de la carte avec statut -->
                <div class="relative p-3 bg-gray-50/50">
                    
                    <div class="absolute top-2 right-2">
                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium
                            @if($reservation->status === 'Confirmée') bg-green-100 text-green-700
                            @elseif($reservation->status === 'En_attente') bg-yellow-100 text-yellow-700
                            @elseif($reservation->status === 'Annulée') bg-red-100 text-red-700
                            @else bg-gray-100 text-gray-700 @endif">
                            @if($reservation->status === 'Confirmée')
                                <i class="fas fa-check-circle mr-1"></i>Confirmée
                            @elseif($reservation->status === 'En_attente')
                                <i class="fas fa-clock mr-1"></i>En attente
                            @elseif($reservation->status === 'Annulée')
                                <i class="fas fa-times-circle mr-1"></i>Annulée
                            @else
                                {{ ucfirst($reservation->status) }}
                            @endif
                        </span>
                    </div>
                    
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gray-600 rounded-lg flex items-center justify-center shadow-sm">
                            <i class="fas fa-car text-white text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-base font-semibold text-gray-800">
                                Trajet #{{ str_pad((string)$reservation->id, 4, '0', STR_PAD_LEFT) }}
                            </h3>
                            <p class="text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($reservation->date)->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Contenu de la carte en layout horizontal -->
                <div class="p-3">
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Colonne gauche - Trajets -->
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <div class="w-6 h-6 bg-gray-100 rounded-md flex items-center justify-center mr-2">
                                    <i class="fas fa-map-marker-alt text-gray-600 text-xs"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs text-gray-500">Départ</p>
                                    <p class="text-sm text-gray-800 font-medium truncate">{{ $reservation->adresse_rammassage }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="w-6 h-6 bg-gray-100 rounded-md flex items-center justify-center mr-2">
                                    <i class="fas fa-flag-checkered text-gray-600 text-xs"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs text-gray-500">Destination</p>
                                    <p class="text-sm text-gray-800 font-medium truncate">{{ $reservation->trip->destination ?? 'Non spécifiée' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Colonne droite - Détails -->
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <div class="w-6 h-6 bg-gray-100 rounded-md flex items-center justify-center mr-2">
                                    <i class="fas fa-clock text-gray-600 text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Heure</p>
                                    <p class="text-sm text-gray-800 font-medium">{{ $reservation->heure_ramassage }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="w-6 h-6 bg-gray-100 rounded-md flex items-center justify-center mr-2">
                                    <i class="fas fa-users text-gray-600 text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Personnes</p>
                                    <p class="text-sm text-gray-800 font-medium">{{ $reservation->nb_personnes }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informations chauffeur et actions en ligne -->
                    <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between">
                        @if($reservation->carDriver && $reservation->carDriver->chauffeur)
                        <div class="flex items-center">
                            <div class="w-6 h-6 bg-gray-600 rounded-md flex items-center justify-center mr-2">
                                <i class="fas fa-user-tie text-white text-xs"></i>
                            </div>
                            <div class="mr-3">
                                <p class="text-xs text-gray-500">Chauffeur</p>
                                <p class="text-sm text-gray-800 font-medium">
                                    {{ $reservation->carDriver->chauffeur->first_name ?? '' }} {{ $reservation->carDriver->chauffeur->last_name ?? '' }}
                                </p>
                            </div>
                            @if($reservation->carDriver->chauffeur->phone_number)
                            <a href="tel:{{ $reservation->carDriver->chauffeur->phone_number }}" 
                               class="bg-gray-600 text-white px-3 py-1 rounded-md hover:bg-gray-700 transition-colors text-xs flex items-center">
                                <i class="fas fa-phone mr-1"></i>Appeler
                            </a>
                            @endif
                        </div>
                        @else
                        <div class="flex items-center">
                            <div class="w-6 h-6 bg-gray-400 rounded-md flex items-center justify-center mr-2">
                                <i class="fas fa-info-circle text-white text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Chauffeur non assigné</p>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Actions -->
                        <div class="flex space-x-2">
                            @if($reservation->status === 'Confirmée' || $reservation->status === 'En_attente')
                                <form action="{{ route('reservations.cancel', $reservation) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 transition-colors text-xs flex items-center">
                                        <i class="fas fa-times mr-1"></i>Annuler
                                    </button>
                                </form>
                            @elseif($reservation->status === 'Annulée')
                                <a href="{{ route('reservations.clientcreate') }}" class="bg-blue-500 text-white px-3 py-1 rounded-md hover:bg-blue-600 transition-colors text-xs flex items-center">
                                    <i class="fas fa-redo mr-1"></i>Réserver
                                </a>
                            @endif
                            
                            <button class="bg-gray-500 text-white px-3 py-1 rounded-md hover:bg-gray-600 transition-colors text-xs flex items-center">
                                <i class="fas fa-eye mr-1"></i>Détails
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8 flex justify-center">
            <div class="bg-white/80 backdrop-blur-lg rounded-xl shadow-lg border border-white/20 p-3">
                {{ $reservations->links('vendor.pagination.tailwind') }}
            </div>
        </div>

        @else
        <!-- État vide avec design moderne -->
        <div class="text-center py-16">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-r from-gray-100 to-gray-200 rounded-full mb-6 shadow-lg">
                <i class="fas fa-calendar-times text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-3">Aucune réservation trouvée</h3>
            <p class="text-gray-600 mb-6 max-w-md mx-auto">Vous n'avez pas encore effectué de réservation. Commencez dès maintenant votre premier voyage avec nous !</p>
            <a href="{{ route('reservations.clientcreate') }}" class="inline-flex items-center bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all transform hover:scale-105 shadow-lg">
                <i class="fas fa-plus mr-2"></i>Réserver maintenant
            </a>
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
    
    .animate-fade-in {
        animation: fade-in 0.8s ease-out;
    }
    
    /* Glassmorphism effects */
    .backdrop-blur-sm {
        backdrop-filter: blur(4px);
    }
    
    .backdrop-blur-lg {
        backdrop-filter: blur(16px);
    }
    
    /* Hover effects */
    .hover\:scale-105:hover {
        transform: scale(1.05);
    }
    
    .hover\:scale-110:hover {
        transform: scale(1.10);
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
    
    .shadow-3xl {
        box-shadow: 0 35px 60px -12px rgba(0, 0, 0, 0.3);
    }
    
    /* Responsive design */
    @media (max-width: 640px) {
        .grid-cols-1 {
            gap: 1rem;
        }
        
        .p-8 {
            padding: 1.5rem;
        }
    }
</style>

<script>
    // Fonctionnalité de recherche et filtrage
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const reservationCards = document.querySelectorAll('.reservation-card');
        
        function filterReservations() {
            const searchTerm = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value;
            
            reservationCards.forEach(card => {
                const searchData = card.getAttribute('data-search');
                const statusData = card.getAttribute('data-status');
                
                const matchesSearch = searchData.includes(searchTerm);
                const matchesStatus = !statusValue || statusData === statusValue;
                
                if (matchesSearch && matchesStatus) {
                    card.style.display = 'block';
                    card.style.animation = 'fade-in 0.5s ease-out';
                } else {
                    card.style.display = 'none';
                }
            });
        }
        
        searchInput.addEventListener('input', filterReservations);
        statusFilter.addEventListener('change', filterReservations);
        
        // Animation au scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);
        
        // Observer toutes les cartes de réservation
        reservationCards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'all 0.6s ease-out';
            observer.observe(card);
        });
    });
</script>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection