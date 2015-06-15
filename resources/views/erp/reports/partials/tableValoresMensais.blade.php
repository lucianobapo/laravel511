<table class="table table-hover table-condensed" ng-app="myApp">
    <thead>
    <tr>
        <th>{{ trans('report.estatOrdem.valoresMensais.mes') }}</th>
        <th>{{ trans('report.estatOrdem.valoresMensais.valorVenda') }}</th>
        <th>{{ trans('report.estatOrdem.valoresMensais.valorCompra') }}</th>
        <th>{{ trans('report.estatOrdem.valoresMensais.diferenca') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $key=>$value)
        <tr>
            <td>{{ $key }}</td>
            <td>{{ formatBRL($value['vendas']) }}</td>
            <td>{{ formatBRL($value['compras']) }}</td>
            @if(($value['vendas']-$value['compras'])>0)
                <td style="color: #0000ff; font-weight: bold">{{ formatBRL($value['vendas']-$value['compras']) }}</td>
            @else
                <td style="color: red; font-weight: bold">{{ formatBRL($value['vendas']-$value['compras']) }}</td>
            @endif

        </tr>
    @endforeach
    </tbody>
</table>