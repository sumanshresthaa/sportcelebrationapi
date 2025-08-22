<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CountryAdminController;

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

Route::get('/countries', [CountryAdminController::class, 'index'])->name('countries.index');
Route::get('/countries/create', [CountryAdminController::class, 'create'])->name('countries.create');
Route::post('/countries', [CountryAdminController::class, 'store'])->name('countries.store');


