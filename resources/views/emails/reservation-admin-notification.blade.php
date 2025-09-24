<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Notification de Réservation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #3B82F6;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f9fafb;
            padding: 20px;
            border-radius: 0 0 8px 8px;
        }
        .reservation-details {
            background-color: white;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            border-left: 4px solid #3B82F6;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-en-attente { background-color: #fef3c7; color: #92400e; }
        .status-confirmée { background-color: #d1fae5; color: #065f46; }
        .status-annulée { background-color: #fee2e2; color: #991b1b; }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>CPRO Services</h1>
        <h2>
            @switch($action)
                @case('created')
                    Nouvelle Réservation Créée
                    @break
                @case('confirmed')
                    Réservation Confirmée
                    @break
                @case('cancelled')
                    Réservation Annulée
                    @break
                @case('updated')
                    Réservation Modifiée
                    @break
                @default
                    Notification de Réservation
            @endswitch
        </h2>
    </div>

    <div class="content">
        <p>Bonjour,</p>

        <p>
            @switch($action)
                @case('created')
                    Une nouvelle réservation a été créée dans le système.
                    @break
                @case('confirmed')
                    Une réservation a été confirmée.
                    @break
                @case('cancelled')
                    Une réservation a été annulée.
                    @break
                @case('updated')
                    Une réservation a été modifiée.
                    @break
                @default
                    Une action a été effectuée sur une réservation.
            @endswitch
        </p>

        <div class="reservation-details">
            <h3>Détails de la Réservation</h3>

            <p><strong>Client :</strong>
                @if($reservation->client)
                    {{ $reservation->client->first_name }} {{ $reservation->client->last_name }}
                @else
                    {{ $reservation->first_name }} {{ $reservation->last_name }} (Prospect)
                @endif
            </p>

            <p><strong>Email :</strong>
                @if($reservation->client)
                    {{ $reservation->client->email }}
                @else
                    {{ $reservation->email }}
                @endif
            </p>

            @if($reservation->carDriver && $reservation->carDriver->chauffeur)
                <p><strong>Chauffeur :</strong> {{ $reservation->carDriver->chauffeur->first_name }} {{ $reservation->carDriver->chauffeur->last_name }}</p>
            @endif

            @if($reservation->trip)
                <p><strong>Trajet :</strong> {{ $reservation->trip->departure }} → {{ $reservation->trip->destination }}</p>
            @endif

            <p><strong>Date :</strong> {{ \Carbon\Carbon::parse($reservation->date)->format('d/m/Y') }}</p>
            <p><strong>Heure de ramassage :</strong> {{ \Carbon\Carbon::parse($reservation->heure_ramassage)->format('H:i') }}</p>
            <p><strong>Heure de vol :</strong> {{ \Carbon\Carbon::parse($reservation->heure_vol)->format('H:i') }}</p>
            <p><strong>Nombre de personnes :</strong> {{ $reservation->nb_personnes }}</p>
            <p><strong>Nombre de valises :</strong> {{ $reservation->nb_valises }}</p>
            <p><strong>Adresse de ramassage :</strong> {{ $reservation->adresse_rammassage }}</p>
            <p><strong>Tarif :</strong> {{ number_format($reservation->tarif, 0, ',', ' ') }} FCFA</p>

            <p><strong>Statut :</strong>
                <span class="status-badge
                    @switch($reservation->status)
                        @case('En_attente')
                            status-en-attente
                            @break
                        @case('confirmée')
                            status-confirmée
                            @break
                        @case('annulée')
                            status-annulée
                            @break
                        @default
                            status-en-attente
                    @endswitch">
                    {{ ucfirst($reservation->status) }}
                </span>
            </p>

            @if($reservation->numero_vol)
                <p><strong>Numéro de vol :</strong> {{ $reservation->numero_vol }}</p>
            @endif
        </div>

        <p>Cette notification a été envoyée automatiquement par le système de gestion des réservations CPRO Services.</p>
    </div>

    <div class="footer">
        <p>CPRO Services - Système de Gestion des Réservations</p>
        <p>Email : cproservices221@gmail.com</p>
    </div>
</body>
</html>
