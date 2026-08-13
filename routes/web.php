<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\LandingController::class, 'index']);

Route::middleware('guest')->group(function () {
    Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('conferences', App\Http\Controllers\Admin\ConferenceController::class);

        Route::resource('topics', App\Http\Controllers\Admin\TopicController::class);

        Route::resource('speakers', App\Http\Controllers\Admin\SpeakerController::class);

        Route::resource('partners', App\Http\Controllers\Admin\PartnerController::class);

        Route::resource('important-dates', App\Http\Controllers\Admin\ImportantDateController::class);

        Route::resource('participants', App\Http\Controllers\Admin\ParticipantController::class);

        Route::resource('submissions', App\Http\Controllers\Admin\SubmissionController::class);

        Route::get('submissions/{submission}/reviews/create', [App\Http\Controllers\Admin\ReviewController::class, 'createForSubmission'])->name('submissions.reviews.create');
        Route::post('submissions/{submission}/reviews', [App\Http\Controllers\Admin\ReviewController::class, 'storeForSubmission'])->name('submissions.reviews.store');
        Route::resource('reviewers', App\Http\Controllers\Admin\ReviewerController::class);

        Route::resource('reviews', App\Http\Controllers\Admin\ReviewController::class);

        Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    });

Route::middleware(['auth'])
    ->prefix('reviewer')
    ->name('reviewer.')
    ->group(function () {

        Route::get('/dashboard', [App\Http\Controllers\Reviewer\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('/reviews', App\Http\Controllers\Reviewer\ReviewController::class);
    });
