<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TourismSubmission;
use App\Models\TourismDestination;
use App\Models\TourismCategory;
use App\Http\Requests\UpdateServiceRakyatStatusRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class TourismSubmissionReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = TourismSubmission::with(['user', 'reviewedBy', 'convertedDestination']);

        if ($request->has('search') && !empty($request->search)) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function ($uq) use ($request) {
                      $uq->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        $submissions = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('Admin/ServiceRakyat/TourismSubmissions/Index', [
            'submissions' => $submissions,
            'filters' => $request->only(['search', 'status'])
        ]);
    }

    public function show(TourismSubmission $tourism_submission)
    {
        return Inertia::render('Admin/ServiceRakyat/TourismSubmissions/Show', [
            'submission' => $tourism_submission->load(['user', 'reviewedBy', 'convertedDestination'])
        ]);
    }

    public function updateStatus(UpdateServiceRakyatStatusRequest $request, TourismSubmission $tourism_submission)
    {
        $validated = $request->validated();
        $validated['reviewed_by'] = Auth::id();
        $validated['reviewed_at'] = now();

        DB::transaction(function () use ($tourism_submission, $validated) {
            $tourism_submission->update($validated);

            if ($validated['status'] === 'disetujui' && !$tourism_submission->converted_destination_id) {
                // Get or create category
                $category = TourismCategory::firstOrCreate(
                    ['slug' => 'wisata-alam'],
                    ['name' => 'Wisata Alam']
                );

                $destination = TourismDestination::create([
                    'tourism_category_id' => $category->id,
                    'name' => $tourism_submission->name,
                    'description' => $tourism_submission->description,
                    'address' => $tourism_submission->address,
                    'latitude' => $tourism_submission->latitude,
                    'longitude' => $tourism_submission->longitude,
                    'cover_image' => $tourism_submission->photo,
                    'status' => 'draft', // status awal draft
                    'views' => 0,
                ]);

                $tourism_submission->update([
                    'converted_destination_id' => $destination->id
                ]);
            }
        });

        return back()->with('success', 'Status pengajuan wisata berhasil diperbarui.');
    }
}
