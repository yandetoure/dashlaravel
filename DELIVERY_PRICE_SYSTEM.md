# Système de Prix de Livraison avec NabooPay

## 🎯 Vue d'ensemble

Le système utilise le **prix de livraison** (champ `tarif` de la réservation) comme montant de paiement NabooPay. Les frais de transaction NabooPay sont prélevés sur ce montant, et le client paie uniquement le prix de livraison sans frais supplémentaires.

## 💰 Flux de Prix de Livraison

### 1. **Calcul du Prix de Livraison**
Le prix de livraison est calculé selon la formule de tarification de la réservation :
```php
// Dans le modèle Reservation
$tarif = $reservation->tarif; // Prix calculé selon nb_personnes, nb_valises, etc.
```

### 2. **Envoi à NabooPay**
```php
$products = [
    [
        'name' => 'Prix de livraison - Dakar vers AIBD',
        'category' => 'Transport',
        'amount' => (int) $amount, // Prix de livraison en XOF
        'quantity' => 1,
        'description' => 'Prix de livraison pour réservation de transport...'
    ]
];
```

### 3. **Affichage NabooPay**
- **Nom du produit** : "Prix de livraison - [Départ] vers [Arrivée]"
- **Montant affiché** : Prix de livraison exact (ex: 50,000 XOF)
- **Description** : Détails de la réservation (personnes, valises)

## 🔄 Calcul des Frais

### Exemple Concret
**Réservation Dakar → AIBD :**
- **Prix de livraison** : 50,000 XOF
- **Client paie** : 50,000 XOF (aucun frais visible)
- **Frais NabooPay** : 1,250 XOF (2.5% pour Wave)
- **Vendeur reçoit** : 48,750 XOF

### Formule de Calcul
```php
$prixLivraison = $reservation->tarif;           // 50,000 XOF
$fraisNabooPay = $prixLivraison * $tauxFrais;   // 1,250 XOF (2.5%)
$montantNet = $prixLivraison - $fraisNabooPay;  // 48,750 XOF
```

## 📊 Structure des Données

### Réservation
```php
$reservation = [
    'tarif' => 50000,        // Prix de livraison calculé
    'nb_personnes' => 2,     // Nombre de personnes
    'nb_valises' => 1,       // Nombre de valises
    'trip_id' => 1,          // ID du trajet
    // ... autres champs
];
```

### Produit NabooPay
```php
$product = [
    'name' => 'Prix de livraison - Dakar vers AIBD',
    'amount' => 50000,       // Prix de livraison
    'description' => 'Prix de livraison pour réservation de transport - Dakar vers AIBD (2 personne(s), 1 valise(s))'
];
```

### Facture
```php
$invoice = [
    'amount' => 50000,              // Prix de livraison
    'total_amount_paid' => 50000,   // Montant payé par le client
    'fee_amount' => 1250,           // Frais NabooPay
    'net_amount_received' => 48750, // Montant net reçu
];
```

## 🧪 Test du Système

### Test avec Réservation Réelle
```php
// Créer une réservation de test
$reservation = Reservation::create([
    'tarif' => 50000,        // Prix de livraison
    'nb_personnes' => 2,
    'nb_valises' => 1,
    'status' => 'Confirmée'
]);

// Créer le paiement NabooPay
$result = $nabooPayService->createReservationTransaction($reservation);

// Vérifier le montant envoyé
echo "Montant NabooPay: " . $result['data']['amount']; // 50000
```

### Test du Webhook
```bash
curl -X POST http://localhost:8000/webhook/naboopay/test \
  -H "Content-Type: application/json" \
  -d '{
    "transaction_id": "test_livraison_123",
    "status": "paid",
    "amount": 50000,
    "payment_method": "wave"
  }'
```

## 📈 Avantages du Système

### Pour les Clients
- ✅ **Prix transparent** : Seul le prix de livraison est affiché
- ✅ **Aucun frais caché** : Pas de frais supplémentaires visibles
- ✅ **Clarté** : Description claire du service payé

### Pour la Plateforme
- ✅ **Contrôle des coûts** : Frais prélevés sur le prix de livraison
- ✅ **Visibilité** : Calcul automatique des frais
- ✅ **Reporting** : Suivi des coûts de transaction

## 🔍 Vérifications Importantes

### 1. **Prix de Livraison Correct**
```php
// Vérifier que le tarif est bien calculé
$reservation = Reservation::find(1);
echo "Prix de livraison: " . $reservation->tarif . " XOF";
```

### 2. **Montant NabooPay**
```php
// Vérifier le montant envoyé à NabooPay
$result = $nabooPayService->createReservationTransaction($reservation);
echo "Montant NabooPay: " . $result['data']['amount'] . " XOF";
```

### 3. **Calcul des Frais**
```php
// Vérifier le calcul des frais
$invoice = Invoice::where('reservation_id', $reservation->id)->first();
echo "Frais calculés: " . $invoice->fee_amount . " XOF";
echo "Montant net: " . $invoice->net_amount_received . " XOF";
```

## 📝 Configuration NabooPay

### Paramètres Importants
- **Fee Payer** : `seller` (frais sur le vendeur)
- **Product Name** : "Prix de livraison - [Trajet]"
- **Amount** : Prix de livraison exact
- **Description** : Détails de la réservation

### Exemple de Configuration
```json
{
  "method_of_payment": ["WAVE", "ORANGE_MONEY"],
  "products": [{
    "name": "Prix de livraison - Dakar vers AIBD",
    "amount": 50000,
    "description": "Prix de livraison pour réservation de transport - Dakar vers AIBD (2 personne(s), 1 valise(s))"
  }],
  "fee_payer": "seller",
  "is_escrow": false
}
```

## 🚀 Résultat Final

### Interface NabooPay
- **Titre** : "Prix de livraison - Dakar vers AIBD"
- **Montant** : "50,000 XOF"
- **Description** : "Prix de livraison pour réservation de transport..."

### Après Paiement
- **Client** : A payé 50,000 XOF (prix de livraison)
- **Plateforme** : Reçoit 48,750 XOF (après frais)
- **NabooPay** : Prélève 1,250 XOF (frais de transaction)

---

**Note** : Le système garantit que le client paie uniquement le prix de livraison, tandis que les frais de transaction sont transparents et gérés par la plateforme.
