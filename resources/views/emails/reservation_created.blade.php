<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation en attente</title>
</head>
<body>
    <h1>Bonjour {{ $reservation->client->first_name }} {{ $reservation->client->last_name }},</h1>

    <p>Nous vous informons que votre réservation est bien enregistrée et en attente de confirmation.</p>
    <p>Voici les détails de votre réservation :</p>

    <ul>
        <li><strong>Chauffeur :</strong> {{ $reservation->chauffeur->first_name }} {{ $reservation->chauffeur->last_name }}</li>
        <li><strong>Date :</strong> {{ \Carbon\Carbon::parse($reservation->date)->format('d/m/Y') }}</li>
        <li><strong>Heure de ramassage :</strong> {{ $reservation->heure_ramassage }}</li>
        <li><strong>Numéro de vol :</strong> {{ $reservation->numero_vol }}</li>
        <li><strong>Nombre de personnes :</strong> {{ $reservation->nb_personnes }}</li>
        <li><strong>Nombre de valises :</strong> {{ $reservation->nb_valises }}</li>
        <li><strong>Nombre d'adresses :</strong> {{ $reservation->nb_adresses }}</li>
    </ul>

    <p>Nous vous tiendrons informé dès que la réservation sera confirmée.</p>

    <p>Cordialement,</p>
    <p>L'équipe de réservation</p>
</body>
</html>
