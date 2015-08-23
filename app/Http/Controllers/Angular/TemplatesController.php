<?php
/**
 * Created by PhpStorm.
 * User: luciano
 * Date: 22/08/15
 * Time: 15:41
 */

namespace app\Http\Controllers\Angular;

use App\Http\Controllers\Controller;

class TemplatesController extends Controller {
    public function login(){
        return view('angular.templates.login');
    }
    public function productsCardapio(){
        return view('angular.templates.productsCardapio');
    }
}