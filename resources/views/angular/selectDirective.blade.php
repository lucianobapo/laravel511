<script type="text/javascript">
    app.directive('select2', function() {
        return {
            restrict: 'A',
            link: function(scope, element, attrs) {
                $( element ).ready(function(){
                    element.select2({
                        minimumResultsForSearch: 7,
                        placeholder: attrs.select2,
                        allowClear: true
                    });
                });
            }
        };
    });
    app.directive('select2partner', function() {
        return {
            restrict: 'A',
            link: function(scope, element, attrs) {
                $( element ).ready(function(){
                    element.select2({
                        minimumResultsForSearch: 7,
                        placeholder: attrs.select2partner,
                        templateResult: formatStateAddress,
                        allowClear: true
                    });
                });
            }
        };
    });

    app.directive('productClick', function() {
        return {
            restrict: 'A',
            link: function(scope, element, attrs) {
                $( element ).ready(function(){
                    element.on("select2:select", function (e) {
                        angular.element(document.querySelector('[name=valor_unitario'+attrs.productClick+']')).val(products[element.val()].valor_venda);
                        angular.element(document.querySelector('[name=cost_id'+attrs.productClick+']')).val(products[element.val()].cost_id).trigger("change");
                    });
                });
            }
        };
    });

    app.directive('select2', function() {
        return {
            restrict: 'C',
            link: function(scope, element, attrs) {
                element.select2({
                    minimumResultsForSearch: 6
                });
            }
        };
    });

    app.directive('select2tagStatus', function() {
        return {
            restrict: 'C',
            link: function(scope, element, attrs) {
                element.select2({
                    tags: true
                });
                element.val(1).trigger("change")
            }
        };
    });
    app.directive('select2tag', function() {
        return {
            restrict: 'C',
            link: function(scope, element, attrs) {
                element.select2({
                    tags: true
                });
            }
        };
    });
</script>