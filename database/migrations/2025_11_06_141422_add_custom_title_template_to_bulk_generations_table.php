<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bulk_generations', function (Blueprint $table) {
            $table->text('custom_title_template')->nullable()->after('template_text');
        });
    }

    public function down(): void
    {
        Schema::table('bulk_generations', function (Blueprint $table) {
            $table->dropColumn('custom_title_template');
        });
    }
};
