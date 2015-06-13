<footer>
    @yield('footer')
</footer>

<!-- Scripts -->
<script src="{{ elixir('js/app.compiled.js') }}"></script>
{{--<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>--}}
{{--<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>--}}
{{--<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.15/angular.min.js"></script>--}}
{{--<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0-rc.2/js/select2.min.js"></script>--}}
<script>
    $('div.alert-autohide').delay(3000).slideUp(300);
    $('#flash-overlay-modal').modal();
</script>
@yield('footerScriptJs')