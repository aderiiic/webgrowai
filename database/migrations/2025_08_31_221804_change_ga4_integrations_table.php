<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ga4_integrations', function (Blueprint $table) {
            // property_id ska kunna sättas efter OAuth
            $table->string('property_id', 120)->nullable()->change();

            // OAuth-kolumner
            if (!Schema::hasColumn('ga4_integrations', 'provider')) {
                $table->string('provider', 24)->default('oauth')->after('site_id');
            }
            if (!Schema::hasColumn('ga4_integrations', 'access_token')) {
                $table->longText('access_token')->nullable()->after('provider');
            }
            if (!Schema::hasColumn('ga4_integrations', 'refresh_token')) {
                $table->longText('refresh_token')->nullable()->after('access_token');
            }
            if (!Schema::hasColumn('ga4_integrations', 'expires_at')) {
                $table->timestamp('expires_at')->nullable()->after('refresh_token');
            }

            // Valfria fält som används i koden
            if (!Schema::hasColumn('ga4_integrations', 'stream_id')) {
                $table->string('stream_id', 120)->nullable()->after('property_id');
            }
            if (!Schema::hasColumn('ga4_integrations', 'hostname')) {
                $table->string('hostname', 190)->nullable()->after('stream_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ga4_integrations', function (Blueprint $table) {
            // Backa endast säkert det som är rimligt – lämna tokens om du vill
            // $table->string('property_id', 120)->nullable(false)->change();
            // $table->dropColumn(['provider','access_token','refresh_token','expires_at','stream_id','hostname']);
        });
    }
};
