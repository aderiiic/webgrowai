<?php
// database/migrations/2025_08_31_000000_create_analytics_snapshots_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('analytics_snapshots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->index();
            $table->unsignedBigInteger('site_id')->nullable()->index();
            $table->date('date')->index();
            $table->string('metric', 64)->index(); // t.ex. web_visitors, facebook_reach, content_score_30d
            $table->bigInteger('value')->default(0);
            // valfria fält för avancerade listor
            $table->string('title')->nullable();
            $table->integer('score')->nullable();
            $table->string('day')->nullable();   // för best_post_time
            $table->string('hour')->nullable();  // för best_post_time
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_snapshots');
    }
};
