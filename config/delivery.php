<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

//    'debug' => env('APP_DEBUG'),

    // Other stuffs
    'storeTraffic' => env('APP_STORE_TRAFFIC', false),
//    'orderItemCountMax' => env('ORDER_ITEM_COUNT_MAX', 3),
    'defaultMandante' => env('MANDANTE', 'teste'),
//    'deliveryOpen' => env('DELIVERY_OPEN', true),
//    'deliveryReturn' => env('DELIVERY_RETURN', 'none'),
    'orderListCountMax' => env('ORDER_LIST_COUNT_MAX', 3),
    'rootRole' => env('ROOT_ROLE', 'Root'),

    'facebookMetaTags' => env('FACEBOOK_META_TAGS', false),

    'document_types' => [
        'cpf' => "CPF",
        'cnpj' => "CNPJ",
        'ie' => "IE",
        'im' => "IM",
        'rg' => "RG",
    ],
    'contact_types' => [
        'email' => "E-mail",
        'telefone' => "Telefone",
        'whatsapp' => "WhatsApp",
    ],

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

//    'domain' => env('APP_DOMAIN','homestead.app'),
//    'sitePrefix' => 'http://',
    'siteCurrentUrl' => 'http://'.(isset($_SERVER["HTTP_HOST"])?$_SERVER["HTTP_HOST"]:env('APP_DOMAIN','homestead.app')),


];
