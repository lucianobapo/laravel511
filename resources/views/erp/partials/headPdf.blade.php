<meta charset="utf-8">

<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Laravel</title>

{{--<link href="{{ asset('/css/app.compiled.css') }}" rel="stylesheet">--}}
<link href="http:{{ config('delivery.siteCurrentUrl').elixir('css/app.compiled.css') }}" rel="stylesheet">
@yield('headScriptCss')

{{--<link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0-rc.2/css/select2.min.css" rel="stylesheet" />--}}

<!-- Fonts -->
<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
