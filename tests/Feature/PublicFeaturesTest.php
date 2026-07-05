<?php

use App\Models\News;
use App\Models\NewsCategory;
use App\Models\TourismDestination;
use App\Models\TourismCategory;
use App\Models\Gallery;
use App\Models\OrganizationProfile;
use App\Models\OrganizationFunction;
use App\Models\OrganizationStructure;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

function seedPublicTestData() {
    $admin = User::create([
        'name' => 'Admin Test',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);

    $newsCat = NewsCategory::create(['name' => 'Berita Utama', 'slug' => 'berita-utama']);
    $tourismCat = TourismCategory::create(['name' => 'Wisata Sejarah', 'slug' => 'wisata-sejarah']);

    News::create([
        'user_id' => $admin->id,
        'news_category_id' => $newsCat->id,
        'title' => 'Festival Budaya Karawang 2026',
        'slug' => 'festival-budaya-karawang-2026',
        'content' => 'Pelaksanaan festival budaya tahunan.',
        'status' => 'published',
    ]);

    TourismDestination::create([
        'tourism_category_id' => $tourismCat->id,
        'name' => 'Candi Jiwa Batujaya',
        'slug' => 'candi-jiwa-batujaya',
        'description' => 'Situs percandian peninggalan Kerajaan Tarumanagara.',
        'address' => 'Desa Segaran, Kec. Batujaya',
        'latitude' => -6.0461000,
        'longitude' => 107.1517000,
        'cover_image' => 'https://example.com/candi.jpg',
        'status' => 'published',
    ]);

    TourismDestination::create([
        'tourism_category_id' => $tourismCat->id,
        'name' => 'Monumen Rawagede',
        'slug' => 'monumen-rawagede',
        'description' => 'Monumen peringatan pahlawan.',
        'address' => 'Desa Balongsari, Kec. Rawamerta',
        'latitude' => -6.2625000,
        'longitude' => 107.2661000,
        'cover_image' => 'https://example.com/rawagede.jpg',
        'status' => 'published',
    ]);

    OrganizationProfile::create([
        'vision' => 'Terwujudnya Ekonomi Kerakyatan',
        'mission' => 'Melestarikan budaya Karawang',
        'history' => 'Sejarah Dinas Pariwisata',
        'address' => 'Jl. Alun-Alun Selatan No. 1',
        'phone' => '(0267) 429800',
        'email' => 'disparbud@karawangkab.go.id',
    ]);

    OrganizationFunction::create([
        'title' => 'Perumusan Kebijakan Strategis',
        'description' => 'Perumusan kebijakan strategis dinas.',
        'order' => 1,
    ]);

    OrganizationStructure::create([
        'name' => 'Abas Sudrajat, S.Sos., M.P.',
        'position' => 'Kepala Dinas',
        'photo' => null,
        'order' => 1,
    ]);

    OrganizationStructure::create([
        'name' => 'H. Jaeni, S.Pd., M.M.Pd.',
        'position' => 'Sekretaris Dinas',
        'photo' => null,
        'order' => 2,
    ]);

    Gallery::create([
        'user_id' => $admin->id,
        'title' => 'Keindahan Candi Jiwa',
        'photo' => 'https://example.com/candi-gallery.jpg',
        'category' => 'wisata',
    ]);
}

test('skenario 1: guest can visit homepage and receive required inertia props', function () {
    seedPublicTestData();

    $this->get(route('public.home'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Public/Home')
            ->has('hero_stats')
            ->has('latest_news')
            ->has('featured_destinations')
            ->has('destinations')
        );
});

test('skenario 2: guest can visit profile page and see board members data', function () {
    seedPublicTestData();

    $this->get(route('public.profile'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Public/Profile')
            ->has('organization.boards', 2)
            ->where('organization.boards.0.name', 'Abas Sudrajat, S.Sos., M.P.')
            ->where('organization.boards.0.position', 'Kepala Dinas')
        );
});

test('skenario 3: guest can visit gallery page and filter by category', function () {
    seedPublicTestData();

    $this->get(route('public.gallery.index'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Public/Gallery/Index')
            ->has('galleries.data', 1)
            ->where('galleries.data.0.title', 'Keindahan Candi Jiwa')
        );

    $this->get(route('public.gallery.index', ['category' => 'wisata']))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Public/Gallery/Index')
            ->has('galleries.data', 1)
        );

    $this->get(route('public.gallery.index', ['category' => 'event']))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Public/Gallery/Index')
            ->has('galleries.data', 0)
        );
});

test('skenario 4: guest can visit destinasi page and destinations return valid numeric coordinates', function () {
    seedPublicTestData();

    $response = $this->get(route('public.destinasi'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Public/Tourism/Index')
            ->has('destinations')
        );

    $destinations = $response->inertiaPage()['props']['destinations'];

    expect($destinations)->not->toBeEmpty();
    foreach ($destinations as $dest) {
        expect($dest['latitude'])->toBeNumeric();
        expect($dest['longitude'])->toBeNumeric();
    }
});
