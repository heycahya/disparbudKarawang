<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\TourismSubmission;
use App\Models\EventBroadcastRequest;
use App\Models\TourismDestination;
use App\Models\TourismCategory;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class AdminLayananMasyarakatController extends Controller
{
    /**
     * Display unified list of public service requests for admin review.
     */
    public function index(Request $request): Response
    {
        $type = $request->query('type', 'all');
        $status = $request->query('status', 'all');
        $search = $request->query('search', '');

        // 1. Fetch ALL records for the requested $type to compute stable stat counters
        $allComplaints = in_array($type, ['all', 'complaint'])
            ? Complaint::withoutGlobalScopes()->with('user')->latest()->get()
            : collect();

        $allSubmissions = in_array($type, ['all', 'tourism_submission'])
            ? TourismSubmission::withoutGlobalScopes()->with('user')->latest()->get()
            : collect();

        $allEvents = in_array($type, ['all', 'event_broadcast'])
            ? EventBroadcastRequest::withoutGlobalScopes()->with('user')->latest()->get()
            : collect();

        // 2. Map raw records to unified array structure
        $mappedComplaints = $allComplaints->map(fn($c) => [
            'id' => $c->id,
            'type' => 'complaint',
            'type_label' => 'Laporan Pengaduan',
            'title' => $c->subject,
            'description' => $c->description,
            'location' => $c->location,
            'organization' => null,
            'event_date' => null,
            'attachment' => $c->attachment,
            'status' => $c->status ?? 'masuk',
            'admin_note' => $c->admin_note,
            'user' => [
                'name' => $c->user?->name ?? 'Guest',
                'email' => $c->user?->email ?? '-',
            ],
            'created_at' => $c->created_at ? $c->created_at->format('Y-m-d H:i') : null,
            'reviewed_at' => $c->reviewed_at ? $c->reviewed_at->format('Y-m-d H:i') : null,
            'converted_id' => null,
        ]);

        $mappedSubmissions = $allSubmissions->map(fn($ts) => [
            'id' => $ts->id,
            'type' => 'tourism_submission',
            'type_label' => 'Usulan Wisata',
            'title' => $ts->name,
            'description' => $ts->description,
            'location' => $ts->address,
            'organization' => null,
            'event_date' => null,
            'attachment' => $ts->photo,
            'status' => $ts->status ?? 'masuk',
            'admin_note' => $ts->admin_note,
            'user' => [
                'name' => $ts->user?->name ?? 'Guest',
                'email' => $ts->user?->email ?? '-',
            ],
            'created_at' => $ts->created_at ? $ts->created_at->format('Y-m-d H:i') : null,
            'reviewed_at' => $ts->reviewed_at ? $ts->reviewed_at->format('Y-m-d H:i') : null,
            'converted_id' => $ts->converted_destination_id,
        ]);

        $mappedEvents = $allEvents->map(fn($eb) => [
            'id' => $eb->id,
            'type' => 'event_broadcast',
            'type_label' => 'Pengajuan Event',
            'title' => $eb->event_name,
            'description' => $eb->description,
            'location' => $eb->event_location,
            'organization' => $eb->organization,
            'event_date' => $eb->event_date ? $eb->event_date->format('Y-m-d') : null,
            'attachment' => $eb->attachment,
            'status' => $eb->status ?? 'masuk',
            'admin_note' => $eb->admin_note,
            'user' => [
                'name' => $eb->user?->name ?? 'Guest',
                'email' => $eb->user?->email ?? '-',
            ],
            'created_at' => $eb->created_at ? $eb->created_at->format('Y-m-d H:i') : null,
            'reviewed_at' => $eb->reviewed_at ? $eb->reviewed_at->format('Y-m-d H:i') : null,
            'converted_id' => $eb->converted_news_id,
        ]);

        $allUnfilteredItems = $mappedComplaints->concat($mappedSubmissions)->concat($mappedEvents)->sortByDesc('created_at')->values();

        // 3. Compute stable stats from ALL items (unaffected by status or search filter)
        $stats = [
            'total' => $allUnfilteredItems->count(),
            'masuk' => $allUnfilteredItems->filter(fn($i) => in_array($i['status'], ['masuk', 'pending']))->count(),
            'ditinjau' => $allUnfilteredItems->filter(fn($i) => in_array($i['status'], ['ditinjau', 'diproses']))->count(),
            'disetujui' => $allUnfilteredItems->filter(fn($i) => in_array($i['status'], ['disetujui', 'approved']))->count(),
            'ditolak' => $allUnfilteredItems->filter(fn($i) => in_array($i['status'], ['ditolak', 'rejected']))->count(),
        ];

        // 4. Filter items for table display based on $status and $search
        $filteredItems = $allUnfilteredItems->filter(function ($item) use ($status, $search) {
            // Status filter with aliases support
            if ($status !== 'all') {
                $statusGroup = match ($status) {
                    'masuk', 'pending' => ['masuk', 'pending'],
                    'ditinjau', 'diproses' => ['ditinjau', 'diproses'],
                    'disetujui', 'approved' => ['disetujui', 'approved'],
                    'ditolak', 'rejected' => ['ditolak', 'rejected'],
                    default => [$status],
                };
                if (!in_array($item['status'], $statusGroup)) {
                    return false;
                }
            }

            // Search filter
            if (!empty($search)) {
                $searchTerm = mb_strtolower($search);
                $titleMatch = Str::contains(mb_strtolower($item['title']), $searchTerm);
                $descMatch = Str::contains(mb_strtolower($item['description']), $searchTerm);
                $userMatch = Str::contains(mb_strtolower($item['user']['name']), $searchTerm) || Str::contains(mb_strtolower($item['user']['email']), $searchTerm);
                $locationMatch = $item['location'] ? Str::contains(mb_strtolower($item['location']), $searchTerm) : false;

                if (!$titleMatch && !$descMatch && !$userMatch && !$locationMatch) {
                    return false;
                }
            }

            return true;
        })->values();

        return Inertia::render('Admin/LayananMasyarakat/Index', [
            'items' => $filteredItems,
            'stats' => $stats,
            'filters' => [
                'type' => $type,
                'status' => $status,
                'search' => $search,
            ],
        ]);
    }

    /**
     * Update review status and admin notes for a service request.
     */
    public function updateStatus(Request $request, string $type, int $id)
    {
        $request->validate([
            'status' => 'required|string|in:masuk,pending,ditinjau,diproses,disetujui,approved,ditolak,rejected',
            'admin_note' => 'nullable|string|max:1000',
        ]);

        $model = match ($type) {
            'complaint' => Complaint::withoutGlobalScopes()->findOrFail($id),
            'tourism_submission' => TourismSubmission::withoutGlobalScopes()->findOrFail($id),
            'event_broadcast' => EventBroadcastRequest::withoutGlobalScopes()->findOrFail($id),
            default => abort(404, 'Jenis layanan tidak valid.'),
        };

        $model->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Status pengajuan masyarakat berhasil diperbarui.');
    }

    /**
     * Clone approved tourism submission or event broadcast into public catalog content.
     */
    public function cloneToPublic(Request $request, string $type, int $id)
    {
        if ($type === 'tourism_submission') {
            $submission = TourismSubmission::withoutGlobalScopes()->findOrFail($id);
            $category = TourismCategory::first();

            $destination = TourismDestination::create([
                'name' => $submission->name,
                'slug' => Str::slug($submission->name) . '-' . time(),
                'tourism_category_id' => $category?->id ?? 1,
                'description' => $submission->description,
                'address' => $submission->address ?? 'Kabupaten Karawang',
                'status' => 'draft',
            ]);

            $submission->update([
                'converted_destination_id' => $destination->id,
                'status' => 'disetujui',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);

            return redirect()->route('admin.tourism-destinations.edit', $destination->id)
                ->with('success', 'Usulan wisata berhasil dikloning ke Destinasi Wisata Publik (Status: Draft). Silakan lengkapi detailnya.');
        }

        if ($type === 'event_broadcast') {
            $broadcast = EventBroadcastRequest::withoutGlobalScopes()->findOrFail($id);
            $newsCategory = NewsCategory::first();

            $news = News::create([
                'title' => $broadcast->event_name,
                'slug' => Str::slug($broadcast->event_name) . '-' . time(),
                'news_category_id' => $newsCategory?->id ?? 1,
                'content' => "<p><strong>Penyelenggara:</strong> {$broadcast->organization}</p><p><strong>Lokasi:</strong> {$broadcast->event_location}</p><p>{$broadcast->description}</p>",
                'user_id' => auth()->id(),
                'status' => 'draft',
            ]);

            $broadcast->update([
                'converted_news_id' => $news->id,
                'status' => 'disetujui',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);

            return redirect()->route('admin.news.edit', $news->id)
                ->with('success', 'Pengajuan event berhasil dikloning ke Berita & Event Publik (Status: Draft). Silakan lengkapi detailnya.');
        }

        return redirect()->back()->with('error', 'Jenis pengajuan ini tidak dapat dikloning ke katalog publik.');
    }
}
