<?php

namespace App\Providers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Shop\Goods\PageController;
use App\Http\Controllers\Shop\Goods\SectionController;
use App\Models\Shop\Urls;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * @var \Request
     */
    protected $currentRequest;

    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->currentRequest = \request();

        $this->mapAjaxRoutes();
        $this->mapCustomRoutes();
        $this->mapSiteRoutes();
    }

    protected function mapAjaxRoutes()
    {

        $originalUrl = $this->currentRequest->path();
        if (substr($originalUrl, 0, 5) === 'ajax/') {
            $namespace = $this->namespace . '\Ajax\\';

            $method = 'index';
            if ($this->currentRequest->get('method')) {
                $method = $this->currentRequest->get('method');
            }

            $url = str_replace('ajax/', '', $originalUrl);
            $parts = explode('/', $url);
            $appName = [];
            foreach ($parts as $p) {
                if (!$p) {
                    continue;
                }
                $appName[] = ucfirst($p);
            }

            if (count($appName)) {
                if (count($appName) > 1) {
                    $namespace .= $appName[0] . '\\';
                    unset($appName[0]);
                }
            }
            $appName = implode('', $appName);
            if (class_exists($namespace . $appName . 'Controller') && method_exists($namespace . $appName . 'Controller',
                    $method)
            ) {
                $class = $appName . 'Controller';

                $app = $namespace . $class;

                if (isset($app::$appSetts)) {
                    \View::share('app_settings', $app::$appSetts);
                }

                $params = $this->currentRequest->get('params');
                Route::middleware(['web'])->any($originalUrl, function () use ($app, $method, $params) {
                    $controller = app($app);

                    return call_user_func_array([$controller, $method], $params ? $params : []);
                });
            } else {
                $this->show404();
            }

        }
    }

    /**
     * Define the "goods" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     */
    protected function mapSiteRoutes()
    {
        $url = $this->currentRequest->path();

        $U = Urls::getEntityByUrl($url);

        if($U){
            return $this->showEntity($U->entity, $U->entity_id);
        }

        $this->show404();
    }

    /**
     * Define the "custom" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapCustomRoutes()
    {
        Route::group([
            'middleware' => ['web'],
            'namespace'  => $this->namespace,
        ], function ($router) {
            require_once base_path('routes/auth.php');
        });
    }

    protected function showEntity($entity, $id)
    {
        $e = $entity::find($id);
        if (!$e) {
            return $this->show404();
        }

        $params = [$e];

        if ($entity === 'Goods') {
            $app = PageController::class;
        } else {
            if ($entity === 'Filters') {
                $app = SectionController::class;
                array_unshift($params, $e->section);
            } else {
                if ($entity === 'Sections') {
                    $app = SectionController::class;
                }
            }
        }
        $url = \request()->path();

        return Route::middleware(['web', 'auth'])->any($url, function () use ($app, $params) {
            $controller = app($app);

            return call_user_func_array([$controller, 'index'], $params);
        });

    }

    protected function show404()
    {
        $menu = Controller::getMenu();
        \View::share('menu', $menu);

        return response()->view('errors.404', [], 404);
    }
}
