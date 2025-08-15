<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('usage_alerts', function (Blueprint $t) {
            $t->id();
            $t->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $t->string('period', 7);        // YYYY-MM
            $t->string('metric_key');       // ai.generate, seo.audit, etc
            $t->string('level');            // warn|stop
            $t->timestamp('sent_at');
            $t->timestamps();
            $t->unique(['customer_id','period','metric_key','level']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('usage_alerts');
    }
};
