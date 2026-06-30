<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsCategory;
use App\Http\Requests\StoreNewsRequest;
use App\Http\Requests\UpdateNewsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

class NewsController extends Controller
{
    protected $uploadApi;

    public function __construct(UploadApi $uploadApi)
    {
        Configuration::instance(env('CLOUDINARY_URL'));
        $this->uploadApi = $uploadApi;
    }

    public function index(Request $request)
    {
        $query = News::with(['category', 'user']);

        if ($request->has('search') && !empty($request->search)) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $news = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('Admin/Content/News/Index', [
            'news' => $news,
            'filters' => $request->only(['search'])
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Content/News/Create', [
            'categories' => NewsCategory::orderBy('name')->get()
        ]);
    }

    public function store(StoreNewsRequest $request)
    {
        $validated = $request->validated();

        // Cloudinary Upload
        $response = $this->uploadApi->upload($request->file('thumbnail')->getRealPath(), [
            'folder' => 'disparbud_karawang/news'
        ]);

        $validated['thumbnail'] = $response['secure_url'];
        $validated['user_id'] = Auth::id();
        
        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        News::create($validated);

        return redirect()->route('admin.news.index')
            ->with('success', 'Berita berhasil diterbitkan.');
    }

    public function edit(News $news)
    {
        return Inertia::render('Admin/Content/News/Edit', [
            'news' => $news,
            'categories' => NewsCategory::orderBy('name')->get()
        ]);
    }

    public function update(UpdateNewsRequest $request, News $news)
    {
        $validated = $request->validated();

        if ($request->hasFile('thumbnail')) {
            // Cloudinary Upload
            $response = $this->uploadApi->upload($request->file('thumbnail')->getRealPath(), [
                'folder' => 'disparbud_karawang/news'
            ]);
            $validated['thumbnail'] = $response['secure_url'];
        }

        if ($validated['status'] === 'published' && !$news->published_at) {
            $validated['published_at'] = now();
        } elseif ($validated['status'] === 'draft') {
            $validated['published_at'] = null;
        }

        // Handle Slug update on title change
        if ($news->title !== $validated['title']) {
            $validated['slug'] = News::generateUniqueSlug($validated['title'], $news->id);
        }

        $news->update($validated);

        return redirect()->route('admin.news.index')
            ->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(News $news)
    {
        $news->delete();
        return redirect()->route('admin.news.index')
            ->with('success', 'Berita berhasil dihapus.');
    }
}
