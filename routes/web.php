<?php declare(strict_types=1);

use App\Models\Actu;
use App\Models\Info;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ActuController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\InvoiceController;
// use Google_Client;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrafficController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CardriverController;
use App\Http\Controllers\DriverGroupController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\ReservationController;
use Spatie\Permission\Middlewares\RoleMiddleware;
use App\Http\Controllers\ClientDashboardController;


Route::get('/', function () {
    $trips = Trip::all();
    $actus = Actu::with('category')->orderBy('created_at', 'desc')->get(); // Récupère toutes les actualités avec catégories, triées par date
    $infos = Info::with('category')->orderBy('created_at', 'desc')->take(4)->get();

    $chauffeurs = User::whereHas('roles', function ($query) {
        $query->where('name', 'chauffeur');
    })->get();

    $clients = User::whereHas('roles', function ($query) {
        $query->where('name', 'client');
    })->get();

    return view('welcome', compact('trips', 'actus', 'infos'));
});



// Route pour afficher tous les utilisateurs avec filtre

Route::middleware('auth:')->group(function () {
    Route::get('/admin/create-account', [AuthController::class, 'createAccountPage'])
        ->name('admin.create.account.page');

    Route::post('/admin/create-account', [AuthController::class, 'createAccount'])
        ->name('admin.create.account');

    Route::get('/clients', [AuthController::class, 'lisclient'])->name('clients.index');
    Route::get('/drivers', [AuthController::class, 'listdriver'])->name('drivers.index');
    Route::get('/agents', [AuthController::class, 'listagent'])->name('agents.index');
    Route::get('/admin', [AuthController::class, 'listadmin'])->name('admins.index');
    Route::get('/superaddmin', [AuthController::class, 'listAllUsers'])->name('superadmins.index');

    Route::Resource('cars', CarController::class);
    Route::Resource('auth', AuthController::class);
    Route::resource('trips', TripController::class);

    // Route::get('/users', [AuthController::class, 'listAllUsers'])->name('users.all');

    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::post('/invoices/{id}/mark-as-paid', [InvoiceController::class, 'markAsPaid'])->name('invoices.markAsPaid');
    Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'downloadPdf'])->name('invoices.download');

    // Route de suppression d'utilisateur
    Route::delete('/users/{id}', [AuthController::class, 'destroy'])->name('users.destroy');


});

Route::resource('actus', ActuController::class);
Route::resource('infos', App\Http\Controllers\InfoController::class);

Route::get('/reservations/confirmed', [ReservationController::class, 'confirmed'])->name('reservations.confirmed');

Route::get('/reservations/cancelled', [ReservationController::class, 'cancelled'])->name('reservations.cancelled');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Routes pour les dashboards avec contrôleur
    Route::get('/admin/dashboard', [DashController::class, 'adminIndex'])->name('dashboard.admin');
    Route::get('/client/dashboard', [DashController::class, 'clientIndex'])->name('dashboard.client');
    Route::get('/chauffeur/dashboard', [DashController::class, 'chauffeurIndex'])->name('dashboard.chauffeur');
    Route::get('/entreprise/dashboard', [DashController::class, 'entrepriseIndex'])->name('dashboard.entreprise');
    Route::get('/agent/dashboard', [DashController::class, 'agentIndex'])->name('dashboard.agent');
    Route::get('/superadmin/dashboard', [DashController::class, 'superadminIndex'])->name('dashboard.superadmin');

    Route::resource('maintenances', MaintenanceController::class);
    Route::resource('cars', CarController::class);
});

Route::get('/reservations/confirmees', [ReservationController::class, 'confirmedReservations'])->name('reservations.confirmed');

// Affichage des formulaires d'inscription
Route::get('/register/agent', [AuthController::class, 'createAgent'])->name('register.agent.form');
Route::get('/register/driver', [AuthController::class, 'createDriver'])->name('register.driver.form');
Route::get('/register/admin', [AuthController::class, 'createAdmin'])->name('register.admin.form');
Route::get('/register/client', [AuthController::class, 'createClient'])->name('register.client.form');

