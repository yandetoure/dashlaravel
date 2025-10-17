# Système QR Code pour Paiement - Guide Complet

## 🎯 Objectif

Permettre aux clients de scanner un QR code pour accéder directement à la page de checkout NabooPay et effectuer le paiement de leur facture.

## 🔄 Flux de Paiement QR Code

### 1. **Génération du QR Code**
```
Client → Facture → Bouton "QR Code" → Génération SVG → Affichage
```

### 2. **Scan du QR Code**
```
Scan QR Code → URL de paiement → Vérification permissions → NabooPay Checkout
```

### 3. **Paiement NabooPay**
```
NabooPay → Paiement → Webhook → Mise à jour statuts → Confirmation
```

## 🛠️ Implémentation Technique

### **InvoiceController.php**

#### Méthode `generateQRCode()`
```php
public function generateQRCode(Invoice $invoice)
{
    // Vérifications de sécurité
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    
    if ($invoice->status === 'payé') {
        return redirect()->back()->with('error', 'Cette facture est déjà payée.');
    }

    // Génération de l'URL de paiement
    $paymentUrl = $this->getPaymentUrl($invoice->reservation->id);
    
    // Génération du QR code SVG
    $qrCodeSvg = QrCode::format('svg')
        ->size(300)
        ->margin(2)
        ->generate($paymentUrl);

    return view('invoices.qrcode', compact('invoice', 'qrCodeSvg', 'paymentUrl'));
}
```

#### Méthode Helper `getPaymentUrl()`
```php
private function getPaymentUrl($reservationId)
{
    // Utiliser l'URL de base configurée ou détecter automatiquement
    $baseUrl = config('app.url');
    
    // Si on est en local, utiliser l'URL de la requête actuelle
    if (str_contains($baseUrl, 'localhost') || str_contains($baseUrl, '127.0.0.1')) {
        $baseUrl = request()->getSchemeAndHttpHost();
    }
    
    return $baseUrl . route('reservations.pay.direct', $reservationId, false);
}
```

### **PaymentController.php**

#### Méthode `payDirect()`
```php
public function payDirect(Reservation $reservation)
{
    // Vérification des permissions
    if (!auth()->check()) {
        return redirect()->route('login')->with('error', 'Vous devez être connecté pour effectuer un paiement.');
    }

    // Vérification des droits de paiement
    $user = auth()->user();
    $canPay = $this->checkPaymentPermissions($user, $reservation);

    if (!$canPay) {
        abort(403, 'Vous n\'êtes pas autorisé à payer cette réservation.');
    }

    try {
        // Création de la transaction NabooPay
        $result = $this->nabooPayService->createReservationTransaction($reservation);

        if (!$result['success']) {
            return back()->withErrors(['error' => $result['error']]);
        }

        $checkoutUrl = $result['checkout_url'] ?? null;
        $transactionId = $result['transaction_id'] ?? null;

        if ($checkoutUrl) {
            // Mise à jour de la facture
            $this->updateInvoiceForPayment($reservation, $transactionId, $checkoutUrl);
            
            // Redirection vers NabooPay
            return redirect($checkoutUrl);
        }
    } catch (\Exception $e) {
        Log::error('Erreur paiement direct: ' . $e->getMessage());
        return back()->withErrors(['error' => 'Une erreur est survenue lors du traitement du paiement.']);
    }
}
```

## 📱 Interface Utilisateur

### **Boutons QR Code**

#### Dans `invoices/index.blade.php`
```html
@if($invoice->status !== 'payé')
    <a href="{{ route('invoices.qrcode', $invoice) }}" 
       class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
        <i class="fas fa-qrcode mr-1"></i> QR Code
    </a>
@endif
```

#### Dans `invoices/show.blade.php`
```html
@if($invoice->status !== 'payé')
    <div class="flex space-x-2">
        <a href="{{ route('invoices.qrcode', $invoice) }}" 
           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
            <i class="fas fa-qrcode mr-2"></i> Générer QR Code
        </a>
    </div>
@endif
```

