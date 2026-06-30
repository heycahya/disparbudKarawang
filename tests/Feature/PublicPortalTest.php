<?php

use App\Models\User;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\TourismDestination;
use App\Models\TourismCategory;
use App\Models\Complaint;
use App\Models\TourismSubmission;
use App\Models\EventBroadcastRequest;
use Cloudinary\Api\Upload\UploadApi;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    // State cleanup
    News::query()->delete();
    NewsCategory::query()->delete();
    TourismDestination::query()->delete();
    TourismCategory::query()->delete();
    Complaint::query()->delete();
    TourismSubmission::query()->delete();
    EventBroadcastRequest::query()->delete();
    User::query()->delete();

    // Mock Cloudinary UploadApi
    $this->mock(UploadApi::class, function ($mock) {
        $mock->shouldReceive('upload')
            ->andReturn(new \Cloudinary\Api\ApiResponse([
                'secure_url' => 'https://res.cloudinary.com/dummy/uploaded.jpg'
            ], []));
    });

    // Create categories
    $this->newsCategory = NewsCategory::create(['name' => 'Kategori 1', 'slug' => 'kategori-1']);
    $this->tourismCategory = TourismCategory::create(['name' => 'Kategori 2', 'slug' => 'kategori-2']);
});

test('guest can access public home, news catalog, and tourism catalog', function () {
    $this->get(route('public.home'))->assertStatus(200);
    $this->get(route('public.news.index'))->assertStatus(200);
    $this->get(route('public.tourism.index'))->assertStatus(200);
});

test('guest can access news show and tourism show', function () {
    $news = News::create([
        'user_id' => User::factory()->create(['role' => 'admin'])->id,
        'news_category_id' => $this->newsCategory->id,
        'title' => 'Berita Karawang',
        'slug' => 'berita-karawang',
        'content' => 'Isi berita',
        'status' => 'published'
    ]);

    $tourism = TourismDestination::create([
        'tourism_category_id' => $this->tourismCategory->id,
        'name' => 'Candi Jiwa',
        'slug' => 'candi-jiwa',
        'description' => 'Candi bersejarah',
        'address' => 'Batujaya, Karawang',
        'cover_image' => 'https://res.cloudinary.com/dummy/candi.jpg'
    ]);

    $this->get(route('public.news.show', $news->slug))->assertStatus(200);
    $this->get(route('public.tourism.show', $tourism->slug))->assertStatus(200);
});

test('unauthorized guest is redirected/blocked from accessing service rakyat', function () {
    $this->get(route('service-rakyat.complaints.create'))->assertRedirect('/login');
    $this->post(route('service-rakyat.complaints.store'), [])->assertRedirect('/login');
});

test('forbidden non-public roles are blocked from accessing service rakyat', function () {
    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);

    $this->actingAs($admin)->get(route('service-rakyat.complaints.create'))->assertStatus(403);
    $this->actingAs($admin)->post(route('service-rakyat.complaints.store'), [])->assertStatus(403);
});

test('public user can submit a complaint (happy path)', function () {
    $user = User::create([
        'name' => 'Rakyat biasa',
        'email' => 'rakyat@example.com',
        'password' => bcrypt('password'),
        'role' => 'public'
    ]);

    Storage::fake('public');
    $file = UploadedFile::fake()->create('laporan.pdf', 100); // 100KB document

    $payload = [
        'title' => 'Jalan Rusak Dekat Candi Jiwa',
        'description' => 'Mohon segera diperbaiki jalannya.',
        'attachment' => $file
    ];

    $response = $this->actingAs($user)
        ->post(route('service-rakyat.complaints.store'), $payload);

    $response->assertRedirect(route('public.home'));

    $this->assertDatabaseHas('complaints', [
        'user_id' => $user->id,
        'subject' => 'Jalan Rusak Dekat Candi Jiwa',
        'description' => 'Mohon segera diperbaiki jalannya.',
        'attachment' => 'https://res.cloudinary.com/dummy/uploaded.jpg',
        'status' => 'masuk'
    ]);
});

