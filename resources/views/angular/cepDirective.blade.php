<script type="text/javascript">
    app.directive('cep', function () {
        return {
            restrict: 'C',
            link: function (scope, element, attrs) {
                element.bind('change', function () {
                    if (element.val().length == (attrs.maxlength)) {
                        if ( (parseInt(element.val())<28890000)||((parseInt(element.val())>28899999)) ){
                            element.tooltip({
                                animation: true,//'fade',
                                title: "{{ trans('app.angular.cepForaFaixa') }}",
                                html:true,
                                trigger: 'manual'//'custom'
                            });
                            element.tooltip('show');
                            element.on('shown.bs.tooltip', function(){
                                setTimeout(function () {
                                    element.tooltip('destroy');
                                }, 2000);
                            });
                        } else {
                            $('input[name=logradouro]').empty();
                            $('input[name=logradouro]').attr("disabled","true");
                            $('input[name=bairro]').empty();
                            $('input[name=bairro]').attr("disabled","true");
                            $('input[name=cidade]').empty();
                            $('input[name=cidade]').attr("disabled","true");
                            $('input[name=estado]').empty();
                            $('input[name=estado]').attr("disabled","true");

                            $('#cep_loading').attr("class","form-control-feedback ng-show");
                            $.get('http://viacep.com.br/ws/'+element.val()+'/json/', function (endereco){
                                if (endereco['erro']) {
                                    element.tooltip({
                                        animation: true,//'fade',
                                        title: "{{ trans('app.angular.cepInvalido') }}",
                                        html:true,
                                        trigger: 'manual'//'custom'
                                    });
                                    element.tooltip('show');
                                    element.on('shown.bs.tooltip', function(){
                                        setTimeout(function () {
                                            element.tooltip('destroy');
                                        }, 2000);
                                    });
                                }else{
                                    $('input[name=logradouro]').val(endereco['logradouro']);
                                    $('input[name=bairro]').val(endereco['bairro']);
                                    $('input[name=cidade]').val(endereco['localidade']);
                                    $('input[name=estado]').val(endereco['uf']);
                                }

                                $('input[name=logradouro]').removeAttr("disabled","true");
                                $('input[name=bairro]').removeAttr("disabled","true");
                                $('input[name=cidade]').removeAttr("disabled","true");
                                $('input[name=estado]').removeAttr("disabled","true");
                                $('#cep_loading').attr("class","form-control-feedback ng-hide");
                            }).fail(function() {
                                $('input[name=logradouro]').removeAttr("disabled","true");
                                $('input[name=bairro]').removeAttr("disabled","true");
                                $('input[name=cidade]').removeAttr("disabled","true");
                                $('input[name=estado]').removeAttr("disabled","true");
                                $('#cep_loading').attr("class","form-control-feedback ng-hide");
                                element.tooltip({
                                    animation: true,//'fade',
                                    title: "{{ trans('app.angular.cepInvalido') }}",
                                    html:true,
                                    trigger: 'manual'//'custom'
                                });
                                element.tooltip('show');
                                element.on('shown.bs.tooltip', function(){
                                    setTimeout(function () {
                                        element.tooltip('destroy');
                                    }, 2000);
                                });
                            });
                        }

                    }
                });
            }
        }
    });
</script>