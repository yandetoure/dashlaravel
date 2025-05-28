<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
            <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Tableau de bord Administrateur</h1>
                    <p class="text-gray-600">Bienvenue, {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                </div>
                    <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <div class="text-sm text-gray-500">Revenus du jour</div>
                        <div class="text-lg font-semibold text-green-600">{{ number_format($stats['daily_revenue'] ?? 0, 0, ',', ' ') }} FCFA</div>
                        </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500">Statut</div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            Administrateur
                        </span>
                    </div>
                </div>
            </div>
                            </div>
                            </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Cards Row 1 -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Réservations du Jour -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-day text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Réservations du Jour</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['daily_reservations'] ?? 0 }}</p>
                        <p class="text-xs text-green-600 mt-1">
                            <i class="fas fa-arrow-up mr-1"></i>
                            +12% vs hier
                                </p>
                            </div>
                </div>
                            </div>

            <!-- Réservations en Attente -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">En Attente</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending_reservations'] ?? 0 }}</p>
                        <p class="text-xs text-red-600 mt-1">
                            <i class="fas fa-arrow-down mr-1"></i>
                            -2 depuis ce matin
                                </p>
                            </div>
                </div>
                            </div>

            <!-- Revenus du Mois -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-money-bill-wave text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Revenus du Mois</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['monthly_revenue'] ?? 0, 0, ',', ' ') }} FCFA</p>
                        <p class="text-xs text-green-600 mt-1">
                                    <i class="fas fa-arrow-up mr-1"></i>
                            +15% vs mois dernier
                                </p>
                            </div>
                </div>
                            </div>

            <!-- Total Clients -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-purple-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Clients</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_clients'] ?? 0 }}</p>
                        <p class="text-xs text-green-600 mt-1">
                            <i class="fas fa-arrow-up mr-1"></i>
                            +28% vs mois dernier
                        </p>
                    </div>
                </div>
                            </div>
        </div>

        <!-- Stats Cards Row 2 -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Véhicules Disponibles -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-car text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Véhicules Disponibles</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['available_vehicles'] ?? 0 }}</p>
                        <p class="text-xs text-gray-500 mt-1">Sur {{ $stats['total_vehicles'] ?? 0 }} total</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($stats['total_vehicles'] ?? 0) > 0 ? (($stats['available_vehicles'] ?? 0) / ($stats['total_vehicles'] ?? 1)) * 100 : 0 }}%"></div>
                    </div>
                </div>
                            </div>

            <!-- En Déplacement -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-road text-orange-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">En Déplacement</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['vehicles_on_trip'] ?? 0 }}</p>
                                <p class="text-xs text-gray-500 mt-1">Actuellement</p>
                            </div>
                </div>
                            </div>

            <!-- En Maintenance -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-tools text-red-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">En Maintenance</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['vehicles_maintenance'] ?? 0 }}</p>
                                <p class="text-xs text-gray-500 mt-1">En cours</p>
                            </div>
                </div>
                            </div>

            <!-- Agents Actifs -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-tie text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Agents Actifs</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['active_agents'] ?? 0 }}</p>
                        <p class="text-xs text-gray-500 mt-1">En ligne</p>
                            </div>
                        </div>
                    </div>
                </div>

        <!-- Charts and Tables -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                    <!-- Revenue Chart -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Revenus Mensuels</h3>
                            <div class="flex space-x-2">
                            <button class="px-3 py-1 text-xs bg-blue-600 text-white rounded">Mois</button>
                                <button class="px-3 py-1 text-xs bg-gray-200 text-gray-700 rounded">Année</button>
                            </div>
                        </div>
                </div>
                <div class="p-6">
                    <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                        <div class="text-center">
                            <i class="fas fa-chart-bar text-gray-400 text-3xl mb-4"></i>
                            <p class="text-gray-500">Graphique des revenus</p>
                            <p class="text-sm text-gray-400">Intégration Chart.js à venir</p>
                        </div>
                    </div>
                        </div>
                    </div>

                    <!-- Vehicle Status -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Statut des Véhicules</h3>
                        </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-blue-600 mr-3"></div>
                                <span class="text-sm text-gray-700">Disponibles</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ $stats['available_vehicles'] ?? 0 }} ({{ ($stats['total_vehicles'] ?? 0) > 0 ? round((($stats['available_vehicles'] ?? 0) / ($stats['total_vehicles'] ?? 1)) * 100) : 0 }}%)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-orange-600 mr-3"></div>
                                <span class="text-sm text-gray-700">En déplacement</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ $stats['vehicles_on_trip'] ?? 0 }} ({{ ($stats['total_vehicles'] ?? 0) > 0 ? round((($stats['vehicles_on_trip'] ?? 0) / ($stats['total_vehicles'] ?? 1)) * 100) : 0 }}%)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-red-600 mr-3"></div>
                                <span class="text-sm text-gray-700">Maintenance</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ $stats['vehicles_maintenance'] ?? 0 }} ({{ ($stats['total_vehicles'] ?? 0) > 0 ? round((($stats['vehicles_maintenance'] ?? 0) / ($stats['total_vehicles'] ?? 1)) * 100) : 0 }}%)</span>
                        </div>
                    </div>
                    
                    <!-- Circular Progress -->
                    <div class="mt-6 flex justify-center">
                        <div class="relative w-32 h-32">
                            <svg class="w-32 h-32 transform -rotate-90" viewBox="0 0 36 36">
                                <path class="text-gray-200" stroke="currentColor" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                <path class="text-blue-600" stroke="currentColor" stroke-width="3" fill="none" stroke-dasharray="{{ ($stats['total_vehicles'] ?? 0) > 0 ? round((($stats['available_vehicles'] ?? 0) / ($stats['total_vehicles'] ?? 1)) * 100) : 0 }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-xl font-semibold text-gray-700">{{ ($stats['total_vehicles'] ?? 0) > 0 ? round((($stats['available_vehicles'] ?? 0) / ($stats['total_vehicles'] ?? 1)) * 100) : 0 }}%</span>
                            </div>
                        </div>
                            </div>
                        </div>
                    </div>
                </div>

        <!-- Recent Reservations Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Réservations Récentes</h3>
                    <a href="{{ route('reservations.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Voir tout →
                    </a>
                </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trajet</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                        @if(isset($recent_reservations) && $recent_reservations->count() > 0)
                            @foreach($recent_reservations as $reservation)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $reservation->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $reservation->client->first_name }} {{ $reservation->client->last_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $reservation->trip->departure }} → {{ $reservation->trip->destination }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($reservation->date)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($reservation->status === 'Confirmée') bg-green-100 text-green-800
                                            @elseif($reservation->status === 'En_attente') bg-yellow-100 text-yellow-800
                                            @elseif($reservation->status === 'En_cours') bg-blue-100 text-blue-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ $reservation->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ number_format($reservation->price ?? 0, 0, ',', ' ') }} FCFA
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('reservations.show', $reservation->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Voir</a>
                                        @if($reservation->status === 'En_attente')
                                            <button class="text-green-600 hover:text-green-900 mr-3">Confirmer</button>
                                        @endif
                                        <button class="text-red-600 hover:text-red-900">Annuler</button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                    Aucune réservation récente
                                </td>
                            </tr>
                        @endif
                            </tbody>
                        </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <a href="{{ route('reservations.index') }}" class="flex items-center p-6 bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow group">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200">
                        <i class="fas fa-calendar-check text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-900">Gérer Réservations</p>
                    <p class="text-xs text-gray-500">Voir et traiter</p>
                </div>
            </a>

            <a href="{{ route('vehicles.index') }}" class="flex items-center p-6 bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow group">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200">
                        <i class="fas fa-car text-green-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-900">Gérer Véhicules</p>
                    <p class="text-xs text-gray-500">Flotte et maintenance</p>
                </div>
            </a>

            <a href="{{ route('users.index') }}" class="flex items-center p-6 bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow group">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200">
                        <i class="fas fa-users text-purple-600"></i>
                    </div>
                        </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-900">Gérer Utilisateurs</p>
                    <p class="text-xs text-gray-500">Clients, agents, chauffeurs</p>
                        </div>
            </a>

            <a href="{{ route('actus.index') }}" class="flex items-center p-6 bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow group">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center group-hover:bg-orange-200">
                        <i class="fas fa-newspaper text-orange-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-900">Actualités</p>
                    <p class="text-xs text-gray-500">Gérer les actualités</p>
                </div>
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection
