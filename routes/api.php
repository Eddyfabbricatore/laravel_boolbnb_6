<?php

use App\Http\Controllers\Admin\ApartmentController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\StatsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/* ALL */
//Salva i risultati della ricerca avanzata
Route::get('search/', [ApartmentController::class, 'viewApartamentsInSearchAdvance']);

/* FRONT */
//Da i risultati nel front dei nostri appartamenti
Route::get('test-api/', [ApartmentController::class, 'getApartmentsTotal']);
//Manda nel back i messaggi inviati dal front
Route::post('send-message', [MessageController::class, 'store']);
//Appartamenti(all/single)
Route::get('/apartments', [ApartmentController::class, 'getApartments']);
Route::get('singleApartment/{slug}', [ApartmentController::class, 'getSingleApartment']);


/* BACK */
//eee
Route::get('updateChart', [StatsController::class, 'updateChart']);
Route::get('updateViewChart', [StatsController::class, 'updateViewChart']);
Route::get('updateMessageChart', [StatsController::class, 'updateMessageChart']);
//Route::get('updateSponsorChart', [StatsController::class, 'updateSponsorChart']);


/* GENERAL */
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
