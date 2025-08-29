<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('image_assets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->index();
            $table->unsignedBigInteger('uploaded_by')->nullable()->index();
            $table->string('disk')->default('s3');
            $table->string('path');              // S3 key: customers/{customer}/images/{Y}/{m}/{uuid}.{ext}
            $table->string('thumb_path')->nullable(); // 256x256
            $table->string('original_name')->nullable();
            $table->string('mime', 100)->nullable();
            $table->unsignedBigInteger('size_bytes')->default(0);
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->string('sha256', 64)->nullable()->index();
            $table->unsignedInteger('usage_count')->default(0);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();

            // Om ni har customers-tabell, lägg gärna foreign key
            // $table->foreign('customer_id')->references('id')->on('customers')->cascadeOnDelete();
        });

        Schema::create('content_publication_image', function (Blueprint $table) {
            $table->unsignedBigInteger('content_publication_id');
            $table->unsignedBigInteger('image_asset_id');
            $table->timestamp('used_at')->nullable();
            $table->primary(['content_publication_id', 'image_asset_id'], 'cpi_pk');
            $table->index('image_asset_id');

            // FK om tabellerna finns namngivna exakt så
            // $table->foreign('content_publication_id')->references('id')->on('content_publications')->cascadeOnDelete();
            // $table->foreign('image_asset_id')->references('id')->on('image_assets')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_publication_image');
        Schema::dropIfExists('image_assets');
    }
};
