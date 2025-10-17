# Test du Système de Paiement Sans Escrow

## 🎯 Objectif
Vérifier que les paiements passent directement sans être bloqués par l'escrow.

## 🔧 Changements Effectués

### 1. Désactivation de l'Escrow
```php
// Dans NabooPayService.php
'is_escrow' => false, // Pas d'escrow - paiement direct
```

### 2. Correction des Statuts
- Utilisation de `'payé'` au lieu de `'payée'` dans tout le code
- Mise à jour des contrôleurs, vues et documentation

## 🧪 Test du Webhook

### Test avec Paiement Réussi
```bash
curl -X POST http://localhost:8000/webhook/naboopay/test \
  -H "Content-Type: application/json" \
  -d '{
    "transaction_id": "test_direct_payment_123",
    "status": "paid"
  }'
```

### Résultat Attendu
- Facture : `en_attente` → `payé`
- Réservation : `Confirmée` → `Payée`
- Date de paiement : maintenant

## 📊 Vérification des Logs

### Logs à Surveiller
```bash
# Rechercher les webhooks
grep "Webhook NabooPay" storage/logs/laravel.log

# Rechercher les mises à jour de facture
grep "Facture mise à jour" storage/logs/laravel.log

# Rechercher les mises à jour de réservation
grep "Réservation mise à jour" storage/logs/laravel.log
```

## 🔍 Points de Contrôle

### 1. Vérifier la Configuration NabooPay
- ✅ `is_escrow` = `false`
- ✅ Webhook URL configuré
- ✅ URLs de succès/erreur configurées

### 2. Vérifier les Statuts de Base de Données
```sql
-- Vérifier l'enum des factures
SHOW COLUMNS FROM invoices WHERE Field = 'status';

-- Résultat attendu : ENUM('payé','en_attente','offert')
```

### 3. Vérifier les Statuts des Réservations
```sql
-- Vérifier l'enum des réservations
SHOW COLUMNS FROM reservations WHERE Field = 'status';

-- Résultat attendu : ENUM('En_attente','Confirmée','Annulée','Payée')
```

## 🚀 Test Complet

### Scénario de Test
1. **Créer une réservation** avec statut `Confirmée`
2. **Créer une facture** avec statut `en_attente`
3. **Simuler un paiement** via webhook avec statut `paid`
4. **Vérifier les mises à jour** :
   - Facture → `payé`
   - Réservation → `Payée`
   - Date de paiement définie

### Script de Test PHP
```php
<?php
// test_direct_payment.php

use App\Models\Reservation;
use App\Models\Invoice;

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
    'invoice_number' => 'TEST-' . time(),
    'invoice_date' => now(),
    'transaction_id' => 'test_direct_' . time()
]);

echo "=== AVANT WEBHOOK ===\n";
echo "Réservation: {$reservation->status}\n";
echo "Facture: {$invoice->status}\n";

// Simuler le webhook
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

echo "\n=== APRÈS WEBHOOK ===\n";
$invoice->refresh();
$reservation->refresh();

echo "Réservation: {$reservation->status}\n";
echo "Facture: {$invoice->status}\n";
echo "Date paiement: {$invoice->paid_at}\n";

// Vérifications
if ($invoice->status === 'payé' && $reservation->status === 'Payée') {
    echo "\n✅ TEST RÉUSSI - Paiement direct fonctionne\n";
} else {
    echo "\n❌ TEST ÉCHOUÉ - Vérifier la configuration\n";
}
```

## 🔧 Dépannage

### Problème : Paiement reste en `pending`
**Cause** : Escrow encore activé
**Solution** : Vérifier `is_escrow => false` dans NabooPayService

### Problème : Erreur "Data truncated for column 'status'"
**Cause** : Enum incorrect dans la base de données
**Solution** : Utiliser `'payé'` au lieu de `'payée'`

### Problème : Webhook non reçu
**Cause** : URL webhook incorrecte
**Solution** : Vérifier la configuration NabooPay

## 📈 Monitoring

### Métriques à Surveiller
- **Taux de succès des paiements** : > 95%
- **Temps de traitement webhook** : < 5 secondes
- **Erreurs de statut** : 0%

### Alertes Recommandées
- ⚠️ Paiement en `pending` > 10 minutes
- ❌ Erreur webhook
- 📊 Taux d'échec > 5%

---

**Note** : Avec l'escrow désactivé, les paiements devraient passer directement de `pending` à `paid` sans intervention manuelle.
