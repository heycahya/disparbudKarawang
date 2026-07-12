<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('upload galeri gagal jika ukuran gambar melebihi 2MB', function () {
    Storage::fake('local');
    $admin = User::factory()->create(['role' => 'admin']);
    
    // Mock CloudinaryService
    $this->mock(\App\Services\CloudinaryService::class, function ($mock) {
        $mock->shouldReceive('upload')->never();
    });
    
    // Buat file fake berukuran 3MB (3000 KB)
    $largeFile = UploadedFile::fake()->image('photo.jpg')->size(3000);

    $response = $this->actingAs($admin)->post(route('admin.galleries.store'), [
        'title' => 'Foto Wisata',
        'category' => 'wisata',
        'media' => $largeFile,
    ]);

    $response->assertSessionHasErrors(['media']);
});
