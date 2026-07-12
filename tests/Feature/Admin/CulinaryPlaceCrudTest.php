<?php

use App\Models\User;
use App\Models\CulinaryPlace;
use Cloudinary\Api\Upload\UploadApi;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    // Truncate tables for clean slate
    CulinaryPlace::query()->delete();
    User::query()->delete();

    // Mock UploadApi
    $this->mock(UploadApi::class, function ($mock) {
        $mock->shouldReceive('upload')
            ->andReturn(new \Cloudinary\Api\ApiResponse([
                'secure_url' => 'https://res.cloudinary.com/dummy/image/upload/culinary.jpg'
            ], []));
    });
});

test('guest is redirected to login when accessing culinary management', function () {
    $this->get(route('admin.culinary-places.index'))->assertRedirect(route('login'));
    $this->get(route('admin.culinary-places.create'))->assertRedirect(route('login'));
});

test('public user is forbidden from accessing culinary management', function () {
    $publicUser = User::create([
        'name' => 'Rakyat Karawang',
        'email' => 'rakyat@example.com',
        'password' => bcrypt('password'),
        'role' => 'public'
    ]);

    $this->actingAs($publicUser)
        ->get(route('admin.culinary-places.index'))
        ->assertStatus(403);
});

test('admin can view culinary management index and create page', function () {
    $admin = User::create([
        'name' => 'Staf Disparbud',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);

    $this->actingAs($admin)
        ->get(route('admin.culinary-places.index'))
        ->assertStatus(200);

    $this->actingAs($admin)
        ->get(route('admin.culinary-places.create'))
        ->assertStatus(200);
});

test('admin can create a new culinary place with cover image', function () {
    $admin = User::create([
        'name' => 'Staf Disparbud',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);

    Storage::fake('public');
    $file = UploadedFile::fake()->create('cover.jpg', 500, 'image/jpeg');

    $payload = [
        'name' => 'Soto Gempol',
        'type' => 'warung',
        'description' => '<p>Kuliner soto legendaris khas Karawang.</p>',
        'address' => 'Jl. Gempol, Karawang',
        'phone' => '0812345678',
        'price_range' => 'Rp 20.000 - Rp 40.000',
        'latitude' => -6.31244,
        'longitude' => 107.31567,
        'status' => 'published',
        'cover_image' => $file
    ];

    $this->actingAs($admin)
        ->post(route('admin.culinary-places.store'), $payload)
        ->assertRedirect(route('admin.culinary-places.index'));

    $this->assertDatabaseHas('culinary_places', [
        'name' => 'Soto Gempol',
        'slug' => 'soto-gempol',
        'type' => 'warung',
        'description' => '<p>Kuliner soto legendaris khas Karawang.</p>',
        'address' => 'Jl. Gempol, Karawang',
        'phone' => '0812345678',
        'price_range' => 'Rp 20.000 - Rp 40.000',
        'latitude' => -6.31244,
        'longitude' => 107.31567,
        'status' => 'published',
        'cover_image' => 'https://res.cloudinary.com/dummy/image/upload/culinary.jpg'
    ]);
});

test('slug generation resolves collisions on culinary places by adding numerical suffixes', function () {
    $admin = User::create([
        'name' => 'Staf Disparbud',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);

    // Create first culinary place
    CulinaryPlace::create([
        'name' => 'Cafe Rindang',
        'slug' => 'cafe-rindang',
        'type' => 'cafe',
        'description' => 'Lorem ipsum',
        'address' => 'Karawang Barat',
        'status' => 'draft',
        'cover_image' => 'https://res.cloudinary.com/dummy/culinary.jpg'
    ]);

    Storage::fake('public');
    $file = UploadedFile::fake()->create('cover.jpg', 100, 'image/jpeg');

    $payload = [
        'name' => 'Cafe Rindang',
        'type' => 'cafe',
        'description' => '<p>Cafe kedua.</p>',
        'address' => 'Karawang Timur',
        'status' => 'draft',
        'cover_image' => $file
    ];

    $this->actingAs($admin)
        ->post(route('admin.culinary-places.store'), $payload)
        ->assertRedirect(route('admin.culinary-places.index'));

    $this->assertDatabaseHas('culinary_places', [
        'name' => 'Cafe Rindang',
        'slug' => 'cafe-rindang-1'
    ]);
});

test('validation rejects empty culinary place fields and files larger than 2MB', function () {
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
        'type' => 'invalid-type',
        'description' => '',
        'address' => '',
        'latitude' => 'invalid-lat',
        'longitude' => 'invalid-lng',
        'status' => 'invalid-status',
        'cover_image' => $largeFile
    ];

    $this->actingAs($admin)
        ->post(route('admin.culinary-places.store'), $payload)
        ->assertSessionHasErrors(['name', 'type', 'description', 'address', 'latitude', 'longitude', 'status', 'cover_image']);
});

test('admin can update culinary place details and optional cover image', function () {
    $admin = User::create([
        'name' => 'Staf Disparbud',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);

    $culinary = CulinaryPlace::create([
        'name' => 'Warung Nasi Sunda',
        'slug' => 'warung-nasi-sunda',
        'type' => 'warung',
        'description' => 'Nasi sunda',
        'address' => 'Telukjambe, Karawang',
        'status' => 'draft',
        'cover_image' => 'https://res.cloudinary.com/dummy/old.jpg'
    ]);

    $payload = [
        'name' => 'Warung Nasi Sunda Asli',
        'type' => 'warung',
        'description' => 'Nasi sunda enak',
        'address' => 'Telukjambe Baru, Karawang',
        'status' => 'published'
    ];

    $this->actingAs($admin)
        ->put(route('admin.culinary-places.update', $culinary->id), $payload)
        ->assertRedirect(route('admin.culinary-places.index'));

    $this->assertDatabaseHas('culinary_places', [
        'id' => $culinary->id,
        'name' => 'Warung Nasi Sunda Asli',
        'slug' => 'warung-nasi-sunda-asli',
        'description' => 'Nasi sunda enak',
        'status' => 'published',
        'cover_image' => 'https://res.cloudinary.com/dummy/old.jpg'
    ]);
});

test('admin can delete culinary place', function () {
    $admin = User::create([
        'name' => 'Staf Disparbud',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);

    $culinary = CulinaryPlace::create([
        'name' => 'Kuliner Sepi',
        'slug' => 'kuliner-sepi',
        'type' => 'restoran',
        'description' => 'Akan dihapus',
        'address' => 'Karawang',
        'status' => 'draft',
        'cover_image' => 'https://res.cloudinary.com/dummy/delete.jpg'
    ]);

    $this->actingAs($admin)
        ->delete(route('admin.culinary-places.destroy', $culinary->id))
        ->assertRedirect(route('admin.culinary-places.index'));

    $this->assertDatabaseMissing('culinary_places', ['id' => $culinary->id]);
});
