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
<div class="bg-gray-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Carte du formulaire -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- En-tête du formulaire -->
            <div class="bg-blue-600 py-4 px-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-white text-center">Créer une réservation pour un client</h2>
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
                
                <form action="{{ route('reservations.agent.store') }}" method="POST">
                    @csrf
                    
                    <!-- Informations client -->
                    <div class="bg-white rounded-lg shadow-sm mb-6 p-4 border border-gray-200">
                        <h3 class="text-lg font-medium text-gray-800 mb-3">Informations client</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label for="client_id" class="block text-sm font-medium text-gray-700 mb-1">Client <span class="text-red-500">*</span></label>
                                <select id="client_id" name="client_id" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                    <option value="">-- Sélectionnez un client --</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}">{{ $client->first_name }} {{ $client->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label for="chauffeur_id" class="block text-sm font-medium text-gray-700 mb-1">Chauffeur <span class="text-red-500">*</span></label>
                                <select id="chauffeur_id" name="chauffeur_id" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                    <option value="">-- Sélectionnez un chauffeur --</option>
                                    @foreach($chauffeurs as $chauffeur)
                                        <option value="{{ $chauffeur->id }}">{{ $chauffeur->first_name }} {{ $chauffeur->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label for="trip_id" class="block text-sm font-medium text-gray-700 mb-1">Trajet <span class="text-red-500">*</span></label>
                                <select id="trip_id" name="trip_id" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                    <option value="">-- Sélectionnez un trajet --</option>
                                    @foreach($trips as $trip)
                                        <option value="{{ $trip->id }}">{{ $trip->departure }} → {{ $trip->arrival }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-6 border-gray-200">
                    
                    <!-- Détails de la réservation -->
                    <div class="bg-white rounded-lg shadow-sm mb-6 p-4 border border-gray-200">
                        <h3 class="text-lg font-medium text-gray-800 mb-3">Détails de la réservation</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date <span class="text-red-500">*</span></label>
                                <input type="date" id="date" name="date" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                            </div>
                            
                            <div>
                                <label for="heure_ramassage" class="block text-sm font-medium text-gray-700 mb-1">Heure de ramassage <span class="text-red-500">*</span></label>
                                <input type="time" id="heure_ramassage" name="heure_ramassage" step="300" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                            </div>
                            
                            <div>
                                <label for="heure_vol" class="block text-sm font-medium text-gray-700 mb-1">Heure de vol <span class="text-red-500">*</span></label>
                                <input type="time" id="heure_vol" name="heure_vol" step="300" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="heure_convocation" class="block text-sm font-medium text-gray-700 mb-1">Heure de convocation (auto)</label>
                            <input type="time" id="heure_convocation" step="300" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-gray-100" readonly>
                        </div>
                        
                        <div class="mb-4">
                            <label for="numero_vol" class="block text-sm font-medium text-gray-700 mb-1">Numéro de vol <span class="text-red-500">*</span></label>
                            <input type="text" id="numero_vol" name="numero_vol" placeholder="Ex: AF1234" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        </div>
                    </div>
                    
                    <hr class="my-6 border-gray-200">
                    
                    <!-- Détails supplémentaires -->
                    <div class="bg-white rounded-lg shadow-sm mb-6 p-4 border border-gray-200">
                        <h3 class="text-lg font-medium text-gray-800 mb-3">Détails supplémentaires</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                            <div>
                                <label for="nb_personnes" class="block text-sm font-medium text-gray-700 mb-1">Nombre de personnes <span class="text-red-500">*</span></label>
                                <input type="number" id="nb_personnes" name="nb_personnes" placeholder="1" min="1" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                            </div>
                            
                            <div>
                                <label for="nb_valises" class="block text-sm font-medium text-gray-700 mb-1">Nombre de valises <span class="text-red-500">*</span></label>
                                <input type="number" id="nb_valises" name="nb_valises" placeholder="0" min="0" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                            </div>
                            
                            <div>
                                <label for="nb_adresses" class="block text-sm font-medium text-gray-700 mb-1">Nombre d'adresses <span class="text-red-500">*</span></label>
                                <input type="number" id="nb_adresses" name="nb_adresses" placeholder="1" min="1" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                            </div>
                            
                            <div>
                                <label for="nb_accompagnants" class="block text-sm font-medium text-gray-700 mb-1">Nombre d'accompagnants</label>
                                <input type="number" id="nb_accompagnants" name="nb_accompagnants" placeholder="0" min="0" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="adresse_rammassage" class="block text-sm font-medium text-gray-700 mb-1">Adresse de ramassage <span class="text-red-500">*</span></label>
                            <input type="text" id="adresse_rammassage" name="adresse_rammassage" placeholder="Adresse complète" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut <span class="text-red-500">*</span></label>
                                <select id="status" name="status" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                    <option value="En_attente">En attente</option>
                                    <option value="Confirmée">Confirmée</option>
                                    <option value="Annulée">Annulée</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="tarif" class="block text-sm font-medium text-gray-700 mb-1">Tarif estimé</label>
                                <input type="text" id="tarif" class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-gray-100" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Bouton de soumission en BLEU (comme dans le premier exemple) -->
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
        var tarifParValiseSupplementaire = 5000;
        var tarifDepotSupplementaire = 2000;
        var tarifAccompagnant = 15000;

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

</body>
</html>
@endsection