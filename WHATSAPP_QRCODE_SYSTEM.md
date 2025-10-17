# Système de QR Code et WhatsApp pour les Factures

## 🎯 Vue d'ensemble

Le système permet maintenant d'envoyer des factures par WhatsApp et de générer des QR codes pour faciliter le paiement des clients. Cette fonctionnalité améliore l'expérience client en offrant des moyens de paiement modernes et pratiques.

## 🚀 Fonctionnalités Disponibles

### 1. **Envoi par WhatsApp**
- Génération automatique d'un message WhatsApp formaté
- Redirection vers WhatsApp Web avec le message pré-rempli
- Message incluant toutes les informations de la facture

### 2. **Génération de QR Code**
- QR code contenant le lien de paiement direct
- Interface moderne et responsive pour l'affichage
- Instructions claires pour le client

### 3. **Boutons d'Action**
- Disponibles dans la liste des factures
- Disponibles dans la vue détail des factures
- Accessibles selon les permissions utilisateur

## 📱 Fonctionnalité WhatsApp

### Message Généré Automatiquement
```
🚗 *FACTURE DE TRANSPORT*

📋 *Numéro de facture:* INV-123-456
👤 *Client:* Jean Dupont
📱 *Téléphone:* 772319878
📍 *Trajet:* Dakar → AIBD
📅 *Date:* 17/10/2025
🕐 *Heure de ramassage:* 08:00
👥 *Personnes:* 2
🧳 *Valises:* 1

💰 *Montant à payer:* 50,000 XOF

💳 *Méthodes de paiement acceptées:*
• Wave
• Orange Money
• Free Money
• Virement bancaire

🔗 *Lien de paiement:* https://votre-site.com/payment/123

Merci pour votre confiance ! 🙏
```

### Utilisation
1. **Cliquer** sur le bouton WhatsApp
2. **Redirection** automatique vers WhatsApp Web
3. **Message pré-rempli** avec toutes les informations
4. **Envoyer** au client

## 🔲 Fonctionnalité QR Code

### Interface QR Code
- **QR Code visuel** : 300x300 pixels avec bordure verte
- **Informations facture** : Numéro, montant, trajet
- **Lien de paiement** : URL directe vers NabooPay
- **Instructions** : Guide étape par étape
- **Méthodes de paiement** : Badges colorés

### Utilisation
1. **Cliquer** sur le bouton QR Code
2. **Page dédiée** s'ouvre avec le QR code
3. **Client scanne** avec son téléphone
4. **Redirection** automatique vers le paiement

## 🔧 Implémentation Technique

### Routes Ajoutées
```php
// Envoi WhatsApp
Route::get('/invoices/{invoice}/whatsapp', [InvoiceController::class, 'sendWhatsAppPayment'])->name('invoices.whatsapp');

// Génération QR Code
Route::get('/invoices/{invoice}/qrcode', [InvoiceController::class, 'generateQRCode'])->name('invoices.qrcode');
```

### Méthodes du Contrôleur
```php
// Génération du message WhatsApp
private function generateWhatsAppMessage(Invoice $invoice): string

// Génération de l'URL WhatsApp
private function generateWhatsAppUrl(string $message): string

// Génération et affichage du QR Code
public function generateQRCode(Invoice $invoice)

// Envoi par WhatsApp
public function sendWhatsAppPayment(Invoice $invoice)
```

### Bibliothèque QR Code
```bash
composer require simplesoftwareio/simple-qrcode
```

## 🎨 Interface Utilisateur

### Boutons dans la Liste des Factures
- **Payer** : Bouton vert avec icône carte de crédit
- **QR Code** : Bouton bleu avec icône QR code
- **WhatsApp** : Bouton vert avec icône WhatsApp

### Boutons dans la Vue Détaillée
- **Télécharger PDF** : Bouton bleu
- **QR Code** : Bouton info
- **WhatsApp** : Bouton vert
- **Marquer payée** : Bouton vert (admin seulement)

### Page QR Code
- **Design moderne** : Gradient de fond, cartes blanches
- **Responsive** : Adapté mobile et desktop
- **Informations complètes** : Facture, montant, trajet
- **Instructions claires** : Guide d'utilisation

## 🔐 Sécurité et Permissions

### Vérifications de Sécurité
- **Permissions utilisateur** : Client ne peut voir que ses factures
- **Permissions chauffeur** : Accès aux factures de ses réservations
- **Permissions admin** : Accès à toutes les factures
- **Statut facture** : Pas d'envoi si déjà payée

### Contrôles d'Accès
```php
// Vérification client
if ($user->hasRole('client') && $invoice->reservation->client_id != $user->id) {
    abort(403, 'Vous n\'êtes pas autorisé...');
}

// Vérification chauffeur
if ($user->hasRole('chauffeur')) {
    $carDriverIds = $user->car_drivers->pluck('id');
    if (!$carDriverIds->contains($invoice->reservation->cardriver_id)) {
        abort(403, 'Vous n\'êtes pas autorisé...');
    }
}
```

## 🧪 Test de la Fonctionnalité

### Test WhatsApp
1. **Créer une facture** de test
2. **Cliquer** sur le bouton WhatsApp
3. **Vérifier** la redirection vers WhatsApp Web
4. **Vérifier** le message pré-rempli

### Test QR Code
1. **Créer une facture** de test
2. **Cliquer** sur le bouton QR Code
3. **Vérifier** l'affichage de la page QR code
4. **Scanner** le QR code avec un téléphone
5. **Vérifier** la redirection vers le paiement

## 📊 Avantages

### Pour les Clients
- ✅ **Paiement facile** : QR code ou WhatsApp
- ✅ **Informations complètes** : Tous les détails dans le message
- ✅ **Méthodes multiples** : QR code, lien direct, WhatsApp
- ✅ **Interface moderne** : Design attrayant et responsive

### Pour la Plateforme
- ✅ **Réduction des appels** : Clients autonomes
- ✅ **Paiements plus rapides** : Accès direct au paiement
- ✅ **Communication moderne** : WhatsApp intégré
- ✅ **Suivi facilité** : Messages formatés et professionnels

### Pour les Admins/Chauffeurs
- ✅ **Envoi facile** : Un clic pour envoyer par WhatsApp
- ✅ **QR code pratique** : Pour affichage physique
- ✅ **Gestion simplifiée** : Boutons d'action intuitifs
- ✅ **Suivi des paiements** : Liens directs vers NabooPay

## 🔄 Flux d'Utilisation

### Scénario 1: Envoi WhatsApp
1. **Admin/Chauffeur** voit une facture en attente
2. **Clique** sur le bouton WhatsApp
3. **WhatsApp Web** s'ouvre avec le message
4. **Envoie** au client
5. **Client** clique sur le lien de paiement
6. **Paiement** via NabooPay

### Scénario 2: QR Code
1. **Admin/Chauffeur** génère le QR code
2. **Affiche** le QR code (écran, impression)
3. **Client** scanne avec son téléphone
4. **Redirection** automatique vers le paiement
5. **Paiement** via NabooPay

## 📝 Notes Importantes

- **Statut facture** : Fonctionnalités disponibles uniquement pour les factures non payées
- **Permissions** : Chaque utilisateur ne peut accéder qu'à ses factures autorisées
- **Responsive** : Interface adaptée mobile et desktop
- **Sécurité** : Vérifications de permissions à chaque accès

---

**Status** : ✅ **FONCTIONNEL** - Système de QR code et WhatsApp opérationnel pour toutes les factures.
