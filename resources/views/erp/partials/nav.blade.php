<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">{{ trans('app.toggleNavigation') }}</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ config('delivery.siteCurrentUrl') }}" style="padding: 0px 15px;">
                {!! app('html')->image('/img/logo-ilhanet.png', trans('delivery.nav.logoAlt'), [
                    'title'=>trans('delivery.nav.logoTitle'),
                    'style'=>'max-height: 100%;']) !!}
            </a>
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

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ trans('order.menu.title') }} <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li>{!! link_to_route('orders.create', trans('order.menu.create'), isset($host)?$host:null) !!}</li>
                        <li>{!! link_to_route('orders.index', trans('order.menu.allOrder'), isset($host)?$host:null) !!}</li>
                        <li>{!! link_to_route('orders.abertas', trans('order.menu.abertas'), isset($host)?$host:null) !!}</li>
                        <li>{!! link_to_route('orders.compras', trans('order.menu.compras'), isset($host)?$host:null) !!}</li>
                        <li>{!! link_to_route('orders.vendas', trans('order.menu.vendas'), isset($host)?$host:null) !!}</li>
                    </ul>
                </li>

                <li>{!! link_to_route('products.index', trans('product.menuProduct'), isset($host)?$host:null) !!}</li>
                <li>{!! link_to_route('costs.index', trans('cost.menuName'), isset($host)?$host:null) !!}</li>
                <li>{!! link_to_route('partners.index', trans('partner.menuName'), isset($host)?$host:null) !!}</li>
                <li>{!! link_to_route('addresses.index', trans('address.menuName'), isset($host)?$host:null) !!}</li>
                <li>{!! link_to_route('contacts.index', trans('contact.menuName'), isset($host)?$host:null) !!}</li>
                <li>{!! link_to_route('documents.index', trans('document.menuName'), isset($host)?$host:null) !!}</li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ trans('report.menu.topName') }} <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li>{!! link_to_route('reports.estoque', trans('report.menu.estoque'), isset($host)?$host:null) !!}</li>
                        <li>{!! link_to_route('reports.estatOrdem', trans('report.menu.estatOrdem'), isset($host)?$host:null) !!}</li>
                        <li>{!! link_to_route('reports.cardapio', trans('report.menu.cardapio'), isset($host)?$host:null) !!}</li>
                        <li>{!! link_to_route('reports.dre', trans('report.menu.dre'), isset($host)?$host:null) !!}</li>
                        <li>{!! link_to_route('reports.diarioGeral', trans('report.menu.diarioGeral'), isset($host)?$host:null) !!}</li>
                    </ul>
                </li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                @if (Auth::guest())
                    <li><a href="{{ route('auth.getLogin') }}">Login</a></li>
                    <li><a href="{{ route('auth.getRegister') }}">Register</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ route('auth.getLogout') }}">Logout</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>