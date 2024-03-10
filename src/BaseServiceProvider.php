<?php

namespace Aphly\Laravel;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Middleware\UserAuth;
use Aphly\Laravel\Providers\ServiceProvider;
use Aphly\Laravel\Middleware\Cross;
use Aphly\Laravel\Middleware\ManagerAuth;
use Aphly\Laravel\Middleware\Rbac;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;

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
        $this->loadViewsFrom(__DIR__.'/views/front', 'laravel-front-default');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->addRouteMiddleware('managerAuth', ManagerAuth::class);
        $this->addRouteMiddleware('rbac', Rbac::class);
        $this->addRouteMiddleware('cross', Cross::class);
        $this->addRouteMiddleware('userAuth', UserAuth::class);
		$this->addDontReport(ApiException::class);
		//$this->addApiException([[ModelNotFoundException::class,ApiException::class]]);
        $this->addBuilder();
        Blade::directive('Linclude', function ($view) {
            $view = str_replace('\'','',$view);
            if(view::exists($view)){
                return '<?php echo view(\''.$view.'\'); ?>';
            }else{
                $view_new = str_replace(config('base.view_namespace_front'),config('base.view_namespace_front_default'),$view);
                if(view::exists($view_new)){
                    return '<?php echo view(\''.$view_new.'\'); ?>';
                }else{
                    return '<?php echo view(\''.$view.'\'); ?>';
                }
            }
        });
    }



}
