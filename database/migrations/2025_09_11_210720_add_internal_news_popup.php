<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('internal_news', function (Blueprint $table) {
            $table->boolean('show_popup')->default(false)->after('is_pinned');
        });

        // Tabell för att spåra vilka användare som sett popups
        Schema::create('user_news_popups_seen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('internal_news_id')->constrained()->onDelete('cascade');
            $table->timestamp('seen_at');
            $table->timestamps(); // Detta lägger till created_at och updated_at
            $table->unique(['user_id', 'internal_news_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_news_popups_seen');
        Schema::table('internal_news', function (Blueprint $table) {
            $table->dropColumn('show_popup');
        });
    }
};
