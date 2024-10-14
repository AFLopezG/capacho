<?php

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

Route::post('login', [App\Http\Controllers\UserController::class, 'login']);
Route::post('me/{token}', [App\Http\Controllers\UserController::class, 'me']);
Route::post('logout', [App\Http\Controllers\UserController::class, 'logout']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    //return $request->user();


    Route::apiResource('user', App\Http\Controllers\UserController::class);
    Route::put('/updatePassword/{user}',[\App\Http\Controllers\UserController::class,'updatePassword']);
    Route::post('/cambioEstado/{id}',[\App\Http\Controllers\UserController::class,'cambioEstado']);

    Route::apiResource('servicio', App\Http\Controllers\ServicioController::class);
    Route::post('/listServicio',[\App\Http\Controllers\ServicioController::class,'listServicio']);
    Route::apiResource('venta', App\Http\Controllers\VentaController::class);
    

});

