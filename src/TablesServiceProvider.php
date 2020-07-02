<?php

namespace Noardcode\Tables;

use Illuminate\Support\ServiceProvider;
use Noardcode\Tables\Components\Table;

/**
 * Class TablesServiceProvider
 *
 * @package Noardcode\Tables
 */
class TablesServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/laravel-tables.php' => config_path('laravel-tables.php'),
        ]);

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'noardcode');

        $this->loadViewComponentsAs('noardcode', [Table::class]);
    }

    /**
     * Register bindings in the container.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-tables.php', 'laravel-tables');
    }
}
