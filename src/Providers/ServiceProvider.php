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

	protected function addDontReport($class)
	{
		$exception = $this->app['Illuminate\Contracts\Debug\ExceptionHandler'];
		return $exception->ignore($class);
	}

	protected function addApiException($class_arr)
	{
		$exception = $this->app['Illuminate\Contracts\Debug\ExceptionHandler'];
		foreach($class_arr as $val){
			$exception->map($val[0],$val[1]);
		}
	}

}
