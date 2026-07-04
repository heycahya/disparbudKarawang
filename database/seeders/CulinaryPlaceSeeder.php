<?php

namespace Database\Seeders;

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
        $culinaryPlaces = [
            [
                'name' => 'Resto Lawasan Caraka',
                'type' => 'restoran',
                'description' => 'Konsep nostalgia perabot antik; ruang makan tematik + edukasi sejarah + pameran UMKM lokal.',
                'address' => 'Jl. Alun-Alun Selatan, Kec. Karawang Barat, Karawang',
                'phone' => null,
                'price_range' => null,
            ],
            [
                'name' => 'Swiss-Cafe Restaurant',
                'type' => 'restoran',
                'description' => 'Sajian Nusantara + fusi Barat-Asia, in-house Swiss-Belhotel Karawang.',
                'address' => 'Jl. Jenderal Ahmad Yani No. 29, Tanjungpura, Kec. Karawang Barat',
                'phone' => null,
                'price_range' => null,
            ],
            [
                'name' => 'Zenfuku Restaurant',
                'type' => 'restoran',
                'description' => 'Boga bahari & masakan Jepang, in-house Asialink Premier Hotel.',
                'address' => 'Jl. Raya Badami RT.04/RW.02, Margakaya, Kec. Telukjambe Barat',
                'phone' => null,
                'price_range' => null,
            ],
            [
                'name' => 'Konter Teppanyaki (Swiss-Belhotel)',
                'type' => 'restoran',
                'description' => 'Atraksi masak teppanyaki di depan tamu, in-house Swiss-Belhotel Karawang.',
                'address' => 'Jl. Jenderal Ahmad Yani No. 29, Tanjungpura, Kec. Karawang Barat',
                'phone' => null,
                'price_range' => null,
            ],
        ];

        foreach ($culinaryPlaces as $item) {
            DB::table('culinary_places')->insert([
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
