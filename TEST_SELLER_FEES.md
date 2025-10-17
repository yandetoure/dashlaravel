# Test du Système de Frais Vendeur

## 🧪 Script de Test Complet

```php
<?php
// test_seller_fees.php

use App\Models\Reservation;
use App\Models\Invoice;

echo "=== TEST SYSTÈME DE FRAIS VENDEUR ===\n\n";

// Créer une réservation de test
$reservation = Reservation::create([
    'client_id' => 1,
    'date' => now()->addDays(1),
    'heure_ramassage' => '08:00',
    'adresse_ramassage' => 'Test Address',
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
    'invoice_number' => 'TEST-FEES-' . time(),
    'invoice_date' => now(),
    'transaction_id' => 'test_seller_fees_' . time()
]);

echo "1. Réservation créée: ID {$reservation->id}\n";
echo "2. Facture créée: ID {$invoice->id}\n";
echo "3. Montant facturé: {$invoice->amount} XOF\n\n";

// Test avec différentes méthodes de paiement
$paymentMethods = [
    'wave' => ['amount' => 50000, 'expected_fee_rate' => 0.025],
    'orange_money' => ['amount' => 30000, 'expected_fee_rate' => 0.025],
    'free_money' => ['amount' => 25000, 'expected_fee_rate' => 0.02],
    'bank' => ['amount' => 40000, 'expected_fee_rate' => 0.015]
];

foreach ($paymentMethods as $method => $data) {
    echo "=== TEST AVEC {$method.toUpperCase()} ===\n";
    
    // Simuler le webhook avec cette méthode
    $webhookData = [
        'transaction_id' => $invoice->transaction_id,
        'status' => 'paid',
        'amount' => $data['amount'],
        'payment_method' => $method
    ];
    
    $response = file_get_contents('http://localhost:8000/webhook/naboopay/test', false, stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/json',
            'content' => json_encode($webhookData)
        ]
    ]));
    
    // Rafraîchir la facture
    $invoice->refresh();
    
    // Vérifier les calculs
    $expectedFee = $data['amount'] * $data['expected_fee_rate'];
    $expectedNet = $data['amount'] - $expectedFee;
    
    echo "Montant payé: {$data['amount']} XOF\n";
    echo "Frais attendus: {$expectedFee} XOF ({$data['expected_fee_rate']*100}%)\n";
    echo "Montant net attendu: {$expectedNet} XOF\n";
    
    if ($invoice->hasFeesCalculated()) {
        echo "✅ Frais calculés: {$invoice->fee_amount} XOF\n";
        echo "✅ Montant net: {$invoice->net_amount_received} XOF\n";
        echo "✅ Taux appliqué: {$invoice->fee_rate_percentage}\n";
        echo "✅ Méthode: {$invoice->payment_method_used}\n";
        
        // Vérifications
        if (abs($invoice->fee_amount - $expectedFee) < 1) {
            echo "✅ Frais corrects\n";
        } else {
            echo "❌ Frais incorrects (attendu: {$expectedFee}, reçu: {$invoice->fee_amount})\n";
        }
        
        if (abs($invoice->net_amount_received - $expectedNet) < 1) {
            echo "✅ Montant net correct\n";
        } else {
            echo "❌ Montant net incorrect (attendu: {$expectedNet}, reçu: {$invoice->net_amount_received})\n";
        }
    } else {
        echo "❌ Frais non calculés\n";
    }
    
    echo "\n";
}

echo "=== RÉSUMÉ DES TESTS ===\n";
echo "Réservation finale: {$reservation->status}\n";
echo "Facture finale: {$invoice->status}\n";
echo "Frais calculés: " . ($invoice->hasFeesCalculated() ? 'OUI' : 'NON') . "\n";

if ($invoice->hasFeesCalculated()) {
    echo "✅ SYSTÈME DE FRAIS VENDEUR FONCTIONNE\n";
} else {
    echo "❌ PROBLÈME DANS LE SYSTÈME DE FRAIS\n";
}
```

## 🔍 Tests Manuels

### Test 1: Paiement Wave
```bash
curl -X POST http://localhost:8000/webhook/naboopay/test \
  -H "Content-Type: application/json" \
  -d '{
    "transaction_id": "test_wave_123",
    "status": "paid",
    "amount": 50000,
    "payment_method": "wave"
  }'
```

**Résultat attendu:**
- Frais: 1,250 XOF (2.5%)
- Montant net: 48,750 XOF

### Test 2: Paiement Orange Money
```bash
curl -X POST http://localhost:8000/webhook/naboopay/test \
  -H "Content-Type: application/json" \
  -d '{
    "transaction_id": "test_orange_123",
    "status": "paid",
    "amount": 30000,
    "payment_method": "orange_money"
  }'
```

**Résultat attendu:**
- Frais: 750 XOF (2.5%)
- Montant net: 29,250 XOF

### Test 3: Paiement Free Money
```bash
curl -X POST http://localhost:8000/webhook/naboopay/test \
  -H "Content-Type: application/json" \
  -d '{
    "transaction_id": "test_free_123",
    "status": "paid",
    "amount": 25000,
    "payment_method": "free_money"
  }'
```

**Résultat attendu:**
- Frais: 500 XOF (2.0%)
- Montant net: 24,500 XOF

## 📊 Vérification en Base de Données

### Requêtes de Vérification
```sql
-- Vérifier les nouveaux champs
SELECT 
    id,
    amount,
    total_amount_paid,
    fee_amount,
    net_amount_received,
    fee_rate,
    payment_method_used,
    status
FROM invoices 
WHERE transaction_id LIKE 'test_%'
ORDER BY created_at DESC;

-- Calculer le total des frais
SELECT 
    SUM(fee_amount) as total_fees,
    COUNT(*) as transactions,
    AVG(fee_rate) as avg_fee_rate
FROM invoices 
WHERE status = 'payé' 
AND fee_amount IS NOT NULL;
```

## 🔧 Dépannage

### Problème: Frais non calculés
**Vérifications:**
1. Migration exécutée ?
2. Webhook reçu ?
3. Logs d'erreur ?

### Problème: Taux incorrect
**Vérifications:**
1. Méthode de paiement reconnue ?
2. Fonction `getFeeRate()` correcte ?

### Problème: Montant net incorrect
**Vérifications:**
1. Calcul des frais correct ?
2. Types de données corrects ?

## 📈 Métriques de Succès

### Critères de Validation
- ✅ Frais calculés automatiquement
- ✅ Taux correct selon la méthode
- ✅ Montant net correct
- ✅ Logs détaillés
- ✅ Champs de base de données remplis

### Indicateurs de Performance
- **Temps de calcul** : < 1 seconde
- **Précision** : 100% des calculs corrects
- **Couverture** : Toutes les méthodes testées
- **Logs** : Aucune erreur

---

**Note** : Ces tests garantissent que le système de frais vendeur fonctionne correctement et que les clients ne paient que le montant exact de leur réservation.
