<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ga4_integrations', function (Blueprint $table) {
            // Byt från timestamp -> datetime för bredare intervall
            $table->dateTime('expires_at')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('ga4_integrations', function (Blueprint $table) {
            // Återställ om du vill (inte nödvändigt)
            // $table->timestamp('expires_at')->nullable()->change();
        });
    }
};
