<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Réservations</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#10B981',
                        dark: '#1F2937',
                    }
                }
            }
        }
    </script>
    <style>
        .reservation-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .status-badge {
            top: -10px;
            right: -10px;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <header class="mb-10">
            <h1 class="text-3xl font-bold text-dark mb-2">Mes Réservations</h1>
            <p class="text-gray-600">Consultez l'historique de vos trajets réservés</p>
            
            <div class="flex items-center mt-6">
                <div class="relative flex-grow max-w-md">
                    <input type="text" placeholder="Rechercher une réservation..." 
                           class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
                <button class="ml-4 bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                    <i class="fas fa-filter mr-2"></i>Filtrer
                </button>
            </div>
        </header>

        <!-- Reservations List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Reservation Card 1 -->
            <div class="reservation-card bg-white rounded-xl shadow-md p-6 relative border-l-4 border-primary transition duration-300">
                <div class="absolute status-badge bg-secondary text-white px-3 py-1 rounded-full text-xs font-semibold">
                    Confirmée
                </div>
                
                <div class="flex items-start mb-4">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-full mr-4">
                        <i class="fas fa-car text-primary text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-dark">Trajet #TRJ-78945</h3>
                        <p class="text-gray-500 text-sm">15 Juin 2023</p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center">
                        <i class="fas fa-user text-gray-400 w-6"></i>
                        <span class="ml-2 text-gray-700">Jean Dupont</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-phone text-gray-400 w-6"></i>
                        <span class="ml-2 text-gray-700">+33 6 12 34 56 78</span>
                    </div>
                    <div class="flex">
                        <i class="fas fa-map-marker-alt text-gray-400 mt-1 w-6"></i>
                        <div class="ml-2">
                            <p class="text-gray-700 font-medium">Adresse de ramassage</p>
                            <p class="text-gray-500 text-sm">12 Rue de la Paix, 75002 Paris</p>
                        </div>
                    </div>
                    <div class="flex">
                        <i class="fas fa-flag-checkered text-gray-400 mt-1 w-6"></i>
                        <div class="ml-2">
                            <p class="text-gray-700 font-medium">Destination</p>
                            <p class="text-gray-500 text-sm">Aéroport Charles de Gaulle</p>
                        </div>
                    </div>
                    <div class="border-t border-gray-100 pt-3 mt-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-user-tie text-gray-400 w-6"></i>
                                <div class="ml-2">
                                    <p class="text-gray-700 font-medium">Chauffeur</p>
                                    <p class="text-gray-500 text-sm">Pierre Martin</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-phone text-gray-400 w-6"></i>
                                <span class="ml-2 text-gray-700">+33 6 98 76 54 32</span>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-2 text-center pt-3">
                        <div class="bg-gray-50 p-2 rounded">
                            <p class="text-xs text-gray-500">Heure</p>
                            <p class="font-medium"><i class="fas fa-clock text-primary mr-1"></i> 08:30</p>
                        </div>
                        <div class="bg-gray-50 p-2 rounded">
                            <p class="text-xs text-gray-500">Personnes</p>
                            <p class="font-medium"><i class="fas fa-users text-primary mr-1"></i> 3</p>
                        </div>
                        <div class="bg-gray-50 p-2 rounded">
                            <p class="text-xs text-gray-500">Valises</p>
                            <p class="font-medium"><i class="fas fa-suitcase text-primary mr-1"></i> 4</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 pt-4 border-t border-gray-100 flex justify-between">
                    <button class="text-primary hover:text-blue-600 font-medium">
                        <i class="fas fa-phone-alt mr-2"></i>Appeler
                    </button>
                    <button class="text-red-500 hover:text-red-600 font-medium">
                        <i class="fas fa-times-circle mr-2"></i>Annuler
                    </button>
                </div>
            </div>

            <!-- Reservation Card 2 -->
            <div class="reservation-card bg-white rounded-xl shadow-md p-6 relative border-l-4 border-yellow-400 transition duration-300">
                <div class="absolute status-badge bg-yellow-400 text-white px-3 py-1 rounded-full text-xs font-semibold">
                    En attente
                </div>
                
                <div class="flex items-start mb-4">
                    <div class="bg-yellow-400 bg-opacity-10 p-3 rounded-full mr-4">
                        <i class="fas fa-car text-yellow-500 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-dark">Trajet #TRJ-78231</h3>
                        <p class="text-gray-500 text-sm">20 Juin 2023</p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center">
                        <i class="fas fa-user text-gray-400 w-6"></i>
                        <span class="ml-2 text-gray-700">Jean Dupont</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-phone text-gray-400 w-6"></i>
                        <span class="ml-2 text-gray-700">+33 6 12 34 56 78</span>
                    </div>
                    <div class="flex">
                        <i class="fas fa-map-marker-alt text-gray-400 mt-1 w-6"></i>
                        <div class="ml-2">
                            <p class="text-gray-700 font-medium">Adresse de ramassage</p>
                            <p class="text-gray-500 text-sm">8 Avenue des Champs-Élysées, 75008 Paris</p>
                        </div>
                    </div>
                    <div class="flex">
                        <i class="fas fa-flag-checkered text-gray-400 mt-1 w-6"></i>
                        <div class="ml-2">
                            <p class="text-gray-700 font-medium">Destination</p>
                            <p class="text-gray-500 text-sm">Gare de Lyon</p>
                        </div>
                    </div>
                    <div class="border-t border-gray-100 pt-3 mt-3">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-yellow-500 w-6"></i>
                            <p class="ml-2 text-yellow-600">Chauffeur non encore assigné</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-2 text-center pt-3">
                        <div class="bg-gray-50 p-2 rounded">
                            <p class="text-xs text-gray-500">Heure</p>
                            <p class="font-medium"><i class="fas fa-clock text-yellow-500 mr-1"></i> 14:15</p>
                        </div>
                        <div class="bg-gray-50 p-2 rounded">
                            <p class="text-xs text-gray-500">Personnes</p>
                            <p class="font-medium"><i class="fas fa-users text-yellow-500 mr-1"></i> 2</p>
                        </div>
                        <div class="bg-gray-50 p-2 rounded">
                            <p class="text-xs text-gray-500">Valises</p>
                            <p class="font-medium"><i class="fas fa-suitcase text-yellow-500 mr-1"></i> 2</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end">
                    <button class="text-red-500 hover:text-red-600 font-medium">
                        <i class="fas fa-times-circle mr-2"></i>Annuler
                    </button>
                </div>
            </div>

            <!-- Reservation Card 3 -->
            <div class="reservation-card bg-white rounded-xl shadow-md p-6 relative border-l-4 border-green-500 transition duration-300">
                <div class="absolute status-badge bg-green-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                    Complétée
                </div>
                
                <div class="flex items-start mb-4">
                    <div class="bg-green-500 bg-opacity-10 p-3 rounded-full mr-4">
                        <i class="fas fa-car text-green-500 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-dark">Trajet #TRJ-77654</h3>
                        <p class="text-gray-500 text-sm">5 Juin 2023</p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center">
                        <i class="fas fa-user text-gray-400 w-6"></i>
                        <span class="ml-2 text-gray-700">Jean Dupont</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-phone text-gray-400 w-6"></i>
                        <span class="ml-2 text-gray-700">+33 6 12 34 56 78</span>
                    </div>
                    <div class="flex">
                        <i class="fas fa-map-marker-alt text-gray-400 mt-1 w-6"></i>
                        <div class="ml-2">
                            <p class="text-gray-700 font-medium">Adresse de ramassage</p>
                            <p class="text-gray-500 text-sm">25 Rue de Rivoli, 75004 Paris</p>
                        </div>
                    </div>
                    <div class="flex">
                        <i class="fas fa-flag-checkered text-gray-400 mt-1 w-6"></i>
                        <div class="ml-2">
                            <p class="text-gray-700 font-medium">Destination</p>
                            <p class="text-gray-500 text-sm">Disneyland Paris</p>
                        </div>
                    </div>
                    <div class="border-t border-gray-100 pt-3 mt-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-user-tie text-gray-400 w-6"></i>
                                <div class="ml-2">
                                    <p class="text-gray-700 font-medium">Chauffeur</p>
                                    <p class="text-gray-500 text-sm">Luc Bernard</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-phone text-gray-400 w-6"></i>
                                <span class="ml-2 text-gray-700">+33 6 45 67 89 01</span>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-2 text-center pt-3">
                        <div class="bg-gray-50 p-2 rounded">
                            <p class="text-xs text-gray-500">Heure</p>
                            <p class="font-medium"><i class="fas fa-clock text-green-500 mr-1"></i> 09:00</p>
                        </div>
                        <div class="bg-gray-50 p-2 rounded">
                            <p class="text-xs text-gray-500">Personnes</p>
                            <p class="font-medium"><i class="fas fa-users text-green-500 mr-1"></i> 4</p>
                        </div>
                        <div class="bg-gray-50 p-2 rounded">
                            <p class="text-xs text-gray-500">Valises</p>
                            <p class="font-medium"><i class="fas fa-suitcase text-green-500 mr-1"></i> 3</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 pt-4 border-t border-gray-100 flex justify-between">
                    <button class="text-primary hover:text-blue-600 font-medium">
                        <i class="fas fa-redo mr-2"></i>Réserver à nouveau
                    </button>
                    <button class="text-gray-600 hover:text-dark font-medium">
                        <i class="fas fa-star mr-2"></i>Noter
                    </button>
                </div>
            </div>
        </div>

        <!-- Empty State (hidden by default) -->
        <div class="hidden text-center py-20">
            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                <i class="fas fa-car text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-xl font-medium text-gray-700 mb-2">Aucune réservation trouvée</h3>
            <p class="text-gray-500 mb-6">Vous n'avez pas encore effectué de réservation.</p>
            <button class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition">
                Réserver maintenant
            </button>
        </div>
    </div>

    <script>
        // JavaScript pour gérer les interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Exemple: Confirmation d'annulation
            const cancelButtons = document.querySelectorAll('button:contains("Annuler")');
            cancelButtons.forEach(button => {
                button.addEventListener('click', function() {
                    if(confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')) {
                        // Logique d'annulation ici
                        this.closest('.reservation-card').classList.add('opacity-50');
                        this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Annulation...';
                        setTimeout(() => {
                            this.closest('.reservation-card').remove();
                            // Vérifier s'il ne reste plus de cartes
                            if(document.querySelectorAll('.reservation-card').length === 0) {
                                document.querySelector('.hidden').classList.remove('hidden');
                            }
                        }, 1500);
                    }
                });
            });

            // Exemple: Filtrage (simplifié)
            const searchInput = document.querySelector('input[type="text"]');
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                document.querySelectorAll('.reservation-card').forEach(card => {
                    const cardText = card.textContent.toLowerCase();
                    if(cardText.includes(searchTerm)) {
                        card.classList.remove('hidden');
                    } else {
                        card.classList.add('hidden');
                    }
                });
            });
        });
    </script>
