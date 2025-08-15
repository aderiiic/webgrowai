<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('demo_requests', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('email');
            $t->string('company')->nullable();
            $t->text('notes')->nullable();
            $t->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('demo_requests');
    }
};
