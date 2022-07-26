<?php

namespace Aphly\Laravel\Providers;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected function addMiddlewareAlias($name, $class)
    {
        $router = $this->app['router'];
        if (method_exists($router, 'aliasMiddleware')) {
            return $router->aliasMiddleware($name, $class);
        }
        return $router->middleware($name, $class);
    }
}
