<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code de Paiement - {{ $invoice->invoice_number }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .qr-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            padding: 2rem;
            margin: 2rem auto;
            max-width: 500px;
        }
        .qr-code {
            text-align: center;
            margin: 2rem 0;
        }
        .qr-code svg {
            border: 3px solid #28a745;
            border-radius: 15px;
            padding: 1rem;
            background: white;
            max-width: 100%;
            height: auto;
        }
        .invoice-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin: 1rem 0;
        }
        .btn-whatsapp {
            background: #25D366;
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            margin: 10px 5px;
            transition: all 0.3s ease;
        }
        .btn-whatsapp:hover {
            background: #128C7E;
            color: white;
            transform: translateY(-2px);
        }
        .btn-primary {
            background: #007bff;
            border: none;
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: bold;
            margin: 10px 5px;
        }
        .payment-url {
            background: #e9ecef;
            border-radius: 8px;
            padding: 1rem;
            word-break: break-all;
            font-family: monospace;
            font-size: 0.9rem;
        }
        .status-badge {
            font-size: 1.1rem;
            padding: 8px 16px;
            border-radius: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="qr-container">
            <!-- En-tête -->
            <div class="text-center mb-4">
                <h1 class="h3 text-primary">
                    <i class="fas fa-qrcode"></i> QR Code de Paiement
                </h1>
                <p class="text-muted">Scannez le QR code pour payer votre facture</p>
            </div>

            <!-- Informations de la facture -->
            <div class="invoice-info">
                <div class="row">
                    <div class="col-md-6">
                        <h5><i class="fas fa-file-invoice"></i> Facture</h5>
                        <p class="mb-1"><strong>Numéro:</strong> {{ $invoice->invoice_number }}</p>
                        <p class="mb-1"><strong>Date:</strong> {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</p>
                        <p class="mb-0"><strong>Statut:</strong> 
                            <span class="badge bg-warning status-badge">{{ ucfirst($invoice->status) }}</span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h5><i class="fas fa-money-bill-wave"></i> Montant</h5>
                        <p class="mb-1"><strong>À payer:</strong> {{ $invoice->formatted_amount }}</p>
                        @if($invoice->reservation)
                            <p class="mb-1"><strong>Trajet:</strong> {{ $invoice->reservation->trip->departure }} → {{ $invoice->reservation->trip->destination }}</p>
                            <p class="mb-0"><strong>Personnes:</strong> {{ $invoice->reservation->nb_personnes }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- QR Code -->
            <div class="qr-code">
                <div class="d-flex justify-content-center">
                    {!! $qrCodeSvg !!}
                </div>
                <p class="text-muted mt-3">
                    <i class="fas fa-mobile-alt"></i> Scannez avec votre téléphone pour payer
                </p>
            </div>

            <!-- URL de paiement -->
            <div class="mb-4">
                <h6><i class="fas fa-link"></i> Lien de paiement direct:</h6>
                <div class="payment-url">{{ $paymentUrl }}</div>
            </div>

            <!-- Boutons d'action -->
            <div class="text-center">
                <a href="{{ route('invoices.whatsapp', $invoice->id) }}" class="btn-whatsapp" target="_blank">
                    <i class="fab fa-whatsapp"></i> Envoyer par WhatsApp
                </a>
                
                <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-primary">
                    <i class="fas fa-eye"></i> Voir la facture
                </a>
                
                <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
                    <i class="fas fa-list"></i> Liste des factures
                </a>
            </div>

            <!-- Instructions -->
            <div class="mt-4 p-3 bg-light rounded">
                <h6><i class="fas fa-info-circle text-info"></i> Instructions:</h6>
                <ol class="mb-0">
                    <li>Scannez le QR code avec votre téléphone</li>
                    <li>Ou cliquez sur le lien de paiement</li>
                    <li>Choisissez votre méthode de paiement (Wave, Orange Money, etc.)</li>
                    <li>Confirmez le paiement</li>
                </ol>
            </div>

            <!-- Méthodes de paiement acceptées -->
            <div class="mt-3 text-center">
                <h6><i class="fas fa-credit-card"></i> Méthodes de paiement acceptées:</h6>
                <div class="d-flex justify-content-center gap-3">
                    <span class="badge bg-success">Wave</span>
                    <span class="badge bg-warning text-dark">Orange Money</span>
                    <span class="badge bg-info">Free Money</span>
                    <span class="badge bg-primary">Virement bancaire</span>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
