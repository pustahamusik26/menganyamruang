<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cultural_sites', function (Blueprint $table) {
            $table->id();
            $table->integer('marker_number')->index();
            $table->string('site_code', 24)->unique();
            $table->string('name');
            $table->string('maps_name')->nullable();
            $table->string('category')->index();
            $table->string('sub_category')->nullable()->index();
            $table->string('gallery_group')->nullable()->index();
            $table->string('color_name', 100)->default('Merah');
            $table->string('color_hex', 16)->default('#B91C1C');
            $table->string('year_period')->nullable();
            $table->text('address')->nullable();
            $table->string('desa_kelurahan')->nullable()->index();
            $table->string('kecamatan')->nullable()->index();
            $table->string('regency_city')->default('Kota Medan');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('uniqueness')->nullable();
            $table->string('source_name')->nullable();
            $table->text('source_url')->nullable();
            $table->text('gmaps_url')->nullable();
            $table->string('thumbnail')->nullable();
            $table->json('photos')->nullable();
            $table->boolean('is_featured_gallery')->default(true);
            $table->string('sheet_origin')->default('Data Situs Fix');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cultural_sites');
    }
};
