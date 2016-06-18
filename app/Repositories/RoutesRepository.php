<?php namespace App\Repositories;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;

class RoutesRepository{

    public static function oAuth2Routes(){
        post('/oauth/access_token', function(){
            return Response::json(Authorizer::issueAccessToken());
        });
        get('/angularTemplates/login',  ['as'=>'angularTemplates.login', 'uses'=>'Angular\TemplatesController@login']);
        get('/angularTemplates/productsCardapio',  ['as'=>'angularTemplates.productsCardapio', 'uses'=>'Angular\TemplatesController@productsCardapio']);

        Route::group([
//            'domain' => '{host}.'.config('app.domain'),
//            'prefix' => 'delivery',
//            'where' => ['host' => 'laravel'],
            'middleware' => 'oauth',
        ],
            function(){
                get('/oauth/productsCardapio', ['as'=>'oauth.productsCardapio', 'uses'=>'OAuth2\DataController@productsCardapio']);
            }
        );
    }

    public static function erpRoutes(){

        // ERP
        Route::group([
//            'domain' => '{host}.'.config('app.domain'),
            'prefix' => 'laravel',
//            'where' => ['host' => 'laravel'],
            'middleware' => 'csrf',
        ],
            function(){
                self::authRoutes();
                self::adminRoutes();
                self::nonAdminRoutes();
                // Temporário
    //            get('relatorios', ['as'=>'relatorios.index', 'uses'=>'RelatoriosController@index']);
            }
        );
    }

    public static function deliveryRoutes(){
        // Delivery routes
        Route::group([
            //'domain' => '{host}.'.config('app.domain'),
            //'domain' => '{host}.'.config('app.domain'),
            'middleware' => config('delivery.forceSiteSSL')?['secure']:[],
//            'prefix' => 'delivery',
//            'where' => ['host' => 'delivery'],
        ],
            function(){
                get('/', ['as'=>'delivery.index', 'uses'=>'DeliveryController@index']);
                get('images/{file}', ['as'=>'images', 'uses'=>'FileController@showImage']);
                post('/addCart', ['as'=>'delivery.addCart', 'uses'=>'DeliveryController@addCart']);
                get('/emptyCart', ['as'=>'delivery.emptyCart', 'uses'=>'DeliveryController@emptyCart']);
                get('/pedido', ['as'=>'delivery.pedido', 'uses'=>'DeliveryController@pedido']);
                post('/addOrder', ['as'=>'delivery.addOrder', 'uses'=>'DeliveryController@addOrder']);
            }
        );
    }

    public static function galleryRoutes(){
        // Gallery
        Route::group([
            'domain' => '{host}.'.config('app.domain'),
//            'prefix' => 'delivery',
            'where' => ['host' => 'gallery'],
        ], function(){

            get('/gallery', ['as'=>'gallery.index', 'uses'=>'GalleryController@index']);
//            controller('/', 'GalleryController', [
//                'getIndex'=>'gallery.index',
//            ]);
        });
    }

    private static function authRoutes()
    {
        Route::controller('auth', 'Auth\AuthController', [
            'getLogin'=>'auth.getLogin',
            'getLogout'=>'auth.getLogout',
            'getRegister'=>'auth.getRegister',
        ]);
        Route::controller('password', 'Auth\PasswordController', [
            'getEmail'=>'password.getEmail',
        ]);

//        Route::controllers([
////            'auth'
//// => 'Auth\AuthController',
//            'password' => 'Auth\PasswordController',
//        ]);
    }

    private static function ordersRoutes()
    {
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

        get('orders/abertas', ['as'=>'orders.abertas', 'uses'=>'Erp\OrdersController@getAbertas']);
        get('orders/compras', ['as'=>'orders.compras', 'uses'=>'Erp\OrdersController@getCompras']);
        get('orders/vendas', ['as'=>'orders.vendas', 'uses'=>'Erp\OrdersController@getVendas']);
    }

    private static function productsRoutes()
    {
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
    }

