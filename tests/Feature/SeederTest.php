<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Database\Seeders\DatabaseSeeder;

uses(RefreshDatabase::class);

test('database seeder seeds all real local data correctly', function () {
    $this->seed(DatabaseSeeder::class);

    expect(DB::table('organization_profiles')->count())->toBeGreaterThan(0);
    expect(DB::table('organization_functions')->count())->toBe(5);
    expect(DB::table('organization_structures')->count())->toBe(5);

    expect(DB::table('tourism_categories')->count())->toBe(5);
    expect(DB::table('tourism_destinations')->count())->toBe(13);
    expect(DB::table('tourism_destinations')->where('name', 'Curug Cigeuntis')->exists())->toBeFalse();

    expect(DB::table('cultures')->count())->toBe(6);
    expect(DB::table('creative_economies')->count())->toBe(6);
    expect(DB::table('accommodations')->count())->toBe(8);
    expect(DB::table('culinary_places')->count())->toBe(4);

    expect(DB::table('news_categories')->count())->toBe(4);
    expect(DB::table('news')->count())->toBe(4);
    expect(DB::table('social_media_links')->count())->toBe(2);
});
