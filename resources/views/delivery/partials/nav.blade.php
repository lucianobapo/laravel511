<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        {{--<div class="navbar-header">--}}

            {{--<div class="navbar-brand" style="padding: 0px 15px;">--}}
                {{--{!! $brand !!}--}}
            {{--</div>--}}
            {{--<a class="navbar-brand" href="#">Laravel</a>--}}
            {{--{{ App::getLocale() }}--}}
{{--            {{ Request::server('HTTP_ACCEPT_LANGUAGE') }}--}}
        {{--</div>--}}

        <ul class="nav navbar-nav navbar-right">
            @if (Auth::guest() )
                {{--<li id="willChange">{!! link_to_route('social.login', '+Google', 'google', $host) !!}</li>--}}
                {{--<li id="willChange">{!! link_to('/easyAuth/github', 'Github', $host) !!}</li>--}}
                <li id="willChange">
                    <div style="padding: 8px;">
                        {!! link_to_route_social_button('easy.provider', '<i class="fa fa-facebook"></i>'.trans('delivery.nav.loginFacebook'), ['facebook'], ['class' => 'btn btn-block btn-social btn-facebook']) !!}
                    </div>
                </li>
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

            <li id="tooltipsted" class="text-right">
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
</nav>