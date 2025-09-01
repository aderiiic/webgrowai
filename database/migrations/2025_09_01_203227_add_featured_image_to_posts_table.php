<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->unsignedBigInteger('featured_image_id')->nullable()->after('body_md');
            $table->foreign('featured_image_id')->references('id')->on('image_assets')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['featured_image_id']);
            $table->dropColumn('featured_image_id');
        });
    }
};
