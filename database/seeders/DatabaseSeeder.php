<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            OrganizationProfileSeeder::class,
            OrganizationFunctionSeeder::class,
            OrganizationStructureSeeder::class,
            TourismCategorySeeder::class,
            TourismDestinationSeeder::class,
            CultureSeeder::class,
            CreativeEconomySeeder::class,
            AccommodationSeeder::class,
            CulinaryPlaceSeeder::class,
            NewsCategorySeeder::class,
            NewsSeeder::class,
            GallerySeeder::class,
            SocialMediaLinkSeeder::class,
        ]);
    }
}
