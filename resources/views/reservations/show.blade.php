<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-lg border-0 rounded">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Réservation #{{ $reservation->id }}</h5>
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-6">
                    <!-- Espacement entre le nom et le téléphone -->
                    <h6><strong>Client :</strong> {{ $reservation->client->first_name }} {{ $reservation->client->last_name }}</h6>
                    <h6 class="mb-3"><strong>Téléphone :</strong> {{ $reservation->client->phone_number }}</h6>
                </div>
                <div class="col-md-5">
                    <h6><strong>Chauffeur :</strong> {{ $reservation->carDriver->chauffeur->first_name ?? 'Non assigné' }} {{ $reservation->carDriver->chauffeur->last_name ?? 'Non assigné' }}</h6>
                    @if ($reservation->carDriver && $reservation->carDriver->car)
                        <h6 class="mb-3"><strong>Numéro du Chauffeur :</strong> {{ $reservation->carDriver->chauffeur->phone_number ?? 'Non précisé' }}</h6>
                        <h6 class="mb-3"><strong>Voiture :</strong> {{ $reservation->carDriver->car->marque ?? 'Non précisé' }} - {{ $reservation->carDriver->car->model ?? 'Non précisé' }}</h6>
                        <h6><strong>Matricule de la voiture :</strong> {{ $reservation->carDriver->car->matricule ?? 'Non précisé' }}</h6>
                    @endif
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <h6><strong>Date de Réservation :</strong> {{ \Carbon\Carbon::parse($reservation->date)->format('d/m/Y') }}</h6>
                </div>
                <div class="col-md-6">
                    <h6><strong>Heure Ramassage :</strong> {{ \Carbon\Carbon::parse($reservation->heure_ramassage)->format('H:i') }}</h6>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <h6><strong>Heure Vol :</strong> {{ \Carbon\Carbon::parse($reservation->heure_vol)->format('H:i') }}</h6>
                </div>
                <div class="col-md-6">
                    <h6><strong>Statut :</strong> 
                        @if($reservation->status == 'En_attente')
                            <span class="badge bg-warning text-dark">En attente</span>
                        @elseif($reservation->status == 'confirmée')
                            <span class="badge bg-success">Confirmée</span>
                        @elseif($reservation->status == 'annulée')
                            <span class="badge bg-danger">Annulée</span>
                        @endif
                    </h6>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <h6><strong>Numéro de Vol :</strong> {{ $reservation->numero_vol ?? 'Non précisé' }}</h6>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <h6><strong>Nombre de Personnes :</strong> {{ $reservation->nb_personnes }}</h6>
                </div>
                <div class="col-md-6">
                    <h6><strong>Nombre de Valises :</strong> {{ $reservation->nb_valises }}</h6>
                </div>
            </div>

            <!-- Si la réservation est en attente, afficher les boutons de confirmation et annulation -->
            @if($reservation->status == 'En_attente')
                <div class="d-flex justify-content-start gap-2 mb-4">
                    <form action="{{ route('reservations.confirm', $reservation->id) }}" method="POST" class="me-2">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm" title="Confirmer"><i class="bi bi-check-circle"></i> Confirmer</button>
                    </form>
                    
                    <form action="{{ route('reservations.cancel', $reservation->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm" title="Annuler"><i class="bi bi-x-circle"></i> Annuler</button>
                    </form>
                </div>
            @endif

            <!-- Bouton Retour -->
        <!-- Bouton Retour -->
        <a href="{{ route('reservations.index') }}" class="btn btn-secondary mt-3"><i class="bi bi-arrow-left-circle"></i> Retour à la liste</a>
    </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Ajouter le script nécessaire pour Bootstrap Modal -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
@endsection
