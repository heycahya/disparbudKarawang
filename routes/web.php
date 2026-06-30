<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicPortalController;
use App\Http\Controllers\ServiceRakyatController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Guest Routes - Portal Publik (Katalog Informasi)
Route::name('public.')->group(function () {
    Route::get('/', [PublicPortalController::class, 'home'])->name('home');
    
    Route::prefix('news')->name('news.')->group(function () {
        Route::get('/', [PublicPortalController::class, 'newsIndex'])->name('index');
        Route::get('/{slug}', [PublicPortalController::class, 'newsShow'])->name('show');
    });

    Route::prefix('tourism')->name('tourism.')->group(function () {
        Route::get('/', [PublicPortalController::class, 'tourismIndex'])->name('index');
        Route::get('/{slug}', [PublicPortalController::class, 'tourismShow'])->name('show');
    });
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

        // Auth & Role:public Routes - Service Rakyat
        Route::prefix('service-rakyat')->name('service-rakyat.')->group(function () {
            // Pengaduan Masyarakat (Complaints)
            Route::get('/complaints/create', [ServiceRakyatController::class, 'createComplaint'])->name('complaints.create');
            Route::post('/complaints', [ServiceRakyatController::class, 'storeComplaint'])->name('complaints.store');
            
            // Usulan Wisata (Tourism Submissions)
            Route::get('/tourism-submissions/create', [ServiceRakyatController::class, 'createTourismSubmission'])->name('tourism-submissions.create');
            Route::post('/tourism-submissions', [ServiceRakyatController::class, 'storeTourismSubmission'])->name('tourism-submissions.store');
            
            // Permohonan Siaran Acara (Event Broadcast Requests)
            Route::get('/event-broadcasts/create', [ServiceRakyatController::class, 'createEventBroadcast'])->name('event-broadcasts.create');
            Route::post('/event-broadcasts', [ServiceRakyatController::class, 'storeEventBroadcast'])->name('event-broadcasts.store');
        });
    });

    // Grup Admin & Super Admin
    Route::middleware('role:super_admin,admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return Inertia::render('Admin/DashboardOverview');
        })->name('dashboard');

        Route::resource('news', \App\Http\Controllers\Admin\NewsController::class);
        Route::resource('tourism-destinations', \App\Http\Controllers\Admin\TourismDestinationController::class);

        // Grup Eksklusif Super Admin
        Route::middleware('role:super_admin')->group(function () {
            Route::get('/manajemen-akun', function () {
                return Inertia::render('Admin/ManajemenAkun');
            })->name('manajemen-akun');
        });
    });
});

require __DIR__.'/auth.php';
