<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Chauffeurs</title>
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
        .driver-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .status-badge {
            top: -8px;
            right: -8px;
        }
        .search-input:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête de page amélioré -->
        <div class="mb-8 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-full mb-4">
                <i class="fas fa-users text-white text-2xl"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">
                Équipe de Chauffeurs
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Gérez votre équipe de conducteurs professionnels et suivez leurs informations essentielles
            </p>
            <div class="mt-4 flex justify-center space-x-4">
                <div class="bg-white rounded-lg px-4 py-2 shadow-sm">
                    <span class="text-sm font-medium text-gray-500">Total:</span>
                    <span class="text-lg font-bold text-blue-600 ml-2">{{ $drivers->total() }}</span>
                </div>
                <div class="bg-white rounded-lg px-4 py-2 shadow-sm">
                    <span class="text-sm font-medium text-gray-500">Disponibles:</span>
                    <span class="text-lg font-bold text-green-600 ml-2">{{ $drivers->where('availability_status', 'available')->count() }}</span>
                </div>
                <div class="bg-white rounded-lg px-4 py-2 shadow-sm">
                    <span class="text-sm font-medium text-gray-500">En repos:</span>
                    <span class="text-lg font-bold text-orange-600 ml-2">{{ $drivers->where('availability_status', 'rest')->count() }}</span>
                </div>
            </div>
        </div>

        <!-- Carte principale améliorée -->
        <div class="bg-white rounded-xl shadow-xl overflow-hidden border border-gray-100">
            <!-- Barre de recherche et filtres -->
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                    <div class="relative flex-1 max-w-md">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" id="searchInput" class="pl-12 pr-4 py-3 border border-gray-300 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 search-input" placeholder="Rechercher un chauffeur...">
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <button id="filterAll" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium">
                            <i class="fas fa-list mr-2"></i>Tous
                        </button>
                        <button id="filterAvailable" class="px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors duration-200 font-medium">
                            <i class="fas fa-check-circle mr-2"></i>Disponibles
                        </button>
                        <button id="filterRest" class="px-4 py-2 bg-orange-100 text-orange-700 rounded-lg hover:bg-orange-200 transition-colors duration-200 font-medium">
                            <i class="fas fa-bed mr-2"></i>En repos
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tableau amélioré -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-blue-600 to-indigo-600">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                <i class="fas fa-id-card mr-2"></i>ID
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                <i class="fas fa-user mr-2"></i>Chauffeur
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                <i class="fas fa-envelope mr-2"></i>Contact
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                <i class="fas fa-map-marker-alt mr-2"></i>Adresse
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                <i class="fas fa-calendar mr-2"></i>Disponibilité
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                <i class="fas fa-cogs mr-2"></i>Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="driversTableBody">
                        @foreach($drivers as $driver)
                            <tr class="driver-card transition-all duration-200 hover:bg-blue-50" data-driver-id="{{ $driver->id }}" data-availability="{{ $driver->availability_status ?? 'unknown' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center">
                                                <span class="text-sm font-medium text-white">{{ substr($driver->first_name, 0, 1) }}{{ substr($driver->last_name, 0, 1) }}</span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">#{{ $driver->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $driver->first_name }} {{ $driver->last_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $driver->email }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <i class="fas fa-phone text-gray-400 mr-2"></i>
                                        <span class="text-sm text-gray-900">{{ $driver->phone_number ?? 'Non renseigné' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="max-w-xs">
                                        <div class="text-sm text-gray-900 truncate" title="{{ $driver->address }}">
                                            {{ $driver->address ?? 'Non renseignée' }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($driver->availability_status === 'rest')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            <i class="fas fa-bed mr-1"></i>
                                            {{ $driver->availability_text }}
                                        </span>
                                    @elseif($driver->availability_status === 'available')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            {{ $driver->availability_text }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-question-circle mr-1"></i>
                                            {{ $driver->availability_text }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button class="text-blue-600 hover:text-blue-900 transition-colors duration-200" title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="text-green-600 hover:text-green-900 transition-colors duration-200" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="text-red-600 hover:text-red-900 transition-colors duration-200" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Aucun résultat -->
            @if(count($drivers) == 0)
            <div class="px-6 py-16 text-center">
                <div class="mx-auto h-24 w-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-users text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun chauffeur trouvé</h3>
                <p class="text-gray-500 mb-6">Commencez par ajouter un nouveau chauffeur à votre équipe.</p>
                <a href="{{ route('admin.create.account.page') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Ajouter un chauffeur
                </a>
            </div>
            @endif

            <!-- Pagination améliorée -->
            @if($drivers->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="text-sm text-gray-700 mb-4 sm:mb-0">
                        <span class="font-medium">Affichage de {{ $drivers->firstItem() ?? 0 }} à {{ $drivers->lastItem() ?? 0 }} sur {{ $drivers->total() }} chauffeurs</span>
                    </div>
                    <div class="flex justify-center">
                        {{ $drivers->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const filterAll = document.getElementById('filterAll');
    const filterAvailable = document.getElementById('filterAvailable');
    const filterRest = document.getElementById('filterRest');
    const tableBody = document.getElementById('driversTableBody');
    const rows = tableBody.querySelectorAll('tr');

    // Fonction de recherche
    function filterDrivers() {
        const searchTerm = searchInput.value.toLowerCase();

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const isVisible = text.includes(searchTerm);
            row.style.display = isVisible ? '' : 'none';
        });
    }

        // Fonction de filtrage par statut
    function filterByStatus(status) {
        rows.forEach(row => {
            const availability = row.getAttribute('data-availability');
            let show = false;
            
            switch(status) {
                case 'all':
                    show = true;
                    break;
                case 'available':
                    show = availability === 'available';
                    break;
                case 'rest':
                    show = availability === 'rest';
                    break;
            }
            
            row.style.display = show ? '' : 'none';
        });
    }

    // Event listeners
    searchInput.addEventListener('input', filterDrivers);

    filterAll.addEventListener('click', () => {
        filterByStatus('all');
        updateFilterButtons('all');
    });

    filterAvailable.addEventListener('click', () => {
        filterByStatus('available');
        updateFilterButtons('available');
    });

    filterRest.addEventListener('click', () => {
        filterByStatus('rest');
        updateFilterButtons('rest');
    });

    function updateFilterButtons(activeFilter) {
        // Reset all buttons
        [filterAll, filterAvailable, filterRest].forEach(btn => {
            btn.className = btn.className.replace(/bg-(blue|green|orange)-600|bg-(blue|green|orange)-100/g, '');
            btn.className = btn.className.replace(/text-white|text-(green|orange)-700/g, '');
        });

        // Set active button
        switch(activeFilter) {
            case 'all':
                filterAll.className += ' bg-blue-600 text-white';
                break;
            case 'available':
                filterAvailable.className += ' bg-green-600 text-white';
                break;
            case 'rest':
                filterRest.className += ' bg-orange-600 text-white';
                break;
        }
    }

    // Initialize with all filter active
    updateFilterButtons('all');
});
</script>

</body>
</html>
@endsection
