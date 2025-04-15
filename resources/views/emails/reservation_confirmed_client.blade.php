<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation confirmée</title>
</head>
<body>
    <h1>Bonjour {{ $reservation->client->first_name }} {{ $reservation->client->last_name }},</h1>

    <p>Nous vous informons que vous devez effectuer une nouvelle réservation</p>
    <p>Voici les détails de votre réservation :</p>

    <ul>
        <li><strong>Chauffeur assigné :</strong> {{ $reservation->carDriver->chauffeur->first_name }} {{ $reservation->carDriver->chauffeur->first_name }}</li>
        <li><strong>Numéro chauffeur :</strong> {{ $reservation->carDriver->chauffeur->phone_number }}</li>
        <li><strong>Numéro client :</strong> {{ $reservation->client->phone_number }}</li>
        <li><strong>Date :</strong> {{ \Carbon\Carbon::parse($reservation->date)->format('d/m/Y') }}</li>
        <li><strong>Heure de ramassage :</strong> {{ $reservation->heure_ramassage }}</li>
        <li><strong>Numéro de vol :</strong> {{ $reservation->numero_vol }}</li>
        <li><strong>Nombre de personnes :</strong> {{ $reservation->nb_personnes }}</li>
        <li><strong>Nombre de valises :</strong> {{ $reservation->nb_valises }}</li>
        <li><strong>Nombre d'adresses :</strong> {{ $reservation->nb_adresses }}</li>
    </ul>

    <p>Veuillez contacter le chauffeur si nécessaire.</p>

    <p>Cordialement,</p>
    <p>L'équipe de réservation de CPRO Services</p>
</body>
</html>
