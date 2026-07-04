<?php

namespace Database\Seeders;

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
        $accommodations = [
            [
                'name' => 'Resinda Hotel Karawang (Padma Hotels)',
                'type' => 'hotel',
                'description' => 'Hotel bintang 4 terintegrasi dengan Resinda Park Mall; kolam air panas indoor, sauna, fasilitas konvensi.',
                'address' => 'Jl. Resinda Raya No. 1, Purwadana, Kec. Telukjambe Timur, 41361',
                'phone' => '(0267) 8622000',
                'price_range' => 'Mulai Rp1.262.973',
            ],
            [
                'name' => 'Mercure Karawang',
                'type' => 'hotel',
                'description' => 'Hotel bintang 4 dekat klaster Galuh Mas & Balai Kota; kolam renang outdoor.',
                'address' => 'Jl. Galuh Mas Raya, Sukaharja, Kec. Telukjambe Timur, 41361',
                'phone' => '(0267) 8638888',
                'price_range' => 'Rp500.136–Rp569.256',
            ],
            [
                'name' => 'Brits Hotel Karawang',
                'type' => 'hotel',
                'description' => 'Hotel bintang 4 dekat gerbang tol Karawang Barat; kitchenette, sauna, dapur terbuka.',
                'address' => 'Jl. Arteri Tol Karawang Barat No. 1, Kav. 8, Margakaya, Kec. Telukjambe Barat, 41361',
                'phone' => null, // Excluded twin duplicate phone to prevent redundant data distortion
                'price_range' => 'Rp466.301–Rp591.143',
            ],
            [
                'name' => 'Swiss-Belhotel Karawang',
                'type' => 'hotel',
                'description' => 'Hotel bintang 3/4 dengan outdoor sky pool, karaoke privat, 9 ruang meeting, konter teppanyaki.',
                'address' => 'Jl. Jenderal Ahmad Yani No. 29, Tanjungpura, Kec. Karawang Barat, 41315',
                'phone' => null,
                'price_range' => 'Rp538.428',
            ],
            [
                'name' => 'Asialink Premier Hotel & Residence',
                'type' => 'hotel',
                'description' => 'Hotel bintang 4 fasilitas Sierra Spa, whirlpool, KTV Bar & Lounge, restoran Zenfuku.',
                'address' => 'Jl. Raya Badami RT.04/RW.02, Margakaya, Kec. Telukjambe Barat, 41361',
                'phone' => '(0267) 8637638',
                'price_range' => 'Mulai Rp263.383',
            ],
            [
                'name' => 'Novotel Karawang',
                'type' => 'hotel',
                'description' => 'Hotel bintang 4 jaringan multinasional, untuk keluarga & delegasi bisnis.',
                'address' => 'Jl. Interchange Karawang Barat, Margakaya, Kec. Telukjambe Barat, 41361',
                'phone' => '(0267) 6483333',
                'price_range' => 'Rp520.651–Rp637.641',
            ],
            [
                'name' => 'PrimeBiz Hotel Karawang',
                'type' => 'hotel',
                'description' => 'Hotel bintang 3 dekat Kota Bukit Indah/Cikampek, untuk profesional logistik/teknisi.',
                'address' => 'Blok C, Kawasan Kota, Kalihurip, Kec. Cikampek, 41363',
                'phone' => '(0264) 8371010',
                'price_range' => 'Mulai Rp334.790',
            ],
            [
                'name' => 'Grand Karawang Indah Hotel',
                'type' => 'hotel',
                'description' => 'Hotel budget opsi ekonomis di pusat kota.',
                'address' => 'Jl. Jenderal Ahmad Yani By Pass No. 28, Tanjungpura, Kec. Karawang Barat, 41315',
                'phone' => '(0267) 410656',
                'price_range' => 'Rp196.560–Rp279.400',
            ],
        ];

        foreach ($accommodations as $item) {
            DB::table('accommodations')->insert([
                'name' => $item['name'],
                'slug' => Str::slug($item['name']),
                'type' => $item['type'],
                'description' => $item['description'],
                'address' => $item['address'],
                'phone' => $item['phone'],
                'price_range' => $item['price_range'],
                'cover_image' => null,
                'latitude' => null,
                'longitude' => null,
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
