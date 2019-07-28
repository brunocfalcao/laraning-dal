<?php

use Laraning\DAL\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLaraningSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token', 255);
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('email')->unique();
            $table->string('password', 255);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('videos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255);
            $table->text('description_short');
            $table->text('description_long')->nullable();
            $table->integer('index')->comment('Should be used for the course type. If the video is connected to a Series then it\'s the published_at field that is used to define the sequence.');
            $table->string('vimeo_id', 255)->nullable();
            $table->time('duration')->nullable();
            $table->string('image_path', 255)->nullable();
            $table->string('link_slug', 255);
            $table->integer('series_id')->nullable();
            $table->datetime('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('series', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255);
            $table->text('description_short');
            $table->text('description_long')->nullable();
            $table->string('series_type', 255)->default('series')->comment('Can be type \'series\' or type \'course\'. Series means it\'s a group of video tutorials without any specific order to be seen. Course means it\'s an organized video tutorial structure with a sequence.');
            $table->string('image_path', 255)->nullable();
            $table->string('link_slug', 255);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('social_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('socialiable');
            $table->string('fb_og_title', 255)->nullable();
            $table->string('fb_og_description', 255)->nullable();
            $table->string('fb_og_type', 255)->nullable();
            $table->string('fb_og_image', 255)->nullable();
            $table->string('fb_og_url', 255)->nullable();
            $table->string('twitter_card', 255)->nullable();
            $table->string('twitter_site', 255)->nullable();
            $table->string('twitter_title', 255)->nullable();
            $table->string('twitter_description', 255)->nullable();
            $table->string('twitter_image', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Create super admin user.
        $user = User::create(['name' => env('WAVE_SA_NAME'),
                              'email' => env('WAVE_SA_EMAIL'),
                              'password' => bcrypt(env('WAVE_SA_PASSWORD')), ]);

        // Create super admin role, and assign it to the user.
        $role = Role::create(['name' => 'super-admin', 'guard_name' => 'wave']);
        $user->assignRole('super-admin');

        // Clean assets files.
        File::cleanDirectory(storage_path('app/public'));
        File::cleanDirectory(public_path('storage/assets/wave'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laraning_schema');
    }
}
