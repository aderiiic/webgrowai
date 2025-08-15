<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('plans', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->text('description')->nullable();
            $t->boolean('is_active')->default(true);
            $t->integer('price_monthly')->default(0);
            $t->integer('price_yearly')->default(0);
            $t->timestamps();
        });

        Schema::create('plan_features', function (Blueprint $t) {
            $t->id();
            $t->foreignId('plan_id')->constrained()->cascadeOnDelete();
            $t->string('key');
            $t->string('limit_value')->nullable();
            $t->boolean('is_enabled')->default(true);
            $t->timestamps();
        });

        Schema::create('subscriptions', function (Blueprint $t) {
            $t->id();
            $t->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $t->foreignId('plan_id')->constrained()->cascadeOnDelete();
            $t->string('status')->default('active');
            $t->timestamp('current_period_start')->nullable();
            $t->timestamp('current_period_end')->nullable();
            $t->timestamps();
        });

        Schema::create('usage_metrics', function (Blueprint $t) {
            $t->id();
            $t->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $t->string('period'); // YYYY-MM
            $t->string('key');
            $t->unsignedInteger('used_value')->default(0);
            $t->timestamps();
            $t->unique(['customer_id','period','key']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('usage_metrics');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('plan_features');
        Schema::dropIfExists('plans');
    }
};
