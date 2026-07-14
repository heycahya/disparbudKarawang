<?php

namespace Database\Seeders;

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
        $cultures = [
            [
                'name' => 'Tari Goyang Karawang',
                'category' => 'kesenian',
                'description' => 'Lahir dari rutinitas agraris perempuan Karawang saat menumbuk gabah era Hindia Belanda. Pola gerak khas: ngala (patah-patah), mincit (perpindahan), pencungan (tempo cepat). Sempat terdistorsi stigma sosial era 1970-an akibat fusi dengan ronggeng jalanan & Topeng Banjet; kini direvitalisasi ke bentuk klasik dengan kostum apok, sinjang, dan sampur.',
                'cover_image' => 'https://images.unsplash.com/photo-1508918290772-74d17d890d6b?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Seni Ajeng',
                'category' => 'tradisi',
                'description' => 'Tabuh gamelan tertua, tumbuh sejak 1913. "Ajeng" dari pangajeng-ngajeng (menyambut tamu agung). Fungsi ganda: arak-arakan pengantin (helaran) siang hari & tarian penghormatan (soja) malam hari. Instrumentasi khas: bonang sebagai penuntun melodi. Pusat pelestarian: trah Abah Tarim, Kampung Bambu Duri, Karang Pawitan.',
                'cover_image' => 'https://images.unsplash.com/photo-1534447677768-be436bb09401?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Topeng Banjet',
                'category' => 'kesenian',
                'description' => 'Teater rakyat & komedi satir vernakular khas Karawang, memadukan dramaturgi, lawakan spontan, dan tarian rakyat. Berelasi dengan akar penari ronggeng dan menjadi katalis lahirnya koreografi awal Tari Goyang Karawang.',
                'cover_image' => 'https://images.unsplash.com/photo-1507676184212-d03ab07a01bf?auto=format&fit=crop&w=800&q=80',
            ],
        ];

        foreach ($cultures as $culture) {
            DB::table('cultures')->insert([
                'name' => $culture['name'],
                'slug' => Str::slug($culture['name']),
                'category' => $culture['category'],
                'description' => $culture['description'],
                'cover_image' => $culture['cover_image'],
                'status' => 'published',
                'views' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
