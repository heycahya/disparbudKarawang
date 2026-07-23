<?php

use App\Models\User;
use App\Models\Complaint;
use App\Models\TourismSubmission;
use App\Models\EventBroadcastRequest;
use App\Models\TourismCategory;
use App\Models\NewsCategory;

test('guest or public user cannot access admin verifikasi layanan', function () {
    $publicUser = User::create([
        'name' => 'Public User',
        'email' => 'publicuser@example.com',
        'password' => bcrypt('password'),
        'role' => 'public',
    ]);

    $this->get(route('admin.verifikasi-layanan.index'))->assertRedirect('/login');
    $this->actingAs($publicUser)->get(route('admin.verifikasi-layanan.index'))->assertStatus(403);
});

test('admin can access verifikasi layanan index page', function () {
    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'adminverifikasi@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);

    $this->actingAs($admin)->get(route('admin.verifikasi-layanan.index'))->assertStatus(200);
});

test('admin can approve a complaint and save admin note', function () {
    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin_complaint@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);

    $publicUser = User::create([
        'name' => 'Pelapor',
        'email' => 'pelapor@example.com',
        'password' => bcrypt('password'),
        'role' => 'public',
    ]);

    $complaint = Complaint::create([
        'user_id' => $publicUser->id,
        'subject' => 'Sampah liar di pantai',
        'description' => 'Tolong dibersihkan',
        'status' => 'masuk',
    ]);

    $response = $this->actingAs($admin)->patch(route('admin.verifikasi-layanan.update-status', [
        'type' => 'complaint',
        'id' => $complaint->id,
    ]), [
        'status' => 'disetujui',
        'admin_note' => 'Telah diteruskan ke dinas lingkungan hidup.',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('complaints', [
        'id' => $complaint->id,
        'status' => 'disetujui',
        'admin_note' => 'Telah diteruskan ke dinas lingkungan hidup.',
        'reviewed_by' => $admin->id,
    ]);
});

test('admin can clone an approved tourism submission to public tourism destination draft', function () {
    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin_clone_tourism@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);

    $category = TourismCategory::create([
        'name' => 'Wisata Alam Test',
        'slug' => 'wisata-alam-test',
    ]);

    $submission = TourismSubmission::create([
        'user_id' => $admin->id,
        'name' => 'Curug Hidden Gem Karawang',
        'description' => 'Curug indah di pegunungan',
        'address' => 'Desa Mekarbuana',
        'status' => 'masuk',
    ]);

    $response = $this->actingAs($admin)->post(route('admin.verifikasi-layanan.clone', [
        'type' => 'tourism_submission',
        'id' => $submission->id,
    ]));

    $response->assertRedirect();
    $this->assertDatabaseHas('tourism_destinations', [
        'name' => 'Curug Hidden Gem Karawang',
        'status' => 'draft',
    ]);
});

test('admin can clone an approved event broadcast to public news draft', function () {
    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin_clone_event@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);

    $newsCategory = NewsCategory::create([
        'name' => 'Event Kebudayaan Test',
        'slug' => 'event-kebudayaan-test',
    ]);

    $event = EventBroadcastRequest::create([
        'user_id' => $admin->id,
        'organization' => 'Seni Karawang',
        'event_name' => 'Pentas Jaipong Karawang',
        'event_location' => 'Lapangan Karangpawitan',
        'event_date' => now()->addDays(7),
        'description' => 'Pagelaran seni tari tradisional',
        'status' => 'masuk',
    ]);

    $response = $this->actingAs($admin)->post(route('admin.verifikasi-layanan.clone', [
        'type' => 'event_broadcast',
        'id' => $event->id,
    ]));

    $response->assertRedirect();
    $this->assertDatabaseHas('news', [
        'title' => 'Pentas Jaipong Karawang',
        'status' => 'draft',
    ]);
});

test('admin filtering by status maintains stable overall stats counters and returns correct filtered items', function () {
    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin_stats_filter@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);

    $publicUser = User::create([
        'name' => 'Pelapor',
        'email' => 'pelapor_filter@example.com',
        'password' => bcrypt('password'),
        'role' => 'public',
    ]);

    Complaint::create([
        'user_id' => $publicUser->id,
        'subject' => 'Aduan Masuk',
        'description' => 'Detail aduan',
        'status' => 'masuk',
    ]);

    TourismSubmission::create([
        'user_id' => $publicUser->id,
        'name' => 'Wisata Disetujui',
        'description' => 'Detail wisata',
        'address' => 'Karawang',
        'status' => 'disetujui',
    ]);

    EventBroadcastRequest::create([
        'user_id' => $publicUser->id,
        'organization' => 'Org Test',
        'event_name' => 'Event Ditolak',
        'event_location' => 'Karawang',
        'event_date' => now()->addDays(2),
        'description' => 'Detail event',
        'status' => 'ditolak',
    ]);

    $responseMasuk = $this->actingAs($admin)->get(route('admin.verifikasi-layanan.index', ['status' => 'masuk']));
    $responseMasuk->assertStatus(200);
    $responseMasuk->assertInertia(fn ($page) => $page
        ->component('Admin/LayananMasyarakat/Index')
        ->has('items', 1)
        ->where('stats.total', 3)
        ->where('stats.masuk', 1)
        ->where('stats.disetujui', 1)
        ->where('stats.ditolak', 1)
    );

    $responseDisetujui = $this->actingAs($admin)->get(route('admin.verifikasi-layanan.index', ['status' => 'disetujui']));
    $responseDisetujui->assertStatus(200);
    $responseDisetujui->assertInertia(fn ($page) => $page
        ->component('Admin/LayananMasyarakat/Index')
        ->has('items', 1)
        ->where('stats.total', 3)
        ->where('stats.disetujui', 1)
    );
});
