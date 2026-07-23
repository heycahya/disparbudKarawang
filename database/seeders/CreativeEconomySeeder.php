<?php

namespace Database\Seeders;

use App\Services\CloudinaryService;
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
        $json = file_get_contents(database_path('seeders/data/ekraf.json'));
        $creativeEconomies = json_decode($json, true);

        $images = [
            'Batik Taza Karawang' => 'https://images.unsplash.com/photo-1617627143750-d86bc21e42bb?auto=format&fit=crop&w=800&h=500&q=80',
            'Bandeng Gepuk C73' => 'https://images.unsplash.com/photo-1534604973900-c43ab4c2e0ab?auto=format&fit=crop&w=800&h=500&q=80',
            'Kriya Bedog Lubuk Karawang' => 'https://images.unsplash.com/photo-1513519245088-0e12902e5a38?auto=format&fit=crop&w=800&h=500&q=80',
            'Sorabi Kuntilanak Rengasdengklok' => 'https://images.unsplash.com/photo-1587314168485-3236d6710814?auto=format&fit=crop&w=800&h=500&q=80',
            'Tenun Ikat Gumanano Karawang' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?auto=format&fit=crop&w=800&h=500&q=80',
            'Kopi Bubuk Sanggabuana Koffie Hideung' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&w=800&h=500&q=80',
            'Jasa Pentas Teater Topeng Banjet' => 'https://images.unsplash.com/photo-1460723237483-7a6dc9d0b212?auto=format&fit=crop&w=800&h=500&q=80',
            'Jasa Pengiring Musik Gamelan Ajeng' => 'https://images.unsplash.com/photo-1511192336575-5a79af67a629?auto=format&fit=crop&w=800&h=500&q=80',
        ];

        foreach ($creativeEconomies as $index => $item) {
            $cover = $images[$item['nama_produk']] ?? 'https://images.unsplash.com/photo-1617627143750-d86bc21e42bb?auto=format&fit=crop&w=800&h=500&q=80';

            DB::table('creative_economies')->insert([
                'name' => $item['nama_produk'],
                'slug' => Str::slug($item['nama_produk']),
                'description' => $item['deskripsi'],
                'owner_name' => $item['nama_usaha'] ?? null,
                'contact' => null,
                'address' => $item['alamat'] ?? null,
                'cover_image' => CloudinaryService::getUrl($cover, 'ekraf'),
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
