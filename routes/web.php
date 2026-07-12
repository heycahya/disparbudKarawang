<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicPortalController;
use App\Http\Controllers\LayananMasyarakatController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Guest Routes - Portal Publik (Katalog Informasi)
Route::name('public.')->group(function () {
    Route::get('/', [PublicPortalController::class, 'home'])->name('home');
    Route::get('/profil', [PublicPortalController::class, 'profile'])->name('profile');
    Route::get('/galeri', [PublicPortalController::class, 'galleryIndex'])->name('gallery.index');
    Route::get('/destinasi', [PublicPortalController::class, 'tourismIndex'])->name('destinasi');
    
    Route::prefix('news')->name('news.')->group(function () {
        Route::get('/', [PublicPortalController::class, 'newsIndex'])->name('index');
        Route::get('/{slug}', [PublicPortalController::class, 'newsShow'])->name('show');
    });

    Route::prefix('tourism')->name('tourism.')->group(function () {
        Route::get('/', [PublicPortalController::class, 'tourismIndex'])->name('index');
        Route::get('/{slug}', [PublicPortalController::class, 'tourismShow'])->name('show');
    });
});

// Public Layanan Masyarakat GET routes
Route::prefix('layanan-masyarakat')->name('layanan-masyarakat.')->group(function () {
    Route::get('/complaints/create', [LayananMasyarakatController::class, 'createComplaint'])->name('complaints.create');
    Route::get('/tourism-submissions/create', [LayananMasyarakatController::class, 'createTourismSubmission'])->name('tourism-submissions.create');
    Route::get('/event-broadcasts/create', [LayananMasyarakatController::class, 'createEventBroadcast'])->name('event-broadcasts.create');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Grup Public
    Route::middleware('role:public')->group(function () {
        Route::get('/dashboard/{alias?}', [\App\Http\Controllers\UserDashboardController::class, 'index'])->name('public.dashboard');

        Route::get('/dashboard', [\App\Http\Controllers\UserDashboardController::class, 'index'])->name('dashboard');

        Route::get('/history', [\App\Http\Controllers\HistoryController::class, 'index'])->name('public.history.index');

        // Auth & Role:public Routes - Layanan Masyarakat (POST only)
        Route::prefix('layanan-masyarakat')->name('layanan-masyarakat.')->group(function () {
            // Pengaduan Masyarakat (Complaints)
            Route::post('/complaints', [LayananMasyarakatController::class, 'storeComplaint'])->name('complaints.store');
            
            // Usulan Wisata (Tourism Submissions)
            Route::post('/tourism-submissions', [LayananMasyarakatController::class, 'storeTourismSubmission'])->name('tourism-submissions.store');
            
            // Permohonan Siaran Acara (Event Broadcast Requests)
            Route::post('/event-broadcasts', [LayananMasyarakatController::class, 'storeEventBroadcast'])->name('event-broadcasts.store');
        });
    });

    // Grup Admin & Super Admin
    Route::middleware('role:super_admin,admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('news', \App\Http\Controllers\Admin\NewsController::class);
        Route::resource('tourism-destinations', \App\Http\Controllers\Admin\TourismDestinationController::class);
        Route::resource('cultures', \App\Http\Controllers\Admin\CultureController::class);
        Route::resource('creative-economies', \App\Http\Controllers\Admin\CreativeEconomyController::class);
        Route::resource('accommodations', \App\Http\Controllers\Admin\AccommodationController::class);
        Route::resource('culinary-places', \App\Http\Controllers\Admin\CulinaryPlaceController::class);
        Route::resource('galleries', \App\Http\Controllers\Admin\GalleryController::class);

        // Grup Eksklusif Super Admin
        Route::middleware('role:super_admin')->group(function () {
            Route::get('/manajemen-akun', function () {
                return Inertia::render('Admin/ManajemenAkun');
            })->name('manajemen-akun');
            
            Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->except(['show']);
        });
    });
});

require __DIR__.'/auth.php';
