<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\TourismSubmission;
use App\Models\EventBroadcastRequest;
use App\Http\Requests\Public\StoreComplaintRequest;
use App\Http\Requests\Public\StoreTourismSubmissionRequest;
use App\Http\Requests\Public\StoreEventBroadcastRequest;
use Cloudinary\Api\Upload\UploadApi;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class LayananMasyarakatController extends Controller
{
    /**
     * Helper method to upload file to Cloudinary with fallback to Local Storage.
     */
    private function uploadFile(UploadedFile $file, string $folder = 'submissions'): string
    {
        $hasCloudinaryConfig = (!empty(env('CLOUDINARY_API_SECRET')) && !empty(env('CLOUDINARY_CLOUD_NAME'))) 
            || (!empty(config('cloudinary.secret')) && !empty(config('cloudinary.cloud_name')))
            || app()->environment('testing');

        if ($hasCloudinaryConfig) {
            try {
                $uploadApi = app(UploadApi::class);
                $response = $uploadApi->upload($file->getRealPath(), [
                    'folder' => 'disparbud_karawang/' . $folder
                ]);
                if (isset($response['secure_url']) && !empty($response['secure_url'])) {
                    return (string) $response['secure_url'];
                }
            } catch (\Throwable $e) {
                // Fallback to local storage if Cloudinary throws any error or TypeError
            }
        }

        // Local Storage Fallback
        $path = $file->store($folder, 'public');
        return Storage::url($path);
    }

    // 1. Pengaduan Masyarakat
    public function createComplaint()
    {
        return Inertia::render('Public/LayananMasyarakat/ComplaintForm');
    }

    public function storeComplaint(StoreComplaintRequest $request)
    {
        $validated = $request->validated();
        $attachmentUrl = null;

        if ($request->hasFile('attachment')) {
            try {
                $attachmentUrl = $this->uploadFile($request->file('attachment'), 'complaints');
            } catch (\Throwable $e) {
                return back()->withErrors(['attachment' => 'Gagal mengunggah lampiran.'])->withInput();
            }
        }

        Complaint::create([
            'user_id' => auth()->id(),
            'subject' => $validated['title'],
            'category' => $validated['category'] ?? null,
            'location' => $validated['location'] ?? null,
            'description' => $validated['description'],
            'attachment' => $attachmentUrl,
            'status' => 'masuk'
        ]);

        return redirect()->route('public.history.index')
            ->with('success', 'Pengaduan Anda berhasil dikirim.');
    }

    // 2. Usulan Wisata
    public function createTourismSubmission()
    {
        return Inertia::render('Public/LayananMasyarakat/TourismSubmissionForm');
    }

    public function storeTourismSubmission(StoreTourismSubmissionRequest $request)
    {
        $validated = $request->validated();
        $photoUrl = null;

        if ($request->hasFile('photos')) {
            try {
                foreach ($request->file('photos') as $photoFile) {
                    $url = $this->uploadFile($photoFile, 'submissions');
                    if (!$photoUrl) {
                        $photoUrl = $url;
                    }
                }
            } catch (\Throwable $e) {
                return back()->withErrors(['photos' => 'Gagal mengunggah foto.'])->withInput();
            }
        }

        TourismSubmission::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'category' => $validated['category'] ?? null,
            'description' => $validated['description'],
            'address' => $validated['location'],
            'contact' => $validated['contact'] ?? null,
            'operating_hours' => $validated['operating_hours'] ?? null,
            'ticket_price' => $validated['ticket_price'] ?? null,
            'photo' => $photoUrl,
            'status' => 'masuk'
        ]);

        return redirect()->route('public.history.index')
            ->with('success', 'Usulan destinasi wisata berhasil dikirim.');
    }

    // 3. Permohonan Siaran Acara
    public function createEventBroadcast()
    {
        return Inertia::render('Public/LayananMasyarakat/EventBroadcastForm');
    }

    public function storeEventBroadcast(StoreEventBroadcastRequest $request)
    {
        $validated = $request->validated();
        $proposalUrl = null;

        if ($request->hasFile('proposal')) {
            try {
                $proposalUrl = $this->uploadFile($request->file('proposal'), 'proposals');
            } catch (\Throwable $e) {
                return back()->withErrors(['proposal' => 'Gagal mengunggah proposal.'])->withInput();
            }
        }

        EventBroadcastRequest::create([
            'user_id' => auth()->id(),
            'organization' => $validated['organization'],
            'event_name' => $validated['event_name'],
            'event_date' => $validated['start_date'],
            'end_date' => $validated['end_date'] ?? null,
            'event_location' => $validated['event_location'],
            'description' => $validated['description'],
            'target_audience' => $validated['target_audience'] ?? null,
            'attachment' => $proposalUrl,
            'status' => 'masuk'
        ]);

        return redirect()->route('public.history.index')
            ->with('success', 'Permohonan siaran acara berhasil dikirim.');
    }
}
