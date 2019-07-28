<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Version563Upgrade extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
            This 5.6.3 version migration file makes the following changes:
            1. Removes column 'is_published' since the published status just
            checks if the published_at is not null.

            2. Defaults the published_at to a null value.
            3. Removes the column 'index'.
         */

        // Drop column 'published_at'.
        if (Schema::hasColumn('videos', 'is_published')) {
            Schema::table('videos', function (Blueprint $table) {
                $table->dropColumn('is_published');
            });
        }

        // Changes default 'published_at' to null
        if (Schema::hasColumn('videos', 'published_at')) {
            Schema::table('videos', function (Blueprint $table) {
                $table->datetime('published_at')
                      ->default(null)
                      ->change();
            });
        }

        // Drop column 'index'.
        if (Schema::hasColumn('videos', 'index')) {
            Schema::table('videos', function (Blueprint $table) {
                $table->dropColumn('index');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (! Schema::hasColumn('videos', 'published_at')) {
            Schema::table('videos', function (Blueprint $table) {
                $table->datetime('published_at')
                      ->after('is_published')
                      ->nullable();
            });
        }

        if (! Schema::hasColumn('videos', 'index')) {
            Schema::table('videos', function (Blueprint $table) {
                $table->integer('index')
                      ->after('is_published')
                      ->default(1);
            });
        }

        DB::transaction(function () {
            // Update all videos with publish = true.
            DB::table('videos')->update(['published_at' => now()->toDateTimeString()]);
        });
    }
}
