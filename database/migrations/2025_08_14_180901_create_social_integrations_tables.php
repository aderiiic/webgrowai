<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('social_integrations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $t->string('provider'); // facebook|instagram
            $t->string('page_id')->nullable();   // FB Page ID
            $t->string('ig_user_id')->nullable();// IG Business User ID
            $t->text('access_token');            // långlivat token
            $t->string('status')->default('active');
            $t->timestamps();
            $t->unique(['customer_id','provider']);
        });

        Schema::create('content_publications', function (Blueprint $t) {
            $t->id();
            $t->foreignId('ai_content_id')->constrained()->cascadeOnDelete();
            $t->string('target'); // wp|facebook|instagram
            $t->string('status')->default('queued'); // queued|processing|published|failed
            $t->timestamp('scheduled_at')->nullable();
            $t->string('external_id')->nullable();
            $t->text('message')->nullable(); // felmeddelande eller info
            $t->json('payload')->nullable(); // skickad payload
            $t->timestamps();
            $t->index(['target','status']);
            $t->index(['scheduled_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_publications');
        Schema::dropIfExists('social_integrations');
    }
};
