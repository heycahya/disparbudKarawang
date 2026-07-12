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
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // Mendapatkan tren bulanan pengajuan baru dalam 6 bulan terakhir
        $trends = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthName = $month->translatedFormat('M Y');
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();

            $complaintsCount = Complaint::whereBetween('created_at', [$start, $end])->count();
            $tourismCount = TourismSubmission::whereBetween('created_at', [$start, $end])->count();
            $eventCount = EventBroadcastRequest::whereBetween('created_at', [$start, $end])->count();

            $trends[] = [
                'label' => $monthName,
                'complaints' => $complaintsCount,
                'tourism_submissions' => $tourismCount,
                'event_requests' => $eventCount,
            ];
        }

        $statistics = [
            'complaints' => [
                'total' => Complaint::count(),
                'masuk' => Complaint::where('status', 'masuk')->count(),
                'ditinjau' => Complaint::where('status', 'ditinjau')->count(),
                'disetujui' => Complaint::where('status', 'disetujui')->count(),
                'ditolak' => Complaint::where('status', 'ditolak')->count(),
            ],
            'tourism_submissions' => [
                'total' => TourismSubmission::count(),
                'masuk' => TourismSubmission::where('status', 'masuk')->count(),
                'ditinjau' => TourismSubmission::where('status', 'ditinjau')->count(),
                'disetujui' => TourismSubmission::where('status', 'disetujui')->count(),
                'ditolak' => TourismSubmission::where('status', 'ditolak')->count(),
            ],
            'event_requests' => [
                'total' => EventBroadcastRequest::count(),
                'masuk' => EventBroadcastRequest::where('status', 'masuk')->count(),
                'ditinjau' => EventBroadcastRequest::where('status', 'ditinjau')->count(),
                'disetujui' => EventBroadcastRequest::where('status', 'disetujui')->count(),
                'ditolak' => EventBroadcastRequest::where('status', 'ditolak')->count(),
            ],
            'news' => [
                'total' => News::count(),
                'published' => News::where('status', 'published')->count(),
                'draft' => News::where('status', 'draft')->count(),
                'views' => (int) News::sum('views'),
            ],
            'destinations' => [
                'total' => TourismDestination::count(),
                'published' => TourismDestination::where('status', 'published')->count(),
                'draft' => TourismDestination::where('status', 'draft')->count(),
                'views' => (int) TourismDestination::sum('views'),
            ],
            'web_visits' => (int) (News::sum('views') + TourismDestination::sum('views') + Culture::sum('views')),
            'trends' => $trends,
        ];

        return Inertia::render('Admin/Dashboard', [
            'statistics' => $statistics,
        ]);
    }
}
