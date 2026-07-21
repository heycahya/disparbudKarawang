<?php

namespace Database\Seeders;

use App\Models\Gallery;
use App\Models\User;
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
                'photo' => 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&w=800&q=80',
                'category' => 'wisata',
            ],
            [
                'title' => 'Keindahan Sunset Pantai Tangkolak',
                'photo' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80',
                'category' => 'wisata',
            ],
            [
                'title' => 'Pertunjukan Tari Jaipong Karawang',
                'photo' => 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&w=800&q=80',
                'category' => 'budaya',
            ],
            [
                'title' => 'Kerajinan Batik Karawang Motif Padi',
                'photo' => 'https://images.unsplash.com/photo-1606744888344-493238951221?auto=format&fit=crop&w=800&q=80',
                'category' => 'ekraf',
            ],
            [
                'title' => 'Festival Festival Kopi Karawang',
                'photo' => 'https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?auto=format&fit=crop&w=800&q=80',
                'category' => 'event',
            ],
            [
                'title' => 'Pemandangan Asri Puncak Sempur',
                'photo' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80',
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
