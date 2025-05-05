<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')
<div class="bg-gray-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Carte du formulaire -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- En-tête du formulaire -->
            <div class="bg-blue-600 py-4 px-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-white text-center">Créer une réservation</h2>
            </div>
            
            <!-- Corps du formulaire -->
            <div class="p-6">
                <!-- Messages d'erreur -->
                @if($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
                        <strong>Erreur(s) :</strong>
                        <ul class="list-disc ml-4">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="{{ route('reservations.store') }}" method="POST">
                    @csrf
                    
                    <!-- Informations de l'utilisateur -->
                    <div class="bg-white rounded-lg shadow-sm mb-6 p-4 border border-gray-200">
                        <h3 class="text-lg font-medium text-gray-800 mb-3">Informations de l'utilisateur</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">Prénom <span class="text-red-500">*</span></label>
                                <input type="text" id="first_name" name="first_name" value="{{ $client->first_name }}" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                            </div>
                            
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Nom <span class="text-red-500">*</span></label>
                                <input type="text" id="last_name" name="last_name" value="{{ $client->last_name }}" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-mail <span class="text-red-500">*</span></label>
                                <input type="email" id="email" name="email" value="{{ $client->email }}" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Numéro de téléphone <span class="text-red-500">*</span></label>
                                <input type="text" id="phone_number" name="phone_number" value="{{ $client->phone_number }}" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-6 border-gray-200">
                    
                    <!-- Informations de la réservation -->
                    <div class="bg-white rounded-lg shadow-sm mb-6 p-4 border border-gray-200">
                        <h3 class="text-lg font-medium text-gray-800 mb-3">Informations de la réservation</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label for="trip_id" class="block text-sm font-medium text-gray-700 mb-1">Trajet <span class="text-red-500">*</span></label>
                                <select id="trip_id" name="trip_id" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                    <option value="">-- Sélectionner un trajet --</option>
                                    @foreach($trips as $trip)
                                        <option value="{{ $trip->id }}">{{ $trip->departure }} - {{ $trip->destination }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date <span class="text-red-500">*</span></label>
                                <input type="date" id="date" name="date" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <label for="heure_ramassage" class="block text-sm font-medium text-gray-700 mb-1">Heure de ramassage <span class="text-red-500">*</span></label>
                            <input type="time" id="heure_ramassage" name="heure_ramassage" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        </div>
                        
                        <div>
                            <label for="heure_vol" class="block text-sm font-medium text-gray-700 mb-1">Heure de vol <span class="text-red-500">*</span></label>
                            <input type="time" id="heure_vol" name="heure_vol" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        </div>
                        
                        <div>
                            <label for="heure_convocation" class="block text-sm font-medium text-gray-700 mb-1">Heure de convocation <span class="text-red-500">*</span></label>
                            <input type="time" id="heure_convocation" name="heure_convocation" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <label for="numero_vol" class="block text-sm font-medium text-gray-700 mb-1">Numéro du vol <span class="text-red-500">*</span></label>
                            <input type="text" id="numero_vol" name="numero_vol" placeholder="Ex: AF1234" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        </div>
                        
                        <div>
                            <label for="nb_personnes" class="block text-sm font-medium text-gray-700 mb-1">Nombre de personnes <span class="text-red-500">*</span></label>
                            <input type="number" id="nb_personnes" name="nb_personnes" placeholder="1" min="1" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        </div>
                        
                        <div>
                            <label for="nb_valises" class="block text-sm font-medium text-gray-700 mb-1">Nombre de valises <span class="text-red-500">*</span></label>
                            <input type="number" id="nb_valises" name="nb_valises" placeholder="0" min="0" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        </div>
                    </div>
                    
                    <hr class="my-6 border-gray-200">
                    
                    <!-- Détails supplémentaires -->
                    <div class="bg-white rounded-lg shadow-sm mb-6 p-4 border border-gray-200">
                        <h3 class="text-lg font-medium text-gray-800 mb-3">Détails supplémentaires</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label for="nb_adresses" class="block text-sm font-medium text-gray-700 mb-1">Nombre de dépôts supplémentaires</label>
                                <input type="number" id="nb_adresses" name="nb_adresses" placeholder="0" min="0" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                            
                            <div>
                                <label for="nb_accompagnants" class="block text-sm font-medium text-gray-700 mb-1">Nombre d'accompagnants</label>
                                <input type="number" id="nb_accompagnants" name="nb_accompagnants" placeholder="0" min="0" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label for="adresse_rammassage" class="block text-sm font-medium text-gray-700 mb-1">Adresse de ramassage <span class="text-red-500">*</span></label>
                                <input type="text" id="adresse_rammassage" name="adresse_rammassage" placeholder="Adresse complète" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                            </div>
                            
                            <div>
                                <label for="tarif" class="block text-sm font-medium text-gray-700 mb-1">Tarif</label>
                                <input type="text" id="tarif" name="tarif" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Bouton de soumission en BLEU -->
                    <div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-md shadow-md transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 flex items-center justify-center">
                            Créer la Réservation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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