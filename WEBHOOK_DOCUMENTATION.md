# Documentation Webhook NabooPay - Système de Réservations

## 🎯 Vue d'ensemble

Le système de webhook NabooPay permet de recevoir automatiquement les notifications de statut de paiement et de mettre à jour les réservations et factures en conséquence.

## 🔄 Flux de Paiement avec Webhook

1. **Client/Chauffeur** initie un paiement via l'interface
2. **Redirection** vers NabooPay pour le paiement
3. **Paiement** effectué sur NabooPay
4. **Webhook** reçoit la notification de statut
5. **Mise à jour automatique** de la facture et de la réservation

## 📡 Configuration du Webhook

### URL du Webhook
```
POST /webhook/naboopay
```

### Configuration NabooPay
Dans votre tableau de bord NabooPay, configurez l'URL du webhook :
```
https://votre-domaine.com/webhook/naboopay
```

## 📊 Statuts de Paiement Gérés

### Statuts NabooPay → Statuts Facture
| Statut NabooPay | Statut Facture | Action |
|----------------|----------------|--------|
| `paid` | `payé` | ✅ Paiement réussi |
| `done` | `payé` | ✅ Paiement terminé |
| `completed` | `payé` | ✅ Paiement complété |
| `success` | `payé` | ✅ Paiement réussi |
| `failed` | `en_attente` | ❌ Échec - reste en attente |
| `cancelled` | `en_attente` | ❌ Annulé - reste en attente |
| `expired` | `en_attente` | ❌ Expiré - reste en attente |
| `pending` | `en_attente` | ⏳ En cours |
| `processing` | `en_attente` | ⏳ En traitement |

### Statuts Réservation
| Statut Actuel | Statut Après Paiement | Condition |
|---------------|----------------------|-----------|
| `Confirmée` | `Payée` | ✅ Paiement réussi |
| `En_attente` | Inchangé | ⚠️ Réservation non confirmée |
| `Annulée` | Inchangé | ⚠️ Réservation annulée |
| `Payée` | Inchangé | ✅ Déjà payée |

## 🔧 Structure des Données Webhook

### Données Reçues
```json
{
    "transaction_id": "txn_123456789",
    "status": "paid",
    "amount": 50000,
    "currency": "XOF",
    "timestamp": "2025-01-17T10:30:00Z"
}
```

### Données Stockées
```json
{
    "transaction_data": {
        "transaction_id": "txn_123456789",
        "status": "paid",
        "amount": 50000,
        "currency": "XOF",
        "timestamp": "2025-01-17T10:30:00Z"
    }
}
```

## 🧪 Test du Webhook

### Route de Test (Développement uniquement)
```
POST /webhook/naboopay/test
```

### Paramètres de Test
```json
{
    "transaction_id": "test_txn_123",
    "status": "paid",
    "amount": 50000,
    "currency": "XOF"
}
```

### Exemple avec cURL
```bash
curl -X POST http://localhost:8000/webhook/naboopay/test \
  -H "Content-Type: application/json" \
  -d '{
    "transaction_id": "test_txn_123",
    "status": "paid"
  }'
```

## 📝 Logs et Monitoring

### Logs Générés
- **Webhook reçu** : `Webhook NabooPay reçu: {...}`
- **Statut de paiement** : `Statut de paiement NabooPay: paid`
- **Facture mise à jour** : `Facture mise à jour`
- **Réservation mise à jour** : `Réservation mise à jour en Payée`

### Localisation des Logs
```
storage/logs/laravel.log
```

### Recherche dans les Logs
```bash
# Rechercher les webhooks
grep "Webhook NabooPay" storage/logs/laravel.log

# Rechercher les erreurs
grep "Erreur webhook" storage/logs/laravel.log
```

## 🛡️ Sécurité

### Validation des Données
- ✅ Vérification de la présence du `transaction_id`
- ✅ Validation du statut de paiement
- ✅ Vérification de l'existence de la facture
- ✅ Gestion des erreurs avec logs détaillés

### Protection CSRF
- ❌ Pas de protection CSRF (webhook externe)
- ✅ Validation des données d'entrée
- ✅ Logs de sécurité pour monitoring

## 🔍 Dépannage

### Problèmes Courants

#### 1. Webhook non reçu
**Symptômes** : Paiement effectué mais statut non mis à jour
**Solutions** :
- Vérifier l'URL du webhook dans NabooPay
- Vérifier les logs pour les erreurs
- Tester avec la route de test

#### 2. Facture non trouvée
**Symptômes** : `Facture non trouvée pour la transaction`
**Solutions** :
- Vérifier que le `transaction_id` correspond
- Vérifier que la facture existe en base
- Vérifier les logs de création de facture

#### 3. Réservation non mise à jour
**Symptômes** : Facture payée mais réservation reste "Confirmée"
**Solutions** :
- Vérifier que la réservation est bien "Confirmée"
- Vérifier les logs de mise à jour
- Vérifier les contraintes de base de données

### Commandes de Diagnostic
```bash
# Vérifier les factures récentes
php artisan tinker
>>> App\Models\Invoice::latest()->take(5)->get(['id', 'transaction_id', 'status', 'created_at']);

# Vérifier les réservations récentes
>>> App\Models\Reservation::latest()->take(5)->get(['id', 'status', 'created_at']);

# Rechercher une transaction spécifique
>>> App\Models\Invoice::where('transaction_id', 'txn_123456789')->first();
```

## 📈 Monitoring et Métriques

### Métriques Importantes
- **Taux de succès des webhooks** : % de webhooks traités avec succès
- **Temps de traitement** : Latence entre réception et traitement
- **Erreurs par type** : Classification des erreurs
- **Paiements par statut** : Distribution des statuts de paiement

### Alertes Recommandées
- ⚠️ Webhook non reçu dans les 5 minutes après paiement
- ❌ Erreur de traitement webhook
- 🔍 Facture non trouvée pour transaction
- 📊 Taux d'erreur > 5%

## 🚀 Améliorations Futures

### Fonctionnalités Possibles
- [ ] Signature de webhook pour sécurité renforcée
- [ ] Retry automatique en cas d'échec
- [ ] Interface de monitoring des webhooks
- [ ] Notifications par email/SMS en cas d'erreur
- [ ] Statistiques en temps réel
- [ ] Webhook pour autres statuts (annulation, remboursement)

### Optimisations
- [ ] Cache des transactions NabooPay
- [ ] Traitement asynchrone des webhooks
- [ ] Compression des logs
- [ ] Archivage automatique des anciens logs

## 📞 Support

En cas de problème avec le système de webhook :
1. Consulter les logs dans `storage/logs/laravel.log`
2. Utiliser la route de test pour diagnostiquer
3. Vérifier la configuration NabooPay
4. Contacter l'équipe technique avec les logs d'erreur

---

**Dernière mise à jour** : 17 Janvier 2025  
**Version** : 1.0  
**Auteur** : Équipe Technique Cpro-Reservations
