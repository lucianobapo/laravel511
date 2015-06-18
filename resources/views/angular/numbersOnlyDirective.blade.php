<script type="text/javascript">
    app.directive('numbersOnly', function () {
        return {
            // atribuímos em forma de classe css nesse caso
            restrict: 'C',
            link: function (scope, element, attrs) {
                // atribuímos o plugin jQuery ao parâmetro `element`
                // nesse caso, o element do DOM que foi bindado a diretiva
                element.keypress(function (e) {
                    //if the letter is not digit then display error and don't type anything
                    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                        //$(element).tooltipster('content', myNewContent);
                        $(element).tooltip({
                            animation: true,//'fade',
                            title: "{{ trans('app.angular.numbersOnly') }}",
                            html:true,
                            //delay: 200,
                            //theme: 'tooltipster-default',
                            //touchDevices: false,
                            trigger: 'manual'//'custom'
                        });
                        //$(element).tooltipster('content',"");
                        $(element).tooltip('show');
                        $(element).on('shown.bs.tooltip', function(){setTimeout(function () {$(element).tooltip('destroy');}, 2000);});
                        //setTimeout(function () {$(element).tooltip('hide');}, 2000);
                        return false;
                    };
                    if (this.value.length > (attrs.maxlength-1)) return false;
                });
            }
        }
    });
</script>