<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $t) {
            $t->id();
            $t->foreignId('site_id')->constrained()->cascadeOnDelete();
            $t->string('visitor_id', 64); // wb_vid från klienten
            $t->string('email')->nullable();
            $t->timestamp('first_seen')->nullable();
            $t->timestamp('last_seen')->nullable();
            $t->unsignedInteger('sessions')->default(0);
            $t->string('last_ip_hash', 64)->nullable();
            $t->string('user_agent_hash', 64)->nullable();
            $t->timestamps();
            $t->unique(['site_id', 'visitor_id']);
            $t->index(['site_id','email']);
        });

        Schema::create('lead_events', function (Blueprint $t) {
            $t->id();
            $t->foreignId('site_id')->constrained()->cascadeOnDelete();
            $t->foreignId('lead_id')->constrained('leads')->cascadeOnDelete();
            $t->string('type'); // pageview|heartbeat|cta|form_submit
            $t->string('url', 1024)->nullable();
            $t->json('meta')->nullable();
            $t->timestamp('occurred_at')->index();
            $t->timestamps();
            $t->index(['site_id','lead_id','type']);
        });

        Schema::create('lead_scores', function (Blueprint $t) {
            $t->id();
            $t->foreignId('site_id')->constrained()->cascadeOnDelete();
            $t->foreignId('lead_id')->constrained('leads')->cascadeOnDelete();
            $t->unsignedInteger('score_raw')->default(0);
            $t->unsignedTinyInteger('score_norm')->default(0); // 1–100
            $t->json('breakdown')->nullable();
            $t->timestamp('last_calculated_at')->nullable();
            $t->timestamps();
            $t->unique(['site_id','lead_id']);
            $t->index(['site_id','score_norm']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_scores');
        Schema::dropIfExists('lead_events');
        Schema::dropIfExists('leads');
    }
};
