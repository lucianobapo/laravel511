<?php

namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        //

        parent::boot($router);

        $router->model('orders', 'App\Models\Order');
        $router->model('products', 'App\Models\Product');
        $router->model('productGroups', 'App\Models\ProductGroup');
        $router->model('partners', 'App\Models\Partner');
        $router->model('costs', 'App\Models\CostAllocate');
        $router->model('addresses', 'App\Models\Address');
        $router->model('documents', 'App\Models\Document');
        $router->model('contacts', 'App\Models\Contact');

//        $router->bind('orders',function($id){
//            return \App\Models\Order::findOrFail($id)->with('partner');
//        });

    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function ($router) {
            require app_path('Http/routes.php');
        });
    }
}
