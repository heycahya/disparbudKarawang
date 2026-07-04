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

        try {
            $response = $this->uploadApi->upload($request->file('cover_image')->getRealPath(), [
                'folder' => 'disparbud_karawang/ekraf'
            ]);
            $validated['cover_image'] = $response['secure_url'];
        } catch (\Exception $e) {
            return back()->withErrors(['cover_image' => 'Gagal mengunggah gambar. Silakan coba lagi.'])->withInput();
        }

        CreativeEconomy::create($validated);

        return redirect()->route('admin.creative-economies.index')
            ->with('success', 'Pelaku ekonomi kreatif berhasil ditambahkan.');
    }

    public function edit(CreativeEconomy $creativeEconomy)
    {
        return Inertia::render('Admin/Content/CreativeEconomy/Edit', [
            'item' => $creativeEconomy
        ]);
    }

    public function update(UpdateCreativeEconomyRequest $request, CreativeEconomy $creativeEconomy)
    {
        $validated = $request->validated();

        if ($request->hasFile('cover_image')) {
            try {
                $response = $this->uploadApi->upload($request->file('cover_image')->getRealPath(), [
                    'folder' => 'disparbud_karawang/ekraf'
                ]);
                $validated['cover_image'] = $response['secure_url'];
            } catch (\Exception $e) {
                return back()->withErrors(['cover_image' => 'Gagal mengunggah gambar. Silakan coba lagi.'])->withInput();
            }
        }

        if ($creativeEconomy->name !== $validated['name']) {
            $validated['slug'] = CreativeEconomy::generateUniqueSlug($validated['name'], $creativeEconomy->id);
        }

        $creativeEconomy->update($validated);

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
