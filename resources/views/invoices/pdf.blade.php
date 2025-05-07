<?php declare(strict_types=1); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture {{ $invoice->invoice_number }}</title>
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

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header, .footer {
            text-align: center;
            margin-bottom: 20px;
        }
        .content {
            margin: 0 auto;
            max-width: 800px;
        }
        .invoice-details, .payment-info {
            margin-bottom: 20px;
        }
        .invoice-details th, .invoice-details td {
            padding: 5px;
            border-bottom: 1px solid #ddd;
        }
        .invoice-details th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .status {
            padding: 5px;
            border-radius: 3px;
            color: white;
        }
        .status-paid {
            background-color: #28a745;
        }
        .status-unpaid {
            background-color: #ffc107;
            color: #333;
        }
        .status-overdue {
            background-color: #dc3545;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="content">
        <div class="header">
        <img src="{{ public_path('images/logo.png') }}" alt="Logo" style="height: 120px; width: auto;">

            {{-- <h1>Facture Cpro Services</h1> --}}
        </div>
        
        <div class="invoice-details">
            <table width="100%">
                <tr>
                    <td>
                        <strong>De:</strong><br>
                        <strong>Cpro Service</strong><br>
                        Sacré cœur, Dakar, Sénégal<br>
                        Email: 221cproservices@gmail.com<br>
                        Téléphone: +221 77 705 67 67
                    </td>
                    <td>
                        <strong>À:</strong><br>
                        {{ $invoice->reservation->client->first_name }} {{ $invoice->reservation->client->last_name }}<br>
                        {{ $invoice->reservation->adresse_rammassage }}<br>
                        Email: {{ $invoice->reservation->client->email }}<br>
                        Téléphone: {{ $invoice->reservation->client->phone_number }}
                    </td>
                </tr>
            </table>
        </div>

        <div class="invoice-details">
            <table width="100%">
                <tr>
                    <th>Service</th>
                    <th>Date</th>
                    <th>Détails</th>
                    <th>Montant</th>
                </tr>
                <tr>
                    <td>Transport</td>
                    <td>{{ \Carbon\Carbon::parse($invoice->reservation->date)->format('d/m/Y') }}</td>
                    <td>
                        <strong>Heure de ramassage:</strong> {{ $invoice->reservation->heure_ramassage }}<br>
                        <strong>Nombre de personnes:</strong> {{ $invoice->reservation->nb_personnes }}<br>
                        <strong>Nombre de valises:</strong> {{ $invoice->reservation->nb_valises }}<br>
                        Chauffeur: {{ optional($invoice->reservation->chauffeur)->first_name }} {{ optional($invoice->reservation->chauffeur)->last_name }}
                    </td>
                    <td>{{ number_format($invoice->amount) }} Fcfa</td>
                </tr>
            </table>
        </div>

        <div class="payment-info">
            <strong>Statut:</strong> 
                                @if($invoice->status == 'Payée')
                                    <span class="badge bg-success">Payée</span>
                                @elseif($invoice->status == 'unpaid')
                                    <span class="badge bg-warning text-dark">En attente de paiement</span>
                                @elseif($invoice->status == 'En retard')
                                    <span class="badge bg-danger">Offert</span>
                                @else
                                    <span class="badge bg-secondary">{{ $invoice->status }}</span>
                                @endif
        </div>

        {{-- <div class="footer"> --}}
              <!-- Notes et conditions -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <p><strong>Conditions de paiement:</strong> Paiement à réception de facture.</p>
                            <p><strong>Note:</strong> Merci de votre confiance. Pour toute question concernant cette facture, veuillez nous contacter.</p>
                        </div>
                    </div>
        {{-- </div> --}}
    </div>
</body>
</html>
