<?php

use App\Models\User;
use App\Models\TourismDestination;
use App\Models\TourismCategory;
use Cloudinary\Api\Upload\UploadApi;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    // Truncate tables for clean slate (State Clean rule)
    TourismDestination::query()->delete();
    TourismCategory::query()->delete();
    User::query()->delete();

    // Create a default category
    $this->category = TourismCategory::create([
        'name' => 'Wisata Alam',
        'slug' => 'wisata-alam'
    ]);

    // Mock UploadApi
    $this->mock(UploadApi::class, function ($mock) {
        $mock->shouldReceive('upload')
            ->andReturn(new \Cloudinary\Api\ApiResponse([
                'secure_url' => 'https://res.cloudinary.com/dummy/image/upload/tourism.jpg'
            ], []));
    });
});

test('guest is redirected to login when accessing tourism management', function () {
    $this->get(route('admin.tourism-destinations.index'))->assertRedirect(route('login'));
    $this->get(route('admin.tourism-destinations.create'))->assertRedirect(route('login'));
});

test('public user is forbidden from accessing tourism management', function () {
    $publicUser = User::create([
        'name' => 'Rakyat Karawang',
        'email' => 'rakyat@example.com',
        'password' => bcrypt('password'),
        'role' => 'public'
    ]);

    $this->actingAs($publicUser)
        ->get(route('admin.tourism-destinations.index'))
        ->assertStatus(403);
});

test('admin can view tourism management index and create page', function () {
    $admin = User::create([
        'name' => 'Staf Disparbud',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);

    $this->actingAs($admin)
        ->get(route('admin.tourism-destinations.index'))
        ->assertStatus(200);

    $this->actingAs($admin)
        ->get(route('admin.tourism-destinations.create'))
        ->assertStatus(200);
});

test('admin can create a new tourism destination with cover image', function () {
    $admin = User::create([
        'name' => 'Staf Disparbud',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);

    Storage::fake('public');
    $file = UploadedFile::fake()->create('cover.jpg', 500, 'image/jpeg'); // 500 KB

    $payload = [
        'name' => 'Curug Cigentis',
        'tourism_category_id' => $this->category->id,
        'description' => '<p>Destinasi wisata air terjun yang indah.</p>',
        'address' => 'Mekarbuana, Tegalwaru, Karawang',
        'latitude' => -6.50244,
        'longitude' => 107.25567,
        'status' => 'published',
        'cover_image' => $file
    ];

    $this->actingAs($admin)
        ->post(route('admin.tourism-destinations.store'), $payload)
        ->assertRedirect(route('admin.tourism-destinations.index'));

    $this->assertDatabaseHas('tourism_destinations', [
        'name' => 'Curug Cigentis',
        'slug' => 'curug-cigentis',
        'tourism_category_id' => $this->category->id,
        'address' => 'Mekarbuana, Tegalwaru, Karawang',
        'latitude' => -6.50244,
        'longitude' => 107.25567,
        'status' => 'published',
        'cover_image' => 'https://res.cloudinary.com/dummy/image/upload/tourism.jpg'
    ]);
});

test('slug generation resolves collisions on destinations by adding numerical suffixes', function () {
    $admin = User::create([
        'name' => 'Staf Disparbud',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);

    // Create first destination
    TourismDestination::create([
        'tourism_category_id' => $this->category->id,
        'name' => 'Pantai Pakis',
        'slug' => 'pantai-pakis',
        'cover_image' => 'https://res.cloudinary.com/dummy/tourism.jpg',
        'description' => 'Lorem ipsum',
        'address' => 'Batujaya, Karawang',
        'status' => 'draft'
    ]);

    Storage::fake('public');
    $file = UploadedFile::fake()->create('cover.jpg', 100, 'image/jpeg');

    $payload = [
        'name' => 'Pantai Pakis',
        'tourism_category_id' => $this->category->id,
        'description' => '<p>Pantai kedua.</p>',
        'address' => 'Batujaya Baru, Karawang',
        'status' => 'draft',
        'cover_image' => $file
    ];

    $this->actingAs($admin)
        ->post(route('admin.tourism-destinations.store'), $payload)
        ->assertRedirect(route('admin.tourism-destinations.index'));

    $this->assertDatabaseHas('tourism_destinations', [
        'name' => 'Pantai Pakis',
        'slug' => 'pantai-pakis-1'
    ]);
});

test('validation rejects empty destination fields and files larger than 2MB', function () {
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
        'tourism_category_id' => 9999, // Non-existent category
        'description' => '',
        'address' => '',
        'latitude' => 'invalid-lat',
        'longitude' => 'invalid-lng',
        'status' => 'invalid-status',
        'cover_image' => $largeFile
    ];

    $this->actingAs($admin)
        ->post(route('admin.tourism-destinations.store'), $payload)
        ->assertSessionHasErrors(['name', 'tourism_category_id', 'description', 'address', 'latitude', 'longitude', 'status', 'cover_image']);
});

test('admin can update destination details and optional cover image', function () {
    $admin = User::create([
        'name' => 'Staf Disparbud',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);

    $destination = TourismDestination::create([
        'tourism_category_id' => $this->category->id,
        'name' => 'Wisata Gunung',
        'slug' => 'wisata-gunung',
        'cover_image' => 'https://res.cloudinary.com/dummy/old-cover.jpg',
        'description' => 'Gunung lama',
        'address' => 'Sanggabuana, Karawang',
        'status' => 'draft'
    ]);

    // Update payload without file
    $payload = [
        'name' => 'Wisata Gunung Sanggabuana',
        'tourism_category_id' => $this->category->id,
        'description' => 'Gunung baru',
        'address' => 'Sanggabuana Raya, Karawang',
        'latitude' => -6.602,
        'longitude' => 107.123,
        'status' => 'published'
    ];

    $this->actingAs($admin)
        ->put(route('admin.tourism-destinations.update', $destination->id), $payload)
        ->assertRedirect(route('admin.tourism-destinations.index'));

    $this->assertDatabaseHas('tourism_destinations', [
        'id' => $destination->id,
        'name' => 'Wisata Gunung Sanggabuana',
        'slug' => 'wisata-gunung-sanggabuana',
        'description' => 'Gunung baru',
        'address' => 'Sanggabuana Raya, Karawang',
        'latitude' => -6.602,
        'longitude' => 107.123,
        'status' => 'published',
        'cover_image' => 'https://res.cloudinary.com/dummy/old-cover.jpg' // Unchanged
    ]);
});

test('admin can delete destination', function () {
    $admin = User::create([
        'name' => 'Staf Disparbud',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);

    $destination = TourismDestination::create([
        'tourism_category_id' => $this->category->id,
        'name' => 'Wisata Hancur',
        'slug' => 'wisata-hancur',
        'cover_image' => 'https://res.cloudinary.com/dummy/delete.jpg',
        'description' => 'Dihapus',
        'address' => 'Karawang',
        'status' => 'draft'
    ]);

    $this->actingAs($admin)
        ->delete(route('admin.tourism-destinations.destroy', $destination->id))
        ->assertRedirect(route('admin.tourism-destinations.index'));

    $this->assertDatabaseMissing('tourism_destinations', ['id' => $destination->id]);
});
