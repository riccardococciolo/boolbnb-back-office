<?php

use App\Http\Controllers\Admin\ApartmentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\SponsorController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])
    ->name ('admin.') // per il nome
    ->prefix('admin') // per URL
    ->group (function () {
        Route::get('/', [DashboardController::class, 'index'])->name ('dashboard');
        Route::resource('apartments', ApartmentController::class)->parameters(['apartments' => 'apartment:slug']);
        Route::resource('leads', LeadController::class)->parameters(['leads' => 'lead:id']);
        Route::resource('sponsor', SponsorController::class)->parameters(['sponsors' => 'sponsor:id']);
    });

require __DIR__.'/auth.php';
