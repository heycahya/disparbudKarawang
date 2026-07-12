<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TourismDestinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = DB::table('tourism_categories')->pluck('id', 'slug');

        $destinations = [
            [
                'name' => 'Candi Jiwa (Percandian Batujaya)',
                'category_slug' => 'sejarahcagar-budaya',
                'description' => 'Situs arkeologi Buddha tertua di tatar Sunda, era Kerajaan Tarumanegara (abad 2–6 M), berupa gundukan bata merah setinggi 4–6 m di tengah sawah.',
                'address' => 'Desa Segaran, Kec. Batujaya',
                'latitude' => -6.05563,
                'longitude' => 107.15351,
                'cover_image' => null,
            ],
            [
                'name' => 'Monumen Perjuangan Rawagede',
                'category_slug' => 'sejarahcagar-budaya',
                'description' => 'Monumen dua lantai segi delapan (diresmikan 1996), mengenang pembantaian warga sipil oleh militer Belanda 9 Des 1947, menginspirasi sajak Chairil Anwar.',
                'address' => 'Jl. Monumen Rawagede No. 2, Desa Balongsari, Kec. Rawamerta',
                'latitude' => -6.23855,
                'longitude' => 107.32657,
                'cover_image' => null,
            ],
            [
                'name' => 'Tugu Kebulatan Tekad',
                'category_slug' => 'sejarahcagar-budaya',
                'description' => 'Didirikan 1950, bekas markas PETA, memperingati Peristiwa Rengasdengklok (penculikan Soekarno-Hatta).',
                'address' => 'Desa Rengasdengklok Utara, Kec. Rengasdengklok',
                'latitude' => -6.16194,
                'longitude' => 107.29277,
                'cover_image' => null,
            ],
            [
                'name' => 'Pantai Tangkolak & Hutan Mangrove',
                'category_slug' => 'alam',
                'description' => 'Hutan mangrove untuk mitigasi abrasi, plus wisata sejarah bawah air (kerangka kapal VOC & koin peninggalan 1799).',
                'address' => 'Desa Sukakerta, Kec. Cilamaya Wetan',
                'latitude' => -5.81753,
                'longitude' => 107.11046,
                'cover_image' => null,
            ],
            [
                'name' => 'Pantai Tanjung Pakis',
                'category_slug' => 'alam',
                'description' => 'Ikon wisata massal di ujung utara Karawang, ramai saat libur panjang.',
                'address' => 'Kec. Tanjung Pakis',
                'latitude' => null,
                'longitude' => null,
                'cover_image' => null,
            ],
            [
                'name' => 'Pantai Samudra Baru',
                'category_slug' => 'alam',
                'description' => 'Destinasi bahari andalan, ramai saat libur lebaran, penopang ekonomi nelayan.',
                'address' => 'Kec. Pedes',
                'latitude' => null,
                'longitude' => null,
                'cover_image' => null,
            ],
            [
                'name' => 'Makam Syekh Quro',
                'category_slug' => 'religi',
                'description' => 'Situs ziarah ulama penyebar Islam generasi awal di pesisir utara Jawa.',
                'address' => 'Kel. Tanjungpura, Kec. Karawang Barat',
                'latitude' => -6.27778,
                'longitude' => 107.28972,
                'cover_image' => null,
            ],
            [
                'name' => 'Makam Keramat Nagasari',
                'category_slug' => 'religi',
                'description' => 'Bagian sirkuit wisata ziarah Karawang.',
                'address' => 'Kec. Karawang Barat',
                'latitude' => null,
                'longitude' => null,
                'cover_image' => null,
            ],
            [
                'name' => 'Danau Cipule (Situ Cipule)',
                'category_slug' => 'alam',
                'description' => 'Bekas galian pasir jadi danau konservasi & rekreasi air; venue Karawang Dragon Boat Festival 2026.',
                'address' => 'Kec. Ciampel',
                'latitude' => null,
                'longitude' => null,
                'cover_image' => null,
            ],
            [
                'name' => 'Situ Darwin',
                'category_slug' => 'alam',
                'description' => 'Jangkar Desa Wisata Pangulah Utara, runner-up Hari Pariwisata Dunia tingkat Jabar.',
                'address' => 'Desa Pangulah Utara, Kec. Kotabaru',
                'latitude' => null,
                'longitude' => null,
                'cover_image' => null,
            ],
            [
                'name' => 'Wisata Danau Wanajaya',
                'category_slug' => 'buatanrekreasi',
                'description' => 'Wisata perairan buatan kelola desa, terintegrasi sirkuit balap.',
                'address' => 'Desa Wanajaya, Kec. Telukjambe Barat',
                'latitude' => null,
                'longitude' => null,
                'cover_image' => null,
            ],
            [
                'name' => 'Kawasan Industri Surya Cipta',
                'category_slug' => 'industri',
                'description' => 'Kawasan manufaktur ditetapkan Perbup sebagai Obyek Wisata Industri (kunjungan pabrik, pelatihan).',
                'address' => 'Kec. Ciampel',
                'latitude' => null,
                'longitude' => null,
                'cover_image' => null,
            ],
            [
                'name' => 'Kawasan International Industrial City (KIIC)',
                'category_slug' => 'industri',
                'description' => 'Kawasan industri lintas negara, ekosistem MICE terpadu.',
                'address' => 'Kec. Telukjambe',
                'latitude' => null,
                'longitude' => null,
                'cover_image' => null,
            ],
        ];

        foreach ($destinations as $item) {
            $catId = $categories[$item['category_slug']] ?? null;

            if ($catId) {
                DB::table('tourism_destinations')->insert([
                    'tourism_category_id' => $catId,
                    'name' => $item['name'],
                    'slug' => Str::slug($item['name']),
                    'description' => $item['description'],
                    'address' => $item['address'],
                    'latitude' => $item['latitude'],
                    'longitude' => $item['longitude'],
                    'cover_image' => $item['cover_image'],
                    'status' => 'published',
                    'views' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
