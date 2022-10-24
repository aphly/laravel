<?php

namespace Aphly\Laravel\Providers;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected function addRouteMiddleware($name, $class)
    {
        $router = $this->app['router'];
        if (method_exists($router, 'aliasMiddleware')) {
            return $router->aliasMiddleware($name, $class);
        }
        return $router->middleware($name, $class);
    }

    protected function addMiddleware($class)
    {
        $router = $this->app['Illuminate\Contracts\Http\Kernel'];
        return $router->pushMiddleware($class);
    }
}
