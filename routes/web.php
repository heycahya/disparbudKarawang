<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicPortalController;
use App\Http\Controllers\LayananMasyarakatController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Guest Routes - Portal Publik (Katalog Informasi & Detail Page)
Route::name('public.')->group(function () {
    Route::get('/', [PublicPortalController::class, 'home'])->name('home');
    
    // Detail routes per kategori & berita
    Route::get('/news/{slug}', [PublicPortalController::class, 'newsShow'])->name('news.show');
    Route::get('/tourism/{slug}', [PublicPortalController::class, 'tourismShow'])->name('tourism.show');
    Route::get('/budaya/{slug}', [PublicPortalController::class, 'cultureShow'])->name('culture.show');
    Route::get('/ekraf/{slug}', [PublicPortalController::class, 'ekrafShow'])->name('ekraf.show');
    Route::get('/akomodasi/{slug}', [PublicPortalController::class, 'accommodationShow'])->name('accommodation.show');
    Route::get('/kuliner/{slug}', [PublicPortalController::class, 'culinaryShow'])->name('culinary.show');
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

        // Auth & Role:public Routes - Layanan Masyarakat (GET & POST)
        Route::prefix('layanan-masyarakat')->name('layanan-masyarakat.')->group(function () {
            // Pengaduan Masyarakat (Complaints)
            Route::get('/complaints/create', [LayananMasyarakatController::class, 'createComplaint'])->name('complaints.create');
            Route::post('/complaints', [LayananMasyarakatController::class, 'storeComplaint'])->name('complaints.store');
            
            // Usulan Wisata (Tourism Submissions)
            Route::get('/tourism-submissions/create', [LayananMasyarakatController::class, 'createTourismSubmission'])->name('tourism-submissions.create');
            Route::post('/tourism-submissions', [LayananMasyarakatController::class, 'storeTourismSubmission'])->name('tourism-submissions.store');
            
            // Permohonan Siaran Acara (Event Broadcast Requests)
            Route::get('/event-broadcasts/create', [LayananMasyarakatController::class, 'createEventBroadcast'])->name('event-broadcasts.create');
            Route::post('/event-broadcasts', [LayananMasyarakatController::class, 'storeEventBroadcast'])->name('event-broadcasts.store');
        });
    });

    // Grup Admin
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('news', \App\Http\Controllers\Admin\NewsController::class);
        Route::resource('tourism-destinations', \App\Http\Controllers\Admin\TourismDestinationController::class);
        Route::resource('cultures', \App\Http\Controllers\Admin\CultureController::class);
        Route::resource('creative-economies', \App\Http\Controllers\Admin\CreativeEconomyController::class);
        Route::resource('accommodations', \App\Http\Controllers\Admin\AccommodationController::class);
        Route::resource('culinary-places', \App\Http\Controllers\Admin\CulinaryPlaceController::class);
        Route::resource('galleries', \App\Http\Controllers\Admin\GalleryController::class);

        // Service Rakyat Inbox
        Route::get('/verifikasi-layanan', [\App\Http\Controllers\Admin\AdminLayananMasyarakatController::class, 'index'])->name('verifikasi-layanan.index');
        Route::patch('/verifikasi-layanan/{type}/{id}/status', [\App\Http\Controllers\Admin\AdminLayananMasyarakatController::class, 'updateStatus'])->name('verifikasi-layanan.update-status');
        Route::post('/verifikasi-layanan/{type}/{id}/clone', [\App\Http\Controllers\Admin\AdminLayananMasyarakatController::class, 'cloneToPublic'])->name('verifikasi-layanan.clone');

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

        // Manajemen Akun & User Management
        Route::get('/manajemen-akun', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('manajemen-akun');
        Route::post('users/{user}/reset-password', [\App\Http\Controllers\Admin\UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->except(['show']);
    });
});

require __DIR__.'/auth.php';
