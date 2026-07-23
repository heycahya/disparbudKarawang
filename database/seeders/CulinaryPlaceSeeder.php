<?php

namespace Database\Seeders;

use App\Services\CloudinaryService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CulinaryPlaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(database_path('seeders/data/culinary.json'));
        $culinaryPlaces = json_decode($json, true);

        $typeMap = [
            'Makanan Khas' => 'warung',
            'Restoran Tradisional' => 'rumah_makan',
            'Pusat Jajanan' => 'warung',
            'Cafe' => 'cafe',
        ];

        $images = [
            'Soto Tangkar Mang Nean' => [
                'https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1625220194771-7ebedd08d063?auto=format&fit=crop&w=800&h=500&q=80',
            ],
            'Pepes Jambal Walahar H. Dirja' => [
                'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1565557623262-b51c2513a641?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=800&h=500&q=80',
            ],
            'Sorabi Kuntilanak Rengasdengklok M. Kasim' => [
                'https://images.unsplash.com/photo-1587314168485-3236d6710814?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=800&h=500&q=80',
            ],
            'Seafood Teh Empop Karangpawitan' => [
                'https://images.unsplash.com/photo-1565557623262-b51c2513a641?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=800&h=500&q=80',
            ],
            'Sangu Tahu (Sangtau) Alun-Alun' => [
                'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1565557623262-b51c2513a641?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=800&h=500&q=80',
            ],
            'Tutut Kuah Kuning Alun-Alun' => [
                'https://images.unsplash.com/photo-1608897013039-887f21d8c804?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1625220194771-7ebedd08d063?auto=format&fit=crop&w=800&h=500&q=80',
            ],
            'Bubur Ayam Cilamaya H. Dul' => [
                'https://images.unsplash.com/photo-1625220194771-7ebedd08d063?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=800&h=500&q=80',
            ],
            'Koffie Hideung Puncak Sempur' => [
                'https://images.unsplash.com/photo-1497935586351-b67a49e012bf?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&h=500&q=80',
            ],
        ];

        foreach ($culinaryPlaces as $index => $item) {
            $type = $typeMap[$item['kategori']] ?? 'warung';

            $itemImages = $images[$item['nama_kuliner']] ?? $images['Soto Tangkar Mang Nean'];
            $cover = $itemImages[0];

            $culinaryId = DB::table('culinary_places')->insertGetId([
                'name' => $item['nama_kuliner'],
                'slug' => Str::slug($item['nama_kuliner']),
                'type' => $type,
                'description' => $item['deskripsi_kelezatan'],
                'address' => $item['alamat'],
                'phone' => null,
                'price_range' => $item['estimasi_harga'],
                'cover_image' => CloudinaryService::getUrl($cover, 'culinary'),
                'latitude' => null,
                'longitude' => null,
                'status' => 'published',
                'views' => rand(10, 300),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Add 2 related sample photos in the gallery
            for ($i = 1; $i <= 2; $i++) {
                $photo = $itemImages[$i] ?? $itemImages[0];
                DB::table('gallery_photos')->insert([
                    'imageable_id' => $culinaryId,
                    'imageable_type' => 'App\Models\CulinaryPlace',
                    'photo' => CloudinaryService::getUrl($photo, 'culinary'),
                    'caption' => $item['nama_kuliner'] . ' Detail ' . $i,
                    'order' => $i - 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
