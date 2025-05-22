<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PresenceController;
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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// untuk validasi token menggunakan middleware
Route::middleware('jwt.auth')->group(function () {
    Route::get('/presence', [PresenceController::class, 'getAllDataPresence']);
    Route::post('/presence', [PresenceController::class, 'generatePresence']);
    Route::post('/presence/{id}/approval', [PresenceController::class, 'approvalPresence']);
});