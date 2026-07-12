<?php

use App\Models\User;
use App\Models\Complaint;
use App\Models\TourismSubmission;
use App\Models\EventBroadcastRequest;
use App\Models\TourismDestination;
use App\Models\TourismCategory;
use App\Models\News;
use App\Models\NewsCategory;

beforeEach(function () {
    // Truncate tables for clean slate (State Clean rule)
    Complaint::query()->delete();
    TourismSubmission::query()->delete();
    EventBroadcastRequest::query()->delete();
    TourismDestination::query()->delete();
    TourismCategory::query()->delete();
    News::query()->delete();
    NewsCategory::query()->delete();
    User::query()->delete();

    // Create Admin User
    $this->admin = User::create([
        'name' => 'Staf Disparbud',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);

    // Create Public User (Submitter)
    $this->publicUser = User::create([
        'name' => 'Rakyat Karawang',
        'email' => 'rakyat@example.com',
        'password' => bcrypt('password'),
        'role' => 'public'
    ]);
});

test('guest is redirected to login when accessing service rakyat inbox', function () {
    $this->get(route('admin.service-rakyat.complaints.index'))->assertRedirect(route('login'));
    $this->get(route('admin.service-rakyat.tourism-submissions.index'))->assertRedirect(route('login'));
    $this->get(route('admin.service-rakyat.event-broadcasts.index'))->assertRedirect(route('login'));
});

test('public user is forbidden from accessing service rakyat inbox', function () {
    $this->actingAs($this->publicUser)
        ->get(route('admin.service-rakyat.complaints.index'))
        ->assertStatus(403);

    $this->actingAs($this->publicUser)
        ->get(route('admin.service-rakyat.tourism-submissions.index'))
        ->assertStatus(403);

    $this->actingAs($this->publicUser)
        ->get(route('admin.service-rakyat.event-broadcasts.index'))
        ->assertStatus(403);
});

test('admin can view complaints index and show details page', function () {
    $complaint = Complaint::create([
        'user_id' => $this->publicUser->id,
        'subject' => 'Jalan Rusak di Wisata Alam',
        'description' => 'Ada kerusakan jalan di sekitar pintu masuk.',
        'status' => 'masuk'
    ]);

    $this->actingAs($this->admin)
        ->get(route('admin.service-rakyat.complaints.index'))
        ->assertStatus(200);

    $this->actingAs($this->admin)
        ->get(route('admin.service-rakyat.complaints.show', $complaint->id))
        ->assertStatus(200);
});

test('admin can view tourism submissions index and show details page', function () {
    $submission = TourismSubmission::create([
        'user_id' => $this->publicUser->id,
        'name' => 'Pantai Baru Karawang',
        'description' => 'Destinasi pantai indah tersembunyi.',
        'address' => 'Kec. Tempuran, Karawang',
        'status' => 'masuk'
    ]);

    $this->actingAs($this->admin)
        ->get(route('admin.service-rakyat.tourism-submissions.index'))
        ->assertStatus(200);

    $this->actingAs($this->admin)
        ->get(route('admin.service-rakyat.tourism-submissions.show', $submission->id))
        ->assertStatus(200);
});

test('admin can view event broadcast requests index and show details page', function () {
    $request = EventBroadcastRequest::create([
        'user_id' => $this->publicUser->id,
        'organization' => 'Karawang Muda',
        'event_name' => 'Konser Kebudayaan 2026',
        'event_date' => '2026-08-15',
        'event_location' => 'Stadion Singaperbangsa',
        'description' => 'Pentas seni musik tradisional.',
        'status' => 'masuk'
    ]);

    $this->actingAs($this->admin)
        ->get(route('admin.service-rakyat.event-broadcasts.index'))
        ->assertStatus(200);

    $this->actingAs($this->admin)
        ->get(route('admin.service-rakyat.event-broadcasts.show', $request->id))
        ->assertStatus(200);
});

test('status update requires admin_note only when status is rejected', function () {
    $complaint = Complaint::create([
        'user_id' => $this->publicUser->id,
        'subject' => 'Laporan Kerusakan',
        'description' => 'Deskripsi laporan.',
        'status' => 'masuk'
    ]);

    // Update to 'ditinjau' without admin_note is allowed
    $this->actingAs($this->admin)
        ->patch(route('admin.service-rakyat.complaints.status', $complaint->id), [
            'status' => 'ditinjau'
        ])
        ->assertSessionHasNoErrors();

    // Update to 'ditolak' without admin_note is rejected
    $this->actingAs($this->admin)
        ->patch(route('admin.service-rakyat.complaints.status', $complaint->id), [
            'status' => 'ditolak'
        ])
        ->assertSessionHasErrors(['admin_note']);

    // Update to 'ditolak' with admin_note is allowed
    $this->actingAs($this->admin)
        ->patch(route('admin.service-rakyat.complaints.status', $complaint->id), [
            'status' => 'ditolak',
            'admin_note' => 'Lokasi bukan wewenang Disparbud.'
        ])
        ->assertSessionHasNoErrors();

    $this->assertDatabaseHas('complaints', [
        'id' => $complaint->id,
        'status' => 'ditolak',
        'admin_note' => 'Lokasi bukan wewenang Disparbud.',
        'reviewed_by' => $this->admin->id
    ]);
});

test('automatically converts approved tourism submissions into tourism destinations', function () {
    $submission = TourismSubmission::create([
        'user_id' => $this->publicUser->id,
        'name' => 'Curug Cigentis Indah',
        'description' => 'Air terjun alami di kaki gunung Sanggabuana.',
        'address' => 'Kec. Tegalwaru, Karawang',
        'latitude' => -6.5000000,
        'longitude' => 107.2000000,
        'photo' => 'https://res.cloudinary.com/dummy/cigentis.jpg',
        'status' => 'masuk'
    ]);

    $this->actingAs($this->admin)
        ->patch(route('admin.service-rakyat.tourism-submissions.status', $submission->id), [
            'status' => 'disetujui'
        ])
        ->assertSessionHasNoErrors();

    // Destination created as draft
    $this->assertDatabaseHas('tourism_destinations', [
        'name' => 'Curug Cigentis Indah',
        'description' => 'Air terjun alami di kaki gunung Sanggabuana.',
        'address' => 'Kec. Tegalwaru, Karawang',
        'latitude' => -6.5000000,
        'longitude' => 107.2000000,
        'cover_image' => 'https://res.cloudinary.com/dummy/cigentis.jpg',
        'status' => 'draft'
    ]);

    $destination = TourismDestination::where('name', 'Curug Cigentis Indah')->first();

    // Submission updated with converted_destination_id
    $this->assertDatabaseHas('tourism_submissions', [
        'id' => $submission->id,
        'status' => 'disetujui',
        'converted_destination_id' => $destination->id,
        'reviewed_by' => $this->admin->id
    ]);
});

test('automatically converts approved event broadcasts into news articles', function () {
    $request = EventBroadcastRequest::create([
        'user_id' => $this->publicUser->id,
        'organization' => 'Sanggar Seni Karawang',
        'event_name' => 'Festival Tari Jaipong 2026',
        'event_date' => '2026-09-10',
        'event_location' => 'Kampung Budaya Karawang',
        'description' => 'Lomba tari jaipong tingkat Jawa Barat.',
        'attachment' => 'https://res.cloudinary.com/dummy/jaipong.jpg',
        'status' => 'masuk'
    ]);

    $this->actingAs($this->admin)
        ->patch(route('admin.service-rakyat.event-broadcasts.status', $request->id), [
            'status' => 'disetujui'
        ])
        ->assertSessionHasNoErrors();

    // Get Event Category
    $category = NewsCategory::where('slug', 'event')->first();

    // News article created as draft
    $this->assertDatabaseHas('news', [
        'user_id' => $this->admin->id,
        'news_category_id' => $category->id,
        'title' => 'Festival Tari Tari Jaipong 2026', // Note Str::title will keep Festival Tari Jaipong 2026? Wait, it's just event_name
        'title' => 'Festival Tari Jaipong 2026',
        'content' => 'Lomba tari jaipong tingkat Jawa Barat.',
        'thumbnail' => 'https://res.cloudinary.com/dummy/jaipong.jpg',
        'status' => 'draft'
    ]);

    $news = News::where('title', 'Festival Tari Jaipong 2026')->first();

    // Request updated with converted_news_id
    $this->assertDatabaseHas('event_broadcast_requests', [
        'id' => $request->id,
        'status' => 'disetujui',
        'converted_news_id' => $news->id,
        'reviewed_by' => $this->admin->id
    ]);
});
