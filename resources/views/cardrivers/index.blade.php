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
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 p-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-slate-900">Gestion Véhicules & Chauffeurs</h1>
                            <p class="text-slate-600 mt-1">Assignation et suivi des chauffeurs aux véhicules</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="text-right">
                            <div class="text-sm text-slate-500">Total véhicules</div>
                            <div class="text-2xl font-bold text-blue-600">{{ $cars->total() }}</div>
                        </div>
                        <a href="{{ route('cardrivers.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Assigner un Chauffeur
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
            @if(session('success'))
            <div class="mb-6">
                <div class="bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-200 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center">
                        <div class="p-2 bg-emerald-100 rounded-full">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-emerald-800">Succès</h3>
                            <p class="text-emerald-700">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Vehicles Grid -->
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                        @foreach($cars as $car)
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                    <!-- Vehicle Header -->
                    <div class="bg-gradient-to-r from-slate-50 to-slate-100 p-6 border-b border-slate-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-blue-100 rounded-lg">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900">{{ $car->marque }} {{ $car->model }}</h3>
                                    <p class="text-sm text-slate-600">Matricule: {{ $car->matricule }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs text-slate-500">Véhicule ID</div>
                                <div class="text-sm font-semibold text-blue-600">#{{ $car->id }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Drivers Section -->
                    <div class="p-6">
                        <div class="mb-4">
                            <h4 class="text-sm font-semibold text-slate-700 mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Chauffeurs assignés ({{ $car->drivers->count() }})
                            </h4>
                            
                                    @if($car->drivers->isEmpty())
                                <div class="text-center py-8">
                                    <div class="p-3 bg-slate-100 rounded-full w-fit mx-auto mb-3">
                                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-slate-500 font-medium">Aucun chauffeur assigné</p>
                                    <p class="text-xs text-slate-400 mt-1">Cliquez sur "Assigner" pour ajouter un chauffeur</p>
                                </div>
                                    @else
                                <div class="space-y-3">
                                            @foreach($car->drivers as $driver)
                                        <div class="flex items-center justify-between p-3 bg-gradient-to-r from-emerald-50 to-green-50 rounded-xl border border-emerald-200">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 bg-gradient-to-r from-emerald-500 to-green-500 rounded-full flex items-center justify-center">
                                                    <span class="text-white font-semibold text-sm">{{ substr($driver->first_name, 0, 1) }}{{ substr($driver->last_name, 0, 1) }}</span>
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-slate-900">{{ $driver->first_name }} {{ $driver->last_name }}</p>
                                                    <p class="text-xs text-slate-600">{{ $driver->email }}</p>
                                                </div>
                                        </div>
                                            <form action="{{ route('car_drivers.destroy', ['car_id' => $car->id, 'user_id' => $driver->id]) }}" method="POST" class="ml-3">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-all duration-200 group"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir retirer ce chauffeur ?')">
                                                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                        @endforeach
                                    </div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-3 pt-4 border-t border-slate-200">
                            <a href="{{ route('cardrivers.create') }}" class="flex-1 text-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-semibold rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200">
                                Assigner Chauffeur
                            </a>
                            @if($car->drivers->isNotEmpty())
                                <button class="px-4 py-2 bg-slate-100 text-slate-700 text-sm font-semibold rounded-lg hover:bg-slate-200 transition-all duration-200">
                                    Voir Détails
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                        @endforeach
            </div>

            <!-- Pagination -->
        @if($cars->hasPages())
            <div class="mt-8">
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                <div class="flex justify-center">
                    {{ $cars->links() }}
                </div>
            </div>
        </div>
        @endif

        <!-- Empty State -->
        @if($cars->isEmpty())
            <div class="text-center py-16">
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-12">
                    <div class="p-4 bg-slate-100 rounded-full w-fit mx-auto mb-6">
                        <svg class="w-16 h-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-2">Aucun véhicule trouvé</h3>
                    <p class="text-slate-600 mb-8">Commencez par ajouter des véhicules à votre flotte pour pouvoir assigner des chauffeurs.</p>
                    <a href="{{ route('cars.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Ajouter un Véhicule
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    // Add some interactive functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Add loading state to forms
        const forms = document.querySelectorAll('form[method="POST"]');
        forms.forEach(form => {
            form.addEventListener('submit', function() {
                const button = form.querySelector('button[type="submit"]');
                if (button) {
                    button.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-current" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Suppression...';
                    button.disabled = true;
                }
            });
        });
    });
</script>

@endsection