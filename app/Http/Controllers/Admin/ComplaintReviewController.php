<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Http\Requests\UpdateServiceRakyatStatusRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ComplaintReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Complaint::with(['user', 'reviewedBy']);

        if ($request->has('search') && !empty($request->search)) {
            $query->where(function ($q) use ($request) {
                $q->where('subject', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function ($uq) use ($request) {
                      $uq->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        $complaints = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('Admin/ServiceRakyat/Complaints/Index', [
            'complaints' => $complaints,
            'filters' => $request->only(['search', 'status'])
        ]);
    }

    public function show(Complaint $complaint)
    {
        return Inertia::render('Admin/ServiceRakyat/Complaints/Show', [
            'complaint' => $complaint->load(['user', 'reviewedBy'])
        ]);
    }

    public function updateStatus(UpdateServiceRakyatStatusRequest $request, Complaint $complaint)
    {
        $validated = $request->validated();
        $validated['reviewed_by'] = Auth::id();
        $validated['reviewed_at'] = now();

        $complaint->update($validated);

        return back()->with('success', 'Status pengaduan berhasil diperbarui.');
    }
}
