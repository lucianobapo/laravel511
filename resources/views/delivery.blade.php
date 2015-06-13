<!DOCTYPE html>
<html lang="pt_br">
<head>
    @include('delivery.partials.head')
</head>
<body>
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
