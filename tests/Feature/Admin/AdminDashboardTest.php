<?php

use App\Models\User;
use App\Models\Complaint;
use App\Models\TourismSubmission;
use App\Models\EventBroadcastRequest;
use App\Models\News;
use App\Models\TourismDestination;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    // Truncate tables for a clean slate
    Complaint::query()->delete();
    TourismSubmission::query()->delete();
    EventBroadcastRequest::query()->delete();
    News::query()->delete();
    TourismDestination::query()->delete();
    User::query()->delete();

    // Create a public user, admin user, and super admin user
    $this->publicUser = User::create([
        'name' => 'Rakyat Karawang',
        'email' => 'rakyat@example.com',
        'password' => bcrypt('password'),
        'role' => 'public'
    ]);

    $this->admin = User::create([
        'name' => 'Staf Disparbud',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);
});

test('guest is redirected to login when accessing admin dashboard', function () {
    $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
});

test('public user is forbidden from accessing admin dashboard', function () {
    $this->actingAs($this->publicUser)
        ->get(route('admin.dashboard'))
        ->assertStatus(403);
});

test('admin can view admin dashboard and receive statistical payload', function () {
    // Seed some data
    Complaint::create([
        'user_id' => $this->publicUser->id,
        'subject' => 'Jalan Rusak di Karawang',
        'description' => 'Ada lubang besar di jalan utama.',
        'status' => 'masuk'
    ]);

    Complaint::create([
        'user_id' => $this->publicUser->id,
        'subject' => 'Lampu Jalan Mati',
        'description' => 'Lampu jalan mati di perempatan.',
        'status' => 'ditinjau'
    ]);

    $category = \App\Models\TourismCategory::create([
        'name' => 'Sejarah',
        'slug' => 'sejarah'
    ]);

    TourismDestination::create([
        'tourism_category_id' => $category->id,
        'name' => 'Candi Jiwa',
        'description' => 'Situs candi Budha tertua.',
        'address' => 'Batujaya, Karawang',
        'status' => 'published',
        'views' => 150
    ]);

    $this->actingAs($this->admin)
        ->get(route('admin.dashboard'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Dashboard')
            ->has('statistics')
            ->where('statistics.complaints.total', 2)
            ->where('statistics.complaints.masuk', 1)
            ->where('statistics.complaints.ditinjau', 1)
            ->where('statistics.destinations.published', 1)
            ->where('statistics.web_visits', 150)
            ->has('statistics.trends')
        );
});
