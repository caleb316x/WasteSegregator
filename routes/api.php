<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArduinoAction;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/check', [App\Http\Controllers\ArduinoAction::class, 'check_drop'])->name('arduino.check_drop');
// Route::get('/prediction', [App\Http\Controllers\ArduinoAction::class, 'prediction'])->name('arduino.prediction');
