<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div class="navbar-brand" style="padding: 0px 15px;">
                {!! $brand !!}
            </div>
            {{--<a class="navbar-brand" href="#">Laravel</a>--}}
            {{--{{ App::getLocale() }}--}}
{{--            {{ Request::server('HTTP_ACCEPT_LANGUAGE') }}--}}
        </div>
        <div class="pull-right">
            <ul class="nav navbar-nav navbar-right">
                <li id="tooltipsted">
                    <a href="#"
                       id="cartPopover"
                       data-placement="bottom"
                       data-html="true"
                       data-toggle="popover"
                       title="<div style='display: block; width: 150px;'>{{ trans('delivery.nav.cartHeader') }}</div>"
                       data-content="{{ $cartView }}">
                        <i class="glyphicon glyphicon-shopping-cart"></i>
                        (<strong id="cartTotal">{!! $totalCart?$totalCart:'<em>Vazio</em>' !!}</strong>)
                        <span class="caret"></span>
                    </a>
                </li>
            </ul>
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
                {{--<li>{!! link_to_route('sharedCurrencies.index', 'Shared Currencies') !!}</li>--}}

                {{--<li><a href="{{ url('/articles/create') }}">Create an Article</a></li>--}}

                {{--<li>{!! link_to_route('relatorios.index', 'Relatorios') !!}</li>--}}
            </ul>

            <ul class="nav navbar-nav navbar-right">
                @if (Auth::guest() )
                    {{--<li id="willChange">{!! link_to_route('social.login', '+Google', 'google', $host) !!}</li>--}}
                    {{--<li id="willChange">{!! link_to('/easyAuth/github', 'Github', $host) !!}</li>--}}
                    <li id="willChange">{!! link_to('/easyAuth/facebook', trans('delivery.nav.loginFacebook'), $host) !!}</li>
                    {{--<li id="willChange">{!! link_to('/easyAuth/google', 'Google', $host) !!}</li>--}}
                    {{--<li><a href="{{ url('/auth/login') }}"><i class="glyphicon glyphicon-log-in"></i> {{ trans('delivery.nav.login') }}</a></li>--}}
                    {{--<li><a href="{{ url('/auth/register') }}"><i class="glyphicon glyphicon-user"></i> {{ trans('delivery.nav.register') }}</a></li>--}}
                @else
                    <li class="dropdown">
                        @if(empty(Auth::user()->avatar))
                            <a style="padding: 15px" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>
                        @else
                            <a style="padding: 0px 15px" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <img width="50" class="img-thumbnail" src="{{ Auth::user()->avatar }}">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>
                        @endif

                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/easyLogout') }}">Logout</a></li>
                        </ul>
                    </li>
                @endif

            </ul>
        </div>
    </div>
</nav>