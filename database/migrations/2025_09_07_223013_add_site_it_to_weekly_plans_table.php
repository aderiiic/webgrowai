<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('weekly_plans', function (Blueprint $table) {
            $table->unsignedBigInteger('site_id')->nullable()->after('customer_id');
            $table->index(['customer_id', 'site_id', 'run_date', 'run_tag'], 'weekly_plans_customer_site_date_tag_idx');
        });
    }

    public function down(): void
    {
        Schema::table('weekly_plans', function (Blueprint $table) {
            $table->dropIndex('weekly_plans_customer_site_date_tag_idx');
            $table->dropColumn('site_id');
        });
    }
};
