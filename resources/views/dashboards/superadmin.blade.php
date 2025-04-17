<?php declare(strict_types=1); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Location Voitures AIBD</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

</head>
<body class="body">

<div id="sidebar" class="d-flex flex-column flex-shrink-0 p-3 sidebar">
    <!-- Logo -->
    <div class="d-flex align-items-center mb-4 border-bottom pb-3">
        <i class="fas fa-car text-warning fs-4 me-2"></i>
        <span class="logo-text fs-5 fw-bold text-white">CPRO-VLC</span>
    </div>

    
   <!-- User Profile -->
    <div class="flex items-center justify-center text-white mb-4 border-b pb-3">
        <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Photo de profil" class="rounded-full w-[45px] h-[45px] object-cover mr-3">
        <div>
        <div class="font-semibold">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
        <small class="text-light text-opacity-75">
            {{ Auth::user()->getRoleNames()->first() }}
        </small>
    </div>
</div>


    <!-- Partie scrollable -->
    <div class="sidebar-links">
    <ul class="nav nav-pills flex-column mb-auto">
        <!-- Réservations -->
            <li><a href="{{ route('dashboard.superadmin') }}" class="nav-link"><span class="material-icons">assignment</span> Tableau de bord</a></li>
        <li><a href="{{ route('reservations.index') }}" class="nav-link"><span class="material-icons">assignment</span> Liste des réservations</a></li>
        <li><a href="{{ route('reservations.create') }}" class="nav-link"><span class="material-icons">add</span> Ajouter une réservation</a></li>
        <li><a href="{{ route('reservations.confirmed') }}" class="nav-link"><span class="material-icons">check_circle</span> Réservations Confirmées</a></li>
        <li><a href="{{ route('reservations.cancelled') }}" class="nav-link"><span class="material-icons">cancel</span> Réservations Annulées</a></li>

        <!-- Trajets -->
        <li><a href="{{ route('trips.index') }}" class="nav-link"><span class="material-icons">directions_car</span> Liste des trajets</a></li>
        <li><a href="{{ route('trips.create') }}" class="nav-link"><span class="material-icons">add</span> Ajouter un trajet</a></li>

        <!-- Super Admin -->
        <li><a href="{{ route('superadmins.index') }}" class="nav-link"><span class="material-icons">people</span> Liste des Super Admins</a></li>
        <li><a href="{{ route('admin.create.account.page') }}" class="nav-link"><span class="material-icons">person_add</span> Ajouter un Super Admin</a></li>

        <!-- Admin -->
        <li><a href="{{ route('admins.index') }}" class="nav-link"><span class="material-icons">people</span> Liste des admin</a></li>
        <li><a href="{{ route('admin.create.account.page') }}" class="nav-link"><span class="material-icons">person_add</span> Ajouter un admin</a></li>

        <!-- Agents -->
        <li><a href="{{ route('agents.index') }}" class="nav-link"><span class="material-icons">people</span> Liste des agents</a></li>
        <li><a href="{{ route('admin.create.account.page') }}" class="nav-link"><span class="material-icons">person_add</span> Ajouter un Agent</a></li>

        <!-- Chauffeurs -->
        <li><a href="{{ route('drivers.index') }}" class="nav-link"><span class="material-icons">directions_car</span> Liste des chauffeurs</a></li>
        <li><a href="{{ route('admin.create.account.page') }}" class="nav-link"><span class="material-icons">person_add</span> Ajouter un Chauffeur</a></li>
        <li><a href="{{ route('admin.assign-day-off') }}" class="nav-link"><span class="material-icons">event_busy</span> Jour de repos</a></li>

        <!-- Voitures assignées -->
        <li><a href="{{ route('cardrivers.index') }}" class="nav-link"><span class="material-icons">directions_car</span> Voitures & Chauffeurs</a></li>
        <li><a href="{{ route('cardrivers.create') }}" class="nav-link"><span class="material-icons">add</span> Ajouter un filiation</a></li>

        <!-- Maintenance -->
        <li><a href="{{ route('maintenances.index') }}" class="nav-link"><span class="material-icons">build</span> Voitures (Maintenance)</a></li>
        <li><a href="{{ route('maintenances.create') }}" class="nav-link"><span class="material-icons">add</span> Créer maintenance</a></li>

        <!-- Clients -->
        <li><a href="{{ route('clients.index') }}" class="nav-link"><span class="material-icons">person</span> Liste des Clients</a></li>
        <li><a href="{{ route('admin.create.account.page') }}" class="nav-link"><span class="material-icons">person_add</span> Ajouter un Client</a></li>

        <!-- Voitures -->
        <li><a href="{{ route('cars.index') }}" class="nav-link"><span class="material-icons">directions_car</span> Liste des Voitures</a></li>
        <li><a href="{{ route('cars.create') }}" class="nav-link"><span class="material-icons">add</span> Ajouter une voiture</a></li>
    </ul>
    </div>
