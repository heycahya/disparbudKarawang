<?php

use App\Models\Culture;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\TourismCategory;
use App\Models\TourismDestination;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders public landing page with required inertia props', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('Welcome')
        ->has('canLogin')
        ->has('canRegister')
        ->has('stats')
        ->has('stats.total_destinations')
        ->has('stats.total_news')
        ->has('stats.total_cultures')
        ->has('featured_destinations')
        ->has('latest_news')
    );
});

it('correctly counts published destinations, news, and cultures in stats prop', function () {
    $user = User::factory()->create(['role' => 'admin']);

    $tourismCategory = TourismCategory::create([
        'name' => 'Wisata Alam',
        'slug' => 'wisata-alam',
    ]);

    TourismDestination::create([
        'tourism_category_id' => $tourismCategory->id,
        'name' => 'Candi Jiwa Batujaya',
        'slug' => 'candi-jiwa-batujaya',
        'description' => 'Situs candi tertua di Pulau Jawa',
        'address' => 'Batujaya, Karawang',
        'status' => 'published',
        'views' => 10,
    ]);

    TourismDestination::create([
        'tourism_category_id' => $tourismCategory->id,
        'name' => 'Draft Destinasi',
        'slug' => 'draft-destinasi',
        'description' => 'Draft destinasi wisata',
        'address' => 'Karawang',
        'status' => 'draft',
        'views' => 0,
    ]);

    $newsCategory = NewsCategory::create([
        'name' => 'Kegiatan',
        'slug' => 'kegiatan',
    ]);

    News::create([
        'user_id' => $user->id,
        'news_category_id' => $newsCategory->id,
        'title' => 'Festival Budaya Karawang 2026',
        'slug' => 'festival-budaya-karawang-2026',
        'content' => 'Acara tahunan festival budaya karawang',
        'status' => 'published',
        'published_at' => now(),
        'views' => 15,
    ]);

    Culture::create([
        'name' => 'Tari Jaipong Karawang',
        'slug' => 'tari-jaipong-karawang',
        'category' => 'kesenian',
        'description' => 'Tari tradisional Karawang',
        'status' => 'published',
        'views' => 12,
    ]);

    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('Welcome')
        ->where('stats.total_destinations', 1)
        ->where('stats.total_news', 1)
        ->where('stats.total_cultures', 1)
        ->has('featured_destinations', 1)
        ->has('latest_news', 1)
    );
});
