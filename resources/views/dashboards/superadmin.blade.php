<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Super Admin - CPRO Transport</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#DC2626',
                        secondary: '#10B981',
                        dark: '#1F2937',
                    }
                }
            }
        }
    </script>
    <style>
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #DC2626 0%, #B91C1C 100%);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

<!-- Header -->
<div class="gradient-bg text-white p-6 mb-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">Dashboard Super Admin</h1>
                <p class="text-red-100">Bonjour {{ Auth::user()->first_name ?? Auth::user()->name }}, bienvenue dans votre espace de gestion</p>
            </div>
            <div class="text-right">
                <div class="text-red-100 text-sm">{{ Carbon\Carbon::now()->format('d/m/Y') }}</div>
                <div class="text-white font-semibold">{{ Carbon\Carbon::now()->format('H:i') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Statistiques principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Réservations -->
        <div class="bg-white rounded-lg shadow-md p-6 card-hover transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Réservations</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_reservations']) }}</h3>
                    <p class="text-xs text-green-600 mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>
                        +{{ $stats['confirmed_reservations'] }} confirmées
                    </p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-calendar-check text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Total Revenus -->
        <div class="bg-white rounded-lg shadow-md p-6 card-hover transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Revenus Totaux</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_revenue']) }}</h3>
                    <p class="text-xs text-gray-500 mt-1">FCFA</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-money-bill-wave text-2xl text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- Total Utilisateurs -->
        <div class="bg-white rounded-lg shadow-md p-6 card-hover transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Utilisateurs</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_users']) }}</h3>
                    <p class="text-xs text-blue-600 mt-1">
                        {{ $stats['total_clients'] }} clients, {{ $stats['total_drivers'] }} chauffeurs
                    </p>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-users text-2xl text-purple-600"></i>
                </div>
            </div>
        </div>

        <!-- Total Véhicules -->
        <div class="bg-white rounded-lg shadow-md p-6 card-hover transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Flotte Véhicules</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_cars']) }}</h3>
                    <p class="text-xs text-orange-600 mt-1">
                        {{ count($cars_in_maintenance) }} en maintenance
                    </p>
                </div>
                <div class="p-3 bg-orange-100 rounded-full">
                    <i class="fas fa-car text-2xl text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques détaillées -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Réservations par statut -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Réservations par Statut</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                        <span class="text-sm text-gray-600">En attente</span>
                    </div>
                    <span class="font-semibold">{{ $stats['pending_reservations'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <span class="text-sm text-gray-600">Confirmées</span>
                    </div>
                    <span class="font-semibold">{{ $stats['confirmed_reservations'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                        <span class="text-sm text-gray-600">Annulées</span>
                    </div>
                    <span class="font-semibold">{{ $stats['cancelled_reservations'] }}</span>
                </div>
            </div>
        </div>

        <!-- Utilisateurs par rôle -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Utilisateurs par Rôle</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Clients</span>
                    <span class="font-semibold text-blue-600">{{ $stats['total_clients'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Chauffeurs</span>
                    <span class="font-semibold text-green-600">{{ $stats['total_drivers'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Agents</span>
                    <span class="font-semibold text-purple-600">{{ $stats['total_agents'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Admins</span>
                    <span class="font-semibold text-red-600">{{ $stats['total_admins'] }}</span>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions Rapides</h3>
            <div class="space-y-3">
                <a href="{{ route('reservations.index') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg transition-colors duration-200">
                    <i class="fas fa-list mr-2"></i>Gérer Réservations
                </a>
                <a href="{{ route('admin.create.account.page') }}" class="block w-full bg-green-600 hover:bg-green-700 text-white text-center py-2 px-4 rounded-lg transition-colors duration-200">
                    <i class="fas fa-user-plus mr-2"></i>Ajouter Utilisateur
                </a>
                <a href="{{ route('cars.index') }}" class="block w-full bg-orange-600 hover:bg-orange-700 text-white text-center py-2 px-4 rounded-lg transition-colors duration-200">
                    <i class="fas fa-car mr-2"></i>Gérer Véhicules
                </a>
                <a href="{{ route('invoices.index') }}" class="block w-full bg-purple-600 hover:bg-purple-700 text-white text-center py-2 px-4 rounded-lg transition-colors duration-200">
                    <i class="fas fa-file-invoice mr-2"></i>Voir Factures
                </a>
            </div>
        </div>
    </div>

    <!-- Graphiques et données -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Graphique des revenus -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Revenus Mensuels</h3>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 text-xs bg-primary text-white rounded">2024</button>
                    <button class="px-3 py-1 text-xs bg-gray-200 text-gray-700 rounded">2023</button>
                </div>
            </div>
            <div class="h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Graphique des réservations -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Réservations Mensuelles</h3>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 text-xs bg-primary text-white rounded">2024</button>
                    <button class="px-3 py-1 text-xs bg-gray-200 text-gray-700 rounded">2023</button>
                </div>
            </div>
            <div class="h-64">
                <canvas id="reservationsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Tableaux de données -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Réservations récentes -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Réservations Récentes</h3>
                <a href="{{ route('reservations.index') }}" class="text-primary hover:text-red-700 text-sm font-medium">Voir tout</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-2 text-xs font-medium text-gray-500 uppercase">Client</th>
                            <th class="text-left py-2 text-xs font-medium text-gray-500 uppercase">Trajet</th>
                            <th class="text-left py-2 text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="text-left py-2 text-xs font-medium text-gray-500 uppercase">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recent_reservations as $reservation)
                        <tr class="border-b border-gray-100">
                            <td class="py-3 text-sm">
                                <div class="font-medium text-gray-900">{{ $reservation->first_name }} {{ $reservation->last_name }}</div>
                                <div class="text-gray-500 text-xs">{{ $reservation->email }}</div>
                            </td>
                            <td class="py-3 text-sm text-gray-600">
                                @if($reservation->trip)
                                    {{ $reservation->trip->departure }} → {{ $reservation->trip->destination }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="py-3 text-sm text-gray-600">{{ Carbon\Carbon::parse($reservation->date)->format('d/m/Y') }}</td>
                            <td class="py-3">
                                @if($reservation->status === 'Confirmée')
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Confirmée</span>
                                @elseif($reservation->status === 'En_attente')
                                    <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">En attente</span>
                                @else
                                    <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">Annulée</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top chauffeurs -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Top Chauffeurs</h3>
                <a href="{{ route('drivers.index') }}" class="text-primary hover:text-red-700 text-sm font-medium">Voir tout</a>
            </div>
            <div class="space-y-4">
                @foreach($top_drivers as $driver)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center font-semibold">
                            {{ substr($driver->first_name, 0, 1) }}{{ substr($driver->last_name, 0, 1) }}
                        </div>
                        <div class="ml-3">
                            <div class="font-medium text-gray-900">{{ $driver->first_name }} {{ $driver->last_name }}</div>
                            <div class="text-sm text-gray-500">{{ $driver->reservations_count }} réservations</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-semibold text-green-600">{{ $driver->points ?? 0 }} pts</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Véhicules en maintenance -->
    @if(count($cars_in_maintenance) > 0)
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-wrench text-orange-500 mr-2"></i>
                Véhicules en Maintenance
            </h3>
            <a href="{{ route('maintenances.index') }}" class="text-primary hover:text-red-700 text-sm font-medium">Gérer maintenances</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($cars_in_maintenance as $maintenance)
            <div class="border border-orange-200 rounded-lg p-4 bg-orange-50">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-medium text-gray-900">{{ $maintenance->car->marque }} {{ $maintenance->car->model }}</h4>
                    <span class="text-xs bg-orange-100 text-orange-800 px-2 py-1 rounded">{{ $maintenance->statut }}</span>
                </div>
                <p class="text-sm text-gray-600 mb-2">{{ $maintenance->motif }}</p>
                <div class="text-xs text-gray-500">
                    <i class="fas fa-calendar mr-1"></i>
                    {{ Carbon\Carbon::parse($maintenance->jour)->format('d/m/Y') }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
// Données pour les graphiques
const monthlyRevenue = @json($monthly_revenue);
const monthlyReservations = @json($monthly_reservations);

// Configuration des graphiques
const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false
        }
    },
    scales: {
        y: {
            beginAtZero: true,
            grid: {
                color: '#f3f4f6'
            }
        },
        x: {
            grid: {
                display: false
            }
        }
    }
};

// Graphique des revenus
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
        datasets: [{
            label: 'Revenus',
            data: monthlyRevenue.map(item => item.total || 0),
            borderColor: '#DC2626',
            backgroundColor: 'rgba(220, 38, 38, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        ...chartOptions,
        plugins: {
            ...chartOptions.plugins,
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Revenus: ' + new Intl.NumberFormat('fr-FR').format(context.parsed.y) + ' FCFA';
                    }
                }
            }
        }
    }
});

// Graphique des réservations
const reservationsCtx = document.getElementById('reservationsChart').getContext('2d');
new Chart(reservationsCtx, {
    type: 'bar',
    data: {
        labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
        datasets: [{
            label: 'Réservations',
            data: monthlyReservations.map(item => item.total || 0),
            backgroundColor: '#10B981',
            borderColor: '#059669',
            borderWidth: 1,
            borderRadius: 4
        }]
    },
    options: chartOptions
});

// Actualisation automatique toutes les 5 minutes
setInterval(() => {
    location.reload();
}, 300000);
</script>

@endsection
