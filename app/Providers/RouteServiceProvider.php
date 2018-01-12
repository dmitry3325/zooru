<?php

namespace App\Providers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Shop\Goods\PageController;
use App\Http\Controllers\Shop\Goods\SectionController;
use App\Models\Shop\ShopBaseModel;
use App\Models\Shop\Urls;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
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
        $this->mapCustomRoutes();

        $this->mapSiteRoutes();
    }

    /**
     * Define the "goods" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     */
    protected function mapSiteRoutes()
    {
        //TODO роуты с числами не работают
        $url = \request()->path();

        if (preg_match('#([a-z]+)\/([0-9]+)#', $url, $matches)) {
            $entity    = ucfirst($matches[1]);
            $entity_id = intval($matches[2]);

            if (ShopBaseModel::checkEntity($entity)) {
                $U = Urls::where('entity', $entity)
                    ->where('entity_id', $entity_id)
                    ->first();

                if ($U) {
                    redirect('/' . $U->url . '.html', 301)->send();
                }
                else {
                    return $this->showEntity($entity, $entity_id);
                }
            }

        }
        elseif (preg_match('#([a-z0-9\-_]+)\.html#', $url, $matches)) {
            $url = $matches[1];
            $U   = Urls::where('url', $url)->first();

            if ($U) {
                return $this->showEntity($U->entity, $U->entity_id);
            }
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
    protected function mapCustomRoutes(){
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
        }
        else if ($entity === 'Filters') {
            $app = SectionController::class;
            array_unshift($params, $e->section);
        }
        else if ($entity === 'Sections') {
            $app = SectionController::class;
        }
        $url = \request()->path();

        return Route::middleware(['web'])->any($url, function () use ($app, $params) {
            $controller = app($app);
            return call_user_func_array([$controller, 'index'], $params);
        });

    }

    protected function show404()
    {
        $menu = Controller::getMenu();
        \View::share ( 'menu', $menu );
        return response()->view('errors.404', [], 404);
    }
}
