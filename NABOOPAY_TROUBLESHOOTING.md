# Guide de Dépannage NabooPay - Erreur 400 Bad Request

## 🔍 Diagnostic de l'Erreur

L'erreur `PUT https://backend.naboopay.com/api/v1/payments/wave 400 (Bad Request)` se produit dans l'interface de paiement NabooPay, pas dans notre code.

## ✅ Ce qui fonctionne :
- ✅ Création de transaction réussie
- ✅ URL de checkout générée : `https://checkout.naboopay.com/checkout/[id]`
- ✅ Redirection vers NabooPay
- ✅ Méthode POST correcte

## ❌ Ce qui pose problème :
- ❌ Erreur lors du paiement Wave dans l'interface NabooPay
- ❌ Erreur 400 Bad Request côté NabooPay

## 🔧 Solutions à tester :

### 1. Vérifier les URLs de redirection
Les URLs locales peuvent causer des problèmes. Nous utilisons maintenant :
- `https://horizonexquis.com/payment/success/[id]`
- `https://horizonexquis.com/payment/error/[id]`

### 2. Tester avec Orange Money
Essayez de payer avec Orange Money au lieu de Wave pour voir si le problème est spécifique à Wave.

### 3. Vérifier le format des données
Les données envoyées sont maintenant :
```json
{
  "method_of_payment": ["WAVE", "ORANGE_MONEY"],
  "products": [{
    "name": "Trajet Dakar - AIBD",
    "category": "Transport",
    "amount": 32500,
    "quantity": 1,
    "description": "Réservation de transport - Dakar vers AIBD (2 personne(s))"
  }],
  "success_url": "https://horizonexquis.com/payment/success/2",
  "error_url": "https://horizonexquis.com/payment/error/2",
  "is_escrow": true,
  "webhook_url": "https://horizonexquis.com/webhook/naboopay",
  "customer_info": {
    "name": "Ndeye Yandé Touré",
    "email": "tourendeyeyande@gmail.com",
    "phone": "772319878"
  },
  "metadata": {
    "reservation_id": 2,
    "trip_id": 1,
    "passengers": 2
  }
}
```

### 4. Tester avec un montant différent
Essayez avec un montant plus petit (ex: 1000 XOF) pour voir si c'est un problème de limite.

### 5. Vérifier les logs NabooPay
Consultez votre dashboard NabooPay pour voir les erreurs détaillées.

## 🚀 Prochaines étapes :

1. **Testez avec Orange Money** au lieu de Wave
2. **Vérifiez votre dashboard NabooPay** pour les erreurs détaillées
3. **Contactez le support NabooPay** avec l'ID de transaction
4. **Testez avec un montant plus petit**

## 📞 Support NabooPay :
- Documentation : https://docs.naboopay.com/docs/naboopay-api/transaction/
- Dashboard : https://dashboard.naboopay.com
- Support : support@naboopay.com

## 🔍 Informations de debug :
- Transaction ID : Voir dans les logs Laravel
- URL de checkout : `https://checkout.naboopay.com/checkout/[id]`
- Montant : 32 500 XOF
- Méthodes : Wave, Orange Money
