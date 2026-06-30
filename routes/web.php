<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Grup Public
    Route::middleware('role:public')->group(function () {
        Route::get('/dashboard/{alias?}', function () {
            return Inertia::render('Public/UserDashboard');
        })->name('public.dashboard');

        Route::get('/dashboard', function () {
            return Inertia::render('Public/UserDashboard');
        })->name('dashboard');
    });

    // Grup Admin & Super Admin
    Route::middleware('role:super_admin,admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return Inertia::render('Admin/DashboardOverview');
        })->name('dashboard');

        Route::resource('news', \App\Http\Controllers\Admin\NewsController::class);
        Route::resource('tourism-destinations', \App\Http\Controllers\Admin\TourismDestinationController::class);

        // Service Rakyat Inbox
        Route::prefix('service-rakyat')->name('service-rakyat.')->group(function () {
            // Complaints
            Route::resource('complaints', \App\Http\Controllers\Admin\ComplaintReviewController::class)->only(['index', 'show']);
            Route::patch('complaints/{complaint}/status', [\App\Http\Controllers\Admin\ComplaintReviewController::class, 'updateStatus'])->name('complaints.status');

            // Tourism Submissions
            Route::resource('tourism-submissions', \App\Http\Controllers\Admin\TourismSubmissionReviewController::class)->only(['index', 'show']);
            Route::patch('tourism-submissions/{tourism_submission}/status', [\App\Http\Controllers\Admin\TourismSubmissionReviewController::class, 'updateStatus'])->name('tourism-submissions.status');

            // Event Broadcast Requests
            Route::resource('event-broadcasts', \App\Http\Controllers\Admin\EventBroadcastReviewController::class)->only(['index', 'show']);
            Route::patch('event-broadcasts/{event_broadcast}/status', [\App\Http\Controllers\Admin\EventBroadcastReviewController::class, 'updateStatus'])->name('event-broadcasts.status');
        });

        // Grup Eksklusif Super Admin
        Route::middleware('role:super_admin')->group(function () {
            Route::get('/manajemen-akun', function () {
                return Inertia::render('Admin/ManajemenAkun');
            })->name('manajemen-akun');
        });
    });
});

require __DIR__.'/auth.php';
