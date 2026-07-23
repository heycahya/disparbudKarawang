<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'creative_economies',
            'accommodations',
            'culinary_places',
            'news',
            'tourism_destinations',
            'cultures',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'views')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->unsignedBigInteger('views')->default(0);
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'creative_economies',
            'accommodations',
            'culinary_places',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'views')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('views');
                });
            }
        }
    }
};
