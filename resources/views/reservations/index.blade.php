<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

<div>
    <!-- En-tête stylisé -->
    <div class="bg-gray-50 rounded-lg shadow-md p-6 mb-8">
        <h1 class="text-3xl font-bold text-center text-blue-600 mb-6">Liste des Réservations</h1>
        <div class="flex flex-col md:flex-row md:justify-between md:items-center space-y-4 md:space-y-0">
            <div class="w-full md:w-1/3">
                <form method="GET" action="{{ route('reservations.index') }}">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Filtrer par statut :</label>
                    <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                            name="status" id="status" onchange="this.form.submit()">
                        <option value="">Tous</option>
                        <option value="En_attente" {{ request('status') == 'En_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="confirmée" {{ request('status') == 'confirmée' ? 'selected' : '' }}>Confirmée</option>
                        <option value="annulée" {{ request('status') == 'annulée' ? 'selected' : '' }}>Annulée</option>
                    </select>
                </form>
            </div>
            <div>
                <a href="{{ route('reservations.create') }}"
                   class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200 flex items-center font-medium shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Nouvelle Réservation
                </a>
            </div>
        </div>
    </div>

    <!-- Tableau des réservations -->
    <div class="overflow-x-auto">
        <div class="inline-block min-w-full rounded-lg overflow-hidden shadow-md">
            <table class="min-w-full">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="py-3 px-4 text-left font-medium text-sm uppercase tracking-wider">Client</th>
                        <th class="py-3 px-4 text-left font-medium text-sm uppercase tracking-wider">Chauffeur</th>
                        <th class="py-3 px-4 text-left font-medium text-sm uppercase tracking-wider">Date</th>
                        <th class="py-3 px-4 text-left font-medium text-sm uppercase tracking-wider">Heure Ramassage</th>
                        <th class="py-3 px-4 text-left font-medium text-sm uppercase tracking-wider">Heure Vol</th>
                        <th class="py-3 px-4 text-left font-medium text-sm uppercase tracking-wider">Statut</th>
                        <th class="py-3 px-4 text-left font-medium text-sm uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach($reservations as $reservation)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="py-4 px-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900">
                                @if($reservation->client)
                                    {{ $reservation->client->first_name }} {{ $reservation->client->last_name }}
                                @else
                                    {{ $reservation->first_name }} {{ $reservation->last_name }} (Prospect)
                                @endif
                            </div>
                        </td>
                        <td class="py-4 px-4 whitespace-nowrap">
                            <div class="text-gray-900">
                                @if($reservation->carDriver && $reservation->carDriver->chauffeur)
                                    {{ $reservation->carDriver->chauffeur->first_name }} {{ $reservation->carDriver->chauffeur->last_name }}
                                @else
                                    Non assigné
                                @endif
                            </div>
                        </td>
                        <td class="py-4 px-4 whitespace-nowrap">
                            <div class="text-gray-700">{{ \Carbon\Carbon::parse($reservation->date)->format('d/m/Y') }}</div>
                        </td>
                        <td class="py-4 px-4 whitespace-nowrap">
                            <div class="text-gray-700">{{ \Carbon\Carbon::parse($reservation->heure_ramassage)->format('H:i') }}</div>
                        </td>
                        <td class="py-4 px-4 whitespace-nowrap">
                            <div class="text-gray-700">{{ \Carbon\Carbon::parse($reservation->heure_vol)->format('H:i') }}</div>
                        </td>
                        <td class="py-4 px-4 whitespace-nowrap">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $reservation->status == 'En_attente' ? 'bg-yellow-100 text-yellow-800' :
                                   ($reservation->status == 'confirmée' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($reservation->status) }}
                            </span>
                        </td>
                        <td class="py-4 px-4 whitespace-nowrap text-sm font-medium">
                            <button type="button"
                                    class="inline-flex items-center mr-2 px-3 py-1.5 border border-blue-600 text-blue-600 hover:bg-blue-50 rounded-md transition-colors"
                                    onclick="openModal('editReservationModal{{ $reservation->id }}')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Modifier
                            </button>
                            <a href="{{ route('reservations.show', $reservation->id) }}"
                               class="inline-flex items-center px-3 py-1.5 border border-indigo-600 text-indigo-600 hover:bg-indigo-50 rounded-md transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Détails
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-center">
        <div class="tailwind-pagination">
            {{ $reservations->links() }}
        </div>
    </div>

    <!-- Modals -->
    @foreach($reservations as $reservation)
    <div id="editReservationModal{{ $reservation->id }}" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-blue-600 px-4 py-3 sm:px-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-medium text-white">
                            Modifier la Réservation
                        </h3>
                        <button type="button" class="text-white hover:text-gray-200" onclick="closeModal('editReservationModal{{ $reservation->id }}')">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <form action="{{ route('reservations.update', $reservation->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                            <input type="date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                   name="date" value="{{ \Carbon\Carbon::parse($reservation->date)->format('Y-m-d') }}" 
                                   onchange="updateChauffeursDisponibles(this.value, {{ $reservation->id }})" required>
                        </div>

                        <div class="mb-4">
                            <label for="heure_ramassage" class="block text-sm font-medium text-gray-700 mb-1">Heure Ramassage</label>
                            <input type="time" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                   name="heure_ramassage" value="{{ \Carbon\Carbon::parse($reservation->heure_ramassage)->format('H:i') }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="heure_vol" class="block text-sm font-medium text-gray-700 mb-1">Heure Vol</label>
                            <input type="time" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                   name="heure_vol" value="{{ \Carbon\Carbon::parse($reservation->heure_vol)->format('H:i') }}">
                        </div>

                        <div class="mb-4">
                            <label for="chauffeur_id" class="block text-sm font-medium text-gray-700 mb-1">Chauffeur</label>
                            <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                    name="chauffeur_id" required>
                                <option value="">-- Sélectionner un chauffeur --</option>
                                @foreach ($reservation->available_drivers as $driver)
                                    <option value="{{ $driver['id'] }}"
                                        {{ $reservation->carDriver && $reservation->carDriver->chauffeur && $reservation->carDriver->chauffeur->id == $driver['id'] ? 'selected' : '' }}
                                        {{ !$driver['is_available'] ? 'disabled' : '' }}>
                                        {{ $driver['first_name'] }} {{ $driver['last_name'] }}
                                        @if(isset($driver['group_name']) && $driver['group_name'] !== 'Aucun groupe')
                                            ({{ $driver['group_name'] }})
                                        @endif
                                        @if(!$driver['is_available'])
                                            - {{ $driver['reason'] }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-sm text-gray-500 mt-1">Seuls les chauffeurs disponibles pour le {{ \Carbon\Carbon::parse($reservation->date)->format('d/m/Y') }} sont affichés (selon les groupes de chauffeurs)</p>
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm transition-colors duration-200">
                                Sauvegarder
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- JavaScript pour les modals -->
<script>
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    // Pour fermer le modal quand on clique en dehors
    window.onclick = function(event) {
        const modals = document.querySelectorAll('[id^="editReservationModal"]');
        modals.forEach(modal => {
            if (event.target == modal) {
                modal.classList.add('hidden');
            }
        });
    }

    // Fonction pour mettre à jour la liste des chauffeurs disponibles selon la date
    function updateChauffeursDisponibles(date, reservationId) {
        if (!date) return;

        // Récupérer le select des chauffeurs pour cette réservation
        const modal = document.getElementById(`editReservationModal${reservationId}`);
        const chauffeurSelect = modal.querySelector('select[name="chauffeur_id"]');
        
        // Afficher un indicateur de chargement
        chauffeurSelect.innerHTML = '<option value="">Chargement des chauffeurs...</option>';

        // Appel AJAX pour récupérer les chauffeurs disponibles
        fetch('/reservations/chauffeurs-disponibles', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ date: date })
        })
        .then(response => response.json())
        .then(chauffeurs => {
            // Vider le select
            chauffeurSelect.innerHTML = '<option value="">-- Sélectionner un chauffeur --</option>';
            
            // Ajouter les chauffeurs disponibles
            chauffeurs.forEach(chauffeur => {
                const isAvailable = chauffeur.disponibilite && chauffeur.disponibilite.aujourdhui === 'Disponible' && !chauffeur.en_repos;
                const option = document.createElement('option');
                option.value = chauffeur.id;
                option.textContent = `${chauffeur.first_name} ${chauffeur.last_name}`;
                
                if (!isAvailable) {
                    option.disabled = true;
                    option.textContent += ` (${chauffeur.disponibilite && chauffeur.disponibilite.aujourdhui === 'En repos' ? 'En repos' : 'Occupé'})`;
                }
                
                chauffeurSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des chauffeurs:', error);
            chauffeurSelect.innerHTML = '<option value="">Erreur lors du chargement</option>';
        });
    }
</script>

<style>
    /* Style personnalisé pour la pagination de Laravel avec Tailwind */
    .tailwind-pagination nav > div {
        @apply flex justify-center;
    }

    .tailwind-pagination .flex-1,
    .tailwind-pagination [role=navigation] {
        @apply hidden sm:flex;
    }

    .tailwind-pagination span.relative,
    .tailwind-pagination a.relative {
        @apply relative inline-flex items-center px-4 py-2 text-sm font-medium border border-gray-300;
    }

    .tailwind-pagination span.relative.text-gray-700 {
        @apply bg-blue-50 border-blue-500 text-blue-600 z-10;
    }

    .tailwind-pagination a.relative:hover {
        @apply bg-gray-50;
    }

    .tailwind-pagination [aria-disabled=true] {
        @apply opacity-50 cursor-not-allowed;
    }
</style>

</body>
</html>
@endsection
