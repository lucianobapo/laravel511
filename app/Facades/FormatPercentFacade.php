<?php namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class FormatPercentFacade extends Facade {

    /**
     * @return string
     */
    public static function getFacadeAccessor(){
        return 'formatPercent';
    }
}