<?php

use App\Models\User;
use App\Models\Culture;
use Cloudinary\Api\Upload\UploadApi;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    // Truncate tables for clean slate (State Clean rule)
    Culture::query()->delete();
    User::query()->delete();

    // Mock UploadApi
    $this->mock(UploadApi::class, function ($mock) {
        $mock->shouldReceive('upload')
            ->andReturn(new \Cloudinary\Api\ApiResponse([
                'secure_url' => 'https://res.cloudinary.com/dummy/image/upload/culture.jpg'
            ], []));
    });
});

test('guest is redirected to login when accessing culture management', function () {
    $this->get(route('admin.cultures.index'))->assertRedirect(route('login'));
    $this->get(route('admin.cultures.create'))->assertRedirect(route('login'));
});

test('public user is forbidden from accessing culture management', function () {
    $publicUser = User::create([
        'name' => 'Rakyat Karawang',
        'email' => 'rakyat@example.com',
        'password' => bcrypt('password'),
        'role' => 'public'
    ]);

    $this->actingAs($publicUser)
        ->get(route('admin.cultures.index'))
        ->assertStatus(403);
});

test('admin can view culture management index and create page', function () {
    $admin = User::create([
        'name' => 'Staf Disparbud',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);

    $this->actingAs($admin)
        ->get(route('admin.cultures.index'))
        ->assertStatus(200);

    $this->actingAs($admin)
        ->get(route('admin.cultures.create'))
        ->assertStatus(200);
});

test('admin can create a new culture with cover image', function () {
    $admin = User::create([
        'name' => 'Staf Disparbud',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);

    Storage::fake('public');
    $file = UploadedFile::fake()->create('cover.jpg', 500, 'image/jpeg');

    $payload = [
        'name' => 'Tari Jaipong',
        'category' => 'kesenian',
        'description' => '<p>Tarian khas sunda.</p>',
        'status' => 'published',
        'cover_image' => $file
    ];

    $this->actingAs($admin)
        ->post(route('admin.cultures.store'), $payload)
        ->assertRedirect(route('admin.cultures.index'));

    $this->assertDatabaseHas('cultures', [
        'name' => 'Tari Jaipong',
        'slug' => 'tari-jaipong',
        'category' => 'kesenian',
        'description' => '<p>Tarian khas sunda.</p>',
        'status' => 'published',
        'cover_image' => 'https://res.cloudinary.com/dummy/image/upload/culture.jpg'
    ]);
});

test('slug generation resolves collisions on cultures by adding numerical suffixes', function () {
    $admin = User::create([
        'name' => 'Staf Disparbud',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);

    // Create first culture
    Culture::create([
        'name' => 'Topeng Banjet',
        'slug' => 'topeng-banjet',
        'category' => 'kesenian',
        'cover_image' => 'https://res.cloudinary.com/dummy/culture.jpg',
        'description' => 'Lorem ipsum',
        'status' => 'draft'
    ]);

    Storage::fake('public');
    $file = UploadedFile::fake()->create('cover.jpg', 100, 'image/jpeg');

    $payload = [
        'name' => 'Topeng Banjet',
        'category' => 'kesenian',
        'description' => '<p>Pertunjukan kedua.</p>',
        'status' => 'draft',
        'cover_image' => $file
    ];

    $this->actingAs($admin)
        ->post(route('admin.cultures.store'), $payload)
        ->assertRedirect(route('admin.cultures.index'));

    $this->assertDatabaseHas('cultures', [
        'name' => 'Topeng Banjet',
        'slug' => 'topeng-banjet-1'
    ]);
});

test('validation rejects empty culture fields and files larger than 2MB', function () {
    $admin = User::create([
        'name' => 'Staf Disparbud',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);

    Storage::fake('public');
    $largeFile = UploadedFile::fake()->create('cover.jpg', 2500, 'image/jpeg'); // 2.5 MB

    $payload = [
        'name' => '',
        'category' => 'invalid-category',
        'description' => '',
        'status' => 'invalid-status',
        'cover_image' => $largeFile
    ];

    $this->actingAs($admin)
        ->post(route('admin.cultures.store'), $payload)
        ->assertSessionHasErrors(['name', 'category', 'description', 'status', 'cover_image']);
});

test('admin can update culture details and optional cover image', function () {
    $admin = User::create([
        'name' => 'Staf Disparbud',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);

    $culture = Culture::create([
        'name' => 'Seni Ajeng',
        'slug' => 'seni-ajeng',
        'category' => 'kesenian',
        'cover_image' => 'https://res.cloudinary.com/dummy/old.jpg',
        'description' => 'Seni klasik',
        'status' => 'draft'
    ]);

    $payload = [
        'name' => 'Seni Ajeng Karawang',
        'category' => 'kesenian',
        'description' => 'Seni klasik Karawang',
        'status' => 'published'
    ];

    $this->actingAs($admin)
        ->put(route('admin.cultures.update', $culture->id), $payload)
        ->assertRedirect(route('admin.cultures.index'));

    $this->assertDatabaseHas('cultures', [
        'id' => $culture->id,
        'name' => 'Seni Ajeng Karawang',
        'slug' => 'seni-ajeng-karawang',
        'description' => 'Seni klasik Karawang',
        'status' => 'published',
        'cover_image' => 'https://res.cloudinary.com/dummy/old.jpg' // Unchanged
    ]);
});

test('admin can delete culture', function () {
    $admin = User::create([
        'name' => 'Staf Disparbud',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);

    $culture = Culture::create([
        'name' => 'Kesenian Punah',
        'slug' => 'kesenian-punah',
        'category' => 'tradisi',
        'cover_image' => 'https://res.cloudinary.com/dummy/delete.jpg',
        'description' => 'Dihapus',
        'status' => 'draft'
    ]);

    $this->actingAs($admin)
        ->delete(route('admin.cultures.destroy', $culture->id))
        ->assertRedirect(route('admin.cultures.index'));

    $this->assertDatabaseMissing('cultures', ['id' => $culture->id]);
});
