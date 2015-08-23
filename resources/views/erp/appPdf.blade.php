<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    @include('erp.partials.headPdf')
</head>
<body>
    <header class="text-right">
{{--        @include('erp.partials.nav')--}}
        <img width="200px" src="http:{{ config('delivery.siteCurrentUrl') }}/img/logo-delivery2.png">
    </header>

    <section class="container">
        @include('flash::message')
        @yield('content')
    </section>

    {{--@include('erp.partials.footer')--}}
</body>
</html>
