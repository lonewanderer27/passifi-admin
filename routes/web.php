<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\Controller;

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

Route::get('/', [Controller::class, 'adminIndex'])->name('dashboard')->middleware('auth');
Route::get('/events/id/{id}/scan', [EventController::class, 'adminScan'])->name('adminScan');

Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics');

Route::post('/login', [UserController::class, 'adminLogin'])->name('login');
Route::get('/signup', [UserController::class, 'showAdminSignup'])->name('signup');
Route::post('/signup', [UserController::class, 'adminSignup'])->name('signup');
Route::get('/logout', [UserController::class, 'adminLogout'])->name('logout');
