<?php

use App\Http\Controllers\Api\ApartmentController;
use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::get('/apartments', [ApartmentController::class, 'index']);
Route::get('/apartment/research/{slug}', [ApartmentController::class, 'show']);
Route::get('/services', [ServiceController::class, 'index']);
Route::post('/apartments/service_filter', [ApartmentController::class, 'checkbox_filter']);
Route::post('/leads', [LeadController::class, 'store']);
Route::get('/apartments/sponsored', [ApartmentController::class, 'getSponsored']);