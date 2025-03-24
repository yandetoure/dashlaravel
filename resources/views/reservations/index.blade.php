<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')
<div class="container">
    <!-- En-tête stylisé -->
    <div class="bg-light p-4 rounded shadow-sm mb-4">
        <h1 class="mb-3 text-primary text-center">Liste des Réservations</h1>
        <div class="row justify-content-between align-items-center">
            <div class="col-md-4">
                <form method="GET" action="{{ route('reservations.index') }}">
                    <label for="status" class="form-label fw-bold">Filtrer par statut :</label>
                    <select class="form-select" name="status" id="status" onchange="this.form.submit()">
                        <option value="">Tous</option>
                        <option value="En_attente" {{ request('status') == 'En_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="confirmée" {{ request('status') == 'confirmée' ? 'selected' : '' }}>Confirmée</option>
                        <option value="annulée" {{ request('status') == 'annulée' ? 'selected' : '' }}>Annulée</option>
                    </select>
                </form>
            </div>
            <div class="col-auto">
                <a href="{{ route('reservations.create') }}" class="btn btn-success fw-bold">
                    + Nouvelle Réservation
                </a>
            </div>
        </div>
    </div>

    <!-- Tableau des réservations -->
    <table class="table table-hover shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>Client</th>
                <th>Chauffeur</th>
                <th>Date</th>
                <th>Heure Ramassage</th>
                <th>Heure Vol</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($reservations as $reservation)
            <tr>
                <td>{{ $reservation->client->first_name }} {{ $reservation->client->last_name }}</td>
                <td>{{ $reservation->carDriver->chauffeur->first_name ?? 'Non assigné' }} {{ $reservation->carDriver->chauffeur->last_name ?? 'Non assigné' }}</td>
                <td>{{ \Carbon\Carbon::parse($reservation->date)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($reservation->heure_ramassage)->format('H:i') }}</td>
                <td>{{ \Carbon\Carbon::parse($reservation->heure_vol)->format('H:i') }}</td>
                <td>
                    <span class="badge {{ $reservation->status == 'En_attente' ? 'bg-warning text-dark' : ($reservation->status == 'confirmée' ? 'bg-success' : 'bg-danger') }}">
                        {{ ucfirst($reservation->status) }}
                    </span>
                </td>
                <td>
                    <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#editReservationModal{{ $reservation->id }}">
                        Modifier
                    </button>
                    <a href="{{ route('reservations.show', $reservation->id) }}" class="btn btn-sm btn-outline-primary">
                        Détails
                    </a>
                </td>
            </tr>
            
            <!-- Modal de modification -->
            <div class="modal fade" id="editReservationModal{{ $reservation->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">Modifier la Réservation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('reservations.update', $reservation->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="date" class="form-control" name="date" value="{{ \Carbon\Carbon::parse($reservation->date)->format('Y-m-d') }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="heure_ramassage" class="form-label">Heure Ramassage</label>
                                    <input type="time" class="form-control" name="heure_ramassage" value="{{ \Carbon\Carbon::parse($reservation->heure_ramassage)->format('H:i') }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="heure_vol" class="form-label">Heure Vol</label>
                                    <input type="time" class="form-control" name="heure_vol" value="{{ \Carbon\Carbon::parse($reservation->heure_vol)->format('H:i') }}">
                                </div>

                                <button type="submit" class="btn btn-primary w-100">Sauvegarder</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $reservations->links() }}
    </div>
</div>
@endsection
