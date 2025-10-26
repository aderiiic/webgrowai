
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bulk_generations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->text('template_text');
            $table->json('variables')->nullable(); // [["stad" => "Malmö", "produkt" => "Soffa"], ...]
            $table->enum('status', ['pending', 'processing', 'done', 'failed'])->default('pending');
            $table->unsignedInteger('total_count')->default(0);
            $table->unsignedInteger('completed_count')->default(0);
            $table->string('content_type')->default('social'); // social, blog, newsletter, multi
            $table->string('tone')->default('short'); // short, long
            $table->foreignId('site_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bulk_generations');
    }
};
