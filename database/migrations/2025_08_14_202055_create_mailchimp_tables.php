<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $t) {
            $t->text('mailchimp_api_key')->nullable()->after('weekly_keywords');
            $t->string('mailchimp_audience_id')->nullable()->after('mailchimp_api_key'); // List ID
            $t->string('mailchimp_from_name')->nullable()->after('mailchimp_audience_id');
            $t->string('mailchimp_reply_to')->nullable()->after('mailchimp_from_name');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $t) {
            $t->dropColumn(['mailchimp_api_key','mailchimp_audience_id','mailchimp_from_name','mailchimp_reply_to']);
        });
    }
};
