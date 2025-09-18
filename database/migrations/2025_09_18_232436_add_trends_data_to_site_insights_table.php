<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('site_insights', function (Blueprint $table) {
            $table->json('trends_data')->nullable()->after('payload');
        });
    }

    public function down(): void
    {
        Schema::table('site_insights', function (Blueprint $table) {
            $table->dropColumn('trends_data');
        });
    }
};
