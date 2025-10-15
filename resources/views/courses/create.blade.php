@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Courses</title>
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
        .course-card:hover {
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
    <div class="max-w-2xl mx-auto">
        <!-- En-tête -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                <span class="inline-block mr-2">🚗</span> Créer une Course
            </h1>
            <p class="mt-2 text-gray-600">Assigner une course à une réservation confirmée</p>
        </div>

        <!-- Formulaire -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <form action="{{ route('courses.store') }}" method="POST">
                @csrf
                
                <!-- Sélection de la réservation -->
                <div class="mb-6">
                    <label for="reservation_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Réservation confirmée
                    </label>
                    <select 
                        name="reservation_id" 
                        id="reservation_id" 
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        required
                    >
                        <option value="">Sélectionner une réservation</option>
                        @foreach($reservations as $reservation)
                            <option value="{{ $reservation->id }}">
                                @if($reservation->client)
                                    {{ $reservation->client->first_name }} {{ $reservation->client->last_name }}
                                @else
                                    {{ $reservation->first_name }} {{ $reservation->last_name }}
                                @endif
                                - {{ \Carbon\Carbon::parse($reservation->date)->format('d/m/Y') }} à {{ $reservation->heure_ramassage }}
                                @if($reservation->carDriver && $reservation->carDriver->chauffeur)
                                    (Chauffeur: {{ $reservation->carDriver->chauffeur->first_name }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('reservation_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Informations sur la réservation sélectionnée -->
                <div id="reservation-details" class="mb-6 p-4 bg-gray-50 rounded-lg" style="display: none;">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Détails de la réservation</h3>
                    <div id="reservation-info" class="text-sm text-gray-600"></div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                    <a href="{{ route('courses.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                        Annuler
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        Créer la course
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const reservationSelect = document.getElementById('reservation_id');
    const reservationDetails = document.getElementById('reservation-details');
    const reservationInfo = document.getElementById('reservation-info');
    
    // Données des réservations
    const reservations = @json($reservations);
    
    reservationSelect.addEventListener('change', function() {
        const selectedId = this.value;
        
        if (selectedId) {
            const reservation = reservations.find(r => r.id == selectedId);
            if (reservation) {
                let info = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p><strong>Client:</strong> ${reservation.client ? reservation.client.first_name + ' ' + reservation.client.last_name : reservation.first_name + ' ' + reservation.last_name}</p>
                            <p><strong>Email:</strong> ${reservation.client ? reservation.client.email : reservation.email}</p>
                            ${reservation.client && reservation.client.phone_number ? '<p><strong>Téléphone:</strong> ' + reservation.client.phone_number + '</p>' : ''}
                        </div>
                        <div>
                            <p><strong>Date:</strong> ${new Date(reservation.date).toLocaleDateString('fr-FR')}</p>
                            <p><strong>Heure:</strong> ${reservation.heure_ramassage}</p>
                            <p><strong>Adresse:</strong> ${reservation.adresse_rammassage}</p>
                            ${reservation.car_driver && reservation.car_driver.chauffeur ? '<p><strong>Chauffeur:</strong> ' + reservation.car_driver.chauffeur.first_name + ' ' + reservation.car_driver.chauffeur.last_name + '</p>' : ''}
                        </div>
                    </div>
                `;
                reservationInfo.innerHTML = info;
                reservationDetails.style.display = 'block';
            }
        } else {
            reservationDetails.style.display = 'none';
        }
    });
});
</script>
@endsection
