<?php

use App\Enums\Role;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ConfirmPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Support\Facades\Route;
use Spatie\Health\Http\Controllers\HealthCheckResultsController;

Route::get('health', HealthCheckResultsController::class)->middleware(['auth', 'role:'.Role::SUPER->value]);

Route::get('password-test', PasswordController::class)
    ->middleware(['auth', 'verified', 'role:'.Role::SUPER->value, 'password.confirm'])
    ->name('password-test');

Route::controller(RegisterController::class)
    ->middleware(['guest'])
    ->group(function () {
        Route::get('register', 'show')->name('register');
        Route::post('register', 'store')->name('register.store')->middleware(['throttle:6,1']);
    });

Route::controller(LoginController::class)
    ->middleware(['guest'])
    ->group(function () {
        Route::get('login', 'show')->name('login');
        Route::post('login', 'store')->name('login.store')->middleware(['throttle:6,1']);
    });

Route::controller(ResetPasswordController::class)
    ->middleware(['guest'])
    ->group(function () {
        Route::get('forgot-password', 'show')->name('password');
        Route::post('forgot-password', 'store')->name('password.store')->middleware(['throttle:6,1']);
        Route::get('reset-password/{token}', 'edit')->name('password.reset');
        Route::patch('reset-password', 'update')->name('password.update')->middleware(['throttle:6,1']);
    });

Route::post('logout', LogoutController::class)
    ->middleware(['auth'])
    ->name('logout');

Route::get('/', DashboardController::class)
    ->middleware(['auth'])
    ->name('home');

Route::controller(AccountController::class)
    ->prefix('account')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('', 'edit')->name('account.edit');
        Route::patch('', 'update')->name('account.update');
    });

Route::controller(ConfirmPasswordController::class)
    ->prefix('account')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('password', 'show')->name('password.confirm');
        Route::post('password', 'store')->name('password.confirm.store')->middleware(['throttle:6,1']);
    });

Route::controller(EmailVerificationController::class)
    ->prefix('account')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('verify', 'show')->name('verification.notice');
        Route::get('verify/{id}/{hash}', 'store')->middleware(['signed'])->name('verification.verify');
        Route::post('verify/resend', 'update')->middleware(['auth', 'throttle:6,1'])->name('verification.send');
    });
