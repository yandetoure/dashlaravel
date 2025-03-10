<?php declare(strict_types=1); 

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route pour l'admin
    Route::get('/admin/dashboard', function () {
        return view('dashboards.admin');
    })->name('dashboard.admin');

    // Route pour le client
    Route::get('/client/dashboard', function () {
        return view('dashboards.client');
    })->name('dashboard.client');

    // Route pour le chauffeur
    Route::get('/chauffeur/dashboard', function () {
        return view('dashboards.driver');
    })->name('dashboard.chauffeur');

    // Route pour l'entreprise
    Route::get('/entreprise/dashboard', function () {
        return view('dashboards.entreprise');
    })->name('dashboard.entreprise');

    // Route pour l'agent
    Route::get('/agent/dashboard', function () {
        return view('dashboards.agent');
    })->name('dashboard.agent');

    // Route pour le super admin
    Route::get('/superadmin/dashboard', function () {
        return view('dashboards.superadmin');
    })->name('dashboard.superadmin'); // Correction du nom
});


require __DIR__.'/auth.php';
