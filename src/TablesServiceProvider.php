<?php

namespace Noardcode\Tables;

use Illuminate\Support\Facades\Blade;
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
            __DIR__.'/../resources/sass' => resource_path('sass/vendor/noardcode'),
        ], 'assets');

        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/noardcode'),
        ], 'lang');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'noardcode');

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'noardcode');

        Blade::component('noardcode-tables', Table::class);
    }

    /**
     * Register bindings in the container.
     */
    public function register()
    {
        //
    }
}