test('public user submit complaint fails validation with large attachment', function () {
    $user = User::create([
        'name' => 'Rakyat biasa',
        'email' => 'rakyat@example.com',
        'password' => bcrypt('password'),
        'role' => 'public'
    ]);

    Storage::fake('public');
    
    // Test doc file validation (max 5MB)
    $largeDoc = UploadedFile::fake()->create('laporan.pdf', 6000); // 6MB
    $payload = [
        'title' => 'Jalan Rusak',
        'description' => 'Detail laporan',
        'attachment' => $largeDoc
    ];

    $this->actingAs($user)
        ->post(route('service-rakyat.complaints.store'), $payload)
        ->assertSessionHasErrors(['attachment']);

    // Test image file validation (max 2MB)
    $largeImage = UploadedFile::fake()->create('foto.jpg', 3000, 'image/jpeg'); // 3MB
    $payload2 = [
        'title' => 'Jalan Rusak 2',
        'description' => 'Detail laporan 2',
        'attachment' => $largeImage
    ];

    $this->actingAs($user)
        ->post(route('service-rakyat.complaints.store'), $payload2)
        ->assertSessionHasErrors(['attachment']);
});

test('public user can submit a tourism destination suggestion', function () {
    $user = User::create([
        'name' => 'Rakyat biasa',
        'email' => 'rakyat@example.com',
        'password' => bcrypt('password'),
        'role' => 'public'
    ]);

    Storage::fake('public');
    $photo1 = UploadedFile::fake()->create('wisata1.jpg', 500, 'image/jpeg');

    $payload = [
        'name' => 'Curug Cigentis Baru',
        'description' => 'Keindahan air terjun asri',
        'location' => 'Loji, Karawang',
        'photos' => [$photo1]
    ];

    $this->actingAs($user)
        ->post(route('service-rakyat.tourism-submissions.store'), $payload)
        ->assertRedirect(route('public.home'));

    $this->assertDatabaseHas('tourism_submissions', [
        'user_id' => $user->id,
        'name' => 'Curug Cigentis Baru',
        'description' => 'Keindahan air terjun asri',
        'address' => 'Loji, Karawang',
        'photo' => 'https://res.cloudinary.com/dummy/uploaded.jpg',
        'status' => 'masuk'
    ]);
});

test('public user suggestion fails validation with invalid input or large photo', function () {
    $user = User::create([
        'name' => 'Rakyat biasa',
        'email' => 'rakyat@example.com',
        'password' => bcrypt('password'),
        'role' => 'public'
    ]);

    Storage::fake('public');
    $largePhoto = UploadedFile::fake()->create('wisata1.jpg', 3000, 'image/jpeg'); // 3MB

    $payload = [
        'name' => '',
        'description' => '',
        'location' => '',
        'photos' => [$largePhoto]
    ];

    $this->actingAs($user)
        ->post(route('service-rakyat.tourism-submissions.store'), $payload)
        ->assertSessionHasErrors(['name', 'description', 'location', 'photos.0']);
});

test('public user can submit an event broadcast request', function () {
    $user = User::create([
        'name' => 'Rakyat biasa',
        'email' => 'rakyat@example.com',
        'password' => bcrypt('password'),
        'role' => 'public'
    ]);

    Storage::fake('public');
    $proposal = UploadedFile::fake()->create('proposal.pdf', 2000); // 2MB

    $payload = [
        'organization' => 'Karang Taruna Karawang',
        'event_name' => 'Festival Kopi Karawang',
        'event_location' => 'Lapangan Karangpawitan',
        'start_date' => now()->addDays(5)->format('Y-m-d'),
        'end_date' => now()->addDays(6)->format('Y-m-d'),
        'description' => 'Acara pameran kopi lokal Karawang.',
        'proposal' => $proposal
    ];

    $this->actingAs($user)
        ->post(route('service-rakyat.event-broadcasts.store'), $payload)
        ->assertRedirect(route('public.home'));

    $this->assertDatabaseHas('event_broadcast_requests', [
        'user_id' => $user->id,
        'organization' => 'Karang Taruna Karawang',
        'event_name' => 'Festival Kopi Karawang',
        'event_location' => 'Lapangan Karangpawitan',
        'event_date' => now()->addDays(5)->format('Y-m-d 00:00:00'),
        'description' => 'Acara pameran kopi lokal Karawang.',
        'attachment' => 'https://res.cloudinary.com/dummy/uploaded.jpg',
        'status' => 'masuk'
    ]);
});
