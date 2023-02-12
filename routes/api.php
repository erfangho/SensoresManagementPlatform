<?php

use App\Http\Controllers\Amounts\TemperatureController;
use App\Http\Controllers\Device\DeviceController;
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
Route::resource('devices', DeviceController::class)->except('create');
Route::resource('temperatures', TemperatureController::class)->except('create', 'delete', 'update', 'edit', 'show');
Route::get('temperatures/{start}/{end}', [TemperatureController::class, 'getTemperatureByDate']);
Route::get('temperatures/{deviceId}', [TemperatureController::class, 'getTemperatureByDeviceId']);
Route::get('temperatures/{date}/{startTime}/{endTime}', [TemperatureController::class, 'getTemperatureByDateTime']);
