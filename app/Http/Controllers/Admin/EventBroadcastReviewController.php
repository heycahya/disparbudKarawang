<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventBroadcastRequest;
use App\Models\News;
use App\Models\NewsCategory;
use App\Http\Requests\UpdateServiceRakyatStatusRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class EventBroadcastReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = EventBroadcastRequest::with(['user', 'reviewedBy', 'convertedNews']);

        if ($request->has('search') && !empty($request->search)) {
            $query->where(function ($q) use ($request) {
                $q->where('event_name', 'like', '%' . $request->search . '%')
                  ->orWhere('organization', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function ($uq) use ($request) {
                      $uq->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        $event_broadcasts = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('Admin/ServiceRakyat/EventBroadcasts/Index', [
            'eventBroadcasts' => $event_broadcasts,
            'filters' => $request->only(['search', 'status'])
        ]);
    }

    public function show(EventBroadcastRequest $event_broadcast)
    {
        return Inertia::render('Admin/ServiceRakyat/EventBroadcasts/Show', [
            'eventBroadcast' => $event_broadcast->load(['user', 'reviewedBy', 'convertedNews'])
        ]);
    }

    public function updateStatus(UpdateServiceRakyatStatusRequest $request, EventBroadcastRequest $event_broadcast)
    {
        $validated = $request->validated();
        $validated['reviewed_by'] = Auth::id();
        $validated['reviewed_at'] = now();

        DB::transaction(function () use ($event_broadcast, $validated) {
            $event_broadcast->update($validated);

            if ($validated['status'] === 'disetujui' && !$event_broadcast->converted_news_id) {
                // Get or create Event category
                $category = NewsCategory::firstOrCreate(
                    ['slug' => 'event'],
                    ['name' => 'Event']
                );

                $news = News::create([
                    'user_id' => Auth::id(),
                    'news_category_id' => $category->id,
                    'title' => $event_broadcast->event_name,
                    'content' => $event_broadcast->description,
                    'thumbnail' => $event_broadcast->attachment,
                    'status' => 'draft', // status awal draft
                    'views' => 0,
                ]);

                $event_broadcast->update([
                    'converted_news_id' => $news->id
                ]);
            }
        });

        return back()->with('success', 'Status permohonan publikasi event berhasil diperbarui.');
    }
}
