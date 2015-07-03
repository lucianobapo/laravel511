<table class="table table-hover table-striped table-condensed" ng-app="myApp">
    <thead>
    <tr>
        <th>{{ trans('report.estatOrdem.valoresMensais.mes') }}</th>
        <th>{{ trans('report.estatOrdem.valoresMensais.valorVenda') }}</th>
        <th>{{ trans('report.estatOrdem.valoresMensais.valorCompra') }}</th>
        <th>{{ trans('report.estatOrdem.valoresMensais.creditoFinanceiro') }}</th>
        <th>{{ trans('report.estatOrdem.valoresMensais.debitoFinanceiro') }}</th>
        <th>{{ trans('report.estatOrdem.valoresMensais.diferenca') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $key=>$value)
        <tr>
            <td>{{ $key }}</td>
            <td>{{ formatBRL($value['vendas']) }}</td>
            <td>{{ formatBRL($value['compras']) }}</td>
            <td>{{ formatBRL($value['creditoFinanceiro']) }}</td>
            <td style="color: red;">{{ formatBRL($value['debitoFinanceiro']) }}</td>
            @if(($value['vendas']-$value['compras'])>0)
                <td style="color: #0000ff; font-weight: bold">{{ formatBRL($value['vendas']-$value['compras']+$value['creditoFinanceiro']-$value['debitoFinanceiro']) }}</td>
            @else
                <td style="color: red; font-weight: bold">{{ formatBRL($value['vendas']-$value['compras']+$value['creditoFinanceiro']-$value['debitoFinanceiro']) }}</td>
            @endif

        </tr>
    @endforeach
    </tbody>
</table>