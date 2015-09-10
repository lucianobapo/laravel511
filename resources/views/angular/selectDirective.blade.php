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
    app.directive('select2endereco', function() {
        return {
            restrict: 'A',
            link: function(scope, element, attrs) {
                $( element ).ready(function(){
                    element.select2({
                        minimumResultsForSearch: 7,
                        placeholder: attrs.select2endereco,
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
                        templateResult: function (state) {
                            if (!state.id) { return state.text; }
                            var $state = $(
                                    '<span>' + state.text + '<br> - {{ trans('modelPartner.getPartnerList') }}: '+partners[state.element.value].address+'</span>'
                            );
                            return $state;
                        },
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

    app.directive('partnerClick', function() {
        return {
            restrict: 'C',
            link: function(scope, element, attrs) {
                $( element ).ready(function(){
                    element.on("select2:select", function (e) {
                        angular.element(document.querySelector('[name=address_id]')).select2({ data: partners[element.val()].address_arr });
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