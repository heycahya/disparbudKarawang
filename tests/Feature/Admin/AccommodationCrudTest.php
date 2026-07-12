<?php

use App\Models\User;
use App\Models\Accommodation;
use Cloudinary\Api\Upload\UploadApi;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    // Truncate tables for clean slate
    Accommodation::query()->delete();
    User::query()->delete();

    // Mock UploadApi
    $this->mock(UploadApi::class, function ($mock) {
        $mock->shouldReceive('upload')
            ->andReturn(new \Cloudinary\Api\ApiResponse([
                'secure_url' => 'https://res.cloudinary.com/dummy/image/upload/accommodation.jpg'
            ], []));
    });
});

test('guest is redirected to login when accessing accommodation management', function () {
    $this->get(route('admin.accommodations.index'))->assertRedirect(route('login'));
    $this->get(route('admin.accommodations.create'))->assertRedirect(route('login'));
});

test('public user is forbidden from accessing accommodation management', function () {
    $publicUser = User::create([
        'name' => 'Rakyat Karawang',
        'email' => 'rakyat@example.com',
        'password' => bcrypt('password'),
        'role' => 'public'
    ]);

    $this->actingAs($publicUser)
        ->get(route('admin.accommodations.index'))
        ->assertStatus(403);
});

test('admin can view accommodation management index and create page', function () {
    $admin = User::create([
        'name' => 'Staf Disparbud',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);

    $this->actingAs($admin)
        ->get(route('admin.accommodations.index'))
        ->assertStatus(200);

    $this->actingAs($admin)
        ->get(route('admin.accommodations.create'))
        ->assertStatus(200);
});

test('admin can create a new accommodation with cover image', function () {
    $admin = User::create([
        'name' => 'Staf Disparbud',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);

    Storage::fake('public');
    $file = UploadedFile::fake()->create('cover.jpg', 500, 'image/jpeg');

    $payload = [
        'name' => 'Hotel Grand Karawang',
        'type' => 'hotel',
        'description' => '<p>Hotel mewah bintang 4.</p>',
        'address' => 'Jl. Tuparev No. 12, Karawang',
        'phone' => '0267-123456',
        'price_range' => 'Rp 500.000 - Rp 1.200.000',
        'latitude' => -6.30244,
        'longitude' => 107.30567,
        'status' => 'published',
        'cover_image' => $file
    ];

    $this->actingAs($admin)
        ->post(route('admin.accommodations.store'), $payload)
        ->assertRedirect(route('admin.accommodations.index'));

    $this->assertDatabaseHas('accommodations', [
        'name' => 'Hotel Grand Karawang',
        'slug' => 'hotel-grand-karawang',
        'type' => 'hotel',
        'description' => '<p>Hotel mewah bintang 4.</p>',
        'address' => 'Jl. Tuparev No. 12, Karawang',
        'phone' => '0267-123456',
        'price_range' => 'Rp 500.000 - Rp 1.200.000',
        'latitude' => -6.30244,
        'longitude' => 107.30567,
        'status' => 'published',
        'cover_image' => 'https://res.cloudinary.com/dummy/image/upload/accommodation.jpg'
    ]);
});

test('slug generation resolves collisions on accommodations by adding numerical suffixes', function () {
    $admin = User::create([
        'name' => 'Staf Disparbud',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);

    // Create first accommodation
    Accommodation::create([
        'name' => 'Villa Hijau',
        'slug' => 'villa-hijau',
        'type' => 'villa',
        'description' => 'Lorem ipsum',
        'address' => 'Loji, Karawang',
        'status' => 'draft',
        'cover_image' => 'https://res.cloudinary.com/dummy/accommodation.jpg'
    ]);

    Storage::fake('public');
    $file = UploadedFile::fake()->create('cover.jpg', 100, 'image/jpeg');

    $payload = [
        'name' => 'Villa Hijau',
        'type' => 'villa',
        'description' => '<p>Villa kedua.</p>',
        'address' => 'Loji Baru, Karawang',
        'status' => 'draft',
        'cover_image' => $file
    ];

    $this->actingAs($admin)
        ->post(route('admin.accommodations.store'), $payload)
        ->assertRedirect(route('admin.accommodations.index'));

    $this->assertDatabaseHas('accommodations', [
        'name' => 'Villa Hijau',
        'slug' => 'villa-hijau-1'
    ]);
});

test('validation rejects empty accommodation fields and files larger than 2MB', function () {
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
        ->post(route('admin.accommodations.store'), $payload)
        ->assertSessionHasErrors(['name', 'type', 'description', 'address', 'latitude', 'longitude', 'status', 'cover_image']);
});

test('admin can update accommodation details and optional cover image', function () {
    $admin = User::create([
        'name' => 'Staf Disparbud',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);

    $accommodation = Accommodation::create([
        'name' => 'Homestay Candi',
        'slug' => 'homestay-candi',
        'type' => 'homestay',
        'description' => 'Dekat candi',
        'address' => 'Batujaya, Karawang',
        'status' => 'draft',
        'cover_image' => 'https://res.cloudinary.com/dummy/old.jpg'
    ]);

    $payload = [
        'name' => 'Homestay Candi Jiwa',
        'type' => 'homestay',
        'description' => 'Dekat Candi Jiwa',
        'address' => 'Batujaya, Karawang',
        'status' => 'published'
    ];

    $this->actingAs($admin)
        ->put(route('admin.accommodations.update', $accommodation->id), $payload)
        ->assertRedirect(route('admin.accommodations.index'));

    $this->assertDatabaseHas('accommodations', [
        'id' => $accommodation->id,
        'name' => 'Homestay Candi Jiwa',
        'slug' => 'homestay-candi-jiwa',
        'description' => 'Dekat Candi Jiwa',
        'status' => 'published',
        'cover_image' => 'https://res.cloudinary.com/dummy/old.jpg'
    ]);
});

test('admin can delete accommodation', function () {
    $admin = User::create([
        'name' => 'Staf Disparbud',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);

    $accommodation = Accommodation::create([
        'name' => 'Hotel Bangkrut',
        'slug' => 'hotel-bangkrut',
        'type' => 'hotel',
        'description' => 'Akan dihapus',
        'address' => 'Karawang Barat',
        'status' => 'draft',
        'cover_image' => 'https://res.cloudinary.com/dummy/delete.jpg'
    ]);

    $this->actingAs($admin)
        ->delete(route('admin.accommodations.destroy', $accommodation->id))
        ->assertRedirect(route('admin.accommodations.index'));

    $this->assertDatabaseMissing('accommodations', ['id' => $accommodation->id]);
});
