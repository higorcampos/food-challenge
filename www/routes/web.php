<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DistancesController;

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

Route::get('/', [DistancesController::class, 'index']);
Route::resource('distances', DistancesController::class)->except([
    'destroy','update'
]);
Route::get('distances/{distance}/delete', [DistancesController::class, 'destroy'])->name('distances.destroy');
Route::post('distances/{distance}/update', [DistancesController::class, 'update'])->name('distances.update');