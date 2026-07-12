<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Gallery;
use App\Services\CloudinaryService;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $galleries = Gallery::with('user')->latest()->paginate(10);
        return Inertia::render('Admin/Galleries/Index', [
            'galleries' => $galleries
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
    public function store(Request $request, CloudinaryService $cloudinary)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:wisata,budaya,ekraf,event,lainnya',
            'media' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $fileUrl = null;

        if ($request->hasFile('media')) {
            try {
                $fileUrl = $cloudinary->upload($request->file('media')->getRealPath(), 'galleries');
            } catch (\Exception $e) {
                return back()->withErrors(['media' => 'Gagal mengunggah file ke cloud storage: ' . $e->getMessage()]);
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(Request $request, Gallery $gallery, CloudinaryService $cloudinary)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:wisata,budaya,ekraf,event,lainnya',
            'media' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('media')) {
            try {
                $fileUrl = $cloudinary->upload($request->file('media')->getRealPath(), 'galleries');
                $gallery->photo = $fileUrl;
            } catch (\Exception $e) {
                return back()->withErrors(['media' => 'Gagal mengunggah file ke cloud storage: ' . $e->getMessage()]);
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
