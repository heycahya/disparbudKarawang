<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CulinaryPlace;
use App\Http\Requests\StoreCulinaryPlaceRequest;
use App\Http\Requests\UpdateCulinaryPlaceRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Cloudinary\Api\Upload\UploadApi;
use App\Services\CloudinaryService;

class CulinaryPlaceController extends Controller
{
    protected $uploadApi;

    public function __construct(UploadApi $uploadApi)
    {
        $this->uploadApi = $uploadApi;
    }

    public function index(Request $request)
    {
        $query = CulinaryPlace::query();

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

        return Inertia::render('Admin/Content/CulinaryPlace/Index', [
            'items' => $items,
            'filters' => $request->only(['search', 'type', 'status'])
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Content/CulinaryPlace/Create');
    }

    public function store(StoreCulinaryPlaceRequest $request)
    {
        $validated = $request->validated();
        $photosData = $validated['photos'] ?? null;
        $photoCaptions = $validated['photo_captions'] ?? null;
        unset($validated['photos'], $validated['photo_captions']);

        try {
            $response = $this->uploadApi->upload($request->file('cover_image')->getRealPath(), [
                'folder' => 'disparbud_karawang/culinary'
            ]);
            $validated['cover_image'] = CloudinaryService::getUrl($response['secure_url'] ?? null, 'culinary');
        } catch (\Exception $e) {
            return back()->withErrors(['cover_image' => 'Gagal mengunggah gambar. Silakan coba lagi.'])->withInput();
        }

        $culinary = CulinaryPlace::create($validated);

        if ($photosData && is_array($photosData)) {
            foreach ($photosData as $i => $file) {
                try {
                    $uploadResponse = $this->uploadApi->upload($file->getRealPath(), [
                        'folder' => 'disparbud_karawang/culinary/gallery'
                    ]);
                    $culinary->photos()->create([
                        'photo' => $uploadResponse['secure_url'],
                        'caption' => $photoCaptions[$i] ?? null,
                        'order' => $i
                    ]);
                } catch (\Exception $e) {
                    // Ignore single photo upload error
                }
            }
        }

        return redirect()->route('admin.culinary-places.index')
            ->with('success', 'Tempat kuliner berhasil ditambahkan.');
    }

    public function edit(CulinaryPlace $culinaryPlace)
    {
        return Inertia::render('Admin/Content/CulinaryPlace/Edit', [
            'item' => $culinaryPlace->load('photos')
        ]);
    }

    public function update(UpdateCulinaryPlaceRequest $request, CulinaryPlace $culinaryPlace)
    {
        $validated = $request->validated();
        $photosData = $validated['photos'] ?? null;
        $photoCaptions = $validated['photo_captions'] ?? null;
        $deletedPhotoIds = $validated['deleted_photo_ids'] ?? null;
        unset($validated['photos'], $validated['photo_captions'], $validated['deleted_photo_ids']);

        if ($request->hasFile('cover_image')) {
            try {
                $response = $this->uploadApi->upload($request->file('cover_image')->getRealPath(), [
                    'folder' => 'disparbud_karawang/culinary'
                ]);
                $validated['cover_image'] = $response['secure_url'];
            } catch (\Exception $e) {
                return back()->withErrors(['cover_image' => 'Gagal mengunggah gambar. Silakan coba lagi.'])->withInput();
            }
        } else {
            unset($validated['cover_image']);
        }

        if ($culinaryPlace->name !== $validated['name']) {
            $validated['slug'] = CulinaryPlace::generateUniqueSlug($validated['name'], $culinaryPlace->id);
        }

        $culinaryPlace->update($validated);

        // Delete requested gallery photos
        if ($deletedPhotoIds && is_array($deletedPhotoIds)) {
            $culinaryPlace->photos()->whereIn('id', $deletedPhotoIds)->delete();
        }

        // Save new gallery photos
        if ($photosData && is_array($photosData)) {
            $maxOrder = $culinaryPlace->photos()->max('order') ?? -1;
            foreach ($photosData as $i => $file) {
                try {
                    $uploadResponse = $this->uploadApi->upload($file->getRealPath(), [
                        'folder' => 'disparbud_karawang/culinary/gallery'
                    ]);
                    $culinaryPlace->photos()->create([
                        'photo' => $uploadResponse['secure_url'],
                        'caption' => $photoCaptions[$i] ?? null,
                        'order' => $maxOrder + 1 + $i
                    ]);
                } catch (\Exception $e) {
                    // Ignore single photo upload error
                }
            }
        }

        return redirect()->route('admin.culinary-places.index')
            ->with('success', 'Tempat kuliner berhasil diperbarui.');
    }

    public function destroy(CulinaryPlace $culinaryPlace)
    {
        $culinaryPlace->delete();
        return redirect()->route('admin.culinary-places.index')
            ->with('success', 'Tempat kuliner berhasil dihapus.');
    }
}
