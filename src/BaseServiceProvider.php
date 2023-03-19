<?php

namespace Aphly\Laravel;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Providers\ServiceProvider;
use Aphly\Laravel\Middleware\Common;
use Aphly\Laravel\Middleware\Cross;
use Aphly\Laravel\Middleware\ManagerAuth;
use Aphly\Laravel\Middleware\Rbac;

class BaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */

    public function register()
    {
		$this->mergeConfigFrom(
            __DIR__.'/config/base.php', 'base'
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
            __DIR__.'/config/base.php' => config_path('base.php'),
            __DIR__.'/public' => public_path('static/base')
        ]);
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadViewsFrom(__DIR__.'/views', 'laravel');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->addRouteMiddleware('managerAuth', ManagerAuth::class);
        $this->addRouteMiddleware('rbac', Rbac::class);
        $this->addMiddleware(Common::class);
        $this->addRouteMiddleware('cross', Cross::class);
		$this->addDontReport(ApiException::class);
		//$this->addApiException([[ModelNotFoundException::class,ApiException::class]]);
        $this->addBuilder();
    }



}
