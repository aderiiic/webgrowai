<?php
// database/migrations/2025_09_03_000000_add_business_prefs_to_sites_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->string('industry', 120)->nullable()->after('status');
            $table->text('business_description')->nullable()->after('industry');
            $table->text('target_audience')->nullable()->after('business_description');
            $table->string('brand_voice', 120)->nullable()->after('target_audience');
            $table->string('locale', 16)->nullable()->default('sv_SE')->after('brand_voice');
            $table->json('ai_prefs')->nullable()->after('locale'); // valfria extra preferenser
        });
    }

    public function down(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->dropColumn([
                'industry',
                'business_description',
                'target_audience',
                'brand_voice',
                'locale',
                'ai_prefs',
            ]);
        });
    }
};
