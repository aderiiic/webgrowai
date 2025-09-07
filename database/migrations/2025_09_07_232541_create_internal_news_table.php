<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('internal_news', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->text('body_md')->nullable();
            $table->string('tags', 500)->nullable();
            $table->enum('type', ['bugfix','feature','info'])->default('info');
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_public')->default(false);
            $table->timestamp('published_at')->nullable()->index();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internal_news');
    }
};
