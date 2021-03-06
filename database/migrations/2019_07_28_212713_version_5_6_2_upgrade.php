<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Laraning\DAL\Models\Video;

class Version562Upgrade extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasColumn('users', 'allow_notifications')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('allow_notifications')
                      ->after('remember_token')
                      ->default(true);
            });
        }

        if (! Schema::hasColumn('videos', 'is_published')) {
            Schema::table('videos', function (Blueprint $table) {
                $table->boolean('is_published')
                      ->after('series_id')
                      ->default(false);
            });
        }

        DB::transaction(function () {
            // Update all videos with publish = true.
            Video::where('is_published', false)->update(['is_published' => true]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
