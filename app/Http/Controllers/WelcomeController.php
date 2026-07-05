<?php

namespace App\Http\Controllers;

use App\Models\Culture;
use App\Models\News;
use App\Models\TourismDestination;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class WelcomeController extends Controller
{
    /**
     * Handle the incoming request for the landing page.
     */
    public function __invoke(): Response
    {
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'stats' => [
                'total_destinations' => TourismDestination::where('status', 'published')->count(),
                'total_news' => News::where('status', 'published')->count(),
                'total_cultures' => Culture::where('status', 'published')->count(),
            ],
            'featured_destinations' => TourismDestination::where('status', 'published')
                ->orderByDesc('views')
                ->limit(6)
                ->get(['id', 'name', 'slug', 'cover_image', 'views']),
            'latest_news' => News::where('status', 'published')
                ->orderByDesc('published_at')
                ->limit(4)
                ->get(['id', 'title', 'slug', 'thumbnail', 'views', 'published_at']),
        ]);
    }
}