</div>

<style>
    #sidebar {
        width: 250px;
        height: 100vh;
        background: linear-gradient(to bottom, #0a3d62, #0c2461);
        color: white;
        padding: 15px;
        position: fixed;
        top: 0;
        left: 0;
        font-weight: bold;
        display: flex;
        flex-direction: column;
    }

    .sticky-header {
        flex-shrink: 0;
        padding: 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-links {
        flex-grow: 1;
        overflow-y: auto;
        padding: 15px;
    }

    .logo-text {
        color: white;
    }

    .nav-link {
        display: flex;
        align-items: center;
        padding: 10px;
        color: #f1f1f1;
        font-weight: bold;
        text-decoration: none;
        border-radius: 8px;
        transition: background 0.3s ease-in-out;
    }

    .nav-link:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .material-icons {
        font-size: 20px;
        margin-right: 10px;
        color: white;
    }

    
</style>



        
        <!-- Main Content -->
        <div id="main-content" class="main-contentflex flex-col overflow-hidden ml-64">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm z-10">
                <div class="flex items-center justify-between px-6 py-3">
                    <div class="flex items-center">
                        <button id="sidebar-toggle" class="text-gray-500 hover:text-gray-600 focus:outline-none">
                            <i class="fas fa-bars"></i>
                        </button>
                        <div class="ml-4 relative">
                            <input type="text" placeholder="Rechercher..." class="border border-gray-300 rounded-full py-1 px-4 pl-10 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <i class="fas fa-search absolute left-3 top-2 text-gray-400"></i>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <button class="text-gray-500 hover:text-gray-700 focus:outline-none">
                                <i class="fas fa-bell text-xl"></i>
                                <span class="notification-badge bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                            </button>
                        </div>
                        <div class="relative">
                            <button class="text-gray-500 hover:text-gray-700 focus:outline-none">
                                <i class="fas fa-envelope text-xl"></i>
                                <span class="notification-badge bg-yellow-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">5</span>
                            </button>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white">
                            <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->last_name }}" class="w-12 h-12 rounded-full mr-4">
                            </div>
                            <span class="text-sm font-medium">Admin</span>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6 bg-gray-100">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Tableau de bord</h1>
                    <p class="text-gray-600">Aperçu des activités de location de voitures</p>
                </div>
                
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow p-6 transition duration-300 card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Clients ce mois</p>
                                <h3 class="text-2xl font-bold text-gray-800">142</h3>
                                <p class="text-sm text-green-500 mt-1">
                                    <i class="fas fa-arrow-up mr-1"></i> 12% vs mois dernier
                                </p>
                            </div>
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow p-6 transition duration-300 card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Réservations</p>
                                <h3 class="text-2xl font-bold text-gray-800">89</h3>
                                <p class="text-sm text-green-500 mt-1">
                                    <i class="fas fa-arrow-up mr-1"></i> 8% vs mois dernier
                                </p>
                            </div>
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <i class="fas fa-calendar-check text-xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow p-6 transition duration-300 card-hover">
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
                    
                    <div class="bg-white rounded-lg shadow p-6 transition duration-300 card-hover">
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
</body>
</html>

