<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreativeEconomySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $creativeEconomies = [
            [
                'name' => 'Batik Karawang (Rumah Kreasi Taza)',
                'description' => 'Restorasi Batik Karawang dengan motif Pare Sagedang (bulir padi), pola tumpal, dan desain geometrik pesisir. Produksi kain eceran hingga seragam birokrasi.',
                'owner_name' => null,
                'contact' => null,
                'address' => 'Kaum I, Jl. KH. Ahmad Dahlan No. 20, Kel. Karawang Kulon, Kec. Karawang Barat',
            ],
            [
                'name' => 'Kampung Belanja Boneka Cikampek',
                'description' => 'Sentra klaster industri boneka skala nasional; ditetapkan sebagai Obyek Wisata Buatan (Wisata Industri Kreatif).',
                'owner_name' => 'Kolektif Warga (Klaster UMKM)',
                'contact' => null,
                'address' => 'Kp. Ciselang, Desa Cikampek Utara, Kec. Kotabaru',
            ],
            [
                'name' => 'Oleh-oleh Turubuk',
                'description' => 'Bunga tebu telur (telubuk/turubuk) diolah jadi oleh-oleh khas Karawang, hasil sinergi Pemda & PHRI.',
                'owner_name' => 'Kolaborasi PHRI Karawang & UMKM',
                'contact' => null,
                'address' => null,
            ],
        ];

        foreach ($creativeEconomies as $item) {
            DB::table('creative_economies')->insert([
                'name' => $item['name'],
                'slug' => Str::slug($item['name']),
                'description' => $item['description'],
                'owner_name' => $item['owner_name'],
                'contact' => $item['contact'],
                'address' => $item['address'],
                'cover_image' => null,
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
