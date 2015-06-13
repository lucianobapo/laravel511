<?php namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class CurrencyFacade extends Facade {

    /**
     * @return string
     */
    public static function getFacadeAccessor(){
        return 'currency';
    }
}