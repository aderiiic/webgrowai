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
        Schema::table('seo_audits', function (Blueprint $table) {
            $table->longText('ai_analysis')->nullable()->after('summary');
            $table->timestamp('ai_analysis_generated_at')->nullable()->after('ai_analysis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seo_audits', function (Blueprint $table) {
            $table->dropColumn(['ai_analysis', 'ai_analysis_generated_at']);
        });
    }
};
