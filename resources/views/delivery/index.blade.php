@extends('delivery')

{{--@section('headScriptCss')--}}
    {{--<meta name="_token" content="{!! csrf_token() !!}"/>--}}
{{--@endsection--}}

@section('content')
    <div class="container-fluid">
        <div class="row text-center">
            {!! Html::image('/img/logo.png', trans('delivery.nav.logoAlt'), [
            'title'=>trans('delivery.nav.logoTitle'),
            'class'=>'img-responsive container-fluid']) !!}
            {{--<h1 class="h2s">{{ trans('delivery.index.subTitle') }}</h1>--}}

            <h1 class="h1s">{{ trans('delivery.index.title') }}</h1>
            <h2 class="h2s">{{ trans('delivery.index.subTitle') }}</h2>
        </div>
        <div class="row">
            @if(!config('app.deliveryOpen'))
                <div class="alert alert-warning">
                    <strong>{{ trans('delivery.deliveryFechado.errorTitle') }}</strong>
                    {{ trans('delivery.deliveryFechado.errorText', ['retorno' => config('app.deliveryReturn')]) }}
                </div>
            @endif

            <?php $panelBlockTitle = $panelTitle ?>
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <h3 class="h3s">{{ $panelBlockTitle }}</h3>
                </div>
                <div class="panel-body">
                    @if (Session::get('session'))
                        <div class="alert alert-warning">
                            <strong>{{ trans('delivery.index.errorTitle') }}</strong> {{ trans('delivery.index.errorText') }}<br><br>
                            <ul>
                                <li>{{ trans('delivery.index.'.str_slug(Session::get('session'))) }}</li>
                            </ul>
                        </div>
                    @endif
                    <div class="text-right" id="btnPedido" style="margin: 10px 0px;">
                        @if(Session::has('cart'))
                            {!! link_to_route('delivery.pedido', trans('delivery.nav.cartBtn'), $host, ['class'=>'btn btn-success tooltipsted2']) !!}
                        @endif
                    </div>
                    {!! $panelBody !!}
                    <div class="text-right" id="btnPedido2" style="margin: 10px 0px;">
                        @if(Session::has('cart'))
                            {!! link_to_route('delivery.pedido', trans('delivery.nav.cartBtn'), $host, ['class'=>'btn btn-success']) !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footerScriptJs')
    {{--<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.15/angular.min.js"></script>--}}

    {{--<script type="text/javascript">--}}
        {{--var app = angular.module('myApp', []);--}}
        {{--app.controller('myCtrl', function($scope) {--}}
{{--//            $scope.firstName= "John";--}}
{{--//            $scope.lastName= "Doe";--}}
        {{--});--}}
    {{--</script>--}}

    {{--@include('angular.globalDirectives')--}}


    <script type="text/javascript">
        jQuery( document ).ready( function( $ ) {
            $( '#form-add-setting' ).on( 'submit', function() {
                //.....
                //show some spinner etc to indicate operation in progress
                //.....


                // get all the inputs into an array.
                var $inputs = $('#form-add-setting :input.pass');
                // not sure if you wanted this, but I thought I'd add it.
                // get an associative array of just the values.
                var values = {"_token": $( this ).find( 'input[name=_token]' ).val()};
                $inputs.each(function() {
                    values[this.name] = $(this).val();
                });
//                console.log(values);
//                console.log($( this ).prop( 'action' ));
//                console.log($( this ).prop( 'action' ));
                $.post(
                        $( this ).prop( 'action' ), values,
                        function( data ) {

                            //do something with data/response returned by server
                            var foiAlterado = false;
                            $('#form-add-setting :input.tooltiped').each (function(){
                                if ($(this).val()>0) foiAlterado = true;
                            });
                            if (foiAlterado) {
                                $('#cartPopover').popover("hide");
                                $('#cartPopover').attr('data-content', data.view);
                                $( '#cartTotal' ).html(data.total);
                                $( '#btnPedido' ).html(data.btnPedido);
                                $( '#btnPedido2' ).html(data.btnPedido);

                                $('#tooltipsted').tooltip({
                                    animation: true,
                                    placement: 'left',
                                    title: "{{ trans('delivery.productBlock.tooltip') }}",
                                    trigger: 'manual'
                                });
                                $('#tooltipsted').tooltip('show');
                                $('#tooltipsted').on('shown.bs.tooltip', function(){setTimeout(function () {$('#tooltipsted').tooltip('destroy');}, 1500);});

                                $('.tooltipsted2').tooltip({
                                    animation: true,
                                    placement: 'left',
                                    title: "{{ trans('delivery.productBlock.tooltip') }}",
                                    trigger: 'manual'
                                });
                                $('.tooltipsted2').tooltip('show');
                                $('.tooltipsted2').on('shown.bs.tooltip', function(){setTimeout(function () {$('.tooltipsted2').tooltip('destroy');}, 1500);});

                                //resetting the form
                                $('#form-add-setting').each (function(){
                                    this.reset();
                                });
                            }
                        },
                        'json'
                );

                //.....
                //do anything else you might want to do
                //.....

                //prevent the form from actually submitting in browser
                return false;
            } );
        } );
    </script>
@endsection