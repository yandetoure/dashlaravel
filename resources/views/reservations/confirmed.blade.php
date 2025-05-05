<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Liste des Réservations</h1>

    <a href="{{ route('reservations.create') }}" class="btn btn-primary mb-3">Créer une Réservation</a>

    <table class="table table-striped table-bordered">
        <thead>
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
                @if($reservation->status == 'En_attente')
                    <span class="badge bg-warning text-dark">En attente</span>
                @elseif($reservation->status == 'confirmée')
                    <span class="badge bg-success">Confirmée</span>
                @elseif($reservation->status == 'annulée')
                    <span class="badge bg-danger">Annulée</span>
                @endif
            </td>
            <td>
  <!-- Modifier la réservation (uniquement date et heure) -->
  <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#editReservationModal{{ $reservation->id }}">Modifier</button>

<!-- Détails de la réservation -->
<a href="{{ route('reservations.show', $reservation->id) }}" class="btn btn-primary btn-sm">Détails</a>
</td>
</tr>


        <!-- Modal de modification -->
        <div class="modal fade" id="editReservationModal{{ $reservation->id }}" tabindex="-1" aria-labelledby="editReservationModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editReservationModalLabel">Modifier la Réservation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Formulaire de modification de la réservation -->
                        <form action="{{ route('reservations.update', $reservation->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" class="form-control" id="date" name="date" value="{{ \Carbon\Carbon::parse($reservation->date)->format('Y-m-d') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="heure_ramassage" class="form-label">Heure Ramassage</label>
                                <input type="time" class="form-control" id="heure_ramassage" name="heure_ramassage" value="{{ \Carbon\Carbon::parse($reservation->heure_ramassage)->format('H:i') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="heure_vol" class="form-label">Heure Vol</label>
                                <input type="time" class="form-control" id="heure_vol" name="heure_vol" value="{{ \Carbon\Carbon::parse($reservation->heure_vol)->format('H:i') }}">
                            </div>

                            <button type="submit" class="btn btn-primary">Sauvegarder les modifications</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
</div>

</body>
</html>
@endsection

@section('scripts')
<!-- Ajouter le script nécessaire pour Bootstrap Modal -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
@endsection
