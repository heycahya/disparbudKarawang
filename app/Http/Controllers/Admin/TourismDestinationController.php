<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TourismDestination;
use App\Models\TourismCategory;
use App\Http\Requests\StoreTourismDestinationRequest;
use App\Http\Requests\UpdateTourismDestinationRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Cloudinary\Api\Upload\UploadApi;
use App\Services\CloudinaryService;

class TourismDestinationController extends Controller
{
    protected $uploadApi;

    public function __construct(UploadApi $uploadApi)
    {
        $this->uploadApi = $uploadApi;
    }

    public function index(Request $request)
    {
        $query = TourismDestination::with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('tourism_category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $destinations = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('Admin/Content/Tourism/Index', [
            'destinations' => $destinations,
            'categories' => TourismCategory::orderBy('name')->get(),
            'filters' => $request->only(['search', 'category_id', 'status'])
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Content/Tourism/Create', [
            'categories' => TourismCategory::orderBy('name')->get()
        ]);
    }

    public function store(StoreTourismDestinationRequest $request)
    {
        $validated = $request->validated();
        $photosData = $validated['photos'] ?? null;
        $photoCaptions = $validated['photo_captions'] ?? null;
        unset($validated['photos'], $validated['photo_captions']);

        // Cloudinary Upload
        try {
            $response = $this->uploadApi->upload($request->file('cover_image')->getRealPath(), [
                'folder' => 'disparbud_karawang/tourism'
            ]);
            $validated['cover_image'] = CloudinaryService::getUrl($response['secure_url'] ?? null, 'tourism');
        } catch (\Exception $e) {
            return back()->withErrors(['cover_image' => 'Gagal mengunggah gambar. Silakan coba lagi.'])->withInput();
        }

        $destination = TourismDestination::create($validated);

        if ($photosData && is_array($photosData)) {
            foreach ($photosData as $i => $file) {
                try {
                    $uploadResponse = $this->uploadApi->upload($file->getRealPath(), [
                        'folder' => 'disparbud_karawang/tourism/gallery'
                    ]);
                    $destination->photos()->create([
                        'photo' => $uploadResponse['secure_url'],
                        'caption' => $photoCaptions[$i] ?? null,
                        'order' => $i
                    ]);
                } catch (\Exception $e) {
                    // Ignore single photo upload error
                }
            }
        }

        return redirect()->route('admin.tourism-destinations.index')
            ->with('success', 'Destinasi wisata berhasil ditambahkan.');
    }

    public function edit(TourismDestination $tourismDestination)
    {
        return Inertia::render('Admin/Content/Tourism/Edit', [
            'destination' => $tourismDestination->load('photos'),
            'categories' => TourismCategory::orderBy('name')->get()
        ]);
    }

    public function update(UpdateTourismDestinationRequest $request, TourismDestination $tourismDestination)
    {
        $validated = $request->validated();
        $photosData = $validated['photos'] ?? null;
        $photoCaptions = $validated['photo_captions'] ?? null;
        $deletedPhotoIds = $validated['deleted_photo_ids'] ?? null;
        unset($validated['photos'], $validated['photo_captions'], $validated['deleted_photo_ids']);

        if ($request->hasFile('cover_image')) {
            // Cloudinary Upload
            try {
                $response = $this->uploadApi->upload($request->file('cover_image')->getRealPath(), [
                    'folder' => 'disparbud_karawang/tourism'
                ]);
                $validated['cover_image'] = $response['secure_url'];
            } catch (\Exception $e) {
                return back()->withErrors(['cover_image' => 'Gagal mengunggah gambar. Silakan coba lagi.'])->withInput();
            }
        } else {
            unset($validated['cover_image']);
        }

        // Handle Slug update on name change
        if ($tourismDestination->name !== $validated['name']) {
            $validated['slug'] = TourismDestination::generateUniqueSlug($validated['name'], $tourismDestination->id);
        }

        $tourismDestination->update($validated);

        // Delete requested gallery photos
        if ($deletedPhotoIds && is_array($deletedPhotoIds)) {
            $tourismDestination->photos()->whereIn('id', $deletedPhotoIds)->delete();
        }

        // Save new gallery photos
        if ($photosData && is_array($photosData)) {
            $maxOrder = $tourismDestination->photos()->max('order') ?? -1;
            foreach ($photosData as $i => $file) {
                try {
                    $uploadResponse = $this->uploadApi->upload($file->getRealPath(), [
                        'folder' => 'disparbud_karawang/tourism/gallery'
                    ]);
                    $tourismDestination->photos()->create([
                        'photo' => $uploadResponse['secure_url'],
                        'caption' => $photoCaptions[$i] ?? null,
                        'order' => $maxOrder + 1 + $i
                    ]);
                } catch (\Exception $e) {
                    // Ignore single photo upload error
                }
            }
        }

        return redirect()->route('admin.tourism-destinations.index')
            ->with('success', 'Destinasi wisata berhasil diperbarui.');
    }

    public function destroy(TourismDestination $tourismDestination)
    {
        $tourismDestination->delete();
        return redirect()->route('admin.tourism-destinations.index')
            ->with('success', 'Destinasi wisata berhasil dihapus.');
    }
}
