<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArduinoAction;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/arduino/drop', [App\Http\Controllers\ArduinoAction::class, 'set_drop'])->name('arduino.set_drop');
Route::get('/optcam', [App\Http\Controllers\ArduinoAction::class, 'request_camera'])->name('arduino.optcam');
Route::get('/', [App\Http\Controllers\ArduinoAction::class, 'index'])->name('arduino.dashboard');
Route::get('/prediction/{class}', [App\Http\Controllers\ArduinoAction::class, 'prediction'])->name('arduino.prediction');
Route::get('/check', [App\Http\Controllers\ArduinoAction::class, 'check_drop'])->name('arduino.check_drop');
Route::get('/getprediction', [App\Http\Controllers\ArduinoAction::class, 'get_prediction'])->name('arduino.get_prediction');