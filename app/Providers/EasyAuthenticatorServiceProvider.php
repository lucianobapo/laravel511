<?php namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class EasyAuthenticatorServiceProvider extends ServiceProvider {

    protected $defer = false;

    public function boot()
    {
//        $this->loadViewsFrom(__DIR__.'/Views', 'easyAuthenticator');

//        $this->publishes([
//            __DIR__.'/Config/easyAuthenticator.php' => config_path('easyAuthenticator.php'),
//            __DIR__.'/Views' => base_path('resources/views/bernardino/easyAuthenticator'),
//            __DIR__.'/Migrations' => base_path('database/migrations'),
//        ]);

        $this->app->config->set('auth.model', $this->app->config->get('easyAuthenticator.model'));

//        include __DIR__.'/routes.php';

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
//        Route::controllers([
//            'auth' => 'Bernardino\EasyAuthenticator\AuthController',
//            'password' => 'Bernardino\EasyAuthenticator\PasswordController',
//        ]);

//        Route::get(config('easyAuthenticator.login_page'), function() {
//            return view('easyAuthenticator::login');
//        });

        get(config('easyAuthenticator.logout'), ['as'=>'easy.logout', 'uses'=>'Bernardino\EasyAuthenticator\AuthenticatorManager@logout']);
//        Route::get(config('easyAuthenticator.logout'), function() {
//            dd($this->app['authenticator']);
//            return $this->app['authenticator']->logout();
//        });

//        Route::get(config('easyAuthenticator.login_redirect'), function() {
//            $user = User::find(\Auth::id());
//            return view('easyAuthenticator::dashboard')->with('user', $user);
//        });

        get('easyAuth/{provider?}', ['as'=>'easy.provider', 'uses'=>'Bernardino\EasyAuthenticator\AuthenticatorManager@login']);
//        Route::get('easyAuth/{provider?}', function($provider = null) {
//            return $this->app['authenticator']->login($provider);
//        });

//        Route::get('activate/{code}', 'bernardino\EasyAuthenticator\AuthController@accountIsActive');
    }

    public function register()
    {
        $this->app->bind('authenticator', function($app)
        {
            return $app->make('Bernardino\EasyAuthenticator\AuthenticatorManager');
        });
        $this->registerSocialite();
        $this->registerUserModel();
    }

    public function registerSocialite()
    {
        $this->app->register('\Laravel\Socialite\SocialiteServiceProvider');
    }

    public function registerUserModel()
    {
        $this->app->make('Bernardino\EasyAuthenticator\Models\User');
    }

    public function provides()
    {
        return [
            'Bernardino\EasyAuthenticator\AuthenticatorManager',
            '\Laravel\Socialite\SocialiteServiceProvider',
        ];
    }
}