<?php

/*
 * This file is part of Laravel Flysystem.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Flysystem;

use Orchestra\Support\Providers\ServiceProvider;

/**
 * This is the flysystem service provider class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class FlysystemServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->addConfigComponent('graham-campbell/flysystem', 'graham-campbell/flysystem', realpath(__DIR__.'/../config'));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerFactory();
        $this->registerManager();
    }

    /**
     * Register the factory class.
     *
     * @return void
     */
    protected function registerFactory()
    {
        $this->app->singleton('flysystem.factory', function ($app) {
            $adapter = new Adapters\ConnectionFactory();
            $cache = new Cache\ConnectionFactory($app['cache']);

            return new Factories\FlysystemFactory($adapter, $cache);
        });

        $this->app->alias('flysystem.factory', 'GrahamCampbell\Flysystem\Factories\FlysystemFactory');
    }

    /**
     * Register the manager class.
     *
     * @return void
     */
    protected function registerManager()
    {
        $this->app->singleton('flysystem', function ($app) {
            $config = $app['config'];
            $factory = $app['flysystem.factory'];

            return new FlysystemManager($config, $factory);
        });

        $this->app->alias('flysystem', 'GrahamCampbell\Flysystem\FlysystemManager');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'flysystem',
            'flysystem.factory',
        ];
    }
}
