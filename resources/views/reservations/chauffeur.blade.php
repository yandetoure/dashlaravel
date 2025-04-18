<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')

@php
    \Carbon\Carbon::setLocale('fr');
@endphp
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Gestion des Réservations</h1>
            <p class="text-gray-600">Visualisez et gérez toutes les réservations de votre flotte</p>
        </div>
    </div>

    @if ($reservations->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach ($reservations as $reservation)
                <div class="reservation-card bg-white rounded-2xl overflow-hidden shadow-md border border-gray-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="p-5">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">#RES-{{ str_pad((string) $reservation->id, 6, '0', STR_PAD_LEFT) }}</h3>
                                <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($reservation->date)->translatedFormat('d F Y') }}</p>
                            </div>
                            <span class="status-badge 
                                {{ $reservation->status === 'confirmed' ? 'confirmed' : ($reservation->status === 'pending' ? 'pending' : 'cancelled') }}">
                                @if($reservation->status === 'confirmée')
                                    <i class="fas fa-check-circle mr-1"></i> Confirmée
                                @elseif($reservation->status === 'En_attente')
                                    <i class="fas fa-clock mr-1"></i> En attente
                                @else
                                    <i class="fas fa-times-circle mr-1"></i> Annulée
                                @endif
                            </span>
                        </div>

                        <div class="space-y-3">
                            <!-- Client -->
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex justify-center items-center text-blue-600 mr-3">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Client</p>
                                    <p class="font-medium">{{ $reservation->client->first_name }} {{ $reservation->client->last_name }}</p>
                                </div>
                            </div>

                            <!-- Chauffeur -->
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-green-100 flex justify-center items-center text-green-600 mr-3">
                                    <i class="fas fa-id-card"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Chauffeur</p>
                                    <p class="font-medium">{{ $reservation->chauffeur->first_name }} {{ $reservation->chauffeur->last_name }}</p>
                                </div>
                            </div>

                            <!-- Voyage -->
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-purple-100 flex justify-center items-center text-purple-600 mr-3">
                                    <i class="fas fa-route"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Voyage</p>
                                    <p class="font-medium">{{ $reservation->trip->name }}</p>
                                </div>
                            </div>

                            <!-- Heure Ramassage -->
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-yellow-100 flex justify-center items-center text-yellow-600 mr-3">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Heure de ramassage</p>
                                    <p class="font-medium">{{ $reservation->heure_ramassage }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-5 py-3 flex justify-between items-center border-t border-gray-100">
                        <div class="flex space-x-2">
                            <a href="{{ route('reservations.confirm', $reservation) }}" class="action-btn bg-green-100 text-green-600 hover:bg-green-200 p-2 rounded-full">
                                <i class="fas fa-check"></i>
                            </a>
                            <a href="{{ route('reservations.cancel', $reservation) }}" class="action-btn bg-red-100 text-red-600 hover:bg-red-200 p-2 rounded-full">
                                <i class="fas fa-times"></i>
                            </a>
                            <form action="{{ route('reservations.destroy', $reservation) }}" method="POST" onsubmit="return confirm('Supprimer cette réservation ?')" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn bg-gray-100 text-gray-600 hover:bg-gray-200 p-2 rounded-full">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                        <button class="action-btn bg-gray-100 text-gray-600 hover:bg-gray-200 px-3 py-1 rounded-lg text-sm">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $reservations->links('vendor.pagination.tailwind') }}
        </div>
    @else
        <p class="text-center text-gray-600">Aucune réservation trouvée.</p>
    @endif
</div>

<style>
    .reservation-card {
        border-radius: 1rem;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid #e5e7eb;
        background: linear-gradient(to bottom, #f9fafb, #ffffff);
    }

    .reservation-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
    }

    .status-badge {
        font-size: 0.75rem;
        padding: 0.35rem 0.75rem;
        border-radius: 9999px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .confirmed {
        background-color: #34d399; /* emerald-400 */
        color: white;
    }

    .pending {
        background-color: #fbbf24; /* amber-400 */
        color: white;
    }

    .cancelled {
        background-color: #f87171; /* red-400 */
        color: white;
    }

    .action-btn {
        transition: transform 0.2s ease-in-out, background-color 0.2s;
        border-radius: 0.5rem;
    }

    .action-btn:hover {
        transform: scale(1.1);
    }

    .card-icon {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 9999px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 1rem;
        font-weight: bold;
    }

    .client-icon {
        background-color: #e0f2fe;
        color: #0284c7;
    }

    .chauffeur-icon {
        background-color: #dcfce7;
        color: #16a34a;
    }

    .trip-icon {
        background-color: #ede9fe;
        color: #7c3aed;
    }

    .time-icon {
        background-color: #fef3c7;
        color: #ca8a04;
    }
    
</style>

@endsection
