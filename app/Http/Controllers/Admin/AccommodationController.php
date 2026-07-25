<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Http\Requests\StoreAccommodationRequest;
use App\Http\Requests\UpdateAccommodationRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Cloudinary\Api\Upload\UploadApi;
use App\Services\CloudinaryService;

class AccommodationController extends Controller
{
    protected $uploadApi;

    public function __construct(UploadApi $uploadApi)
    {
        $this->uploadApi = $uploadApi;
    }

    public function index(Request $request)
    {
        $query = Accommodation::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $items = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('Admin/Content/Accommodation/Index', [
            'items' => $items,
            'filters' => $request->only(['search', 'type', 'status'])
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Content/Accommodation/Create');
    }

    public function store(StoreAccommodationRequest $request)
    {
        $validated = $request->validated();
        $photosData = $validated['photos'] ?? null;
        $photoCaptions = $validated['photo_captions'] ?? null;
        unset($validated['photos'], $validated['photo_captions']);

        try {
            $response = $this->uploadApi->upload($request->file('cover_image')->getRealPath(), [
                'folder' => 'disparbud_karawang/accommodation'
            ]);
            $validated['cover_image'] = CloudinaryService::getUrl($response['secure_url'] ?? null, 'accommodation');
        } catch (\Exception $e) {
            return back()->withErrors(['cover_image' => 'Gagal mengunggah gambar. Silakan coba lagi.'])->withInput();
        }

        $accommodation = Accommodation::create($validated);

        if ($photosData && is_array($photosData)) {
            foreach ($photosData as $i => $file) {
                try {
                    $uploadResponse = $this->uploadApi->upload($file->getRealPath(), [
                        'folder' => 'disparbud_karawang/accommodation/gallery'
                    ]);
                    $accommodation->photos()->create([
                        'photo' => $uploadResponse['secure_url'],
                        'caption' => $photoCaptions[$i] ?? null,
                        'order' => $i
                    ]);
                } catch (\Exception $e) {
                    // Ignore single photo upload error
                }
            }
        }

        return redirect()->route('admin.accommodations.index')
            ->with('success', 'Akomodasi berhasil ditambahkan.');
    }

    public function edit(Accommodation $accommodation)
    {
        return Inertia::render('Admin/Content/Accommodation/Edit', [
            'item' => $accommodation->load('photos')
        ]);
    }

    public function update(UpdateAccommodationRequest $request, Accommodation $accommodation)
    {
        $validated = $request->validated();
        $photosData = $validated['photos'] ?? null;
        $photoCaptions = $validated['photo_captions'] ?? null;
        $deletedPhotoIds = $validated['deleted_photo_ids'] ?? null;
        unset($validated['photos'], $validated['photo_captions'], $validated['deleted_photo_ids']);

        if ($request->hasFile('cover_image')) {
            try {
                $response = $this->uploadApi->upload($request->file('cover_image')->getRealPath(), [
                    'folder' => 'disparbud_karawang/accommodation'
                ]);
                $validated['cover_image'] = $response['secure_url'];
            } catch (\Exception $e) {
                return back()->withErrors(['cover_image' => 'Gagal mengunggah gambar. Silakan coba lagi.'])->withInput();
            }
        } else {
            unset($validated['cover_image']);
        }

        if ($accommodation->name !== $validated['name']) {
            $validated['slug'] = Accommodation::generateUniqueSlug($validated['name'], $accommodation->id);
        }

        $accommodation->update($validated);

        // Delete requested gallery photos
        if ($deletedPhotoIds && is_array($deletedPhotoIds)) {
            $accommodation->photos()->whereIn('id', $deletedPhotoIds)->delete();
        }

        // Save new gallery photos
        if ($photosData && is_array($photosData)) {
            $maxOrder = $accommodation->photos()->max('order') ?? -1;
            foreach ($photosData as $i => $file) {
                try {
                    $uploadResponse = $this->uploadApi->upload($file->getRealPath(), [
                        'folder' => 'disparbud_karawang/accommodation/gallery'
                    ]);
                    $accommodation->photos()->create([
                        'photo' => $uploadResponse['secure_url'],
                        'caption' => $photoCaptions[$i] ?? null,
                        'order' => $maxOrder + 1 + $i
                    ]);
                } catch (\Exception $e) {
                    // Ignore single photo upload error
                }
            }
        }

        return redirect()->route('admin.accommodations.index')
            ->with('success', 'Akomodasi berhasil diperbarui.');
    }

    public function destroy(Accommodation $accommodation)
    {
        $accommodation->delete();
        return redirect()->route('admin.accommodations.index')
            ->with('success', 'Akomodasi berhasil dihapus.');
    }
}
