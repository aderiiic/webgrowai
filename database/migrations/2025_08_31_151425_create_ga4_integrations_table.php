<?php
// database/migrations/2025_08_31_100000_create_ga4_integrations_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ga4_integrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('site_id')->index();
            $table->string('property_id', 120);
            $table->longText('service_account_json')->nullable(); // krypterad sträng
            $table->string('status', 24)->default('connected');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('ga4_integrations');
    }
};
