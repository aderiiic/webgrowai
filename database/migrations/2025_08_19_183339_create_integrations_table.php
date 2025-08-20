<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('integrations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('site_id')->constrained()->cascadeOnDelete();
            $t->string('provider'); // wordpress|shopify|custom
            $t->json('credentials');
            $t->string('status')->default('disconnected'); // disconnected|connected|error
            $t->text('last_error')->nullable();
            $t->timestamp('synced_at')->nullable();
            $t->timestamps();
            $t->unique(['site_id','provider']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('integrations');
    }
};
