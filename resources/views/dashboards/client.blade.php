<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Espace Client - CarReserv</title>
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
        .sidebar {
            transition: all 0.3s;
        }
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
                    <h2 class="text-xl font-semibold text-gray-800">Mon Tableau de bord</h2>
                    <div class="flex items-center space-x-4">
                        <button class="px-4 py-2 bg-primary text-white rounded-lg flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            <span>Nouvelle réservation</span>
                        </button>
                        <button class="p-2 text-gray-600 hover:text-primary">
                            <i class="fas fa-bell"></i>
                            <span class="absolute top-2 right-2 h-2 w-2 rounded-full bg-danger"></span>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <main class="p-6">
                <!-- Welcome Banner -->
                <div class="bg-gradient-to-r from-primary to-blue-400 rounded-lg shadow p-6 mb-6 text-white">
                    <h2 class="text-2xl font-bold mb-2">Bonjour, Moussa Diallo !</h2>
                    <p class="mb-4">Bienvenue dans votre espace client. Gérez facilement vos réservations et vos factures.</p>
                    <div class="flex space-x-4">
                        <div class="bg-white bg-opacity-20 p-3 rounded-lg flex items-center">
                            <i class="fas fa-star mr-2"></i>
                            <span>Client VIP</span>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-lg flex items-center">
                            <i class="fas fa-calendar-check mr-2"></i>
                            <span>12 réservations cette année</span>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <!-- Réservations du jour -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Réservations aujourd'hui</p>
                                <h3 class="text-2xl font-bold mt-1">2</h3>
                                <p class="text-xs text-gray-500 mt-1">En cours et à venir</p>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-full text-primary">
                                <i class="fas fa-calendar-day text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Réservations du mois -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Réservations ce mois</p>
                                <h3 class="text-2xl font-bold mt-1">5</h3>
                                <p class="text-xs text-green-500 mt-1 flex items-center">
                                    <i class="fas fa-arrow-up mr-1"></i>
                                    <span>2 de plus que le mois dernier</span>
                                </p>
                            </div>
                            <div class="p-3 bg-green-100 rounded-full text-secondary">
                                <i class="fas fa-calendar-alt text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Factures impayées -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Factures impayées</p>
                                <h3 class="text-2xl font-bold mt-1">3</h3>
                                <p class="text-xs text-gray-500 mt-1">Total: 225,000 FCFA</p>
                            </div>
                            <div class="p-3 bg-red-100 rounded-full text-danger">
                                <i class="fas fa-exclamation-circle text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Points fidélité -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Points fidélité</p>
                                <h3 class="text-2xl font-bold mt-1">1,250</h3>
                                <p class="text-xs text-gray-500 mt-1">50 points jusqu'au prochain avantage</p>
                            </div>
                            <div class="p-3 bg-yellow-100 rounded-full text-warning">
                                <i class="fas fa-award text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts and Recent Reservations -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <!-- Réservations mensuelles -->
                    <div class="bg-white rounded-lg shadow p-6 lg:col-span-2">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Mes réservations mensuelles</h3>
                            <div class="flex space-x-2">
                                <button class="px-3 py-1 text-xs bg-primary text-white rounded">2023</button>
                                <button class="px-3 py-1 text-xs bg-gray-200 text-gray-700 rounded">2022</button>
                            </div>
                        </div>
                        <div class="chart-container">
                            <canvas id="reservationsChart"></canvas>
                        </div>
                    </div>

                    <!-- Prochaine réservation -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4">Ma prochaine réservation</h3>
                        <div class="bg-blue-50 p-4 rounded-lg mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-medium">Toyota Corolla</span>
                                <span class="text-sm bg-primary text-white px-2 py-1 rounded">Confirmée</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600 mb-2">
                                <i class="fas fa-calendar-day mr-2"></i>
                                <span>20 Juin 2023</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600 mb-3">
                                <i class="fas fa-clock mr-2"></i>
                                <span>08:00 - 18:00</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-medium">85,000 FCFA</span>
                                <div class="flex space-x-2">
                                    <button class="p-1 text-primary hover:text-blue-700">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="p-1 text-danger hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <h4 class="text-md font-medium mt-6 mb-3">Actions rapides</h4>
                        <div class="grid grid-cols-2 gap-3">
                            <button class="p-3 bg-green-100 text-green-800 rounded-lg flex flex-col items-center">
                                <i class="fas fa-file-invoice mb-1"></i>
                                <span class="text-xs">Payer facture</span>
                            </button>
                            <button class="p-3 bg-blue-100 text-blue-800 rounded-lg flex flex-col items-center">
                                <i class="fas fa-car mb-1"></i>
                                <span class="text-xs">Réserver</span>
                            </button>
                            <button class="p-3 bg-purple-100 text-purple-800 rounded-lg flex flex-col items-center">
                                <i class="fas fa-question mb-1"></i>
                                <span class="text-xs">Aide</span>
                            </button>
                            <button class="p-3 bg-yellow-100 text-yellow-800 rounded-lg flex flex-col items-center">
                                <i class="fas fa-star mb-1"></i>
                                <span class="text-xs">Avantages</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Dernières réservations -->
                <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-semibold">Mes dernières réservations</h3>
                        <a href="#" class="text-sm text-primary hover:underline">Voir toutes</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Véhicule</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Heure</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#RES023</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Toyota Corolla</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">20 Juin 2023</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">08:00 - 18:00</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Confirmée</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">85,000 FCFA</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="#" class="text-primary hover:text-blue-900 mr-3">Voir</a>
                                        <a href="#" class="text-danger hover:text-red-900">Annuler</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#RES022</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Hyundai Tucson</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">15 Juin 2023</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">09:00 - 20:00</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Terminée</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">95,000 FCFA</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="#" class="text-primary hover:text-blue-900 mr-3">Voir</a>
                                        <a href="#" class="text-danger hover:text-red-900">Facture</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#RES021</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Kia Picanto</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">10 Juin 2023</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">07:30 - 19:30</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Terminée</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">65,000 FCFA</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="#" class="text-primary hover:text-blue-900 mr-3">Voir</a>
                                        <a href="#" class="text-danger hover:text-red-900">Facture</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#RES020</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Mercedes Classe C</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">5 Juin 2023</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">10:00 - 22:00</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Annulée</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">-</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="#" class="text-primary hover:text-blue-900 mr-3">Voir</a>
                                        <a href="#" class="text-secondary hover:text-green-900">Réserver</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="bg-gray-50 px-6 py-3 flex items-center justify-between border-t border-gray-200">
                        <div class="text-sm text-gray-500">
                            Affichage <span class="font-medium">1</span> à <span class="font-medium">4</span> sur <span class="font-medium">12</span> résultats
                        </div>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 text-sm bg-gray-200 text-gray-700 rounded">Précédent</button>
                            <button class="px-3 py-1 text-sm bg-primary text-white rounded">Suivant</button>
                        </div>
                    </div>
                </div>

                <!-- Factures impayées -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-semibold">Mes factures impayées</h3>
                        <a href="#" class="text-sm text-primary hover:underline">Voir toutes</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">N° Facture</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Réservation</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Échéance</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#FAC2023-056</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">15 Juin 2023</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#RES022</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">25 Juin 2023</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">95,000 FCFA</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">En retard</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="#" class="text-primary hover:text-blue-900 mr-3">Payer</a>
                                        <a href="#" class="text-secondary hover:text-green-900">Télécharger</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#FAC2023-048</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">10 Juin 2023</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#RES021</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">20 Juin 2023</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">65,000 FCFA</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Impayée</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="#" class="text-primary hover:text-blue-900 mr-3">Payer</a>
                                        <a href="#" class="text-secondary hover:text-green-900">Télécharger</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#FAC2023-037</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">25 Mai 2023</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#RES019</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">5 Juin 2023</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">65,000 FCFA</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Impayée</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="#" class="text-primary hover:text-blue-900 mr-3">Payer</a>
                                        <a href="#" class="text-secondary hover:text-green-900">Télécharger</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="bg-gray-50 px-6 py-3 flex items-center justify-between border-t border-gray-200">
                        <div class="text-sm text-gray-500">
                            Total impayé: <span class="font-medium">225,000 FCFA</span>
                        </div>
                        <button class="px-4 py-2 bg-primary text-white rounded-lg flex items-center">
                            <i class="fas fa-credit-card mr-2"></i>
                            <span>Payer tout</span>
                        </button>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Reservations Chart
        const reservationsCtx = document.getElementById('reservationsChart').getContext('2d');
        const reservationsChart = new Chart(reservationsCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
                datasets: [{
                    label: 'Réservations',
                    data: [1, 0, 2, 1, 3, 5, 0, 0, 0, 0, 0, 0],
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderColor: '#3B82F6',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
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
    </script>
</body>
</html>
@endsection
