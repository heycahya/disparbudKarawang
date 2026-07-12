<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NewsCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Event/Olahraga & Pariwisata',
            'Kebijakan Daerah',
            'Destinasi/Desa Wisata',
            'Inovasi Teknologi',
        ];

        foreach ($categories as $category) {
            DB::table('news_categories')->updateOrInsert(
                ['slug' => Str::slug($category)],
                [
                    'name' => $category,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
