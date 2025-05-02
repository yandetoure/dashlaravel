<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Réservation Voitures</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#10B981',
                        danger: '#EF4444',
                        warning: '#F59E0B',
                        dark: '#1F2937',
                    }
                }
            }
        }
    </script>
    <style>
        .chart-container {
            position: relative;
            height: 300px;
        }
        .progress-ring__circle {
            transition: stroke-dashoffset 0.35s;
            transform: rotate(-90deg);
            transform-origin: 50% 50%;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex h-screen overflow-hidden">

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <!-- Header -->
            <header class="bg-white shadow-sm">
                <div class="flex justify-between items-center p-4">
                    <h2 class="text-xl font-semibold text-gray-800">Tableau de bord</h2>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="text" placeholder="Rechercher..." class="pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                        <button class="p-2 text-gray-600 hover:text-primary">
                            <i class="fas fa-bell"></i>
                        </button>
                        <button class="p-2 text-gray-600 hover:text-primary">
                            <i class="fas fa-envelope"></i>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <main class="p-6">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <!-- Réservations du jour -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Réservations du jour</p>
                                <h3 class="text-2xl font-bold mt-1">24</h3>
                                <p class="text-xs text-green-500 mt-1 flex items-center">
                                    <i class="fas fa-arrow-up mr-1"></i>
                                    <span>12% vs hier</span>
                                </p>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-full text-primary">
                                <i class="fas fa-calendar-day text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Réservations en attente -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Réservations en attente</p>
                                <h3 class="text-2xl font-bold mt-1">8</h3>
                                <p class="text-xs text-red-500 mt-1 flex items-center">
                                    <i class="fas fa-arrow-down mr-1"></i>
                                    <span>2 en moins</span>
                                </p>
                            </div>
                            <div class="p-3 bg-yellow-100 rounded-full text-warning">
                                <i class="fas fa-clock text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Revenu du mois -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Revenu du mois</p>
                                <h3 class="text-2xl font-bold mt-1">1,450,000 FCFA</h3>
                                <p class="text-xs text-green-500 mt-1 flex items-center">
                                    <i class="fas fa-arrow-up mr-1"></i>
                                    <span>15% vs mois dernier</span>
                                </p>
                            </div>
                            <div class="p-3 bg-green-100 rounded-full text-secondary">
                                <i class="fas fa-money-bill-wave text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Clients (avec augmentation) -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Clients</p>
                                <h3 class="text-2xl font-bold mt-1">156</h3>
                                <p class="text-xs text-green-500 mt-1 flex items-center">
                                    <i class="fas fa-arrow-up mr-1"></i>
                                    <span>28% vs mois dernier</span>
                                </p>
                            </div>
                            <div class="p-3 bg-purple-100 rounded-full text-purple-500">
                                <i class="fas fa-user-plus text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Second Row Stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <!-- Voitures disponibles -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Voitures disponibles</p>
                                <h3 class="text-2xl font-bold mt-1">18</h3>
                                <p class="text-xs text-gray-500 mt-1">Sur 32 total</p>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-full text-primary">
                                <i class="fas fa-car text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-primary h-2 rounded-full" style="width: 56%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Voitures en déplacement -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-gray-500">En déplacement</p>
                                <h3 class="text-2xl font-bold mt-1">10</h3>
                                <p class="text-xs text-gray-500 mt-1">Actuellement</p>
                            </div>
                            <div class="p-3 bg-orange-100 rounded-full text-orange-500">
                                <i class="fas fa-road text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Voitures en maintenance -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-gray-500">En maintenance</p>
                                <h3 class="text-2xl font-bold mt-1">4</h3>
                                <p class="text-xs text-gray-500 mt-1">En cours</p>
                            </div>
                            <div class="p-3 bg-red-100 rounded-full text-danger">
                                <i class="fas fa-tools text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Agents -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Agents</p>
                                <h3 class="text-2xl font-bold mt-1">12</h3>
                                <p class="text-xs text-gray-500 mt-1">Actifs</p>
                            </div>
                            <div class="p-3 bg-green-100 rounded-full text-secondary">
                                <i class="fas fa-user-tie text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts and Recent Reservations -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <!-- Revenue Chart -->
                    <div class="bg-white rounded-lg shadow p-6 lg:col-span-2">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Revenus mensuels</h3>
                            <div class="flex space-x-2">
                                <button class="px-3 py-1 text-xs bg-primary text-white rounded">Mois</button>
                                <button class="px-3 py-1 text-xs bg-gray-200 text-gray-700 rounded">Année</button>
                            </div>
                        </div>
                        <div class="chart-container">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>

                    <!-- Vehicle Status -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4">Statut des véhicules</h3>
                        <div class="flex justify-center">
                            <svg width="200" height="200" viewBox="0 0 200 200" class="mx-auto">
                                <!-- Background circle -->
                                <circle cx="100" cy="100" r="80" stroke="#E5E7EB" stroke-width="16" fill="none" />
                                <!-- Available -->
                                <circle class="progress-ring__circle" cx="100" cy="100" r="80" stroke="#3B82F6" stroke-width="16" stroke-dasharray="502.4" stroke-dashoffset="251.2" fill="none" />
                                <!-- On trip -->
                                <circle class="progress-ring__circle" cx="100" cy="100" r="80" stroke="#F59E0B" stroke-width="16" stroke-dasharray="502.4" stroke-dashoffset="351.68" fill="none" />
                                <!-- Maintenance -->
                                <circle class="progress-ring__circle" cx="100" cy="100" r="80" stroke="#EF4444" stroke-width="16" stroke-dasharray="502.4" stroke-dashoffset="452.16" fill="none" />
                            </svg>
                        </div>
                        <div class="mt-4 space-y-2">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-primary mr-2"></div>
                                <span class="text-sm">Disponibles (56%)</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-warning mr-2"></div>
                                <span class="text-sm">En déplacement (31%)</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-danger mr-2"></div>
                                <span class="text-sm">Maintenance (13%)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Reservations -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold">Réservations récentes</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Véhicule</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#RES001</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Moussa Diallo</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Toyota Corolla</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">15 Juin 2023</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Confirmée</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">75,000 FCFA</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="#" class="text-primary hover:text-blue-900 mr-3">Voir</a>
                                        <a href="#" class="text-danger hover:text-red-900">Annuler</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#RES002</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Aminata Ndiaye</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Hyundai Tucson</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">15 Juin 2023</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">En attente</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">85,000 FCFA</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="#" class="text-primary hover:text-blue-900 mr-3">Voir</a>
                                        <a href="#" class="text-danger hover:text-red-900">Annuler</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#RES003</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Papa Diop</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Mercedes Classe C</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">14 Juin 2023</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">En cours</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">120,000 FCFA</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="#" class="text-primary hover:text-blue-900 mr-3">Voir</a>
                                        <a href="#" class="text-danger hover:text-red-900">Annuler</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#RES004</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Fatou Bâ</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Kia Picanto</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">14 Juin 2023</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Terminée</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">60,000 FCFA</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="#" class="text-primary hover:text-blue-900 mr-3">Voir</a>
                                        <a href="#" class="text-danger hover:text-red-900">Annuler</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="bg-gray-50 px-6 py-3 flex items-center justify-between border-t border-gray-200">
                        <div class="text-sm text-gray-500">
                            Affichage <span class="font-medium">1</span> à <span class="font-medium">4</span> sur <span class="font-medium">24</span> résultats
                        </div>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 text-sm bg-gray-200 text-gray-700 rounded">Précédent</button>
                            <button class="px-3 py-1 text-sm bg-primary text-white rounded">Suivant</button>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
                datasets: [{
                    label: 'Revenus (FCFA)',
                    data: [850000, 920000, 1050000, 1150000, 1250000, 1450000, 0, 0, 0, 0, 0, 0],
                    backgroundColor: '#3B82F6',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value / 1000 + 'k';
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Vehicle Status Pie Chart (using SVG)
        document.addEventListener('DOMContentLoaded', function() {
            const availableCircle = document.querySelector('circle:nth-child(2)');
            const onTripCircle = document.querySelector('circle:nth-child(3)');
            const maintenanceCircle = document.querySelector('circle:nth-child(4)');
            
            // Set the correct dash offset based on percentage
            availableCircle.style.strokeDashoffset = '251.2'; // 56% of 502.4 (2πr)
            onTripCircle.style.strokeDashoffset = '351.68';   // 31% of 502.4
            maintenanceCircle.style.strokeDashoffset = '452.16'; // 13% of 502.4
        });
    </script>
</body>
</html>
@endsection