</body>
</html>


{{-- 
@php
    \Carbon\Carbon::setLocale('fr');
@endphp
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> --}}




{{-- 

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Gestion des Réservations</h1>
            <p class="text-gray-600">Visualisez et gérez toutes les réservations de votre flotte</p>
        </div>
    </div>

    @if ($reservations->count() > 0)
        <div class="grid grid-cols-3 md:grid-cols-2 gap-6">
            @foreach ($reservations as $reservation)
                <div class="reservation-card bg-white rounded-2xl overflow-hidden shadow-md border border-gray-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="p-5">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">#RES-{{ str_pad((string) $reservation->id, 6, '0', STR_PAD_LEFT) }}</h3>
                                <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($reservation->date)->translatedFormat('d F Y') }}</p>
                            </div>
                            <span class="status-badge 
                                {{ $reservation->status === 'confirmée' ? 'confirmée' : ($reservation->status === 'En_attente' ? 'En_attente' : 'annulé') }}">
                                @if($reservation->status === 'confirmée')
                                    <i class="fas fa-check-circle mr-1"></i> Confirmée
                                @elseif($reservation->status === 'En_attente')
                                    <i class="fas fa-clock mr-1"></i> En attente
                                @else
                                    <i class="fas fa-times-circle mr-1"></i> Annulée
                                @endif
                            </span>
                        </div>

                        <div class="space-y-3">
                            <!-- Client -->
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex justify-center items-center text-blue-600 mr-3">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Client</p>
                                    <p class="font-medium">{{ $reservation->client->first_name }} {{ $reservation->client->last_name }}</p>
                                </div>
                            </div>

                            <!-- Chauffeur -->
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-green-100 flex justify-center items-center text-green-600 mr-3">
                                    <i class="fas fa-id-card"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Chauffeur</p>
                                    <p class="font-medium">{{ $reservation->chauffeur->first_name }} {{ $reservation->chauffeur->last_name }}</p>
                                </div>
                            </div>

                            <!-- Voyage -->
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-purple-100 flex justify-center items-center text-purple-600 mr-3">
                                    <i class="fas fa-route"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Voyage</p>
                                    <p class="font-medium">{{ $reservation->trip->name }}</p>
                                </div>
                            </div>

                            <!-- Heure Ramassage -->
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-yellow-100 flex justify-center items-center text-yellow-600 mr-3">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Heure de ramassage</p>
                                    <p class="font-medium">{{ $reservation->heure_ramassage }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-5 py-3 flex justify-between items-center border-t border-gray-100">
                        <div class="flex space-x-2">
                            <a href="{{ route('reservations.confirm', $reservation) }}" class="action-btn bg-green-100 text-green-600 hover:bg-green-200 p-2 rounded-full">
                                <i class="fas fa-check"></i>
                            </a>
                            <a href="{{ route('reservations.cancel', $reservation) }}" class="action-btn bg-red-100 text-red-600 hover:bg-red-200 p-2 rounded-full">
                                <i class="fas fa-times"></i>
                            </a>
                            <form action="{{ route('reservations.destroy', $reservation) }}" method="POST" onsubmit="return confirm('Supprimer cette réservation ?')" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn bg-gray-100 text-gray-600 hover:bg-gray-200 p-2 rounded-full">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                        <button class="action-btn bg-gray-100 text-gray-600 hover:bg-gray-200 px-3 py-1 rounded-lg text-sm">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $reservations->links('vendor.pagination.tailwind') }}
        </div>
    @else
        <p class="text-center text-gray-600">Aucune réservation trouvée.</p>
    @endif
</div>

<style>
    .reservation-card {
        border-radius: 1rem;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid #e5e7eb;
        background: linear-gradient(to bottom, #f9fafb, #ffffff);
    }

    .reservation-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
    }

    .status-badge {
        font-size: 0.75rem;
        padding: 0.35rem 0.75rem;
        border-radius: 9999px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .confirmed {
        background-color: #34d399; /* emerald-400 */
        color: white;
    }

    .pending {
        background-color: #fbbf24; /* amber-400 */
        color: white;
    }

    .cancelled {
        background-color: #f87171; /* red-400 */
        color: white;
    }

    .action-btn {
        transition: transform 0.2s ease-in-out, background-color 0.2s;
        border-radius: 0.5rem;
    }

    .action-btn:hover {
        transform: scale(1.1);
    }

    .card-icon {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 9999px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 1rem;
        font-weight: bold;
    }

    .client-icon {
        background-color: #e0f2fe;
        color: #0284c7;
    }

    .chauffeur-icon {
        background-color: #dcfce7;
        color: #16a34a;
    }

    .trip-icon {
        background-color: #ede9fe;
        color: #7c3aed;
    }

    .time-icon {
        background-color: #fef3c7;
        color: #ca8a04;
    }
    
</style>

 --}}
@endsection