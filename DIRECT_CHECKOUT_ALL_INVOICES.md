# Système de Paiement Direct NabooPay - Toutes les Factures

## Objectif

**Toutes les factures** doivent maintenant utiliser directement l'URL de checkout NabooPay pour le paiement, sans passer par une page intermédiaire Laravel.

## Modifications Apportées

### 1. Vue des Factures (`resources/views/invoices/index.blade.php`)

#### Avant
```html
<a href="{{ route('reservations.pay.direct', $invoice->reservation->id) }}" 
   class="btn btn-success btn-sm">
    <i class="fas fa-credit-card"></i>
</a>
```

#### Après
```php
@php
    // Générer automatiquement l'URL de checkout si elle n'existe pas
    $checkoutUrl = $invoice->payment_url;
    if (!$checkoutUrl) {
        // Générer directement l'URL de checkout NabooPay
        $nabooPayService = app(\App\Services\NabooPayService::class);
        $result = $nabooPayService->createReservationTransaction($invoice->reservation);
        
        if (isset($result['checkout_url'])) {
            $checkoutUrl = $result['checkout_url'];
            // Mettre à jour la facture avec l'URL générée
            $invoice->update([
                'payment_url' => $checkoutUrl,
                'transaction_id' => $result['transaction_id'] ?? null
            ]);
        }
    }
@endphp
@if($checkoutUrl)
    <a href="{{ $checkoutUrl }}" 
       class="btn btn-success btn-sm"
       target="_blank">
        <i class="fas fa-credit-card"></i>
    </a>
@else
    <button class="btn btn-secondary btn-sm" disabled>
        <i class="fas fa-exclamation-triangle"></i>
    </button>
@endif
```

### 2. Génération Automatique lors de la Création

#### InvoiceController
- ✅ **Méthode ajoutée** : `generateCheckoutUrlForInvoice()`
- ✅ **Appel automatique** lors de la création de factures avec statut `en_attente`

#### ReservationController  
- ✅ **Méthode ajoutée** : `generateCheckoutUrlForInvoice()`
- ✅ **Appel automatique** lors de la confirmation de réservations

### 3. Commande Artisan pour les Factures Existantes

```bash
php artisan invoices:generate-checkout-urls
```

**Fonctionnalités** :
- ✅ Génère les URLs de checkout pour toutes les factures en attente
- ✅ Option `--force` pour régénérer même si l'URL existe déjà
- ✅ Barre de progression et résumé détaillé
- ✅ Gestion d'erreurs robuste

## Flux de Paiement

### ✅ Nouveau Flux (Toutes les Factures)
```
1. Utilisateur clique sur "Payer avec NabooPay"
   ↓
2. Vérification si payment_url existe
   ↓
3a. Si existe → Redirection directe vers checkout NabooPay
3b. Si n'existe pas → Génération automatique → Redirection directe
   ↓
4. Paiement sur NabooPay
   ↓
5. Webhook → Mise à jour statut facture/réservation
```

### ❌ Ancien Flux (Supprimé)
```
1. Utilisateur clique sur "Payer avec NabooPay"
   ↓
2. Redirection vers Laravel (reservations.pay.direct)
   ↓
3. Génération transaction NabooPay
   ↓
4. Redirection vers checkout NabooPay
   ↓
5. Paiement sur NabooPay
```

## Avantages

### 1. Performance
- **Moins de requêtes** : Pas de passage par Laravel si l'URL existe
- **Redirection directe** : Clic → Checkout NabooPay immédiatement
- **Moins de latence** : Suppression d'une étape intermédiaire

### 2. Expérience Utilisateur
- **Plus rapide** : Paiement en 1 clic
- **Plus fluide** : Pas d'attente supplémentaire
- **Plus fiable** : Moins de points de défaillance

### 3. Robustesse
- **Génération automatique** : URLs créées dès la création des factures
- **Fallback intelligent** : Génération à la volée si nécessaire
- **Gestion d'erreurs** : Boutons désactivés si génération impossible

## Structure des Données

### Facture avec URL de Checkout
```json
{
    "id": 3,
    "status": "en_attente",
    "payment_url": "https://checkout.naboopay.com/checkout/2d455b82-6648-4e2b-94ee-1ea248f165ad",
    "transaction_id": "TXN-123456",
    "amount": 37500
}
```

### Bouton de Paiement
```html
<a href="https://checkout.naboopay.com/checkout/2d455b82-6648-4e2b-94ee-1ea248f165ad" 
   class="btn btn-success btn-sm"
   target="_blank">
    <i class="fas fa-credit-card"></i>
</a>
```

## Gestion des Erreurs

### Facture sans Réservation
- ⚠️ **Log d'avertissement** : "Réservation manquante"
- 🔒 **Bouton désactivé** : Impossible de payer

### Erreur de Génération d'URL
- ⚠️ **Log d'erreur** : Détails de l'erreur NabooPay
- 🔒 **Bouton désactivé** : "Impossible de générer l'URL de paiement"

### Facture Déjà Payée
- ✅ **Bouton masqué** : Pas de bouton de paiement
- ✅ **Statut affiché** : "Payée" avec icône de validation

## Commandes Utiles

### Générer URLs pour Factures Existantes
```bash
php artisan invoices:generate-checkout-urls
```

### Forcer la Régénération
```bash
php artisan invoices:generate-checkout-urls --force
```

### Vérifier les Factures
```bash
php artisan tinker --execute="
\$invoices = App\Models\Invoice::where('status', 'en_attente')->get();
foreach (\$invoices as \$invoice) {
    echo 'ID: ' . \$invoice->id . ' - URL: ' . (\$invoice->payment_url ? 'Oui' : 'Non') . PHP_EOL;
}
"
```

## Impact

### ✅ Factures Nouvelles
- **URL générée automatiquement** lors de la création
- **Paiement direct** dès le premier clic
- **Aucune étape intermédiaire**

### ✅ Factures Existantes  
- **URL générée à la demande** lors du premier clic
- **Mise en cache** pour les clics suivants
- **Commande Artisan** pour traitement en lot

### ✅ Interface Utilisateur
- **Boutons intelligents** : Actifs/désactivés selon le contexte
- **Messages informatifs** : Explication des erreurs
- **Ouverture dans nouvel onglet** : `target="_blank"`

---

**Date d'implémentation** : 17/10/2025  
**Statut** : ✅ Implémenté  
**Impact** : Paiement direct pour toutes les factures
