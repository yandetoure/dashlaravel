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
            background-color: #10B981;
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
            border-left: 4px solid #10B981;
        }
        .driver-info {
            background-color: #d1fae5;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            border-left: 4px solid #059669;
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
        .contact-info {
            background-color: #f3f4f6;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>CPRO Services</h1>
        <h2>
            @switch($action)
                @case('confirmed')
                    Réservation Confirmée
                    @break
                @case('cancelled')
                    Réservation Annulée
                    @break
                @default
                    Notification de Réservation
            @endswitch
        </h2>
    </div>

    <div class="content">
        <p>Bonjour
            @if($reservation->client)
                {{ $reservation->client->first_name }} {{ $reservation->client->last_name }},
            @else
                {{ $reservation->first_name }} {{ $reservation->last_name }},
            @endif
        </p>

        <p>
            @switch($action)
                @case('confirmed')
                    Votre réservation a été confirmée. Voici les détails de votre trajet :
                    @break
                @case('cancelled')
                    Votre réservation a été annulée. Voici les détails :
                    @break
                @default
                    Voici les détails de votre réservation :
            @endswitch
        </p>

        <div class="reservation-details">
            <h3>Détails de la Réservation</h3>

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

        @if($action === 'confirmed' && $reservation->carDriver && $reservation->carDriver->chauffeur)
            <div class="driver-info">
                <h3>🚗 Informations du Chauffeur</h3>
                <p><strong>Nom du chauffeur :</strong> {{ $reservation->carDriver->chauffeur->first_name }} {{ $reservation->carDriver->chauffeur->last_name }}</p>
                @if($reservation->carDriver->chauffeur->phone_number)
                    <p><strong>Numéro de téléphone :</strong> <a href="tel:{{ $reservation->carDriver->chauffeur->phone_number }}">{{ $reservation->carDriver->chauffeur->phone_number }}</a></p>
                @endif
                @if($reservation->carDriver->car)
                    <p><strong>Véhicule :</strong> {{ $reservation->carDriver->car->marque }} {{ $reservation->carDriver->car->modele }} ({{ $reservation->carDriver->car->immatriculation }})</p>
                @endif
            </div>
        @endif

        <div class="contact-info">
            <h3>📞 Contact CPRO Services</h3>
            <p><strong>Téléphone :</strong> +221 77 705 67 67</p>
            <p><strong>WhatsApp :</strong> +221 77 705 69 69</p>
            <p><strong>Email :</strong> cproservices221@gmail.com</p>
        </div>

        @if($action === 'confirmed')
            <p><strong>Important :</strong> Veuillez être prêt à l'adresse de ramassage 15 minutes avant l'heure prévue. Le chauffeur vous contactera pour confirmer son arrivée.</p>
        @endif

        @if($action === 'cancelled')
            <p><strong>Note :</strong> Si vous avez des questions concernant cette annulation, n'hésitez pas à nous contacter.</p>
        @endif
    </div>

    <div class="footer">
        <p>CPRO Services - Transport de qualité</p>
        <p>Merci de votre confiance !</p>
    </div>
</body>
</html>
