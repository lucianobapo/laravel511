<script type="text/javascript">
    app.directive('sendDelete', function($timeout) {
        return {
            restrict: 'A',
            link: function(scope, element, attrs) {
                var idToDelete = attrs.sendDelete;

                element.bind('click', function(e) {
                    e.preventDefault();
//                    console.log("#form"+idToDelete);
                    $("#form"+idToDelete).submit();
                });
            }
        };
    });
</script>