### **Page QR Code (`invoices/qrcode.blade.php`)**
```html
<div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-6">
    <h2 class="text-2xl font-bold text-center mb-6">QR Code de Paiement</h2>
    
    <!-- Informations de la facture -->
    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
        <h3 class="font-semibold text-lg mb-2">Facture #{{ $invoice->invoice_number }}</h3>
        <p class="text-gray-600">Montant: <span class="font-bold">{{ $invoice->formatted_amount }}</span></p>
        <p class="text-gray-600">Client: {{ $invoice->reservation->client->first_name }} {{ $invoice->reservation->client->last_name }}</p>
    </div>
    
    <!-- QR Code -->
    <div class="text-center mb-6">
        <div class="inline-block p-4 bg-white border-2 border-gray-200 rounded-lg">
            {!! $qrCodeSvg !!}
        </div>
        <p class="text-sm text-gray-500 mt-2">Scannez ce code pour payer</p>
    </div>
    
    <!-- URL de paiement -->
    <div class="text-center">
        <p class="text-xs text-gray-400 mb-2">Ou cliquez sur le lien :</p>
        <a href="{{ $paymentUrl }}" 
           class="text-blue-500 hover:text-blue-600 text-sm break-all">
            {{ $paymentUrl }}
        </a>
    </div>
</div>
```

## 🔗 Routes Configurées

### **Routes Principales**
```php
// Génération du QR code
Route::get('/invoices/{invoice}/qrcode', [InvoiceController::class, 'generateQRCode'])
    ->name('invoices.qrcode');

// Paiement direct (cible du QR code)
Route::get('/reservations/{reservation}/pay-direct', [PaymentController::class, 'payDirect'])
    ->name('reservations.pay.direct');
```

### **Route de Test (Développement)**
```php
// Route de test pour le QR code (développement uniquement)
if (app()->environment('local')) {
    Route::get('/test-qrcode/{invoice}', function(App\Models\Invoice $invoice) {
        return app(App\Http\Controllers\InvoiceController::class)->generateQRCode($invoice);
    })->name('test.qrcode');
}
```

## 🧪 Tests et Validation

### **Test de Génération QR Code**
```bash
php artisan tinker --execute="
use SimpleSoftwareIO\QrCode\Facades\QrCode;

\$invoice = App\Models\Invoice::first();
if (\$invoice) {
    \$baseUrl = 'http://127.0.0.1:8002';
    \$paymentUrl = \$baseUrl . route('reservations.pay.direct', \$invoice->reservation_id, false);
    
    echo 'URL de paiement: ' . \$paymentUrl . PHP_EOL;
    
    \$qrCodeSvg = QrCode::format('svg')
        ->size(300)
        ->margin(2)
        ->generate(\$paymentUrl);
    
    echo 'QR Code généré avec succès!' . PHP_EOL;
    echo 'Taille du SVG: ' . strlen(\$qrCodeSvg) . ' caractères' . PHP_EOL;
}
"
```

### **Test d'URL de Paiement**
```bash
php artisan tinker --execute="
\$invoice = App\Models\Invoice::first();
if (\$invoice) {
    echo 'URL de test QR code: ' . url('/test-qrcode/' . \$invoice->id) . PHP_EOL;
    echo 'URL de paiement direct: ' . url(route('reservations.pay.direct', \$invoice->reservation_id)) . PHP_EOL;
}
"
```

## 🔒 Sécurité et Permissions

### **Vérifications de Sécurité**

#### 1. **Authentification Requise**
```php
if (!auth()->check()) {
    return redirect()->route('login');
}
```

