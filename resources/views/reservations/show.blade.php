<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-lg border-0 rounded">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-receipt"></i> Réservation #{{ $reservation->id }}</h5>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6><i class="bi bi-person-circle"></i> <strong>Client :</strong> {{ $reservation->client->first_name }} {{ $reservation->client->last_name }}</h6>
                        <h6><i class="bi bi-telephone"></i> <strong>Téléphone :</strong> {{ $reservation->client->phone_number }}</h6>
                        <h6><i class="bi bi-geo-alt"></i> <strong>Adresse de Récupération :</strong> {{ $reservation->adresse_rammassage }}</h6>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6><i class="bi bi-car-front"></i> <strong>Chauffeur :</strong> {{ $reservation->carDriver->chauffeur->first_name ?? 'Non assigné' }} {{ $reservation->carDriver->chauffeur->last_name ?? '' }}</h6>
                        @if ($reservation->carDriver && $reservation->carDriver->car)
                            <h6><i class="bi bi-telephone"></i> <strong>Numéro :</strong> {{ $reservation->carDriver->chauffeur->phone_number ?? 'Non précisé' }}</h6>
                            <h6><i class="bi bi-car-front"></i> <strong>Voiture :</strong> {{ $reservation->carDriver->car->marque }} - {{ $reservation->carDriver->car->model }}</h6>
                            <h6><i class="bi bi-card-checklist"></i> <strong>Matricule :</strong> {{ $reservation->carDriver->car->matricule }}</h6>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-3">
                <div class="col-md-4">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6><i class="bi bi-suitcase"></i> <strong>Nombre de Valises :</strong> {{ $reservation->nb_valises }}</h6>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6><i class="bi bi-calendar"></i> <strong>Date :</strong> {{ \Carbon\Carbon::parse($reservation->date)->format('d/m/Y') }}</h6>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6><i class="bi bi-clock"></i> <strong>Heure Ramassage :</strong> {{ \Carbon\Carbon::parse($reservation->heure_ramassage)->format('H:i') }}</h6>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-3">
                <div class="col-md-4">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6><i class="bi bi-airplane"></i> <strong>Heure Vol :</strong> {{ \Carbon\Carbon::parse($reservation->heure_vol)->format('H:i') }}</h6>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6><i class="bi bi-tag"></i> <strong>Statut :</strong> <span class="badge {{ $reservation->status == 'En_attente' ? 'bg-warning text-dark' : ($reservation->status == 'confirmée' ? 'bg-success' : 'bg-danger') }}">
                        {{ ucfirst($reservation->status) }}
                    </span>
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
                <div class="col-md-4">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6><i class="bi bi-airplane-engines"></i> <strong>Numéro de Vol :</strong> {{ $reservation->numero_vol ?? 'Non précisé' }}</h6>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-3">
                <div class="col-md-4">
                    <div class="p-3 bg-light rounded shadow-sm">
                        <h6><i class="bi bi-people"></i> <strong>Nombre de Personnes :</strong> {{ $reservation->nb_personnes }}</h6>
                    </div>
                </div>
            </div>

            @if($reservation->status == 'En_attente')
                <div class="d-flex justify-content-start gap-2 mt-4">
    <form action="{{ route('reservations.confirm', $reservation->id) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-success"><i class="bi bi-check-circle"></i> Confirmer</button>
    </form>
    
    <form action="{{ route('reservations.cancel', $reservation->id) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-danger"><i class="bi bi-x-circle"></i> Annuler</button>
    </form>
    @endif
    <a href="{{ route('reservations.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left-circle"></i> Retour</a>
</div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
@endsection
