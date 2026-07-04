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

        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $cultures = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('Admin/Content/Culture/Index', [
            'cultures' => $cultures,
            'filters' => $request->only(['search'])
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Content/Culture/Create');
    }

    public function store(StoreCultureRequest $request)
    {
        $validated = $request->validated();

        try {
            $response = $this->uploadApi->upload($request->file('cover_image')->getRealPath(), [
                'folder' => 'disparbud_karawang/culture'
            ]);
            $validated['cover_image'] = $response['secure_url'];
        } catch (\Exception $e) {
            return back()->withErrors(['cover_image' => 'Gagal mengunggah gambar. Silakan coba lagi.'])->withInput();
        }

        Culture::create($validated);

        return redirect()->route('admin.cultures.index')
            ->with('success', 'Kebudayaan berhasil ditambahkan.');
    }

    public function edit(Culture $culture)
    {
        return Inertia::render('Admin/Content/Culture/Edit', [
            'culture' => $culture
        ]);
    }

    public function update(UpdateCultureRequest $request, Culture $culture)
    {
        $validated = $request->validated();

        if ($request->hasFile('cover_image')) {
            try {
                $response = $this->uploadApi->upload($request->file('cover_image')->getRealPath(), [
                    'folder' => 'disparbud_karawang/culture'
                ]);
                $validated['cover_image'] = $response['secure_url'];
            } catch (\Exception $e) {
                return back()->withErrors(['cover_image' => 'Gagal mengunggah gambar. Silakan coba lagi.'])->withInput();
            }
        }

        if ($culture->name !== $validated['name']) {
            $validated['slug'] = Culture::generateUniqueSlug($validated['name'], $culture->id);
        }

        $culture->update($validated);

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
