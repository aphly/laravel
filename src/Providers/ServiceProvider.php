<?php

namespace Aphly\Laravel\Providers;

use Aphly\Laravel\Exceptions\ApiException;
use Illuminate\Database\Eloquent\Builder;

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
        if($exception instanceof \Illuminate\Foundation\Exceptions\Handler){
            return $exception->ignore($class);
        }
	}

	protected function addApiException($class_arr)
	{
		$exception = $this->app['Illuminate\Contracts\Debug\ExceptionHandler'];
		foreach($class_arr as $val){
			$exception->map($val[0],$val[1]);
		}
	}

    function addBuilder(){
        Builder::macro('firstOrError', function () {
            $info = $this->first();
            if (!empty($info)) {
                return $info;
            }else{
                throw new ApiException(['code'=>1,'msg'=>'First Error']);
            }
        });

        Builder::macro('firstOr404', function () {
            $info = $this->first();
            if (!empty($info)) {
                return $info;
            }else{
                throw new ApiException(['code'=>0,'msg'=>'404','data'=>['redirect'=>'/404']]);
            }
        });

        Builder::macro('firstToArray', function () {
            $info = $this->first();
            if (!empty($info)) {
                return $info->toArray();
            }else{
                return [];
            }
        });
    }

}
