<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // users: role + onboarding_step
        Schema::table('users', function (Blueprint $t) {
            $t->string('role')->default('customer')->after('password'); // admin|customer
            $t->unsignedTinyInteger('onboarding_step')->default(0)->after('role');
        });

        // customers
        Schema::create('customers', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('contact_email')->nullable();
            $t->string('status')->default('active');
            $t->timestamps();
        });

        // pivot: många användare per kund
        Schema::create('customer_user', function (Blueprint $t) {
            $t->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->string('role_in_customer')->default('owner'); // owner|member
            $t->primary(['customer_id','user_id']);
        });

        // sites: flera sajter per kund
        Schema::create('sites', function (Blueprint $t) {
            $t->id();
            $t->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $t->string('name');
            $t->string('url');
            $t->string('status')->default('active');
            $t->string('public_key')->unique();
            $t->string('secret')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('sites');
        Schema::dropIfExists('customer_user');
        Schema::dropIfExists('customers');
        Schema::table('users', function (Blueprint $t) {
            $t->dropColumn(['role','onboarding_step']);
        });
    }
};
