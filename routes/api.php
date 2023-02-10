<?php

use App\Http\Controllers\Zone\SubZoneController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Zone\ZoneController;
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
//route:middleware(['sanctum'])->group(function () {
//    Route::resource('users', UserController::class)->except('create');
//});


Route::resource('users', UserController::class)->except('create');
Route::resource('zones', ZoneController::class)->except('create');
Route::resource('sub-zones', SubZoneController::class)->except('create');
