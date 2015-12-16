<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    @include('delivery.partials.head')
</head>
<body class="paddingTop">
    <header>
        @include('delivery.partials.nav')
    </header>

    <section class="container-fluid">
        @yield('contentWide')
    </section>
    <section class="container">
        @include('flash::message')
    </section>
    <section class="container">
        <div class="alert alert-info text-center" style="margin-top: 20px;">
            <h4>Aceitamos cartões débito e crédito:
                <i class="fa fa-cc-visa fa-lg"></i>
                <i class="fa fa-cc-mastercard fa-lg"></i></h4>

        </div>
        @yield('content')
    </section>

    <footer>
        @include('delivery.partials.footer')
        @yield('footer')
    </footer>
{{ setTraffic() }}
</body>
</html>
