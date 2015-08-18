@extends((isset($usePdf)&&$usePdf)?'erp.appPdf':'erp.app')
@section('content')
    <style>
        table {
            border:0;
            /*border-collapse:separate;*/
            border-spacing:0px;
        }
        @page {
            margin-top: 0px;
            /*margin-left: 0.6em;*/
        }
        /*thead:after {*/
        /*content:'';*/
        /*display:table-row;*/
        /*position:absolute;*/
        /*visibility:hidden;*/
        /*}*/

        /*thead tr:first-child th{*/
            /*border:3px solid!important;*/
        /*}*/
        /*thead tr:first-child th{*/
            /*padding: 0px!important;*/
            /*margin: 0px!important;*/
        /*}*/

        /*table {*/
            /*border-collapse: collapse;*/
        /*}*/

        /*td, th {*/
            /*border: 1px solid #999;*/
            /*padding: 0.5rem;*/
            /*text-align: left;*/
        /*}*/

    </style>
    <h1 class="h1s">{{ trans('report.cardapio.title') }}</h1>
    <h3>{{ trans('report.cardapio.reportTime',['tempo'=>Carbon\Carbon::now()->format('d/m/Y H:i')]) }}</h3>
    <hr>
    <table style="" class="table table-hover table-striped table-condensed" ng-app="myApp">
        <thead style="">
            <tr>
                <th style="border: 0px"></th>
                <th style="border: 0px"></th>
                <th style="border: 0px"></th>
            </tr>
            <tr>
                <th style="border: 0px;">{{ trans('report.cardapio.id') }}</th>
                <th style="border: 0px;">{{ trans('report.cardapio.produto') }}</th>
                <th style="border: 0px;">{{ trans('report.cardapio.valorVenda') }}</th>
            </tr>
        </thead>
        <tbody style="">
            <tr style="background-color: #ffffff;">
                <td style="border: 0px;"></td>
                <td style="border: 0px;"></td>
                <td style="border: 0px;"></td>
            </tr>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->nome }}</td>
                    <td>
                        @if($product->promocao)
                            <del style="color: red;">{{ formatBRL($product->valorUnitVenda) }}</del>
                            {{ formatBRL($product->valorUnitVendaPromocao) }}
                        @else
                            {{ formatBRL($product->valorUnitVenda) }}
                        @endif
                        </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
@section('footerScriptJs')
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.15/angular.min.js"></script>
    <script type="text/javascript">
        var app = angular.module('myApp', []);
        //        app.controller('myCtrl', function($scope) {
        //            //            $scope.firstName= "John";
        //            //            $scope.lastName= "Doe";
        ////            $scope.list = [];
        ////            $scope.text = 'hello';
        //            $scope.submit = function() {
        //                if ($scope.text) {
        //                    $scope.list.push(this.text);
        //                    $scope.text = '';
        //                }
        //            };
        //        });
    </script>
@endsection