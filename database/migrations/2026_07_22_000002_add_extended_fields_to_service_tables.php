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
        Schema::table('tourism_submissions', function (Blueprint $table) {
            if (!Schema::hasColumn('tourism_submissions', 'category')) {
                $table->string('category')->nullable()->after('name');
            }
            if (!Schema::hasColumn('tourism_submissions', 'contact')) {
                $table->string('contact')->nullable()->after('address');
            }
            if (!Schema::hasColumn('tourism_submissions', 'operating_hours')) {
                $table->string('operating_hours')->nullable()->after('contact');
            }
            if (!Schema::hasColumn('tourism_submissions', 'ticket_price')) {
                $table->string('ticket_price')->nullable()->after('operating_hours');
            }
        });

        Schema::table('event_broadcast_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('event_broadcast_requests', 'end_date')) {
                $table->date('end_date')->nullable()->after('event_date');
            }
            if (!Schema::hasColumn('event_broadcast_requests', 'target_audience')) {
                $table->string('target_audience')->nullable()->after('description');
            }
        });

        Schema::table('complaints', function (Blueprint $table) {
            if (!Schema::hasColumn('complaints', 'category')) {
                $table->string('category')->nullable()->after('subject');
            }
            if (!Schema::hasColumn('complaints', 'location')) {
                $table->string('location')->nullable()->after('category');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tourism_submissions', function (Blueprint $table) {
            $table->dropColumn(['category', 'contact', 'operating_hours', 'ticket_price']);
        });

        Schema::table('event_broadcast_requests', function (Blueprint $table) {
            $table->dropColumn(['end_date', 'target_audience']);
        });

        Schema::table('complaints', function (Blueprint $table) {
            $table->dropColumn(['category', 'location']);
        });
    }
};
