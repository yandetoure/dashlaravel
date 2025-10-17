# Exemples d'Utilisation du Webhook NabooPay

## 🧪 Tests Manuels

### 1. Test avec une Transaction Existante

```bash
# Récupérer une transaction existante
curl -X POST http://localhost:8000/webhook/naboopay/test \
  -H "Content-Type: application/json" \
  -d '{
    "transaction_id": "txn_existing_123",
    "status": "paid"
  }'
```

### 2. Test avec Différents Statuts

```bash
# Test paiement réussi
curl -X POST http://localhost:8000/webhook/naboopay/test \
  -H "Content-Type: application/json" \
  -d '{
    "transaction_id": "test_success_123",
    "status": "paid"
  }'

# Test paiement échoué
curl -X POST http://localhost:8000/webhook/naboopay/test \
  -H "Content-Type: application/json" \
  -d '{
    "transaction_id": "test_failed_123",
    "status": "failed"
  }'

# Test paiement en cours
curl -X POST http://localhost:8000/webhook/naboopay/test \
  -H "Content-Type: application/json" \
  -d '{
    "transaction_id": "test_pending_123",
    "status": "pending"
  }'
```

## 🔍 Vérification des Résultats

### 1. Vérifier la Facture
```php
// Dans tinker
$invoice = App\Models\Invoice::where('transaction_id', 'test_success_123')->first();
echo "Statut facture: " . $invoice->status;
echo "Date paiement: " . $invoice->paid_at;
```

### 2. Vérifier la Réservation
```php
// Dans tinker
$reservation = $invoice->reservation;
echo "Statut réservation: " . $reservation->status;
```

### 3. Vérifier les Logs
```bash
# Voir les derniers logs de webhook
tail -f storage/logs/laravel.log | grep "Webhook NabooPay"
```

## 📊 Script de Test Complet

```php
<?php
// test_webhook.php

use App\Models\Invoice;
use App\Models\Reservation;

// Créer une réservation de test
$reservation = Reservation::create([
    'client_id' => 1,
    'date' => now()->addDays(1),
    'heure_ramassage' => '08:00',
    'adresse_rammassage' => 'Test Address',
    'numero_vol' => 'TEST123',
    'nb_personnes' => 2,
    'nb_valises' => 1,
    'tarif' => 50000,
    'status' => 'Confirmée'
]);

// Créer une facture de test
$invoice = Invoice::create([
    'reservation_id' => $reservation->id,
    'amount' => 50000,
    'status' => 'en_attente',
    'invoice_number' => 'TEST-' . time(),
    'invoice_date' => now(),
    'transaction_id' => 'test_webhook_' . time()
]);

echo "Réservation créée: ID {$reservation->id}\n";
echo "Facture créée: ID {$invoice->id}\n";
echo "Transaction ID: {$invoice->transaction_id}\n";

// Tester le webhook
$webhookData = [
    'transaction_id' => $invoice->transaction_id,
    'status' => 'paid'
];

$response = file_get_contents('http://localhost:8000/webhook/naboopay/test', false, stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/json',
        'content' => json_encode($webhookData)
    ]
]));

echo "Réponse webhook: " . $response . "\n";

// Vérifier les résultats
$invoice->refresh();
$reservation->refresh();

echo "Statut facture après webhook: {$invoice->status}\n";
echo "Statut réservation après webhook: {$reservation->status}\n";
echo "Date paiement: {$invoice->paid_at}\n";
```

## 🚨 Scénarios d'Erreur

### 1. Transaction ID Inexistant
```bash
curl -X POST http://localhost:8000/webhook/naboopay/test \
  -H "Content-Type: application/json" \
  -d '{
    "transaction_id": "inexistant_123",
    "status": "paid"
  }'
```

**Résultat attendu** : Erreur 404 "Facture non trouvée"

### 2. Statut Invalide
```bash
curl -X POST http://localhost:8000/webhook/naboopay/test \
  -H "Content-Type: application/json" \
  -d '{
    "transaction_id": "test_123",
    "status": "invalid_status"
  }'
```

**Résultat attendu** : Erreur de validation

### 3. Transaction ID Manquant
```bash
curl -X POST http://localhost:8000/webhook/naboopay/test \
  -H "Content-Type: application/json" \
  -d '{
    "status": "paid"
  }'
```

**Résultat attendu** : Erreur de validation

## 📈 Monitoring en Temps Réel

### Script de Monitoring
```bash
#!/bin/bash
# monitor_webhooks.sh

echo "Monitoring des webhooks NabooPay..."
echo "Appuyez sur Ctrl+C pour arrêter"

tail -f storage/logs/laravel.log | grep --line-buffered "Webhook NabooPay\|Facture mise à jour\|Réservation mise à jour" | while read line; do
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $line"
done
```

### Dashboard Simple
```php
<?php
// webhook_dashboard.php

$recentWebhooks = collect();
$recentInvoices = App\Models\Invoice::latest()->take(10)->get();
$recentReservations = App\Models\Reservation::latest()->take(10)->get();

echo "=== DASHBOARD WEBHOOK ===\n";
echo "Factures récentes:\n";
foreach ($recentInvoices as $invoice) {
    echo "- ID: {$invoice->id} | Statut: {$invoice->status} | Transaction: {$invoice->transaction_id}\n";
}

echo "\nRéservations récentes:\n";
foreach ($recentReservations as $reservation) {
    echo "- ID: {$reservation->id} | Statut: {$reservation->status} | Date: {$reservation->date}\n";
}
```

## 🔧 Configuration NabooPay

### URL du Webhook à Configurer
```
Production: https://votre-domaine.com/webhook/naboopay
Développement: http://localhost:8000/webhook/naboopay
```

### Paramètres NabooPay
- **Méthode** : POST
- **Format** : JSON
- **Timeout** : 30 secondes
- **Retry** : 3 tentatives
- **Headers** : Content-Type: application/json

---

**Note** : Ces exemples sont destinés au développement et aux tests. En production, utilisez uniquement les vraies notifications NabooPay.
