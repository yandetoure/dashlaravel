<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Réservation en Attente</title>
</head>
<body>
    <h1>Nouvelle réservation en attente</h1>

    <p>Bonjour,</p>
    <p>Une nouvelle réservation a été créée et est en attente de confirmation.</p>

    <h3>Détails de la réservation :</h3>
    <ul>
        <li><strong>Client :</strong> {{ $reservation->client->first_name }} {{ $reservation->client->last_name }}</li>
        <li><strong>Chauffeur assigné :</strong> {{ $reservation->chauffeur->first_name }} {{ $reservation->chauffeur->last_name }}</li>
        <li><strong>Date de réservation :</strong> {{ \Carbon\Carbon::parse($reservation->date)->format('d/m/Y') }}</li>
        <li><strong>Heure de ramassage :</strong> {{ $reservation->heure_ramassage }}</li>
        <li><strong>Numéro de vol :</strong> {{ $reservation->numero_vol }}</li>
        <li><strong>Nombre de personnes :</strong> {{ $reservation->nb_personnes }}</li>
        <li><strong>Nombre de valises :</strong> {{ $reservation->nb_valises }}</li>
        <li><strong>Nombre d'adresses :</strong> {{ $reservation->nb_adresses }}</li>
    </ul>

    <p>Merci de confirmer cette réservation dès que possible.</p>

    <p>Cordialement,</p>
    <p>L'équipe des réservations de CPRO Services</p>
</body>
</html>
