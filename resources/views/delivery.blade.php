<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    @include('delivery.partials.head')
</head>
<body class="paddingTop">
    <header>
        @include('delivery.partials.nav')
    </header>

    <section class="container">
        @include('flash::message')
        @yield('content')
    </section>

    <footer>
        @include('delivery.partials.footer')
        @yield('footer')
    </footer>
{{ setTraffic() }}
</body>
</html>
