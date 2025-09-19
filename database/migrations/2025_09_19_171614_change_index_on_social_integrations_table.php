<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('social_integrations', function (Blueprint $table) {
            // 1) Säkerställ separat index på customer_id (så FK kan använda den)
            // Laravel saknar hasIndex out-of-the-box: kolla INFORMATION_SCHEMA
            $hasCustomerIdx = collect(DB::select("
                SELECT 1 FROM INFORMATION_SCHEMA.STATISTICS
                WHERE TABLE_SCHEMA = DATABASE()
                  AND TABLE_NAME = 'social_integrations'
                  AND INDEX_NAME = 'social_integrations_customer_id_index'
                LIMIT 1
            "))->isNotEmpty();

            if (!$hasCustomerIdx) {
                $table->index('customer_id', 'social_integrations_customer_id_index');
            }
        });

        // 2) Droppa gamla unika indexen (customer_id, provider)
        // Försök med namnet, annars med kolumn-listan
        Schema::table('social_integrations', function (Blueprint $table) {
            try {
                $table->dropUnique('social_integrations_customer_id_provider_unique');
            } catch (\Throwable $e) {
                try {
                    $table->dropUnique(['customer_id','provider']);
                } catch (\Throwable $e2) {
                    // Om det fortfarande inte går, kasta tydligt fel
                    throw $e2;
                }
            }
        });

        // 3) Lägg till ny unik index per site+provider
        Schema::table('social_integrations', function (Blueprint $table) {
            $table->unique(['site_id','provider'], 'social_integrations_site_id_provider_unique');
        });
    }

    public function down(): void
    {
        // Backa: ta bort (site_id, provider) unik, återskapa (customer_id, provider) unik
        Schema::table('social_integrations', function (Blueprint $table) {
            try {
                $table->dropUnique('social_integrations_site_id_provider_unique');
            } catch (\Throwable $e) {
                try { $table->dropUnique(['site_id','provider']); } catch (\Throwable $e2) {}
            }

            $table->unique(['customer_id','provider'], 'social_integrations_customer_id_provider_unique');
        });

        // (Valfritt) Lämna kvar customer_id-index – skadar inte.
    }
};
