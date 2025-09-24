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
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Modifier la Réservation</h1>

            <form action="{{ route('reservations.update', $reservation->id) }}" method="POST" id="editReservationForm">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Date -->
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Date de la réservation</label>
                        <input type="date"
                               id="date"
                               name="date"
                               value="{{ \Carbon\Carbon::parse($reservation->date)->format('Y-m-d') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               onchange="updateAvailableDrivers()"
                               required>
                    </div>

                    <!-- Heure de ramassage -->
                    <div>
                        <label for="heure_ramassage" class="block text-sm font-medium text-gray-700 mb-2">Heure de ramassage</label>
                        <input type="time"
                               id="heure_ramassage"
                               name="heure_ramassage"
                               value="{{ \Carbon\Carbon::parse($reservation->heure_ramassage)->format('H:i') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               onchange="updateAvailableDrivers()"
                               required>
                    </div>

                    <!-- Heure de vol -->
                    <div>
                        <label for="heure_vol" class="block text-sm font-medium text-gray-700 mb-2">Heure de vol</label>
                        <input type="time"
                               id="heure_vol"
                               name="heure_vol"
                               value="{{ \Carbon\Carbon::parse($reservation->heure_vol)->format('H:i') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Chauffeur -->
                    <div>
                        <label for="chauffeur_id" class="block text-sm font-medium text-gray-700 mb-2">Chauffeur</label>
                        <select id="chauffeur_id"
                                name="chauffeur_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                            <option value="">-- Sélectionner un chauffeur --</option>
                        </select>
                        <p class="text-sm text-gray-500 mt-1">Seuls les chauffeurs disponibles pour la date sélectionnée sont affichés</p>
                    </div>
                </div>

                <!-- Section des chauffeurs disponibles -->
                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Chauffeurs disponibles pour le <span id="selectedDate">{{ \Carbon\Carbon::parse($reservation->date)->format('d/m/Y') }}</span></h3>

                    <div id="driversLoading" class="hidden">
                        <div class="flex items-center justify-center py-4">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                            <span class="ml-2 text-gray-600">Chargement des chauffeurs...</span>
                        </div>
                    </div>

                    <div id="driversList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Les chauffeurs seront chargés ici dynamiquement -->
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('reservations.index') }}"
                       class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                        Annuler
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        Sauvegarder
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Fonction pour mettre à jour la liste des chauffeurs disponibles
function updateAvailableDrivers() {
    const date = document.getElementById('date').value;
    const heureRamassage = document.getElementById('heure_ramassage').value;

    if (!date) return;

    // Afficher le loading
    document.getElementById('driversLoading').classList.remove('hidden');
    document.getElementById('driversList').innerHTML = '';

    // Mettre à jour la date affichée
    if (date) {
        const dateObj = new Date(date);
        const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
        document.getElementById('selectedDate').textContent = dateObj.toLocaleDateString('fr-FR', options);
    }

    // Appel AJAX pour récupérer les chauffeurs disponibles
    fetch('/reservations/chauffeurs-disponibles-reservation', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            date: date,
            heure_ramassage: heureRamassage
        })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('driversLoading').classList.add('hidden');

        if (data.success) {
            displayDrivers(data.drivers);
            updateDriverSelect(data.drivers);
        } else {
            document.getElementById('driversList').innerHTML =
                '<div class="col-span-full text-center text-red-600">Erreur lors du chargement des chauffeurs</div>';
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        document.getElementById('driversLoading').classList.add('hidden');
        document.getElementById('driversList').innerHTML =
            '<div class="col-span-full text-center text-red-600">Erreur lors du chargement des chauffeurs</div>';
    });
}

// Fonction pour afficher les chauffeurs dans la grille
function displayDrivers(drivers) {
    const driversList = document.getElementById('driversList');
    driversList.innerHTML = '';

    drivers.forEach(driver => {
        const card = document.createElement('div');
        card.className = 'bg-white border rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow';

        const statusClass = driver.is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
        const statusText = driver.is_available ? 'Disponible' : (driver.reason || 'Non disponible');

        card.innerHTML = `
            <div class="flex items-center justify-between mb-2">
                <h4 class="font-medium text-gray-900">${driver.first_name} ${driver.last_name}</h4>
                <span class="px-2 py-1 text-xs font-medium rounded-full ${statusClass}">
                    ${statusText}
                </span>
            </div>
            <div class="text-sm text-gray-600">
                <p><strong>Groupe:</strong> ${driver.group_name}</p>
                ${!driver.is_available && driver.reason ? `<p><strong>Raison:</strong> ${driver.reason}</p>` : ''}
            </div>
            ${driver.is_available ?
                `<button type="button"
                         onclick="selectDriver(${driver.id}, '${driver.first_name} ${driver.last_name}')"
                         class="mt-2 w-full px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors">
                    Sélectionner
                </button>` : ''
            }
        `;

        driversList.appendChild(card);
    });
}

// Fonction pour mettre à jour le select des chauffeurs
function updateDriverSelect(drivers) {
    const select = document.getElementById('chauffeur_id');
    select.innerHTML = '<option value="">-- Sélectionner un chauffeur --</option>';

    drivers.forEach(driver => {
        const option = document.createElement('option');
        option.value = driver.id;
        option.textContent = `${driver.first_name} ${driver.last_name} (${driver.group_name})`;

        if (!driver.is_available) {
            option.disabled = true;
            option.textContent += ` - ${driver.reason || 'Non disponible'}`;
        }

        select.appendChild(option);
    });
}

// Fonction pour sélectionner un chauffeur depuis la carte
function selectDriver(driverId, driverName) {
    const select = document.getElementById('chauffeur_id');
    select.value = driverId;

    // Mettre à jour visuellement la sélection
    const cards = document.querySelectorAll('#driversList .bg-white');
    cards.forEach(card => {
        card.classList.remove('ring-2', 'ring-blue-500');
    });

    // Trouver et mettre en évidence la carte sélectionnée
    const selectedCard = Array.from(cards).find(card =>
        card.querySelector('button') &&
        card.querySelector('button').getAttribute('onclick').includes(`selectDriver(${driverId}`)
    );

    if (selectedCard) {
        selectedCard.classList.add('ring-2', 'ring-blue-500');
    }
}

// Initialiser la liste des chauffeurs au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    updateAvailableDrivers();
});
</script>

@endsection
