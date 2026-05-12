<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CitizenController;
use App\Http\Controllers\DueController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\MailController;

//public route
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//protected route
Route::middleware('auth:api')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Pendataan Warga
    Route::get('/citizens', [CitizenController::class, 'index']);
    Route::post('/citizens', [CitizenController::class, 'store']);
    Route::get('/citizens/{id}', [CitizenController::class, 'show']);
    Route::put('/citizens/{id}', [CitizenController::class, 'update']);
    Route::delete('/citizens/{id}', [CitizenController::class, 'destroy']);

    // Iuran Warga
    Route::get('/dues', [DueController::class, 'index']);
    Route::post('/dues', [DueController::class, 'store']);
    Route::get('/dues/{id}', [DueController::class, 'show']);
    Route::put('/dues/{id}', [DueController::class, 'update']);
    Route::delete('/dues/{id}', [DueController::class, 'destroy']);

    // Aduan Warga
    Route::get('/complaints', [ComplaintController::class, 'index']);
    Route::post('/complaints', [ComplaintController::class, 'store']);
    Route::get('/complaints/{id}', [ComplaintController::class, 'show']);
    Route::put('/complaints/{id}', [ComplaintController::class, 'update']);
    Route::delete('/complaints/{id}', [ComplaintController::class, 'destroy']);

    // Persuratan
    Route::get('/mails', [MailController::class, 'index']);
    Route::post('/mails', [MailController::class, 'store']);
    Route::get('/mails/{id}', [MailController::class, 'show']);
    Route::put('/mails/{id}', [MailController::class, 'update']);
    Route::delete('/mails/{id}', [MailController::class, 'destroy']);
});