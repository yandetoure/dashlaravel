<?php declare(strict_types=1);

use App\Http\Controllers\API\LoginAPIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/login', [LoginAPIController::class, 'createAccount']);
