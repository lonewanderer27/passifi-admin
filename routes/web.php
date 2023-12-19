<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', [\App\Http\Controllers\Controller::class, 'adminIndex'])->name('dashboard')->middleware('auth');
Route::get('/events/id/{id}/scan', [\App\Http\Controllers\EventController::class, 'adminScan'])->name('adminScan');

Route::get('/statistics', [\App\Http\Controllers\StatisticsController::class, 'index'])->name('statistics');

Route::post('/login', [\App\Http\Controllers\UserController::class, 'adminLogin'])->name('login');
Route::get('/signup', [\App\Http\Controllers\UserController::class, 'showAdminSignup'])->name('signup');
Route::post('/signup', [\App\Http\Controllers\UserController::class, 'adminSignup'])->name('signup');
Route::get('/logout', [\App\Http\Controllers\UserController::class, 'adminLogout'])->name('logout');
