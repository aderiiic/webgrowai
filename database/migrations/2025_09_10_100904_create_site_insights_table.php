<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('site_insights', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('site_id')->index();
            $table->date('week_start');
            $table->json('payload');
            $table->string('model')->nullable();
            $table->timestamp('generated_at');
            $table->timestamps();

            $table->unique(['site_id', 'week_start']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_insights');
    }
};
