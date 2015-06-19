<meta charset="utf-8">

<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="{{ trans('delivery.head.metaDescription') }}">
<meta name="robots" content="{{ trans('delivery.head.metaRobots') }}">
<meta name="author" content="{{ trans('delivery.head.metaAuthor') }}">
<meta name="google-site-verification" content="B0qADrueaz2VqkVySrBDlWHtmkjyXI6heuELzkEmmZ0" />
<title>{{ trans('delivery.head.title') }}</title>

{{--<link href="{{ asset('/css/app.compiled.css') }}" rel="stylesheet">--}}
<link href="{{ elixir('css/app.compiled.css') }}" rel="stylesheet">
@yield('headScriptCss')

{{--<link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0-rc.2/css/select2.min.css" rel="stylesheet" />--}}

<!-- Fonts -->
{{--<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>--}}
<link href='http://fonts.googleapis.com/css?family=Architects+Daughter' rel='stylesheet' type='text/css'>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->