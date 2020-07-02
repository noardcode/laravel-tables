<?php

namespace Noardcode\Tables;

use Illuminate\Support\ServiceProvider;

class FlashAlertsServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/laravel-tables.php' => config_path('laravel-tables.php'),
        ]);
    }
    
    /**
     * Register bindings in the container.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-tables.php', 'laravel-tables');
    }
}
