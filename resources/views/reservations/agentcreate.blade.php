<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Créer une Réservation</h1>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <form action="{{ route('reservations.storeByAgent') }}" method="POST">
        @csrf

        <!-- Formulaire pour les détails de la réservation -->
        <div class="form-group">
            <label for="client_id">Client</label>
            <select name="client_id" id="client_id" class="form-control">
            <option selected disabled>-- Sélectionner un client --</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->first_name }} {{ $client->last_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="first_name">Prénom</label>
            <input type="text" name="first_name" id="first_name" class="form-control">
        </div>

        <div class="form-group">
            <label for="last_name">Nom</label>
            <input type="text" name="last_name" id="last_name" class="form-control">
        </div>

        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="text" name="email" id="email" class="form-control">
        </div>

        <div class="form-group">
            <label for="trip_id">Trajet</label>
            <select name="trip_id" id="trip_id" class="form-control" required>
            <option selected disabled>-- Sélectionner un trajet --</option>
                @foreach($trips as $trip)
                    <option value="{{ $trip->id }}">{{ $trip->departure }} - {{ $trip->destination }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="date">Date</label>
            <input type="date" name="date" id="date" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="heure_ramassage">Heure de ramassage</label>
            <input type="time" name="heure_ramassage" id="heure_ramassage" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="heure_vol">Heure de vol</label>
            <input type="time" name="heure_vol" id="heure_vol" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="heure_convocation">Heure de convocation</label>
            <input type="time" name="heure_convocation" id="heure_convocation" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="numero_vol">Numéro du vol</label>
            <input type="text" name="numero_vol" id="numero_vol" class="form-control" required>
        </div>

    <div class="form-group">
        <label for="nb_personnes">Nombre de personnes</label>
        <input type="number" name="nb_personnes" id="nb_personnes" class="form-control" required min="1">
    </div>

    <div class="form-group">
        <label for="nb_valises">Nombre de valises</label>
        <input type="number" name="nb_valises" id="nb_valises" class="form-control" required min="0">
    </div>

    <div class="form-group">
        <label for="nb_adresses">Nombre de dépôts supplémentaires</label>
        <input type="number" name="nb_adresses" id="nb_adresses" class="form-control" required min="0">
    </div>

    <div class="form-group">
        <label for="nb_accompagnants">Nombre d'accompagnants</label>
        <input type="number" name="nb_accompagnants" id="nb_accompagnants" class="form-control" required min="0">
    </div>

    <div class="form-group">
        <label for="tarif">Tarif</label>
        <input type="text" name="tarif" id="tarif" class="form-control" value="{{ old('tarif') }}" readonly>
    </div>


        <button type="submit" class="btn btn-primary mt-3">Créer la Réservation</button>
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
    var tarifParValiseSupplementaire = 1000;  // 1000F par valise supplémentaire
    var tarifDepotSupplementaire = 2000; // 2000F par dépôt supplémentaire
    var tarifAccompagnant = 3000; // 3000F par accompagnant

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

</script>

@endsection
