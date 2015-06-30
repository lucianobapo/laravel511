<?php

if (!function_exists('controller')) {
    /**
     * Route a resource to a controller.
     *
     * @param  string  $name
     * @param  string  $controller
     * @param  array   $options
     * @return void
     */
    function controller($name, $controller, array $options = [])
    {
        return app('router')->controller($name, $controller, $options);
    }
}

if ( ! function_exists('link_to_route_sort_by')){
    function link_to_route_sort_by($route, $column, $body, array $params=array(), array $attributes = array()){
        $params['sortBy']=$column;
        $params['direction']=!$params['direction'];
        return link_to_route($route, $body, $params, $attributes);
    }
}

if ( ! function_exists('labelEx')){
    function labelEx($name, $value = null, $options = array()){
        return sprintf( app('form')->label($name, '%s', $options), $value );
//        return app('form')->label('cep',trans('modelPartner.attributes.cep').'<span style="color:red;">*</span>:');
    }
}

if ( ! function_exists('formatBRL')){
    function formatBRL($valor = 0){
        //if (empty($valor)) return app('currency')->convert(0)->from('BRL')->format();
//        return app('currency')->convert($valor)->from('BRL')->format();
        return app('currency')->format($valor);
    }
}

if ( ! function_exists('formatPercent')){
    function formatPercent($valor = 0){
        return ( app('formatPercent')->format($valor));
    }
}

if ( ! function_exists('array_search_second_level')){
    function array_search_second_level($array, $key, $value){
        $encontrou=false;
        foreach ($array as $sub_array){
            if (isset($sub_array[$key]) && $sub_array[$key]==$value) $encontrou=true;
        }
        return $encontrou;
    }
}

if ( ! function_exists('setTraffic')){
    function setTraffic() {
        Auth::guest()? $user = 'Guest' : $user = Auth::user()->toJson();
        if (config('app.storeTraffic'))
            \App\Models\Traffic::create([
                'user_info' => $user,
                'session_id' => app('session')->getId(),
                'remote_address' => $_SERVER['REMOTE_ADDR'],
                'server_info' => json_encode([
                    $_SERVER['HTTP_HOST'],
                    $_SERVER['HTTP_USER_AGENT'],
                    $_SERVER['HTTP_ACCEPT_LANGUAGE'],
                    $_SERVER['SERVER_NAME'],
                    $_SERVER['SERVER_ADDR'],
                    $_SERVER['SERVER_PORT'],
                    $_SERVER['REMOTE_ADDR'],
                    $_SERVER['REMOTE_PORT'],
                    $_SERVER['REQUEST_SCHEME'],
                    $_SERVER['REQUEST_METHOD'],
                    $_SERVER['QUERY_STRING'],
                    $_SERVER['REQUEST_URI'],
                    $_SERVER['SCRIPT_NAME'],
                    $_SERVER['PHP_SELF'],
                    $_SERVER['REQUEST_TIME_FLOAT'],
                    $_SERVER['REQUEST_TIME'],
                ]),
            ]);

    }
}