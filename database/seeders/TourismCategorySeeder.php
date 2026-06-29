<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TourismCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ['Wisata Alam', 'Wisata Kuliner', 'Wisata Budaya', 'Wisata Sejarah', 'Wisata Religi', 'Wisata Buatan'];

        foreach ($categories as $category) {
            DB::table('tourism_categories')->insert([
                'name' => $category,
                'slug' => Str::slug($category),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
