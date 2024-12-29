<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Mobile\AuthController;
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

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->get('/user', [AuthController::class, 'getUser']);

Route::get('/attendances/{attendance}', [AttendanceController::class, 'show'])->name('attendances.show');
Route::post('/attendances', [AttendanceController::class, 'store'])->name('attendances.store');
Route::put('/attendances/{attendance}', [AttendanceController::class, 'update'])->name('attendances.update');

Route::apiResource('messages', MessageController::class);
