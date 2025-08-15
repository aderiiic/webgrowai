<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('invoices', function (Blueprint $t) {
            $t->id();
            $t->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $t->string('period', 7); // YYYY-MM
            $t->unsignedInteger('plan_amount')->default(0);    // ören
            $t->unsignedInteger('addon_amount')->default(0);   // ören
            $t->unsignedInteger('total_amount')->default(0);   // ören
            $t->string('currency', 3)->default('SEK');
            $t->date('due_date')->nullable();
            $t->string('status')->default('draft'); // draft|sent|paid|overdue|void
            $t->timestamp('sent_at')->nullable();
            $t->timestamp('paid_at')->nullable();
            $t->json('lines')->nullable(); // [{"type":"ai.generate","qty":120,"unit":30,"amount":3600}, ...]
            $t->timestamps();
            $t->index(['customer_id','period']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('invoices');
    }
};
