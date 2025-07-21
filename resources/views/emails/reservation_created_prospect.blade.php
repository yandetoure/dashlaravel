<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Confirmation de réservation</title>
</head>
<body>
    <h1>Bonjour {{ $reservation->nom ?? 'Client' }},</h1>
    <p>Votre réservation a bien été prise en compte.</p>
    <p>Merci de votre confiance.</p>
    <p>Voici un récapitulatif de votre réservation :</p>
    <ul>
        <li>Email : {{ $reservation->email }}</li>
        <li>Date : {{ $reservation->date ?? 'Non précisée' }}</li>
        <li>Autres informations : ...</li>
    </ul>
    <p>Cordialement,<br>L'équipe</p>
</body>
</html>
