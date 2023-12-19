<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('users', [UserController::class, 'index']);
Route::post('users/signup', [UserController::class, 'signupUser']);
Route::get('users/{id}', [UserController::class, 'findUserById']);
Route::get('users/{id}/events/approved', [UserController::class, 'getApprovedEventsForUser']);
Route::post('users/getByEmailAndPassword', [UserController::class, 'getUserByEmailAndPassword']);

Route::get('events', [EventController::class, 'index']);
Route::get('events/{id}/avatar', [EventController::class, 'getAvatar']);
Route::post('events', [EventController::class, 'adminStore']);
Route::post('events/join', [GuestController::class, 'joinUsingInviteCode']);
Route::get('events/{id}/qr-code', [EventController::class, 'getEventQR'])->name("event-qrcode");

Route::post('events/{id}/guests', [GuestController::class, 'store']);
Route::get('events/{id}/guests', [GuestController::class, 'getAllGuestsByEvent']);
Route::get('events/{id}/guests/approved', [GuestController::class, 'getApprovedGuestsByEvent']);
Route::get('events/{id}/guests/denied', [GuestController::class, 'getDeniedGuestsByEvent']);
Route::get('events/{id}/guests/pending', [GuestController::class, 'getPendingGuestsByEvent']);
Route::patch('events/{id}/guests/{guest_id}/approve', [GuestController::class, 'approveGuest']);
Route::patch('events/{id}/guests/{guest_id}/deny', [GuestController::class, 'denyGuest']);
