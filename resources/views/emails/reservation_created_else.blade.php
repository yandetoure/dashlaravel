<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle de votre réservation</title>
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
            background-color: #10b981;
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
            border-left: 4px solid #10b981;
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
        .chauffeur-info {
            background-color: #dbeafe;
            border: 1px solid #3b82f6;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding: 20px;
            color: #64748b;
            font-size: 14px;
        }
        .status {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
        }
        .status-en-attente {
            background-color: #fef3c7;
            color: #d97706;
        }
        .status-en-attente-chauffeur {
            background-color: #fee2e2;
            color: #dc2626;
        }
        .important {
            background-color: #f0f9ff;
            border: 1px solid #0ea5e9;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Nouvelle Réservation en attente</h1>
    </div>

    <div class="content">
        <p>Bonjour chère équipe,</p>

        <p>Une nouvelle demande de réservation doit être traitée</p>
        

        <div class="reservation-details">
            <h3>📋 Détails de votre réservation</h3>  

            <div class="detail-row">
                <span class="detail-label">No complet :</span>
                <span class="detail-value">{{ $reservation->first_name }} {{ $reservation->last_name }}</span>
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
            
            <div class="detail-row">
                <span class="detail-label">Statut :</span>
                <span class="detail-value">
                    <span class="status {{ $reservation->chauffeur_id ? 'status-en-attente' : 'status-en-attente-chauffeur' }}">
                        {{ $reservation->chauffeur_id ? 'En attente de confirmation' : 'En attente d\'assignation chauffeur' }}
                    </span>
                </span>
            </div>
        </div>

        @if($reservation->chauffeur_id)
        <div class="chauffeur-info">
            <h3>🚗 Chauffeur assigné</h3>
            <p><strong>Un chauffeur a été automatiquement assigné à votre réservation.</strong></p>
            <p>Vous recevrez une confirmation finale dans les prochaines heures.</p>
        </div>
        @else
        <div class="important">
            <h3>⏳ En cours de traitement</h3>
            <p>Nous recherchons actuellement un chauffeur disponible pour votre trajet.</p>
            <p>Vous recevrez une notification dès qu'un chauffeur sera assigné.</p>
        </div>
        @endif

    </div>

    <div class="footer">
        <p>Cet email confirme votre demande de réservation. Merci de le conserver.</p>
        <p>© {{ date('Y') }} CPRO Services. Tous droits réservés.</p>
    </div>
</body>
</html>
