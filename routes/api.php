<?php

use App\Http\Controllers\Amounts\CurrentController;
use App\Http\Controllers\Amounts\HumidityController;
use App\Http\Controllers\Amounts\TemperatureController;
use App\Http\Controllers\Device\DeviceController;
use App\Http\Controllers\User\AuthController;
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

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::resource('users', UserController::class)
    ->except('create')
    ->middleware('auth:sanctum');
Route::resource('zones', ZoneController::class)->except('create');
Route::resource('sub-zones', SubZoneController::class)->except('create');
Route::resource('devices', DeviceController::class)->except('create');

//Route::resource('temperatures', TemperatureController::class)->except('create', 'delete', 'update', 'edit', 'show');

Route::prefix('temperatures')->group(function () {
    Route::post('/', [TemperatureController::class, 'store'])->middleware('apikey');
    Route::get('/', [TemperatureController::class, 'index']);
    Route::get('/{start}/{end}', [TemperatureController::class, 'getTemperatureByDate']);
    Route::get('/{deviceId}', [TemperatureController::class, 'getTemperatureByDeviceId']);
    Route::get('/datetime/{date}/{timeRange}', [TemperatureController::class, 'getTemperatureByDateTime']);
});

//Route::resource('humidities', HumidityController::class)->except('create', 'delete', 'update', 'edit', 'show');

Route::prefix('humidities')->group(function () {
    Route::post('/', [HumidityController::class, 'store'])->middleware('apikey');
    Route::get('/', [HumidityController::class, 'index']);
    Route::get('/{start}/{end}', [HumidityController::class, 'getHumidityByDate']);
    Route::get('/{deviceId}', [HumidityController::class, 'getHumidityByDeviceId']);
    Route::get('/datetime/{date}/{timeRange}', [HumidityController::class, 'getHumidityByDateTime']);
});



//Route::resource('currents', CurrentController::class)->except('create', 'delete', 'update', 'edit', 'show');


Route::prefix('currents')->group(function () {
    Route::post('/', [CurrentController::class, 'store'])->middleware('apikey');
    Route::get('/', [CurrentController::class, 'index']);
    Route::get('/{start}/{end}', [CurrentController::class, 'getCurrentByDate']);
    Route::get('/{deviceId}', [CurrentController::class, 'getCurrentByDeviceId']);
    Route::get('/datetime/{date}/{timeRange}', [CurrentController::class, 'getCurrentByDateTime']);
});
