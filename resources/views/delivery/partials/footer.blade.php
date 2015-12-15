<!-- Scripts -->
<script src="{{ elixir('js/app.compiled.js') }}"></script>
{{--<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>--}}
{{--<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>--}}

{{--<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0-rc.2/js/select2.min.js"></script>--}}
@yield('footerScriptJs')
<script>
    $('div.alert-autohide').delay(3000).slideUp(300);
    $('#flash-overlay-modal').modal();
    $(document).ready(function(){
        $('[data-toggle="popover"]').popover();
    });

//    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
//    ga('create', '".getenv("GOOGLE_ANALYTICS_ID")."', 'auto');
//    ga('require', 'linkid', 'linkid.js');
//    ga('require', 'displayfeatures');
//    ga('send', 'pageview');
</script>

<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', {{ config('delivery.googleAnalyticsId') }}, 'auto');
    ga('require', 'linkid', 'linkid.js');
    ga('require', 'displayfeatures');
    ga('send', 'pageview');
</script>
<script>
//    idleTimer = null;
//    idleState = false;
//    idleWait = 5000;

//    (function ($) {
//
//        $(document).ready(function () {
//
//            $('*').bind('mousemove keydown scroll', function () {
//
//                clearTimeout(idleTimer);
//
//                if (idleState == true) {
//
//                    // Reactivated event
//                    $("body").append("<p>Welcome Back.</p>");
//                }
//
//                idleState = false;
//
//                idleTimer = setTimeout(function () {
//
//                    // Idle Event
//                    $("body").append("<p>You've been idle for " + idleWait/1000 + " seconds.</p>");
//
//                    idleState = true; }, idleWait);
//            });
//
//            $("body").trigger("mousemove");
//
//        });
//    }) (jQuery)
</script>