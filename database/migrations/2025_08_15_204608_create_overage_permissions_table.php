<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('overage_permissions', function (Blueprint $t) {
            $t->id();
            $t->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $t->string('period', 7); // YYYY-MM
            $t->boolean('approved')->default(false);
            $t->timestamp('approved_at')->nullable();
            $t->text('note')->nullable();
            $t->timestamps();
            $t->unique(['customer_id','period']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('overage_permissions');
    }
};
