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
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    @endif
    <a href="{{ route('reservations.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left-circle"></i> Retour</a>
    <div class="mt-3">
    @php
    $phone = preg_replace('/[^0-9]/', '', $reservation->client->phone_number); // Nettoyage du numéro
    $phone = '221' . $phone; // Ajout de l'indicatif du Sénégal
    $message = urlencode(
        "Bonjour " . $reservation->client->first_name . ",\n\n" .
        "Votre réservation #{$reservation->id} a bien été confirmée.\n\n" .
        "Détails de la réservation :\n" .
        "Client : " . $reservation->client->first_name . " " . $reservation->client->last_name . "\n" .
        "Téléphone : " . $reservation->client->phone_number . "\n" .
        "Adresse de Récupération : " . $reservation->adresse_rammassage . "\n" .
        "Chauffeur : " . ($reservation->carDriver->chauffeur->first_name ?? 'Non assigné') . " " . ($reservation->carDriver->chauffeur->last_name ?? '') . "\n" .
        "Numéro Chauffeur : " . ($reservation->carDriver->chauffeur->phone_number ?? 'Non précisé') . "\n" .
        "Voiture : " . ($reservation->carDriver->car->marque ?? 'Non précisé') . " - " . ($reservation->carDriver->car->model ?? 'Non précisé') . "\n" .
        "Matricule : " . ($reservation->carDriver->car->matricule ?? 'Non précisé') . "\n" .
        "Nombre de Valises : " . $reservation->nb_valises . "\n" .
        "Date : " . \Carbon\Carbon::parse($reservation->date)->format('d/m/Y') . "\n" .
        "Heure Ramassage : " . \Carbon\Carbon::parse($reservation->heure_ramassage)->format('H:i') . "\n" .
        "Heure Vol : " . \Carbon\Carbon::parse($reservation->heure_vol)->format('H:i') . "\n" .
        "Statut : " . ucfirst($reservation->status) . "\n" .
        "Numéro de Vol : " . ($reservation->numero_vol ?? 'Non précisé') . "\n" .
        "Nombre de Personnes : " . $reservation->nb_personnes . "\n\n" .
        "Merci de votre confiance !"
    );
    @endphp

    @if($reservation->status == 'confirmée' && !$reservation->avis)
    <hr>
    <div class="mt-4">
        <h5><i class="bi bi-star-fill text-warning"></i> Laissez une note</h5>
        <form action="{{ route('reservations.avis', $reservation->id) }}" method="POST">
            @csrf
            <div class="mb-3 d-flex gap-2">
                @for ($i = 1; $i <= 5; $i++)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="note" id="note{{ $i }}" value="{{ $i }}">
                        <label class="form-check-label" for="note{{ $i }}">
                            <i class="bi bi-star{{ $i <= 3 ? '-fill' : '' }}"></i> {{ $i }}
                        </label>
                    </div>
                @endfor
            </div>
            <div class="mb-3">
                <label for="comment" class="form-label">Commentaire (optionnel)</label>
                <textarea name="comment" id="comment" rows="3" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Envoyer l’avis</button>
        </form>
    </div>
@endif

@if($reservation->avis)
    <hr>
    <div class="mt-4">
        <h5><i class="bi bi-star-fill text-warning"></i> Avis laissé</h5>
        <p>
            @for($i = 1; $i <= 5; $i++)
                <i class="bi {{ $i <= $reservation->avis->note ? 'bi-star-fill text-warning' : 'bi-star' }}"></i>
            @endfor
        </p>
        <p><strong>Commentaire :</strong> {{ $reservation->avis->commentaire ?? 'Aucun commentaire.' }}</p>
    </div>
@endif

    <a 
        href="https://wa.me/{{ $phone }}?text={{ $message }}" 
        class="btn btn-success" 
        target="_blank"
    >
        <i class="bi bi-whatsapp"></i> Envoyer par WhatsApp
    </a>

    </div>
</div>

        </div>
    </div>
</div>

</body>
</html>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
@endsection
