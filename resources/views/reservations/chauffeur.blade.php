<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Gestion des Réservations</h1>
            <!-- <p class="text-gray-600">Visualisez et gérez toutes les réservations de votre flotte</p> -->
        </div>
    </div>

    @if ($reservations->count() > 0)
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach ($reservations as $reservation)
                <div class="col">
                    <div class="card shadow-lg rounded-3 p-3 bg-white border border-light">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-4">
                                <div>
                                    <h5 class="card-title font-weight-bold text-dark">#RES-{{ str_pad((string) $reservation->id, 6, '0', STR_PAD_LEFT) }}</h5>
                                    <p class="text-muted"><i class="fas fa-calendar-day"></i> {{ \Carbon\Carbon::parse($reservation->date)->format('d M Y') }}</p>
                                </div>
                                <span class="badge 
                                    {{ $reservation->status === 'confirmée' ? 'bg-success' : ($reservation->status === 'en_attente' ? 'bg-warning' : 'bg-danger') }} text-white">
                                    @if($reservation->status === 'confirmée')
                                        <i class="fas fa-check-circle mr-1"></i> Confirmée
                                    @elseif($reservation->status === 'En_attente')
                                        <i class="fas fa-clock mr-1"></i> En attente
                                    @else
                                        <i class="fas fa-times-circle mr-1"></i> Annulée
                                    @endif
                                </span>
                            </div>

                            <div class="d-flex flex-column">
                                <!-- Client -->
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-primary text-white p-2 mr-3">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <p class="text-muted"><i class="fas fa-id-card-alt"></i> Client</p>
                                        <p class="font-weight-bold">{{ $reservation->client->first_name }} {{ $reservation->client->last_name }}</p>
                                    </div>
                                </div>

                                <!-- Chauffeur -->
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-success text-white p-2 mr-3">
                                        <i class="fas fa-car"></i>
                                    </div>
                                    <div>
                                        <p class="text-muted"><i class="fas fa-id-badge"></i> Chauffeur</p>
                                        <p class="font-weight-bold">{{ $reservation->chauffeur->first_name }} {{ $reservation->chauffeur->last_name }}</p>
                                    </div>
                                </div>

                                <!-- Voyage -->
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-info text-white p-2 mr-3">
                                        <i class="fas fa-route"></i>
                                    </div>
                                    <div>
                                        <p class="text-muted"><i class="fas fa-map-signs"></i> Voyage</p>
                                        <p class="font-weight-bold">{{ $reservation->trip->departure }} → {{ $reservation->trip->destinatio }} </p>
                                    </div>
                                </div>

                                <!-- Heure Ramassage -->
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-warning text-white p-2 mr-3">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div>
                                        <p class="text-muted"><i class="fas fa-hourglass-start"></i> Heure de ramassage</p>
                                        <p class="font-weight-bold">{{ $reservation->heure_ramassage }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-light d-flex justify-content-between align-items-center border-top">
                            <div class="d-flex">
                                <a href="{{ route('reservations.confirm', $reservation) }}" class="btn btn-success btn-sm mr-2" title="Confirmer la réservation">
                                    <i class="fas fa-check"></i> Confirmer
                                </a>
                                <a href="{{ route('reservations.cancel', $reservation) }}" class="btn btn-danger btn-sm mr-2" title="Annuler la réservation">
                                    <i class="fas fa-times"></i> Annuler
                                </a>
                                <form action="{{ route('reservations.destroy', $reservation) }}" method="POST" onsubmit="return confirm('Supprimer cette réservation ?')" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-secondary btn-sm" title="Supprimer la réservation">
                                        <i class="fas fa-trash-alt"></i> Supprimer
                                    </button>
                                </form>
                            </div>
                            <button class="btn btn-outline-secondary btn-sm" title="Plus d'options">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                        </div>
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

@endsection
