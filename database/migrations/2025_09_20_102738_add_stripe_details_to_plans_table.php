<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->string('stripe_price_monthly')->nullable()->after('price_monthly');
            $table->string('stripe_price_yearly')->nullable()->after('price_yearly');
            $table->index(['is_active', 'price_monthly']);
        });
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropIndex(['is_active', 'price_monthly']);
            $table->dropColumn(['stripe_price_monthly', 'stripe_price_yearly']);
        });
    }
};
