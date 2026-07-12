<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TourismDestinationSeeder extends Seeder
{
    public function run(): void
    {
        $categories = DB::table('tourism_categories')->pluck('id', 'slug');

        $destinations = [
            [
                'name' => 'Candi Jiwa (Percandian Batujaya)',
                'category_slug' => 'sejarahcagar-budaya',
                'description' => 'Situs arkeologi Buddha tertua di tatar Sunda, era Kerajaan Tarumanegara (abad 2–6 M), berupa gundukan bata merah setinggi 4–6 m di tengah sawah. Menjadi saksi bisu peradaban awal Nusantara yang kini menjadi situs warisan nasional yang dijaga ketat.',
                'address' => 'Desa Segaran, Kec. Batujaya',
                'latitude' => -6.05563,
                'longitude' => 107.15351,
                'cover_image' => 'https://images.unsplash.com/photo-1508193638397-1c4234db14d8?w=1200&q=80',
                'photos' => [
                    'https://images.unsplash.com/photo-1567157577867-05ccb1388e66?w=1200&q=80',
                    'https://images.unsplash.com/photo-1544930876-2a5c3da3dd5a?w=1200&q=80',
                    'https://images.unsplash.com/photo-1583394293214-0b42c0781832?w=1200&q=80',
                ],
            ],
            [
                'name' => 'Monumen Perjuangan Rawagede',
                'category_slug' => 'sejarahcagar-budaya',
                'description' => 'Monumen dua lantai segi delapan (diresmikan 1996), mengenang pembantaian warga sipil oleh militer Belanda 9 Des 1947, menginspirasi sajak Chairil Anwar "Karawang-Bekasi".',
                'address' => 'Jl. Monumen Rawagede No. 2, Desa Balongsari, Kec. Rawamerta',
                'latitude' => -6.23855,
                'longitude' => 107.32657,
                'cover_image' => 'https://images.unsplash.com/photo-1526958097901-5e6d742d3371?w=1200&q=80',
                'photos' => [
                    'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=1200&q=80',
                    'https://images.unsplash.com/photo-1574271143515-5cddf8da19be?w=1200&q=80',
                ],
            ],
            [
                'name' => 'Tugu Kebulatan Tekad',
                'category_slug' => 'sejarahcagar-budaya',
                'description' => 'Didirikan 1950, bekas markas PETA, memperingati Peristiwa Rengasdengklok — penculikan Soekarno-Hatta menjelang Proklamasi 17 Agustus 1945.',
                'address' => 'Desa Rengasdengklok Utara, Kec. Rengasdengklok',
                'latitude' => -6.16194,
                'longitude' => 107.29277,
                'cover_image' => 'https://images.unsplash.com/photo-1555636222-cae831e670b3?w=1200&q=80',
                'photos' => [
                    'https://images.unsplash.com/photo-1484627147104-f5197bcd6651?w=1200&q=80',
                ],
            ],
            [
                'name' => 'Pantai Tangkolak & Hutan Mangrove',
                'category_slug' => 'alam',
                'description' => 'Hutan mangrove untuk mitigasi abrasi, plus wisata sejarah bawah air (kerangka kapal VOC & koin peninggalan 1799). Surga bagi pecinta alam dan fotografer alam liar.',
                'address' => 'Desa Sukakerta, Kec. Cilamaya Wetan',
                'latitude' => -5.81753,
                'longitude' => 107.11046,
                'cover_image' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1200&q=80',
                'photos' => [
                    'https://images.unsplash.com/photo-1547036967-23d11aacaee0?w=1200&q=80',
                    'https://images.unsplash.com/photo-1520520731457-9283dd14aa66?w=1200&q=80',
                    'https://images.unsplash.com/photo-1559128010-7c1ad6e1b6a5?w=1200&q=80',
                ],
            ],
            [
                'name' => 'Pantai Tanjung Pakis',
                'category_slug' => 'alam',
                'description' => 'Ikon wisata massal di ujung utara Karawang, ramai saat libur panjang. Menawarkan pemandangan matahari terbenam yang memukau dan kuliner seafood segar.',
                'address' => 'Kec. Tanjung Pakis',
                'latitude' => -5.89200,
                'longitude' => 107.26800,
                'cover_image' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1200&q=80',
                'photos' => [
                    'https://images.unsplash.com/photo-1506929562872-bb421503ef21?w=1200&q=80',
                    'https://images.unsplash.com/photo-1519046904884-53103b34b206?w=1200&q=80',
                    'https://images.unsplash.com/photo-1473186505569-9c61870c11f9?w=1200&q=80',
                ],
            ],
            [
                'name' => 'Pantai Samudra Baru',
                'category_slug' => 'alam',
                'description' => 'Destinasi bahari andalan, ramai saat libur lebaran. Penopang ekonomi nelayan lokal dan destinasi wisata keluarga yang terjangkau di pesisir utara Karawang.',
                'address' => 'Kec. Pedes',
                'latitude' => -6.01200,
                'longitude' => 107.22000,
                'cover_image' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1200&q=80',
                'photos' => [
                    'https://images.unsplash.com/photo-1488085061387-422e29b40080?w=1200&q=80',
                    'https://images.unsplash.com/photo-1439405326854-014607f694d7?w=1200&q=80',
                ],
            ],
            [
                'name' => 'Makam Syekh Quro',
                'category_slug' => 'religi',
                'description' => 'Situs ziarah ulama penyebar Islam generasi awal di pesisir utara Jawa. Menjadi destinasi wisata religi yang dikunjungi ribuan peziarah setiap tahunnya.',
                'address' => 'Kel. Tanjungpura, Kec. Karawang Barat',
                'latitude' => -6.27778,
                'longitude' => 107.28972,
                'cover_image' => 'https://images.unsplash.com/photo-1564769625905-50e93615e769?w=1200&q=80',
                'photos' => [
                    'https://images.unsplash.com/photo-1575674088105-8cdf9b8b95d5?w=1200&q=80',
                    'https://images.unsplash.com/photo-1584551246679-0daf3d275d0f?w=1200&q=80',
                ],
            ],
            [
                'name' => 'Makam Keramat Nagasari',
                'category_slug' => 'religi',
                'description' => 'Bagian sirkuit wisata ziarah Karawang yang bersejarah. Tempat ini dikunjungi oleh peziarah yang ingin mendekatkan diri dengan warisan religi Karawang.',
                'address' => 'Kec. Karawang Barat',
                'latitude' => -6.30100,
                'longitude' => 107.29500,
                'cover_image' => 'https://images.unsplash.com/photo-1564769625905-50e93615e769?w=1200&q=80',
                'photos' => [],
            ],
            [
                'name' => 'Danau Cipule (Situ Cipule)',
                'category_slug' => 'alam',
                'description' => 'Bekas galian pasir jadi danau konservasi & rekreasi air; venue Karawang Dragon Boat Festival 2026. Menjadi ikon wisata air yang terus berkembang di Karawang.',
                'address' => 'Kec. Ciampel',
                'latitude' => -6.39000,
                'longitude' => 107.26000,
                'cover_image' => 'https://images.unsplash.com/photo-1501854140801-50d01698950b?w=1200&q=80',
                'photos' => [
                    'https://images.unsplash.com/photo-1433086966358-54859d0ed716?w=1200&q=80',
                    'https://images.unsplash.com/photo-1518020382113-a7e8fc38eac9?w=1200&q=80',
                ],
            ],
            [
                'name' => 'Situ Darwin',
                'category_slug' => 'alam',
                'description' => 'Jangkar Desa Wisata Pangulah Utara, runner-up Hari Pariwisata Dunia tingkat Jabar. Danau alami yang dikelilingi perbukitan hijau dan menjadi surga wisata alam.',
                'address' => 'Desa Pangulah Utara, Kec. Kotabaru',
                'latitude' => -6.52000,
                'longitude' => 107.45000,
                'cover_image' => 'https://images.unsplash.com/photo-1501854140801-50d01698950b?w=1200&q=80',
                'photos' => [
                    'https://images.unsplash.com/photo-1439405326854-014607f694d7?w=1200&q=80',
                ],
            ],
            [
                'name' => 'Wisata Danau Wanajaya',
                'category_slug' => 'buatanrekreasi',
                'description' => 'Wisata perairan buatan kelola desa, terintegrasi sirkuit balap. Destinasi unik yang memadukan wisata air dengan atraksi otomotif.',
                'address' => 'Desa Wanajaya, Kec. Telukjambe Barat',
                'latitude' => -6.34000,
                'longitude' => 107.22000,
                'cover_image' => 'https://images.unsplash.com/photo-1576013551627-0cc20b96c2a7?w=1200&q=80',
                'photos' => [
                    'https://images.unsplash.com/photo-1560185007-5f0bb1866cab?w=1200&q=80',
                ],
            ],
            [
                'name' => 'Kawasan Industri Surya Cipta',
                'category_slug' => 'industri',
                'description' => 'Kawasan manufaktur ditetapkan Perbup sebagai Obyek Wisata Industri (kunjungan pabrik, pelatihan). Pengalaman unik belajar proses industri secara langsung.',
                'address' => 'Kec. Ciampel',
                'latitude' => -6.40000,
                'longitude' => 107.28000,
                'cover_image' => 'https://images.unsplash.com/photo-1565043666747-69f6646db940?w=1200&q=80',
                'photos' => [
                    'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=1200&q=80',
                ],
            ],
            [
                'name' => 'Kawasan International Industrial City (KIIC)',
                'category_slug' => 'industri',
                'description' => 'Kawasan industri lintas negara, ekosistem MICE terpadu. Menjadi salah satu kawasan industri terbesar dan paling modern di Jawa Barat.',
                'address' => 'Kec. Telukjambe',
                'latitude' => -6.33000,
                'longitude' => 107.24000,
                'cover_image' => 'https://images.unsplash.com/photo-1565043666747-69f6646db940?w=1200&q=80',
                'photos' => [
                    'https://images.unsplash.com/photo-1504328345606-18bbc8c9d7d1?w=1200&q=80',
                ],
            ],
        ];

        foreach ($destinations as $item) {
            $catId = $categories[$item['category_slug']] ?? null;
            if (!$catId) continue;

            $destId = DB::table('tourism_destinations')->insertGetId([
                'tourism_category_id' => $catId,
                'name' => $item['name'],
                'slug' => Str::slug($item['name']),
                'description' => $item['description'],
                'address' => $item['address'],
                'latitude' => $item['latitude'] ?? null,
                'longitude' => $item['longitude'] ?? null,
                'cover_image' => $item['cover_image'],
                'status' => 'published',
                'views' => rand(10, 500),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert gallery photos
            foreach ($item['photos'] as $order => $photoUrl) {
                DB::table('tourism_destination_photos')->insert([
                    'tourism_destination_id' => $destId,
                    'photo' => $photoUrl,
                    'caption' => null,
                    'order' => $order,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
