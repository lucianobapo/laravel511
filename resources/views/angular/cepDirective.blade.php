<script type="text/javascript">
    app.directive('clickOnce', function($timeout) {
        return {
            restrict: 'A',
            link: function(scope, element, attrs) {
                var replacementText = attrs.clickOnce;

                element.bind('click', function() {
                    window.onbeforeunload = null;
                    $timeout(function() {
                        if (replacementText) {
                            element.html(replacementText);
                            element.val(replacementText);
                        }
                        element.attr('disabled', true);
                    }, 0);
                });
            }
        };
    });
</script>