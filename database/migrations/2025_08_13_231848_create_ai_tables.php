<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('content_templates')) {
            Schema::create('content_templates', function (Blueprint $t) {
                $t->id();
                $t->string('slug')->unique();
                $t->string('name');
                $t->string('provider')->default('auto'); // openai|anthropic|auto
                $t->unsignedSmallInteger('max_tokens')->default(1500);
                $t->decimal('temperature', 3, 2)->default(0.7);
                $t->string('visibility')->default('system'); // system|customer
                $t->timestamps();
            });
        }
        if (!Schema::hasTable('ai_contents')) {
            Schema::create('ai_contents', function (Blueprint $t) {
                $t->id();
                $t->foreignId('customer_id')->constrained()->cascadeOnDelete();
                $t->foreignId('site_id')->nullable()->constrained()->nullOnDelete();
                $t->foreignId('template_id')->constrained('content_templates')->cascadeOnDelete();
                $t->string('title')->nullable();
                $t->string('tone')->nullable(); // short|long
                $t->string('provider')->nullable(); // openai|anthropic
                $t->string('status')->default('draft'); // draft|queued|ready|failed|published
                $t->json('inputs')->nullable(); // audience, goal, keywords, brand etc
                $t->longText('body_md')->nullable();
                $t->text('error')->nullable();
                $t->timestamp('scheduled_at')->nullable();
                $t->timestamps();
            });
        }
    }
    public function down(): void {
        Schema::dropIfExists('ai_contents');
        Schema::dropIfExists('content_templates');
    }
};
