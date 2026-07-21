<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsCategory;
use App\Models\TourismDestination;
use App\Models\Culture;
use App\Models\CreativeEconomy;
use App\Models\Accommodation;
use App\Models\CulinaryPlace;
use App\Models\Gallery;
use App\Models\OrganizationProfile;
use App\Models\OrganizationFunction;
use App\Models\OrganizationStructure;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PublicPortalController extends Controller
{
    public function home()
    {
        $latestNews = News::with('category')
            ->where('status', 'published')
            ->latest()
            ->get();

        $featuredDestinations = TourismDestination::with('category')
            ->where('status', 'published')
            ->latest()
            ->get();

        $cultures = Culture::where('status', 'published')
            ->latest()
            ->get();

        $ekraf = CreativeEconomy::where('status', 'published')
            ->latest()
            ->get();

        $accommodations = Accommodation::where('status', 'published')
            ->latest()
            ->get();

        $culinary = CulinaryPlace::where('status', 'published')
            ->latest()
            ->get();

        $mapDestinations = TourismDestination::with('category')
            ->where('status', 'published')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'slug' => $item->slug,
                    'latitude' => (float) $item->latitude,
                    'longitude' => (float) $item->longitude,
                    'description' => \Illuminate\Support\Str::limit(strip_tags($item->description), 120),
                    'address' => $item->address,
                    'image_url' => $item->cover_image,
                    'category' => $item->category?->name,
                ];
            });

        $heroStats = [
            'destinations' => TourismDestination::where('status', 'published')->count(),
            'news' => News::where('status', 'published')->count(),
            'cultures' => Culture::where('status', 'published')->count(),
            'ekraf' => CreativeEconomy::where('status', 'published')->count(),
        ];

        $galleries = \App\Models\Gallery::latest()->take(6)->get();
        $profile = \App\Models\OrganizationProfile::first();

        return Inertia::render('Public/Home', [
            'hero_stats' => $heroStats,
            'latest_news' => $latestNews,
            'featured_destinations' => $featuredDestinations,
            'cultures' => $cultures,
            'ekraf' => $ekraf,
            'accommodations' => $accommodations,
            'culinary' => $culinary,
            'destinations' => $mapDestinations,
            'galleries' => $galleries,
            'organization_profile' => $profile,
            'news' => $latestNews,
            'tourism' => $featuredDestinations,
        ]);
    }

    public function profile()
    {
        $profile = OrganizationProfile::first();
        $functions = OrganizationFunction::orderBy('order')->get();
        $boards = OrganizationStructure::orderBy('order')->get();

        return Inertia::render('Public/Profile', [
            'organization' => [
                'profile' => $profile ? $profile->history : '',
                'vision_mission' => [
                    'vision' => $profile ? $profile->vision : '',
                    'mission' => $profile ? $profile->mission : '',
                ],
                'tupoksi' => $functions,
                'boards' => $boards,
                'details' => $profile,
            ],
        ]);
    }

    public function galleryIndex(Request $request)
    {
        $category = $request->query('category');

        $query = Gallery::query();
        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }

        $galleries = $query->latest()->paginate(12)->withQueryString();

        return Inertia::render('Public/Gallery/Index', [
            'galleries' => $galleries,
            'categories' => ['wisata', 'budaya', 'ekraf', 'event', 'lainnya'],
            'activeCategory' => $category ?? 'all',
        ]);
    }

    public function newsIndex(Request $request)
    {
        $categorySlug = $request->query('category');

        $query = News::with('category')
            ->where('status', 'published');

        if ($categorySlug && $categorySlug !== 'all') {
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        $news = $query->latest()
            ->paginate(9)
            ->withQueryString();

        $categories = NewsCategory::all();

        return Inertia::render('Public/News/Index', [
            'news' => $news,
            'categories' => $categories,
            'activeCategory' => $categorySlug ?? 'all',
        ]);
    }

    public function newsShow(string $slug)
    {
        $news = News::with(['category', 'user'])
            ->where('status', 'published')
            ->where('slug', $slug)
            ->firstOrFail();

        $news->increment('views');

        $relatedNews = News::with('category')
            ->where('status', 'published')
            ->where('id', '!=', $news->id)
            ->latest()
            ->take(3)
            ->get();

        return Inertia::render('Public/News/Show', [
            'news' => $news,
            'relatedNews' => $relatedNews,
            'seo' => [
                'title' => $news->title,
                'description' => \Illuminate\Support\Str::limit(strip_tags($news->content), 150),
                'image' => $news->thumbnail,
                'type' => 'article',
            ]
        ]);
    }

    public function tourismIndex(Request $request)
    {
        $tab = $request->query('tab', 'tourism');
        $data = null;

        switch ($tab) {
            case 'culture':
                $data = \App\Models\Culture::where('status', 'published')
                    ->latest()
                    ->paginate(9)
                    ->withQueryString();
                break;
            case 'ekraf':
                $data = \App\Models\CreativeEconomy::where('status', 'published')
                    ->latest()
                    ->paginate(9)
                    ->withQueryString();
                break;
            case 'accommodation':
                $data = \App\Models\Accommodation::where('status', 'published')
                    ->latest()
                    ->paginate(9)
                    ->withQueryString();
                break;
            case 'culinary':
                $data = \App\Models\CulinaryPlace::where('status', 'published')
                    ->latest()
                    ->paginate(9)
                    ->withQueryString();
                break;
            case 'tourism':
            default:
                $data = TourismDestination::with('category')
                    ->where('status', 'published')
                    ->latest()
                    ->paginate(9)
                    ->withQueryString();
                $tab = 'tourism';
                break;
        }

        $destinations = TourismDestination::with('category')
            ->where('status', 'published')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'slug' => $item->slug,
                    'latitude' => (float) $item->latitude,
                    'longitude' => (float) $item->longitude,
                    'description' => \Illuminate\Support\Str::limit(strip_tags($item->description), 120),
                    'address' => $item->address,
                    'image_url' => $item->cover_image,
                    'category' => $item->category?->name,
                ];
            });

        return Inertia::render('Public/Tourism/Index', [
            'data' => $data,
            'activeTab' => $tab,
            'destinations' => $destinations,
        ]);
    }

    public function tourismShow(string $slug)
    {
        $destination = TourismDestination::with(['category', 'photos'])
            ->where('slug', $slug)
            ->firstOrFail();

        $destination->increment('views');

        // Build photos array: cover_image first, then gallery photos
        $photos = collect();
        if ($destination->cover_image) {
            $photos->push(['url' => $destination->cover_image, 'caption' => $destination->name]);
        }
        foreach ($destination->photos as $p) {
            $photos->push(['url' => $p->photo, 'caption' => $p->caption ?? '']);
        }

        $relatedItems = TourismDestination::with('category')
            ->where('id', '!=', $destination->id)
            ->where('status', 'published')
            ->latest()
            ->take(3)
            ->get();

        return Inertia::render('Public/Tourism/Show', [
            'type' => 'tourism',
            'item' => $destination,
            'photos' => $photos->values(),
            'relatedItems' => $relatedItems,
            'seo' => [
                'title' => $destination->name,
                'description' => \Illuminate\Support\Str::limit(strip_tags($destination->description), 150),
                'image' => $destination->cover_image,
                'type' => 'website',
            ]
        ]);
    }

    public function cultureShow(string $slug)
    {
        $culture = Culture::with('photos')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $culture->increment('views');

        $photos = collect();
        if ($culture->cover_image) {
            $photos->push(['url' => $culture->cover_image, 'caption' => $culture->name]);
        }
        foreach ($culture->photos as $p) {
            $photos->push(['url' => $p->photo, 'caption' => $p->caption ?? '']);
        }

        $relatedItems = Culture::where('id', '!=', $culture->id)
            ->where('status', 'published')
            ->latest()
            ->take(3)
            ->get();

        return Inertia::render('Public/Tourism/Show', [
            'type' => 'culture',
            'item' => $culture,
            'photos' => $photos->values(),
            'relatedItems' => $relatedItems,
            'seo' => [
                'title' => $culture->name,
                'description' => \Illuminate\Support\Str::limit(strip_tags($culture->description), 150),
                'image' => $culture->cover_image,
                'type' => 'website',
            ]
        ]);
    }

    public function ekrafShow(string $slug)
    {
        $ekraf = CreativeEconomy::with('photos')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $photos = collect();
        if ($ekraf->cover_image) {
            $photos->push(['url' => $ekraf->cover_image, 'caption' => $ekraf->name]);
        }
        foreach ($ekraf->photos as $p) {
            $photos->push(['url' => $p->photo, 'caption' => $p->caption ?? '']);
        }

        $relatedItems = CreativeEconomy::where('id', '!=', $ekraf->id)
            ->where('status', 'published')
            ->latest()
            ->take(3)
            ->get();

        return Inertia::render('Public/Tourism/Show', [
            'type' => 'ekraf',
            'item' => $ekraf,
            'photos' => $photos->values(),
            'relatedItems' => $relatedItems,
            'seo' => [
                'title' => $ekraf->name,
                'description' => \Illuminate\Support\Str::limit(strip_tags($ekraf->description), 150),
                'image' => $ekraf->cover_image,
                'type' => 'website',
            ]
        ]);
    }

    public function accommodationShow(string $slug)
    {
        $accommodation = Accommodation::with('photos')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $photos = collect();
        if ($accommodation->cover_image) {
            $photos->push(['url' => $accommodation->cover_image, 'caption' => $accommodation->name]);
        }
        foreach ($accommodation->photos as $p) {
            $photos->push(['url' => $p->photo, 'caption' => $p->caption ?? '']);
        }

        $relatedItems = Accommodation::where('id', '!=', $accommodation->id)
            ->where('status', 'published')
            ->latest()
            ->take(3)
            ->get();

        return Inertia::render('Public/Tourism/Show', [
            'type' => 'accommodation',
            'item' => $accommodation,
            'photos' => $photos->values(),
            'relatedItems' => $relatedItems,
            'seo' => [
                'title' => $accommodation->name,
                'description' => \Illuminate\Support\Str::limit(strip_tags($accommodation->description), 150),
                'image' => $accommodation->cover_image,
                'type' => 'website',
            ]
        ]);
    }

    public function culinaryShow(string $slug)
    {
        $culinary = CulinaryPlace::with('photos')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $photos = collect();
        if ($culinary->cover_image) {
            $photos->push(['url' => $culinary->cover_image, 'caption' => $culinary->name]);
        }
        foreach ($culinary->photos as $p) {
            $photos->push(['url' => $p->photo, 'caption' => $p->caption ?? '']);
        }

        $relatedItems = CulinaryPlace::where('id', '!=', $culinary->id)
            ->where('status', 'published')
            ->latest()
            ->take(3)
            ->get();

        return Inertia::render('Public/Tourism/Show', [
            'type' => 'culinary',
            'item' => $culinary,
            'photos' => $photos->values(),
            'relatedItems' => $relatedItems,
            'seo' => [
                'title' => $culinary->name,
                'description' => \Illuminate\Support\Str::limit(strip_tags($culinary->description), 150),
                'image' => $culinary->cover_image,
                'type' => 'website',
            ]
        ]);
    }
}
