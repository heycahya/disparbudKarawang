<?php

use App\Models\User;
use App\Models\Complaint;
use App\Models\TourismSubmission;
use App\Models\EventBroadcastRequest;

beforeEach(function () {
    // Truncate relevant tables (State Clean rule)
    Complaint::query()->delete();
    TourismSubmission::query()->delete();
    EventBroadcastRequest::query()->delete();
    User::query()->delete();
});

test('guest is redirected to login when accessing tracking history', function () {
    $this->get(route('public.history.index'))
        ->assertRedirect(route('login'));
});

test('public user can access tracking history page', function () {
    $user = User::create([
        'name' => 'Rakyat Karawang',
        'email' => 'rakyat@example.com',
        'password' => bcrypt('password'),
        'role' => 'public'
    ]);

    $this->actingAs($user)
        ->get(route('public.history.index'))
        ->assertStatus(200);
});

test('tracking history only returns the authenticated users submissions', function () {
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

    // Create submissions for User A
    $complaint = Complaint::create([
        'user_id' => $userA->id,
        'subject' => 'Laporan A',
        'description' => 'Detail A',
        'status' => 'masuk'
    ]);
    $complaint->created_at = now()->subMinutes(10);
    $complaint->save();

    $submission = TourismSubmission::create([
        'user_id' => $userA->id,
        'name' => 'Usulan A',
        'description' => 'Detail A',
        'address' => 'Lokasi A',
        'status' => 'ditinjau'
    ]);
    $submission->created_at = now();
    $submission->save();

    // Create submissions for User B
    EventBroadcastRequest::create([
        'user_id' => $userB->id,
        'organization' => 'Org B',
        'event_name' => 'Event B',
        'event_date' => now()->addDays(5),
        'event_location' => 'Lokasi B',
        'description' => 'Detail B',
        'status' => 'disetujui'
    ]);

    // Authenticate as User A and query history
    $response = $this->actingAs($userA)
        ->get(route('public.history.index'));

    $response->assertStatus(200);

    // Assert that response has userA's 2 submissions but not userB's submission
    $response->assertInertia(function ($page) {
        $page->component('Public/History/Index')
            ->has('submissions', 2)
            ->where('submissions.0.title', 'Usulan A') // sorted by created_at desc (created second)
            ->where('submissions.1.title', 'Laporan A');
    });
});
