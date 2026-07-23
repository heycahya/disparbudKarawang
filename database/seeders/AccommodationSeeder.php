<?php

namespace Database\Seeders;

use App\Services\CloudinaryService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AccommodationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(database_path('seeders/data/accommodation.json'));
        $accommodations = json_decode($json, true);

        $typeMap = [
            'Hotel Bintang' => 'hotel',
            'Resort' => 'villa',
            'Hotel Melati' => 'penginapan',
        ];

        $images = [
            'Resinda Hotel Karawang' => [
                'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1584132967334-10e028bd69f7?auto=format&fit=crop&w=800&h=500&q=80',
            ],
            'Mercure Karawang' => [
                'https://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=800&h=500&q=80',
            ],
            'Delonix Hotel Karawang' => [
                'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=800&h=500&q=80',
            ],
            'Swiss-Belinn Karawang' => [
                'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=800&h=500&q=80',
            ],
            'favehotel Karawang' => [
                'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1584132967334-10e028bd69f7?auto=format&fit=crop&w=800&h=500&q=80',
            ],
            'Kampung Turis Resort & Waterpark' => [
                'https://images.unsplash.com/photo-1584132967334-10e028bd69f7?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=800&h=500&q=80',
            ],
            'Rumantara Inn Karawang' => [
                'https://images.unsplash.com/photo-1495365200479-c4ed1d392743?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&w=800&h=500&q=80',
            ],
            'Front One Akshaya Hotel Karawang' => [
                'https://images.unsplash.com/photo-1445019980597-93fa8acb246c?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=800&h=500&q=80',
            ],
        ];

        foreach ($accommodations as $index => $item) {
            $type = $typeMap[$item['tipe']] ?? 'hotel';

            $itemImages = $images[$item['nama_akomodasi']] ?? $images['Resinda Hotel Karawang'];
            $cover = $itemImages[0];

            $accId = DB::table('accommodations')->insertGetId([
                'name' => $item['nama_akomodasi'],
                'slug' => Str::slug($item['nama_akomodasi']),
                'type' => $type,
                'description' => $item['fasilitas_utama'],
                'address' => $item['alamat'],
                'phone' => null,
                'price_range' => $item['estimasi_harga'],
                'cover_image' => CloudinaryService::getUrl($cover, 'accommodation'),
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
                    'imageable_id' => $accId,
                    'imageable_type' => 'App\Models\Accommodation',
                    'photo' => CloudinaryService::getUrl($photo, 'accommodation'),
                    'caption' => $item['nama_akomodasi'] . ' Detail ' . $i,
                    'order' => $i - 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
