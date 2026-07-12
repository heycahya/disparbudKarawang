<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SocialMediaLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $links = [
            [
                'platform' => 'instagram',
                'url' => 'https://instagram.com/disparbudkrwkab',
                'is_active' => true,
            ],
            [
                'platform' => 'youtube',
                'url' => 'https://youtube.com/@disparporakabkarawang',
                'is_active' => true,
            ],
        ];

        foreach ($links as $link) {
            DB::table('social_media_links')->insert(array_merge($link, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
