<?php declare(strict_types=1); ?>
@extends('layouts.app')
@section('content')
<div class="bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête de page -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 text-center">
                <span class="inline-block mr-2">🔧</span> Liste des Maintenances
            </h1>
            <p class="mt-2 text-center text-gray-600">Gestion des entretiens et réparations</p>
        </div>

        <!-- Carte principale -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Message de succès -->
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mx-6 mt-6 rounded relative text-center font-medium">
                {{ session('success') }}
            </div>
            @endif

            <!-- Bouton d'ajout -->
            <div class="p-6 border-b border-gray-200 flex justify-end">
                <a href="{{ route('maintenances.create') }}" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200 flex items-center font-medium shadow-sm">
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Ajouter une Maintenance
                </a>
            </div>

            <!-- Tableau -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-green-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Voiture</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jour</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Heure</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motif</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Garagiste</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($maintenances as $maintenance)
                            <tr class="hover:bg-green-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $maintenance->car->marque }} {{ $maintenance->car->model }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $maintenance->jour }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $maintenance->heure }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $maintenance->motif }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $maintenance->garagiste }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format(floatval($maintenance->prix)) }} FCFA</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $maintenance->statut ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $maintenance->statut ? 'Payé' : 'Non Payé' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    <form action="{{ route('maintenances.destroy', $maintenance->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 hover:bg-red-200 rounded-md transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Supprimer
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Aucun résultat (montré conditionnellement) -->
            @if(count($maintenances) == 0)
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune maintenance trouvée</h3>
                <p class="mt-1 text-sm text-gray-500">Commencez par ajouter une nouvelle maintenance.</p>
            </div>
            @endif

            <!-- Pagination -->
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                <div class="flex justify-between items-center">
                    <div class="hidden sm:block">
                        <p class="text-sm text-gray-700">
                            Liste des maintenances
                        </p>
                    </div>
                    <div>
                        {{ $maintenances->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection