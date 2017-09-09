<?php

namespace App\Providers;

use App\Http\Controllers\Shop\Goods\PageController;
use App\Models\Shop\ShopBaseModel;
use App\Models\Shop\Urls;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use App\Models\Shop\Filters;
use App\Models\Shop\Goods;
use App\Models\Shop\HtmlPages;
use App\Models\Shop\Sections;
use ReflectionClass;

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
        $this->mapSiteRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapSiteRoutes()
    {
        $url = \request()->path();

        if (preg_match('#([a-z]+)\/([0-9]+)\.html#', $url, $matches)) {
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
        elseif (preg_match('#([a-z\-_]+)\.html#', $url, $matches)) {
            $url = $matches[1];
            $U   = Urls::where('url', $url)->first();

            if ($U) {
                $this->showEntity($U->entity, $U->entity_id);
            }
        }

        return $this->show404();

    }

    protected function showEntity($entity, $id)
    {
        $e = Goods::find($id);
//        $e = $entity::find($id);
        if (!$e) {
            return $this->show404();
        }

        if($entity === 'Goods'){
            $app = PageController::class;
        }

        $url = \request()->path();

        Route::any($url, function () use ($app, $e) {
            $controller = app($app);
            return call_user_func_array([$controller, 'index'], [$e]);
        });

    }

    protected function show404()
    {

    }
}
