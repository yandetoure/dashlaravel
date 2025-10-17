# Système de Cashout NabooPay - Documentation Complète

## Vue d'ensemble

Le système de cashout permet aux administrateurs et agents de retirer des fonds de leur compte NabooPay vers des portefeuilles mobiles (Wave et Orange Money).

## Fonctionnalités

### 1. Récupération du Solde
- **Endpoint utilisé** : `/account/` (GET)
- **Authentification** : Bearer Token
- **Données retournées** :
  - `balance` : Solde disponible en XOF
  - `account_is_activate` : Statut d'activation du compte (boolean)
  - `method_of_payment` : Méthode de paiement configurée (ex: "WAVE")
  - `loyalty_credit` : Crédits de fidélité en XOF
  - `account_number` : Numéro de compte unique (UUID)

### 2. Cashout Wave
- **Endpoint** : `https://api.naboopay.com/api/v1/cashout/wave`
- **Méthode** : POST
- **Paramètres requis** :
  - `amount` : Montant en XOF (entier)
  - `phone_number` : Numéro au format +221XXXXXXXXX
  - `full_name` : Nom complet du destinataire

### 3. Cashout Orange Money
- **Endpoint** : `/cashout`
- **Méthode** : POST
- **Paramètres requis** :
  - `amount` : Montant en XOF (entier)
  - `phone_number` : Numéro de téléphone

## Accès par Rôle

### Administrateurs (`admin`, `super-admin`)
- Accès complet au système de cashout
- Interface : `/admin/cashout`
- Routes :
  - `GET /admin/cashout` - Page principale
  - `POST /admin/cashout/wave` - Cashout Wave
  - `POST /admin/cashout/orange-money` - Cashout Orange Money

### Agents (`agent`)
- Accès limité au système de cashout
- Interface : `/agent/cashout`
- Routes :
  - `GET /agent/cashout` - Page principale
  - `POST /agent/cashout/wave` - Cashout Wave
  - `POST /agent/cashout/orange-money` - Cashout Orange Money

## Normalisation des Numéros de Téléphone

Le système normalise automatiquement les numéros de téléphone sénégalais :

```php
public function normalizePhoneNumber(?string $phoneNumber): ?string
{
    if (empty($phoneNumber)) {
        return null;
    }

    // Supprime tous les espaces
    $cleanedPhoneNumber = str_replace(' ', '', $phoneNumber);

    // Vérifie si le numéro commence par "+221"
    if (!str_starts_with($cleanedPhoneNumber, '+221')) {
        // Ajoute "+221" et supprime l'éventuel '0' initial
        return '+221' . ltrim($cleanedPhoneNumber, '0');
    }

    return $cleanedPhoneNumber;
}
```

## Validation des Données

### Montant
- **Minimum** : 10 XOF
- **Maximum** : 500,000 XOF
- **Type** : Entier

### Numéro de Téléphone
- **Format accepté** : `[0-9+\-\s]+`
- **Normalisation automatique** vers le format `+221XXXXXXXXX`

### Nom Complet
- **Longueur maximale** : 255 caractères
- **Requis** pour les cashouts Wave

## Gestion des Erreurs

### Erreurs d'API NabooPay
- **403** : Non authentifié
- **404** : Endpoint non trouvé
- **422** : Données invalides
- **500** : Erreur serveur

### Erreurs de Validation
- Montant invalide
- Numéro de téléphone invalide
- Nom manquant

## Logging

Toutes les opérations sont loggées avec :
- Timestamp
- Utilisateur (ID et rôle)
- Montant
- Numéro de téléphone (normalisé)
- Résultat de l'opération
- Erreurs éventuelles

## Interface Utilisateur

### Page de Cashout
- Affichage du solde en temps réel
- Formulaire séparé pour Wave et Orange Money
- Validation côté client et serveur
- Messages de succès/erreur

### Navigation
- **Sidebar Admin** : "Gestion des Cashouts"
- **Sidebar Agent** : "Cashout"

## Sécurité

### Authentification
- Middleware `auth` requis
- Vérification des rôles (`admin`, `super-admin`, `agent`)

### Validation
- Validation stricte des données d'entrée
- Normalisation des numéros de téléphone
- Limites de montant

### Logging
- Traçabilité complète des opérations
- Logs d'erreur détaillés

## Structure des Données NabooPay

### Réponse de l'API `/account/`
```json
{
    "account_number": "97f6bdcd-9478-4f25-9e28-c8216228276b",
    "balance": 0,
    "account_is_activate": true,
    "method_of_payment": "WAVE",
    "loyalty_credit": 10
}
```

### Champs disponibles dans les vues
- `$accountInfo['balance']` : Solde principal en XOF
- `$accountInfo['account_is_activate']` : Statut d'activation (boolean)
- `$accountInfo['account_number']` : Identifiant unique du compte
- `$accountInfo['method_of_payment']` : Méthode de paiement configurée
- `$accountInfo['loyalty_credit']` : Crédits de fidélité en XOF

## Exemple d'Utilisation

### Récupération du Solde
```php
$nabooPayService = app(\App\Services\NabooPayService::class);
$accountInfo = $nabooPayService->getAccountInfo();

echo 'Solde: ' . $accountInfo['balance'] . ' XOF';
echo 'Compte activé: ' . ($accountInfo['account_is_activate'] ? 'Oui' : 'Non');
echo 'Crédits de fidélité: ' . $accountInfo['loyalty_credit'] . ' XOF';
```

### Cashout Wave
```php
$result = $nabooPayService->waveCashout([
    'amount' => 10000,
    'phone_number' => '77 123 45 67',
    'full_name' => 'John Doe'
]);

if ($result['success']) {
    echo 'Cashout réussi!';
} else {
    echo 'Erreur: ' . $result['error'];
}
```

## Statuts de Cashout

### Wave
- `pending` : En attente
- `paid` : Payé
- `done` : Terminé

### Orange Money
- Statuts définis par l'API Orange Money

## Maintenance

### Vérification du Solde
- Le solde est récupéré en temps réel à chaque accès
- Pas de cache pour garantir l'exactitude

### Gestion des Erreurs
- Retry automatique sur erreurs temporaires
- Fallback vers calcul du solde à partir des transactions

## Support

En cas de problème :
1. Vérifier les logs Laravel
2. Contacter le support NabooPay
3. Vérifier la configuration API

---

**Dernière mise à jour** : $(date)
**Version** : 1.0.0
**Statut** : ✅ Fonctionnel
