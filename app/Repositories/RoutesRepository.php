<?php namespace App\Repositories;

use Illuminate\Support\Facades\Route;

class RoutesRepository{
    public static function erpRoutes(){
        // ERP
        Route::group([
            'domain' => '{host}.'.config('app.domain'),
//            'prefix' => 'delivery',
            'where' => ['host' => 'laravel'],
        ], function(){


            Route::controllers([
                'auth' => 'Auth\AuthController',
                'password' => 'Auth\PasswordController',
            ]);

            // Temporário
//            get('relatorios', ['as'=>'relatorios.index', 'uses'=>'RelatoriosController@index']);

            Route::group([
//                'errorRedirect' => '/',//return redirect(route('orders.index', $host));
//                'host' => '{host}',
                'middleware' => ['auth', 'roles'],
                'roles' => ['Administrator', 'Order Manager']
            ], function() {
                resource('orders','Erp\OrdersController', [
                    'names' => [
                        'index'=>'orders.index',
                        'create'=>'orders.create',
                        'store'=>'orders.store',
                        'edit'=>'orders.edit',
                        'update'=>'orders.update',
                        'destroy'=>'orders.destroy',
                    ],
                    'only'=>[
                        'index',
                        'create',
                        'store',
                        'edit',
                        'update',
                        'destroy',
                    ],
                ]);

                resource('products','Erp\ProductsController', [
                    'names' => [
                        'index'=>'products.index',
                        'edit'=>'products.edit',
                        'update'=>'products.update',
                        'store'=>'products.store',
                        'destroy'=>'products.destroy',
                    ],
                    'only'=>[
                        'index',
                        'edit',
                        'update',
                        'store',
                        'destroy',
                    ],
                ]);

                resource('partners','Erp\PartnersController', [
                    'names' => [
                        'index'=>'partners.index',
                        'store'=>'partners.store',
                        'destroy'=>'partners.destroy',
                    ],
                    'only'=>[
                        'index',
                        'store',
                        'destroy',
                    ],
                ]);

                get('reports/estoque', ['as'=>'reports.estoque', 'uses'=>'Erp\ReportsController@estoque']);
            });


//
//            resource('sharedCurrencies','Erp\SharedCurrenciesController', [
//                'names' => [
//                    'index'=>'sharedCurrencies.index',
//                    'show'=>'sharedCurrencies.show',
//                ],
//                'only'=>[
//                    'index',
//                    'show',
//                ],
//            ]);
        });
    }

    public static function deliveryRoutes(){
        // Delivery
        Route::group([
            'domain' => '{host}.'.config('app.domain'),
//            'prefix' => 'delivery',
            'where' => ['host' => 'delivery'],
        ], function(){

            get('/', ['as'=>'delivery.index', 'uses'=>'DeliveryController@index']);
            post('/addCart', ['as'=>'delivery.addCart', 'uses'=>'DeliveryController@addCart']);
            get('/emptyCart', ['as'=>'delivery.emptyCart', 'uses'=>'DeliveryController@emptyCart']);
            get('/pedido', ['as'=>'delivery.pedido', 'uses'=>'DeliveryController@pedido']);
            post('/addOrder', ['as'=>'delivery.addOrder', 'uses'=>'DeliveryController@addOrder']);

            get('images/{file}', ['as'=>'images', 'uses'=>'ImageController@show']);
        });
    }
}