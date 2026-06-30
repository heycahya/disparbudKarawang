<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\TourismSubmission;
use App\Models\EventBroadcastRequest;
use App\Http\Requests\Public\StoreComplaintRequest;
use App\Http\Requests\Public\StoreTourismSubmissionRequest;
use App\Http\Requests\Public\StoreEventBroadcastRequest;
use Cloudinary\Api\Upload\UploadApi;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ServiceRakyatController extends Controller
{
    protected $uploadApi;

    public function __construct(UploadApi $uploadApi)
    {
        $this->uploadApi = $uploadApi;
    }

    // 1. Pengaduan Masyarakat
    public function createComplaint()
    {
        return Inertia::render('Public/ServiceRakyat/ComplaintForm');
    }

    public function storeComplaint(StoreComplaintRequest $request)
    {
        $validated = $request->validated();
        $attachmentUrl = null;

        if ($request->hasFile('attachment')) {
            try {
                $response = $this->uploadApi->upload($request->file('attachment')->getRealPath(), [
                    'folder' => 'disparbud_karawang/complaints'
                ]);
                $attachmentUrl = $response['secure_url'];
            } catch (\Exception $e) {
                return back()->withErrors(['attachment' => 'Gagal mengunggah lampiran.'])->withInput();
            }
        }

        Complaint::create([
            'user_id' => auth()->id(),
            'subject' => $validated['title'],
            'description' => $validated['description'],
            'attachment' => $attachmentUrl,
            'status' => 'masuk'
        ]);

        return redirect()->route('public.home')
            ->with('success', 'Pengaduan Anda berhasil dikirim.');
    }

    // 2. Usulan Wisata
    public function createTourismSubmission()
    {
        return Inertia::render('Public/ServiceRakyat/TourismSubmissionForm');
    }

    public function storeTourismSubmission(StoreTourismSubmissionRequest $request)
    {
        $validated = $request->validated();
        $photoUrl = null;

        if ($request->hasFile('photos')) {
            try {
                foreach ($request->file('photos') as $photoFile) {
                    $response = $this->uploadApi->upload($photoFile->getRealPath(), [
                        'folder' => 'disparbud_karawang/submissions'
                    ]);
                    if (!$photoUrl) {
                        $photoUrl = $response['secure_url'];
                    }
                }
            } catch (\Exception $e) {
                return back()->withErrors(['photos' => 'Gagal mengunggah foto.'])->withInput();
            }
        }

        TourismSubmission::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'description' => $validated['description'],
            'address' => $validated['location'],
            'photo' => $photoUrl,
            'status' => 'masuk'
        ]);

        return redirect()->route('public.home')
            ->with('success', 'Usulan destinasi wisata berhasil dikirim.');
    }

    // 3. Permohonan Siaran Acara
    public function createEventBroadcast()
    {
        return Inertia::render('Public/ServiceRakyat/EventBroadcastForm');
    }

    public function storeEventBroadcast(StoreEventBroadcastRequest $request)
    {
        $validated = $request->validated();
        $proposalUrl = null;

        if ($request->hasFile('proposal')) {
            try {
                $response = $this->uploadApi->upload($request->file('proposal')->getRealPath(), [
                    'folder' => 'disparbud_karawang/proposals'
                ]);
                $proposalUrl = $response['secure_url'];
            } catch (\Exception $e) {
                return back()->withErrors(['proposal' => 'Gagal mengunggah proposal.'])->withInput();
            }
        }

        EventBroadcastRequest::create([
            'user_id' => auth()->id(),
            'organization' => $validated['organization'],
            'event_name' => $validated['event_name'],
            'event_date' => $validated['start_date'],
            'event_location' => $validated['event_location'],
            'description' => $validated['description'],
            'attachment' => $proposalUrl,
            'status' => 'masuk'
        ]);

        return redirect()->route('public.home')
            ->with('success', 'Permohonan siaran acara berhasil dikirim.');
    }
}
