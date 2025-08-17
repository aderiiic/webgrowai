<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('post_suggestions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('provider', 50)->index(); // 'linkedin'
            $table->string('topic')->nullable();
            $table->text('content');
            $table->json('recommended_times')->nullable();
            $table->timestamp('expires_at')->index();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('post_suggestions');
    }
};
