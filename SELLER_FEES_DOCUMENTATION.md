# Système de Frais Vendeur - NabooPay

## 🎯 Vue d'ensemble

Le système est maintenant configuré pour que les frais de transaction NabooPay soient prélevés sur le vendeur (plateforme) plutôt que sur le client. Cela améliore l'expérience client en évitant les frais supplémentaires.

## 💰 Configuration des Frais

### Paramètre Principal
```php
'fee_payer' => 'seller' // Frais prélevés sur le vendeur
```

### Taux de Frais par Méthode de Paiement
| Méthode | Taux | Frais sur 50,000 XOF |
|---------|------|---------------------|
| **Wave** | 2.5% | 1,250 XOF |
| **Orange Money** | 2.5% | 1,250 XOF |
| **Free Money** | 2.0% | 1,000 XOF |
| **Bank Transfer** | 1.5% | 750 XOF |

## 🔄 Flux de Paiement avec Frais Vendeur

### 1. **Client Effectue le Paiement**
- Client paie le montant complet (ex: 50,000 XOF)
- Aucun frais supplémentaire pour le client

### 2. **NabooPay Prélève les Frais**
- NabooPay prélève automatiquement les frais sur le vendeur
- Montant reçu par le vendeur = Montant client - Frais

### 3. **Calcul Automatique des Frais**
```php
// Exemple pour un paiement Wave de 50,000 XOF
$totalAmount = 50000;        // Montant payé par le client
$feeRate = 0.025;           // 2.5% pour Wave
$feeAmount = 1250;          // Frais prélevés
$netAmount = 48750;         // Montant net reçu par le vendeur
```

## 📊 Nouveaux Champs de Base de Données

### Table `invoices`
| Champ | Type | Description |
|-------|------|-------------|
| `total_amount_paid` | decimal(10,2) | Montant total payé par le client |
| `fee_amount` | decimal(10,2) | Montant des frais de transaction |
| `net_amount_received` | decimal(10,2) | Montant net reçu par le vendeur |
| `fee_rate` | decimal(5,4) | Taux de frais appliqué |
| `payment_method_used` | string | Méthode de paiement utilisée |

## 🔧 Fonctionnalités Ajoutées

### 1. **Calcul Automatique des Frais**
- Calcul automatique lors du webhook de paiement
- Enregistrement des détails de frais dans la facture
- Logs détaillés pour le suivi

### 2. **Méthodes Utilitaires**
```php
// Obtenir le montant des frais formaté
$invoice->formatted_fee_amount; // "1,250 XOF"

// Obtenir le montant net formaté  
$invoice->formatted_net_amount; // "48,750 XOF"

// Obtenir le taux en pourcentage
$invoice->fee_rate_percentage; // "2.50%"

// Vérifier si les frais ont été calculés
$invoice->hasFeesCalculated(); // true/false
```

### 3. **Logs de Suivi**
```
Frais de transaction calculés: {
    "invoice_id": 123,
    "total_amount": 50000,
    "fee_amount": 1250,
    "net_amount": 48750,
    "fee_rate": 0.025,
    "payment_method": "wave"
}
```

## 🧪 Test du Système

### Test avec Webhook
```bash
curl -X POST http://localhost:8000/webhook/naboopay/test \
  -H "Content-Type: application/json" \
  -d '{
    "transaction_id": "test_fees_123",
    "status": "paid",
    "amount": 50000,
    "payment_method": "wave"
  }'
```

### Résultat Attendu
- **Montant total payé** : 50,000 XOF
- **Frais** : 1,250 XOF (2.5%)
- **Montant net reçu** : 48,750 XOF
- **Méthode utilisée** : wave

## 📈 Avantages du Système

### Pour les Clients
- ✅ **Aucun frais supplémentaire** visible
- ✅ **Prix transparent** et prévisible
- ✅ **Meilleure expérience** de paiement

### Pour la Plateforme
- ✅ **Contrôle des coûts** de transaction
- ✅ **Visibilité complète** sur les frais
- ✅ **Reporting détaillé** des coûts
- ✅ **Avantage concurrentiel** (pas de frais client)

## 📊 Reporting et Analytics

### Métriques Importantes
- **Total des frais payés** par période
- **Taux de frais moyen** par méthode de paiement
- **Montant net reçu** vs montant facturé
- **Répartition des méthodes** de paiement

### Requêtes Utiles
```sql
-- Total des frais payés ce mois
SELECT SUM(fee_amount) as total_fees 
FROM invoices 
WHERE status = 'payé' 
AND MONTH(paid_at) = MONTH(NOW());

-- Répartition par méthode de paiement
SELECT 
    payment_method_used,
    COUNT(*) as transactions,
    AVG(fee_rate) as avg_fee_rate,
    SUM(fee_amount) as total_fees
FROM invoices 
WHERE status = 'payé'
GROUP BY payment_method_used;
```

## 🔍 Dépannage

### Problème : Frais non calculés
**Cause** : Webhook non reçu ou erreur dans le calcul
**Solution** : Vérifier les logs et recalculer manuellement

### Problème : Taux de frais incorrect
**Cause** : Méthode de paiement non reconnue
**Solution** : Vérifier la méthode `getFeeRate()`

### Problème : Montant net incorrect
**Cause** : Erreur dans le calcul des frais
**Solution** : Vérifier la logique de calcul

## 🚀 Migration

### Exécuter la Migration
```bash
php artisan migrate
```

### Vérifier les Nouveaux Champs
```sql
DESCRIBE invoices;
-- Vérifier la présence des nouveaux champs
```

## 📝 Configuration NabooPay

### Paramètres à Configurer
1. **Fee Payer** : `seller`
2. **Webhook URL** : `https://votre-domaine.com/webhook/naboopay`
3. **Success URL** : `https://votre-domaine.com/payment/success/{id}`
4. **Error URL** : `https://votre-domaine.com/payment/error/{id}`

### Vérification
- ✅ Frais prélevés sur le vendeur
- ✅ Client paie le montant exact
- ✅ Calcul automatique des frais
- ✅ Logs de suivi complets

---

**Note** : Ce système garantit une expérience client optimale tout en maintenant une visibilité complète sur les coûts de transaction pour la plateforme.
