<?php
// database/migrations/2025_09_08_000000_add_weekly_settings_to_sites.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->string('weekly_brand_voice')->nullable();
            $table->string('weekly_audience')->nullable();
            $table->string('weekly_goal')->nullable();
            $table->json('weekly_keywords')->nullable();
            $table->text('weekly_recipients')->nullable(); // komma-separerade e-post
        });
    }

    public function down(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->dropColumn([
                'weekly_brand_voice',
                'weekly_audience',
                'weekly_goal',
                'weekly_keywords',
                'weekly_recipients',
            ]);
        });
    }
};
