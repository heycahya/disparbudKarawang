<?php

namespace Database\Seeders;

use App\Services\CloudinaryService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TourismDestinationSeeder extends Seeder
{
    public function run(): void
    {
        $categories = DB::table('tourism_categories')->pluck('id', 'slug');

        $json = file_get_contents(database_path('seeders/data/tourism.json'));
        $destinations = json_decode($json, true);

        $categoryMap = [
            'Alam' => 'alam',
            'Desa Wisata' => 'alam',
            'Sejarah' => 'sejarahcagar-budaya',
            'Buatan' => 'buatanrekreasi',
            'Bahari' => 'alam',
        ];

        $images = [
            'Curug Cigentis' => [
                'https://images.unsplash.com/photo-1508739773434-c26b3d09e071?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1473081556163-2a17de81fc97?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1447752875215-b2761acb3c5d?auto=format&fit=crop&w=800&h=500&q=80',
            ],
            'Green Canyon Karawang' => [
                'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1501854140801-50d01698950b?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1446776811953-b23d57bd21aa?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1472214222541-d510753a4707?auto=format&fit=crop&w=800&h=500&q=80',
            ],
            'Puncak Sempur' => [
                'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1434064511983-18c6dae20ed5?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1470252649378-9c29740c9fa8?auto=format&fit=crop&w=800&h=500&q=80',
            ],
            'Candi Jiwa' => [
                'https://images.unsplash.com/photo-1596701062351-df5f8af54b85?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1605649487212-47bdab064df7?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1544816155-12df9643f363?auto=format&fit=crop&w=800&h=500&q=80',
            ],
            'Candi Blandongan' => [
                'https://images.unsplash.com/photo-1544816155-12df9643f363?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1605649487212-47bdab064df7?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1596701062351-df5f8af54b85?auto=format&fit=crop&w=800&h=500&q=80',
            ],
            'Monumen Kebulatan Tekad Rengasdengklok' => [
                'https://images.unsplash.com/photo-1572945281869-e7bc7ca84a2b?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1475721027785-f74eccf877e2?auto=format&fit=crop&w=800&h=500&q=80',
            ],
            'Kampung Turis Karawang' => [
                'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=800&h=500&q=80',
            ],
            'Pantai Tanjung Pakis' => [
                'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1519046904884-53103b34b206?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1505118380757-91f5f5632de0?auto=format&fit=crop&w=800&h=500&q=80',
                'https://images.unsplash.com/photo-1548574505-5e239809ee19?auto=format&fit=crop&w=800&h=500&q=80',
            ],
        ];

        foreach ($destinations as $index => $item) {
            $mappedCatSlug = $categoryMap[$item['kategori']] ?? 'alam';
            $catId = $categories[$mappedCatSlug] ?? null;
            if (!$catId) continue;

            $slug = Str::slug($item['nama_tempat']);

            $nameKey = $item['nama_tempat'];
            if ($nameKey === 'Monumen Kebulatan Tekad Rengasdengklok') {
                $nameKey = 'Monumen Kebulatan Tekad Rengasdengklok';
            }
            // Fallback match to keys
            $itemImages = $images[$nameKey] ?? $images['Curug Cigentis'];

            $cover = $itemImages[0];

            $destId = DB::table('tourism_destinations')->insertGetId([
                'tourism_category_id' => $catId,
                'name' => $item['nama_tempat'],
                'slug' => $slug,
                'description' => $item['deskripsi'],
                'address' => $item['alamat'],
                'latitude' => isset($item['latitude']) ? (float)$item['latitude'] : null,
                'longitude' => isset($item['longitude']) ? (float)$item['longitude'] : null,
                'cover_image' => CloudinaryService::getUrl($cover, 'tourism'),
                'status' => 'published',
                'views' => rand(10, 500),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Add 3 related sample photos in the gallery
            for ($i = 1; $i <= 3; $i++) {
                $photo = $itemImages[$i] ?? $itemImages[0];
                DB::table('tourism_destination_photos')->insert([
                    'tourism_destination_id' => $destId,
                    'photo' => CloudinaryService::getUrl($photo, 'tourism'),
                    'caption' => $item['nama_tempat'] . ' Detail ' . $i,
                    'order' => $i - 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
