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

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::get('conferences/{conference}/settings', [App\Http\Controllers\Admin\ConferenceSettingController::class, 'edit'])->name('conferences.settings.edit');
        Route::put('conferences/{conference}/settings', [App\Http\Controllers\Admin\ConferenceSettingController::class, 'update'])->name('conferences.settings.update');

        Route::get('conferences/{conference}/configuration', [App\Http\Controllers\Admin\ConferenceConfigurationController::class, 'edit'])->name('conferences.configuration.edit');
        Route::put('conferences/{conference}/configuration', [App\Http\Controllers\Admin\ConferenceConfigurationController::class, 'update'])->name('conferences.configuration.update');

        Route::resource('conferences', App\Http\Controllers\Admin\ConferenceController::class);

        Route::resource('topics', App\Http\Controllers\Admin\TopicController::class);

        Route::resource('speakers', App\Http\Controllers\Admin\SpeakerController::class);

        Route::resource('partners', App\Http\Controllers\Admin\PartnerController::class);

        Route::resource('important-dates', App\Http\Controllers\Admin\ImportantDateController::class);

        Route::resource('participants', App\Http\Controllers\Admin\ParticipantController::class);

        Route::patch('submissions/{submission}/camera-ready/approve', [App\Http\Controllers\Admin\SubmissionController::class, 'approveCameraReady'])->name('submissions.camera-ready.approve');
        Route::patch('submissions/{submission}/camera-ready/correction', [App\Http\Controllers\Admin\SubmissionController::class, 'requestCameraReadyCorrection'])->name('submissions.camera-ready.correction');
        Route::resource('submissions', App\Http\Controllers\Admin\SubmissionController::class);

        Route::get('submissions/{submission}/reviews/create', [App\Http\Controllers\Admin\ReviewController::class, 'createForSubmission'])->name('submissions.reviews.create');
        Route::post('submissions/{submission}/reviews', [App\Http\Controllers\Admin\ReviewController::class, 'storeForSubmission'])->name('submissions.reviews.store');
        Route::resource('reviewers', App\Http\Controllers\Admin\ReviewerController::class);

        Route::resource('reviews', App\Http\Controllers\Admin\ReviewController::class);

        Route::resource('users', App\Http\Controllers\Admin\UserController::class);

        Route::get('payments', [App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments.index');
        Route::get('payments/{payment}', [App\Http\Controllers\Admin\PaymentController::class, 'show'])->name('payments.show');
        Route::patch('payments/{payment}/verify', [App\Http\Controllers\Admin\PaymentController::class, 'verify'])->name('payments.verify');
        Route::patch('payments/{payment}/reject', [App\Http\Controllers\Admin\PaymentController::class, 'reject'])->name('payments.reject');
        Route::delete('payments/{payment}', [App\Http\Controllers\Admin\PaymentController::class, 'destroy'])->name('payments.destroy');

        Route::post('certificates/{certificate}/regenerate', [App\Http\Controllers\Admin\CertificateController::class, 'regenerate'])->name('certificates.regenerate');
        Route::get('certificates/{certificate}/download', [App\Http\Controllers\Admin\CertificateController::class, 'download'])->name('certificates.download');
        Route::resource('certificates', App\Http\Controllers\Admin\CertificateController::class)->only(['index', 'create', 'store', 'show', 'destroy']);
    });

Route::middleware(['auth', 'role:reviewer'])
    ->prefix('reviewer')
    ->name('reviewer.')
    ->group(function () {

        Route::get('/dashboard', [App\Http\Controllers\Reviewer\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('/reviews', App\Http\Controllers\Reviewer\ReviewController::class);
    });

Route::middleware(['auth', 'role:participant'])
    ->prefix('participant')
    ->name('participant.')
    ->group(function () {

        Route::get('/dashboard', [App\Http\Controllers\Participant\DashboardController::class, 'index'])->name('dashboard');

        Route::get('/profile', [App\Http\Controllers\Participant\ProfileController::class, 'edit'])->name('profile.edit');

        Route::put('/profile', [App\Http\Controllers\Participant\ProfileController::class, 'update'])->name('profile.update');

        Route::get('/registration', [App\Http\Controllers\Participant\RegistrationController::class, 'index'])->name('registration.index');
        Route::get('/registration/create', [App\Http\Controllers\Participant\RegistrationController::class, 'create'])->name('registration.create');
        Route::post('/registration', [App\Http\Controllers\Participant\RegistrationController::class, 'store'])->name('registration.store');

        Route::get('/payments', [App\Http\Controllers\Participant\PaymentController::class, 'index'])->name('payments.index');
        Route::get('/payments/create', [App\Http\Controllers\Participant\PaymentController::class, 'create'])->name('payments.create');
        Route::post('/payments', [App\Http\Controllers\Participant\PaymentController::class, 'store'])->name('payments.store');

        Route::get('/submissions', [App\Http\Controllers\Participant\SubmissionController::class, 'index'])->name('submissions.index');
        Route::get('/submissions/create', [App\Http\Controllers\Participant\SubmissionController::class, 'create'])->name('submissions.create');
        Route::post('/submissions', [App\Http\Controllers\Participant\SubmissionController::class, 'store'])->name('submissions.store');
        Route::get('/submissions/{submission}', [App\Http\Controllers\Participant\SubmissionController::class, 'show'])->name('submissions.show');

        Route::get('/submissions/{submission}/revision', [App\Http\Controllers\Participant\SubmissionController::class, 'revision'])->name('submissions.revision');
        Route::post('/submissions/{submission}/revision', [App\Http\Controllers\Participant\SubmissionController::class, 'uploadRevision'])->name('submissions.revision.upload');
        Route::get('/submissions/{submission}/camera-ready', [App\Http\Controllers\Participant\SubmissionController::class, 'cameraReady'])->name('submissions.camera-ready');
        Route::post('/submissions/{submission}/camera-ready', [App\Http\Controllers\Participant\SubmissionController::class, 'uploadCameraReady'])->name('submissions.camera-ready.upload');

        Route::get('/certificates', [App\Http\Controllers\Participant\CertificateController::class, 'index'])->name('certificates.index');
        Route::get('/certificates/{certificate}', [App\Http\Controllers\Participant\CertificateController::class, 'show'])->name('certificates.show');
        Route::get('/certificates/{certificate}/download', [App\Http\Controllers\Participant\CertificateController::class, 'download'])->name('certificates.download');
    });
