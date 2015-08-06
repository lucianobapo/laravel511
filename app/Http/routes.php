<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//App::before(function($request)
//{
//    $headers=array('Cache-Control'=>'no-cache, no-store, max-age=0, must-revalidate','Pragma'=>'no-cache','Expires'=>'Fri, 01 Jan 1990 00:00:00 GMT');
//
//    View::share('headers', $headers);
//});

Route::controller('welcome', 'WelcomeController');

//Route::get('/', function () { return 'foi'; });

//Route::get('/welcome', function () {
//    return view('welcome');
//});

//get('/gallery', ['as'=>'gallery.index', 'uses'=>'GalleryController@index']);

RoutesRepository::erpRoutes();
RoutesRepository::deliveryRoutes();
RoutesRepository::galleryRoutes();