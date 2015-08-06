{{--<nav class="navbar navbar-inverse navbar-fixed-top">--}}
    {{--<div class="container-fluid">--}}
        {{--<div class="navbar-header">--}}
            {{--<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">--}}
                {{--<span class="sr-only">{{ trans('app.toggleNavigation') }}</span>--}}
                {{--<span class="icon-bar"></span>--}}
                {{--<span class="icon-bar"></span>--}}
                {{--<span class="icon-bar"></span>--}}
            {{--</button>--}}
            {{--<a class="navbar-brand" href="{{ config('delivery.siteCurrentUrl') }}" style="padding: 0px 15px;">--}}
                {{--{!! app('html')->image('/img/logo-ilhanet.png', trans('delivery.nav.logoAlt'), [--}}
                    {{--'title'=>trans('delivery.nav.logoTitle'),--}}
                    {{--'style'=>'max-height: 100%;']) !!}--}}
            {{--</a>--}}
            {{--<a class="navbar-brand" href="#">Laravel</a>--}}
{{--            {{ App::getLocale() }}--}}
{{--            {{ Request::server('HTTP_ACCEPT_LANGUAGE') }}--}}
        {{--</div>--}}

        {{--<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">--}}
            {{--<ul class="nav navbar-nav">--}}
                {{--<li>{!! link_to_route('index', 'Welcome') !!}</li>--}}
                {{--<li>{!! link_to_route('home.index', 'Home') !!}</li>--}}
                {{--<li>{!! link_to_route('contact', 'Contact') !!}</li>--}}
                {{--<li>{!! link_to_route('about', 'About') !!}</li>--}}
                {{--<li>{!! link_to('/') !!}</li>--}}
                {{--<li>{!! link_to_route('articles.index', 'List of Articles') !!}</li>--}}
                {{--<li>{!! link_to_route('articles.create', 'Create an Article') !!}</li>--}}
                {{--<li>{!! link_to_route('sharedCurrencies.index', 'Shared Currencies', $host) !!}</li>--}}

                {{--<li><a href="{{ url('/articles/create') }}">Create an Article</a></li>--}}

                {{--<li>{!! link_to_route('relatorios.index', 'Relatorios') !!}</li>--}}

                {{--<li class="dropdown">--}}
                    {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ trans('order.menu.title') }} <span class="caret"></span></a>--}}
                    {{--<ul class="dropdown-menu" role="menu">--}}
                        {{--<li>{!! link_to_route('orders.create', trans('order.menu.create'), $host) !!}</li>--}}
                        {{--<li>{!! link_to_route('confirmations.index', trans('order.menu.confirmation'), $host) !!}</li>--}}
                        {{--<li><hr></li>--}}
                        {{--<li>{!! link_to_route('orders.index', trans('order.menu.allOrder'), $host) !!}</li>--}}
                        {{--<li>{!! link_to_route('ordersSearch.compras', trans('order.menu.compras'), $host) !!}</li>--}}
                        {{--<li>{!! link_to_route('ordersSearch.vendas', trans('order.menu.vendas'), $host) !!}</li>--}}
                    {{--</ul>--}}
                {{--</li>--}}

                {{--<li>{!! link_to_route('products.index', trans('product.menuProduct'), $host) !!}</li>--}}
                {{--<li>{!! link_to_route('costs.index', trans('cost.menuName'), $host) !!}</li>--}}
                {{--<li>{!! link_to_route('partners.index', trans('partner.menuName'), $host) !!}</li>--}}
                {{--<li>{!! link_to_route('addresses.index', trans('address.menuName'), $host) !!}</li>--}}
                {{--<li>{!! link_to_route('contacts.index', trans('contact.menuName'), $host) !!}</li>--}}
                {{--<li>{!! link_to_route('documents.index', trans('document.menuName'), $host) !!}</li>--}}
                {{--<li class="dropdown">--}}
                    {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ trans('report.menu.topName') }} <span class="caret"></span></a>--}}
                    {{--<ul class="dropdown-menu" role="menu">--}}
                        {{--<li>{!! link_to_route('reports.estoque', trans('report.menu.estoque'), $host) !!}</li>--}}
                        {{--<li>{!! link_to_route('reports.estatOrdem', trans('report.menu.estatOrdem'), $host) !!}</li>--}}
                        {{--<li>{!! link_to_route('reports.dre', trans('report.menu.dre'), $host) !!}</li>--}}
                    {{--</ul>--}}
                {{--</li>--}}
            {{--</ul>--}}

            {{--<ul class="nav navbar-nav navbar-right">--}}
                {{--@if (Auth::guest())--}}
                    {{--<li><a href="{{ url('/auth/login') }}">Login</a></li>--}}
                    {{--<li><a href="{{ url('/auth/register') }}">Register</a></li>--}}
                {{--@else--}}
                    {{--<li class="dropdown">--}}
                        {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>--}}
                        {{--<ul class="dropdown-menu" role="menu">--}}
                            {{--<li><a href="{{ url('/auth/logout') }}">Logout</a></li>--}}
                        {{--</ul>--}}
                    {{--</li>--}}
                {{--@endif--}}
            {{--</ul>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</nav>--}}

<!-- Navigation -->
<nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
                <i class="fa fa-bars"></i>
            </button>
            <a class="navbar-brand page-scroll" href="#page-top">
                <i class="fa fa-play-circle"></i>  <span class="light">Start</span> Bootstrap
            </a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-right navbar-main-collapse">
            <ul class="nav navbar-nav">
                <!-- Hidden li included to remove active class from about link when scrolled up past about section -->
                <li class="hidden">
                    <a href="#page-top"></a>
                </li>
                <li>
                    <a class="page-scroll" href="#about">About</a>
                </li>
                <li>
                    <a class="page-scroll" href="#download">Download</a>
                </li>
                <li>
                    <a class="page-scroll" href="#contact">Contact</a>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>