// Soumission des formulaires d'inscription
Route::post('/register/agent', [AuthController::class, 'storeAgent'])->name('register.agent');
Route::post('/register/driver', [AuthController::class, 'storeDriver'])->name('register.driver');
Route::post('/register/admin', [AuthController::class, 'storeAdmin'])->name('register.admin');
Route::post('/register/client', [AuthController::class, 'storeClient'])->name('register.client');


Route::middleware(['auth'])->group(function () {
    Route::get('/car_drivers', [CardriverController::class, 'index'])->name('cardrivers.index');
    Route::get('/car_drivers/create', [CardriverController::class, 'create'])->name('cardrivers.create');
    Route::post('/car_drivers', [CardriverController::class, 'store'])->name('cardrivers.store');
    Route::delete('/car_drivers/{car_id}/{user_id}', [CardriverController::class, 'destroy'])->name('car_drivers.destroy');
});
Route::post('/reservations/check-availability', [ReservationController::class, 'checkAvailability'])->name('reservations.checkAvailability');

// Route pour les réservations des prospects (accessible sans authentification)
Route::post('/reservations/new-reservation', [ReservationController::class, 'storeByProspect'])->name('reservations.storeByProspect');

Route::prefix('reservations')->name('reservations.')->middleware('auth')->group(function () {
    // Affichage de toutes les réservations
    Route::get('/', [ReservationController::class, 'index'])->name('index');

    // Formulaire pour créer une réservation
    Route::get('create', [ReservationController::class, 'create'])->name('create');

    Route::get('client-create', [ReservationController::class, 'clientcreate'])->name('clientcreate');

    // Enregistrement d'une nouvelle réservation
    Route::post('store', [ReservationController::class, 'store'])->name('store');

    // Confirmation d'une réservation
    Route::post('{reservation}/confirm', [ReservationController::class, 'confirm'])->name('confirm');

    // Annulation d'une réservation
    Route::post('{reservation}/cancel', [ReservationController::class, 'cancel'])->name('cancel');

    // Suppression d'une réservation
    Route::delete('{reservation}', [ReservationController::class, 'destroy'])->name('destroy');

    Route::post('store-agent', [ReservationController::class, 'storeByAgent'])->name('storeByAgent');

    Route::put('/{id}', [ReservationController::class, 'update'])->name('reservations.update');

    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
});

Route::post('/reservations/{reservation}/avis', [ReservationController::class, 'storeAvis'])->name('reservations.avis');

Route::middleware(['auth'])->group(function () {
    // Route::get('reservations/calendar', [ReservationController::class, 'calendar'])->name('reservations.calendar');
    // Route::get('/calendar', [ReservationController::class, 'showCalendar'])->name('Calendar');
    Route::get('/reservations/calendar', [ReservationController::class, 'showCalendar'])->name('reservations.showCalendar');
});

Route::get('/reservations/agent/create', [ReservationController::class, 'agentCreateReservation'])->name('reservations.agent.create.reservation');
Route::post('/reservations/agent/store', [ReservationController::class, 'agentStoreReservation'])->name('reservations.agent.store');


Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');

Route::put('/reservations/{id}', [ReservationController::class, 'update'])->name('reservations.update');

Route::middleware(['auth'])->group(function () {
    // Routes réservées aux Chauffeurs
    Route::get('/chauffeur/reservations', [ReservationController::class, 'chauffeurReservations'])->name('chauffeur.reservations')->middleware('role:chauffeur');

    // Routes réservées aux Clients
    Route::get('/client/reservations', [ReservationController::class, 'clientReservations'])->name('client.reservations')->middleware('role:client');

    // Routes réservées aux Admin et Super Admin
    Route::get('/admin/reservations', [ReservationController::class, 'adminReservations'])->name('admin.reservations')->middleware('role:admin');
    Route::get('/superadmin/reservations', [ReservationController::class, 'superAdminReservations'])->name('superadmin.reservations')->middleware('role:superadmin');

});

