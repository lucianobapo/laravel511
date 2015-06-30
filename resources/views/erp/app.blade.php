<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    @include('erp.partials.head')
</head>
<body>
    <header>
        @include('erp.partials.nav')
    </header>

    <section class="container">
        @include('flash::message')
        @yield('content')
    </section>

    @include('erp.partials.footer')
</body>
</html>
