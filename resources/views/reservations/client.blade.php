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
        @if($reservations->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($reservations as $reservation)
            <div class="reservation-card bg-white rounded-xl shadow-md p-4 relative border-l-4 
                @if($reservation->status === 'Confirmée') border-green-500 
                @elseif($reservation->status === 'En_attente') border-yellow-500 
                @elseif($reservation->status === 'Annulée') border-red-600 
                @else border-gray-300 @endif transition duration-300">
                <div class="absolute status-badge 
                    @if($reservation->status === 'Confirmée') bg-green-600 
                    @elseif($reservation->status === 'En_attente') bg-yellow-600 
                    @elseif($reservation->status === 'Annulée') bg-red-600 
                    @else bg-gray-500 @endif
                    text-white px-3 py-1 rounded-full text-xs font-semibold">
                    @if($reservation->status === 'confirmée')
                        Confirmée
                    @elseif($reservation->status === 'En_attente')
                        En attente
                    @elseif($reservation->status === 'annulée')
                        Annulée
                    @else
                        {{ ucfirst($reservation->status) }}
                    @endif
                </div>
                <div class="flex items-start mb-2">
                    <div class="bg-primary bg-opacity-10 p-2 rounded-full mr-3">
                        <i class="fas fa-car text-primary text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-base text-dark">
                            Trajet #TRJ-{{ $reservation->trip->id ?? $reservation->id }}
                        </h3>
                        <p class="text-gray-500 text-xs">
                            {{ \Carbon\Carbon::parse($reservation->date)->format('d M Y') }}
                        </p>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center text-sm">
                        <i class="fas fa-map-marker-alt text-gray-400 w-5"></i>
                        <span class="ml-2 text-gray-700 truncate">{{ $reservation->adresse_rammassage }}</span>
                    </div>
                    <div class="flex items-center text-sm">
                        <i class="fas fa-flag-checkered text-gray-400 w-5"></i>
                        <span class="ml-2 text-gray-700 truncate">{{ $reservation->trip->destination ?? '-' }}</span>
                    </div>
                    <div class="flex items-center text-sm">
                        <i class="fas fa-clock text-gray-400 w-5"></i>
                        <span class="ml-2 text-gray-700">{{ $reservation->heure_ramassage }}</span>
                        <i class="fas fa-users text-gray-400 w-5 ml-4"></i>
                        <span class="ml-1 text-gray-700">{{ $reservation->nb_personnes }}</span>
                    </div>
                    @if($reservation->carDriver && $reservation->carDriver->chauffeur)
                    <div class="flex items-center text-sm">
                        <i class="fas fa-user-tie text-gray-400 w-5"></i>
                        <span class="ml-2 text-gray-700">
                            {{ $reservation->carDriver->chauffeur->first_name ?? '' }} {{ $reservation->carDriver->chauffeur->last_name ?? '' }}
                        </span>
                        @if($reservation->carDriver->chauffeur->phone_number)
                        <a href="tel:{{ $reservation->carDriver->chauffeur->phone_number }}" class="ml-4 px-2 py-1 bg-primary text-white rounded text-xs hover:bg-blue-700 flex items-center">
                            <i class="fas fa-phone-alt mr-1"></i>Appeler
                        </a>
                        @endif
                    </div>
                    @else
                    <div class="flex items-center text-sm">
                        <i class="fas fa-info-circle text-yellow-500 w-5"></i>
                        <span class="ml-2 text-yellow-600">Chauffeur non encore assigné</span>
                    </div>
                    @endif
                </div>
               {{-- <div class="mt-4 pt-2 border-t border-gray-100 flex justify-start space-x-2">

                    @if($reservation->agent)
                        <div class="flex items-center text-sm">
                            <i class="fas fa-user-tie text-gray-400 w-5"></i>
                            <span class="ml-2 text-gray-700">
                                Agent: {{ $reservation->agent->first_name ?? '' }} {{ $reservation->agent->last_name ?? '' }}
                            </span>
                            @if($reservation->agent->phone_number)
                                <a href="tel:{{ $reservation->agent->phone_number }}" class="ml-4 px-2 py-1 bg-primary text-white rounded text-xs hover:bg-blue-700 flex items-center">
                                    <i class="fas fa-phone-alt mr-1"></i>Appeler
                                </a>
                            @endif
                        </div>
                    @endif
                </div> --}}
                
                <div class="mt-4 pt-2 border-t border-gray-100 flex justify-end space-x-2">
                    @if($reservation->status === 'confirmée' || $reservation->status === 'En_attente')
                        <form action="{{ route('reservations.cancel', $reservation) }}" method="POST" onsubmit="return confirm('Annuler cette réservation ?')" style="display:inline;">
                            @csrf
                            <button type="submit" class="text-red-500 hover:text-red-600 font-medium text-xs flex items-center">
                                <i class="fas fa-times-circle mr-1"></i>Annuler
                            </button>
                        </form>
                    @elseif($reservation->status === 'annulée')
                        <button class="text-primary hover:text-blue-600 font-medium text-xs flex items-center">
                            <i class="fas fa-redo mr-1"></i>Réserver à nouveau
                        </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-6">
            {{ $reservations->links('vendor.pagination.tailwind') }}
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-20">
            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                <i class="fas fa-car text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-xl font-medium text-gray-700 mb-2">Aucune réservation trouvée</h3>
            <p class="text-gray-500 mb-6">Vous n'avez pas encore effectué de réservation.</p>
            <a href="{{ route('reservations.clientcreate') }}" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition">
                Réserver maintenant
            </a>
        </div>
        @endif
    </div>
</body>
</html>
@endsection