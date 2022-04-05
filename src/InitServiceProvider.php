<?php

namespace Aphly\Laravel;

use Aphly\Laravel\Middleware\Common;
use Aphly\Laravel\Middleware\UserAuth;
use Illuminate\Support\ServiceProvider;

class InitServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */

    public function register()
    {
		$this->mergeConfigFrom(
            __DIR__.'/config/laravel.php', 'laravel'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/laravel.php' => config_path('laravel.php'),
        ]);
        $this->publishes([__DIR__.'/public' => public_path('vendor/laravel')]);
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadViewsFrom(__DIR__.'/views', 'laravel');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->addMiddlewareAlias('userAuth', UserAuth::class);
        $this->addMiddlewareAlias('cookie', Common::class);
    }

    protected function addMiddlewareAlias($name, $class)
    {
        $router = $this->app['router'];
        if (method_exists($router, 'aliasMiddleware')) {
            return $router->aliasMiddleware($name, $class);
        }
        return $router->middleware($name, $class);
    }

}
