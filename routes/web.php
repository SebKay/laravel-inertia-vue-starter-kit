<?php

use App\Enums\Role;
use Illuminate\Support\Facades\Route;

Route::get('health', Spatie\Health\Http\Controllers\HealthCheckResultsController::class)->middleware(['auth', 'role:'.Role::SUPER_ADMIN->value]);

Route::get('elements', fn() => inertia('Elements'))->middleware(['auth', 'role:'.Role::SUPER_ADMIN->value])->name('elements');

Route::controller(App\Http\Controllers\RegisterController::class)
    ->middleware(['guest'])
    ->group(function () {
        Route::get('register', 'show')->name('register');
        Route::post('register', 'store')->name('register.store')->middleware(['throttle:6,1']);
    });

Route::controller(App\Http\Controllers\LoginController::class)
    ->middleware(['guest'])
    ->group(function () {
        Route::get('login', 'show')->name('login');
        Route::post('login', 'store')->name('login.store')->middleware(['throttle:6,1']);
    });

Route::controller(App\Http\Controllers\ResetPasswordController::class)
    ->group(function () {
        Route::get('forgot-password', 'show')->name('password');
        Route::post('forgot-password', 'store')->name('password.store')->middleware(['throttle:6,1']);
        Route::get('reset-password/{token}', 'edit')->name('password.reset');
        Route::patch('reset-password', 'update')->name('password.update')->middleware(['throttle:6,1']);
    });

Route::post('logout', App\Http\Controllers\LogoutController::class)
    ->middleware(['auth'])
    ->name('logout');

Route::controller(App\Http\Controllers\DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->group(function () {
        Route::get('/', 'index')->name('home');
    });

Route::controller(App\Http\Controllers\AccountController::class)
    ->prefix('account')
    ->middleware(['auth', 'verified'])
    ->group(function () {
        Route::get('', 'edit')->name('account.edit');
        Route::patch('', 'update')->name('account.update');
    });

Route::controller(App\Http\Controllers\EmailVerificationController::class)
    ->prefix('account')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('verify', 'show')->name('verification.notice');
        Route::get('verify/{id}/{hash}', 'store')->middleware(['signed'])->name('verification.verify');
        Route::post('verify/resend', 'update')->middleware(['auth', 'throttle:6,1'])->name('verification.send');
    });
