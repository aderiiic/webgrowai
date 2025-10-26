<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ai_contents', function (Blueprint $table) {
            $table->foreignId('bulk_generation_id')->nullable()->after('template_id')->constrained()->onDelete('cascade');
            $table->json('placeholders')->nullable()->after('inputs'); // {"stad": "Malmö", "produkt": "Soffa"}
            $table->unsignedInteger('batch_index')->nullable()->after('placeholders');
            $table->string('type')->default('generic')->after('tone'); // social, blog, multi, newsletter
        });
    }

    public function down(): void
    {
        Schema::table('ai_contents', function (Blueprint $table) {
            $table->dropForeign(['bulk_generation_id']);
            $table->dropColumn(['bulk_generation_id', 'placeholders', 'batch_index', 'type']);
        });
    }
};
