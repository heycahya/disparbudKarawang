<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CulinaryPlace;
use App\Http\Requests\StoreCulinaryPlaceRequest;
use App\Http\Requests\UpdateCulinaryPlaceRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Cloudinary\Api\Upload\UploadApi;

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

        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $items = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('Admin/Content/CulinaryPlace/Index', [
            'items' => $items,
            'filters' => $request->only(['search'])
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Content/CulinaryPlace/Create');
    }

    public function store(StoreCulinaryPlaceRequest $request)
    {
        $validated = $request->validated();

        try {
            $response = $this->uploadApi->upload($request->file('cover_image')->getRealPath(), [
                'folder' => 'disparbud_karawang/culinary'
            ]);
            $validated['cover_image'] = $response['secure_url'];
        } catch (\Exception $e) {
            return back()->withErrors(['cover_image' => 'Gagal mengunggah gambar. Silakan coba lagi.'])->withInput();
        }

        CulinaryPlace::create($validated);

        return redirect()->route('admin.culinary-places.index')
            ->with('success', 'Tempat kuliner berhasil ditambahkan.');
    }

    public function edit(CulinaryPlace $culinaryPlace)
    {
        return Inertia::render('Admin/Content/CulinaryPlace/Edit', [
            'item' => $culinaryPlace
        ]);
    }

    public function update(UpdateCulinaryPlaceRequest $request, CulinaryPlace $culinaryPlace)
    {
        $validated = $request->validated();

        if ($request->hasFile('cover_image')) {
            try {
                $response = $this->uploadApi->upload($request->file('cover_image')->getRealPath(), [
                    'folder' => 'disparbud_karawang/culinary'
                ]);
                $validated['cover_image'] = $response['secure_url'];
            } catch (\Exception $e) {
                return back()->withErrors(['cover_image' => 'Gagal mengunggah gambar. Silakan coba lagi.'])->withInput();
            }
        }

        if ($culinaryPlace->name !== $validated['name']) {
            $validated['slug'] = CulinaryPlace::generateUniqueSlug($validated['name'], $culinaryPlace->id);
        }

        $culinaryPlace->update($validated);

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
