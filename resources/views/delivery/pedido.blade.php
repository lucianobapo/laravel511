@extends('delivery')

@section('contentWide')
    <div class="row text-center" style="background:darkgray url('/img/capa2.png') no-repeat center center;background-size:cover;">
        {{--{!! Html::image('/img/logo-delivery3.png', trans('delivery.nav.logoAlt'), [--}}
        {{--'title'=>trans('delivery.nav.logoTitle'),--}}
        {{--'class'=>'img-responsive container-fluid']) !!}--}}
        {{--<h1 class="h2s">{{ trans('delivery.index.subTitle') }}</h1>--}}

        <h1 class="h1s" style="color: #ffffff;">{{ trans('delivery.index.title') }}</h1>
        <h2 class="h2s" style="color: #ffffff;">{{ trans('delivery.pedidos.subTitle') }}</h2>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <h3 class="h3s">{{ $panelTitle }}</h3>

                </div>
                <div class="panel-body">
                    {!! $panelBody !!}
                </div>
            </div>
        </div>
        {!! $panelGuest !!}
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <h3 class="h3s">{{ trans('delivery.pedidos.panelEntregaTitle') }}</h3>
                </div>
                <div class="panel-body">
                    {{--@include('delivery.partials.pedidoForm')--}}
                    {!! $panelFormBody !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footerScriptJs')
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.15/angular.min.js"></script>

    <script type="text/javascript">
        var app = angular.module('myApp', []);
        app.controller('myCtrl', function($scope) {
        //            $scope.firstName= "John";
        //            $scope.lastName= "Doe";
        });
    </script>

    @include('angular.clickOnceDirective')
    @include('angular.numbersOnlyDirective')
    @include('angular.cepDirective')
@endsection