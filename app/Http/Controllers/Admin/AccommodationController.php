<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Http\Requests\StoreAccommodationRequest;
use App\Http\Requests\UpdateAccommodationRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Cloudinary\Api\Upload\UploadApi;

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

        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $items = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('Admin/Content/Accommodation/Index', [
            'items' => $items,
            'filters' => $request->only(['search'])
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Content/Accommodation/Create');
    }

    public function store(StoreAccommodationRequest $request)
    {
        $validated = $request->validated();

        try {
            $response = $this->uploadApi->upload($request->file('cover_image')->getRealPath(), [
                'folder' => 'disparbud_karawang/accommodation'
            ]);
            $validated['cover_image'] = $response['secure_url'];
        } catch (\Exception $e) {
            return back()->withErrors(['cover_image' => 'Gagal mengunggah gambar. Silakan coba lagi.'])->withInput();
        }

        Accommodation::create($validated);

        return redirect()->route('admin.accommodations.index')
            ->with('success', 'Akomodasi berhasil ditambahkan.');
    }

    public function edit(Accommodation $accommodation)
    {
        return Inertia::render('Admin/Content/Accommodation/Edit', [
            'item' => $accommodation
        ]);
    }

    public function update(UpdateAccommodationRequest $request, Accommodation $accommodation)
    {
        $validated = $request->validated();

        if ($request->hasFile('cover_image')) {
            try {
                $response = $this->uploadApi->upload($request->file('cover_image')->getRealPath(), [
                    'folder' => 'disparbud_karawang/accommodation'
                ]);
                $validated['cover_image'] = $response['secure_url'];
            } catch (\Exception $e) {
                return back()->withErrors(['cover_image' => 'Gagal mengunggah gambar. Silakan coba lagi.'])->withInput();
            }
        }

        if ($accommodation->name !== $validated['name']) {
            $validated['slug'] = Accommodation::generateUniqueSlug($validated['name'], $accommodation->id);
        }

        $accommodation->update($validated);

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
