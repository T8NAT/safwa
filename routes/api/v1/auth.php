<?php

use App\Http\Controllers\Api\V1\User\Auth\AuthorizationController;
use App\Http\Controllers\Api\V1\User\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\V1\User\Auth\LoginController;
use App\Http\Controllers\Api\V1\User\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

//User Auth Routes
Route::middleware(['api.user.auth.guard'])->group(function () {
    Route::controller(RegisterController::class)->group(function () {
        Route::post('register', 'register');
    });

    Route::controller(LoginController::class)->group(function () {
        Route::post('login', 'login');
    });

    Route::controller(ForgotPasswordController::class)->group(function () {
        Route::post('find/user', 'findUserSendCode');
        Route::post('verify/code', 'verifyCode');
        Route::get('resend/code', 'resendCode');
        Route::post('reset', 'resetPassword');
    });

});
Route::controller(AuthorizationController::class)->group(function () {
    Route::get('mail/resend/code', 'resendCode');
    Route::post('mail/verify/code', 'verifyCode');
});
