<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
            <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Tableau de bord Agent</h1>
                    <p class="text-gray-600">Bienvenue, {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                </div>
                    <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <div class="text-sm text-gray-500">Points</div>
                        <div class="text-lg font-semibold text-blue-600">{{ $stats['points'] ?? 0 }}</div>
                        </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500">Statut</div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            Agent
                        </span>
                    </div>
                </div>
            </div>
                            </div>
                            </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Réservations Traitées -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-tasks text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Réservations Traitées</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_reservations_handled'] }}</p>
                    </div>
                            </div>
                            </div>

            <!-- En Attente -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">En Attente</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending_reservations'] }}</p>
                    </div>
                            </div>
                            </div>

            <!-- Confirmées Aujourd'hui -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Confirmées Aujourd'hui</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['confirmed_today'] }}</p>
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
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_clients'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Réservations en Attente -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Réservations en Attente</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            {{ $pending_reservations->count() }} en attente
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    @if($pending_reservations->count() > 0)
                        <div class="space-y-4 max-h-96 overflow-y-auto">
                            @foreach($pending_reservations as $reservation)
                                <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                                    <span class="text-xs font-medium text-yellow-800">
                                                        @if($reservation->client)
                                                            {{ substr($reservation->client->first_name, 0, 1) }}{{ substr($reservation->client->last_name, 0, 1) }}
                                                        @else
                                                            {{ substr($reservation->first_name, 0, 1) }}{{ substr($reservation->last_name, 0, 1) }}
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                            <div>
                                                <p class="text-sm font-medium text-gray-900">
                                                    @if($reservation->client)
                                                        {{ $reservation->client->first_name }} {{ $reservation->client->last_name }}
                                                    @else
                                                        {{ $reservation->first_name }} {{ $reservation->last_name }} (Prospect)
                                                    @endif
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    {{ $reservation->trip->departure }} → {{ $reservation->trip->destination }}
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    {{ \Carbon\Carbon::parse($reservation->date)->format('d/m/Y') }} à {{ $reservation->heure_ramassage }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button class="px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700 transition-colors">
                                            Confirmer
                                        </button>
                                        <button class="px-3 py-1 bg-gray-600 text-white text-xs rounded hover:bg-gray-700 transition-colors">
                                            Voir
                                        </button>
                            </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('reservations.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Voir toutes les réservations →
                            </a>
                            </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-check-circle text-green-400 text-3xl mb-4"></i>
                            <p class="text-gray-500">Aucune réservation en attente</p>
                            <p class="text-sm text-gray-400">Excellent travail !</p>
                        </div>
                    @endif
                        </div>
                    </div>

            <!-- Actions Rapides -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Actions Rapides</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-4">
                        <a href="{{ route('reservations.index') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors group">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200">
                                    <i class="fas fa-list text-blue-600"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Gérer les Réservations</p>
                                <p class="text-xs text-gray-500">Voir et traiter les demandes</p>
                            </div>
                        </a>

                        <a href="{{ route('actus.index') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors group">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200">
                                    <i class="fas fa-newspaper text-green-600"></i>
                        </div>
                    </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Actualités</p>
                                <p class="text-xs text-gray-500">Gérer les actualités</p>
                            </div>
                        </a>

                        <a href="{{ route('clients.index') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors group">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200">
                                    <i class="fas fa-users text-purple-600"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Clients</p>
                                <p class="text-xs text-gray-500">Gérer les clients</p>
                        </div>
                        </a>

                        <a href="{{ route('reservations.showCalendar') }}" class="flex items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors group">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center group-hover:bg-orange-200">
                                    <i class="fas fa-calendar text-orange-600"></i>
                    </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Calendrier</p>
                                <p class="text-xs text-gray-500">Voir le planning</p>
                            </div>
                        </a>
                            </div>
                        </div>
                    </div>
                </div>

        <!-- Two Column Layout - Bottom -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Réservations Récemment Traitées -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Récemment Traitées</h3>
                </div>
                <div class="p-6">
                    @if($recent_handled->count() > 0)
                        <div class="space-y-4">
                            @foreach($recent_handled as $reservation)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                @if($reservation->status === 'Confirmée')
                                                    <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                                                @elseif($reservation->status === 'En_attente')
                                                    <div class="w-3 h-3 bg-yellow-400 rounded-full"></div>
                                                @else
                                                    <div class="w-3 h-3 bg-red-400 rounded-full"></div>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">
                                                    @if($reservation->client)
                                                        {{ $reservation->client->first_name }} {{ $reservation->client->last_name }}
                                                    @else
                                                        {{ $reservation->first_name }} {{ $reservation->last_name }} (Prospect)
                                                    @endif
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    {{ $reservation->trip->departure }} → {{ $reservation->trip->destination }}
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    Traité le {{ $reservation->updated_at->format('d/m/Y à H:i') }}
                                                </p>
                                            </div>
                            </div>
                        </div>
                                    <div class="text-right">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($reservation->status === 'Confirmée') bg-green-100 text-green-800
                                            @elseif($reservation->status === 'En_attente') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ $reservation->status }}
                                        </span>
                        </div>
                    </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-history text-gray-400 text-3xl mb-4"></i>
                            <p class="text-gray-500">Aucune réservation traitée récemment</p>
                            </div>
                    @endif
                        </div>
                    </div>

            <!-- Chauffeurs Disponibles -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Chauffeurs Disponibles</h3>
                </div>
                <div class="p-6">
                    @if($available_drivers->count() > 0)
                        <div class="space-y-4">
                            @foreach($available_drivers as $driver)
                                <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg border border-green-200">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                <span class="text-xs font-medium text-green-800">
                                                    {{ substr($driver->first_name, 0, 1) }}{{ substr($driver->last_name, 0, 1) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $driver->first_name }} {{ $driver->last_name }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ $driver->phone ?? 'Téléphone non renseigné' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <div class="w-2 h-2 bg-green-400 rounded-full mr-1"></div>
                                            Disponible
                                        </span>
                    </div>
                    </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('drivers.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Voir tous les chauffeurs →
                            </a>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-user-slash text-gray-400 text-3xl mb-4"></i>
                            <p class="text-gray-500">Aucun chauffeur disponible</p>
                            <p class="text-sm text-gray-400">Tous les chauffeurs sont occupés</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection
