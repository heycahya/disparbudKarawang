<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = DB::table('users')->where('role', 'super_admin')->value('id') ?? 1;
        $categories = DB::table('news_categories')->pluck('id', 'slug');

        $articles = [
            [
                'title' => 'Pemkab Karawang Bakal Gelar Festival Dragon Boat Pertama, 1.500 Peserta Siap Ramaikan Danau Cipule',
                'category_name' => 'Event/Olahraga & Pariwisata',
                'published_at' => '2026-06-17 00:00:00',
                'content' => 'Festival dayung perahu naga perdana di Danau Cipule, Kec. Ciampel, 19–21 Juni 2026, melibatkan >30 perahu dan ±1.500 atlet/ofisial — jadi instrumen sports tourism yang mendongkrak ekonomi UMKM lokal.',
            ],
            [
                'title' => 'Bidang Kebudayaan dan Pora Tukar Tempat, Disparbud Karawang Siap Jalani Transisi',
                'category_name' => 'Kebijakan Daerah',
                'published_at' => '2025-10-08 00:00:00',
                'content' => 'Perda penataan kelembagaan memindahkan Bidang Kebudayaan ke Dinas Pendidikan dan Bidang Pemuda/Olahraga ke Dinas Pariwisata, membentuk Disparpora mulai 2026. Kepala Dinas Abas Sudrajat memimpin proses transisi.',
            ],
            [
                'title' => 'Bukan Sekadar Kawasan Industri, Karawang Targetkan Penambahan Desa Wisata Baru di 2026',
                'category_name' => 'Destinasi/Desa Wisata',
                'published_at' => '2025-08-01 00:00:00',
                'content' => 'Pemda mendorong pengembangan Desa Wisata dari basis 8 desa aktif (termasuk Desa Pangulah Utara pemenang runner-up Hari Pariwisata Dunia tingkat Jabar lewat Situ Darwin), dengan dukungan fiskal & manajerial.',
            ],
            [
                'title' => 'Sebentar Lagi Warga Bisa Akses Informasi Cagar Budaya Karawang Lewat Kode QR (SAGAWANG)',
                'category_name' => 'Inovasi Teknologi',
                'published_at' => '2025-07-01 00:00:00',
                'content' => 'Peluncuran platform digital SAGAWANG (Sejarah Cagar Budaya Karawang), digitalisasi manuskrip & silsilah cagar budaya, dengan plakat QR Code di situs-situs cagar budaya.',
            ],
        ];

        foreach ($articles as $article) {
            $slug = Str::slug($article['category_name']);
            $catId = $categories[$slug] ?? null;

            if ($catId) {
                DB::table('news')->insert([
                    'user_id' => $userId,
                    'news_category_id' => $catId,
                    'title' => $article['title'],
                    'slug' => Str::slug($article['title']),
                    'thumbnail' => null,
                    'content' => $article['content'],
                    'status' => 'published',
                    'published_at' => $article['published_at'],
                    'views' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
