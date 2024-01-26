<?php

use App\Http\Controllers\Admin\ApartmentController;
use App\Http\Controllers\Admin\MessageController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/apartments', [ApartmentController::class, 'getApartments']);

Route::get('search/', [ApartmentController::class, 'viewApartamentsInSearchAdvance']);

Route::get('singleApartment/{slug}', [ApartmentController::class, 'getSingleApartment']);
Route::post('send-message', [MessageController::class, 'store']);






Route::get('test-api/', [ApartmentController::class, 'getApartmentsTotal']);
