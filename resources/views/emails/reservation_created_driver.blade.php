<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle réservation assignée</title>
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
            background-color: #2563eb;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f8fafc;
            padding: 20px;
            border: 1px solid #e2e8f0;
        }
        .reservation-details {
            background-color: white;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            border-left: 4px solid #2563eb;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin: 8px 0;
            padding: 5px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .detail-label {
            font-weight: bold;
            color: #475569;
        }
        .detail-value {
            color: #1e293b;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding: 20px;
            color: #64748b;
            font-size: 14px;
        }
        .important {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🚗 Nouvelle réservation assignée</h1>
        <p>Vous avez une nouvelle réservation à traiter</p>
    </div>

    <div class="content">
        <p>Bonjour,</p>
        
        <p>Une nouvelle réservation vous a été automatiquement assignée. Veuillez consulter les détails ci-dessous :</p>

        <div class="reservation-details">
            <h3>📋 Détails de la réservation</h3>
            
            <div class="detail-row">
                <span class="detail-label">Nom du client :</span>
                <span class="detail-value">{{ $reservation->first_name }} {{ $reservation->last_name }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Email :</span>
                <span class="detail-value">{{ $reservation->email }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Téléphone :</span>
                <span class="detail-value">{{ $reservation->phone_number }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Date :</span>
                <span class="detail-value">{{ \Carbon\Carbon::parse($reservation->date)->format('d/m/Y') }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Heure de ramassage :</span>
                <span class="detail-value">{{ $reservation->heure_ramassage }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Adresse de ramassage :</span>
                <span class="detail-value">{{ $reservation->adresse_rammassage }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Nombre de personnes :</span>
                <span class="detail-value">{{ $reservation->nb_personnes }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Nombre de valises :</span>
                <span class="detail-value">{{ $reservation->nb_valises }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Tarif :</span>
                <span class="detail-value">{{ number_format($reservation->tarif, 0, ',', ' ') }} FCFA</span>
            </div>
        </div>

        <div class="important">
            <p><strong>⚠️ Important :</strong></p>
            <ul>
                <li>Cette réservation a été créée par un prospect (nouveau client)</li>
                <li>Veuillez confirmer votre disponibilité dans le système</li>
                <li>Contactez le client pour confirmer les détails</li>
                <li>Mettez à jour le statut de la réservation après confirmation</li>
            </ul>
        </div>

        <p>Merci de traiter cette réservation dans les plus brefs délais.</p>
        
        <p>Cordialement,<br>
        L'équipe CPRO Services</p>
    </div>

    <div class="footer">
        <p>Cet email a été envoyé automatiquement. Merci de ne pas y répondre directement.</p>
        <p>© {{ date('Y') }} CPRO Services. Tous droits réservés.</p>
    </div>
</body>
</html>

