<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('wp_integrations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('site_id')->constrained()->cascadeOnDelete();
            $t->string('wp_url');
            $t->string('wp_username');
            $t->text('wp_app_password'); // encrypted
            $t->string('status')->default('disconnected'); // connected|disconnected|error
            $t->text('last_error')->nullable();
            $t->timestamps();
            $t->unique('site_id');
        });

        Schema::create('seo_audits', function (Blueprint $t) {
            $t->id();
            $t->foreignId('site_id')->constrained()->cascadeOnDelete();
            $t->unsignedSmallInteger('lighthouse_performance')->nullable();
            $t->unsignedSmallInteger('lighthouse_accessibility')->nullable();
            $t->unsignedSmallInteger('lighthouse_best_practices')->nullable();
            $t->unsignedSmallInteger('lighthouse_seo')->nullable();
            $t->unsignedSmallInteger('title_issues')->default(0);
            $t->unsignedSmallInteger('meta_issues')->default(0);
            $t->json('summary')->nullable(); // valfri extra sammanfattning
            $t->timestamps();
        });

        Schema::create('seo_audit_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('seo_audit_id')->constrained()->cascadeOnDelete();
            $t->string('type'); // title|meta|lighthouse
            $t->string('page_url');
            $t->text('message');
            $t->string('severity')->default('info'); // info|low|medium|high
            $t->json('data')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_audit_items');
        Schema::dropIfExists('seo_audits');
        Schema::dropIfExists('wp_integrations');
    }
};
