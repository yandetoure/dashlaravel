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
<div class="bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête de page -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 text-center">
                <span class="inline-block mr-2">🚗</span> Liste des Voitures & Chauffeurs
            </h1>
            <p class="mt-2 text-center text-gray-600">Gestion des chauffeurs et des véhicules</p>
        </div>

        <!-- Carte principale -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Message de succès -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 mx-6 mt-6 rounded font-medium text-center">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Bouton d'ajout -->
            <div class="p-6 border-b border-gray-200 flex justify-end">
                <a href="{{ route('cardrivers.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200 flex items-center font-bold shadow-md text-base">
                    <span class="mr-2">➕</span> Assigner un Chauffeur
                </a>
            </div>

            <!-- Tableau -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-amber-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Voiture</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Matricule</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Chauffeurs</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($cars as $car)
                            <tr class="hover:bg-amber-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center">{{ $car->marque }} {{ $car->model }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $car->matricule }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    @if($car->drivers->isEmpty())
                                        <span class="text-red-600 font-medium">Aucun chauffeur</span>
                                    @else
                                        <div class="flex flex-wrap justify-center gap-2">
                                            @foreach($car->drivers as $driver)
                                                <span class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-800 rounded-full">
                                                    {{ $driver->first_name }} {{ $driver->last_name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    <div class="flex flex-col items-center space-y-2">
                                        @foreach($car->drivers as $driver)
                                            <form action="{{ route('car_drivers.destroy', ['car_id' => $car->id, 'user_id' => $driver->id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 hover:bg-red-200 rounded-md transition-colors duration-200 shadow-sm">
                                                    <span class="mr-1">❌</span> Retirer {{ $driver->first_name }}
                                                </button>
                                            </form>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                <div class="flex justify-center">
                    {{ $cars->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
@endsection