<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('conversion_suggestions', function (Blueprint $t) {
            $t->id();
            $t->foreignId('site_id')->constrained()->cascadeOnDelete();

            $t->unsignedBigInteger('wp_post_id'); // WP post/page id
            $t->string('wp_type')->default('page'); // page|post
            $t->string('url', 1024);

            $t->json('insights')->nullable(); // sammanfattning av fynd (AI)
            $t->json('suggestions')->nullable(); // {title:{current,suggested}, cta:{...}, form:{...}, aboveFold:{...}}
            $t->string('status')->default('new'); // new|applied|dismissed|partial
            $t->timestamp('applied_at')->nullable();

            $t->timestamps();

            $t->index(['site_id','wp_post_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversion_suggestions');
    }
};
