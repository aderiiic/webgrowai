<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('subscriptions', function (Blueprint $t) {
            $t->timestamp('trial_ends_at')->nullable()->after('status');
            $t->string('billing_cycle')->default('monthly')->after('trial_ends_at'); // monthly|annual
            $t->string('last_invoiced_period', 7)->nullable()->after('current_period_end'); // YYYY-MM
        });
    }
    public function down(): void {
        Schema::table('subscriptions', function (Blueprint $t) {
            $t->dropColumn(['trial_ends_at','billing_cycle','last_invoiced_period']);
        });
    }
};
