<?php declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CardriverController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\DashController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\ReservationController;
use Spatie\Permission\Middlewares\RoleMiddleware;
// use Google_Client;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth:')->group(function () {
    Route::get('/admin/create-account', [AuthController::class, 'createAccountPage'])
        ->name('admin.create.account.page');

    Route::post('/admin/create-account', [AuthController::class, 'createAccount'])
        ->name('admin.create.account');

    Route::get('/clients', [AuthController::class, 'lisclient'])->name('clients.index');
    Route::get('/drivers', [AuthController::class, 'listdriver'])->name('drivers.index');
    Route::get('/agents', [AuthController::class, 'listagent'])->name('agents.index');
    Route::get('/admin', [AuthController::class, 'listadmin'])->name('admins.index');
    Route::get('/superaddmin', [AuthController::class, 'listsuperadmin'])->name('superadmins.index');

    Route::Resource('cars', CarController::class); 
    Route::Resource('auth', AuthController::class); 
    Route::resource('trips', TripController::class); 

});

Route::get('/reservations/confirmed', [ReservationController::class, 'confirmed'])->name('reservations.confirmed');

Route::get('/reservations/cancelled', [ReservationController::class, 'cancelled'])->name('reservations.cancelled');

Route::get('/assign-day-off', [AuthController::class, 'createDayOff'])->name('admins.assign-day-off');
Route::post('/assign-day-off', [AuthController::class, 'assignRandomDayOff'])->name('admin.assign-day-off');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // // Route pour l'admin
    // Route::get('/admin/dashboard', function () {
    //     return view('dashboards.admin');
    // })->name('dashboard.admin');

    // Route pour le client
    // Route::get('/client/dashboard', function () {
    //     return view('dashboards.client');
    // })->name('dashboard.client');

    // Route pour le chauffeur
    // Route::get('/chauffeur/dashboard', function () {
    //     return view('dashboards.driver');
    // })->name('dashboard.chauffeur');

    // Route pour l'entreprise
    // Route::get('/entreprise/dashboard', function () {
    //     return view('dashboards.entreprise');
    // })->name('dashboard.entreprise');

    // Route pour l'agent
    // Route::get('/agent/dashboard', function () {
    //     return view('dashboards.agent');
    // })->name('dashboard.agent');

    // Route pour le super admin
    // Route::get('/superadmin/dashboard', function () {
    //     return view('dashboards.superadmin');
    // })->name('dashboard.superadmin'); 


    Route::resource('maintenances', MaintenanceController::class);
    Route::resource('cars', CarController::class);
});

Route::middleware('auth')->group(function () {

    // Routes pour l'Admin
    Route::get('/admin/dashboard', [DashController::class, 'adminIndex'])->name('dashboard.admin');

    // Routes pour le Client
    Route::get('/client/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard.client');

    // Routes pour le Chauffeur
    Route::get('/chauffeur/dashboard', [DashController::class, 'chauffeurIndex'])->name('dashboard.chauffeur');

    // Routes pour l'Entreprise
    Route::get('/entreprise/dashboard', [DashController::class, 'entrepriseIndex'])->name('dashboard.entreprise');

    // Routes pour l'Agent
    Route::get('/agent/dashboard', [DashController::class, 'agentIndex'])->name('dashboard.agent');

    // Routes pour le Superadmin
    Route::get('/superadmin/dashboard', [DashController::class, 'superadminIndex'])->name('dashboard.superadmin');

});


Route::middleware(['auth', 'role:admin'])->get('/admin/dashboard', [AuthController::class, 'showAdminDashboard'])->name('dashboard.admin');

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

require __DIR__.'/auth.php';
