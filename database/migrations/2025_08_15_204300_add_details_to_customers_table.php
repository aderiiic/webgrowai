<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('customers', function (Blueprint $t) {
            $t->string('company_name')->nullable()->after('name');
            $t->string('org_nr')->nullable();
            $t->string('vat_nr')->nullable();
            $t->string('billing_address')->nullable();
            $t->string('billing_zip', 20)->nullable();
            $t->string('billing_city')->nullable();
            $t->string('billing_country', 2)->nullable();
            $t->string('billing_email')->nullable();
            $t->string('contact_name')->nullable();
            $t->string('contact_phone')->nullable();
        });
    }
    public function down(): void {
        Schema::table('customers', function (Blueprint $t) {
            $t->dropColumn([
                'company_name','org_nr','vat_nr','billing_address','billing_zip','billing_city',
                'billing_country','billing_email','contact_name','contact_phone'
            ]);
        });
    }
};
