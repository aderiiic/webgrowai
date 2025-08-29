<?php

use App\Models\Site;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('social_integrations', function (Blueprint $table) {
            // 1) Lägg till nullable + FK (SET NULL initialt för säkerhet)
            if (!Schema::hasColumn('social_integrations', 'site_id')) {
                $table->foreignId('site_id')->nullable()->after('customer_id')->constrained()->nullOnDelete();
            }
            $table->index(['site_id', 'provider'], 'social_integrations_site_provider_idx');
        });

        // 2) Backfill: välj första sajten för varje rad om möjligt
        $rows = DB::table('social_integrations')->select('id','customer_id','site_id')->get();
        foreach ($rows as $row) {
            if ($row->site_id) {
                continue;
            }
            $siteId = Site::where('customer_id', $row->customer_id)->orderBy('id')->value('id');
            if ($siteId) {
                DB::table('social_integrations')->where('id', $row->id)->update(['site_id' => $siteId]);
            }
        }

        // 3) Byt FK till CASCADE, men låt kolumnen vara nullable tills vi säkrat 100%
        Schema::table('social_integrations', function (Blueprint $table) {
            $table->dropForeign(['site_id']); // droppa SET NULL-FK
        });

        Schema::table('social_integrations', function (Blueprint $table) {
            $table->foreign('site_id')->references('id')->on('sites')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('social_integrations', function (Blueprint $table) {
            $table->dropIndex('social_integrations_site_provider_idx');
            $table->dropForeign(['site_id']);
            $table->dropColumn('site_id');
        });
    }
};
