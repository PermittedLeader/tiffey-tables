<?php
namespace Permittedleader\Tables;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class TablesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__."/../config/tables.php" => config_path('forms.php')
        ],'tables-config');
        $this->publishes([
            __DIR__."/../resources/views" => resource_path('views/vendor/tables')
        ],'tables-views');
        $this->loadViewsFrom(__DIR__.'/../resources/views','tables');
        Blade::componentNamespace('Permittedleader\\Tables\\View','tables');

        $this->loadTranslationsFrom(__DIR__.'/../lang', 'tables');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__."/../config/tables.php",'tables');
        
    }
}