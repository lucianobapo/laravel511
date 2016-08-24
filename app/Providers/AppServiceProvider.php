<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
//        \Form::component('customText', 'components.form.text',
//            ['name', 'label' => null, 'value' => null, 'attributes' => []]);
//        \Form::component('customCheckbox', 'components.form.checkbox',
//            ['name', 'label' => null, 'value' => null, 'attributes' => [], 'checked' => false]);
//        \Form::component('customFile', 'components.form.file',
//            ['name', 'label' => null, 'value' => null, 'attributes' => []]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('trans', function ($app, $params) {
//            dd(func_get_args());
            return trans('general.'.$params[0], isset($params[1])?$params[1]:[]);
        });
    }
}
