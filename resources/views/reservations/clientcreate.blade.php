<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center">Créer une Réservation</h1>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

<div class="container">
    <h1>Créer une Réservation</h1>

    <form action="{{ route('reservations.store') }}" method="POST">
    @csrf
        
         <!-- Informations de l'utilisateur -->
        <div class="card shadow-sm mb-4 p-3">
            <h5 class="mb-3 text-dark">Informations de l'utilisateur</h5>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="first_name" class="form-label">Prénom <span class="text-danger"></span></label>
                <input type="text" name="first_name" id="first_name" class="form-control" value="{{ $client->first_name }}" required>
                </div>
                <div class="col-md-4">
                    <label for="last_name" class="form-label">Nom <span class="text-danger"></span></label>
                <input type="text" name="last_name" id="last_name" class="form-control" value="{{ $client->last_name }}" required>
                </div>
                <div class="col-md-4">
                    <label for="email" class="form-label">E-mail <span class="text-danger"></span></label>
                <input type="email" name="email" id="email" class="form-control" value="{{ $client->email }}" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="phone_number" class="form-label">Numéro de téléphone <span class="text-danger"></span></label>
                <input type="text" class="form-control" name="phone_number" id="phone_number" value="{{ $client->phone_number }}" required>
                </div>
            </div>
        </div>


        <!-- Informations de la réservation -->
        <div class="card shadow-sm mb-4 p-3">
            <h5 class="mb-3 text-dark">Informations de la réservation</h5>
            <div class="row mb-3">
                {{-- <div class="col-md-4">
                    <label for="client_id" class="form-label">Client <span class="text-danger">*</span></label>
                    <select name="client_id" id="client_id" class="form-select" required>
                        <option value="">-- Sélectionner un client --</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->first_name }} {{ $client->last_name }}</option>
                        @endforeach
                    </select>
                </div> --}}
                <div class="col-md-4">
                    <label for="trip_id" class="form-label">Trajet <span class="text-danger">*</span></label>
                    <select name="trip_id" id="trip_id" class="form-select" required>
                        <option value="">-- Sélectionner un trajet --</option>
                        @foreach($trips as $trip)
                            <option value="{{ $trip->id }}">{{ $trip->departure }} - {{ $trip->destination }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                    <input type="date" name="date" id="date" class="form-control" required>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="heure_ramassage" class="form-label">Heure de ramassage <span class="text-danger">*</span></label>
                <input type="time" name="heure_ramassage" id="heure_ramassage" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label for="heure_vol" class="form-label">Heure de vol <span class="text-danger">*</span></label>
                <input type="time" name="heure_vol" id="heure_vol" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label for="heure_convocation" class="form-label">Heure de convocation <span class="text-danger">*</span></label>
                <input type="time" name="heure_convocation" id="heure_convocation" class="form-control" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="numero_vol" class="form-label">Numéro du vol <span class="text-danger">*</span></label>
                <input type="text" name="numero_vol" id="numero_vol" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label for="nb_personnes" class="form-label">Nombre de personnes <span class="text-danger">*</span></label>
                <input type="number" name="nb_personnes" id="nb_personnes" class="form-control" required min="1">
            </div>
            <div class="col-md-4">
                <label for="nb_valises" class="form-label">Nombre de valises <span class="text-danger">*</span></label>
                <input type="number" name="nb_valises" id="nb_valises" class="form-control" required min="0">
            </div>
        </div>

        <hr class="my-4">

        <!-- Détails supplémentaires -->
        <div class="card shadow-sm mb-4 p-3">
            <h5 class="mb-3 text-dark">Détails supplémentaires</h5>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="nb_adresses" class="form-label">Nombre de dépôts supplémentaires</label>
                    <input type="number" name="nb_adresses" id="nb_adresses" class="form-control" min="0">
                </div>
                <div class="col-md-4">
                    <label for="nb_accompagnants" class="form-label">Nombre d'accompagnants</label>
                    <input type="number" name="nb_accompagnants" id="nb_accompagnants" class="form-control" min="0">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="adresse_rammassage" class="form-label">Adresse de ramassage</label>
                    <input type="text" name="adresse_rammassage" id="adresse_rammassage" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label for="tarif" class="form-label">Tarif</label>
                    <input type="text" name="tarif" id="tarif" class="form-control" readonly>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 mt-4">Créer la Réservation</button>
    </form>
</div>

<script>
    // Calcul automatique du tarif en fonction du nombre de personnes et de valises
    document.getElementById('nb_personnes').addEventListener('input', updateTarif);
document.getElementById('nb_valises').addEventListener('input', updateTarif);
document.getElementById('nb_adresses').addEventListener('input', updateTarif);
document.getElementById('nb_accompagnants').addEventListener('input', updateTarif);

function updateTarif() {
    var nbPersonnes = parseInt(document.getElementById('nb_personnes').value) || 0;
    var nbValises = parseInt(document.getElementById('nb_valises').value) || 0;
    var nbAdresses = parseInt(document.getElementById('nb_adresses').value) || 0;
    var nbAccompagnants = parseInt(document.getElementById('nb_accompagnants').value) || 0;

    // Tarifs de base
    var tarifBasePersonnes = 32500;  // Tarif de base pour les 1 à 3 personnes
    var tarifParPersonneSupplementaire = 5000; // 5000F par personne supplémentaire
    var tarifParValiseSupplementaire = 5000;  // 1000F par valise supplémentaire
    var tarifDepotSupplementaire = 2000; // 2000F par dépôt supplémentaire
    var tarifAccompagnant = 15000; // 3000F par accompagnant

    var tarif = 0;

    // Tarif de base pour les personnes
    if (nbPersonnes <= 3) {
        tarif = tarifBasePersonnes;
    } else {
        // Plus de 3 personnes
        tarif = tarifBasePersonnes + (nbPersonnes - 3) * tarifParPersonneSupplementaire;
    }

    // Calcul des valises supplémentaires (plus de 2 par personne)
    if (nbValises > nbPersonnes * 2) {
        var valisesSupplementaires = nbValises - (nbPersonnes * 2);
        tarif += valisesSupplementaires * tarifParValiseSupplementaire;
    }

    // Calcul des dépôts supplémentaires
    tarif += nbAdresses * tarifDepotSupplementaire;

    // Calcul des accompagnants
    tarif += nbAccompagnants * tarifAccompagnant;

    // Affichage du tarif
    document.getElementById('tarif').value = tarif + ' F';
}

 // === LOGIQUE HEURE AUTOMATIQUE ===
 document.getElementById("heure_vol").addEventListener("change", calculerHeures);

function calculerHeures() {
    const heureVol = document.getElementById("heure_vol").value;

    if (!heureVol) return;

    const [heure, minute] = heureVol.split(':').map(Number);
    let dateVol = new Date(2000, 0, 1, heure, minute);

    // Heure de convocation = vol - 3h30
    let dateConvocation = new Date(dateVol.getTime() - (3 * 60 + 30) * 60000);

    // Heure de ramassage = convocation - 1h
    let dateRamassage = new Date(dateConvocation.getTime() - 60 * 60000);

    // Format HH:MM
    function formatHeure(date) {
        let h = date.getHours().toString().padStart(2, '0');
        let m = date.getMinutes().toString().padStart(2, '0');
        return `${h}:${m}`;
    }

    document.getElementById("heure_convocation").value = formatHeure(dateConvocation);
    document.getElementById("heure_ramassage").value = formatHeure(dateRamassage);
}

</script>
@endsection