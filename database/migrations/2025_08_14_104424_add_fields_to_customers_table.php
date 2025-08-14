<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $t) {
            $t->text('weekly_recipients')->nullable()->after('contact_email'); // komma-separerade e-postadresser
            $t->string('weekly_brand_voice')->nullable()->after('weekly_recipients');
            $t->string('weekly_audience')->nullable()->after('weekly_brand_voice');
            $t->string('weekly_goal')->nullable()->after('weekly_audience');
            $t->json('weekly_keywords')->nullable()->after('weekly_goal');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $t) {
            $t->dropColumn(['weekly_recipients','weekly_brand_voice','weekly_audience','weekly_goal','weekly_keywords']);
        });
    }
};