Route::middleware(['auth'])->group(function () {
    Route::get('/mes-reservations-client', [ReservationController::class, 'mesReservationsClient'])->name('reservations.client.mes');
    Route::get('/mes-reservations-chauffeur', [ReservationController::class, 'mesReservationsChauffeur'])->name('reservations.chauffeur.mes');
});

// Routes pour le système de trafic
Route::get('/traffic', [TrafficController::class, 'index'])->name('traffic.index');
Route::get('/traffic/fetch', [TrafficController::class, 'fetchIncidents'])->name('traffic.fetch');
Route::get('/traffic/api', [TrafficController::class, 'api'])->name('traffic.api');

// Routes pour la gestion des groupes de chauffeurs
Route::middleware('auth')->group(function () {
    Route::get('/driver-groups/schedule', [DriverGroupController::class, 'schedule'])->name('driver-groups.schedule');
    Route::get('/driver-groups/available-drivers', [DriverGroupController::class, 'getAvailableDrivers'])->name('driver-groups.available-drivers');
    Route::resource('driver-groups', DriverGroupController::class);
    Route::post('/driver-groups/{driverGroup}/advance-rotation', [DriverGroupController::class, 'advanceRotation'])->name('driver-groups.advance-rotation');
    Route::post('/driver-groups/{driverGroup}/reverse-rotation', [DriverGroupController::class, 'reverseRotation'])->name('driver-groups.reverse-rotation');
    Route::post('/driver-groups/{driverGroup}/reset-rotation', [DriverGroupController::class, 'resetRotation'])->name('driver-groups.reset-rotation');
    Route::post('/driver-groups/auto-assign', [DriverGroupController::class, 'autoAssignGroups'])->name('driver-groups.auto-assign');
});

Route::get('/google-auth', function () {
    $client = new Google_Client();
    $client->setAuthConfig(storage_path('app/google-calendar/credentials.json'));
    $client->addScope(Google_Service_Calendar::CALENDAR);
    $client->setAccessType('offline');
    $client->setPrompt('consent');

    $authUrl = $client->createAuthUrl();
    return redirect($authUrl);
});

Route::get('/google-auth', function () {
    $client = new Google_Client();
    $client->setAuthConfig(storage_path('app/google-calendar/credentials.json'));
    $client->addScope(Google_Service_Calendar::CALENDAR);
    $client->setAccessType('offline');
    $client->setPrompt('consent');

    $authUrl = $client->createAuthUrl();
    return redirect($authUrl);
});
Route::get('/oauth2callback', function () {
    $client = new Google_Client();
    $client->setAuthConfig(storage_path('app/google-calendar/credentials.json'));
    $client->addScope(Google_Service_Calendar::CALENDAR);
    $client->setAccessType('offline');
    $client->setRedirectUri('http://localhost:8000/oauth2callback');

    // Vérifiez si le code d'autorisation est présent
    if (!request()->has('code')) {
        return response()->json(['error' => 'No code provided'], 400);
    }

    try {
        // Échange le code d'autorisation contre un token d'accès
        $accessToken = $client->fetchAccessTokenWithAuthCode(request('code'));

        // Vérifiez si des erreurs sont présentes dans le token d'accès
        if (array_key_exists('error', $accessToken)) {
            throw new Exception($accessToken['error']);
        }

        // Sauvegarde du token dans un fichier local
        file_put_contents(storage_path('app/google-calendar/token.json'), json_encode($accessToken));

        return response()->json(['message' => 'Authentification réussie, token sauvegardé']);
    } catch (Exception $e) {
        return response()->json(['error' => 'Erreur lors de l\'authentification : ' . $e->getMessage()], 400);
    }
});
// Route::get('/reservations/calendar', [ReservationController::class, 'showCalendar'])->name('reservations.showCalendar');

//DashboardStts

// Route::middleware(['auth'])->group(function () {
//     Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('client.dashboard');
// });

// Routes pour la gestion des catégories
Route::middleware('auth')->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::get('/api/categories/active', [CategoryController::class, 'getActive'])->name('categories.active');
});

require __DIR__.'/auth.php';