    private static function partnersRoutes()
    {
        resource('partners','Erp\PartnersController', [
            'names' => [
                'index'=>'partners.index',
                'store'=>'partners.store',
                'destroy'=>'partners.destroy',
                'edit'=>'partners.edit',
                'update'=>'partners.update',
            ],
            'only'=>[
                'index',
                'store',
                'destroy',
                'edit',
                'update',
            ],
        ]);
    }

    private static function costsRoutes()
    {
        resource('costs','Erp\CostsController', [
            'names' => [
                'index'=>'costs.index',
                'store'=>'costs.store',
                'destroy'=>'costs.destroy',
                'edit'=>'costs.edit',
                'update'=>'costs.update',
            ],
            'only'=>[
                'index',
                'store',
                'destroy',
                'edit',
                'update',
            ],
        ]);
    }

    private static function addressesRoutes()
    {
        resource('addresses','Erp\AddressesController', [
            'names' => [
                'index'=>'addresses.index',
                'store'=>'addresses.store',
                'destroy'=>'addresses.destroy',
                'edit'=>'addresses.edit',
                'update'=>'addresses.update',
            ],
            'only'=>[
                'index',
                'store',
                'destroy',
                'edit',
                'update',
            ],
        ]);
    }

    private static function documentsRoutes()
    {
        resource('documents','Erp\DocumentsController', [
            'names' => [
                'index'=>'documents.index',
                'store'=>'documents.store',
                'destroy'=>'documents.destroy',
                'edit'=>'documents.edit',
                'update'=>'documents.update',
            ],
            'only'=>[
                'index',
                'store',
                'destroy',
                'edit',
                'update',
            ],
        ]);
    }

    private static function contactsRoutes()
    {
        resource('contacts','Erp\ContactsController', [
            'names' => [
                'index'=>'contacts.index',
                'store'=>'contacts.store',
                'destroy'=>'contacts.destroy',
                'edit'=>'contacts.edit',
                'update'=>'contacts.update',
            ],
            'only'=>[
                'index',
                'store',
                'destroy',
                'edit',
                'update',
            ],
        ]);
    }

    private static function adminRoutes()
    {
        Route::group([
            //'errorRedirect' => '/',//return redirect(route('orders.index', $host));
            //'host' => '{host}',
            'middleware' => ['auth', 'roles'],
            'roles' => ['Root', 'Administrator']
        ],
            function() {
                self::ordersRoutes();
                self::productsRoutes();
                self::partnersRoutes();
                self::costsRoutes();
                self::addressesRoutes();
                self::documentsRoutes();
                self::contactsRoutes();
                get('attachment/{file}', ['as'=>'attachment', 'uses'=>'FileController@showAttachment']);

                controller('confirmations', 'Erp\OrderConfirmationsController', [
                    //                    'getIndex'=>'confirmations.index',
                    'getConfirm'=>'confirmations.getConfirm',
                    'postConfirm'=>'confirmations.postConfirm',
                ]);
            }
        );
    }

    private static function nonAdminRoutes()
    {
        Route::group([
//          'middleware' => ['auth', 'roles'],
            'roles' => ['Root', 'Administrator']
        ],
            function() {
                get('reports/estoque', ['as'=>'reports.estoque', 'uses'=>'Erp\ReportsController@estoque']);
                get('reports/estatOrdem', ['as'=>'reports.estatOrdem', 'uses'=>'Erp\ReportsController@estatOrdem']);
                get('reports/estatOrdemFinalizadas', ['as'=>'reports.estatOrdemFinalizadas', 'uses'=>'Erp\ReportsController@estatOrdemFinalizadas']);
                get('reports/dre', ['as'=>'reports.dre', 'uses'=>'Erp\ReportsController@dre']);
                get('reports/dre/pdf', ['as'=>'reports.drePdf', 'uses'=>'Erp\ReportsController@drePdf']);
                get('reports/diarioGeral', ['as'=>'reports.diarioGeral', 'uses'=>'Erp\ReportsController@diarioGeral']);
                get('reports/cardapio', ['as'=>'reports.cardapio', 'uses'=>'Erp\ReportsController@cardapio']);
                get('reports/cardapio/pdf', ['as'=>'reports.cardapioPdf', 'uses'=>'Erp\ReportsController@cardapioPdf']);
            }
        );
    }
}