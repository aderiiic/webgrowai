<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('weekly_plans', function (Blueprint $t) {
            $t->id();
            $t->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $t->date('run_date');                 // datum för körning (måndag/fredag)
            $t->string('run_tag');                // monday|friday
            $t->string('type');                   // campaigns|topics|next_week
            $t->text('title')->nullable();        // rubrik för listan
            $t->longText('content_md')->nullable(); // markdown-innehåll
            $t->timestamp('emailed_at')->nullable();
            $t->timestamps();
            $t->index(['customer_id','run_date']);
            $t->index(['customer_id','run_tag']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weekly_plans');
    }
};
