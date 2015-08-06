<meta charset="utf-8">

<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Laravel</title>

{{--<link href="{{ asset('/css/app.compiled.css') }}" rel="stylesheet">--}}
<link href="{{ config('delivery.siteCurrentUrl').elixir('css/app.compiled.css') }}" rel="stylesheet">
<link href="{{ config('delivery.siteCurrentUrl').elixir('css/gallery.compiled.css') }}" rel="stylesheet">
@yield('headScriptCss')

{{--<link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0-rc.2/css/select2.min.css" rel="stylesheet" />--}}

<!-- Fonts -->
<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

<!-- Custom Fonts -->
{{--<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">--}}
<link href="http://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic" rel="stylesheet" type="text/css">
<link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->