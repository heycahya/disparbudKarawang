<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\EventBroadcastRequest;
use App\Models\TourismSubmission;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserDashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        $complaints = Complaint::where('user_id', $userId)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'type' => 'complaint',
                    'type_label' => 'Pengaduan Masyarakat',
                    'title' => $item->subject,
                    'description' => $item->description,
                    'status' => $item->status,
                    'admin_note' => $item->admin_note,
                    'created_at' => $item->created_at ? $item->created_at->toISOString() : null,
                ];
            });

        $tourismSubmissions = TourismSubmission::where('user_id', $userId)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'type' => 'tourism_submission',
                    'type_label' => 'Usulan Wisata Baru',
                    'title' => $item->name,
                    'description' => $item->description,
                    'status' => $item->status,
                    'admin_note' => $item->admin_note,
                    'created_at' => $item->created_at ? $item->created_at->toISOString() : null,
                ];
            });

        $eventBroadcasts = EventBroadcastRequest::where('user_id', $userId)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'type' => 'event_broadcast',
                    'type_label' => 'Siaran Acara',
                    'title' => $item->event_name,
                    'description' => $item->description,
                    'status' => $item->status,
                    'admin_note' => $item->admin_note,
                    'created_at' => $item->created_at ? $item->created_at->toISOString() : null,
                ];
            });

        $serviceRequests = collect()
            ->concat($complaints)
            ->concat($tourismSubmissions)
            ->concat($eventBroadcasts)
            ->sortByDesc('created_at')
            ->values()
            ->all();

        return Inertia::render('Public/UserDashboard', [
            'service_requests' => $serviceRequests,
        ]);
    }
}
