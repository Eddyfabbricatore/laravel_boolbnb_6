<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ApartmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
/* rotta da vue */
Route::get('/', function () {
    return view('viteHome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])
    ->name('admin.')
    ->prefix('admin')
    ->group(function () {
        // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        // Route::get('/', [DashboardController::class,'index'])->name('home');
        Route::resource('apartments', ApartmentController::class);
        // Route::resource('technologies', TechnologyController::class);
        // Route::resource('types', TypeController::class);
        // Route::get('typeProjects', [TypeController::class, 'typeProjects'])->name('typeProjects');
        // Route::get('projects-technology/{technology}', [TechnologyController::class, 'projectsTechnology'])->name('projects-technology');
    });

require __DIR__.'/auth.php';
