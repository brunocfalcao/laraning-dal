<?php

namespace Laraning\DAL;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class LaraningDALServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        if (! class_exists('CreateLaraningSchema')) {
            $timestamp = date('Y_m_d_His', time());
            $this->publishes([
                __DIR__.'/../Database/Migrations/create_laraning_schema.php.stub' => $this->app->databasePath()."/migrations/{$timestamp}_create_laraning_schema.php",
            ], 'laraning-create-schema');
        }

        if (! class_exists('Version562Upgrade')) {
            $timestamp = date('Y_m_d_His', time());
            $this->publishes([
                __DIR__.'/../Database/Migrations/version_5_6_2_upgrade.php.stub' => $this->app->databasePath()."/migrations/{$timestamp}_version_5_6_2_upgrade.php",
            ], 'laraning-upgrade-5-6-2');
        }

        if (! class_exists('Version563Upgrade')) {
            $timestamp = date('Y_m_d_His', time());
            $this->publishes([
                __DIR__.'/../Database/Migrations/version_5_6_3_upgrade.php.stub' => $this->app->databasePath()."/migrations/{$timestamp}_version_5_6_3_upgrade.php",
            ], 'laraning-upgrade-5-6-3');
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
