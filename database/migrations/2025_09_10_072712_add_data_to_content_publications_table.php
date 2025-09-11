<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('content_publications', function (Blueprint $table) {
            if (!Schema::hasColumn('content_publications', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable()->after('scheduled_at');
            }
            if (!Schema::hasColumn('content_publications', 'queue_ref')) {
                $table->string('queue_ref', 191)->nullable()->after('cancelled_at');
            }
            if (!Schema::hasColumn('content_publications', 'external_url')) {
                $table->text('external_url')->nullable()->after('external_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('content_publications', function (Blueprint $table) {
            if (Schema::hasColumn('content_publications', 'external_url')) {
                $table->dropColumn('external_url');
            }
            if (Schema::hasColumn('content_publications', 'queue_ref')) {
                $table->dropColumn('queue_ref');
            }
            if (Schema::hasColumn('content_publications', 'cancelled_at')) {
                $table->dropColumn('cancelled_at');
            }
        });
    }
};
