<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('subscription_change_requests', function (Blueprint $t) {
            $t->id();
            $t->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $t->unsignedBigInteger('desired_plan_id');
            $t->string('billing_cycle')->default('monthly'); // monthly|annual
            $t->string('status')->default('pending'); // pending|approved|rejected
            $t->text('note')->nullable();
            $t->timestamps();

            $t->index(['customer_id','status']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('subscription_change_requests');
    }
};
