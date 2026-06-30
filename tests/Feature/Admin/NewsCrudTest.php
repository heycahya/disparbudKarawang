<?php

use App\Models\User;
use App\Models\News;
use App\Models\NewsCategory;
use Cloudinary\Api\Upload\UploadApi;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    // Truncate tables for clean slate (State Clean rule)
    News::query()->delete();
    NewsCategory::query()->delete();
    User::query()->delete();

    // Create a default category
    $this->category = NewsCategory::create([
        'name' => 'Budaya',
        'slug' => 'budaya'
    ]);

    // Mock UploadApi
    $this->mock(UploadApi::class, function ($mock) {
        $mock->shouldReceive('upload')
            ->andReturn(new \Cloudinary\Api\ApiResponse([
                'secure_url' => 'https://res.cloudinary.com/dummy/image/upload/news.jpg'
            ], []));
    });
});

test('guest is redirected to login when accessing news management', function () {
    $this->get(route('admin.news.index'))->assertRedirect(route('login'));
    $this->get(route('admin.news.create'))->assertRedirect(route('login'));
});

test('public user is forbidden from accessing news management', function () {
    $publicUser = User::create([
        'name' => 'Rakyat Karawang',
        'email' => 'rakyat@example.com',
        'password' => bcrypt('password'),
        'role' => 'public'
    ]);

    $this->actingAs($publicUser)
        ->get(route('admin.news.index'))
        ->assertStatus(403);
});

test('admin can view news management index and create page', function () {
    $admin = User::create([
        'name' => 'Staf Disparbud',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);

    $this->actingAs($admin)
        ->get(route('admin.news.index'))
        ->assertStatus(200);

    $this->actingAs($admin)
        ->get(route('admin.news.create'))
        ->assertStatus(200);
});

test('admin can create a new news post with cover thumbnail', function () {
    $admin = User::create([
        'name' => 'Staf Disparbud',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);

    Storage::fake('public');
    $file = UploadedFile::fake()->create('cover.jpg', 500, 'image/jpeg'); // 500 KB

    $payload = [
        'title' => 'Festival Goyang Karawang 2026',
        'news_category_id' => $this->category->id,
        'content' => '<p>Konten festival kesenian goyang karawang.</p>',
        'status' => 'published',
        'thumbnail' => $file
    ];

    $this->actingAs($admin)
        ->post(route('admin.news.store'), $payload)
        ->assertRedirect(route('admin.news.index'));

    $this->assertDatabaseHas('news', [
        'title' => 'Festival Goyang Karawang 2026',
        'slug' => 'festival-goyang-karawang-2026',
        'news_category_id' => $this->category->id,
        'status' => 'published',
        'thumbnail' => 'https://res.cloudinary.com/dummy/image/upload/news.jpg'
    ]);
});

test('slug generation resolves collisions by adding numerical suffixes', function () {
    $admin = User::create([
        'name' => 'Staf Disparbud',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);

    // Create first news
    News::create([
        'user_id' => $admin->id,
        'news_category_id' => $this->category->id,
        'title' => 'Kesenian Karawang',
        'slug' => 'kesenian-karawang',
        'thumbnail' => 'https://res.cloudinary.com/dummy/news.jpg',
        'content' => 'Lorem ipsum',
        'status' => 'draft'
    ]);

    Storage::fake('public');
    $file = UploadedFile::fake()->create('cover.jpg', 100, 'image/jpeg');

    $payload = [
        'title' => 'Kesenian Karawang',
        'news_category_id' => $this->category->id,
        'content' => '<p>Isi berita kedua.</p>',
        'status' => 'draft',
        'thumbnail' => $file
    ];

    $this->actingAs($admin)
        ->post(route('admin.news.store'), $payload)
        ->assertRedirect(route('admin.news.index'));

    // The second news should have 'kesenian-karawang-1' as its slug
    $this->assertDatabaseHas('news', [
        'title' => 'Kesenian Karawang',
        'slug' => 'kesenian-karawang-1'
    ]);
});

test('validation rejects empty fields and files larger than 2MB', function () {
    $admin = User::create([
        'name' => 'Staf Disparbud',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);

    Storage::fake('public');
    $largeFile = UploadedFile::fake()->create('cover.jpg', 2500, 'image/jpeg'); // 2.5 MB

    $payload = [
        'title' => '',
        'news_category_id' => 9999, // Non-existent category
        'content' => '',
        'status' => 'invalid-status',
        'thumbnail' => $largeFile
    ];

    $this->actingAs($admin)
        ->post(route('admin.news.store'), $payload)
        ->assertSessionHasErrors(['title', 'news_category_id', 'content', 'status', 'thumbnail']);
});

test('admin can update news content and optional thumbnail', function () {
    $admin = User::create([
        'name' => 'Staf Disparbud',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);

    $news = News::create([
        'user_id' => $admin->id,
        'news_category_id' => $this->category->id,
        'title' => 'Berita Lama',
        'slug' => 'berita-lama',
        'thumbnail' => 'https://res.cloudinary.com/dummy/old.jpg',
        'content' => 'Konten lama',
        'status' => 'draft'
    ]);

    // Update payload without file
    $payload = [
        'title' => 'Berita Baru Terupdate',
        'news_category_id' => $this->category->id,
        'content' => 'Konten terupdate',
        'status' => 'published'
    ];

    $this->actingAs($admin)
        ->put(route('admin.news.update', $news->id), $payload)
        ->assertRedirect(route('admin.news.index'));

    $this->assertDatabaseHas('news', [
        'id' => $news->id,
        'title' => 'Berita Baru Terupdate',
        'slug' => 'berita-baru-terupdate',
        'content' => 'Konten terupdate',
        'status' => 'published',
        'thumbnail' => 'https://res.cloudinary.com/dummy/old.jpg' // Unchanged
    ]);
});

test('admin can delete news post', function () {
    $admin = User::create([
        'name' => 'Staf Disparbud',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);

    $news = News::create([
        'user_id' => $admin->id,
        'news_category_id' => $this->category->id,
        'title' => 'Berita Dihapus',
        'slug' => 'berita-dihapus',
        'thumbnail' => 'https://res.cloudinary.com/dummy/delete.jpg',
        'content' => 'Konten dihapus',
        'status' => 'draft'
    ]);

    $this->actingAs($admin)
        ->delete(route('admin.news.destroy', $news->id))
        ->assertRedirect(route('admin.news.index'));

    $this->assertDatabaseMissing('news', ['id' => $news->id]);
});
