<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->text('mailchimp_api_key')->nullable(); // encrypted string
            $table->string('mailchimp_audience_id', 120)->nullable();
            $table->string('mailchimp_from_name', 120)->nullable();
            $table->string('mailchimp_reply_to', 190)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->dropColumn([
                'mailchimp_api_key',
                'mailchimp_audience_id',
                'mailchimp_from_name',
                'mailchimp_reply_to',
            ]);
        });
    }
};
