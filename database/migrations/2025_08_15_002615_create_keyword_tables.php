<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ranking_snapshots', function (Blueprint $t) {
            $t->id();
            $t->foreignId('site_id')->constrained()->cascadeOnDelete();
            $t->unsignedBigInteger('wp_post_id')->nullable();
            $t->string('wp_type')->default('page'); // page|post
            $t->string('url', 1024);
            // Hashad URL för indexering (undviker 1071 Max key length)
            $t->char('url_hash', 64)->nullable();
            // Begränsa keyword för säker indexering på alla MySQL-varianter
            $t->string('keyword', 191);
            $t->unsignedSmallInteger('position')->nullable(); // 1..100
            $t->string('serp_link', 1024)->nullable();
            $t->string('device')->default('desktop'); // desktop|mobile
            $t->string('locale')->default('se');
            $t->timestamp('checked_at');
            $t->timestamps();

            // Indexera site + url_hash i stället för full url
            $t->index(['site_id','url_hash'], 'ranking_snapshots_site_id_urlhash_index');
            $t->index(['site_id','keyword'], 'ranking_snapshots_site_id_keyword_index');
        });

        Schema::create('keyword_suggestions', function (Blueprint $t) {
            $t->id();
            $t->foreignId('site_id')->constrained()->cascadeOnDelete();
            $t->unsignedBigInteger('wp_post_id');
            $t->string('wp_type')->default('page');
            $t->string('url', 1024);
            $t->json('current')->nullable();     // {title, meta, keywords[]}
            $t->json('suggested')->nullable();   // {title, meta, keywords[]}
            $t->json('insights')->nullable();    // motivering/punktlista
            $t->string('status')->default('new'); // new|applied|dismissed|partial
            $t->timestamp('applied_at')->nullable();
            $t->timestamps();

            $t->index(['site_id','wp_post_id'], 'keyword_suggestions_site_id_post_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keyword_suggestions');
        Schema::dropIfExists('ranking_snapshots');
    }
};
