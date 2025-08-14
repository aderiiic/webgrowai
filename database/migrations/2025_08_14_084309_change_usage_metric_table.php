<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('usage_metrics', function (Blueprint $t) {
            // Lägg ny kolumn
            $t->string('metric_key')->nullable()->after('period');
        });

        // Kopiera data
        DB::table('usage_metrics')->update([
            'metric_key' => DB::raw('`key`')
        ]);

        // Ta bort gamla index/unique om de finns
        try { Schema::table('usage_metrics', fn (Blueprint $t) => $t->dropUnique(['customer_id','period','key'])); } catch (\Throwable $e) {}
        try { Schema::table('usage_metrics', fn (Blueprint $t) => $t->dropIndex(['customer_id','period'])); } catch (\Throwable $e) {}

        // Droppa gamla kolumnen
        Schema::table('usage_metrics', function (Blueprint $t) {
            $t->dropColumn('key');
        });

        // Sätt NOT NULL och nya index
        Schema::table('usage_metrics', function (Blueprint $t) {
            $t->string('metric_key')->nullable(false)->change();
            $t->unique(['customer_id','period','metric_key']);
            $t->index(['customer_id','period']);
        });
    }

    public function down(): void
    {
        // Backa: återskapa key, kopiera tillbaka och droppa metric_key
        Schema::table('usage_metrics', function (Blueprint $t) {
            $t->string('key')->nullable()->after('period');
        });

        DB::table('usage_metrics')->update([
            'key' => DB::raw('`metric_key`')
        ]);

        try { Schema::table('usage_metrics', fn (Blueprint $t) => $t->dropUnique(['customer_id','period','metric_key'])); } catch (\Throwable $e) {}
        try { Schema::table('usage_metrics', fn (Blueprint $t) => $t->dropIndex(['customer_id','period'])); } catch (\Throwable $e) {}

        Schema::table('usage_metrics', function (Blueprint $t) {
            $t->dropColumn('metric_key');
            $t->string('key')->nullable(false)->change();
            $t->unique(['customer_id','period','key']);
            $t->index(['customer_id','period']);
        });
    }
};
