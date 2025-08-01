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
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">{{ $driverGroup->group_name }}</h1>
            <div class="flex space-x-3">
                <a href="{{ route('driver-groups.edit', $driverGroup) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-edit mr-2"></i>Modifier
                </a>
                <a href="{{ route('driver-groups.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Informations du groupe -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Informations du groupe</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm font-medium text-gray-500">Nom du groupe</p>
                <p class="text-lg text-gray-900">{{ $driverGroup->group_name }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Statut</p>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $driverGroup->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $driverGroup->is_active ? 'Actif' : 'Inactif' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Membres du groupe -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Membres du groupe</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @if($driverGroup->driver1)
            <div class="border rounded-lg p-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="text-blue-600 font-semibold">{{ substr($driverGroup->driver1->first_name, 0, 1) }}{{ substr($driverGroup->driver1->last_name, 0, 1) }}</span>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">{{ $driverGroup->driver1->first_name }} {{ $driverGroup->driver1->last_name }}</p>
                        <p class="text-sm text-gray-500">{{ $driverGroup->driver1->email }}</p>
                    </div>
                </div>
            </div>
            @endif

            @if($driverGroup->driver2)
            <div class="border rounded-lg p-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <span class="text-green-600 font-semibold">{{ substr($driverGroup->driver2->first_name, 0, 1) }}{{ substr($driverGroup->driver2->last_name, 0, 1) }}</span>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">{{ $driverGroup->driver2->first_name }} {{ $driverGroup->driver2->last_name }}</p>
                        <p class="text-sm text-gray-500">{{ $driverGroup->driver2->email }}</p>
                    </div>
                </div>
            </div>
            @endif

            @if($driverGroup->driver3)
            <div class="border rounded-lg p-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                        <span class="text-yellow-600 font-semibold">{{ substr($driverGroup->driver3->first_name, 0, 1) }}{{ substr($driverGroup->driver3->last_name, 0, 1) }}</span>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">{{ $driverGroup->driver3->first_name }} {{ $driverGroup->driver3->last_name }}</p>
                        <p class="text-sm text-gray-500">{{ $driverGroup->driver3->email }}</p>
                    </div>
                </div>
            </div>
            @endif

            @if($driverGroup->driver4)
            <div class="border rounded-lg p-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                        <span class="text-purple-600 font-semibold">{{ substr($driverGroup->driver4->first_name, 0, 1) }}{{ substr($driverGroup->driver4->last_name, 0, 1) }}</span>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">{{ $driverGroup->driver4->first_name }} {{ $driverGroup->driver4->last_name }}</p>
                        <p class="text-sm text-gray-500">{{ $driverGroup->driver4->email }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Planning de la semaine actuelle -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Planning de la semaine actuelle</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jour</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chauffeurs en repos</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chauffeurs disponibles</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($weeklySchedule as $date => $schedule)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $schedule['day_name'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $schedule['date']->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @foreach($schedule['rest_drivers'] as $driverId)
                                @php
                                    $driver = $driverGroup->getAllDrivers()->firstWhere('id', $driverId);
                                @endphp
                                @if($driver)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mr-1">
                                        {{ $driver->first_name }} {{ $driver->last_name }}
                                    </span>
                                @endif
                            @endforeach
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @foreach($schedule['available_drivers'] as $driverId)
                                @php
                                    $driver = $driverGroup->getAllDrivers()->firstWhere('id', $driverId);
                                @endphp
                                @if($driver)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mr-1">
                                        {{ $driver->first_name }} {{ $driver->last_name }}
                                    </span>
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Planning des prochaines semaines -->
    @foreach($nextWeeksSchedule as $weekNumber => $weekSchedule)
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Semaine {{ $weekNumber }} ({{ $weekSchedule[array_key_first($weekSchedule)]['date']->format('d/m/Y') }} - {{ $weekSchedule[array_key_last($weekSchedule)]['date']->format('d/m/Y') }})</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jour</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chauffeurs en repos</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chauffeurs disponibles</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($weekSchedule as $date => $schedule)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $schedule['day_name'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $schedule['date']->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @foreach($schedule['rest_drivers'] as $driverId)
                                @php
                                    $driver = $driverGroup->getAllDrivers()->firstWhere('id', $driverId);
                                @endphp
                                @if($driver)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mr-1">
                                        {{ $driver->first_name }} {{ $driver->last_name }}
                                    </span>
                                @endif
                            @endforeach
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @foreach($schedule['available_drivers'] as $driverId)
                                @php
                                    $driver = $driverGroup->getAllDrivers()->firstWhere('id', $driverId);
                                @endphp
                                @if($driver)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mr-1">
                                        {{ $driver->first_name }} {{ $driver->last_name }}
                                    </span>
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endforeach

    <!-- Actions de rotation -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Actions de rotation</h2>
        <div class="flex space-x-4">
            <form action="{{ route('driver-groups.advance-rotation', $driverGroup) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-forward mr-2"></i>Avancer la rotation
                </button>
            </form>
            <form action="{{ route('driver-groups.reverse-rotation', $driverGroup) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-backward mr-2"></i>Reculer la rotation
                </button>
            </form>
            <form action="{{ route('driver-groups.reset-rotation', $driverGroup) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-undo mr-2"></i>Réinitialiser la rotation
                </button>
            </form>
        </div>
    </div>
</div>
@endsection 