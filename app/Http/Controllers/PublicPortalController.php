<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\TourismDestination;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PublicPortalController extends Controller
{
    public function home()
    {
        $news = News::with('category')
            ->where('status', 'published')
            ->latest()
            ->take(3)
            ->get();

        $tourism = TourismDestination::with('category')
            ->latest()
            ->take(3)
            ->get();

        return Inertia::render('Public/Home', [
            'news' => $news,
            'tourism' => $tourism,
        ]);
    }

    public function newsIndex(Request $request)
    {
        $news = News::with('category')
            ->where('status', 'published')
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return Inertia::render('Public/News/Index', [
            'news' => $news,
        ]);
    }

    public function newsShow(string $slug)
    {
        $news = News::with(['category', 'user'])
            ->where('status', 'published')
            ->where('slug', $slug)
            ->firstOrFail();

        $news->increment('views');

        return Inertia::render('Public/News/Show', [
            'news' => $news,
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

        return Inertia::render('Public/Tourism/Index', [
            'data' => $data,
            'activeTab' => $tab,
        ]);
    }

    public function tourismShow(string $slug)
    {
        $destination = TourismDestination::with('category')
            ->where('slug', $slug)
            ->firstOrFail();

        return Inertia::render('Public/Tourism/Show', [
            'destination' => $destination,
        ]);
    }
}
