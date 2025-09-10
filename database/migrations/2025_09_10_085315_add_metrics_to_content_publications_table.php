<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('content_publications', function (Blueprint $table) {
            if (!Schema::hasColumn('content_publications', 'metrics')) {
                $table->json('metrics')->nullable()->after('payload');
            }
            if (!Schema::hasColumn('content_publications', 'metrics_refreshed_at')) {
                $table->timestamp('metrics_refreshed_at')->nullable()->after('metrics');
            }
        });
    }

    public function down(): void
    {
        Schema::table('content_publications', function (Blueprint $table) {
            if (Schema::hasColumn('content_publications', 'metrics_refreshed_at')) {
                $table->dropColumn('metrics_refreshed_at');
            }
            if (Schema::hasColumn('content_publications', 'metrics')) {
                $table->dropColumn('metrics');
            }
        });
    }
};
