<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            {{--<a class="navbar-brand" href="#">Laravel</a>--}}
{{--            {{ App::getLocale() }}--}}
{{--            {{ Request::server('HTTP_ACCEPT_LANGUAGE') }}--}}
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
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
                <li>{!! link_to_route('orders.index', trans('order.menuOrder'), $host) !!}</li>
                <li>{!! link_to_route('products.index', trans('product.menuProduct'), $host) !!}</li>
                <li>{!! link_to_route('partners.index', trans('partner.menuName'), $host) !!}</li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                @if (Auth::guest())
                    <li><a href="{{ url('/auth/login') }}">Login</a></li>
                    <li><a href="{{ url('/auth/register') }}">Register</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/auth/logout') }}">Logout</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>