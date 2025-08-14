<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Hämta alla index på usage_metrics
        $rows = collect(DB::select("SHOW INDEX FROM `usage_metrics`"));

        // Grupp per Key_name => lista av kolumnnamn i ordning + unik-flagga
        $indexes = $rows->groupBy('Key_name')->map(function ($items, $name) {
            $cols = $items->sortBy('Seq_in_index')->pluck('Column_name')->values()->all();
            $unique = (int) $items->first()->Non_unique === 0;
            return ['name' => $name, 'columns' => $cols, 'unique' => $unique];
        });

        // Felaktiga index vi vill ta bort (om de finns)
        $patternsToDrop = [
            ['customer_id','period'],          // saknar metric_key
            ['customer_id','period','key'],    // gammalt namn "key"
        ];

        foreach ($indexes as $idx) {
            foreach ($patternsToDrop as $pat) {
                if ($idx['unique'] && $idx['columns'] === $pat) {
                    // Droppa index med det faktiska namnet
                    DB::statement("ALTER TABLE `usage_metrics` DROP INDEX `{$idx['name']}`");
                }
            }
        }

        // Kontrollera om korrekt unikt index redan finns
        $hasCorrect = $indexes->first(function ($idx) {
                return $idx['unique'] && $idx['columns'] === ['customer_id','period','metric_key'];
            }) !== null;

        // Skapa korrekt unikt index om det saknas
        if (! $hasCorrect) {
            DB::statement("ALTER TABLE `usage_metrics` ADD UNIQUE `usage_metrics_cust_period_metric_key_unique` (`customer_id`,`period`,`metric_key`)");
        }
    }

    public function down(): void
    {
        // Ta bort vårt korrekta index om det finns (ingen återställning av fel index)
        $rows = collect(DB::select("SHOW INDEX FROM `usage_metrics`"));
        $exists = $rows->first(function ($r) {
            return $r->Key_name === 'usage_metrics_cust_period_metric_key_unique';
        });
        if ($exists) {
            DB::statement("ALTER TABLE `usage_metrics` DROP INDEX `usage_metrics_cust_period_metric_key_unique`");
        }
    }
};
