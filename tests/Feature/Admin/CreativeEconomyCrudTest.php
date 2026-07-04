<?php

use App\Models\User;
use App\Models\CreativeEconomy;
use Cloudinary\Api\Upload\UploadApi;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    // Truncate tables for clean slate
    CreativeEconomy::query()->delete();
    User::query()->delete();

    // Mock UploadApi
    $this->mock(UploadApi::class, function ($mock) {
        $mock->shouldReceive('upload')
            ->andReturn(new \Cloudinary\Api\ApiResponse([
                'secure_url' => 'https://res.cloudinary.com/dummy/image/upload/ekraf.jpg'
            ], []));
    });
});

test('guest is redirected to login when accessing ekraf management', function () {
    $this->get(route('admin.creative-economies.index'))->assertRedirect(route('login'));
    $this->get(route('admin.creative-economies.create'))->assertRedirect(route('login'));
});

test('public user is forbidden from accessing ekraf management', function () {
    $publicUser = User::create([
        'name' => 'Rakyat Karawang',
        'email' => 'rakyat@example.com',
        'password' => bcrypt('password'),
        'role' => 'public'
    ]);

    $this->actingAs($publicUser)
        ->get(route('admin.creative-economies.index'))
        ->assertStatus(403);
});

test('admin can view ekraf management index and create page', function () {
    $admin = User::create([
        'name' => 'Staf Disparbud',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);

    $this->actingAs($admin)
        ->get(route('admin.creative-economies.index'))
        ->assertStatus(200);

    $this->actingAs($admin)
        ->get(route('admin.creative-economies.create'))
        ->assertStatus(200);
});

test('admin can create a new ekraf with cover image', function () {
    $admin = User::create([
        'name' => 'Staf Disparbud',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);

    Storage::fake('public');
    $file = UploadedFile::fake()->create('cover.jpg', 500, 'image/jpeg');

    $payload = [
        'name' => 'Pengrajin Golok Lubuk',
        'description' => '<p>Kerajinan golok khas Karawang.</p>',
        'owner_name' => 'Pak Karman',
        'contact' => '081234567890',
        'address' => 'Telukjambe, Karawang',
        'status' => 'published',
        'cover_image' => $file
    ];

    $this->actingAs($admin)
        ->post(route('admin.creative-economies.store'), $payload)
        ->assertRedirect(route('admin.creative-economies.index'));

    $this->assertDatabaseHas('creative_economies', [
        'name' => 'Pengrajin Golok Lubuk',
        'slug' => 'pengrajin-golok-lubuk',
        'description' => '<p>Kerajinan golok khas Karawang.</p>',
        'owner_name' => 'Pak Karman',
        'contact' => '081234567890',
        'address' => 'Telukjambe, Karawang',
        'status' => 'published',
        'cover_image' => 'https://res.cloudinary.com/dummy/image/upload/ekraf.jpg'
    ]);
});

test('slug generation resolves collisions on creative_economies by adding numerical suffixes', function () {
    $admin = User::create([
        'name' => 'Staf Disparbud',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);

    // Create first ekraf
    CreativeEconomy::create([
        'name' => 'Batik Karawang',
        'slug' => 'batik-karawang',
        'description' => 'Lorem ipsum',
        'status' => 'draft',
        'cover_image' => 'https://res.cloudinary.com/dummy/ekraf.jpg'
    ]);

    Storage::fake('public');
    $file = UploadedFile::fake()->create('cover.jpg', 100, 'image/jpeg');

    $payload = [
        'name' => 'Batik Karawang',
        'description' => '<p>Toko batik kedua.</p>',
        'status' => 'draft',
        'cover_image' => $file
    ];

    $this->actingAs($admin)
        ->post(route('admin.creative-economies.store'), $payload)
        ->assertRedirect(route('admin.creative-economies.index'));

    $this->assertDatabaseHas('creative_economies', [
        'name' => 'Batik Karawang',
        'slug' => 'batik-karawang-1'
    ]);
});

test('validation rejects empty ekraf fields and files larger than 2MB', function () {
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
        'description' => '',
        'status' => 'invalid-status',
        'cover_image' => $largeFile
    ];

    $this->actingAs($admin)
        ->post(route('admin.creative-economies.store'), $payload)
        ->assertSessionHasErrors(['name', 'description', 'status', 'cover_image']);
});

test('admin can update ekraf details and optional cover image', function () {
    $admin = User::create([
        'name' => 'Staf Disparbud',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);

    $ekraf = CreativeEconomy::create([
        'name' => 'Boneka Cikampek',
        'slug' => 'boneka-cikampek',
        'description' => 'Boneka lucu',
        'status' => 'draft',
        'cover_image' => 'https://res.cloudinary.com/dummy/old.jpg'
    ]);

    $payload = [
        'name' => 'Boneka Cikampek Baru',
        'description' => 'Boneka lucu baru',
        'status' => 'published'
    ];

    $this->actingAs($admin)
        ->put(route('admin.creative-economies.update', $ekraf->id), $payload)
        ->assertRedirect(route('admin.creative-economies.index'));

    $this->assertDatabaseHas('creative_economies', [
        'id' => $ekraf->id,
        'name' => 'Boneka Cikampek Baru',
        'slug' => 'boneka-cikampek-baru',
        'description' => 'Boneka lucu baru',
        'status' => 'published',
        'cover_image' => 'https://res.cloudinary.com/dummy/old.jpg'
    ]);
});

test('admin can delete ekraf', function () {
    $admin = User::create([
        'name' => 'Staf Disparbud',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);

    $ekraf = CreativeEconomy::create([
        'name' => 'Toko Gulung Tikar',
        'slug' => 'toko-gulung-tikar',
        'description' => 'Akan dihapus',
        'status' => 'draft',
        'cover_image' => 'https://res.cloudinary.com/dummy/delete.jpg'
    ]);

    $this->actingAs($admin)
        ->delete(route('admin.creative-economies.destroy', $ekraf->id))
        ->assertRedirect(route('admin.creative-economies.index'));

    $this->assertDatabaseMissing('creative_economies', ['id' => $ekraf->id]);
});
