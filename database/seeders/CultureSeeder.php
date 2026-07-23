<?php

namespace Database\Seeders;

use App\Services\CloudinaryService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CultureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(database_path('seeders/data/culture.json'));
        $cultures = json_decode($json, true);

        $categoryMap = [
            'Seni Pertunjukan' => 'kesenian',
            'Cagar Budaya' => 'warisan_budaya',
            'Ritus' => 'warisan_budaya',
            'Tradisi' => 'tradisi',
        ];

        $images = [
            'Tari Goyang Karawang' => 'https://images.unsplash.com/photo-1508700115892-45ecd05ae2ad?auto=format&fit=crop&w=800&h=500&q=80',
            'Topeng Banjet' => 'https://images.unsplash.com/photo-1534447677768-be436bb09401?auto=format&fit=crop&w=800&h=500&q=80',
            'Seni Ajeng' => 'https://images.unsplash.com/photo-1598387181032-a3103a2db5b3?auto=format&fit=crop&w=800&h=500&q=80',
            'Gedung Sekolah SDN Pisangsambo 1' => 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=800&h=500&q=80',
            'Eks Kantor Kawedanaan Rengasdengklok' => 'https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?auto=format&fit=crop&w=800&h=500&q=80',
            'Tradisi Babarit / Hajat Bumi' => 'https://images.unsplash.com/photo-1530103862676-de8c9debad1d?auto=format&fit=crop&w=800&h=500&q=80',
            'Bedog Lubuk Karawang' => 'https://images.unsplash.com/photo-1588681664899-f142ff22516b?auto=format&fit=crop&w=800&h=500&q=80',
            'Ritus Nadran / Pesta Laut' => 'https://images.unsplash.com/photo-1516690561799-46d8f74f9abf?auto=format&fit=crop&w=800&h=500&q=80',
        ];

        foreach ($cultures as $index => $item) {
            $category = $categoryMap[$item['kategori']] ?? 'warisan_budaya';
            $cover = $images[$item['nama_budaya']] ?? 'https://images.unsplash.com/photo-1508700115892-45ecd05ae2ad?auto=format&fit=crop&w=800&h=500&q=80';

            DB::table('cultures')->insert([
                'name' => $item['nama_budaya'],
                'slug' => Str::slug($item['nama_budaya']),
                'category' => $category,
                'description' => $item['deskripsi'],
                'cover_image' => CloudinaryService::getUrl($cover, 'culture'),
                'status' => 'published',
                'views' => rand(10, 300),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
