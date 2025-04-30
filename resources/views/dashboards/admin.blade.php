<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')

    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Widgets -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <main class="flex-1 overflow-y-auto p-6 bg-gray-100">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Tableau de bord</h1>
                    <p class="text-gray-600">Aperçu des activités de location de voitures</p>
                </div>
                
                <!-- Stats Cards -->
                <div class="flex flex-wrap gap-2 mb-6">
                <div class="bg-white rounded-lg shadow p-6 transition duration-300 card-hover flex-1 min-w-[250px] max-w-[300px]">
                <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Clients ce mois</p>
                                <h3 class="text-2xl font-bold text-gray-800">142</h3>
                                <h1>Nombre de réservations : {{ $reservationsCount }}</h1>

                                <p class="text-sm text-green-500 mt-1">
                                    <i class="fas fa-arrow-up mr-1"></i> 12% vs mois dernier
                                </p>
                            </div>
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow p-6 transition duration-300 card-hover flex-1 min-w-[250px] max-w-[300px]">
                    <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Réservations</p>
                                <h3 class="text-2xl font-bold text-gray-800">89</h3>
                                <h1>Nombre d'utilisateurs : {{ $usersCount }}</h1>
                                <p class="text-sm text-green-500 mt-1">
                                    <i class="fas fa-arrow-up mr-1"></i> 8% vs mois dernier
                                </p>
                            </div>
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <i class="fas fa-calendar-check text-xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow p-6 transition duration-300 card-hover flex-1 min-w-[250px] max-w-[300px]">
                    <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Véhicules en maintenance</p>
                                <h3 class="text-2xl font-bold text-gray-800">7</h3>
                                <p class="text-sm text-red-500 mt-1">
                                    <i class="fas fa-arrow-up mr-1"></i> 2 de plus que la moyenne
                                </p>
                            </div>
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                <i class="fas fa-tools text-xl"></i>
                            </div>
                        </div>
                    </div>
                    
                <div class="bg-white rounded-lg shadow p-6 transition duration-300 card-hover flex-1 min-w-[250px] max-w-[300px]">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Revenus ce mois</p>
                                <h3 class="text-2xl font-bold text-gray-800">12,450,000 FCFA</h3>
                                <p class="text-sm text-green-500 mt-1">
                                    <i class="fas fa-arrow-up mr-1"></i> 15% vs mois dernier
                                </p>
                            </div>
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                <i class="fas fa-money-bill-wave text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Réservations par type</h3>
                            <div class="flex space-x-2">
                                <button class="px-3 py-1 text-xs bg-blue-100 text-blue-600 rounded-full">Mois</button>
                                <button class="px-3 py-1 text-xs bg-gray-100 text-gray-600 rounded-full">Année</button>
                            </div>
                        </div>
                        <div class="h-64">
                            <canvas id="reservationTypeChart"></canvas>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Clients nouveaux vs récurrents</h3>
                            <div class="flex space-x-2">
                                <button class="px-3 py-1 text-xs bg-blue-100 text-blue-600 rounded-full">Mois</button>
                                <button class="px-3 py-1 text-xs bg-gray-100 text-gray-600 rounded-full">Année</button>
                            </div>
                        </div>
                        <div class="h-64">
                            <canvas id="clientTypeChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activities and Maintenance -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Dernières réservations</h3>
                            <a href="#" class="text-sm text-blue-600 hover:underline">Voir tout</a>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <div class="p-2 rounded-full bg-green-100 text-green-600">
                                    <i class="fas fa-car"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <h4 class="font-medium">M. Diop</h4>
                                        <span class="text-xs text-gray-500">Aujourd'hui, 10:30</span>
                                    </div>
                                    <p class="text-sm text-gray-600">Toyota Corolla - AIBD à Dakar (3 jours)</p>
                                    <p class="text-xs text-blue-600">12,000 FCFA/jour</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3">
                                <div class="p-2 rounded-full bg-blue-100 text-blue-600">
                                    <i class="fas fa-car"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <h4 class="font-medium">Mme. Ndiaye</h4>
                                        <span class="text-xs text-gray-500">Hier, 15:45</span>
                                    </div>
                                    <p class="text-sm text-gray-600">Mercedes Classe E - Avec chauffeur (1 semaine)</p>
                                    <p class="text-xs text-blue-600">25,000 FCFA/jour</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3">
                                <div class="p-2 rounded-full bg-purple-100 text-purple-600">
                                    <i class="fas fa-car"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <h4 class="font-medium">Entreprise XYZ</h4>
                                        <span class="text-xs text-gray-500">Hier, 09:20</span>
                                    </div>
                                    <p class="text-sm text-gray-600">2x Duster - Hors Dakar (1 mois)</p>
                                    <p class="text-xs text-blue-600">15,000 FCFA/jour/voiture</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Véhicules en maintenance</h3>
                            <a href="#" class="text-sm text-blue-600 hover:underline">Voir tout</a>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <div class="p-2 rounded-full bg-red-100 text-red-600">
                                    <i class="fas fa-tools"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <h4 class="font-medium">Toyota RAV4 (AA-1234-AB)</h4>
                                        <span class="text-xs text-gray-500">Depuis 5 jours</span>
                                    </div>
                                    <p class="text-sm text-gray-600">Révision générale + Freins</p>
                                    <p class="text-xs text-red-600">Retour prévu: 12/06/2023</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3">
                                <div class="p-2 rounded-full bg-yellow-100 text-yellow-600">
                                    <i class="fas fa-tools"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <h4 class="font-medium">Hyundai Tucson (AB-5678-CD)</h4>
                                        <span class="text-xs text-gray-500">Depuis 2 jours</span>
                                    </div>
                                    <p class="text-sm text-gray-600">Changement pneus + Vidange</p>
                                    <p class="text-xs text-yellow-600">En cours</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3">
                                <div class="p-2 rounded-full bg-blue-100 text-blue-600">
                                    <i class="fas fa-tools"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <h4 class="font-medium">Nissan Qashqai (AC-9012-EF)</h4>
                                        <span class="text-xs text-gray-500">Aujourd'hui</span>
                                    </div>
                                    <p class="text-sm text-gray-600">Contrôle technique</p>
                                    <p class="text-xs text-blue-600">En attente diagnostic</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Toggle sidebar
        document.getElementById('sidebar-toggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            
            sidebar.classList.toggle('sidebar-collapsed');
            mainContent.classList.toggle('main-content-expanded');
            mainContent.classList.toggle('ml-64');
        });

        // Charts
        document.addEventListener('DOMContentLoaded', function() {
            // Reservation Type Chart
            const reservationTypeCtx = document.getElementById('reservationTypeChart').getContext('2d');
            const reservationTypeChart = new Chart(reservationTypeCtx, {
                type: 'doughnut',
                data: {
                    labels: ['AIBD', 'Privé', 'Avec chauffeur Dakar', 'Avec chauffeur Hors Dakar'],
                    datasets: [{
                        data: [35, 20, 25, 20],
                        backgroundColor: [
                            '#3B82F6',
                            '#10B981',
                            '#F59E0B',
                            '#8B5CF6'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                        }
                    }
                }
            });

            // Client Type Chart
            const clientTypeCtx = document.getElementById('clientTypeChart').getContext('2d');
            const clientTypeChart = new Chart(clientTypeCtx, {
                type: 'bar',
                data: {
                    labels: ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4'],
                    datasets: [
                        {
                            label: 'Nouveaux clients',
                            data: [15, 20, 25, 30],
                            backgroundColor: '#3B82F6',
                            borderRadius: 4
                        },
                        {
                            label: 'Clients récurrents',
                            data: [20, 25, 30, 32],
                            backgroundColor: '#10B981',
                            borderRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });
        });
    </script>
            </div>

        </div>
    </div>

@endsection
