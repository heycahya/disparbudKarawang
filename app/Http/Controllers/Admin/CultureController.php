<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Culture;
use App\Http\Requests\StoreCultureRequest;
use App\Http\Requests\UpdateCultureRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Cloudinary\Api\Upload\UploadApi;

class CultureController extends Controller
{
    protected $uploadApi;

    public function __construct(UploadApi $uploadApi)
    {
        $this->uploadApi = $uploadApi;
    }

    public function index(Request $request)
    {
        $query = Culture::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $cultures = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('Admin/Content/Culture/Index', [
            'cultures' => $cultures,
            'filters' => $request->only(['search', 'category', 'status'])
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Content/Culture/Create');
    }

    public function store(StoreCultureRequest $request)
    {
        $validated = $request->validated();
        $photosData = $validated['photos'] ?? null;
        $photoCaptions = $validated['photo_captions'] ?? null;
        unset($validated['photos'], $validated['photo_captions']);

        try {
            $response = $this->uploadApi->upload($request->file('cover_image')->getRealPath(), [
                'folder' => 'disparbud_karawang/culture'
            ]);
            $validated['cover_image'] = $response['secure_url'];
        } catch (\Exception $e) {
            return back()->withErrors(['cover_image' => 'Gagal mengunggah gambar. Silakan coba lagi.'])->withInput();
        }

        $culture = Culture::create($validated);

        if ($photosData && is_array($photosData)) {
            foreach ($photosData as $i => $file) {
                try {
                    $uploadResponse = $this->uploadApi->upload($file->getRealPath(), [
                        'folder' => 'disparbud_karawang/culture/gallery'
                    ]);
                    $culture->photos()->create([
                        'photo' => $uploadResponse['secure_url'],
                        'caption' => $photoCaptions[$i] ?? null,
                        'order' => $i
                    ]);
                } catch (\Exception $e) {
                    // Ignore single photo upload error
                }
            }
        }

        return redirect()->route('admin.cultures.index')
            ->with('success', 'Kebudayaan berhasil ditambahkan.');
    }

    public function edit(Culture $culture)
    {
        return Inertia::render('Admin/Content/Culture/Edit', [
            'culture' => $culture->load('photos')
        ]);
    }

    public function update(UpdateCultureRequest $request, Culture $culture)
    {
        $validated = $request->validated();
        $photosData = $validated['photos'] ?? null;
        $photoCaptions = $validated['photo_captions'] ?? null;
        $deletedPhotoIds = $validated['deleted_photo_ids'] ?? null;
        unset($validated['photos'], $validated['photo_captions'], $validated['deleted_photo_ids']);

        if ($request->hasFile('cover_image')) {
            try {
                $response = $this->uploadApi->upload($request->file('cover_image')->getRealPath(), [
                    'folder' => 'disparbud_karawang/culture'
                ]);
                $validated['cover_image'] = $response['secure_url'];
            } catch (\Exception $e) {
                return back()->withErrors(['cover_image' => 'Gagal mengunggah gambar. Silakan coba lagi.'])->withInput();
            }
        } else {
            unset($validated['cover_image']);
        }

        if ($culture->name !== $validated['name']) {
            $validated['slug'] = Culture::generateUniqueSlug($validated['name'], $culture->id);
        }

        $culture->update($validated);

        // Delete requested gallery photos
        if ($deletedPhotoIds && is_array($deletedPhotoIds)) {
            $culture->photos()->whereIn('id', $deletedPhotoIds)->delete();
        }

        // Save new gallery photos
        if ($photosData && is_array($photosData)) {
            $maxOrder = $culture->photos()->max('order') ?? -1;
            foreach ($photosData as $i => $file) {
                try {
                    $uploadResponse = $this->uploadApi->upload($file->getRealPath(), [
                        'folder' => 'disparbud_karawang/culture/gallery'
                    ]);
                    $culture->photos()->create([
                        'photo' => $uploadResponse['secure_url'],
                        'caption' => $photoCaptions[$i] ?? null,
                        'order' => $maxOrder + 1 + $i
                    ]);
                } catch (\Exception $e) {
                    // Ignore single photo upload error
                }
            }
        }

        return redirect()->route('admin.cultures.index')
            ->with('success', 'Kebudayaan berhasil diperbarui.');
    }

    public function destroy(Culture $culture)
    {
        $culture->delete();
        return redirect()->route('admin.cultures.index')
            ->with('success', 'Kebudayaan berhasil dihapus.');
    }
}
