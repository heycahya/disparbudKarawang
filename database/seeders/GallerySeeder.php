<?php

namespace Database\Seeders;

use App\Models\Gallery;
use App\Models\User;
use App\Services\CloudinaryService;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first()
            ?? User::factory()->create(['role' => 'admin']);

        $items = [
            [
                'title' => 'Kemegahan Candi Jiwa Batujaya',
                'photo' => CloudinaryService::getSampleUrl('tourism', 0),
                'category' => 'wisata',
            ],
            [
                'title' => 'Keindahan Sunset Pantai Tangkolak',
                'photo' => CloudinaryService::getSampleUrl('tourism', 1),
                'category' => 'wisata',
            ],
            [
                'title' => 'Pertunjukan Tari Jaipong Karawang',
                'photo' => CloudinaryService::getSampleUrl('culture', 0),
                'category' => 'budaya',
            ],
            [
                'title' => 'Kerajinan Batik Karawang Motif Padi',
                'photo' => CloudinaryService::getSampleUrl('ekraf', 0),
                'category' => 'ekraf',
            ],
            [
                'title' => 'Festival Kopi Karawang',
                'photo' => CloudinaryService::getSampleUrl('gallery', 3),
                'category' => 'event',
            ],
            [
                'title' => 'Pemandangan Asri Puncak Sempur',
                'photo' => CloudinaryService::getSampleUrl('tourism', 3),
                'category' => 'wisata',
            ],
        ];

        foreach ($items as $item) {
            Gallery::create([
                'user_id' => $admin->id,
                'title' => $item['title'],
                'photo' => $item['photo'],
                'category' => $item['category'],
            ]);
        }
    }
}
