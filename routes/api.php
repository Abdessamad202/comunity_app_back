<?php

use App\Http\Controllers\Api\Auth\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\Api\Guest\Authentication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authenticated Routes (requires Sanctum middleware)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Profile Routes
});
Route::get('/profile/{profile}', [ProfileController::class, 'show']);
Route::get('/posts', [PostController::class, 'index']);

// Guest Routes (no authentication required)
Route::prefix('register')->group(function () {
    Route::post('/step-1', [Authentication::class, 'registerStep1']);
    Route::post('/confirm/resend-code/{user}', [Authentication::class, 'resendVerificationEmailCode']);
    Route::post('/step-2/{user}', [Authentication::class, 'registerStep2']);
    Route::post('/step-3/{user}', [Authentication::class, 'registerStep3']);
});

Route::post('/login', [Authentication::class, 'login']);
Route::post('/verify-mail', [Authentication::class, 'verifyMail']);
Route::post('/check-code/{user}', [Authentication::class, 'checkCode']);
Route::post('/verify/resend-code/{user}', [Authentication::class, 'resendVerificationEmailCode']);
Route::post('/change-password/{user}', [Authentication::class, 'changePassword']);
Route::post('/logout', [Authentication::class, 'logout']);
