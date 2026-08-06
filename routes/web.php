<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\LandingController::class, 'index']);

Route::middleware('guest')->group(function () {
    Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLogin'])
        ->name('login');
    Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])
        ->name('login.store');
});

Route::middleware('auth')->group(function () {

    Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])
        ->name('logout');
});

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get(
            '/dashboard',
            [App\Http\Controllers\Admin\DashboardController::class, 'index']
        )->name('dashboard');
        Route::resource('conferences', App\Http\Controllers\Admin\ConferenceController::class);
        Route::resource('topics', App\Http\Controllers\Admin\TopicController::class);
    });

// Route::middleware(['auth'])
//     ->prefix('admin')
//     ->name('admin.')
//     ->group(function () {
//         Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
//         Route::resource('conference', App\Http\Controllers\Admin\ConferenceController::class);
//         Route::resource('topics', App\Http\Controllers\Admin\TopicController::class);
//         Route::resource('speakers', App\Http\Controllers\Admin\SpeakerController::class);
//         Route::resource('partners', App\Http\Controllers\Admin\PartnerController::class);
//     });
