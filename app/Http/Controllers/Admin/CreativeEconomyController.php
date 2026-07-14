<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CreativeEconomy;
use App\Http\Requests\StoreCreativeEconomyRequest;
use App\Http\Requests\UpdateCreativeEconomyRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Cloudinary\Api\Upload\UploadApi;

class CreativeEconomyController extends Controller
{
    protected $uploadApi;

    public function __construct(UploadApi $uploadApi)
    {
        $this->uploadApi = $uploadApi;
    }

    public function index(Request $request)
    {
        $query = CreativeEconomy::query();

        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $items = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('Admin/Content/CreativeEconomy/Index', [
            'items' => $items,
            'filters' => $request->only(['search'])
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Content/CreativeEconomy/Create');
    }

    public function store(StoreCreativeEconomyRequest $request)
    {
        $validated = $request->validated();
        $photosData = $validated['photos'] ?? null;
        $photoCaptions = $validated['photo_captions'] ?? null;
        unset($validated['photos'], $validated['photo_captions']);

        try {
            $response = $this->uploadApi->upload($request->file('cover_image')->getRealPath(), [
                'folder' => 'disparbud_karawang/ekraf'
            ]);
            $validated['cover_image'] = $response['secure_url'];
        } catch (\Exception $e) {
            return back()->withErrors(['cover_image' => 'Gagal mengunggah gambar. Silakan coba lagi.'])->withInput();
        }

        $ekraf = CreativeEconomy::create($validated);

        if ($photosData && is_array($photosData)) {
            foreach ($photosData as $i => $file) {
                try {
                    $uploadResponse = $this->uploadApi->upload($file->getRealPath(), [
                        'folder' => 'disparbud_karawang/ekraf/gallery'
                    ]);
                    $ekraf->photos()->create([
                        'photo' => $uploadResponse['secure_url'],
                        'caption' => $photoCaptions[$i] ?? null,
                        'order' => $i
                    ]);
                } catch (\Exception $e) {
                    // Ignore single photo upload error
                }
            }
        }

        return redirect()->route('admin.creative-economies.index')
            ->with('success', 'Pelaku ekonomi kreatif berhasil ditambahkan.');
    }

    public function edit(CreativeEconomy $creativeEconomy)
    {
        return Inertia::render('Admin/Content/CreativeEconomy/Edit', [
            'item' => $creativeEconomy->load('photos')
        ]);
    }

    public function update(UpdateCreativeEconomyRequest $request, CreativeEconomy $creativeEconomy)
    {
        $validated = $request->validated();
        $photosData = $validated['photos'] ?? null;
        $photoCaptions = $validated['photo_captions'] ?? null;
        $deletedPhotoIds = $validated['deleted_photo_ids'] ?? null;
        unset($validated['photos'], $validated['photo_captions'], $validated['deleted_photo_ids']);

        if ($request->hasFile('cover_image')) {
            try {
                $response = $this->uploadApi->upload($request->file('cover_image')->getRealPath(), [
                    'folder' => 'disparbud_karawang/ekraf'
                ]);
                $validated['cover_image'] = $response['secure_url'];
            } catch (\Exception $e) {
                return back()->withErrors(['cover_image' => 'Gagal mengunggah gambar. Silakan coba lagi.'])->withInput();
            }
        } else {
            unset($validated['cover_image']);
        }

        if ($creativeEconomy->name !== $validated['name']) {
            $validated['slug'] = CreativeEconomy::generateUniqueSlug($validated['name'], $creativeEconomy->id);
        }

        $creativeEconomy->update($validated);

        // Delete requested gallery photos
        if ($deletedPhotoIds && is_array($deletedPhotoIds)) {
            $creativeEconomy->photos()->whereIn('id', $deletedPhotoIds)->delete();
        }

        // Save new gallery photos
        if ($photosData && is_array($photosData)) {
            $maxOrder = $creativeEconomy->photos()->max('order') ?? -1;
            foreach ($photosData as $i => $file) {
                try {
                    $uploadResponse = $this->uploadApi->upload($file->getRealPath(), [
                        'folder' => 'disparbud_karawang/ekraf/gallery'
                    ]);
                    $creativeEconomy->photos()->create([
                        'photo' => $uploadResponse['secure_url'],
                        'caption' => $photoCaptions[$i] ?? null,
                        'order' => $maxOrder + 1 + $i
                    ]);
                } catch (\Exception $e) {
                    // Ignore single photo upload error
                }
            }
        }

        return redirect()->route('admin.creative-economies.index')
            ->with('success', 'Pelaku ekonomi kreatif berhasil diperbarui.');
    }

    public function destroy(CreativeEconomy $creativeEconomy)
    {
        $creativeEconomy->delete();
        return redirect()->route('admin.creative-economies.index')
            ->with('success', 'Pelaku ekonomi kreatif berhasil dihapus.');
    }
}
