<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center text-primary font-weight-bold">Créer une réservation pour un client</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Erreur(s) :</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('reservations.agent.store') }}" method="POST" class="bg-light p-4 rounded shadow-sm">
        @csrf

        <!-- Bloc Informations Client -->
        <div class="card shadow-sm mb-4 p-3">
            <h5 class="mb-3 text-dark">Informations client</h5>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="client_id" class="form-label">Client <span class="text-danger">*</span></label>
                    <select name="client_id" id="client_id" class="form-select" required>
                        <option value="">-- Sélectionnez un client --</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->first_name }} {{ $client->last_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="chauffeur_id" class="form-label">Chauffeur <span class="text-danger">*</span></label>
                    <select name="chauffeur_id" id="chauffeur_id" class="form-select" required>
                        <option value="">-- Sélectionnez un chauffeur --</option>
                        @foreach($chauffeurs as $chauffeur)
                            <option value="{{ $chauffeur->id }}">{{ $chauffeur->first_name }} {{ $chauffeur->last_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="trip_id" class="form-label">Trajet <span class="text-danger">*</span></label>
                    <select name="trip_id" id="trip_id" class="form-select" required>
                        <option value="">-- Sélectionnez un trajet --</option>
                        @foreach($trips as $trip)
                            <option value="{{ $trip->id }}">{{ $trip->departure }} → {{ $trip->arrival }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Bloc Détails réservation -->
        <div class="card shadow-sm mb-4 p-3">
            <h5 class="mb-3 text-dark">Détails de la réservation</h5>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                    <input type="date" name="date" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label for="heure_ramassage" class="form-label">Heure de ramassage <span class="text-danger">*</span></label>
                    <input type="time" id="heure_ramassage" name="heure_ramassage" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label for="heure_vol" class="form-label">Heure de vol <span class="text-danger">*</span></label>
                    <input type="time" id="heure_vol" name="heure_vol" class="form-control" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="heure_convocation" class="form-label">Heure de convocation (auto)</label>
                <input type="time" id="heure_convocation" class="form-control" readonly>
            </div>

            <div class="mb-3">
                <label for="numero_vol" class="form-label">Numéro de vol <span class="text-danger">*</span></label>
                <input type="text" name="numero_vol" class="form-control" required>
            </div>
        </div>

        <!-- Bloc Détails supplémentaires -->
        <div class="card shadow-sm mb-4 p-3">
            <h5 class="mb-3 text-dark">Détails supplémentaires</h5>
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="nb_personnes" class="form-label">Nombre de personnes <span class="text-danger">*</span></label>
                    <input type="number" name="nb_personnes" id="nb_personnes" class="form-control" min="1" required>
                </div>

                <div class="col-md-3">
                    <label for="nb_valises" class="form-label">Nombre de valises <span class="text-danger">*</span></label>
                    <input type="number" name="nb_valises" id="nb_valises" class="form-control" min="0" required>
                </div>

                <div class="col-md-3">
                    <label for="nb_adresses" class="form-label">Nombre d'adresses <span class="text-danger">*</span></label>
                    <input type="number" name="nb_adresses" id="nb_adresses" class="form-control" min="1" required>
                </div>

                <div class="col-md-3">
                    <label for="nb_accompagnants" class="form-label">Nombre d'accompagnants</label>
                    <input type="number" name="nb_accompagnants" id="nb_accompagnants" class="form-control" min="0">
                </div>
            </div>

            <div class="mb-3">
                <label for="adresse_rammassage" class="form-label">Adresse de ramassage <span class="text-danger">*</span></label>
                <input type="text" name="adresse_rammassage" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Statut <span class="text-danger">*</span></label>
                <select name="status" id="status" class="form-select" required>
                    <option value="En_attente">En attente</option>
                    <option value="Confirmée">Confirmée</option>
                    <option value="Annulée">Annulée</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="tarif" class="form-label">Tarif estimé</label>
                <input type="text" id="tarif" class="form-control" readonly>
            </div>
        </div>

        <button type="submit" class="btn btn-success btn-block mt-4">Créer la réservation</button>
    </form>
</div>

<script>
    // Calcul automatique du tarif
    document.getElementById('nb_personnes').addEventListener('input', updateTarif);
    document.getElementById('nb_valises').addEventListener('input', updateTarif);
    document.getElementById('nb_adresses').addEventListener('input', updateTarif);
    document.getElementById('nb_accompagnants').addEventListener('input', updateTarif);

    function updateTarif() {
        var nbPersonnes = parseInt(document.getElementById('nb_personnes').value) || 0;
        var nbValises = parseInt(document.getElementById('nb_valises').value) || 0;
        var nbAdresses = parseInt(document.getElementById('nb_adresses').value) || 0;
        var nbAccompagnants = parseInt(document.getElementById('nb_accompagnants').value) || 0;

        var tarifBasePersonnes = 32500;
        var tarifParPersonneSupplementaire = 5000;
        var tarifParValiseSupplementaire = 1000;
        var tarifDepotSupplementaire = 2000;
        var tarifAccompagnant = 3000;

        var tarif = 0;

        // Base personnes
        tarif = nbPersonnes <= 3 ? tarifBasePersonnes : tarifBasePersonnes + (nbPersonnes - 3) * tarifParPersonneSupplementaire;

        // Valises supplémentaires
        var maxValisesSansFrais = nbPersonnes * 2;
        if (nbValises > maxValisesSansFrais) {
            tarif += (nbValises - maxValisesSansFrais) * tarifParValiseSupplementaire;
        }

        // Dépôts
        tarif += nbAdresses * tarifDepotSupplementaire;

        // Accompagnants
        tarif += nbAccompagnants * tarifAccompagnant;

        document.getElementById('tarif').value = tarif + ' F';
    }

    // Calcul automatique des heures
    document.getElementById("heure_vol").addEventListener("change", calculerHeures);

    function calculerHeures() {
        const heureVol = document.getElementById("heure_vol").value;
        if (!heureVol) return;

        const [heure, minute] = heureVol.split(':').map(Number);
        let dateVol = new Date(2000, 0, 1, heure, minute);

        let dateConvocation = new Date(dateVol.getTime() - (3 * 60 + 30) * 60000);
        let dateRamassage = new Date(dateConvocation.getTime() - 60 * 60000);

        function formatHeure(date) {
            return date.getHours().toString().padStart(2, '0') + ':' + date.getMinutes().toString().padStart(2, '0');
        }

        document.getElementById("heure_convocation").value = formatHeure(dateConvocation);
        document.getElementById("heure_ramassage").value = formatHeure(dateRamassage);
    }
</script>

@endsection
