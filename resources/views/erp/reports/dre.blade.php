@extends((isset($usePdf)&&$usePdf)?'erp.appPdf':'erp.app')
@section('content')
    <h1 class="h1s">{{ trans('report.dre.title') }}</h1>
    <hr>
    <table class="table table-hover table-striped table-condensed" ng-app="myApp">
        <thead>
            <tr>
                <th style="border: 0px"></th>
                @foreach($periodos as $periodo)
                    <th style="border: 0px"></th>
                @endforeach
            </tr>
            <tr>
                <th style="border: 0px">{{ trans('report.dre.estrutura') }}</th>
                @foreach($periodos as $periodo)
                    <th style="border: 0px">{{ $periodo['title'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="border: 0px"></td>
                @foreach($periodos as $periodo)
                    <td style="border: 0px"></td>
                @endforeach
            </tr>
            <tr>
                <td style="border-top: 2px solid #dddddd;">{{ trans('report.dre.receitaBruta') }}</td>
                @foreach($periodos as $periodo)
                    <td style="border-top: 2px solid #dddddd;">{{ formatBRL($periodo['ordersMes']['receitaBruta']) }}</td>
                @endforeach
            </tr>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;{{ trans('report.dre.receitaBrutaDinheiro') }}</td>
                @foreach($periodos as $periodo)
                    <td>{{ formatBRL($periodo['ordersMes']['receitaBrutaDinheiro']) }}</td>
                @endforeach
            </tr>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;{{ trans('report.dre.receitaBrutaCartaoDebito') }}</td>
                @foreach($periodos as $periodo)
                    <td>{{ formatBRL($periodo['ordersMes']['receitaBrutaCartaoDebito']) }}</td>
                @endforeach
            </tr>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;{{ trans('report.dre.receitaBrutaCartaoCredito') }}</td>
                @foreach($periodos as $periodo)
                    <td>{{ formatBRL($periodo['ordersMes']['receitaBrutaCartaoCredito']) }}</td>
                @endforeach
            </tr>

            <tr>
                <td>{{ trans('report.dre.deducaoReceita') }}</td>
                @foreach($periodos as $periodo)
                    <td>{{ formatBRL($periodo['ordersMes']['deducaoReceita']) }}</td>
                @endforeach
            </tr>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;{{ trans('report.dre.imposto') }}</td>
                @foreach($periodos as $periodo)
                    <td>{{ formatBRL($periodo['ordersMes']['imposto']) }}</td>
                @endforeach
            </tr>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;{{ trans('report.dre.honorariosPayleven') }}</td>
                @foreach($periodos as $periodo)
                    <td>{{ formatBRL($periodo['ordersMes']['honorariosPayleven']) }}</td>
                @endforeach
            </tr>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ trans('report.dre.honorariosPaylevenDebito') }}</td>
                @foreach($periodos as $periodo)
                    <td>{{ formatBRL($periodo['ordersMes']['honorariosPaylevenDebito']) }}</td>
                @endforeach
            </tr>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ trans('report.dre.honorariosPaylevenCredito') }}</td>
                @foreach($periodos as $periodo)
                    <td>{{ formatBRL($periodo['ordersMes']['honorariosPaylevenCredito']) }}</td>
                @endforeach
            </tr>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;{{ trans('report.dre.honorariosPedidosJa') }}</td>
                @foreach($periodos as $periodo)
                    <td>{{ formatBRL($periodo['ordersMes']['honorariosPedidosJa']) }}</td>
                @endforeach
            </tr>

            <tr>
                <td>{{ trans('report.dre.receitaLiquida') }}</td>
                @foreach($periodos as $periodo)
                    <td>{{ formatBRL($periodo['ordersMes']['receitaLiquida']) }}</td>
                @endforeach
            </tr>
            <tr>
                <td>{{ trans('report.dre.custoProdutos') }}</td>
                @foreach($periodos as $periodo)
                    <td>{{ formatBRL($periodo['ordersMes']['custoProdutos']) }}</td>
                @endforeach
            </tr>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;{{ trans('report.dre.custoMercadorias') }}</td>
                @foreach($periodos as $periodo)
                    <td>{{ formatBRL($periodo['ordersMes']['custoMercadorias']) }}</td>
                @endforeach
            </tr>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;{{ trans('report.dre.custoLanches') }}</td>
                @foreach($periodos as $periodo)
                    <td>{{ formatBRL($periodo['ordersMes']['custoLanches']) }}</td>
                @endforeach
            </tr>
            <tr>
                <td>{{ trans('report.dre.margem') }}</td>
                @foreach($periodos as $periodo)
                    <td>{{ formatBRL($periodo['ordersMes']['margem']) }}</td>
                @endforeach
            </tr>
            <tr>
                <td>{{ trans('report.dre.despesas') }}</td>
                @foreach($periodos as $periodo)
                    <td>{{ formatBRL($periodo['ordersMes']['despesas']) }}</td>
                @endforeach
            </tr>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;{{ trans('report.dre.despesasGerais') }}</td>
                @foreach($periodos as $periodo)
                    <td>{{ formatBRL($periodo['ordersMes']['despesasGerais']) }}</td>
                @endforeach
            </tr>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;{{ trans('report.dre.despesasTransporte') }}</td>
                @foreach($periodos as $periodo)
                    <td>{{ formatBRL($periodo['ordersMes']['despesasTransporte']) }}</td>
                @endforeach
            </tr>
            <tr>
                <td style="font-style: italic">{{ trans('report.dre.ebitda') }}</td>
                @foreach($periodos as $periodo)
                    @if(($ebitda = $periodo['ordersMes']['ebitda'])>0)
                        <td style="font-style: italic; color: #0000ff">{{ formatBRL($ebitda) }}</td>
                    @else
                        <td style="font-style: italic; color: red">{{ formatBRL($ebitda) }}</td>
                    @endif

                @endforeach
            </tr>
            <tr>
                <td>{{ trans('report.dre.depreciacao') }}</td>
                @foreach($periodos as $periodo)
                    <td>{{ formatBRL(0) }}</td>
                @endforeach
            </tr>
            <tr>
                <td>{{ trans('report.dre.lucroAntes') }}</td>
                @foreach($periodos as $periodo)
                    <td>{{ formatBRL(0) }}</td>
                @endforeach
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