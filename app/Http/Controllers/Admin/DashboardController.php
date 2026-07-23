<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\TourismSubmission;
use App\Models\EventBroadcastRequest;
use App\Models\News;
use App\Models\TourismDestination;
use App\Models\Culture;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Safely calculate total views sum for a given model class without throwing QueryException.
     */
    private function safeSumViews(string $modelClass): int
    {
        try {
            $instance = new $modelClass;
            if (Schema::hasColumn($instance->getTable(), 'views')) {
                return (int) $modelClass::sum('views');
            }
        } catch (\Throwable $e) {
            // Silence exception and fallback safely to 0
        }

        return 0;
    }

    public function index()
    {
        // Tren bulanan pengajuan baru (6 bulan terakhir)
        $trends = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthName = $month->translatedFormat('M Y');
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();

            $complaintsCount = Complaint::withoutGlobalScopes()->whereBetween('created_at', [$start, $end])->count();
            $tourismCount = TourismSubmission::withoutGlobalScopes()->whereBetween('created_at', [$start, $end])->count();
            $eventCount = EventBroadcastRequest::withoutGlobalScopes()->whereBetween('created_at', [$start, $end])->count();

            $trends[] = [
                'label' => $monthName,
                'complaints' => $complaintsCount,
                'tourism_submissions' => $tourismCount,
                'event_requests' => $eventCount,
            ];
        }

        $complaints = [
            'total' => Complaint::withoutGlobalScopes()->count(),
            'masuk' => Complaint::withoutGlobalScopes()->whereIn('status', ['masuk', 'pending'])->count(),
            'ditinjau' => Complaint::withoutGlobalScopes()->whereIn('status', ['ditinjau', 'diproses'])->count(),
            'disetujui' => Complaint::withoutGlobalScopes()->whereIn('status', ['disetujui', 'approved'])->count(),
            'ditolak' => Complaint::withoutGlobalScopes()->whereIn('status', ['ditolak', 'rejected'])->count(),
        ];

        $tourismSubmissions = [
            'total' => TourismSubmission::withoutGlobalScopes()->count(),
            'masuk' => TourismSubmission::withoutGlobalScopes()->whereIn('status', ['masuk', 'pending'])->count(),
            'ditinjau' => TourismSubmission::withoutGlobalScopes()->whereIn('status', ['ditinjau', 'diproses'])->count(),
            'disetujui' => TourismSubmission::withoutGlobalScopes()->whereIn('status', ['disetujui', 'approved'])->count(),
            'ditolak' => TourismSubmission::withoutGlobalScopes()->whereIn('status', ['ditolak', 'rejected'])->count(),
        ];

        $eventRequests = [
            'total' => EventBroadcastRequest::withoutGlobalScopes()->count(),
            'masuk' => EventBroadcastRequest::withoutGlobalScopes()->whereIn('status', ['masuk', 'pending'])->count(),
            'ditinjau' => EventBroadcastRequest::withoutGlobalScopes()->whereIn('status', ['ditinjau', 'diproses'])->count(),
            'disetujui' => EventBroadcastRequest::withoutGlobalScopes()->whereIn('status', ['disetujui', 'approved'])->count(),
            'ditolak' => EventBroadcastRequest::withoutGlobalScopes()->whereIn('status', ['ditolak', 'rejected'])->count(),
        ];

        $statistics = [
            'complaints' => $complaints,
            'tourism_submissions' => $tourismSubmissions,
            'event_requests' => $eventRequests,
            'layanan_summary' => [
                'total' => $complaints['total'] + $tourismSubmissions['total'] + $eventRequests['total'],
                'pending' => $complaints['masuk'] + $tourismSubmissions['masuk'] + $eventRequests['masuk'],
                'ditinjau' => $complaints['ditinjau'] + $tourismSubmissions['ditinjau'] + $eventRequests['ditinjau'],
                'disetujui' => $complaints['disetujui'] + $tourismSubmissions['disetujui'] + $eventRequests['disetujui'],
                'ditolak' => $complaints['ditolak'] + $tourismSubmissions['ditolak'] + $eventRequests['ditolak'],
            ],
            'news' => [
                'total' => News::count(),
                'published' => News::where('status', 'published')->count(),
                'draft' => News::where('status', 'draft')->count(),
                'views' => $this->safeSumViews(News::class),
            ],
            'destinations' => [
                'total' => TourismDestination::count(),
                'published' => TourismDestination::where('status', 'published')->count(),
                'draft' => TourismDestination::where('status', 'draft')->count(),
                'views' => $this->safeSumViews(TourismDestination::class),
            ],
            'web_visits' => $this->safeSumViews(News::class)
                + $this->safeSumViews(TourismDestination::class)
                + $this->safeSumViews(Culture::class)
                + $this->safeSumViews(\App\Models\CreativeEconomy::class)
                + $this->safeSumViews(\App\Models\Accommodation::class)
                + $this->safeSumViews(\App\Models\CulinaryPlace::class),
            'trends' => $trends,
        ];

        return Inertia::render('Admin/Dashboard', [
            'statistics' => $statistics,
        ]);
    }
}
