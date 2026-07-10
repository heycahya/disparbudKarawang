<?php

use App\Models\User;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\TourismDestination;
use App\Models\TourismCategory;
use App\Models\Complaint;
use App\Models\TourismSubmission;
use App\Models\EventBroadcastRequest;
use App\Models\Culture;
use App\Models\CreativeEconomy;
use App\Models\Accommodation;
use App\Models\CulinaryPlace;
use Cloudinary\Api\Upload\UploadApi;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    // State cleanup
    News::query()->delete();
    NewsCategory::query()->delete();
    TourismDestination::query()->delete();
    TourismCategory::query()->delete();
    Complaint::query()->delete();
    TourismSubmission::query()->delete();
    EventBroadcastRequest::query()->delete();
    Culture::query()->delete();
    CreativeEconomy::query()->delete();
    Accommodation::query()->delete();
    CulinaryPlace::query()->delete();
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

test('guest can access all directory tabs in public catalog', function () {
    $this->get(route('public.tourism.index', ['tab' => 'tourism']))->assertStatus(200);
    $this->get(route('public.tourism.index', ['tab' => 'culture']))->assertStatus(200);
    $this->get(route('public.tourism.index', ['tab' => 'ekraf']))->assertStatus(200);
    $this->get(route('public.tourism.index', ['tab' => 'accommodation']))->assertStatus(200);
    $this->get(route('public.tourism.index', ['tab' => 'culinary']))->assertStatus(200);
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

test('unauthorized guest is redirected/blocked from accessing layanan masyarakat', function () {
    $this->get(route('layanan-masyarakat.complaints.create'))->assertRedirect('/login');
    $this->post(route('layanan-masyarakat.complaints.store'), [])->assertRedirect('/login');
});

test('forbidden non-public roles are blocked from accessing layanan masyarakat', function () {
    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);

    $this->actingAs($admin)->get(route('layanan-masyarakat.complaints.create'))->assertStatus(403);
    $this->actingAs($admin)->post(route('layanan-masyarakat.complaints.store'), [])->assertStatus(403);
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
        ->post(route('layanan-masyarakat.complaints.store'), $payload);

    $response->assertRedirect(route('public.history.index'));

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
        ->post(route('layanan-masyarakat.complaints.store'), $payload)
        ->assertSessionHasErrors(['attachment']);

    // Test image file validation (max 2MB)
    $largeImage = UploadedFile::fake()->create('foto.jpg', 3000, 'image/jpeg'); // 3MB
    $payload2 = [
        'title' => 'Jalan Rusak 2',
        'description' => 'Detail laporan 2',
        'attachment' => $largeImage
    ];

    $this->actingAs($user)
        ->post(route('layanan-masyarakat.complaints.store'), $payload2)
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
        ->post(route('layanan-masyarakat.tourism-submissions.store'), $payload)
        ->assertRedirect(route('public.history.index'));

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
        ->post(route('layanan-masyarakat.tourism-submissions.store'), $payload)
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
        ->post(route('layanan-masyarakat.event-broadcasts.store'), $payload)
        ->assertRedirect(route('public.history.index'));

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

test('public pages render successfully with global footer component', function () {
    $this->get(route('public.home'))->assertStatus(200);
    $this->get(route('public.profile'))->assertStatus(200);
    $this->get(route('public.gallery.index'))->assertStatus(200);
    $this->get(route('public.news.index'))->assertStatus(200);
    $this->get(route('public.destinasi'))->assertStatus(200);
});

test('authenticated user accessing dashboard receives service_requests prop for summary table', function () {
    $user = User::create([
        'name' => 'Warga Karawang',
        'email' => 'warga@example.com',
        'password' => bcrypt('password'),
        'role' => 'public'
    ]);

    Complaint::create([
        'user_id' => $user->id,
        'subject' => 'Keluhan Fasilitas Wisata',
        'description' => 'Fasilitas umum butuh perbaikan.',
        'status' => 'masuk'
    ]);

    TourismSubmission::create([
        'user_id' => $user->id,
        'name' => 'Usulan Spot Foto Citarum',
        'description' => 'Spot keindahan tepi sungai',
        'address' => 'Karawang Barat',
        'status' => 'ditinjau'
    ]);

    EventBroadcastRequest::create([
        'user_id' => $user->id,
        'organization' => 'Komunitas Budaya',
        'event_name' => 'Pentas Jaipong',
        'event_location' => 'Gedung Kesenian',
        'event_date' => now()->addDays(3),
        'description' => 'Acara seni tahunan',
        'status' => 'disetujui'
    ]);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Public/UserDashboard')
        ->has('service_requests', 3)
    );
});

test('public user cannot access another users data due to global scope / RLS', function () {
    $userA = User::create([
        'name' => 'User A',
        'email' => 'usera@example.com',
        'password' => bcrypt('password'),
        'role' => 'public'
    ]);

    $userB = User::create([
        'name' => 'User B',
        'email' => 'userb@example.com',
        'password' => bcrypt('password'),
        'role' => 'public'
    ]);

    Complaint::create([
        'user_id' => $userA->id,
        'subject' => 'Laporan User A',
        'description' => 'Detail A',
        'status' => 'masuk'
    ]);

    // Authenticate as User B
    $this->actingAs($userB);

    // Complaint::all() should not contain User A's complaint because of the global scope
    expect(Complaint::all())->toBeEmpty();
});

test('policies prevent unauthorized access (IDOR) on Layanan Masyarakat models', function () {
    $userA = User::create([
        'name' => 'User A',
        'email' => 'usera@example.com',
        'password' => bcrypt('password'),
        'role' => 'public'
    ]);

    $userB = User::create([
        'name' => 'User B',
        'email' => 'userb@example.com',
        'password' => bcrypt('password'),
        'role' => 'public'
    ]);

    $complaint = Complaint::create([
        'user_id' => $userA->id,
        'subject' => 'Laporan User A',
        'description' => 'Detail A',
        'status' => 'masuk'
    ]);

    $tourism = TourismSubmission::create([
        'user_id' => $userA->id,
        'name' => 'Usulan User A',
        'description' => 'Detail A',
        'address' => 'Lokasi A',
        'status' => 'masuk'
    ]);

    $event = EventBroadcastRequest::create([
        'user_id' => $userA->id,
        'organization' => 'Org A',
        'event_name' => 'Event A',
        'event_location' => 'Lokasi A',
        'event_date' => now()->addDays(5),
        'description' => 'Detail A',
        'status' => 'masuk'
    ]);

    // Test that userB cannot view/update/delete userA's models via Policy
    expect($userB->can('view', $complaint))->toBeFalse();
    expect($userB->can('update', $complaint))->toBeFalse();
    expect($userB->can('delete', $complaint))->toBeFalse();

    expect($userB->can('view', $tourism))->toBeFalse();
    expect($userB->can('update', $tourism))->toBeFalse();
    expect($userB->can('delete', $tourism))->toBeFalse();

    expect($userB->can('view', $event))->toBeFalse();
    expect($userB->can('update', $event))->toBeFalse();
    expect($userB->can('delete', $event))->toBeFalse();

    // Test that userA can view/update/delete their own models
    expect($userA->can('view', $complaint))->toBeTrue();
    expect($userA->can('update', $complaint))->toBeTrue();
    expect($userA->can('delete', $complaint))->toBeTrue();

    // Test that admin can bypass the policy
    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);

    expect($admin->can('view', $complaint))->toBeTrue();
    expect($admin->can('update', $complaint))->toBeTrue();
    expect($admin->can('delete', $complaint))->toBeTrue();
});

