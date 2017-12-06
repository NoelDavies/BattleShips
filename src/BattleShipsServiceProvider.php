<?php

namespace NoelDavies\BattleShips;

use Illuminate\Support\ServiceProvider;

class BattleShipsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['noeldavies-battleships-grid'] = $this->app->share(function ($app) {
            return new Grid();
        });
        $this->app['noeldavies-battleships-ship'] = $this->app->share(function ($app) {
            return new Ship();
        });
        $this->app['noeldavies-battleships-coordinate'] = $this->app->share(function ($app) {
            return new Coordinate();
        });

        // Register Facade
        $this->app->booting(function () {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('BattleshipGrid', 'NoelDavies\BattleShips\Facades\Grid');
            $loader->alias('BattleshipShip', 'NoelDavies\BattleShips\Facades\Ship');
            $loader->alias('BattleshipPoint', 'NoelDavies\BattleShips\Facades\Coordinate');
        });
    }
}
