<?php

use App\Http\Controllers\Amounts\AmountController;
use App\Http\Controllers\Amounts\CurrentController;
use App\Http\Controllers\Amounts\HumidityController;
use App\Http\Controllers\Amounts\PowerController;
use App\Http\Controllers\Amounts\TemperatureController;
use App\Http\Controllers\Amounts\VoltageController;
use App\Http\Controllers\Device\DeviceController;
use App\Http\Controllers\Device\OrderController;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\Zone\SubZoneController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Zone\ZoneController;
use App\Models\Power;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

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
Route::get('user/info', [UserController::class, 'getUserByToken'])->middleware('auth:sanctum');;


Route::resource('zones', ZoneController::class)->except('create')->middleware('auth:sanctum');
Route::resource('sub-zones', SubZoneController::class)->except('create')->middleware('auth:sanctum');
Route::get('devices/status', [DeviceController::class, 'getDevicesStatus'])->middleware('auth:sanctum');
Route::resource('devices', DeviceController::class)->except('create')->middleware('auth:sanctum');


//Route::resource('temperatures', TemperatureController::class)->except('create', 'delete', 'update', 'edit', 'show');

Route::prefix('temperatures')->group(function () {
    Route::get('/export-csv', [TemperatureController::class, 'exportTemperatureAsCsv'])->middleware('auth:sanctum');
    Route::get('/average/{start}/{end}', [TemperatureController::class, 'getAverageTemperatureByDate'])->middleware('auth:sanctum');
    Route::post('/', [TemperatureController::class, 'store'])->middleware('apikey');
    Route::get('/', [TemperatureController::class, 'index'])->middleware('auth:sanctum');
    Route::get('/{start}/{end}', [TemperatureController::class, 'getTemperatureByDate'])->middleware('auth:sanctum');
    Route::get('/{deviceId}', [TemperatureController::class, 'getTemperatureByDeviceId'])->middleware('auth:sanctum');
    Route::get('/datetime/{date}/{timeRange}', [TemperatureController::class, 'getTemperatureByDateTime'])->middleware('auth:sanctum');
});

//Route::resource('humidities', HumidityController::class)->except('create', 'delete', 'update', 'edit', 'show');

Route::prefix('humidities')->group(function () {
    Route::get('/export-csv', [HumidityController::class, 'exportHumidityAsCsv'])->middleware('auth:sanctum');
    Route::get('/average/{start}/{end}', [HumidityController::class, 'getAverageHumidityByDate'])->middleware('auth:sanctum');
    Route::post('/', [HumidityController::class, 'store'])->middleware('apikey');
    Route::get('/', [HumidityController::class, 'index'])->middleware('auth:sanctum');
    Route::get('/{start}/{end}', [HumidityController::class, 'getHumidityByDate'])->middleware('auth:sanctum');
    Route::get('/{deviceId}', [HumidityController::class, 'getHumidityByDeviceId'])->middleware('auth:sanctum');
    Route::get('/datetime/{date}/{timeRange}', [HumidityController::class, 'getHumidityByDateTime'])->middleware('auth:sanctum');
});



//Route::resource('currents', CurrentController::class)->except('create', 'delete', 'update', 'edit', 'show');


Route::prefix('currents')->group(function () {
    Route::get('/export-csv', [CurrentController::class, 'exportCurrentAsCsv'])->middleware('auth:sanctum');
    Route::get('/average/{start}/{end}', [CurrentController::class, 'getAverageCurrentByDate'])->middleware('auth:sanctum');
    Route::post('/', [CurrentController::class, 'store'])->middleware('apikey');
    Route::get('/', [CurrentController::class, 'index'])->middleware('auth:sanctum');
    Route::get('/{start}/{end}', [CurrentController::class, 'getCurrentByDate'])->middleware('auth:sanctum');
    Route::get('/{deviceId}', [CurrentController::class, 'getCurrentByDeviceId'])->middleware('auth:sanctum');
    Route::get('/datetime/{date}/{timeRange}', [CurrentController::class, 'getCurrentByDateTime'])->middleware('auth:sanctum');
});


Route::prefix('voltages')->group(function () {
    Route::get('/export-csv', [VoltageController::class, 'exportVoltageAsCsv'])->middleware('auth:sanctum');
    Route::get('/average/{start}/{end}', [VoltageController::class, 'getAverageVoltageByDate'])->middleware('auth:sanctum');
    Route::post('/', [VoltageController::class, 'store'])->middleware('apikey');
    Route::get('/', [VoltageController::class, 'index'])->middleware('auth:sanctum');
    Route::get('/{start}/{end}', [VoltageController::class, 'getVoltageByDate'])->middleware('auth:sanctum');
    Route::get('/{deviceId}', [VoltageController::class, 'getVoltageByDeviceId'])->middleware('auth:sanctum');
    Route::get('/datetime/{date}/{timeRange}', [VoltageController::class, 'getVoltageByDateTime'])->middleware('auth:sanctum');
});

Route::prefix('powers')->group(function () {
    Route::get('/', [PowerController::class, 'index'])->middleware('auth:sanctum');
    Route::get('/average/{start}/{end}', [PowerController::class, 'getAverageByDate'])->middleware('auth:sanctum');
});

Route::post('/amounts', [AmountController::class, 'setAllAmounts'])->middleware('apikey');

Route::get('/orders', [OrderController::class, 'index'])->middleware('auth:sanctum');
Route::post('/orders', [OrderController::class, 'store'])->middleware('auth:sanctum');

Route::get('auth-problem', function () {
    return response()->json(['error' => 'unauthenticated'], ResponseAlias::HTTP_FORBIDDEN);
})->name('auth-exception');

