<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Gallery;
use App\Services\CloudinaryService;
use App\Http\Requests\StoreGalleryRequest;
use App\Http\Requests\UpdateGalleryRequest;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Gallery::with('user');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $galleries = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('Admin/Galleries/Index', [
            'galleries' => $galleries,
            'filters' => $request->only(['search', 'category']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Galleries/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGalleryRequest $request, CloudinaryService $cloudinary)
    {
        $validated = $request->validated();
        $fileUrl = null;

        if ($request->hasFile('media')) {
            try {
                $rawUrl = $cloudinary->upload($request->file('media')->getRealPath(), 'galleries');
                $fileUrl = CloudinaryService::getUrl($rawUrl, 'gallery');
            } catch (\Exception $e) {
                return back()->withErrors(['media' => 'Gagal mengunggah file ke cloud storage: ' . $e->getMessage()])->withInput();
            }
        }

        Gallery::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'category' => $validated['category'],
            'photo' => $fileUrl,
        ]);

        return redirect()->route('admin.galleries.index')->with('success', 'Galeri berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gallery $gallery)
    {
        return Inertia::render('Admin/Galleries/Edit', [
            'gallery' => $gallery
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGalleryRequest $request, Gallery $gallery, CloudinaryService $cloudinary)
    {
        $validated = $request->validated();

        if ($request->hasFile('media')) {
            try {
                $rawUrl = $cloudinary->upload($request->file('media')->getRealPath(), 'galleries');
                $gallery->photo = CloudinaryService::getUrl($rawUrl, 'gallery');
            } catch (\Exception $e) {
                return back()->withErrors(['media' => 'Gagal mengunggah file ke cloud storage: ' . $e->getMessage()])->withInput();
            }
        }

        $gallery->title = $validated['title'];
        $gallery->category = $validated['category'];
        $gallery->save();

        return redirect()->route('admin.galleries.index')->with('success', 'Galeri berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gallery $gallery)
    {
        $gallery->delete();
        return redirect()->route('admin.galleries.index')->with('success', 'Galeri berhasil dihapus.');
    }
}