#### 2. **Permissions de Paiement**
```php
$canPay = false;

if ($user->hasRole('admin') || $user->hasRole('super-admin')) {
    $canPay = true;
} elseif ($user->hasRole('client') && $reservation->client_id === $user->id) {
    $canPay = true;
} elseif ($user->hasRole('chauffeur')) {
    $carDrivers = $user->car_drivers->pluck('id');
    if ($carDrivers->contains($reservation->cardriver_id)) {
        $canPay = true;
    }
}
```

#### 3. **Statut de Facture**
```php
if ($invoice->status === 'payé') {
    return redirect()->back()->with('error', 'Cette facture est déjà payée.');
}
```

## 🌐 Gestion des URLs

### **URL Dynamique selon l'Environnement**

#### Développement Local
```php
// URL détectée automatiquement
$baseUrl = request()->getSchemeAndHttpHost(); // http://127.0.0.1:8002
```

#### Production
```php
// URL configurée dans .env
$baseUrl = config('app.url'); // https://votre-domaine.com
```

### **Exemple d'URLs Générées**

#### Développement
```
http://127.0.0.1:8002/reservations/1/pay-direct
```

#### Production
```
https://votre-domaine.com/reservations/1/pay-direct
```

## 📊 Intégration NabooPay

### **Configuration Transaction**
```php
$data = [
    'amount' => (int) $amount,
    'currency' => 'XOF',
    'description' => 'Prix de livraison - ' . $trip->departure . ' vers ' . $trip->destination,
    'is_escrow' => false, // Pas d'escrow - paiement direct
    'fee_payer' => 'seller', // Frais prélevés sur le vendeur
    'webhook_url' => $baseUrl . '/webhook/naboopay',
    'success_url' => $baseUrl . '/payment/success/' . $reservation->id,
    'error_url' => $baseUrl . '/payment/error/' . $reservation->id,
];
```

### **Flux de Paiement NabooPay**
1. **Scan QR Code** → URL de paiement
2. **Clic sur URL** → Vérification permissions
3. **Création transaction** → NabooPay API
4. **Redirection** → Checkout NabooPay
5. **Paiement** → Webhook notification
6. **Mise à jour** → Statuts facture/réservation

## 🚀 Utilisation

### **Pour les Clients**
1. Accéder à la liste des factures
2. Cliquer sur "QR Code" pour une facture impayée
3. Scanner le QR code avec leur téléphone
4. Être redirigé vers NabooPay
5. Effectuer le paiement
6. Recevoir confirmation

### **Pour les Administrateurs**
1. Accéder aux factures clients
2. Générer QR codes pour faciliter les paiements
3. Envoyer QR codes par WhatsApp
4. Suivre les paiements via webhooks

## 🔧 Dépannage

### **Problèmes Courants**

#### QR Code ne s'affiche pas
- Vérifier que `simplesoftwareio/simple-qrcode` est installé
- Vérifier les permissions de la facture
- Vérifier que la facture n'est pas déjà payée

#### URL incorrecte dans le QR code
- Vérifier la configuration `APP_URL` dans `.env`
- Vérifier que `getPaymentUrl()` détecte correctement l'environnement

#### Erreur de permissions
- Vérifier que l'utilisateur est connecté
- Vérifier les rôles et permissions
- Vérifier que la réservation appartient à l'utilisateur

### **Logs de Debug**
```php
Log::info('QR Code généré', [
    'invoice_id' => $invoice->id,
    'payment_url' => $paymentUrl,
    'user_id' => auth()->id()
]);
```

## 📈 Avantages du Système

### **Pour les Clients**
- ✅ Paiement rapide et facile
- ✅ Pas besoin de saisir manuellement l'URL
- ✅ Compatible avec tous les smartphones
- ✅ Intégration WhatsApp possible

### **Pour l'Administration**
- ✅ Réduction des erreurs de saisie
- ✅ Amélioration de l'expérience utilisateur
- ✅ Suivi automatique des paiements
- ✅ Intégration complète avec NabooPay

---

**Status** : ✅ **OPÉRATIONNEL** - Le système QR code permet maintenant aux clients de scanner et payer directement via NabooPay.
