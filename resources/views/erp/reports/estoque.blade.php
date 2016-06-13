@extends('erp.app')
@section('content')
    <h1 class="h1s">{{ trans('report.estoque.title') }}</h1>
    <hr>
    <table class="table table-hover table-striped table-condensed" ng-app="myApp">
        <thead>
            <tr>
                <th>{{ trans('report.estoque.id') }}</th>
                <th>{{ trans('report.estoque.produto') }}</th>
                <th>{{ trans('report.estoque.compras') }}</th>
                <th>{{ trans('report.estoque.vendas') }}</th>
                <th>{{ trans('report.estoque.estoque') }}</th>
                <th>{{ trans('report.estoque.estoqueMinimo') }}</th>
                <th>{{ trans('report.estoque.custoMedioUnitario') }}</th>
                <th>{{ trans('report.estoque.custoMedioSubTotal') }}</th>
                <th>{{ trans('report.estoque.valorVenda') }}</th>
                <th>{{ trans('report.estoque.valorVendaSubTotal') }}</th>
                <th>{{ trans('report.estoque.margem') }}</th>
            </tr>
        </thead>
        <tbody>
            {{--<tr>--}}
                {{--<td colspan="7" class="text-right"><strong>{{ trans('report.estoque.total') }}</strong></td>--}}
                {{--<td colspan="2">{{ formatBRL(isset($custoTotal)?$custoTotal:0) }}</td>--}}
                {{--<td colspan="2">{{ formatBRL(isset($valorVendaTotal)?$valorVendaTotal:0) }}</td>--}}
            {{--</tr>--}}
            <?php $somaCusto=0; ?>
            @foreach($products as $product)
                @if(isset($estoque[$product->id])&&($product->estoque_minimo>0)&&($estoque[$product->id]<$product->estoque_minimo))
                    <tr style="font-weight: bold;color: red">
                @else
                    <tr>
                @endif

                    <td>{{ $product->id }}</td>
                    <td>{{ $product->nome }}</td>
                    <td>{{ isset($compras[$product->id])?$compras[$product->id]:0 }}</td>
                    <td>{{ isset($vendas[$product->id])?$vendas[$product->id]:0 }}</td>
                    <td>{{ isset($estoque[$product->id])?$estoque[$product->id]:0 }}</td>
                    <td>{{ $product->estoque_minimo }}</td>
                    <td>{{ formatBRL(isset($custoMedioEstoque[$product->id])?$custoMedioEstoque[$product->id]:0) }}</td>
                        @if(isset($estoque[$product->id]) && isset($custoMedioEstoque[$product->id]))
                            <td>{{ formatBRL($estoque[$product->id]*$custoMedioEstoque[$product->id]) }}</td>
                            <?php $somaCusto=$somaCusto+$estoque[$product->id]*$custoMedioEstoque[$product->id]; ?>
                        @else
                            <td>{{ formatBRL(0) }}</td>
                        @endif
{{--                    <td>{{ formatBRL(isset($estoque[$product->id])&&isset($custoMedioEstoque[$product->id])?$estoque[$product->id]*$custoMedioEstoque[$product->id]:0) }}</td>--}}
                    <td>{{ formatBRL($product->valorUnitVenda) }}</td>
                    <td>{{ formatBRL(isset($estoque[$product->id])?$estoque[$product->id]*$product->valorUnitVenda:0) }}</td>
                    <td>{{ formatPercent(1-(isset($estoque[$product->id])&&isset($custoMedioEstoque[$product->id])?( $custoMedioEstoque[$product->id] / $product->valorUnitVenda ):0)) }}</td>
                </tr>
            @endforeach

            <tr>
                <td colspan="7" class="text-right"><strong>{{ trans('report.estoque.total') }}</strong></td>
                <td colspan="2">{{ formatBRL($somaCusto) }}</td>
                <td colspan="2">{{ formatBRL(isset($valorVendaTotal)?$valorVendaTotal:0) }}</td>
            </tr>
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