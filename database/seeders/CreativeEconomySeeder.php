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
                'cover_image' => 'https://images.unsplash.com/photo-1606744888344-493238951221?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Kampung Belanja Boneka Cikampek',
                'description' => 'Sentra klaster industri boneka skala nasional; ditetapkan sebagai Obyek Wisata Buatan (Wisata Industri Kreatif).',
                'owner_name' => 'Kolektif Warga (Klaster UMKM)',
                'contact' => null,
                'address' => 'Kp. Ciselang, Desa Cikampek Utara, Kec. Kotabaru',
                'cover_image' => 'https://images.unsplash.com/photo-1559251606-c623743a6d76?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Oleh-oleh Turubuk',
                'description' => 'Bunga tebu telur (telubuk/turubuk) diolah jadi oleh-oleh khas Karawang, hasil sinergi Pemda & PHRI.',
                'owner_name' => 'Kolaborasi PHRI Karawang & UMKM',
                'contact' => null,
                'address' => null,
                'cover_image' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=800&q=80',
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
                'cover_image' => $item['cover_image'],
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
