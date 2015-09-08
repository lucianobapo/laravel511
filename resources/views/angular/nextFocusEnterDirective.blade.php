<script type="text/javascript">
    app.directive('nextFocusEnter', function () {
        return {
            // atribuímos em forma de classe css nesse caso
            restrict: 'C',
            link: function (scope, element, attrs) {
                // register jQuery extension
                jQuery.extend(jQuery.expr[':'], {
                    focusable: function (el, index, selector) {
                        return $(el).is('a, button, :input, [tabindex]');
                    }
                });
                element.keypress(function (e) {
                    if (e.which == 13) {
                        e.preventDefault();
                        // Get all focusable elements on the page
                        var $canfocus = $(':focusable');
                        var index = $canfocus.index(this) + 1;
                        if (index >= $canfocus.length) index = 0;
                        $canfocus.eq(index).focus();
                    };
                });
            }
        }
    });
</script>