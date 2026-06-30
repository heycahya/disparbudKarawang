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

        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $destinations = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('Admin/Content/Tourism/Index', [
            'destinations' => $destinations,
            'filters' => $request->only(['search'])
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

        // Cloudinary Upload
        try {
            $response = $this->uploadApi->upload($request->file('cover_image')->getRealPath(), [
                'folder' => 'disparbud_karawang/tourism'
            ]);
            $validated['cover_image'] = $response['secure_url'];
        } catch (\Exception $e) {
            return back()->withErrors(['cover_image' => 'Gagal mengunggah gambar. Silakan coba lagi.'])->withInput();
        }

        TourismDestination::create($validated);

        return redirect()->route('admin.tourism-destinations.index')
            ->with('success', 'Destinasi wisata berhasil ditambahkan.');
    }

    public function edit(TourismDestination $tourismDestination)
    {
        return Inertia::render('Admin/Content/Tourism/Edit', [
            'destination' => $tourismDestination,
            'categories' => TourismCategory::orderBy('name')->get()
        ]);
    }

    public function update(UpdateTourismDestinationRequest $request, TourismDestination $tourismDestination)
    {
        $validated = $request->validated();

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
        }

        // Handle Slug update on name change
        if ($tourismDestination->name !== $validated['name']) {
            $validated['slug'] = TourismDestination::generateUniqueSlug($validated['name'], $tourismDestination->id);
        }

        $tourismDestination->update($validated);

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
