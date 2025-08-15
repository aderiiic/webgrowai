<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('slug')->unique();
            $t->string('excerpt', 300)->nullable();
            $t->longText('body_md')->nullable();
            $t->timestamp('published_at')->nullable();
            $t->timestamps();
            $t->index(['published_at']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
