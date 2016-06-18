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

Route::controller('welcome', 'WelcomeController');

//Route::get('/welcome', function () {
//    return view('welcome');
//});
if (config('app.debug'))
    Route::get('/phpinfo', function () {
        return phpinfo();
    });

get('/cron/{minites}', ['as'=>'cron', 'uses'=>'CronController@cron']);

RoutesRepository::oAuth2Routes();
RoutesRepository::erpRoutes();
RoutesRepository::deliveryRoutes();