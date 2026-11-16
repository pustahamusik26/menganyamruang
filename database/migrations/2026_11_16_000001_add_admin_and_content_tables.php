<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'username')) {
                    $table->string('username', 64)->unique()->nullable()->after('id');
                }
                if (!Schema::hasColumn('users', 'role')) {
                    $table->string('role', 32)->default('admin')->after('email');
                }
            });
        }

        if (!Schema::hasTable('documentary_videos')) {
            Schema::create('documentary_videos', function (Blueprint $table) {
                $table->id();
                $table->integer('sort_order')->default(1)->index();
                $table->string('code', 24)->unique();
                $table->string('title');
                $table->string('location_subtitle')->nullable();
                $table->string('category', 100)->default('Situs & Cagar Budaya');
                $table->string('youtube_id', 32);
                $table->string('youtube_url');
                $table->string('embed_url');
                $table->string('thumbnail')->nullable();
                $table->string('duration', 24)->default('05:00');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('team_members')) {
            Schema::create('team_members', function (Blueprint $table) {
                $table->id();
                $table->integer('sort_order')->default(1)->index();
                $table->string('no', 12)->default('01');
                $table->string('name');
                $table->string('institution')->nullable();
                $table->string('role');
                $table->boolean('is_proposer')->default(false)->index();
                $table->string('website')->nullable();
                $table->string('website_label')->nullable();
                $table->text('bio')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('team_members');
        Schema::dropIfExists('documentary_videos');

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'username')) {
                    $table->dropColumn('username');
                }
                if (Schema::hasColumn('users', 'role')) {
                    $table->dropColumn('role');
                }
            });
        }
    }
};
